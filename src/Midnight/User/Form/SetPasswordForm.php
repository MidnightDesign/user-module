<?php

namespace Midnight\User\Form;

use Zend\Form\Form;

class SetPasswordForm extends Form
{
    public function __construct()
    {
        parent::__construct();

        $this->setInputFilter(new SetPasswordInputFilter());

        $this->add(
            [
                'name' => 'password1',
                'type' => 'password',
                'options' => [
                    'label' => 'Neues Passwort',
                ],
            ]
        );

        $this->add(
            [
                'name' => 'password2',
                'type' => 'password',
                'options' => [
                    'label' => 'Passwort wiederholen',
                ],
            ]
        );

        $this->add(
            [
                'name' => 'submit',
                'type' => 'submit',
                'attributes' => [
                    'value' => 'Speichern',
                ],
            ]
        );
    }
}
