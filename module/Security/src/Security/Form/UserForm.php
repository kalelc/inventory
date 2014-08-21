<?php
namespace Security\Form;

use Zend\Form\Form;
use Zend\Form\Element\Select;
use Zend\Form\Element;
use Tools\Model\DocumentTypeTable;

class UserForm extends Form
{
    private $adapter;

    public function __construct($roles)
    {
        parent::__construct('user');
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'form-horizontal');
        $this->setAttribute('enctype','multipart/form-data');

        $this->add(array(
            'name' => 'first_name',
            'attributes' => array(
                'type' => 'text',
                'id' => 'first_name',
                'maxlength' => 60,
                'class' => 'form-control',
                ),
            'options' => array(
                'label' => 'first_name',
                'label_attributes' => array(
                    'class'  => 'col-sm-2 control-label'
                    ),  
                )
            ));

        $this->add(array(
            'name' => 'last_name',
            'attributes' => array(
                'type' => 'text',
                'id' => 'last_name',
                'maxlength' => 60,
                'class' => 'form-control',
                ),
            'options' => array(
                'label' => 'last_name',
                'label_attributes' => array(
                    'class'  => 'col-sm-2 control-label'
                    ),  
                )
            ));
        $this->add(array(
            'name' => 'username',
            'attributes' => array(
                'type' => 'text',
                'id' => 'username',
                'maxlength' => 60,
                'class' => 'form-control',
                ),
            'options' => array(
                'label' => 'username',
                'label_attributes' => array(
                    'class'  => 'col-sm-2 control-label'
                    ),  
                )
            ));

        $this->add(array(
            'name' => 'password',
            'attributes' => array(
                'type' => 'password',
                'id' => 'password',
                'maxlength' => 20,
                'class' => 'form-control',
                ),
            'options' => array(
                'label' => 'password',
                'label_attributes' => array(
                    'class'  => 'col-sm-2 control-label'
                    ),  
                )
            ));

        $this->add(array(
            'name' => 'email',
            'attributes' => array(
                'type' => 'text',
                'id' => 'email',
                'maxlength' => 100,
                'class' => 'form-control',
                ),
            'options' => array(
                'label' => 'email',
                'label_attributes' => array(
                    'class'  => 'col-sm-2 control-label'
                    ),  
                )
            ));

        $this->add(array(
            'name' => 'picture',
            'attributes' => array(
                'type'  => 'file',
                ),
            'options' => array(
                'label' => 'picture',
                'label_attributes' => array(
                    'class'  => 'col-sm-2 control-label'
                    ),  
                ),
            )); 

        $this->add(array(
            'name' => 'signature',
            'attributes' => array(
                'type'  => 'file',
                ),
            'options' => array(
                'label' => 'signature',
                'label_attributes' => array(
                    'class'  => 'col-sm-2 control-label'
                    ),  
                ),
            )); 

        $this->add(array(
            'type' => 'Select',
            'name' => 'rol',
            'options' => array(
                'label' => 'rol',
                'label_attributes' => array(
                    'class'  => 'col-sm-2 control-label'
                    ),  
                'value_options' => $roles,
                'empty_option' => "Seleccione",
                'disable_inarray_validator' => true
                ),
            'attributes' => array(
                'id' => 'rol',
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