<?php 
namespace Admin\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;


class CategorySpecificationTable
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

	public function getCategorySpecificationCheckValue($category)
	{

		$select = new Select($this->tableGateway->getTable());
		$select->columns(array('specification'));
		$select->order($this->tableGateway->getTable().'.order');
		$select->join('specifications', "specifications.id = ".$this->tableGateway->getTable().".specification", array('specification_name' => 'name', 'specification_image' => 'image'), 'inner');
		$select->where(array($this->tableGateway->getTable().'.category' => $category));

		$resulset = $this->tableGateway->selectWith($select);
		return $resulset;

		//echo $select->getSqlString()."<br>";
		//echo str_replace('"', '', $select->getSqlString());
		//exit();
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
		$select->order('order');


		$resultSet = $this->tableGateway->selectWith($select);
		$rows = array();

		foreach($resultSet  as $result) {
			$rows[] = $result->getSpecification();
		}

		return $rows ? $rows : false ;
	}

	public function save($category,$specifications)
	{
		$i = 1 ;
		$this->delete($category);
		foreach($specifications as $specification){
			$this->tableGateway->insert(array('category'=>$category,'specification' =>$specification, 'order' => $i));
			$i++;
		}

	}

	public function delete($category)
	{
		$this->tableGateway->delete(array('category' => $category));
	}
}