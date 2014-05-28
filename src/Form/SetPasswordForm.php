<?php

namespace Midnight\UserModule\Form;

use Zend\Form\Form;
use Zend\Stdlib\PriorityQueue;

class SetPasswordForm extends Form
{
    public function __construct()
    {
        parent::__construct();

        $this->setInputFilter(new SetPasswordInputFilter());

        $this->add(
            array(
                'name' => 'password1',
                'type' => 'password',
                'options' => array(
                    'label' => 'Neues Passwort',
                ),
            )
        );

        $this->add(
            array(
                'name' => 'password2',
                'type' => 'password',
                'options' => array(
                    'label' => 'Passwort wiederholen',
                ),
            )
        );

        $this->add(
            array(
                'name' => 'submit',
                'type' => 'submit',
                'attributes' => array(
                    'value' => 'Speichern',
                ),
            )
        );
    }
}
