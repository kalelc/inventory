<?php
namespace Admin\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Specification implements InputFilterAwareInterface
{
	private $id;
	private $name;
	private $specificationMaster;
	private $image;
	private $meaning;
	private $generalInformation;
	private $specificationMasterName;
	protected $inputFilter;

	public function exchangeArray($data)
	{
		if (array_key_exists('id', $data)) $this->setId($data['id']);
		if (array_key_exists('name', $data)) $this->setName($data['name']);
		if (array_key_exists('specification_master', $data)) $this->setSpecificationMaster($data['specification_master']);
		if (array_key_exists('sm_name', $data)) $this->setSpecificationMasterName($data['sm_name']);
		if (array_key_exists('image', $data)) $this->setImage($data['image']);
		if (array_key_exists('meaning', $data)) $this->setMeaning($data['meaning']);
		if (array_key_exists('general_information', $data)) $this->setGeneralInformation($data['general_information']);
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
					'name'     => 'name',
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
					'name'     => 'specification_master',
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
					'name'     => 'meaning',
					'required' => false,
					'filters'  => array(
							array('name' => 'StripTags'),
							array('name' => 'StringTrim'),
					),
			)));
				
			$inputFilter->add($factory->createInput(array(
					'name'     => 'general_information',
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

	public function getId(){
		return $this->id;
	}
	public function setId($id){
		$this->id = $id;
		return $this;
	}
	public function getName(){
		return $this->name;
	}
	public function setName($name){
		$this->name = $name;
		return $this;
	}
	public function getSpecificationMaster(){
		return $this->specificationMaster;
	}
	public function setSpecificationMaster($specificationMaster){
		$this->specificationMaster = $specificationMaster;
		return $this;
	}

	public function getSpecificationMasterName(){
		return $this->specificationMasterName;
	}

	public function setSpecificationMasterName($specificationMasterName){
		$this->specificationMasterName = $specificationMasterName;
		return $this;
	}
	
	public function getImage(){
		return $this->image;
	}
	public function setImage($image){
		$this->image = $image;
		return $this;
	}
	public function getMeaning(){
		return $this->meaning;
	}
	public function setMeaning($meaning){
		$this->meaning = $meaning;
		return $this;
	}
	public function getGeneralInformation(){
		return $this->generalInformation;
	}
	public function setGeneralInformation($generalInformation){
		$this->generalInformation = $generalInformation;
		return $this;
	}


}
