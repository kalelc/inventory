<?php
namespace Admin\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class CategorySerialName implements InputFilterAwareInterface
{
	private $category;
	private $serialName;
	protected $inputFilter;

	public function exchangeArray($data)
	{
		if (array_key_exists('category', $data)) $this->setCategory($data['category']);
		if (array_key_exists('serial_name', $data)) $this->setSerialName($data['serial_name']);
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
	{}

	public function getCategory(){
		return $this->category;
	}

	public function setCategory($category){
		$this->category = $category;
		return $this;
	}

	public function getSerialName(){
		return $this->serialName;
	}

	public function setSerialName($serialName){
		$this->serialName = $serialName;
		return $this;
	}
}
