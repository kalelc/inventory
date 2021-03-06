<?php 
namespace Process\Model;

use Application\Db\TableGateway;
use Zend\Db\Sql\Select;

class ReceiveInventoryTable
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

	public function get($id,$user)
	{	
		$select = new Select($this->tableGateway->getTable());
		$select->columns(array("id","register_date","guide","invoice","invoice_file","observations"));
		$select->join('customers', "customers.id = ".$this->tableGateway->getTable().".customer", array('customer_first_name' => 'first_name','customer_last_name' => 'first_name'), 'inner');
		$select->join(array('shipments' => 'customers'), "shipments.id = ".$this->tableGateway->getTable().".shipment", array('shipment_first_name' => 'first_name',
			'shipment_last_name' => 'last_name',
			'shipment_company' => 'company'), 
		'inner');
		$select->join('payments_methods', "payments_methods.id = ".$this->tableGateway->getTable().".payment_method", array('payment_method_name' => 'name'), 'inner');
		$select->where(array($this->tableGateway->getTable().".id" => $id,$this->tableGateway->getTable().".user" => $user));
		$rowset = $this->tableGateway->selectWith($select);
		$row = $rowset->current();

		return $row;
	}

	public function save(ReceiveInventory $receiveInventory)
	{
		
		$data = array(
			'register_date' => date("Y-m-d H:i:s", time()),
			'user' => $receiveInventory->getUser(),
			'customer' => $receiveInventory->getCustomer(),
			'payment_method' => $receiveInventory->getPaymentMethod(),
			'shipment' => $receiveInventory->getShipment(),
			'guide' => $receiveInventory->getGuideNumber(),
			'invoice' => $receiveInventory->getInvoice(),
			'invoice_file' => $receiveInventory->getInvoiceFile(),
			'observations' => $receiveInventory->getObservation(),
			);

		$id = (int)$receiveInventory->getId();
		
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