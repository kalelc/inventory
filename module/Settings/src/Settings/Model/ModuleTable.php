<?php 
namespace Settings\Model;

use Zend\Db\Sql\Insert;
use Zend\Db\TableGateway\TableGateway;

class ModuleTable
{

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

	public function save()
	{

		$insert = new Insert($this->tableGateway->getTable());
		$insert->columns(array("name","url"));

		$name = array(
			"Bancos",
			"Metodos de Pago",
			"Maestras",
			"Especificaciones",
			"Maestra",
			"Categorias",
			"Tipos de Medidas",
			"Medidas",
			"Nombre de seriales",
			"lista de precios",
			"Marcas",
			"Productos",
			"Roles",
			"Usuarios",
			);
		$url = array(
			"admin/bank",
			"admin/payment_method",
			"admin/specification_master",
			"admin/specification",
			"admin/master_category",
			"admin/category",
			"admin/measure_type",
			"admin/measure",
			"admin/serial_name",
			"admin/list_price",
			"admin/brand",
			"admin/product",
			"security/rol",
			"security/user"
			);

		for($i= 0; $i<count($name);$i++) {
			//$insert->values(array("name" => $name[$i], "url" => $url[$i]));
			//$this->tableGateway->insertWith($insert);
			//$this->tableGateway->insert($data);
			//dump($insert->getSqlString(),"sql");
		}
		dumpx("fin");
	}
}