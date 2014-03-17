<?php
namespace Settings\Form;

use Zend\Form\Form;
use Zend\Form\Element\Select;
use Zend\Form\Element\Checkbox;
use Zend\Form\Element;

class UserShortCutForm extends Form
{
   public function __construct($modules,$users)
   {
      parent::__construct('user_shortcut');
      $this->setAttribute('method', 'post');
      $this->setAttribute('class', 'form-horizontal');
      $this->setAttribute('enctype','multipart/form-data');

      $this->add(array(
         'type' => 'select',
         'name' => 'modules',
         'attributes' => array(
            'id' => 'modules',
            'multiple' => 'multiple',
            'class' => 'form-control',
            ),
         'options' => array(
            'label' => 'modules',
            'value_options' => $modules,
            'disable_inarray_validator' => true,
            'empty_option' => 'seleccione una opción',
            ),
         ));

      $this->add(array(
         'type' => 'Select',
         'name' => 'user',
         'options' => array(
            'label' => 'Usuarios:',
            'value_options' => $users,
            'disable_inarray_validator' => true,
            'empty_option' => 'seleccione una opción',
            ),
         'attributes' => array(
            'id' => 'user',
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