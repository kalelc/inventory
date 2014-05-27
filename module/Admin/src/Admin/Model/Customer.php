<?php
namespace Admin\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Customer implements InputFilterAwareInterface
{
	private $id;
	private $identification;
	private $identificationType;
	private $firstName;
	private $lastName;
	private $emails;
	private $addresses;
	private $phones;
	private $zipcode;
	private $company;
	private $manager;
	private $webpage;
	private $birthday;
	private $alias;
	private $description;
	private $city;

	protected $inputFilter;

	public function exchangeArray($data)
	{
		if (array_key_exists('id', $data)) $this->setId($data['id']);
		if (array_key_exists('identification', $data)) $this->setIdentification($data['identification']);
		if (array_key_exists('identification_type', $data)) $this->setIdentificationType($data['identification_type']);
		if (array_key_exists('first_name', $data)) $this->setFirstName($data['first_name']);
		if (array_key_exists('last_name', $data)) $this->setLastName($data['last_name']);
		if (array_key_exists('emails', $data)) $this->setEmails($data['emails']);
		if (array_key_exists('addresses', $data)) $this->setAddresses($data['addresses']);
		if (array_key_exists('phones', $data)) $this->setPhones($data['phones']);
		if (array_key_exists('zipcode', $data)) $this->setZipcode($data['zipcode']);
		if (array_key_exists('company', $data)) $this->setCompany($data['company']);
		if (array_key_exists('manager', $data)) $this->setManager($data['manager']);
		if (array_key_exists('webpage', $data)) $this->setWebpage($data['webpage']);
		if (array_key_exists('birthday', $data)) $this->setBirthday($data['birthday']);
		if (array_key_exists('alias', $data)) $this->setAlias($data['alias']);
		if (array_key_exists('description', $data)) $this->setDescription($data['description']);
		if (array_key_exists('city', $data)) $this->setCity($data['city']);
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
				'name'     => 'identification',
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
				'name'     => 'identification_type',
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
				'name'     => 'first_name',
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
				'name'     => 'last_name',
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
				'name'     => 'zipcode',
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
				'name'     => 'company',
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
				'name'     => 'manager',
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
				'name'     => 'webpage',
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
				'name'     => 'birthday',
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
				'name'     => 'alias',
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
				'name'     => 'description',
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

	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
		return $this->id;
	}

	public function getIdentification()
	{
		return $this->identification;
	}

	public function setIdentification($identification)
	{
		$this->identification = $identification;
		return $this;
	}

	public function getIdentificationType()
	{
		return $this->identificationType;
	}

	public function setIdentificationType($identificationType)
	{
		$this->identificationType = $identificationType;
		return $this;
	}

	public function getFirstName()
	{
		return $this->firstName;
	}

	public function setFirstName($firstName)
	{
		$this->firstName = $firstName;
		return $this;
	}

	public function getLastName()
	{
		return $this->lastName;
	}

	public function setLastName($lastName)
	{
		$this->lastName = $lastName;
		return $this;
	}

	public function getEmails()
	{
		return $this->emails;
	}

	public function setEmails($emails)
	{
		$this->emails = $emails;
		return $this;
	}

	public function getAddress()
	{
		return $this->address;
	}

	public function setAddress($address)
	{
		$this->address = $address;
		return $this;
	}

	public function getPhones()
	{
		return $this->phones;
	}

	public function setPhones($phones)
	{
		$this->phones = $phones;
		return $this;
	}

	public function getZipcode()
	{
		return $this->zipcode;
	}

	public function setZipcode($zipcode)
	{
		$this->zipcode = $zipcode;
		return $this;
	}

	public function getCompany()
	{
		return $this->company;
	}

	public function setCompany($company)
	{
		$this->company = $company;
		return $this;
	}

	public function getManager()
	{
		return $this->manager;
	}

	public function setManager($manager)
	{
		$this->manager = $manager;
		return $this;
	}

	public function getWebpage()
	{
		return $this->webpage;
	}

	public function setWebpage($webpage)
	{
		$this->webpage = $webpage;
		return $this;
	}

	public function getBirthday()
	{
		return $this->birthday;
	}

	public function setBirthday($birthday)
	{
		$this->birthday = $birthday;
		return $this;
	}

	public function getAlias()
	{
		return $this->alias;
	}

	public function setAlias($alias)
	{
		$this->alias = $alias;
		return $this;
	}

	public function getDescription()
	{
		return $this->description;
	}

	public function setDescription($description)
	{
		$this->description = $description;
		return $this;
	}

	public function getAddresses()
	{
		return $this->addresses;
	}

	public function setAddresses($addresses)
	{
		$this->addresses = $addresses;
		return $this;
	}

    public function getCity()
    {
        return $this->city;
    }

    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }
}
