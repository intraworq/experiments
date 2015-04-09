<?php
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Config\SessionConfig;
use Zend\Session\SessionManager;

//obsługa ACL
$adapter = new JeremyKendall\Slim\Auth\Adapter\Db\PdoAdapter(
	$app->db, 'users', 'username', 'password', new \JeremyKendall\Password\PasswordValidator()
);

$sessionConfig = new SessionConfig();
$sessionConfig->setOptions(array(
	'remember_me_seconds' => 60 * 60 * 24 * 7,
	'name' => 'slim-auth-impl',
));
$sessionManager = new SessionManager($sessionConfig);
$storage = new SessionStorage(null, null, $sessionManager);
$authBootstrap = new \JeremyKendall\Slim\Auth\Bootstrap($app, $adapter, $app->acl);
$authBootstrap->setStorage($storage);
$authBootstrap->bootstrap();

$app->acl->setPerm();
