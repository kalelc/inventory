<?php
namespace Process\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class ProductsReceiveInventory implements InputFilterAwareInterface
{
	protected $detailsReceiveInventory;
    protected $number;
    protected $serial;
	protected $status;

	protected $inputFilter;

	public function exchangeArray($data)
	{
        if (array_key_exists('details_receive_inventory', $data)) $this->setDetailsReceiveInventory($data['details_receive_inventory']);
		if (array_key_exists('number', $data)) $this->setNumber($data['number']);
        if (array_key_exists('serial', $data)) $this->setSerial($data['serial']);
		if (array_key_exists('status', $data)) $this->setStatus($data['status']);
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


    public function getDetailsReceiveInventory()
    {
        return $this->detailsReceiveInventory;
    }
    
    public function setDetailsReceiveInventory($detailsReceiveInventory)
    {
        $this->detailsReceiveInventory = $detailsReceiveInventory;
        return $this;
    }

    public function getSerial()
    {
        return $this->serial;
    }

    public function setSerial($serial)
    {
        $this->serial = $serial;
        return $this;
    }

    public function getNumber()
    {
        return $this->number;
    }

    protected function setNumber($number)
    {
        $this->number = $number;
        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    protected function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }
}