<?php
namespace Process\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class OutputInventory implements InputFilterAwareInterface
{
    protected $id;
    protected $user;
    protected $registerDate;
    protected $client;
    protected $paymentMethod;
    protected $seller;
    protected $guideNumber;
    protected $observation;

    protected $clientFirstName; 
    protected $clientLastName; 
    protected $sellerFirstName;
    protected $sellerLastName;
    protected $sellerCompany;
    protected $paymentMethodName;
    
    protected $inputFilter;

    public function exchangeArray($data)
    {
        if (array_key_exists('id', $data)) $this->setId($data['id']);
        if (array_key_exists('user', $data)) $this->setUser($data['user']);
        if (array_key_exists('client', $data)) $this->setClient($data['client']);
        if (array_key_exists('payment_method', $data)) $this->setPaymentMethod($data['payment_method']);
        if (array_key_exists('seller', $data)) $this->setSeller($data['seller']);
        if (array_key_exists('guide_number', $data)) $this->setGuideNumber($data['guide_number']);
        if (array_key_exists('observation', $data)) $this->setObservation($data['observation']);

        if (array_key_exists('client_first_name', $data)) $this->setClientFirstName($data['client_first_name']);
        if (array_key_exists('client_last_name', $data)) $this->setClientLastName($data['client_last_name']);
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
            'name'     => 'client',
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

    public function getClient()
    {
        return $this->client;
    }

    public function setClient($client)
    {
        $this->client = $client;
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

    public function getSeller()
    {
        return $this->seller;
    }

    public function setSeller($seller)
    {
        $this->seller = $seller;
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

    public function getObservation()
    {
        return $this->observation;
    }

    public function setObservation($observation)
    {
        $this->observation = $observation;
        return $this;
    }

    public function getClientFirstName()
    {
        return $this->clientFirstName;
    }

    public function setClientFirstName($clientFirstName)
    {
        $this->clientFirstName = $clientFirstName;
        return $this;
    }

    public function getClientLastName()
    {
        return $this->clientLastName;
    }

    public function setClientLastName($clientLastName)
    {
        $this->clientLastName = $clientLastName;
        return $this;
    }

    public function getSellerFirstName()
    {
        return $this->sellerFirstName;
    }

    public function setSellerFirstName($sellerFirstName)
    {
        $this->sellerFirstName = $sellerFirstName;
        return $this;
    }

    public function getSellerLastName()
    {
        return $this->sellerLastName;
    }
    
    public function setSellerLastName($sellerLastName)
    {
        $this->sellerLastName = $sellerLastName;
        return $this;
    }

    public function getSellerCompany()
    {
        return $this->sellerCompany;
    }
    
    public function setSellerCompany($sellerCompany)
    {
        $this->sellerCompany = $sellerCompany;
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
