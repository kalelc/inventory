<?php
return array(
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
