<?php

namespace Midnight\User\Form;

use Zend\Form\Form;

class CreateUserForm extends Form
{
    public function __construct($name = null, $options = [])
    {
        parent::__construct($name, $options);

        $this->add(
            [
                'name' => 'email',
                'type' => 'email',
                'options' => [
                    'label' => 'E-Mail',
                ],
            ]
        );

        $this->add(
            [
                'name' => 'password',
                'type' => 'password',
                'options' => [
                    'label' => 'Passwort',
                ],
            ]
        );

        $this->add(
            [
                'name' => 'submit',
                'type' => 'submit',
                'attributes' => [
                    'value' => 'Benutzer erstellen',
                ],
            ]
        );
    }
}
