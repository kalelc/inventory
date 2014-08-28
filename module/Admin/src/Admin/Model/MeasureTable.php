<?php 
namespace Admin\Model;

use Application\Db\TableGateway;
use Zend\Db\Sql\Select;

class MeasureTable
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
		$select->from('measures');
		$select->join('measures_types','measures_types.id = measures.measure_type', array('mt_name' => 'name'),$select::JOIN_LEFT);
		$select->join('specifications','specifications.id = measures.specification', array('s_name' => 'name'));
		
		$resultSet = $this->tableGateway->selectWith($select);
		$rows = $resultSet->buffer();
		return $rows ? $rows : false ;
	}

	public function getByCategory($category)
	{
		$select = new Select($this->tableGateway->getTable());
		$select->columns(array('id' => 'id','measure_value'=> 'measure_value'/*,'image'=> 'image'*/));
		$select->join('measures_types','measures_types.id = '.$this->tableGateway->getTable().'.measure_type', array('mt_name' => 'name'),$select::JOIN_LEFT);
		$select->join('specifications', $this->tableGateway->getTable().".specification = specifications.id", array(), 'inner');
		$select->join('categories_specifications', "categories_specifications.specification = specifications.id", array(), 'inner');
		$select->order('categories_specifications.order');
		$select->where(array('categories_specifications.category' => $category));

		$result = $this->tableGateway->selectWith($select);

		return $result ? $result : false;
	}

	public function getByProduct($product)
	{
		$select = new Select($this->tableGateway->getTable());
		$select->columns(array("id" => "id","measure_value" => "measure_value", "image" => "image"));
		$select->join('products_measures','products_measures.measure = '.$this->tableGateway->getTable().'.id', array(), 'inner');
		$select->join('measures_types','measures_types.id = '.$this->tableGateway->getTable().'.measure_type', array('mt_name' => 'name'),$select::JOIN_LEFT);
		$select->join('specifications', $this->tableGateway->getTable().".specification = specifications.id", array('s_name' => 'name','image' => 'image','specification' => 'id'), 'inner');
		$select->join('categories_specifications', "categories_specifications.specification = specifications.id", array(), 'inner');
		$select->order('categories_specifications.order');
		$select->where(array("products_measures.product" => $product));

		$rows = $this->tableGateway->selectWith($select);
		return $rows ? $rows : false ;
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
		$select->join('measures_types','measures_types.id = measures.measure_type', array('mt_name' => 'name'),$select::JOIN_LEFT);
		$select->join('specifications','specifications.id = measures.specification', array('s_name' => 'name'));
		$select->where(array($this->tableGateway->getTable().'.specification' => $specification));
		
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet ? $resultSet : false ;
	}

	public function save(measure $measure)
	{
		if($measure->getMeasureType()==false)
			$measure->setMeasureType(NULL);
		
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