<?php 
namespace Admin\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class MeasureTable
{
	protected $tableGateway;

	public function __construct(TableGateway $tableGateway)
	{
		$this->tableGateway = $tableGateway;
	}

	public function fetchAll()
	{

		$select = new select();
		$select->from('measures');
		$select->join('measures_types','measures_types.id = measures.measure_type', array('mt_name' => 'name'));
		$select->join('specifications','specifications.id = measures.specification', array('s_name' => 'name'));
		
		$resultSet = $this->tableGateway->selectWith($select);
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

	public function getBySpecification($specification)
	{
		$select = new select();
		$select->from('measures');
		$select->join('measures_types','measures_types.id = measures.measure_type', array('mt_name' => 'name'));
		$select->join('specifications','specifications.id = measures.specification', array('s_name' => 'name'));
		$select->where(array($this->tableGateway->getTable().'.specification' => $specification));
		
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet ? $resultSet : false ;
	}

	public function save(measure $measure)
	{
		$data = array(
			'specification' => $measure->getSpecification(),
			'measure_type' => $measure->getMeasureType(),
			'measure_value' => $measure->getMeasureValue(),
			'image' => $measure->getImage(),
			'meaning' => $measure->getMeaning(),
			'general_information' => $measure->getGeneralInformation(),
			);

		$id = (int)$measure->getId();
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