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
 * Connect event
 *
 * @package    MySQLndUhTool
 * @author     Gordon Franke <info@nevalon.de>
 */
class Connect extends Base
{
    private $host;
    private $user;
    private $password;
    private $database;
    private $port;
    private $socket;
    private $mysqlFlags;

    public function __construct($resource, \MySQLndUhTool\Proxy $proxy, $host, $user, $password, $database, $port, $socket, $mysql_flags) {
        $this->setHost($host);
        $this->setUser($user);
        $this->setPassword($password);
        $this->setDatabase($database);
        $this->setPort($port);
        $this->setSocket($socket);
        $this->setMysqlFlags($mysql_flags);

        parent::__construct($resource, $proxy);
    }

    public function getHost() {
        return $this->host;
    }

    public function setHost($host) {
        $this->host = $host;
    }

    public function getUser() {
        return $this->user;
    }

    public function setUser($user) {
        $this->user = $user;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function getDatabase() {
        return $this->database;
    }

    public function setDatabase($database) {
        $this->database = $database;
    }

    public function getPort() {
        return $this->port;
    }

    public function setPort($port) {
        $this->port = $port;
    }

    public function getSocket() {
        return $this->socket;
    }

    public function setSocket($socket) {
        $this->socket = $socket;
    }

    public function getMysqlFlags() {
        return $this->mysqlFlags;
    }

    public function setMysqlFlags($mysqlFlags) {
        $this->mysqlFlags = $mysqlFlags;
    }
}