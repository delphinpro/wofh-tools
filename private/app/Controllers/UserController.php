<?php

namespace WofhTools\Controllers;


use Firebase\JWT\JWT;
use Slim\Http\Request;
use Slim\Http\Response;
use WofhTools\Core\User;
use WofhTools\Core\Forms\Form;
use WofhTools\Core\Forms\Field;
use WofhTools\Helpers\Password;
use WofhTools\Core\Forms\Assert;
use WofhTools\Core\BaseController;


/**
 * Class UserController
 *
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   copyright © 2019 delphinpro
 * @license     licensed under the MIT license
 * @package     WofhTools\Controllers
 */
final class UserController extends BaseController
{
    public function dispatch(Request $request, Response $response, $args)
    {
        return $this->sendRequest($request, $response);
    }


    public function doLogin(Request $request, Response $response, $args)
    {
        $payload = [
            'token' => '',
            'form'  => [],
            'post'  => $request->getParsedBody(),
        ];
        $status = true;
        $message = '';

        $this->bootEloquent();
        $form = new Form($request);

        $form->addField(new Field('username'))
            ->assert(Assert::notEmpty('Это обязательное поле'));

        $form->addField(new Field('password'))
            ->assert(Assert::notEmpty('Это обязательное поле'));

        $form->validate();

        $users = null;
        if ($form->isValid()) {

            $username = $form->getField('username')->getValue();
            $password = $form->getField('password')->getValue();

            /** @var User $user */
            $user = User::where('username', '=', $username)
                ->orWhere('email', '=', $username)
                ->first();

            $payload['user'] = $user;

            if ($user) {

                if (Password::verify($password, $user->password)) {
                    if (Password::heedRehash($user->password, PASSWORD_DEFAULT)) {
                        $user->password = Password::hash($password, PASSWORD_DEFAULT);
                        $user->save();
                    }

                    $payload['token'] = $this->makeJWT($user);
                }

            } else {

                $status = false;
                $message = 'Incorrect username or password';

            }
        }

        return $this->sendRequest($request, $response, $payload, $status, $message);
    }


    /**
     * @param \WofhTools\Core\User $user
     *
     * @return string
     */
    private function makeJWT(User $user): string
    {
        $createdTime = time();

        $SECRET_KEY = env('JWT_SECRET_KEY');
        $token = [
            'userId' => $user->id,
            'uuid'   => GUID(),
            'iat'    => $createdTime,
            'exp'    => $createdTime + 60 * 1,
        ];

        return JWT::encode($token, $SECRET_KEY);
    }

}
