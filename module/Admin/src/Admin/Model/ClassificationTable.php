<?php 
namespace Admin\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class ClassificationTable
{

	public function __construct(TableGateway $tableGateway)
	{
		$this->tableGateway = $tableGateway;
	}

	public function fetchAll()
	{
		$select = new Select($this->tableGateway->getTable());
		$select->join('user_types', "user_types.id = ".$this->tableGateway->getTable().".user_type", array('user_type_name' => 'name'), 'inner');
		$resultSet = $this->tableGateway->selectWith($select);
		$resultSet->buffer();
		return $resultSet;
	}

	public function get($id)
	{
		$id  = (int) $id;
		$rowset = $this->tableGateway->select(array('id' => $id));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row $id");
		}
		return $row;
	}

	public function save(Classification $classification)
	{
		$data = array(
			'name' => $classification->getName(),
			'user_type' => $classification->getUserType(),
			'description' => $classification->getDescription(),
			);

		$id = (int)$classification->getId();
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