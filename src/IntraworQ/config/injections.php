<?php

return [
	IntraworQ\Controllers\userController::class => DI\object()->constructor(DI\Link('App')),
	IntraworQ\Controllers\notesController::class => DI\object()->constructor(DI\Link('App'))
];
