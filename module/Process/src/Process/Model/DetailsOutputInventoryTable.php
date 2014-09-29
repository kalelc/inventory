<?php 
namespace Process\Model;

use Application\Db\TableGateway;
use Zend\Db\Sql\Select;

class DetailsOutputInventoryTable
{
	protected $tableGateway;
	protected $eventManager;
	protected $cache;
	protected $productTable;
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

	public function get($outputInventoryId,$id = false)
	{
		$select = new Select($this->tableGateway->getTable());
		$select->join('output_inventory', "output_inventory.id = ".$this->tableGateway->getTable().".output_inventory", array(), 'inner');
		$select->where(array($this->tableGateway->getTable().".output_inventory" => $outputInventoryId));

		if($id) {
			$select->where(array($this->tableGateway->getTable().".id" => $id));
			$resultSet = $this->tableGateway->selectWith($select);
			$result = $resultSet->current();
			$product = $this->productTable->getName(false,$result->getProduct());
			$result->setProduct(implode($product));
		}
		else {
			$resultSet = $this->tableGateway->selectWith($select);

			$result = array();

			foreach($resultSet as $rows) {
				$product = $this->productTable->getName(false,$rows->getProduct());
				$rows->setProduct(implode($product));
				
				switch($rows->getIva()) {
					case 1 :
					$rows->setIvaAccumulated($rows->getCost() - ($rows->getCost() / 1.16));
					$rows->setCost($rows->getCost() / 1.16);
					break ;
					case 2 :
					$rows->setIvaAccumulated($rows->getCost() * 0.16);
					break ;
					case 3 :
					$rows->setIvaAccumulated(0);
					break ;
				}
				$result[] = $rows;
			}
		}
		return $result;
	}

	public function save(DetailsOutputInventory $detailsOutputInventory)
	{
		$data = array(
			'output_inventory' => $detailsOutputInventory->getOutputInventory(),
			'product' => $detailsOutputInventory->getProduct(),
			'cost' => $detailsOutputInventory->getCost(),
			'iva' => $detailsOutputInventory->getIva(),
			'serial' => $detailsOutputInventory->getSerial(),
			'register_date'			=> date("Y-m-d H:i:s", time()),
			'update_date' 			=> date("Y-m-d H:i:s", time()),
			);

		$id = (int)$detailsOutputInventory->getId();
		
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

	public function getEventManager()
	{
		return $this->eventManager;
	}

	public function setEventManager($eventManager)
	{
		$this->eventManager = $eventManager;
		return $this;
	}

	public function getCache()
	{
		return $this->cache;
	}

	public function setCache($cache)
	{
		$this->cache = $cache;
		return $this;
	}

	public function getProductTable()
	{
		return $this->productTable;
	}

	public function setProductTable($productTable)
	{
		$this->productTable = $productTable;

		return $this;
	}

	public function calculateIva($cost,$opt)
	{

	}
}