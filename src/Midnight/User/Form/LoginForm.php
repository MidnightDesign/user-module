<?php

namespace Midnight\User\Form;

use Zend\Form\Form;
use Zend\Stdlib\PriorityQueue;

class LoginForm extends Form
{
    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);

        $this->add(
            array(
                'name' => 'email',
                'type' => 'email',
                'options' => array(
                    'label' => 'E-Mail'
                ),
            )
        );

        $this->add(
            array(
                'name' => 'password',
                'type' => 'password',
                'options' => array(
                    'label' => 'Passwort'
                ),
            )
        );

        $this->add(
            array(
                'name' => 'submit',
                'type' => 'submit',
                'attributes' => array(
                    'value' => 'Login'
                ),
            )
        );
    }
}