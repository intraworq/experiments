<?php
/*
 * This file is part of the DebugBar package.
 *
 * (c) 2013 Maxime Bouroumeau-Fuseau
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace DebugBar\Bridge;
use DebugBar\DataCollector\DataCollectorInterface;
use DebugBar\DataCollector\MessagesAggregateInterface;
use DebugBar\DataCollector\Renderable;

class Log4PhpCollector extends LoggerAppender implements DataCollectorInterface, Renderable, MessagesAggregateInterface
{
    protected $name;
    protected $records = array();
    /**
     * Logger
     */
    protected $logger;

    /**
     * @param Logger $logger
     * @param int $level
     * @param boolean $bubble
     * @param string $name
     */
    public function __construct(Logger $logger, $name = 'log4php')
    {
        parent::__construct($name);
        $this->logger = $logger;
        $this->logger->addAppender($this);
    }

    /**
     * Log4PHP
     */
    public function append(LoggerLoggingEvent $event) {
		$this->records[] = array(
            'message' => $event->getMessage(),
            'is_string' => true,
            'label' => $event->getLevel(),
            'time' => $event->getTime()
        );
	}

	public function getMessages()
    {
        return $this->records;
    }

    public function collect()
    {
        return array(
            'count' => count($this->records),
            'records' => $this->records
        );
    }
    
    public function getName()
    {
        return $this->name;
    }
    public function getWidgets()
    {
        $name = $this->getName();
        return array(
            $name => array(
                "icon" => "suitcase",
                "widget" => "PhpDebugBar.Widgets.MessagesWidget",
                "map" => "$name.records",
                "default" => "[]"
            ),
            "$name:badge" => array(
                "map" => "$name.count",
                "default" => "null"
            )
        );
    }
}