<?php
namespace Process\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class DetailsOutputInventory implements InputFilterAwareInterface
{
    protected $id;
	protected $receiveInventory;
	protected $cost;
    protected $iva;
    protected $ivaAccumulated;
    protected $product;
    protected $qty;
    protected $manifestFile;
    protected $serials;


	protected $inputFilter;

	public function exchangeArray($data)
	{
        if (array_key_exists('id', $data)) $this->setId($data['id']);
        if (array_key_exists('receive_inventory', $data)) $this->setReceiveInventory($data['receive_inventory']);
        if (array_key_exists('cost', $data)) $this->setCost($data['cost']);
        if (array_key_exists('iva', $data)) $this->setIva($data['iva']);
        if (array_key_exists('product', $data)) $this->setProduct($data['product']);
        if (array_key_exists('qty', $data)) $this->setQty($data['qty']);
        if (array_key_exists('serials', $data)) $this->setSerials($data['serials']);
        if (array_key_exists('manifest_file', $data)) $this->setManifestFile($data['manifest_file']);
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
				'name'     => 'cost',
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
								\Zend\Validator\NotEmpty::IS_EMPTY => 'ingrese el costo'
								),
							),
						),
					),
				)));

			$inputFilter->add($factory->createInput(array(
				'name'     => 'iva',
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
								\Zend\Validator\NotEmpty::IS_EMPTY => 'seleccione el iva'
								),
							),
						),
					),
				)));

			$inputFilter->add($factory->createInput(array(
				'name'     => 'qty',
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
								\Zend\Validator\NotEmpty::IS_EMPTY => 'ingrese cantidad'
								),
							),
						),
					),
				)));

			$inputFilter->add($factory->createInput(array(
				'name'     => 'product',
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
								\Zend\Validator\NotEmpty::IS_EMPTY => 'seleccione el producto'
								),
							),
						),
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

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getReceiveInventory()
    {
        return $this->receiveInventory;
    }

    public function setReceiveInventory($receiveInventory)
    {
        $this->receiveInventory = $receiveInventory;

        return $this;
    }

    public function getCost()
    {
        return $this->cost;
    }

    public function setCost($cost)
    {
        $this->cost = $cost;

        return $this;
    }

    public function getIva()
    {
        return $this->iva;
    }

    public function setIva($iva)
    {
        $this->iva = $iva;

        return $this;
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

    public function getQty()
    {
        return $this->qty;
    }
 
    public function setQty($qty)
    {
        $this->qty = $qty;

        return $this;
    }

    public function getSerials()
    {
        return $this->serials;
    }
    
    public function setSerials($serials)
    {
        $this->serials = $serials;

        return $this;
    }

    public function getManifestFile()
    {
        return $this->manifestFile;
    }
    
    public function setManifestFile($manifestFile)
    {
        $this->manifestFile = $manifestFile;

        return $this;
    }
    public function getIvaAccumulated()
    {
        return $this->ivaAccumulated;
    }

    public function setIvaAccumulated($ivaAccumulated)
    {
        $this->ivaAccumulated = $ivaAccumulated;

        return $this;
    }
}
