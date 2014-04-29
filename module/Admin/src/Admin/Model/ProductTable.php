<?php 
namespace Admin\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class ProductTable
{

	public function __construct(TableGateway $tableGateway)
	{
		$this->tableGateway = $tableGateway;
	}

	public function fetchAll()
	{

		$select = new Select($this->tableGateway->getTable());
		$select->join('categories', "categories.id = ".$this->tableGateway->getTable().".category", array('category_name' => 'singular_name'), 'inner');
		$select->join('brands', "brands.id = ".$this->tableGateway->getTable().".brand", array('brand_name' => 'name'), 'inner');
		$rows = $this->tableGateway->selectWith($select);
		return $rows;

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

	public function save(Product $product)
	{
		$data = array(
			'upc_bar_code' 			=> $product->getUpcBarCode(),
			'model' 				=> $product->getModel(),
			'brand' 				=> $product->getBrand(),
			'category' 				=> $product->getCategory(),
			'part_no' 				=> $product->getPartNo(),
			'price' 				=> $product->getPrice(),
			'iva' 					=> $product->getIva(),
			'qty_low' 				=> $product->getQtyLow(),
			'qty_buy' 				=> $product->getQtyBuy(),
			'description' 			=> $product->getDescription(),
			'specification_file' 	=> $product->getSpecificationFile(),
			'image1'				=> $product->getImage1(),
			'image2'				=> $product->getImage2(),
			'image3'				=> $product->getImage3(),
			'image4'				=> $product->getImage4(),
			'image5'				=> $product->getImage5(),
			'image6'				=> $product->getImage6(),
			'manual_file'			=> $product->getManualFile(),
			'video' 				=> $product->getVideo(),
			'status' 				=> $product->getStatus(),
			'register_date'			=> date("Y-m-d H:i:s", time()),
			'update_date' 			=> date("Y-m-d H:i:s", time()),
			);

		$id = (int)$product->getId();
		if ($id == 0) {
			$this->tableGateway->insert($data);
			$id = $this->tableGateway->getLastInsertValue();
		} else {
			if ($this->get($id)) {
				unset($data['register_date']);
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