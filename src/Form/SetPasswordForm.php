<?php

namespace Midnight\UserModule\Form;

use Midnight\User\Entity\UserInterface;
use Zend\Form\Form;

class SetPasswordForm extends Form
{
    public function __construct()
    {
        parent::__construct();

        $this->add(array(
            'type' => 'Password',
            'name' => 'password',
            'options' => array(
                'label' => 'New password',
            ),
        ));

        $this->add(array(
            'type' => 'Submit',
            'name' => 'submit',
            'attributes' => array(
                'value' => 'Change password',
            ),
        ));
    }
} 
