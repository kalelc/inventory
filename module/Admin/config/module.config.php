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
							'route'    => '/payment-method[/:action][/:id]',
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
					'measure_type' => array(
						'type'    => 'segment',
						'options' => array(
							'route'    => '/measure-type[/:action][/:id]',
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
							'route'    => '/measure[/:action][/:id]',
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
							'route'    => '/bank[/:action][/:id]',
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
							'route'    => '/specification[/:action][/:id]',
							'constraints' => array(
								'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
								'id'     => '[0-9]+',
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
							'route'    => '/specification-master[/:action][/:id]',
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
							'route'    => '/serial-name[/:action][/:id]',
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
							'route'    => '/category[/:action][/:id]',
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
							'route'    => '/master-category[/:action][/:id]',
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
							'route'    => '/list-price[/:action][/:id]',
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
							'route'    => '/brand[/:action][/:id]',
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
							'route'    => '/product[/:action][/:id]',
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
		'modalHelper' => 'Admin\View\Helper\ModalHelper',
		),
	),
'view_manager' => array(
	'template_path_stack' => array(
		'admin' => __DIR__ . '/../view',
		),
	),

);