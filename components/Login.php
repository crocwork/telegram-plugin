<?php namespace Croqo\Telegram\Components;

use Cms\Classes\ComponentBase;

/**
 * Login Component
 */
class Login extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Login widget',
            'description' => 'Adds a "login with telegram" button'
        ];
    }

    public function defineProperties()
    {
        return [
            "name" => [
                'title'             => "croqo.telegram::lang.components.login.name.title",
                'description'       => "croqo.telegram::lang.components.login.name.desc",
                'type'              => 'string',
            ],
            "size" => [
                'title'             => "croqo.telegram::lang.components.login.size.title",
                'description'       => "croqo.telegram::lang.components.login.size.desc",
                'type'              => 'dropdown',
                'options'           => ['large' => 'Large','medium' => 'Medium', 'small' => 'Small']
            ],
            "rad" => [
                'title'             => "croqo.telegram::lang.components.login.rad.title",
                'description'       => "croqo.telegram::lang.components.login.rad.desc",
                'type'              => 'string',
                'default'           => 10,
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'The property can contain only numeric symbols'
            ],
            "url" => [
                'title'             => "croqo.telegram::lang.components.login.url.title",
                'description'       => "croqo.telegram::lang.components.login.url.desc",
                'type'              => 'string',
            ]
        ];
    }
}
