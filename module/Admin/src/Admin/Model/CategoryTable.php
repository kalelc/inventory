<?php 
namespace Admin\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class CategoryTable
{
	protected $tableGateway;

	public function __construct(TableGateway $tableGateway)
	{
		$this->tableGateway = $tableGateway;
	}

	public function fetchAll()
	{

		$select = new select();
		$select->from('categories');
		$select->join('master_categories','master_categories.id = categories.master_category', array('master_category_name' => 'name'));
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

	public function save(Category $category)
	{
		$data = array(
				'master_category' => $category->getMasterCategory(),
				'singular_name' => $category->getSingularName(),
				'plural_name' => $category->getPluralName(),
				'image' => $category->getImage(),
				'shipping_cost' => $category->getShippingCost(),
				'additional_shipping' => $category->getAdditionalShipping(),
				'description' => $category->getDescription(),
		);
		
		$id = (int)$category->getId();
		if ($id == 0) {
			$this->tableGateway->insert($data);
			$id = $this->tableGateway->getLastInsertValue();
		} else {
			if ($this->get($id)) {
				$this->tableGateway->update($data, array('id' => $id));
			} else {
				$id = false;
			}
		}
		return $id;
	}

	public function delete($id)
	{
		$this->tableGateway->delete(array('id' => $id));
	}
}