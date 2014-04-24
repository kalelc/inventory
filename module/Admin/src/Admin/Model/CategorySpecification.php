<?php
namespace Admin\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class CategorySpecification implements InputFilterAwareInterface
{
	private $category;
	private $specification;
	private $specificationName;
	private $specificationImage;

	protected $inputFilter;

	public function exchangeArray($data)
	{
		if (array_key_exists('category', $data)) $this->setCategory($data['category']);
		if (array_key_exists('specification', $data)) $this->setSpecification($data['specification']);
		if (array_key_exists('specification_name', $data)) $this->setSpecificationName($data['specification_name']);
		if (array_key_exists('specification_image', $data)) $this->setSpecificationImage($data['specification_image']);
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
				'name'     => 'category',
				'required' => true,
				'filters'  => array(
					array('name' => 'Int'),
					),
				)));

			$inputFilter->add($factory->createInput(array(
				'name'     => 'specification',
				'required' => true,
				'filters'  => array(
					array('name' => 'Int'),
					),
				)));

			$this->inputFilter = $inputFilter;
		}

		return $this->inputFilter;
	}

	public function getCategory()
	{
		return $this->category;
	}

	public function setCategory($category)
	{
		$this->category = $category;
		return $this;
	}

	public function getSpecification()
	{
		return $this->specification;
	}

	public function setSpecification($specification)
	{
		$this->specification = $specification;
		return $this;
	}

    public function getSpecificationName()
    {
        return $this->specificationName;
    }

    public function setSpecificationName($specificationName)
    {
        $this->specificationName = $specificationName;
        return $this;
    }

    public function getSpecificationImage()
    {
        return $this->specificationImage;
    }

    public function setSpecificationImage($specificationImage)
    {
        $this->specificationImage = $specificationImage;
        return $this;
    }
}
