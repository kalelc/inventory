<?php
return array(
	'resources' => array(
		"admin" => array(
			"app" => array("list", "create", "edit", "remove"),
			"brand" => array("list", "create", "edit", "remove"),
			"bank" => array("list", "create", "edit", "remove"),
			"category" => array("list", "create", "edit", "remove"),
			"classification" => array("list", "create", "edit", "remove"),
			"customer" => array("list", "create", "edit", "remove"),
			"list_price" => array("list", "create", "edit", "remove"),
			"master_category" => array("list", "create", "edit", "remove"),
			"measure_type" => array("list", "create", "edit", "remove"),
			"measure" => array("list", "create", "edit", "remove"),
			"payment_method" => array("list", "create", "edit", "remove"),
			"product" => array("list", "create", "edit", "remove"),
			"serial_name" => array("list", "create", "edit", "remove"),
			"specification" => array("list", "create", "edit", "remove"),
			"specification_master" => array("list", "create", "edit", "remove"),
			"user_type" => array("list", "create", "edit", "remove"),
			),
		"security" => array(
			"rol" => array("create", "edit", "remove"),
			"user" => array("create", "edit", "remove"),
			),
		"process" => array(
			"receive_inventory" => array("create"),
			//"out_inventory" => array( "create")
			)
		)
);
?>