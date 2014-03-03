<?php
namespace Admin\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class MeasureType implements InputFilterAwareInterface
{
	private $id;
	private $abbreviation;
	private $name;
	private $description;
	protected $inputFilter;

	public function exchangeArray($data)
	{
		
		if (array_key_exists('id', $data)) $this->setId($data['id']);
		if (array_key_exists('abbreviation', $data)) $this->setAbbreviation($data['abbreviation']);
		if (array_key_exists('name', $data)) $this->setName($data['name']);
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
					'name'     => 'abbreviation',
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
					'name'     => 'name',
					'required' => true,
					'filters'  => array(
							array('name' => 'StripTags'),
							array('name' => 'StringTrim'),
					),
					'validators' => array(
							array(
									'name'    => 'StringLength',
									'options' => array(
											'encoding' => 'UTF-8',
											'min'      => 3,
											'max'      => 50,
											'messages' => array(
													\Zend\Validator\StringLength::TOO_LONG => 'debe tener maximo %max% caracteres',
													\Zend\Validator\StringLength::TOO_SHORT => 'debe tener minimo %min% caracteres'
											),
									),
							),
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
	
	public function getAbbreviation(){
		return $this->abbreviation;
	}
	
	public function setAbbreviation($abbreviation){
		$this->abbreviation = $abbreviation;
		return $this;
	}
	
	public function getDescription(){
		return $this->description;
	}
	
	public function setDescription($description){
		$this->description = $description;
		return $this;
	}
}
