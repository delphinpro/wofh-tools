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
        return $this->sendRequest($request, $response, [
            'user' => 123,
        ]);
    }


    public function profile(Request $request, Response $response, $args)
    {
        $token = $request->getAttribute('token');

        $user = User::find($token['userId']);

        $userData = [
            'id'        => $user->id,
            'email'     => $user->email,
            'username'  => $user->username,
            'createdAt' => $user->created_at->timestamp,
            'updatedAt' => $user->updated_at->timestamp,
            'sex'       => $user->sex,
            'status'    => $user->status,
            'lang'      => $user->lang,
            'avatar'    => $user->avatar,
            'verified'  => $user->verified,
        ];

        $this->push('user', $userData);

        return $this->sendRequest($request, $response);
    }


    public function doLogin(Request $request, Response $response, $args)
    {
        $this->push('token', null);

        $status = true;
        $message = '';

        $form = new Form($request);

        $form->addField(new Field('username'))
            ->assert(Assert::notEmpty('Это обязательное поле'));

        $form->addField(new Field('password'))
            ->assert(Assert::notEmpty('Это обязательное поле'));

        $form->validate();
        $this->push('validation', $form->toArray());

        if ($form->isValid()) {

            $username = $form->getField('username')->getValue();
            $password = $form->getField('password')->getValue();

            /** @var User $user */
            $user = User::where('username', '=', $username)
                ->orWhere('email', '=', $username)
                ->first();

            if ($user) {

                if (Password::verify($password, $user->password)) {
                    if (Password::heedRehash($user->password, PASSWORD_DEFAULT)) {
                        $user->password = Password::hash($password, PASSWORD_DEFAULT);
                        $user->save();
                    }

                    $this->push('token', $this->makeJWT($user));
                    $message = 'You are logged in.';
                }

            } else {

                $status = false;
                $message = 'Incorrect username or password';

            }
        }

        return $this->sendRequest($request, $response, [], $status, $message);
    }


    public function doSave(Request $request, Response $response, $args)
    {
        $this->bootEloquent();

        $token = $request->getAttribute('token');

        $user = User::find($token['userId']);
        $post = $request->getParsedBody();

        if (!empty($post['password'])) {
            if ($post['password'] !== $post['password2']) {
                return $this->sendRequest($request, $response, [], false, 'Пароли не совпали');
            }

            $user->password = Password::hash($post['password'], PASSWORD_DEFAULT);
        }

        if ($user->id === 1) {
            $user->email = $post['email'];
        }


        $user->username = $post['username'];
        $user->sex = $post['sex'];

        $user->save();

        $message = 'Profile updated.';

        return $this->sendRequest($request, $response, [], true, $message);
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
            'exp'    => $createdTime + 60 * env('JWT_LIFETIME', 15),
        ];

        return JWT::encode($token, $SECRET_KEY);
    }

}
