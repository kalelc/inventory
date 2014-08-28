<?php 
namespace Admin\Model;

use Application\Db\TableGateway;
use Zend\Db\Sql\Select;

class ProductMeasureTable
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

	public function getByProduct($product)
	{
		$select = new Select($this->tableGateway->getTable());
		$select->where(array("product" => $product));
		$rows = $this->tableGateway->selectWith($select);

		return $rows ? $rows : false ;
	}

	public function save($product,$measures)
	{
		$this->delete($product);
		foreach($measures as $measure){
			$this->tableGateway->insert(array('product'=>$product,'measure' =>$measure));
		}
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