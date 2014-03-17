<?php
namespace Settings\Form;

use Zend\Form\Form;
use Zend\Form\Element\Select;
use Zend\Form\Element\Checkbox;
use Zend\Form\Element;

class UserShortCutForm extends Form
{

    private $adapter;

    public function __construct($modules,$users)
    {
        parent::__construct('user_shortcut');
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'form-horizontal');
        $this->setAttribute('enctype','multipart/form-data');

        $this->add(array(
            'type' => 'Checkbox',
            'name' => 'modules',
            'options' => array(
                'label' => 'Modulos:',
                'value_options' => $modules,
                'unchecked_value' => '0',
                'disable_inarray_validator' => true
                ),
            'attributes' => array(
                'id' => 'modules',
                )
            ));

        $this->add(array(
            'type' => 'Select',
            'name' => 'users',
            'options' => array(
                'label' => 'Usuarios:',
                'value_options' => $users,
                'unchecked_value' => '0',
                'disable_inarray_validator' => true
                ),
            'attributes' => array(
                'id' => 'modules',
                'class' => 'form-control',
                )
            ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Csrf',
            'name' => 'csrf',
            'options' => array(
                'csrf_options' => array(
                    'timeout' => 600
                    )
                )
            ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Go',
                'id' => 'submitbutton',
                'class' => 'btn btn-primary btn-sm btn-sm'
                )
            ));
    }

}