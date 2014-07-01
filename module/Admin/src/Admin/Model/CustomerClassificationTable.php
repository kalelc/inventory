<?php 
namespace Admin\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class CustomerClassificationTable
{
	protected $tableGateway;

	public function __construct(TableGateway $tableGateway)
	{
		$this->tableGateway = $tableGateway;
	}

	public function fetchAll()
	{
		$resultSet = $this->tableGateway->select();
		$resultSet->buffer();
		return $resultSet;
	}

	public function get($customer)
	{
		$customer  = (int) $customer;
		$resultSet = $this->tableGateway->select(array('customer' => $customer));

		$rows = array();

		foreach($resultSet  as $result) {
			$rows[] = $result->getClassification();
		}
		
		return $rows ? $rows : false ;
	}

	public function save($customer,$classifications)
	{
		$this->delete($customer);
		
		foreach($classifications as $classification)
			$this->tableGateway->insert(array('customer'=>$customer,'classification' =>$classification));
	}


	public function delete($customer)
	{
		$this->tableGateway->delete(array('customer' => $customer));
	}
}