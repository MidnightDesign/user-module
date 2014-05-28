<?php

namespace Midnight\UserModule\Form;

use Zend\Form\Form;
use Zend\Stdlib\Hydrator\Reflection;

class LoginForm extends Form
{
    public function __construct()
    {
        parent::__construct();

        $this->setHydrator(new Reflection());

        $this->add(array(
            'name' => 'email',
            'type' => 'Email',
            'options' => array('label' => 'Email'),
        ));

        $this->add(array(
            'name' => 'password',
            'type' => 'Password',
            'options' => array('label' => 'Password'),
        ));

        $this->add(array(
            'name' => 'next',
            'type' => 'Hidden',
        ));

        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array('value' => 'Log in'),
        ));
    }
} 
