<?php
namespace Process\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class ReceiveInventory implements InputFilterAwareInterface
{
	protected $id;
	protected $registerDate;
	protected $customer;
	protected $paymentMethod;
	protected $shipment;
	protected $guideNumber;
	protected $invoice;
	protected $invoiceFile;
	protected $observation;

	protected $inputFilter;

	public function exchangeArray($data)
	{
        if (array_key_exists('id', $data)) $this->setId($data['id']);
        if (array_key_exists('register_date', $data)) $this->setId($data['register_date']);
        if (array_key_exists('customer', $data)) $this->setId($data['customer']);
        if (array_key_exists('payment_method', $data)) $this->setId($data['payment_method']);
        if (array_key_exists('shipment', $data)) $this->setId($data['shipment']);
        if (array_key_exists('guide_number', $data)) $this->setId($data['guide_number']);
        if (array_key_exists('invoice', $data)) $this->setId($data['invoice']);
        if (array_key_exists('invoice_file', $data)) $this->setId($data['invoice_file']);
        if (array_key_exists('observation', $data)) $this->setId($data['observation']);
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
				'name'     => 'customer',
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
				'name'     => 'payment_method',
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
				'name'     => 'shipment',
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
				'name'     => 'guide_number',
				'required' => false,
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
				'name'     => 'invoice',
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
                'name'     => 'invoice_file',
                'required' => false,
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
                'name'     => 'observation',
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
     * Gets the value of registerDate.
     *
     * @return mixed
     */
    public function getRegisterDate()
    {
        return $this->registerDate;
    }
    
    /**
     * Sets the value of registerDate.
     *
     * @param mixed $registerDate the register date 
     *
     * @return self
     */
    public function setRegisterDate($registerDate)
    {
        $this->registerDate = $registerDate;

        return $this;
    }

    /**
     * Gets the value of customer.
     *
     * @return mixed
     */
    public function getCustomer()
    {
        return $this->customer;
    }
    
    /**
     * Sets the value of customer.
     *
     * @param mixed $customer the customer 
     *
     * @return self
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Gets the value of paymentMethod.
     *
     * @return mixed
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }
    
    /**
     * Sets the value of paymentMethod.
     *
     * @param mixed $paymentMethod the payment method 
     *
     * @return self
     */
    public function setPaymentMethod($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    /**
     * Gets the value of shipment.
     *
     * @return mixed
     */
    public function getShipment()
    {
        return $this->shipment;
    }
    
    /**
     * Sets the value of shipment.
     *
     * @param mixed $shipment the shipment 
     *
     * @return self
     */
    public function setShipment($shipment)
    {
        $this->shipment = $shipment;

        return $this;
    }

    /**
     * Gets the value of guideNumber.
     *
     * @return mixed
     */
    public function getGuideNumber()
    {
        return $this->guideNumber;
    }
    
    /**
     * Sets the value of guideNumber.
     *
     * @param mixed $guideNumber the guide number 
     *
     * @return self
     */
    public function setGuideNumber($guideNumber)
    {
        $this->guideNumber = $guideNumber;

        return $this;
    }

    /**
     * Gets the value of invoice.
     *
     * @return mixed
     */
    public function getInvoice()
    {
        return $this->invoice;
    }
    
    /**
     * Sets the value of invoice.
     *
     * @param mixed $invoice the invoice 
     *
     * @return self
     */
    public function setInvoice($invoice)
    {
        $this->invoice = $invoice;

        return $this;
    }

    /**
     * Gets the value of invoiceFile.
     *
     * @return mixed
     */
    public function getInvoiceFile()
    {
        return $this->invoiceFile;
    }
    
    /**
     * Sets the value of invoiceFile.
     *
     * @param mixed $invoiceFile the invoice file 
     *
     * @return self
     */
    public function setInvoiceFile($invoiceFile)
    {
        $this->invoiceFile = $invoiceFile;

        return $this;
    }

    /**
     * Gets the value of observation.
     *
     * @return mixed
     */
    public function getObservation()
    {
        return $this->observation;
    }
    
    /**
     * Sets the value of observation.
     *
     * @param mixed $observation the observation 
     *
     * @return self
     */
    public function setObservation($observation)
    {
        $this->observation = $observation;

        return $this;
    }
}
