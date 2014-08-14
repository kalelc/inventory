<?php
namespace Admin\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class NoteForm extends Form
{
	public function __construct()
	{
		parent::__construct('note');
		
		$this->setAttribute('method', 'post');
		$this->setAttribute('class', 'form-horizontal');
		$this->setAttribute('enctype','multipart/form-data');

		$this->add(array(
			'name' => 'id',
			'attributes' => array(
				'type'  => 'hidden',
				),
			));

		$this->add(array(
			'name' => 'title',
			'attributes' => array(
				'id'  => 'title',
				'type'  => 'text',
				'class' => 'form-control',
				),
			'options' => array(
				'label' => 'title',
				'label_attributes' => array(
					'class'  => 'col-sm-2 control-label'
					),

				),
			));

		$this->add(array(
			'name' => 'content',
			'attributes' => array(
				'id'  => 'content',
				'type'  => 'textarea',
				'class' => 'form-control',
				),
			'options' => array(
				'label' => 'content',
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
			'name' => 'button',
			'attributes' => array(
				'type'  => 'button',
				'class' => 'btn btn-primary btn-sm'
				),
			));
	}
}