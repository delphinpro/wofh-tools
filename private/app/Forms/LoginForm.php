<?php

namespace WofhTools\Forms;


use WofhTools\Core;


/**
 * Class LoginForm
 *
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   copyright © 2015–2019 delphinpro
 * @license     licensed under the MIT license
 * @package     WofhTools\Forms
 */
class LoginForm extends Core\Forms\Form
{
    protected function setFields()
    {
        $this->addField(new Core\Forms\Field('email'))
            ->assert(Core\Forms\Assert::notEmpty())
            ->assert(new Core\Asserts\IsEmail());

        $this->addField(new Core\Forms\Field('password'))
            ->assert(new Core\Asserts\IsNotEmpty());
    }
}
