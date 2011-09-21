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
 * Close event
 *
 * @package    MySQLndUhTool
 * @author     Gordon Franke <info@nevalon.de>
 */
class Close extends Base
{
    private $closeType;

    public function __construct($resource, \MySQLndUhTool\Proxy $proxy, $closeType) {
        $this->setCloseType($closeType);

        parent::__construct($resource, $proxy);
    }

    public function getCloseType() {
        return $this->closeType;
    }

    public function setCloseType($closeType) {
        $this->closeType = $closeType;
    }
}