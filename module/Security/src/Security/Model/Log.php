<?php
namespace Security\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Log implements InputFilterAwareInterface
{
	private $id;
	private $table;
    private $tableId;
	private $operation;
    private $user;
    private $data;
	private $registerDate;
	
	protected $inputFilter;

	public function exchangeArray($data)
	{
		if (array_key_exists('id', $data)) $this->setId($data['id']);
		if (array_key_exists('table', $data)) $this->setTable($data['table']);
		if (array_key_exists('table_id', $data)) $this->setTableId($data['table_id']);
		if (array_key_exists('operation', $data)) $this->setOperation($data['operation']);
        if (array_key_exists('user', $data)) $this->setUser($data['user']);
        if (array_key_exists('data', $data)) $this->setData($data['data']);
		if (array_key_exists('register_date', $data)) $this->setRegisterDate($data['register_date']);
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

    public function getTable()
    {
        return $this->table;
    }

    public function setTable($table)
    {
        $this->table = $table;

        return $this;
    }

    public function getTableId()
    {
        return $this->tableId;
    }
    
    public function setTableId($tableId)
    {
        $this->tableId = $tableId;

        return $this;
    }

    public function getOperation()
    {
        return $this->operation;
    }

    public function setOperation($operation)
    {
        $this->operation = $operation;

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

    public function getData()
    {
        return $this->data;
    }

    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }
}
