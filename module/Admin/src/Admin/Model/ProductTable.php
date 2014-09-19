<?php 
namespace Admin\Model;

use Application\Db\TableGateway;
use Zend\Db\Sql\Select;

class ProductTable
{

	public function __construct(TableGateway $tableGateway,TableGateway $measureTableGateway)
	{
		$this->tableGateway = $tableGateway;
		$this->featureSet = $this->tableGateway->getFeatureSet()->getFeatureByClassName('Zend\Db\TableGateway\Feature\EventFeature');
		$this->measureTableGateway = $measureTableGateway;
	}

	public function fetchAll()
	{

		$select = new Select($this->tableGateway->getTable());
		$select->join('categories', "categories.id = ".$this->tableGateway->getTable().".category", array('category_name' => 'singular_name'), 'inner');
		$select->join('brands', "brands.id = ".$this->tableGateway->getTable().".brand", array('brand_name' => 'name'), 'inner');
		$rows = $this->tableGateway->selectWith($select);
		$rows->buffer();
		return $rows;
	}

	public function getName($upcBarCode = false, $id = false)
	{
		$select = new Select($this->tableGateway->getTable());
		$select->columns(array('model','id'));
		$select->join('categories', "categories.id = ".$this->tableGateway->getTable().".category", array('category_name' => 'singular_name'));
		$select->join('brands', "brands.id = ".$this->tableGateway->getTable().".brand", array('brand_name' => 'name'));
		
		if($upcBarCode)
			$select->where(array($this->tableGateway->getTable().".upc_bar_code"=> $upcBarCode));
		if($id)
			$select->where(array($this->tableGateway->getTable().".id"=> $id));

		$rows = $this->tableGateway->selectWith($select);


		$productList = array();

		foreach($rows as $row) {
			$measureValue = "" ;
			$measureValue .= " ".$row->getCategoryName() ;
			$measureValue .= " ".$row->getBrandName() ;
			$measureValue .= " ".$row->getModel() ;

			$selectMeasures = new Select($this->measureTableGateway->getTable());
			$selectMeasures->columns(array("measure_value" => "measure_value", "image" => "image"));
			$selectMeasures->join('products_measures','products_measures.measure = measures.id', array(), 'inner');
			$selectMeasures->join('measures_types','measures_types.id = measures.measure_type', array('mt_name' => 'name','mt_abbreviation' => 'abbreviation'),$selectMeasures::JOIN_LEFT);
			$selectMeasures->join('specifications', "measures.specification = specifications.id", array('s_name' => 'name'), 'inner');
			$selectMeasures->join('categories_specifications', "categories_specifications.specification = specifications.id", array(), 'inner');
			$selectMeasures->order('categories_specifications.order');
			$selectMeasures->where(array("products_measures.product" => $row->getId(),"categories_specifications.name" => 1));

			$measureRows = $this->measureTableGateway->selectWith($selectMeasures);

			foreach ($measureRows as $measureRow) {

				if($measureRow->getMeasureTypeAbbreviation()!=false) {
					$measureValue .= " ".$measureRow->getMeasureValue();
					$measureValue .= " ".$measureRow->getMeasureTypeAbbreviation();
				}
				else
					$measureValue .= " ".$measureRow->getMeasureValue();
			}
			$productList[$row->getId()] = $measureValue;
		}

		return $productList;
	}

	public function getBySerial($serial)
	{
		$select = new Select($this->tableGateway->getTable());
		$select->columns(array('model','id'));
		$select->join('categories', "categories.id = ".$this->tableGateway->getTable().".category", array('category_name' => 'singular_name'));
		$select->join('brands', "brands.id = ".$this->tableGateway->getTable().".brand", array('brand_name' => 'name'));
		$select->join("details_receive_inventory", "details_receive_inventory.product = ".$this->tableGateway->getTable().".id", array());
		$select->where->like("details_receive_inventory.serials","%".$serial."%");

		$rows = $this->tableGateway->selectWith($select);

		$productList = array();

		foreach($rows as $row) {
			$measureValue = "" ;
			$measureValue .= " ".$row->getCategoryName() ;
			$measureValue .= " ".$row->getBrandName() ;
			$measureValue .= " ".$row->getModel() ;

			$selectMeasures = new Select($this->measureTableGateway->getTable());
			$selectMeasures->columns(array("measure_value" => "measure_value", "image" => "image"));
			$selectMeasures->join('products_measures','products_measures.measure = measures.id', array(), 'inner');
			$selectMeasures->join('measures_types','measures_types.id = measures.measure_type', array('mt_name' => 'name','mt_abbreviation' => 'abbreviation'),$selectMeasures::JOIN_LEFT);
			$selectMeasures->join('specifications', "measures.specification = specifications.id", array('s_name' => 'name'), 'inner');
			$selectMeasures->join('categories_specifications', "categories_specifications.specification = specifications.id", array(), 'inner');
			$selectMeasures->order('categories_specifications.order');
			$selectMeasures->where(array("products_measures.product" => $row->getId(),"categories_specifications.name" => 1));

			$measureRows = $this->measureTableGateway->selectWith($selectMeasures);

			foreach ($measureRows as $measureRow) {

				if($measureRow->getMeasureTypeAbbreviation()!=false) {
					$measureValue .= " ".$measureRow->getMeasureValue();
					$measureValue .= " ".$measureRow->getMeasureTypeAbbreviation();
				}
				else
					$measureValue .= " ".$measureRow->getMeasureValue();
			}
			$productList[$row->getId()] = $measureValue;
		}

		return $productList;
	}

	public function get($id)
	{
		$id  = (int) $id;
		$rowset = $this->tableGateway->select(array('id' => $id));
		$row = $rowset->current();
		if (!$row) {
			return false;
		}
		return $row;
	}

	public function getAll()
	{
		$select = new Select($this->tableGateway->getTable());
		$select->join('categories', "categories.id = ".$this->tableGateway->getTable().".category", array('category_name' => 'singular_name'), 'inner');
		$select->join('brands', "brands.id = ".$this->tableGateway->getTable().".brand", array('brand_name' => 'name'), 'inner');
		$rows = $this->tableGateway->selectWith($select);
		
		return $rows;

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
		
		$params = array();
		$params['table'] = $this->tableGateway->getTableName();
		$params['operation'] = 1;
		$params['data'] = json_encode($data);
		
		if ($id == 0) {
			$this->tableGateway->insert($data);
			$id = $this->tableGateway->getLastInsertValue();
			
			if($id) {
				$params['id'] = $id;
				$this->featureSet->getEventManager()->trigger("log.save", $this,$params);
				return $id;
			}
			else
				return false;
			
		} else {
			if ($this->get($id)) {
				$params['id'] = $id;
				$params['operation'] = 2;
				$this->featureSet->getEventManager()->trigger("log.save", $this,$params);
				$this->tableGateway->update($data, array('id' => $id));
				return $id;
			} else {
				return false;
			}
		}
	}

	public function delete($id)
	{	
		$params = array();
		$params['table'] = $this->tableGateway->getTableName();
		$result = $this->tableGateway->delete(array('id' => $id));

		if($result) {
			$params['id'] = $id;
			$params['operation'] = 3;
			$this->featureSet->getEventManager()->trigger("log.save", $this,$params);
			return true;
		}
		else
			return false;
	}
}