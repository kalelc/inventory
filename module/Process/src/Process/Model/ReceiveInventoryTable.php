<?php 
namespace Process\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class ReceiveInventoryTable
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

	public function get($id)
	{
		$id  = (int) $id;
		$rowset = $this->tableGateway->select(array('id' => $id));
		$row = $rowset->current();
		if (!$row) {
			$row = false;
		}
		return $row;
	}

	public function save(ReceiveInventory $receiveInventory)
	{
		$data = array(
			'name' => $receiveInventory->getName(),
			'image' => $receiveInventory->getImage(),
			'description' => $receiveInventory->getDescription(),
			);

		$id = (int)$receiveInventory->getId();
		if ($id == 0) {
			$this->tableGateway->insert($data);
		} else {
			if ($this->get($id)) {
				$this->tableGateway->update($data, array('id' => $id));
			} else {
				throw new \Exception('Form id does not exist');
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