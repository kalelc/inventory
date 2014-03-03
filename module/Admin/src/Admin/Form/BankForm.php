<?php
namespace Admin\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class BankForm extends Form
{
	public function __construct($name = null)
	{
		parent::__construct('bank');
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
						'label' => 'name',						
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
						'class' => 'btn btn-primary btn-sm btn-sm'
				),
		));
	}
}