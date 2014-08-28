<?php 
namespace Admin\Model;

use Application\Db\TableGateway;

class BankTable
{
	private $tableGateway;
	private $eventManager;

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
			return false;
		}
		return $row;
	}

	public function save(Bank $bank)
	{	
		$params = array();
		$params['table'] = $this->tableGateway->getTable();

		$data = array(
			'name' => $bank->getName(),
			'description' => $bank->getDescription(),
			);

		$id = (int)$bank->getId();
		if ($id == 0) {
			$this->tableGateway->insert($data);
			$id = $this->tableGateway->getLastInsertValue();
			
			if($id) {
				$params['id'] = $id;
				$params['operation'] = 1;
				$this->featureSet->getEventManager()->trigger("log.save", $this,$params);
				return true;
			}
			else
				return false;
		} else {
			if ($this->get($id)) {
				$params['id'] = $id;
				$params['operation'] = 2;
				$this->featureSet->getEventManager()->trigger("log.save", $this,$params);
				$this->tableGateway->update($data, array('id' => $id));
				return true;
			} else {
				return false;
			}
		}
	}

	public function delete($id)
	{	
		$params = array();
		$params['table'] = $this->tableGateway->getTable();

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