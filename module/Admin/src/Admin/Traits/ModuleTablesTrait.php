<?php
namespace Admin\Traits;

Trait ModuleTablesTrait
{
	public function getBankTable()
	{
		$bankTable = "";
		if (!$bankTable) {
			$bankTable = $this->getServiceLocator()->get('Admin\Model\BankTable');
		}
		return $bankTable;
	}

	public function getBrandTable()
	{
		$brandTable = "";
		if (!$brandTable) {
			$brandTable = $this->getServiceLocator()->get('Admin\Model\BrandTable');
		}
		return $brandTable;
	}

	public function getCategorySerialNameTable()
	{
		$categorySerialNameTable = "";
		if(!$categorySerialNameTable){
			$categorySerialNameTable = $this->getServiceLocator()->get('Admin\Model\CategorySerialNameTable') ;
		}
		return $categorySerialNameTable;
	}

	public function getCategorySpecificationTable()
	{
		$categorySpecificationTable = "";
		if(!$categorySpecificationTable) {
			$categorySpecificationTable = $this->getServiceLocator()->get('Admin\Model\CategorySpecificationTable') ;
		}
		return $categorySpecificationTable;
	}

	public function getCategoryTable()
	{
		$categoryTable = "";
		if(!$categoryTable){
			$categoryTable = $this->getServiceLocator()->get('Admin\Model\CategoryTable') ;
		}
		return $categoryTable;
	}

	public function getListPriceTable()
	{
		$listPriceTable = "";
		if(!$listPriceTable){
			$listPriceTable = $this->getServiceLocator()->get('Admin\Model\ListPriceTable');
		}
		return $listPriceTable;

	}

	public function getMasterCategoryTable()
	{
		$masterCategoryTable = "";
		if (!$masterCategoryTable) {
			$masterCategoryTable = $this->getServiceLocator()->get('Admin\Model\masterCategoryTable');
		}
		return $masterCategoryTable;
	}

	public function getMeasureTable()
	{
		$measureTable ="";
		if (!$measureTable) {
			$measureTable = $this->getServiceLocator()->get('Admin\Model\MeasureTable');
		}
		return $measureTable;
	}

	public function getPaymentMethodTable()
	{
		$paymentMethodTable ="";
		if (!$paymentMethodTable) {
			$paymentMethodTable = $this->getServiceLocator()->get('Admin\Model\PaymentMethodTable');
		}
		return $paymentMethodTable;
	}

	public function getProductTable()
	{
		$productTable = "";
		if (!$productTable) {
			$productTable = $this->getServiceLocator()->get('Admin\Model\ProductTable');
		}
		return $productTable;
	}

	public function getSerialNameTable()
	{
		$serialNameTable = "";
		if (!$serialNameTable) {
			$serialNameTable = $this->getServiceLocator()->get('Admin\Model\SerialNameTable');
		}
		return $serialNameTable;
	}

	public function getSpecificationTable()
	{
		$specificationTable = "";
		if (!$specificationTable) {
			$specificationTable = $this->getServiceLocator()->get('Admin\Model\SpecificationTable');
		}
		
		return $specificationTable;
	}

	public function getMeasureTypeTable()
	{
		$measureTypeTable = "";
		if (!$measureTypeTable) {
			$measureTypeTable = $this->getServiceLocator()->get('Admin\Model\MeasureTypeTable');
		}
		return $measureTypeTable;
	}

	public function getSpecificationMasterTable()
	{
		$specificationMasterTable = "";
		if (!$specificationMasterTable) {
			$specificationMasterTable = $this->getServiceLocator()->get('Admin\Model\SpecificationMasterTable');
		}
		return $specificationMasterTable;
	}

	public function getProductMeasureTable()
	{
		$productMeasureTable = "";
		if(!$productMeasureTable) {
			$productMeasureTable = $this->getServiceLocator()->get('Admin\model\ProductMeasureTable');
		}
		return $productMeasureTable;
	}

	public function getAppTable()
	{
		$appTable = "";
		if(!$appTable) {
			$appTable = $this->getServiceLocator()->get('Admin\model\AppTable');
		}
		return $appTable;
	}
}
?>