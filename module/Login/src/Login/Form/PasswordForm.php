<?php
namespace Login\Form;

use Zend\Form\Form;

class PasswordForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('login');
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'username',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
        $this->add(array(
            'name' => 'password',
            'attributes' => array(
                'type'  => 'password',
            ),
            'options' => array(
                'label' => 'Current Password',
            ),
        ));
        $this->add(array(
            'name' => 'npassword',
            'attributes' => array(
                'type'  => 'password',
            ),
            'options' => array(
                'label' => 'New Password',
            ),
        ));
        $this->add(array(
            'name' => 'ncpassword',
            'attributes' => array(
                'type'  => 'password',
            ),
            'options' => array(
                'label' => 'New Password Confirm',
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Go',
                'id' => 'submitbutton',
            ),
        ));
    }
}