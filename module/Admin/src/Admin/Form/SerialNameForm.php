<?php
namespace Admin\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class SerialNameForm extends Form
{
	public function __construct($name = null)
	{
		parent::__construct('serial_name');
		$this->setAttribute('method', 'post');
		$this->setAttribute('class', 'form-horizontal');

		$this->add(array(
			'name' => 'id',
			'attributes' => array(
				'type'  => 'hidden',
				),
			));
		
		$this->add(array(
			'name' => 'name',
			'attributes' => array(
				'type'  => 'text',
				'class' => 'form-control',
				),
			'options' => array(
				'label' => 'nombre',
				'label_attributes' => array(
					'class'  => 'col-sm-2 control-label'
					),						
				),
			));
		
		$this->add(array(
			'name' => 'description',
			'attributes' => array(
				'type'  => 'textarea',
				'class' => 'form-control',
				),
			'options' => array(
				'label' => 'descripción',
				'label_attributes' => array(
					'class'  => 'col-sm-2 control-label'
					),						
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
				'class' => 'btn btn-primary btn-sm'
				),
			));
	}
}