<?php 
namespace Process\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class ReceiveInventoryTable
{
	protected $tableGateway;

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

	public function get($id)
	{
		$id  = (int) $id;
		$rowset = $this->tableGateway->select(array('id' => $id));
		$row = $rowset->current();
		if (!$row) {
			$row = false;
		}
		return $row;
	}

	public function save(ReceiveInventory $receiveInventory)
	{
		$data = array(
			'register_date' => date("Y-m-d H:i:s", time()),
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
			return $this->tableGateway->getLastInsertValue();
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
}