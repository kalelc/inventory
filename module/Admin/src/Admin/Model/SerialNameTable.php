<?php 
namespace Admin\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class SerialNameTable
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
			throw new \Exception("Could not find row $id");
		}
		return $row;
	}

	public function getSerialName($category)
	{
		$select = new Select($this->tableGateway->getTable());
		$select->columns(array('id','name'));
		$select->join('categories_serials_name',''.$this->tableGateway->getTable().'.id = categories_serials_name.serial_name', array(), 'inner');

		$rows = $this->tableGateway->selectWith($select);

		$result = array();
		foreach($rows as $row) {
			$result[$row->getId()] = $row->getName();
		}
		return $result ;

	}

	public function save(SerialName $serialName)
	{
		$data = array(
			'name' => $serialName->getName(),
			'description' => $serialName->getDescription(),
			);

		$id = (int)$serialName->getId();
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