<?php 
namespace Security\Model;

use Application\Db\TableGateway;
use Zend\Db\Sql\Select;

class RolTable
{
	protected $tableGateway;
	protected $featureSet;

	public function __construct(TableGateway $tableGateway)
	{
		$this->tableGateway = $tableGateway;
		$this->featureSet = $this->tableGateway->getFeatureSet()->getFeatureByClassName('Zend\Db\TableGateway\Feature\EventFeature');
	}

	public function fetchAll()
	{
		$select = new Select($this->tableGateway->getTable());
		$select->where("id != 1");
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
	}

	public function get($id)
	{
		$id  = (int) $id;
		if($id != 1){
			$rowset = $this->tableGateway->select(array('id' => $id));
			$row = $rowset->current();
			if (!$row) {
				return false;
			}
			return $row;
		}
		else {
			return false;
		}

	}

	public function save(Rol $rol)
	{
		$data = array(
			'name' => $rol->getName(),
			'description' => $rol->getDescription(),
			);

		$id = (int)$rol->getId();
		if ($id == 0) {
			$this->tableGateway->insert($data);
		} else {
			if ($this->get($id)) {
				$this->tableGateway->update($data, array('id' => $id));
			} else {
				throw new \Exception('Form id does not exist');
			}
		}
	}

	public function delete($id)
	{
		$this->tableGateway->delete(array('id' => $id));
	}
}