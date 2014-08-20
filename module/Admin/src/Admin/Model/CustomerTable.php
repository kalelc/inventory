<?php 
namespace Admin\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class CustomerTable
{
	protected $tableGateway;

	public function __construct(TableGateway $tableGateway)
	{
		$this->tableGateway = $tableGateway;
	}

	public function fetchAll($userType = false)
	{
		$select = new Select($this->tableGateway->getTable());
		$select->join('cities', "cities.id = ".$this->tableGateway->getTable().".city", array('city_name' => 'name'), 'inner');
		$select->join('customers_classifications', "customers_classifications.customer = ".$this->tableGateway->getTable().".id", array(), 'inner');
		$select->join('classifications', "classifications.id = customers_classifications.classification", array(), 'inner');
		$select->join('user_types', "user_types.id = classifications.user_type", array(), 'inner');
		
		if($userType)
			$select->where(array("user_types.id" => $userType));

		$resultSet = $this->tableGateway->selectWith($select);
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

	public function save(Customer $customer)
	{
		$data = array(
			'identification' => $customer->getIdentification(),
			'identification_type' => $customer->getIdentificationType(),
			'first_name' => $customer->getFirstName(),
			'last_name' => $customer->getLastName(),
			'emails' => $customer->getEmails(),
			'addresses' => $customer->getAddresses(),
			'phones' => $customer->getPhones(),
			'zipcode' => $customer->getZipcode(),
			'company' => $customer->getCompany(),
			'manager' => $customer->getManager(),
			'webpage' => $customer->getWebpage(),
			'birthday' => date($customer->getBirthday(), time()),
			'alias' => $customer->getAlias(),
			'description' => $customer->getDescription(),
			'city' => $customer->getCity(),
			);

		$id = (int)$customer->getId();
		if ($id == 0) {
			$this->tableGateway->insert($data);
			$id = $this->tableGateway->getLastInsertValue();
		} else {
			if ($this->get($id)) {
				$this->tableGateway->update($data, array('id' => $id));
			} else {
				$id = false;
			}
		}

		return $id;
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