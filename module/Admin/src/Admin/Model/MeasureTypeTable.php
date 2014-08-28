<?php 
namespace Admin\Model;

use Application\Db\TableGateway;

class MeasureTypeTable
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
			throw new \Exception("Could not find row $id");
		}
		return $row;
	}

	public function save(MeasureType $measureType)
	{
		$data = array(
			'name' => $measureType->getName(),
			'abbreviation' => $measureType->getAbbreviation(),
			'description' => $measureType->getDescription(),
			);

		$id = (int)$measureType->getId();
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
		try {
			$result = $this->tableGateway->delete(array('id' => $id));
		}
		catch(\Exception $e) {
			$result = false;
		}
		return $result;
	}
}