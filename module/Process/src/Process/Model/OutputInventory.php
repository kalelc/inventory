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
	protected $seller;
	protected $guideNumber;
	protected $observation;

    protected $customerFirstName;
    protected $customerLastName;
    protected $sellerFirstName;
    protected $sellerLastName;
    protected $sellerCompany;
    protected $paymentMethodName;
    
	protected $inputFilter;

	public function exchangeArray($data)
	{
        if (array_key_exists('id', $data)) $this->setId($data['id']);
        if (array_key_exists('user', $data)) $this->setUser($data['user']);
        if (array_key_exists('register_date', $data)) $this->setRegisterDate($data['register_date']);
        if (array_key_exists('customer', $data)) $this->setCustomer($data['customer']);
        if (array_key_exists('payment_method', $data)) $this->setPaymentMethod($data['payment_method']);
        if (array_key_exists('seller', $data)) $this->setSeller($data['seller']);
        if (array_key_exists('guide_number', $data)) $this->setGuideNumber($data['guide_number']);
        if (array_key_exists('invoice', $data)) $this->setInvoice($data['invoice']);
        if (array_key_exists('invoice_file', $data)) $this->setInvoiceFile($data['invoice_file']);
        if (array_key_exists('observation', $data)) $this->setObservation($data['observation']);
        
        if (array_key_exists('customer_first_name', $data)) $this->setCustomerFirstName($data['customer_first_name']);
        if (array_key_exists('customer_last_name', $data)) $this->setCustomerLastName($data['customer_last_name']);
        if (array_key_exists('seller_first_name', $data)) $this->setSellerFirstName($data['seller_first_name']);
        if (array_key_exists('seller_last_name', $data)) $this->setSellerLastName($data['seller_last_name']);
        if (array_key_exists('seller_company', $data)) $this->setSellerCompany($data['seller_company']);
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
				'name'     => 'seller',
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
     * Gets the value of user.
     *
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }
    
    /**
     * Sets the value of user.
     *
     * @param mixed $user the user 
     *
     * @return self
     */
    public function setUser($user)
    {
        $this->user = $user;

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
     * Gets the value of seller.
     *
     * @return mixed
     */
    public function getSeller()
    {
        return $this->seller;
    }
    
    /**
     * Sets the value of seller.
     *
     * @param mixed $seller the seller 
     *
     * @return self
     */
    public function setSeller($seller)
    {
        $this->seller = $seller;

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

    /**
     * Gets the value of customerFirstName.
     *
     * @return mixed
     */
    public function getCustomerFirstName()
    {
        return $this->customerFirstName;
    }
    
    /**
     * Sets the value of customerFirstName.
     *
     * @param mixed $customerFirstName the customer first name 
     *
     * @return self
     */
    public function setCustomerFirstName($customerFirstName)
    {
        $this->customerFirstName = $customerFirstName;

        return $this;
    }

    /**
     * Gets the value of customerLastName.
     *
     * @return mixed
     */
    public function getCustomerLastName()
    {
        return $this->customerLastName;
    }
    
    /**
     * Sets the value of customerLastName.
     *
     * @param mixed $customerLastName the customer last name 
     *
     * @return self
     */
    public function setCustomerLastName($customerLastName)
    {
        $this->customerLastName = $customerLastName;

        return $this;
    }

    /**
     * Gets the value of sellerFirstName.
     *
     * @return mixed
     */
    public function getSellerFirstName()
    {
        return $this->sellerFirstName;
    }
    
    /**
     * Sets the value of sellerFirstName.
     *
     * @param mixed $sellerFirstName the seller first name 
     *
     * @return self
     */
    public function setSellerFirstName($sellerFirstName)
    {
        $this->sellerFirstName = $sellerFirstName;

        return $this;
    }

    /**
     * Gets the value of sellerLastName.
     *
     * @return mixed
     */
    public function getSellerLastName()
    {
        return $this->sellerLastName;
    }
    
    /**
     * Sets the value of sellerLastName.
     *
     * @param mixed $sellerLastName the seller last name 
     *
     * @return self
     */
    public function setSellerLastName($sellerLastName)
    {
        $this->sellerLastName = $sellerLastName;

        return $this;
    }

    /**
     * Gets the value of sellerCompany.
     *
     * @return mixed
     */
    public function getSellerCompany()
    {
        return $this->sellerCompany;
    }
    
    /**
     * Sets the value of sellerCompany.
     *
     * @param mixed $sellerCompany the seller company 
     *
     * @return self
     */
    public function setSellerCompany($sellerCompany)
    {
        $this->sellerCompany = $sellerCompany;

        return $this;
    }

    /**
     * Gets the value of paymentMethodName.
     *
     * @return mixed
     */
    public function getPaymentMethodName()
    {
        return $this->paymentMethodName;
    }
    
    /**
     * Sets the value of paymentMethodName.
     *
     * @param mixed $paymentMethodName the payment method name 
     *
     * @return self
     */
    public function setPaymentMethodName($paymentMethodName)
    {
        $this->paymentMethodName = $paymentMethodName;

        return $this;
    }
}
