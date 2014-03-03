<?php
namespace Admin\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\Form\Element\Select as select;
use Zend\Form\Element\MultiCheckbox;

class CategoryForm extends Form
{
	protected $masterCategoryList;
	protected $serialNameList;
	protected $specificationList;

	public function __construct($masterCategoryList,$serialNameList,$specificationList)
	{
		parent::__construct('category');

		$this->setAttribute('method', 'post');
		$this->setAttribute('class', 'form-horizontal');
		$this->setAttribute('enctype','multipart/form-data');
		
		$this->masterCategoryList = $masterCategoryList;
		$this->serialNameList = $serialNameList;
		$this->specificationList = $specificationList;
		
		$this->add(array(
				'name' => 'id',
				'attributes' => array(
						'type'  => 'hidden',
				),
		));

		$this->add(array(
				'type' => 'select',
				'name' => 'master_category',
				'options' => array(
						'label' => 'master_category',
						'value_options' => $this->masterCategoryList,
						'empty_option' => 'seleccione una opción',
						'disable_inarray_validator' => true,
				),
				'attributes' => array(
						'class' => 'form-control',
				)
		));
		
		$this->add(array(
				'name' => 'singular_name',
				'attributes' => array(
						'type'  => 'text',
						'class' => 'form-control',
				),
				'options' => array(
						'label' => 'singular_name',

				),
		));

		$this->add(array(
				'name' => 'plural_name',
				'attributes' => array(
						'type'  => 'text',
						'class' => 'form-control',
				),
				'options' => array(
						'label' => 'plural_name',

				),
		));
		
		$this->add(array(
            'name' => 'image',
            'attributes' => array(
                'type'  => 'file',
            ),
            'options' => array(
                'label' => 'Imagen',
            ),
        )); 

        $this->add(array(
				'name' => 'shipping_cost',
				'attributes' => array(
						'type'  => 'text',
						'class' => 'form-control number-format',
				),
				'options' => array(
						'label' => 'plural_name',

				),
		));

		$this->add(array(
				'name' => 'additional_shipping',
				'attributes' => array(
						'type'  => 'text',
						'class' => 'form-control number-format',
				),
				'options' => array(
						'label' => 'plural_name',

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
				'type' => 'select',
				'name' => 'serial_name',
				'attributes' => array(
                'multiple' => 'multiple',
                'class' => 'form-control',
            	),
				'options' => array(
						'label' => 'serial_name',
						'value_options' => $this->serialNameList,
						'empty_option' => 'seleccione una opción',
				),
		));

		$this->add(array(
				'type' => 'MultiCheckbox',
				'name' => 'specification',
				'options' => array(
						'label' => 'Especificaciones',
						'value_options' => $this->specificationList,
				),
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