<?php 
namespace Admin\Model;

use Application\Db\TableGateway;

class ProductAppTable
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

	public function get($product)
	{
		$product  = (int) $product;
		$resultSet = $this->tableGateway->select(array('product' => $product));

		$rows = array();

		foreach($resultSet  as $result) {
			$rows[] = $result->getApp();
		}
		
		return $rows ? $rows : false ;
	}

	public function save($product,$apps)
	{
		$this->delete($product);
		
		foreach($apps as $app)
			$this->tableGateway->insert(array('product'=>$product,'app' =>$app));
	}

	public function delete($productId)
	{
		$this->tableGateway->delete(array('product' => $productId));
	}
}