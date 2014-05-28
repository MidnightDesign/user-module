<?php

namespace Midnight\UserModule\Form;

use Zend\InputFilter\InputFilter;

class SetPasswordInputFilter extends InputFilter
{
    function __construct()
    {
        $this->add(
            array(
                'name' => 'password1',
            )
        );
        $this->add(
            array(
                'name' => 'password2',
                'validators' => array(
                    array(
                        'name' => 'identical',
                        'options' => array('token' => 'password1'),
                    )
                ),
            )
        );
    }
}
