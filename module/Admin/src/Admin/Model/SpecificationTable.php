<?php 
namespace Admin\Model;

use Application\Db\TableGateway;
use Zend\Db\Sql\Select;

class SpecificationTable
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

		$select = new select();
		$select->from('specifications');
		$select->join('specifications_masters','specifications_masters.id = specifications.specification_master', array('sm_name' => 'name'));

		$resultSet = $this->tableGateway->selectWith($select);
		$resultSet->buffer();
		return $resultSet ? $resultSet : false ;
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

	public function save(Specification $specification)
	{
		$data = array(
			'name' => $specification->getName(),
			'specification_master' => $specification->getSpecificationMaster(),
			'image' => $specification->getImage(),
			'meaning' => $specification->getMeaning(),
			'general_information' => $specification->getGeneralInformation(),
			);
		
		$id = (int)$specification->getId();
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