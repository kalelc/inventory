<?php 
namespace Process\Model;

use Application\Db\TableGateway;
use Zend\Db\Sql\Select;

class ProductsReceiveInventoryTable
{
	protected $tableGateway;
	protected $eventManager;
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

	public function getBySerial($serial)
	{
		$select = new Select($this->tableGateway->getTable());
		$select->join('details_receive_inventory', "details_receive_inventory.id = ".$this->tableGateway->getTable().".details_receive_inventory", array("product"), 'inner');
		$select->where(array($this->tableGateway->getTable().".serial" => $serial));

		$rowset = $this->tableGateway->selectWith($select);
		$row = $rowset->current();
		if (!$row) {
			return false;
		}
		return $row;

	}

	public function get($id)
	{	
		$id  = (int) $id;
		$rowset = $this->tableGateway->select(array('details_receive_inventory' => $id));
		dumpx($rowset);
		$row = $rowset->current();
		if (!$row) {
			return false;
		}
		return $row;
	}

	public function getSerialList($id)
	{	
		$id  = (int) $id;
		$row = $this->tableGateway->select(array('details_receive_inventory' => $id));

		if (!$row) {
			return false;
		}
		return $row;
	}

	public function update($detailsReceiveInventory,$number,$status = 1) 
	{
		$result = $this->tableGateway->update(array('status' => $status), array(
			'details_receive_inventory' => $detailsReceiveInventory,
			'number' => $number
			));

		if($result) {
			return $result;
		}
		else
			return false;
	}

	public function save(ProductsReceiveInventory $productsReceiveInventory)
	{
		$serialList = $productsReceiveInventory->getSerial();

		foreach($serialList as $index => $serials) {
			foreach($serials as $serial) {
				$this->tableGateway->insert(array(
					'details_receive_inventory' => $productsReceiveInventory->getDetailsReceiveInventory(),
					'number' => ($index +1),
					'serial' => $serial,
					));
			}
		}
		return true;
	}

	public function delete($id)
	{	
		$result = $this->tableGateway->delete(array('details_receive_inventory' => $id));
		return $result;
	}
}