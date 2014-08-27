<?php 
namespace Admin\Model;

use Zend\Db\TableGateway\TableGateway;


class BankTable
{

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

	public function save(Bank $bank)
	{
		$data = array(
			'name' => $bank->getName(),
			'description' => $bank->getDescription(),
			);

		$id = (int)$bank->getId();
		if ($id == 0) {
			$this->tableGateway->insert($data);
			return true;
		} else {
			if ($this->get($id)) {
				$this->tableGateway->update($data, array('id' => $id));
				return true;
			} else {
				return false;
			}
		}
	}

	public function delete($id)
	{	
		try {
			$result = $this->tableGateway->delete(array('id' => $id));
		}
		catch(\Exception $e) {
			$result = false;
		}
		return $result;
	}
}