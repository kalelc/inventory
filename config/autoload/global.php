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
);
