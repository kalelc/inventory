<?php 
namespace Security\Model;

use Application\Db\TableGateway;

class ModuleRolTable
{
	private $tableGateway;
	private $eventManager;

	public function __construct(TableGateway $tableGateway)
	{
		$this->tableGateway = $tableGateway;
	}

	public function fetchAll($rol)
	{
		$resultSet = $this->tableGateway->select(array("rol" => $rol));
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

	public function save($rol,$resources)
	{	
		foreach($resources as  $resource) {
			$this->tableGateway->insert(array("rol" => $rol,"module" => $resource));
		}
	}

	public function delete()
	{	
		$result = $this->tableGateway->delete();
	}
}