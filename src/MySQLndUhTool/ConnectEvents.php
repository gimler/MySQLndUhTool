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

/**
 * Contains all connect events
 *
 * @package    MySQLndUhTool
 * @author     Gordon Franke <info@nevalon.de>
 */
final class ConnectEvents
{
    /**
     * The REQUEST event occurs at the very beginning of request
     * dispatching
     *
     * This event allows you to create a response for a request before any
     * other code in the framework is executed. The event listener method
     * receives a Symfony\Component\HttpKernel\Event\GetResponseEvent
     * instance.
     *
     * @var string
     *
     * @api
     */
    const PRE_CONNECT = 'connect.pre_connect';

    const POST_CONNECT = 'connect.post_connect';

    const FAIL = 'connect.fail';
}