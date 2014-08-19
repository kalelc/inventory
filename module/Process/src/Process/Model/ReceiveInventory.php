<?php
namespace Process\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class ReceiveInventory implements InputFilterAwareInterface
{
    protected $id;
	protected $user;
	protected $registerDate;
	protected $customer;
	protected $paymentMethod;
	protected $shipment;
	protected $guideNumber;
	protected $invoice;
	protected $invoiceFile;
	protected $observation;

    protected $customerFirstName;
    protected $customerLastName;
    protected $shipmentFirstName;
    protected $shipmentLastName;
    protected $shipmentCompany;
    protected $paymentMethodName;
    

	protected $inputFilter;

	public function exchangeArray($data)
	{
        if (array_key_exists('id', $data)) $this->setId($data['id']);
        if (array_key_exists('user', $data)) $this->setUser($data['user']);
        if (array_key_exists('register_date', $data)) $this->setRegisterDate($data['register_date']);
        if (array_key_exists('customer', $data)) $this->setCustomer($data['customer']);
        if (array_key_exists('payment_method', $data)) $this->setPaymentMethod($data['payment_method']);
        if (array_key_exists('shipment', $data)) $this->setShipment($data['shipment']);
        if (array_key_exists('guide_number', $data)) $this->setGuideNumber($data['guide_number']);
        if (array_key_exists('invoice', $data)) $this->setInvoice($data['invoice']);
        if (array_key_exists('invoice_file', $data)) $this->setInvoiceFile($data['invoice_file']);
        if (array_key_exists('observation', $data)) $this->setObservation($data['observation']);
        
        if (array_key_exists('customer_first_name', $data)) $this->setCustomerFirstName($data['customer_first_name']);
        if (array_key_exists('customer_last_name', $data)) $this->setCustomerLastName($data['customer_last_name']);
        if (array_key_exists('shipment_first_name', $data)) $this->setShipmentFirstName($data['shipment_first_name']);
        if (array_key_exists('shipment_last_name', $data)) $this->setShipmentLastName($data['shipment_last_name']);
        if (array_key_exists('shipment_company', $data)) $this->setShipmentCompany($data['shipment_company']);
        if (array_key_exists('payment_method_name', $data)) $this->setPaymentMethodName($data['payment_method_name']);
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

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    public function getRegisterDate()
    {
        return $this->registerDate;
    }
    

    public function setRegisterDate($registerDate)
    {
        $this->registerDate = $registerDate;

        return $this;
    }

    public function getCustomer()
    {
        return $this->customer;
    }
    

    public function setCustomer($customer)
    {
        $this->customer = $customer;

        return $this;
    }

    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    public function getShipment()
    {
        return $this->shipment;
    }
    
    public function setShipment($shipment)
    {
        $this->shipment = $shipment;

        return $this;
    }

    public function getGuideNumber()
    {
        return $this->guideNumber;
    }
    
    public function setGuideNumber($guideNumber)
    {
        $this->guideNumber = $guideNumber;

        return $this;
    }

    public function getInvoice()
    {
        return $this->invoice;
    }
    
    public function setInvoice($invoice)
    {
        $this->invoice = $invoice;

        return $this;
    }

    public function getInvoiceFile()
    {
        return $this->invoiceFile;
    }
    
    public function setInvoiceFile($invoiceFile)
    {
        $this->invoiceFile = $invoiceFile;

        return $this;
    }

    public function getObservation()
    {
        return $this->observation;
    }

    public function setObservation($observation)
    {
        $this->observation = $observation;
        return $this;
    }

    public function getCustomerFirstName()
    {
        return $this->customerFirstName;
    }
    
    public function setCustomerFirstName($customerFirstName)
    {
        $this->customerFirstName = $customerFirstName;
        return $this;
    }

    public function getCustomerLastName()
    {
        return $this->customerLastName;
    }

    public function setCustomerLastName($customerLastName)
    {
        $this->customerLastName = $customerLastName;
        return $this;
    }

    public function getShipmentFirstName()
    {
        return $this->shipmentFirstName;
    }

    public function setShipmentFirstName($shipmentFirstName)
    {
        $this->shipmentFirstName = $shipmentFirstName;
        return $this;
    }

    public function getShipmentLastName()
    {
        return $this->shipmentLastName;
    }
    
    public function setShipmentLastName($shipmentLastName)
    {
        $this->shipmentLastName = $shipmentLastName;
        return $this;
    }

    public function getShipmentCompany()
    {
        return $this->shipmentCompany;
    }

    public function setShipmentCompany($shipmentCompany)
    {
        $this->shipmentCompany = $shipmentCompany;
        return $this;
    }

    public function getPaymentMethodName()
    {
        return $this->paymentMethodName;
    }

    public function setPaymentMethodName($paymentMethodName)
    {
        $this->paymentMethodName = $paymentMethodName;
        return $this;
    }
    
}
