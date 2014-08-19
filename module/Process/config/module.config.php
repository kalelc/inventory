<?php
return array(
	'controllers' => array(
		'invokables' => array(
			'Process\Controller\ReceiveInventory' 		=> 'Process\Controller\ReceiveInventoryController',
			),
		),
	'router' => array(
		'routes' => array(
			'process' => array(
				'type' => 'literal',
				'options' => array(
					'route' => '/process',
					'defaults' => array(
						'controller' => 'Security\Controller\Session',
						'action' => 'index'
						)
					),
				'may_terminate' => true,
				'child_routes' => array(
					'receive_inventory' => array(
						'type'    => 'literal',
						'options' => array(
							'route'    => '/receive-inventory',
							'defaults' => array(
								'controller' => 'Process\Controller\ReceiveInventory',
								'action'     => 'add',
								),
							),
						'may_terminate' => true,
						'child_routes' => array(
							'add' => array(
								'type'    => 'literal',
								'options' => array(
									'route'    => '/add',
									'defaults' => array(
										'controller' => 'Process\Controller\ReceiveInventory',
										'action'     => 'add',
										),
									),
								'may_terminate' => true,
								'child_routes' => array(
									'details' => array(
										'type'    => 'literal',
										'options' => array(
											'route'    => '/details',
											'defaults' => array(
												'controller' => 'Process\Controller\ReceiveInventory',
												'action'     => 'details',
												),
											),
										),
									),
								),
							'finish' => array(
								'type'    => 'literal',
								'options' => array(
									'route'    => '/finish',
									'defaults' => array(
										'controller' => 'Process\Controller\ReceiveInventory',
										'action'     => 'finish',
										),
									),
								
								),
							),
						),
					),
				),
			),
		),

	'view_helpers' => array(),
	'view_manager' => array(
		'template_path_stack' => array(
			'process' => __DIR__ . '/../view',
			),
		),
	);