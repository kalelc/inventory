<?php 
namespace Admin\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class ProductMeasureTable
{
	protected $tableGateway;

	public function __construct(TableGateway $tableGateway)
	{
		$this->tableGateway = $tableGateway;
	}

	public function fetchAll()
	{
		$resultSet = $this->tableGateway->select();
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

	public function save($product,$measures)
	{
		$this->delete($product);
		foreach($measures as $measure){
			dump($product);
			dump($measure);
			$this->tableGateway->insert(array('product'=>$product,'measure' =>$measure));
		}

		dumpx("exit");
	}

	public function delete($product)
	{	
		try {
			$result = $this->tableGateway->delete(array('product' => $product));
		}
		catch(\Exception $e) {
			$result = false;
		}
		return $result;
	}
}