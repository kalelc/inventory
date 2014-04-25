<?php
namespace Admin\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class ProductMeasure implements InputFilterAwareInterface
{
	private $product;
	private $measure;
	protected $inputFilter;

	public function exchangeArray($data)
	{
		if (array_key_exists('product', $data)) $this->setProduct($data['product']);
		if (array_key_exists('measure', $data)) $this->setMeasure($data['measure']);
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
				'name'     => 'product',
				'required' => true,
				'filters'  => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
					),
				)));

			$inputFilter->add($factory->createInput(array(
				'name'     => 'measure',
				'required' => true,
				'filters'  => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
					),
				)));

			$this->inputFilter = $inputFilter;
		}

		return $this->inputFilter;
	}

    public function getProduct()
    {
    	return $this->product;
    }

    public function setProduct($product)
    {
    	$this->product = $product;
    	return $this;
    }

    public function getMeasure()
    {
        return $this->measure;
    }

    public function setMeasure($measure)
    {
        $this->measure = $measure;
        return $this;
    }
}
