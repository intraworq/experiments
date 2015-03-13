<?php 


return [

'\PlanQ\Controllers\Main' => DI\object()
        ->constructor(DI\Link('App')),
'\PlanQ\Controllers\User' => DI\object()
		->constructor(DI\Link('App'), DI\Link('PlanQ\Models\User'))
];