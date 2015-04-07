<?php 


return [
	'IntraworQ\Models\User' => DI\object()->constructor('dupa'),
	'\IntraworQ\Controllers\Main' => DI\object()
		->constructor(DI\Link('App')),
	'\IntraworQ\Controllers\User' => DI\object()
		->constructor(DI\Link('App'), DI\Link('IntraworQ\Models\User')),
	'\IntraworQ\Controllers\Ajax' => DI\object()
		->constructor(DI\Link('App'))
];