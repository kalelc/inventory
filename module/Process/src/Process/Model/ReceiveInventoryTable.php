<?php 
namespace Process\Model;

use Application\Db\TableGateway;
use Zend\Db\Sql\Select;

class ReceiveInventoryTable
{
	protected $tableGateway;
	protected $eventManager;
	protected $cache;
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
		$id = (int) $id;
		$class = get_class();
		$key = md5($id."_".$class);

		$row = $this->cache->getItem($key);
		if(!$row) {
			$select = new Select($this->tableGateway->getTable());
			$select->columns(array("id","register_date","guide","invoice","invoice_file","observations"));
			$select->join('customers', "customers.id = ".$this->tableGateway->getTable().".customer", array('customer_first_name' => 'first_name','customer_last_name' => 'first_name'), 'inner');
			$select->join(array('shipments' => 'customers'), "shipments.id = ".$this->tableGateway->getTable().".shipment", array('shipment_first_name' => 'first_name',
				'shipment_last_name' => 'last_name',
				'shipment_company' => 'company'), 
			'inner');
			$select->join('payments_methods', "payments_methods.id = ".$this->tableGateway->getTable().".payment_method", array('payment_method_name' => 'name'), 'inner');

			$select->where(array($this->tableGateway->getTable().".id" => $id,$this->tableGateway->getTable().".user" => 1));

			$rowset = $this->tableGateway->selectWith($select);
			$row = $rowset->current();
			$this->cache->addItem($key,$row);
		}

		return $row;
	}

	public function save(ReceiveInventory $receiveInventory)
	{
		
		$data = array(
			'register_date' => date("Y-m-d H:i:s", time()),
			'user' => 1,
			'customer' => $receiveInventory->getCustomer(),
			'payment_method' => $receiveInventory->getPaymentMethod(),
			'shipment' => $receiveInventory->getShipment(),
			'guide' => $receiveInventory->getGuideNumber(),
			'invoice' => $receiveInventory->getInvoice(),
			'invoice_file' => $receiveInventory->getInvoiceFile(),
			'observations' => $receiveInventory->getObservation(),
			);

		$id = (int)$receiveInventory->getId();
		if ($id == 0) {
			$this->tableGateway->insert($data);
			$id = $this->tableGateway->getLastInsertValue();
			$this->get($id);
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
}