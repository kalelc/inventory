<?php
namespace Admin;

use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Db\ResultSet\ResultSet;
use Application\Db\TableGateway;
use Zend\EventManager\EventInterface;

use Admin\Model\PaymentMethod;
use Admin\Model\PaymentMethodTable;
use Admin\Model\Bank;
use Admin\Model\BankTable;
use Admin\Model\MeasureType;
use Admin\Model\MeasureTypeTable;
use Admin\Model\Measure;
use Admin\Model\MeasureTable;
use Admin\Model\SpecificationMaster;
use Admin\Model\SpecificationMasterTable;
use Admin\Model\Specification;
use Admin\Model\SpecificationTable;
use Admin\Model\SerialName;
use Admin\Model\SerialNameTable;
use Admin\Model\MasterCategory;
use Admin\Model\MasterCategoryTable;
use Admin\Model\Category;
use Admin\Model\CategoryTable;
use Admin\Model\CategorySerialName;
use Admin\Model\CategorySerialNameTable;
use Admin\Model\ListPrice;
use Admin\Model\ListPriceTable;
use Admin\Model\Brand;
use Admin\Model\BrandTable;
use Admin\Model\Product;
use Admin\Model\ProductTable;
use Admin\Model\CategorySpecification;
use Admin\Model\CategorySpecificationTable;
use Admin\Model\ProductMeasure;
use Admin\Model\ProductMeasureTable;
use Admin\Model\App;
use Admin\Model\AppTable;
use Admin\Model\Note;
use Admin\Model\NoteTable;
use Admin\Model\ProductApp;
use Admin\Model\ProductAppTable;
use Admin\Model\City;
use Admin\Model\CityTable;
use Admin\Model\Customer;
use Admin\Model\CustomerTable;
use Admin\Model\UserType;
use Admin\Model\UserTypeTable;
use Admin\Model\Classification;
use Admin\Model\ClassificationTable;
use Admin\Model\CustomerClassification;
use Admin\Model\CustomerClassificationTable;

use Admin\Form\SpecificationForm;
use Admin\Form\MeasureForm;
use Admin\Form\CategoryForm;
use Admin\Form\ProductForm;
use Admin\Form\CustomerForm;
use Admin\Form\ClassificationForm;

use Admin\Service\FileService;
use Zend\ModuleManager\ModuleManager;

use Application\ConfigAwareInterface;

use Zend\Authentication\AuthenticationService;

class Module
{
	public function getConfig()
	{
		return include __DIR__ . '/config/module.config.php';
	}

	public function getAutoloaderConfig()
	{
		return array(
			'Zend\Loader\StandardAutoloader' => array(
				'namespaces' => array(
					__NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
					),
				),
			);
	}

	public function init(ModuleManager $moduleManager)
	{
		$sharedEvents = $moduleManager->getEventManager()->getSharedManager();
		$sharedEvents->attach(__NAMESPACE__, 'dispatch', function ($e)
		{
			$controller = $e->getTarget();
			$controller->layout('layout/admin');

			$authenticationService = new AuthenticationService();
			if($authenticationService->hasIdentity()){
				$authenticationService->getStorage()->read();
				$identity = $authenticationService->getIdentity();
				$controller->layout()->setVariable("components",$components);
			}
			else {
				$controller->plugin('redirect')->toRoute('security/login');
			}
		}, 100);
	}

	public function getViewHelperConfig()
	{
		return array(
			'factories' => array(
				'modalHelper' => function ($serviceManager) {
					$serviceLocator = $serviceManager->getServiceLocator();
					return new \Admin\View\Helper\ModalHelper($serviceLocator);
				},
				'menuHelper' => function ($serviceManager) {
					$serviceLocator = $serviceManager->getServiceLocator();
					return new \Admin\View\Helper\AuthenticationHelper($serviceLocator);
				}
			),
		);
	}

	public function getServiceConfig()
	{
		return array(
			'factories' => array(
				'Admin\Service\FileService' => function($sm) {
					$fileService = new FileService();
					return $fileService;
				},
				'Admin\Model\PaymentMethodTable' =>  function($sm) {
					$tableGateway = $sm->get('PaymentMethodTableGateway');
					$table = new PaymentMethodTable($tableGateway);
					return $table;
				},
				'PaymentMethodTableGateway' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new PaymentMethod());
					return new TableGateway('payments_methods', $dbAdapter, null, $resultSetPrototype);
				},
				'Admin\Model\BankTable' => function($sm) {
					$tableGateway = $sm->get('BankTableGateway');
					$table = new BankTable($tableGateway);
					return $table;
				},

				'BankTableGateway' => function ($sm) {

					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');

					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new Bank());
					return new TableGateway('banks', $dbAdapter, null, $resultSetPrototype);
				},
				'Admin\Model\MeasureTypeTable' =>  function($sm) {
					$tableGateway = $sm->get('MeasureTypeTableGateway');
					$table = new MeasureTypeTable($tableGateway);
					return $table;
				},
				'MeasureTypeTableGateway' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new MeasureType());
					return new TableGateway('measures_types', $dbAdapter, null, $resultSetPrototype);
				},
				'Admin\Model\MeasureTable' =>  function($sm) {
					$tableGateway = $sm->get('MeasureTableGateway');
					$table = new MeasureTable($tableGateway);
					return $table;
				},
				'MeasureTableGateway' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new Measure());
					return new TableGateway('measures', $dbAdapter, null, $resultSetPrototype);
				},
				'Admin\Model\SpecificationMasterTable' =>  function($sm) {
					$tableGateway = $sm->get('SpecificationMasterTableGateway');
					$table = new SpecificationMasterTable($tableGateway);
					return $table;
				},
				'SpecificationMasterTableGateway' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new SpecificationMaster());
					return new TableGateway('specifications_masters', $dbAdapter, null, $resultSetPrototype);
				},
				'Admin\Model\SpecificationTable' =>  function($sm) {
					$tableGateway = $sm->get('SpecificationTableGateway');
					$table = new SpecificationTable($tableGateway);
					return $table;
				},
				'SpecificationTableGateway' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new Specification());
					return new TableGateway('specifications', $dbAdapter, null, $resultSetPrototype);
				},
				'Admin\Form\SpecificationForm' =>  function($sm) {
					$specificationMasterTable = $sm->get("Admin/Model/SpecificationMasterTable");
					$results = $specificationMasterTable->fetchAll();
					$list = array();

					foreach($results as $result){
						$list[$result->getId()] = $result->getName();
					}

					$form = new SpecificationForm($list);
					return $form;
				},
				'Admin\Model\SerialNameTable' =>  function($sm) {
					$tableGateway = $sm->get('SerialNameTableGateway');
					$table = new SerialNameTable($tableGateway);
					return $table;
				},
				'SerialNameTableGateway' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new SerialName());
					return new TableGateway('serials_name', $dbAdapter, null, $resultSetPrototype);
				},
				'Admin\Form\MeasureForm' =>  function($sm) {
					$specificationTable = $sm->get("Admin/Model/SpecificationTable");
					$specifications = $specificationTable->fetchAll();

					$specificationList = array();
					$measureTypesList = array();

					foreach($specifications as $specification){
						$specificationList[$specification->getId()] = $specification->getName();
					}

					$measureTypeTable = $sm->get("Admin/Model/MeasureTypeTable");
					$measureTypes = $measureTypeTable->fetchAll();

					foreach($measureTypes as $measureType){
						$measureTypesList[$measureType->getId()] = $measureType->getName();
					}

					$form = new MeasureForm($specificationList,$measureTypesList);
					return $form;
				},
				'Admin\Model\MasterCategoryTable' =>  function($sm) {
					$tableGateway = $sm->get('MasterCategoryTableGateway');
					$table = new MasterCategoryTable($tableGateway);
					return $table;
				},
				'MasterCategoryTableGateway' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new MasterCategory());
					return new TableGateway('master_categories', $dbAdapter, null, $resultSetPrototype);
				},
				'Admin\Model\CategoryTable' =>  function($sm) {
					$tableGateway = $sm->get('CategoryTableGateway');
					$table = new CategoryTable($tableGateway);
					return $table;
				},
				'CategoryTableGateway' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new Category());
					return new TableGateway('categories', $dbAdapter, null, $resultSetPrototype);
				},
				'Admin\Form\CategoryForm' =>  function($sm) {
					$masterCategoryTable = $sm->get("Admin/Model/MasterCategoryTable");
					$masterCategories = $masterCategoryTable->fetchAll();
					$masterCategoryList = array();

					foreach($masterCategories as $masterCategory){
						$masterCategoryList[$masterCategory->getId()] = $masterCategory->getName();
					}

					$serialNameTable = $sm->get("Admin/Model/SerialNameTable");
					$serialsName = $serialNameTable->fetchAll();
					$serialNameList = array();

					foreach($serialsName as $serialName){
						$serialNameList[$serialName->getId()] = $serialName->getName();
					}

					$specificationTable = $sm->get("Admin/Model/SpecificationTable");
					$specifications = $specificationTable->fetchAll();
					$specificationList = array();

					foreach($specifications as $specification){
						$specificationList[$specification->getId()] = $specification->getName();
					}

					$form = new CategoryForm($masterCategoryList,$serialNameList,$specificationList);
					return $form;
				},
				'Admin\Model\CategorySerialNameTable' =>  function($sm) {
					$tableGateway = $sm->get('CategorySerialNameTableGateway');
					$table = new CategorySerialNameTable($tableGateway);
					return $table;
				},
				'CategorySerialNameTableGateway' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new CategorySerialName());
					return new TableGateway('categories_serials_name', $dbAdapter, null, $resultSetPrototype);
				},
				'Admin\Model\ProductAppTable' =>  function($sm) {
					$tableGateway = $sm->get('ProductAppTableGateway');
					$table = new ProductAppTable($tableGateway);
					return $table;
				},
				'ProductAppTableGateway' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new ProductApp());
					return new TableGateway('products_app', $dbAdapter, null, $resultSetPrototype);
				},
				'Admin\Model\ListPriceTable' =>  function($sm) {
					$tableGateway = $sm->get('ListPriceTableGateway');
					$table = new ListPriceTable($tableGateway);
					return $table;
				},
				'ListPriceTableGateway' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new ListPrice());
					return new TableGateway('list_prices', $dbAdapter, null, $resultSetPrototype);
				},
				'Admin\Model\BrandTable' =>  function($sm) {
					$tableGateway = $sm->get('BrandTableGateway');
					$table = new BrandTable($tableGateway);
					return $table;
				},
				'BrandTableGateway' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new Brand());
					return new TableGateway('brands', $dbAdapter, null, $resultSetPrototype);
				},
				'Admin\Model\ProductTable' =>  function($sm) {
					$tableGateway = $sm->get('ProductTableGateway');
					$measureTableGateway = $sm->get('MeasureTableGateway');
					$table = new ProductTable($tableGateway,$measureTableGateway);
					return $table;
				},
				'ProductTableGateway' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new Product());
					return new TableGateway('products', $dbAdapter, null, $resultSetPrototype);
				},
				'Admin\Model\ProductMeasureTable' =>  function($sm) {
					$tableGateway = $sm->get('ProductMeasureTableGateway');
					$table = new ProductMeasureTable($tableGateway);
					return $table;
				},
				'ProductMeasureTableGateway' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new ProductMeasure());
					return new TableGateway('products_measures', $dbAdapter, null, $resultSetPrototype);
				},
				'Admin\Form\ProductForm' =>  function($sm) {
					$brandTable = $sm->get("Admin/Model/BrandTable");
					$brands = $brandTable->fetchAll();
					$brandList = array();

					foreach($brands as $brand){
						$brandList[$brand->getId()] = $brand->getName();
					}

					$categoryTable = $sm->get("Admin/Model/CategoryTable");
					$categories = $categoryTable->fetchAll();
					$categoryList = array();

					foreach($categories as $category){
						$categoryList[$category->getId()] = $category->getSingularName();
					}

					$appTable = $sm->get("Admin/Model/AppTable");
					$apps = $appTable->fetchAll();
					$appsList = array();

					foreach($apps as $app){
						$appsList[$app->getId()] = $app->getName();
					}

					$form = new ProductForm($brandList,$categoryList,$appsList);
					return $form;
				},

				'Admin\Model\AppTable' =>  function($sm) {
					$tableGateway = $sm->get('AppTableGateway');
					$table = new AppTable($tableGateway);
					return $table;
				},
				'AppTableGateway' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new App());
					return new TableGateway('apps', $dbAdapter, null, $resultSetPrototype);
				},

				'Admin\Model\CategorySpecificationTable' => function($sm) {
					$categorySpecificationTableGateway = $sm->get("CategorySpecificationTableGateway");
					$table = new CategorySpecificationTable($categorySpecificationTableGateway);
					return $table;
				},
				'CategorySpecificationTableGateway' => function($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new CategorySpecification());
					return new TableGateway('categories_specifications', $dbAdapter, null, $resultSetPrototype);
				},
				'Admin\Model\CustomerTable' => function($sm) {
					$categorySpecificationTableGateway = $sm->get("CustomerTableGateway");
					$table = new CustomerTable($categorySpecificationTableGateway);
					return $table;
				},
				'CustomerTableGateway' => function($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new Customer());
					return new TableGateway('customers', $dbAdapter, null, $resultSetPrototype);
				},
				'Admin\Model\UserTypeTable' => function($sm) {
					$categorySpecificationTableGateway = $sm->get("UserTypeTableGateway");
					$table = new UserTypeTable($categorySpecificationTableGateway);
					return $table;
				},
				'UserTypeTableGateway' => function($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new UserType());
					return new TableGateway('user_types', $dbAdapter, null, $resultSetPrototype);
				},
				'Admin\Model\CityTable' => function($sm) {
					$cityTableGateway = $sm->get("CityTableGateway");
					$table = new CityTable($cityTableGateway);
					return $table;
				},
				'CityTableGateway' => function($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new City());
					return new TableGateway('cities', $dbAdapter, null, $resultSetPrototype);
				},

				'Admin\Model\ClassificationTable' => function($sm) {
					$cityTableGateway = $sm->get("ClassificationTableGateway");
					$table = new ClassificationTable($cityTableGateway);
					return $table;
				},
				'ClassificationTableGateway' => function($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new Classification());
					return new TableGateway('classifications', $dbAdapter, null, $resultSetPrototype);
				},
				'Admin\Form\ClassificationForm' =>  function($sm) {
					$userTypeTable = $sm->get("Admin/Model/UserTypeTable");
					$userTypes = $userTypeTable->fetchAll();
					$userTypesList = array();

					foreach($userTypes as $userType){
						$userTypesList[$userType->getId()] = $userType->getName();
					}
					$form = new ClassificationForm($userTypesList);
					return $form;
				},
				'Admin\Model\CustomerClassificationTable' =>  function($sm) {
					$tableGateway = $sm->get('CustomerClassificationTableGateway');
					$table = new CustomerClassificationTable($tableGateway);
					return $table;
				},
				'CustomerClassificationTableGateway' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new CustomerClassification());
					return new TableGateway('customers_classifications', $dbAdapter, null, $resultSetPrototype);
				},
				'Admin\Model\NoteTable' =>  function($sm) {
					$tableGateway = $sm->get('NoteTableGateway');
					$table = new NoteTable($tableGateway);
					return $table;
				},
				'NoteTableGateway' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new Note());
					return new TableGateway('notes', $dbAdapter, null, $resultSetPrototype);
				},
				'Admin\Form\CustomerForm' =>  function($sm) {
					$cityTable = $sm->get("Admin/Model/CityTable");
					$cities = $cityTable->fetchAll();
					$citiesList = array();

					foreach($cities as $city){
						$citiesList[$city->getId()] = $city->getName();
					}

					$classificationTable = $sm->get("Admin/Model/ClassificationTable");
					$classifications= $classificationTable->fetchAll();
					$classificationList = array();

					foreach($classifications as $classification){
						$classificationList[$classification->getId()] = $classification->getName();
					}

					$form = new CustomerForm($citiesList,$classificationList);
					return $form;
				},
				),
);
}
}