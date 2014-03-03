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

	protected $inputFilter;

	public function exchangeArray($data)
	{
		if (array_key_exists('category', $data)) $this->setCategory($data['category']);
		if (array_key_exists('specification', $data)) $this->setSpecification($data['specification']);
		if (array_key_exists('specification_name', $data)) $this->setSpecificationName($data['specification_name']);
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

    /**
     * Gets the value of specificationName.
     *
     * @return mixed
     */
    public function getSpecificationName()
    {
        return $this->specificationName;
    }
    
    /**
     * Sets the value of specificationName.
     *
     * @param mixed $specificationName the specification name
     *
     * @return self
     */
    public function setSpecificationName($specificationName)
    {
        $this->specificationName = $specificationName;

        return $this;
    }
}
