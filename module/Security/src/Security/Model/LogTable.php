<?php 
namespace Security\Model;

use Application\Db\TableGateway;

class LogTable
{

	public function __construct(TableGateway $tableGateway)
	{
		$this->tableGateway = $tableGateway;
	}

	public function fetchAll()
	{
		$resultSet = $this->tableGateway->select();
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

	public function save(Log $log)
	{
		$data = array(
			'table' => $log->getTable(),
			'table_id' => $log->getTableId(),
			'operation' => $log->getOperation(),
			'user' => $log->getUser(),
			'timestamp' => date("Y-m-d H:i:s", time()),
		);

		$id = (int)$log->getId();
		if ($id == 0) {
			$this->tableGateway->insert($data);
			return true;
		} else {
			return false;
		}
	}
}