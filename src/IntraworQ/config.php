<?php

$config = 
	['pdo' => [
			"dsn" => 'mysql:dbname=slim_mp;host=localhost;port=3306',
			"username" => '',
			"password" => '',
			"options" => []
	],
	'logger' => [
       "appenders"  => [
           "console" => [
               "class"   => "LoggerAppenderConsole",
               "layout"  => [
                   "class"  => "LoggerLayoutPattern",
                   "params" => [
                       "conversionPattern" => "%date %-5level %msg (%F:%L)%n"
                   ]
               ],
               "filters" => [
                   [
                       "class"  => "LoggerFilterStringMatch",
                       "params" => 	[
                       		'stringToMatch' => 'CONFIG',
							'acceptOnMatch' => false,
                       ]
                   ]
               ]
           ],
           "echo"    => [
               "class"   => "LoggerAppenderEcho",
               "layout"  => [
                   "class"  => "LoggerLayoutPattern",
                   "params" => [
                       "conversionPattern" => "%-5level %msg%n"
                   ]
               ],
               "filters" => [
                   [
                       "class"  => "LoggerFilterStringMatch",
                       "params" => [
                       		'stringToMatch' => 'CONFIG',
							'acceptOnMatch' => true,
                       ]
                   ],
                   [
                       "class" => "LoggerFilterDenyAll"
                   ]
               ]
           ]
       ],
       "rootLogger" => [
           "appenders" => [
               "console",
               "echo"
           ]
       ]
   ]
];