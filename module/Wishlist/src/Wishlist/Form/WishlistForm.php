<?php
namespace Wishlist\Form;

use Zend\Form\Form;

class WishlistForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('brand');
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));

        $this->add(array(
            'name' => 'user',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));

        $this->add(array(
            'name' => 'description',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Description',
            ),
        ));

        $this->add(array(
            'name' => 'cost',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Cost',
            ),
        ));

        $this->add(array(
            'name' => 'storeOrWebsite',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Store or Website',
            ),
        ));

        $this->add(array(
            'name' => 'additionalInformation',
            'attributes' => array(
                'type'  => 'textarea',
            ),
            'options' => array(
                'label' => 'Additional Information',
            ),
        ));

        $this->add(array(
            'name' => 'taken',
            'attributes' => array(
                'type'  => 'hidden',
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