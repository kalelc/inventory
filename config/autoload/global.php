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
	);
