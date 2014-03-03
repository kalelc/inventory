<?php
namespace Security\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class LoginForm extends Form
{
	public function __construct()
	{
		parent::__construct('login');
		$this->setAttribute('method', 'post');
		$this->setAttribute('class', 'form-horizontal');
		$this->setAttribute('enctype','multipart/form-data');

		$this->add(array(
			'name' => 'username',
			'attributes' => array(
				'type'  => 'text',
				'class' => 'form-control',
				'placeholder' => 'nombre de usuario'
				),
			'options' => array(
				'label' => 'Nombre de Usuario',
				),
			));

		$this->add(array(
			'name' => 'password',
			'attributes' => array(
				'type'  => 'password',
				'class' => 'form-control',
				'placeholder' => 'Contraseña'
				),
			'options' => array(
				'label' => 'contraseña',
				),
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
				'type'  => 'submit',
				'value' => 'Ingresar',
				'class' => 'btn btn-primary btn-sm btn-sm'
				),
			));
	}
}