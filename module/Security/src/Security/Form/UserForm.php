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
                'label' => 'Nombres:'
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
                'label' => 'Apellidos:'
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
                'label' => 'Usuario:'
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
                'label' => 'Contraseña:'
                )
            ));

         $this->add(array(
            'name' => 'password_repeat',
            'attributes' => array(
                'type' => 'password',
                'id' => 'password_repeat',
                'maxlength' => 20,
                'class' => 'form-control',
                ),
            'options' => array(
                'label' => 'Contraseña:'
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
                'label' => 'Correo electrónico:'
                )
            ));

        $this->add(array(
            'type' => 'Select',
            'name' => 'roles',
            'options' => array(
                'label' => 'Roles:',
                'value_options' => $roles,
                'empty_option' => "Seleccione",
                'disable_inarray_validator' => true
                ),
            'attributes' => array(
                'id' => 'roles',
                'class' => 'form-control',
                )

            ));

        $this->add(array(
            'type' => 'Select',
            'name' => 'status',
            'options' => array(
                'label' => 'Parametros reservados:',
                'value_options' => array(1 => "activo",0 => "inactivo"),
                'disable_inarray_validator' => true
                ),
            'attributes' => array(
                'id' => 'status',
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