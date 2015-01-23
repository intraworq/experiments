<?php

namespace Lib;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of log4phpCollector
 *
 * @author mpiekarczyk
 */
class Log4phpCollector extends \DebugBar\DataCollector\MessagesCollector {

	/**
	 * Klasa loggera
	 * @var \Logger
	 */
	private $logger;

	public function __construct(\Logger $logger) {
		parent::__construct('Log4php');
		$this->logger = $logger;
	}


	public function collect() {
		$messages = $this->getMessages();		
        $return = [
            'count' => count($messages),
			'messages' => $messages 
        ];
		
		return $return;
	}

	public function getMessages() {
		/** $appender \Lib\LoggerAppenderDebugBar  **/
		$appender = $this->logger->getParent()->getAppender('console');		
		return $appender->getMessages();		
	}
	
	public function getWidgets() {
		$name = $this->getName();
		return array(
			"$name" => array(
				'icon' => 'list-alt',
				"widget" => "PhpDebugBar.Widgets.MessagesWidget",
				"map" => "$name.messages",
				"default" => "[]"
			),
			"$name:badge" => array(
				"map" => "$name.count",
				"default" => "0"
			)
		);
	}

}
