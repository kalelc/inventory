<?php 
namespace Process\Model;

use Application\Db\TableGateway;
use Zend\Db\Sql\Select;

class DetailsReceiveInventoryTable
{
	protected $tableGateway;
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

	public function searchSerial($serial)
	{
		$select = new Select($this->tableGateway->getTable());
		$select->columns(array());
		$select->join("products", "products.id = ".$this->tableGateway->getTable().".product", array('id' => 'id'));
		$select->join("products_receive_inventory", "products_receive_inventory.details_receive_inventory = ".$this->tableGateway->getTable().".id", array('serial' => 'serial'));
		$select->where(array("products_receive_inventory.status" => 0));
		$select->where->like("products_receive_inventory.serial","%".$serial."%");

		error_log($select->getSqlString());

		$result = $this->tableGateway->selectWith($select);
		return $result;
	}

	public function save(DetailsReceiveInventory $detailsReceiveInventory)
	{

		$data = array(
			'receive_inventory' => $detailsReceiveInventory->getReceiveInventory(),
			'cost' => $detailsReceiveInventory->getCost(),
			'iva' => $detailsReceiveInventory->getIva(),
			'product' => $detailsReceiveInventory->getProduct(),
			'manifest_file' => $detailsReceiveInventory->getManifestFile(),
			'register_date'			=> date("Y-m-d H:i:s", time()),
			'update_date' 			=> date("Y-m-d H:i:s", time()),
			);

		$id = (int)$detailsReceiveInventory->getId();

		if ($id == 0) {
			$this->tableGateway->insert($data);
			$id = $this->tableGateway->getLastInsertValue();
			
			if($id) {
				return $id;
			}
			else
				return false;
			
		} else {
			if ($this->get($id)) {
				$this->tableGateway->update($data, array('id' => $id));
				return $id;
			} else {
				return false;
			}
		}
	}

	public function delete($id)
	{	
		$result = $this->tableGateway->delete(array('id' => $id));

		if($result) {
			return true;
		}
		else
			return false;
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