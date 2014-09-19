<?php
namespace Process\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class DetailsOutputInventory implements InputFilterAwareInterface
{
    protected $id;
    protected $outputInventory;
    protected $qty;
    protected $cost;
    protected $iva;
    protected $product;
    protected $ivaAccumulated;
    protected $inputFilter;

    public function exchangeArray($data)
    {
        if (array_key_exists('id', $data)) $this->setId($data['id']);
        if (array_key_exists('output_inventory', $data)) $this->setOutputInventory($data['output_inventory']);
        if (array_key_exists('qty', $data)) $this->setQty($data['qty']);
        if (array_key_exists('cost', $data)) $this->setCost($data['cost']);
        if (array_key_exists('iva', $data)) $this->setIva($data['iva']);
        if (array_key_exists('product', $data)) $this->setProduct($data['product']);
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


    /**
     * Gets the value of id.
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Sets the value of id.
     *
     * @param mixed $id the id 
     *
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the value of outputInventory.
     *
     * @return mixed
     */
    public function getOutputInventory()
    {
        return $this->outputInventory;
    }
    
    /**
     * Sets the value of outputInventory.
     *
     * @param mixed $outputInventory the output inventory 
     *
     * @return self
     */
    public function setOutputInventory($outputInventory)
    {
        $this->outputInventory = $outputInventory;

        return $this;
    }

    /**
     * Gets the value of qty.
     *
     * @return mixed
     */
    public function getQty()
    {
        return $this->qty;
    }
    
    /**
     * Sets the value of qty.
     *
     * @param mixed $qty the qty 
     *
     * @return self
     */
    public function setQty($qty)
    {
        $this->qty = $qty;

        return $this;
    }

    /**
     * Gets the value of cost.
     *
     * @return mixed
     */
    public function getCost()
    {
        return $this->cost;
    }
    
    /**
     * Sets the value of cost.
     *
     * @param mixed $cost the cost 
     *
     * @return self
     */
    public function setCost($cost)
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * Gets the value of iva.
     *
     * @return mixed
     */
    public function getIva()
    {
        return $this->iva;
    }
    
    /**
     * Sets the value of iva.
     *
     * @param mixed $iva the iva 
     *
     * @return self
     */
    public function setIva($iva)
    {
        $this->iva = $iva;

        return $this;
    }

    /**
     * Gets the value of product.
     *
     * @return mixed
     */
    public function getProduct()
    {
        return $this->product;
    }
    
    /**
     * Sets the value of product.
     *
     * @param mixed $product the product 
     *
     * @return self
     */
    public function setProduct($product)
    {
        $this->product = $product;

        return $this;
    }

    
    public function setIvaAccumulated($ivaAccumulated)
    {
        $this->ivaAccumulated = $ivaAccumulated;

        return $this;
    }

    public function getIvaAccumulated()
    {
        return $this->ivaAccumulated;
    }
}
