<?php 
namespace Admin\Model;

use Application\Db\TableGateway;
use Zend\Db\Sql\Select;


class CategorySpecificationTable
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

	public function getCategorySpecificationCheckValue($category)
	{

		$select = new Select($this->tableGateway->getTable());
		$select->columns(array('specification','name'));
		$select->order($this->tableGateway->getTable().'.order');
		$select->join('specifications', "specifications.id = ".$this->tableGateway->getTable().".specification", array('specification_name' => 'name', 'specification_image' => 'image'), 'inner');
		$select->where(array($this->tableGateway->getTable().'.category' => $category));

		$resulset = $this->tableGateway->selectWith($select);
		return $resulset;
	}

	public function getCategorySpecificationUncheckValue()
	{

		$select = new Select($this->tableGateway->getTable());
		$select->columns(array('specification'));
		$select->join('specifications', "specifications.id = ".$this->tableGateway->getTable().".specification", array('specification' => 'id','specification_name' => 'name'), 'right');
		$select->where(array($this->tableGateway->getTable().'.specification' => null));

		$resulset = $this->tableGateway->selectWith($select);

		//echo str_replace('"', '', $select->getSqlString());
		//exit();
		return $resulset;

	}

	public function get($category)
	{	
		$category  = (int) $category;
		$select = new Select($this->tableGateway->getTable());
		$select->where(array('category' => $category));
		$select->join('specifications', "specifications.id = ".$this->tableGateway->getTable().".specification", array('specification' => 'id','specification_name' => 'name'), 'right');
		$select->order('order');


		$resultSet = $this->tableGateway->selectWith($select);
		$rows = array();

		foreach($resultSet  as $result) {
			$rows[] = $result->getSpecification();
		}

		return $rows ? $rows : false ;
	}

	public function save($category,$specifications,$names)
	{
		$i = 1 ;

		$this->delete($category);
		foreach($specifications as $key => $specification){

			$name = array_search($specification,$names)!==false ? 1 : 0 ;
			$this->tableGateway->insert(array('category'=>$category,'specification' =>$specification, 'name' => $name, 'order' => $i));
			$i++;
		}

	}

	public function delete($category)
	{
		$this->tableGateway->delete(array('category' => $category));
	}
}