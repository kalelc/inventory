<?php
return array(
	'db' => array(
		'driver' => 'Pdo',
		'dsn' => 'mysql:dbname=kaozend;host=localhost',
		'driver_options' => array(
			PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
			),
		),
	'service_manager' => array(
		'abstract_factories' => array(
			'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
			),
		'factories' => array(
			'Zend\Db\Adapter\Adapter'=> 'Zend\Db\Adapter\AdapterServiceFactory',
			),
		),
	'file_characteristics' => array(
		'image' => array(
			'size' => array('min' => '1KB', 'max' => '2MB'),
			'extension' => array('jpg','jpeg','png','gif'),
			),
		'video' => array(
			'size' => array('min' => '1KB', 'max' => '2MB'),
			'extension' => array('avi','mpg','wmv','mp4'),
			),
		'file' => array(
			'size' => array('min' => '1KB', 'max' => '2MB'),
			'extension' => array('txt','pdf','xls'),
			)
		),
	'main_config_values' => array(
		'cache_ttl' => array(
			'master_slave_best_server' => 360,
			'master_slave_factory_process' => 5
			),
		),
	'pagination' => array(
		'itempage' => 25,
		'pagerange' => 10
		),
	'customers' => array(
		'client' => 1,
		'provider' => 2,
		'transporter' => 3,
		),
	'roles' => array(
		'admin' => 1,
		'user' => 2
		),
	'authentication_codes' => array(
		0 => 'error',                       
		1 => 'ok' ,                     
		-1 => 'identidad invalida',   
		-2 => 'identidad ambigua',   
		-3 => 'contraseña invalida',   
		-4 => 'error desconocido',
		-5 => 'El usuario con el que intenta acceder se encuentra inactivo',       
		-6 => 'La dirección de correo electrónico o la contraseña que introducido no es correcta.',       
		)
	);
