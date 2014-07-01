<?php 
namespace Admin\Model;

use Zend\Db\TableGateway\TableGateway;

class CategorySerialNameTable
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

	public function get($category)
	{
		$category  = (int) $category;
		$resultSet = $this->tableGateway->select(array('category' => $category));

		$rows = array();

		foreach($resultSet  as $result) {
			$rows[] = $result->getSerialName();
		}
		
		return $rows ? $rows : false ;
	}

	public function save($category,$serialsName)
	{
		$this->delete($category);
		
		foreach($serialsName as $serialName)
			$this->tableGateway->insert(array('category'=>$category,'serial_name' =>$serialName));
	}

	public function delete($categoryId)
	{
		$this->tableGateway->delete(array('category' => $categoryId));
	}
}