<?php
namespace Util\Form;

use Zend\Form\Form;

class FamilyForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('brand');
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
        $this->add(array(
            'name' => 'lastName',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Family Name',
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