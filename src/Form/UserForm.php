<?php

namespace Midnight\UserModule\Form;

use Zend\Form\Form;

class UserForm extends Form
{
    public function __construct()
    {
        parent::__construct();

        $this->add(array(
            'type' => 'Email',
            'name' => 'email',
            'options' => array(
                'label' => 'Email',
            ),
        ));

        $this->add(array(
            'type' => 'Password',
            'name' => 'password',
            'options' => array(
                'label' => 'Password',
            ),
        ));

        $this->add(array(
            'type' => 'Checkbox',
            'name' => 'is_admin',
            'options' => array(
                'label' => 'Admin',
            ),
        ));

        $this->add(array(
            'type' => 'Submit',
            'name' => 'submit',
            'attributes' => array(
                'value' => 'Create user',
            ),
        ));
    }

} 
