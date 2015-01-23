<?php

namespace Lib;

class LoggerAppenderDebugBar extends \LoggerAppenderConsole {

	public $messages = array();

	public function append(\LoggerLoggingEvent $event) {
		parent::append($event);		

		$this->messages[] = [
			'message' => $event->getMessage(),
			'is_string' => true,
			'label' => strtolower($event->getLevel()->toString()),
			'time' => $event->getRelativeTime()
		];
	}

	public function getMessages() {
		return $this->messages;
	}

}
