<?php 
namespace Process\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class DetailsReceiveInventoryTable
{
	protected $tableGateway;
	protected $eventManager;
	protected $cache;
	protected $productTable;

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

	public function get($receiveInventoryId,$id = false)
	{
		$select = new Select($this->tableGateway->getTable());
		$select->join('receive_inventory', "receive_inventory.id = ".$this->tableGateway->getTable().".receive_inventory", array(), 'inner');
		$select->where(array($this->tableGateway->getTable().".receive_inventory" => $receiveInventoryId));

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

	public function save(DetailsReceiveInventory $detailsReceiveInventory)
	{

		if($detailsReceiveInventory->getIva()==1) {
			$detailsReceiveInventory->setCost($detailsReceiveInventory->getCost() - ($detailsReceiveInventory->getCost() / 1.16));
		}

		$data = array(
			'receive_inventory' => $detailsReceiveInventory->getReceiveInventory(),
			'cost' => $detailsReceiveInventory->getCost(),
			'iva' => $detailsReceiveInventory->getIva(),
			'product' => $detailsReceiveInventory->getProduct(),
			'qty' => $detailsReceiveInventory->getQty(),
			'serials' => $detailsReceiveInventory->getSerials(),
			'manifest_file' => $detailsReceiveInventory->getManifestFile(),
			);

		$id = (int)$detailsReceiveInventory->getId();
		if ($id == 0) {
			$this->tableGateway->insert($data);
			$id = $this->tableGateway->getLastInsertValue();
			return $id;

		} else {
			return false;
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