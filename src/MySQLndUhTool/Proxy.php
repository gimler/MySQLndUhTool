<?php

/*
 * This file is part of the MySQLndUhTool utility.
 *
 * (c) Gordon Franke <info@nevalon.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MySQLndUhTool;

use MySQLndUhTool\CloseEvents;
use MySQLndUhTool\ConnectEvents;
use MySQLndUhTool\QueryEvents;
use MySQLndUhTool\Event\Close;
use MySQLndUhTool\Event\Connect;
use MySQLndUhTool\Event\Query;

/**
 * Proxy object to add events to the mysql commands.
 *
 * @package    MySQLndUhTool
 * @author     Gordon Franke <info@nevalon.de>
 */
class Proxy extends \MySQLndUhConnection
{
    /**
     * The event dispatcher instance
     *
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    private $eventDispatcher = null;

    /**
     * The executed queries
     *
     * @var array
     */
    private $queries = array();

    /**
     * Maximum time to execute a query use for QueryEvents::SLOW
     *
     * @var integer 
     */
    protected $maxQueryExecutionTime = 1;

    public function __construct(\Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher) {
        if (!extension_loaded('mysqlnd_uh')) {
            throw new \RuntimeException('mysqlnd_uh extension is not enabled.');
        }

        mysqlnd_uh_set_connection_proxy($this);

        $this->eventDispatcher = $eventDispatcher;
    }

    public function connect($res, $host, $user, $password, $database, $port, $socket, $mysql_flags) {
        $event = new Event\Connect($res, $this, $host, $user, $password, $database, $port, $socket, $mysql_flags);

        $this->eventDispatcher->dispatch(ConnectEvents::PRE_CONNECT, $event);

        $return = parent::connect($res, $event->getHost(), $event->getUser(), $event->getPassword(), $event->getDatabase(), $event->getPort(), $event->getSocket(), $event->getMysqlFlags());

        $this->eventDispatcher->dispatch(ConnectEvents::POST_CONNECT, $event);

        if (false === $return) {
            $this->eventDispatcher->dispatch(ConnectEvents::FAIL, $event);
        }

        return $return;
    }

    public function query($res, $query) {
        $backtrace = debug_backtrace();
#        $file = $backtrace[1]['file'];
#        $line = $backtrace[1]['line'];
#        echo sprintf('%s line %u query `%s`', $file, $line, $query) . PHP_EOL;
        $event = new Event\Query($res, $this, $query);

        // duplicate
        if ($this->hasQuery($query)) {
            $this->eventDispatcher->dispatch(QueryEvents::DUPLICATE, $event);
#            echo sprintf('%s line %u duplicate query `%s`', $file, $line, $query) . PHP_EOL;
        }

        $this->eventDispatcher->dispatch(QueryEvents::PRE_EXECUTION, $event);

        $starttime = microtime(true);
        $return = parent::query($res, $event->getQuery());
        $time = microtime(true) - $starttime;

        $event->setTime($time);

        $this->eventDispatcher->dispatch(QueryEvents::POST_EXECUTION, $event);

        $this->addQuery($query, $backtrace);

        if (false === $return) {
            $this->eventDispatcher->dispatch(QueryEvents::FAIL, $event);
#            echo sprintf('%s line %u error query `%s` failed with `%s (%u)`', $file, $line, $query, $this->getErrorString($res), $this->getErrorNumber($res)) . PHP_EOL;
        } else if ($time > $this->maxQueryExecutionTime) {
            $this->eventDispatcher->dispatch(QueryEvents::SLOW, $event);
#            echo sprintf('%s line %u long query `%s`', $file, $line, $query) . PHP_EOL;
        }

        return $return;
    }

    public function close($res, $close_type) {
        $event = new Event\Close($res, $this, $close_type);

        $this->eventDispatcher->dispatch(CloseEvents::PRE_CLOSE, $event);

        $return = parent::close($event->getResource(), $event->getCloseType());

        $this->eventDispatcher->dispatch(CloseEvents::POST_CLOSE, $event);

        return $return;
    }

    /**
     * Get executed sql queries
     *
     * @return type 
     */
    public function getSqlQueries() {
        return array_keys($this->queries);
    }

    /**
     * Get executed queries with backtrace
     *
     * @return type 
     */
    public function getQueries() {
        return $this->queries;
    }

    /**
     * Get executed queries with backtrace
     *
     * @return type 
     */
    public function getEventDispatcher() {
        return $this->eventDispatcher;
    }

    /**
     * Set the maximum query execution time in seconds
     *
     * @param float $time maximum execution time in seconds
     */
    public function setMaxExecutionQueryTime($time) {
        $this->maxQueryExecutionTime = $time;
    }

    /**
     * Add query to query list
     *
     * @param string $query
     * @param array $backtrace
     */
    protected function addQuery($query, $backtrace) {
        if (!isset($this->queries[$query])) {
            $this->queries[$query] = array($backtrace);
        } else {
            $this->queries[$query][] = $backtrace;    
        }
    }

    /**
     * Check if query was allready executed
     *
     * @param string $query sql query to check
     *
     * @return boolean
     */
    public function hasQuery($query) {
        return isset($this->queries[$query]);
    }

    /**
     * Return number of executed queries
     *
     * @return integer
     */
    public function countExecutedQueries () {
        $i = 0;
        foreach ($this->getQueries() as $backtraces) {
            $i += count($backtraces);
        }

        return $i;
    }

    /**
     * Return number of unique queries
     *
     * @return integer
     */
    public function countUniqueQueries () {
        return count($this->queries);
    }
}