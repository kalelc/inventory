<?php
return array(
	'controllers' => array(
		'invokables' => array(
			'Process\Controller\ReceiveInventory' 		=> 'Process\Controller\ReceiveInventoryController',
			'Process\Controller\OutputInventory' 		=> 'Process\Controller\OutputInventoryController',
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
										'may_terminate' => true,
										'child_routes' => array(
											'delete' => array(
												'type'    => 'segment',
												'options' => array(
													'route'    => '/delete[/:id]',
													'constraints' => array(
														'id'     => '[0-9]+',
														),
													'defaults' => array(
														'controller' => 'Process\Controller\ReceiveInventory',
														'action'     => 'deleteDetail',
														),
													),
												),
											'search' => array(
												'type'    => 'segment',
												'options' => array(
													'route'    => '/search-serial',
													'constraints' => array(
														'id'     => '[0-9]+',
														),
													'defaults' => array(
														'controller' => 'Process\Controller\ReceiveInventory',
														'action'     => 'searchSerial',
														),
													),
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

'output_inventory' => array(
	'type'    => 'literal',
	'options' => array(
		'route'    => '/output-inventory',
		'defaults' => array(
			'controller' => 'Process\Controller\OutputInventory',
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
					'controller' => 'Process\Controller\OutputInventory',
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
							'controller' => 'Process\Controller\OutputInventory',
							'action'     => 'details',
							),
						),
					'may_terminate' => true,
					'child_routes' => array(
						'delete' => array(
							'type'    => 'segment',
							'options' => array(
								'route'    => '/delete[/:id]',
								'constraints' => array(
									'id'     => '[0-9]+',
									),
								'defaults' => array(
									'controller' => 'Process\Controller\OutputInventory',
									'action'     => 'deleteDetail',
									),
								),
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
					'controller' => 'Process\Controller\OutputInventory',
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