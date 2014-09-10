<?php 
namespace Security\Model;

use Application\Db\TableGateway;
use Zend\Db\Sql\Select;

class RolTable
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
		$select = new Select($this->tableGateway->getTable());
		$select->where("id != 1");
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
	}

	public function get($id)
	{
		$id  = (int) $id;
		if($id != 1){
			$rowset = $this->tableGateway->select(array('id' => $id));
			$row = $rowset->current();
			if (!$row) {
				return false;
			}
			return $row;
		}
		else {
			return false;
		}

	}

	public function save(Rol $rol)
	{
		$data = array(
			'name' => $rol->getName(),
			'description' => $rol->getDescription(),
			);

		$id = (int)$rol->getId();
		
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