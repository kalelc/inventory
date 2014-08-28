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
		$data = array(
			'name' => $bank->getName(),
			'description' => $bank->getDescription(),
			);

		$id = (int)$bank->getId();
		if ($id == 0) {
			$this->tableGateway->insert($data);
			$id = $this->tableGateway->getLastInsertValue();

			
			if($id) {
				$params = array();

				$params['id'] = $id;
				$params['table'] = $this->tableGateway->getTable();
				$params['operation'] = 1;

				$this->featureSet->getEventManager()->trigger("log.save", $this,$params);
				dumpx("exit");
				return true;
			}
			else
				return false;
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
			if($result) {
				//$this->evenManager->trigger("log.save", $this);
			}
		}
		catch(\Exception $e) {
			$result = false;
		}
		return $result;
	}
}