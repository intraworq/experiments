<?php
//obsługa ACL
$adapter = new JeremyKendall\Slim\Auth\Adapter\Db\PdoAdapter(
	$app->db, 'users', 'username', 'password', new \JeremyKendall\Password\PasswordValidator()
);

$authBootstrap = new \JeremyKendall\Slim\Auth\Bootstrap($app, $adapter, $app->acl);
$authBootstrap->setStorage($app->session);
$authBootstrap->bootstrap();

$app->acl->setPerm();

