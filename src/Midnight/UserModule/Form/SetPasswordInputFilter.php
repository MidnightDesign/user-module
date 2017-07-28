<?php

namespace Midnight\UserModule\Form;

use Zend\InputFilter\InputFilter;

class SetPasswordInputFilter extends InputFilter
{
    public function __construct()
    {
        $this->add(
            [
                'name' => 'password1',
            ]
        );
        $this->add(
            [
                'name' => 'password2',
                'validators' => [
                    [
                        'name' => 'identical',
                        'options' => ['token' => 'password1'],
                    ],
                ],
            ]
        );
    }
}
