<?php
namespace Admin\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class CustomerForm extends Form
{
	protected $citiesList;

	public function __construct($citiesList)
	{
		parent::__construct('customer');
		$this->setAttribute('method', 'post');
		$this->setAttribute('class', 'form-horizontal');

		$this->citiesList = $citiesList;

		$this->add(array(
			'name' => 'id',
			'attributes' => array(
				'type'  => 'hidden',
				),
			));

		$this->add(array(
			'name' => 'identification',
			'attributes' => array(
				'type'  => 'text',
				'class' => 'form-control',
				),
			'options' => array(
				'label' => 'identification',
				'label_attributes' => array(
					'class'  => 'col-sm-2 control-label'
					),
				),
			));

		$this->add(array(
			'type' => 'select',
			'name' => 'identification_type',
			'options' => array(
				'label' => 'identification_type',
				'label_attributes' => array(
					'class'  => 'col-sm-2 control-label'
					),
				'value_options' => array(1 => "Cedula de Ciudadania",2 => "Nit",3 => "Cedula de Extranjeria",4 => "Pasaporte",5 => "Otro"),
				'empty_option' => "",
				'disable_inarray_validator' => true,
				),
			'attributes' => array(
				'class' => 'form-control',
				)

			));

		$this->add(array(
			'name' => 'first_name',
			'attributes' => array(
				'type'  => 'text',
				'class' => 'form-control',
				),
			'options' => array(
				'label' => 'first_name',
				'label_attributes' => array(
					'class'  => 'col-sm-2 control-label'
					),
				),
			));

		$this->add(array(
			'name' => 'last_name',
			'attributes' => array(
				'type'  => 'text',
				'class' => 'form-control',
				),
			'options' => array(
				'label' => 'last_name',
				'label_attributes' => array(
					'class'  => 'col-sm-2 control-label'
					),
				),
			));
		
		$this->add(array(
			'name' => 'zipcode',
			'attributes' => array(
				'type'  => 'text',
				'class' => 'form-control',
				),
			'options' => array(
				'label' => 'zipcode',
				'label_attributes' => array(
					'class'  => 'col-sm-2 control-label'
					),
				),
			));

		$this->add(array(
			'name' => 'company',
			'attributes' => array(
				'type'  => 'text',
				'class' => 'form-control',
				),
			'options' => array(
				'label' => 'company',
				'label_attributes' => array(
					'class'  => 'col-sm-2 control-label'
					),
				),
			));

		$this->add(array(
			'name' => 'manager',
			'attributes' => array(
				'type'  => 'text',
				'class' => 'form-control',
				),
			'options' => array(
				'label' => 'manager',
				'label_attributes' => array(
					'class'  => 'col-sm-2 control-label'
					),
				),
			));
		
		$this->add(array(
			'name' => 'webpage',
			'attributes' => array(
				'type'  => 'text',
				'class' => 'form-control',
				),
			'options' => array(
				'label' => 'webpage',
				'label_attributes' => array(
					'class'  => 'col-sm-2 control-label'
					),
				),
			));

		$this->add(array(
			'name' => 'birthday',
			'attributes' => array(
				'id' => 'birthday',
				'type'  => 'text',
				'class' => 'form-control',
				'data-date-format' => "mm/dd/yyyy",
				),
			'options' => array(
				'label' => 'birthday',
				'label_attributes' => array(
					'class'  => 'col-sm-2 control-label'
					),
				),
			));

		$this->add(array(
			'name' => 'alias',
			'attributes' => array(
				'type'  => 'text',
				'class' => 'form-control',
				),
			'options' => array(
				'label' => 'alias',
				'label_attributes' => array(
					'class'  => 'col-sm-2 control-label'
					),
				),
			));

		$this->add(array(
			'type' => 'select',
			'name' => 'city',
			'options' => array(
				'label' => 'city',
				'label_attributes' => array(
					'class'  => 'col-sm-2 control-label'
					),
				'value_options' => $this->getCitiesList(),
				'empty_option' => '',
				'disable_inarray_validator' => true,
				),
			'attributes' => array(
				'class' => 'form-control',
				)
			));

		$this->add(array(
			'name' => 'description',
			'attributes' => array(
				'type'  => 'textarea',
				'class' => 'form-control',
				'rows', '5'
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
				'class' => 'btn btn-primary btn-sm btn-sm'
				),
			));
	}

	public function getCitiesList()
	{
		return $this->citiesList;
	}

	public function setCitiesList($citiesList)
	{
		$this->citiesList = $citiesList;
		return $this;
	}
}