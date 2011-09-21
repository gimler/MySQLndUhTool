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
use Symfony\Component\EventDispatcher\Event;

/**
 * Base class for all events
 *
 * @package    MySQLndUhTool
 * @author     Gordon Franke <info@nevalon.de>
 */
abstract class Base extends Event
{
    private $resource;
    private $proxy;

    public function __construct($resource, \MySQLndUhTool\Proxy $proxy) {
        $this->setResource($resource);
        $this->setProxy($proxy);
    }

    public function getResource() {
        return $this->resource;
    }

    public function setResource($resource) {
        $this->resource = $resource;
    }

    public function getProxy() {
        return $this->proxy;
    }

    public function setProxy(\MySQLndUhTool\Proxy $proxy) {
        $this->proxy = $proxy;
    }
}