<?php

$adapter = new JeremyKendall\Slim\Auth\Adapter\Db\PdoAdapter(
	$app->db, 'users', 'username', 'password', new \JeremyKendall\Password\PasswordValidator()
);
$acl = new \IntraworQ\ACL\Acl();
$authBootstrap = new \JeremyKendall\Slim\Auth\Bootstrap($app, $adapter, $acl);
$authBootstrap->bootstrap();
$app->add(new \Slim\Middleware\SessionCookie());
