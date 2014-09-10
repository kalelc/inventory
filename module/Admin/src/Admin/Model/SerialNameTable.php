<?php 
namespace Admin\Model;

use Application\Db\TableGateway;
use Zend\Db\Sql\Select;

class SerialNameTable
{
	protected $tableGateway;
protected $featureSet;

	public function __construct(TableGateway $tableGateway)
	{
		$this->tableGateway = $tableGateway;
		$this->featureSet = $this->tableGateway->getFeatureSet()->getFeatureByClassName('Zend\Db\TableGateway\Feature\EventFeature');
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
		
		$params = array();
		$params['table'] = $this->tableGateway->getTableName();
		$params['operation'] = 1;
		$params['data'] = json_encode($data);
		
		if ($id == 0) {
			$this->tableGateway->insert($data);
			$id = $this->tableGateway->getLastInsertValue();
			
			if($id) {
				$params['id'] = $id;
				$this->featureSet->getEventManager()->trigger("log.save", $this,$params);
				return $id;
			}
			else
				return false;
			
		} else {
			if ($this->get($id)) {
				$params['id'] = $id;
				$params['operation'] = 2;
				$this->featureSet->getEventManager()->trigger("log.save", $this,$params);
				$this->tableGateway->update($data, array('id' => $id));
				return $id;
			} else {
				return false;
			}
		}
	}

	public function delete($id)
	{	
		$params = array();
		$params['table'] = $this->tableGateway->getTableName();
		$result = $this->tableGateway->delete(array('id' => $id));

		if($result) {
			$params['id'] = $id;
			$params['operation'] = 3;
			$this->featureSet->getEventManager()->trigger("log.save", $this,$params);
			return true;
		}
		else
			return false;
	}
}