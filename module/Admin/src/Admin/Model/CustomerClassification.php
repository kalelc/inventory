<?php
namespace Admin\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class CustomerClassification implements InputFilterAwareInterface
{
	private $customer;
	private $classification;

	protected $inputFilter;

	public function exchangeArray($data)
	{
		if (array_key_exists('customer', $data)) $this->setCustomer($data['customer']);
		if (array_key_exists('classification', $data)) $this->setClassification($data['classification']);
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

	public function getCustomer()
	{
		return $this->customer;
	}

	public function setCustomer($customer)
	{
		$this->customer = $customer;
		return $this;
	}

	public function getClassification()
	{
		return $this->classification;
	}

	public function setClassification($classification)
	{
		$this->classification = $classification;
		return $this;
	}
}
