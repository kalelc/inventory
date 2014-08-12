<?php
return array(
	'controllers' => array(
		'invokables' => array(
			'Admin\Controller\PaymentMethod' 		=> 'Admin\Controller\PaymentMethodController',
			'Admin\Controller\Bank' 				=> 'Admin\Controller\BankController',
			'Admin\Controller\MeasureType' 			=> 'Admin\Controller\MeasureTypeController',
			'Admin\Controller\Specification' 		=> 'Admin\Controller\SpecificationController',
			'Admin\Controller\SpecificationMaster' 	=> 'Admin\Controller\SpecificationMasterController',
			'Admin\Controller\SerialName' 			=> 'Admin\Controller\SerialNameController',
			'Admin\Controller\Measure' 				=> 'Admin\Controller\MeasureController',
			'Admin\Controller\MasterCategory' 		=> 'Admin\Controller\MasterCategoryController',
			'Admin\Controller\Category' 			=> 'Admin\Controller\CategoryController',
			'Admin\Controller\ListPrice' 			=> 'Admin\Controller\ListPriceController',
			'Admin\Controller\Brand' 				=> 'Admin\Controller\BrandController',
			'Admin\Controller\Product' 				=> 'Admin\Controller\ProductController',
			'Admin\Controller\App' 					=> 'Admin\Controller\AppController',
			'Admin\Controller\Customer' 			=> 'Admin\Controller\CustomerController',
			'Admin\Controller\UserType'				=> 'Admin\Controller\UserTypeController',
			'Admin\Controller\Classification'		=> 'Admin\Controller\ClassificationController',
			),
),
'router' => array(
	'routes' => array(
		'admin' => array(
			'type' => 'literal',
			'options' => array(
				'route' => '/admin',
				'defaults' => array(
					'controller' => 'Security\Controller\Session',
					'action' => 'index'
					)
				),
			'may_terminate' => true,
			'child_routes' => array(
				'payment_method' => array(
					'type'    => 'segment',
					'options' => array(
						'route'    => '/payment-method[/page/:page][/:action][/:id]',
						'constraints' => array(
							'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
							'id'     => '[0-9]+',
							),
						'defaults' => array(
							'controller' => 'Admin\Controller\PaymentMethod',
							'action'     => 'index',
							),
						),
					),
				'app' => array(
					'type'    => 'segment',
					'options' => array(
						'route'    => '/app[/page/:page][/:action][/:id]',
						'constraints' => array(
							'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
							'id'     => '[0-9]+',
							),
						'defaults' => array(
							'controller' => 'Admin\Controller\App',
							'action'     => 'index',
							),
						),
					),
				'measure_type' => array(
					'type'    => 'segment',
					'options' => array(
						'route'    => '/measure-type[/page/:page][/:action][/:id]',
						'constraints' => array(
							'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
							'id'     => '[0-9]+',
							),
						'defaults' => array(
							'controller' => 'Admin\Controller\MeasureType',
							'action'     => 'index',
							),
						),
					),
				'measure' => array(
					'type'    => 'segment',
					'options' => array(
						'route'    => '/measure[/page/:page][/:action][/:id]',
						'constraints' => array(
							'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
							'id'     => '[0-9]+',
							),
						'defaults' => array(
							'controller' => 'Admin\Controller\Measure',
							'action'     => 'index',
							),
						),
					),
				'bank' => array(
					'type'    => 'segment',
					'options' => array(
						'route'    => '/bank[/page/:page][/:action][/:id]',
						'constraints' => array(
							'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
							'id'     => '[0-9]+',
							),
						'defaults' => array(
							'controller' => 'Admin\Controller\Bank',
							'action'     => 'index',
							),
						),
					),
				'specification' => array(
					'type'    => 'segment',
					'options' => array(
						'route'    => '/specification[/page/:page][/:action][/:id]',
						'constraints' => array(
							'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
							'id'     => '[0-9]+',
							'page'     => '[0-9]+',
							),
						'defaults' => array(
							'controller' => 'Admin\Controller\Specification',
							'action'     => 'index',
							),
						),
					),
				'specification_master' => array(
					'type'    => 'segment',
					'options' => array(
						'route'    => '/specification-master[/page/:page][/:action][/:id]',
						'constraints' => array(
							'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
							'id'     => '[0-9]+',
							),
						'defaults' => array(
							'controller' => 'Admin\Controller\SpecificationMaster',
							'action'     => 'index',
							),
						),
					),
				'serial_name' => array(
					'type'    => 'segment',
					'options' => array(
						'route'    => '/serial-name[/page/:page][/:action][/:id]',
						'constraints' => array(
							'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
							'id'     => '[0-9]+',
							),
						'defaults' => array(
							'controller' => 'Admin\Controller\SerialName',
							'action'     => 'index',
							),
						),
					),
				'category' => array(
					'type'    => 'segment',
					'options' => array(
						'route'    => '/category[/page/:page][/:action][/:id]',
						'constraints' => array(
							'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
							'id'     => '[0-9]+',
							),
						'defaults' => array(
							'controller' => 'Admin\Controller\Category',
							'action'     => 'index',
							),
						),
					),
				'master_category' => array(
					'type'    => 'segment',
					'options' => array(
						'route'    => '/master-category[/page/:page][/:action][/:id]',
						'constraints' => array(
							'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
							'id'     => '[0-9]+',
							),
						'defaults' => array(
							'controller' => 'Admin\Controller\MasterCategory',
							'action'     => 'index',
							),
						),
					),
				'list_price' => array(
					'type'    => 'segment',
					'options' => array(
						'route'    => '/list-price[/page/:page][/:action][/:id]',
						'constraints' => array(
							'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
							'id'     => '[0-9]+',
							),
						'defaults' => array(
							'controller' => 'Admin\Controller\ListPrice',
							'action'     => 'index',
							),
						),
					),
				'brand' => array(
					'type'    => 'segment',
					'options' => array(
						'route'    => '/brand[/page/:page][/:action][/:id]',
						'constraints' => array(
							'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
							'id'     => '[0-9]+',
							),
						'defaults' => array(
							'controller' => 'Admin\Controller\Brand',
							'action'     => 'index',
							),
						),
					),
				'product' => array(
					'type'    => 'segment',
					'options' => array(
						'route'    => '/product[/page/:page][/:action][/:id]',
						'constraints' => array(
							'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
							'id'     => '[0-9]+',
							),
						'defaults' => array(
							'controller' => 'Admin\Controller\Product',
							'action'     => 'index',
							),
						),
					),
				'customer' => array(
					'type'    => 'segment',
					'options' => array(
						'route'    => '/customer[/page/:page][/:action][/:id]',
						'constraints' => array(
							'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
							'id'     => '[0-9]+',
							),
						'defaults' => array(
							'controller' => 'Admin\Controller\Customer',
							'action'     => 'index',
							),
						),
					),
				'customer_modal_add' => array(
					'type'    => 'literal',
					'options' => array(
						'route'    => '/customer/modal/add',
						'defaults' => array(
							'controller' => 'Admin\Controller\Customer',
							'action'     => 'addModal',
							),
						),
					),

				'productSearchSpecifications' => array(
					'type'    => 'segment',
					'options' => array(
						'route'    => '/product/search-specifications',
						'constraints' => array(
							'category'     => '[0-9]+',
							),
						'defaults' => array(
							'controller' => 'Admin\Controller\Product',
							'action'     => 'getSpecificationToCategory',
							),
						),
					),

				'productGetSerialList' => array(
					'type'    => 'literal',
					'options' => array(
						'route'    => '/product/get-serialList',
						'defaults' => array(
							'controller' => 'Admin\Controller\Product',
							'action'     => 'getSerialList',
							),
						),
					),

				'productSearch' => array(
					'type'    => 'literal',
					'options' => array(
						'route'    => '/product/search',
						'defaults' => array(
							'controller' => 'Admin\Controller\Product',
							'action'     => 'productSearch',
							),
						),
					),

				'user_type' => array(
					'type'    => 'segment',
					'options' => array(
						'route'    => '/user-type[/page/:page][/:action][/:id]',
						'constraints' => array(
							'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
							'id'     => '[0-9]+',
							),
						'defaults' => array(
							'controller' => 'Admin\Controller\UserType',
							'action'     => 'index',
							),
						),
					),
				'classification' => array(
					'type'    => 'segment',
					'options' => array(
						'route'    => '/classification[/page/:page][/:action][/:id]',
						'constraints' => array(
							'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
							'id'     => '[0-9]+',
							),
						'defaults' => array(
							'controller' => 'Admin\Controller\Classification',
							'action'     => 'index',
							),
						),
					),
				),
),
),
),

'view_helpers' => array(
	'factories' => array(
		'Requesthelper' => function($sm){
			$helper = new \Admin\View\Helper\Requesthelper;
			$request = $sm->getServiceLocator()->get('Request');
			$helper->setRequest($request);
			return $helper;
		}
		),
	'invokables' => array(
		//'modalHelper' => 'Admin\View\Helper\ModalHelper',
		'barCodeHelper' => 'Admin\View\Helper\BarCodeHelper',
		'layoutHelper' => 'Admin\View\Helper\LayoutHelper',
		),
	),
'view_manager' => array(
	'template_path_stack' => array(
		'admin' => __DIR__ . '/../view',
		),
	'template_map' => array(
		'pagination' => __DIR__ . '/../view/admin/helper/pagination.phtml',
		),
	),
);