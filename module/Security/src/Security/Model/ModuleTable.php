<?php 
namespace Security\Model;

use Application\Db\TableGateway;

class ModuleTable
{
	private $tableGateway;
	private $eventManager;

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

	public function get($id)
	{
		$id  = (int) $id;
		$rowset = $this->tableGateway->select(array('id' => $id));
		$row = $rowset->current();
		if (!$row) {
			return false;
		}
		return $row;
	}

	public function save($modules)
	{	
		foreach($modules as $module) {
			$this->tableGateway->insert(array("name" =>$module));
		}
	}

	public function delete()
	{	
		$result = $this->tableGateway->delete();
	}
}