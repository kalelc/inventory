<?php
namespace Admin\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Measure implements InputFilterAwareInterface
{
	private $id;
	private $specification;
	private $measureType;
	private $measureValue;
	private $image;
	private $meaning;
	private $generalInformation;

	private $specificationName ;
	private $measureTypeName ;
	private $measureTypeAbbreviation ;

	protected $inputFilter;

	public function exchangeArray($data)
	{
		if (array_key_exists('id', $data)) $this->setId($data['id']);
		if (array_key_exists('specification', $data)) $this->setSpecification($data['specification']);
		if (array_key_exists('measure_type', $data)) $this->setMeasureType($data['measure_type']);
		if (array_key_exists('s_name', $data)) $this->setSpecificationName($data['s_name']);
		if (array_key_exists('mt_name', $data)) $this->setMeasureTypeName($data['mt_name']);
		if (array_key_exists('measure_value', $data)) $this->setMeasureValue($data['measure_value']);
		if (array_key_exists('image', $data)) $this->setImage($data['image']);
		if (array_key_exists('meaning', $data)) $this->setMeaning($data['meaning']);
		if (array_key_exists('general_information', $data)) $this->setGeneralInformation($data['general_information']);
		if (array_key_exists('mt_name', $data)) $this->setMeasureTypeName($data['mt_name']);
		if (array_key_exists('mt_abbreviation', $data)) $this->setMeasureTypeAbbreviation($data['mt_abbreviation']);
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
				'name'     => 'specification',
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
				'name'     => 'measure_type',
				'required' => false,
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
				'name'     => 'measure_value',
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
	public function getMeasureValue(){
		return $this->measureValue;
	}
	public function setMeasureValue($measureValue){
		$this->measureValue = $measureValue;
		return $this;
	}
	public function getSpecification(){
		return $this->specification;
	}

	public function setSpecification($specification){
		$this->specification = $specification;
		return $this;
	}

	public function setSpecificationName($specificationName){
		$this->specificationName = $specificationName ; 
		return $this;
	}

	public function getSpecificationName() {
		return $this->specificationName ;
	}

	public function setMeasureTypeName($measureTypeName){
		$this->measureTypeName = $measureTypeName ;
		return $this;
	}

	public function getMeasureTypeName(){
		return $this->measureTypeName;
	}

	public function setMeasureType($measureType){
		$this->measureType = $measureType;
	}

	public function getMeasureType(){
		return $this->measureType;
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

    public function getMeasureTypeAbbreviation()
    {
        return $this->measureTypeAbbreviation;
    }

    public function setMeasureTypeAbbreviation($measureTypeAbbreviation)
    {
        $this->measureTypeAbbreviation = $measureTypeAbbreviation;

        return $this;
    }
}
