<?php

/*
 * This file is part of the MySQLndUhTool utility.
 *
 * (c) Gordon Franke <info@nevalon.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MySQLndUhTool\Event;

use MySQLndUhTool\Observer;
use MySQLndUhTool\Event\Base;
use Symfony\Component\EventDispatcher\Event;

/**
 * Query event
 *
 * @package    MySQLndUhTool
 * @author     Gordon Franke <info@nevalon.de>
 */
class Query extends Base
{
    private $query;
    private $time;

    public function __construct($resource, \MySQLndUhTool\Proxy $proxy, $query, $time = null) {
        $this->setQuery($query);
        $this->setTime($time);

        parent::__construct($resource, $proxy);
    }

    public function getQuery() {
        return $this->query;
    }

    public function setQuery($query) {
        $this->query = $query;
    }

    public function getTime() {
        return $this->time;
    }

    public function setTime($time) {
        $this->time = $time;
    }
}