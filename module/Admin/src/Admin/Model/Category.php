<?php
namespace Admin\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Category implements InputFilterAwareInterface
{
	private $id;
	private $masterCategory;
	private $singularName;
	private $pluralName;
	private $image;
	private $shippingCost;
	private $additionalShipping;
	private $description;

	private $masterCategoryName;

	protected $inputFilter;

	public function exchangeArray($data)
	{
		if (array_key_exists('id', $data)) $this->setId($data['id']);
		if (array_key_exists('master_category', $data)) $this->setMasterCategory($data['master_category']);
		if (array_key_exists('master_category_name', $data)) $this->setMasterCategoryName($data['master_category_name']);
		if (array_key_exists('singular_name', $data)) $this->setSingularName($data['singular_name']);
		if (array_key_exists('plural_name', $data)) $this->setPluralName($data['plural_name']);
		if (array_key_exists('image', $data)) $this->setImage($data['image']);
		if (array_key_exists('shipping_cost', $data)) $this->setShippingCost($data['shipping_cost']);
		if (array_key_exists('additional_shipping', $data)) $this->setAdditionalShipping($data['additional_shipping']);
		if (array_key_exists('description', $data)) $this->setDescription($data['description']);
	}

	public function getArrayCopy()
	{
		return get_object_vars($this);
	}

	public function setInputFilter(InputFilterInterface $inputFilter)
	{
		throw new \Exception("Not used");
	}

	public function getInputFilter()
	{
		if (!$this->inputFilter) {
			$inputFilter = new InputFilter();
			$factory     = new InputFactory();

			$inputFilter->add($factory->createInput(array(
					'name'     => 'id',
					'required' => true,
					'filters'  => array(
							array('name' => 'Int'),
					),
			)));

			$inputFilter->add($factory->createInput(array(
					'name'     => 'master_category',
					'required' => true,
					'validators' => array(
							array(
									'name'    => 'NotEmpty',
									'options' => array(
											'messages' => array(
													\Zend\Validator\NotEmpty::IS_EMPTY => 'el campo no debe estar vacio'
											),
									),
							),
					),
			)));

			$inputFilter->add($factory->createInput(array(
					'name'     => 'serial_name',
					'required' => true,
					'validators' => array(
							array(
									'name'    => 'NotEmpty',
									'options' => array(
											'messages' => array(
													\Zend\Validator\NotEmpty::IS_EMPTY => 'el campo no debe estar vacio'
											),
									),
							),
					),
			)));

			$inputFilter->add($factory->createInput(array(
					'name'     => 'specification',
					'required' => true,
					'validators' => array(
							array(
									'name'    => 'NotEmpty',
									'options' => array(
											'messages' => array(
													\Zend\Validator\NotEmpty::IS_EMPTY => 'debe seleccionar una especificación como minimo'
											),
									),
							),
					),
			)));

			$inputFilter->add($factory->createInput(array(
					'name'     => 'singular_name',
					'required' => true,
					'filters'  => array(
							array('name' => 'StripTags'),
							array('name' => 'StringTrim'),
					),
					'validators' => array(
							array(
									'name'    => 'NotEmpty',
									'options' => array(
											'messages' => array(
													\Zend\Validator\NotEmpty::IS_EMPTY => 'el campo no debe estar vacio'
											),
									),
							),
					),
			)));

			$inputFilter->add($factory->createInput(array(
					'name'     => 'plural_name',
					'required' => true,
					'filters'  => array(
							array('name' => 'StripTags'),
							array('name' => 'StringTrim'),
					),
					'validators' => array(
							array(
									'name'    => 'NotEmpty',
									'options' => array(
											'messages' => array(
													\Zend\Validator\NotEmpty::IS_EMPTY => 'el campo no debe estar vacio'
											),
									),
							),
					),
			)));
				

			$inputFilter->add($factory->createInput(array(
					'name'     => 'image',
					'required' => false,
					'validators' => array(
							array(
									'name'    => 'NotEmpty',
									'options' => array(
											'messages' => array(
													\Zend\Validator\NotEmpty::IS_EMPTY => 'Debe seleccionar una imagen'
											),
									),
							),
					),
			))
			);


			$inputFilter->add($factory->createInput(array(
					'name'     => 'shipping_cost',
					'required' => true,
					'filters'  => array(
							array('name' => 'StripTags'),
							array('name' => 'StringTrim'),
					),
					'validators' => array(
							array(
									'name'    => 'Digits',
									'options' => array(
											'messages' => array(
													\Zend\Validator\Digits::NOT_DIGITS => 'Debe ingresar un valor numerico',
													\Zend\Validator\Digits::STRING_EMPTY => 'Debe ingresar un valor numerico'
											),
									),
							),
							array(
									'name'    => 'NotEmpty',
									'options' => array(
											'messages' => array(
													\Zend\Validator\NotEmpty::IS_EMPTY => ''
											),
									),
							),
					),
			)));

			$inputFilter->add($factory->createInput(array(
					'name'     => 'additional_shipping',
					'required' => true,
					
					'validators' => array(
							array(
									'name'    => 'Digits',
									'options' => array(
											'messages' => array(
													\Zend\Validator\Digits::NOT_DIGITS => 'Debe ingresar un valor numerico',
													\Zend\Validator\Digits::STRING_EMPTY => 'Debe ingresar un valor numerico'
											),
									),
							),
							array(
									'name'    => 'NotEmpty',
									'options' => array(
											'messages' => array(
													\Zend\Validator\NotEmpty::IS_EMPTY => ''
											),
									),
							),
					),
			)));


			$inputFilter->add($factory->createInput(array(
					'name'     => 'description',
					'required' => false,
					'filters'  => array(
							array('name' => 'StripTags'),
							array('name' => 'StringTrim'),
					),
			)));
				

			$this->inputFilter = $inputFilter;
		}

		return $this->inputFilter;
	}

	public function getId() 
	{ 
		return $this->id; 
	} 
	public function getMasterCategory() 
	{ 
		return $this->masterCategory; 
	} 
	public function getMasterCategoryName() 
	{ 
		return $this->masterCategoryName; 
	}

	public function getSingularName() 
	{ 
		return $this->singularName; 
	} 
	public function getPluralName() 
	{ 
		return $this->pluralName; 
	} 
	public function getImage() 
	{ 
		return $this->image;
	} 
	public function getShippingCost() 
	{ 
		return $this->shippingCost; 
	} 
	public function getAdditionalShipping() 
	{ 
		return $this->additionalShipping; 
	} 
	public function getDescription() 
	{ 
		return $this->description;
	} 
	public function setId($id) 
	{ 
		$this->id = $id; 
		return $this ; 
	} 
	public function setMasterCategory($masterCategory) 
	{ 
		$this->masterCategory = $masterCategory; 
		return $this ; 
	} 

	public function setMasterCategoryName($masterCategoryName)
	{
		$this->masterCategoryName = $masterCategoryName;
		return $this;
	}

	public function setSingularName($singularName) 
	{ 
		$this->singularName = $singularName;  
		return $this ; 
	} 
	public function setPluralName($pluralName) 
	{ 
		$this->pluralName = $pluralName;  
		return $this ; 
	} 
	public function setImage($image) 
	{ 
		$this->image = $image;  
		return $this ; 
	} 
	public function setShippingCost($shippingCost) 
	{ 
		$this->shippingCost = $shippingCost;  
		return $this ; 
	} 
	public function setAdditionalShipping($additionalShipping) 
	{ 
		$this->additionalShipping = $additionalShipping;  
		return $this ; 
	} 
	public function setDescription($description) 
	{ 
		$this->description = $description;  
		return $this ; 
	} 
}
