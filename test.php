<?php
class QueryEventListener {
    static public function preExecution($event) {
        $backtrace = debug_backtrace();
        $file = $backtrace[5]['file'];
        $line = $backtrace[5]['line'];
        echo sprintf('%s line %u query `%s`', $file, $line, $event->getQuery()) . PHP_EOL;
    }

    public function postExecution($event) {
        $backtrace = debug_backtrace();
        $file = $backtrace[5]['file'];
        $line = $backtrace[5]['line'];
        echo sprintf('%s line %u query post execution `%s` in %f sec', $file, $line, $event->getQuery(), $event->getTime()) . PHP_EOL;        
    }
}

require_once(__DIR__ . '/mysqlnduhtool.phar');

$eventDispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();
$callback = function(MySQLndUhTool\Event\Connect $event) {
    $backtrace = debug_backtrace();
    $file = $backtrace[5]['file'];
    $line = $backtrace[5]['line'];
    echo sprintf('%s line %u connect %s:%s@%s:%u/%s %s %s', $file, $line, $event->getUser(), $event->getPassword(), $event->getHost(), $event->getPort(), $event->getDatabase(), $event->getSocket(), $event->getMysqlFlags()) . PHP_EOL;
};
$eventDispatcher->addListener(MySQLndUhTool\ConnectEvents::FAIL, $callback);
$callback = function(MySQLndUhTool\Event\Connect $event) {
    $observer = $event->getProxy();
    $resource = $event->getResource();
    $backtrace = debug_backtrace();
    $file = $backtrace[5]['file'];
    $line = $backtrace[5]['line'];
    echo sprintf('%s line %u error connect failed with `%s (%u)`', $file, $line, $observer->getErrorString($resource), $observer->getErrorNumber($resource)) . PHP_EOL;
};
$eventDispatcher->addListener(MySQLndUhTool\ConnectEvents::FAIL, $callback);

$eventDispatcher->addListener(MySQLndUhTool\QueryEvents::PRE_EXECUTION, 'QueryEventListener::preExecution');
$eventDispatcher->addListener(MySQLndUhTool\QueryEvents::POST_EXECUTION, array( new QueryEventListener, 'postExecution'));
$callback = function($event) {
    $backtrace = debug_backtrace();
    $file = $backtrace[5]['file'];
    $line = $backtrace[5]['line'];
    echo sprintf('%s line %u query post execution via closure `%s`', $file, $line, $event->getQuery()) . PHP_EOL;
};
$eventDispatcher->addListener(MySQLndUhTool\QueryEvents::POST_EXECUTION, $callback, 1);
$callback = function(MySQLndUhTool\Event\Close $event) {
    $backtrace = debug_backtrace();
    $file = $backtrace[5]['file'];
    $line = $backtrace[5]['line'];
    echo sprintf('%s line %u mysql_close %s', $file, $line, $event->getCloseType()) . PHP_EOL;
};
$eventDispatcher->addListener(MySQLndUhTool\CloseEvents::PRE_CLOSE, $callback, 1);

$callback = function(MySQLndUhTool\Event\Query $event) {
    $backtrace = debug_backtrace();
    $file = $backtrace[5]['file'];
    $line = $backtrace[5]['line'];
    error_log(sprintf('%s line %u failed query %s', $file, $line, $event->getQuery()));
};
$eventDispatcher->addListener(MySQLndUhTool\QueryEvents::FAIL, $callback);
$analyser = new MySQLndUhTool\Proxy($eventDispatcher);



$con = mysql_connect('localhost', 'root2', '');
$con = mysql_connect('localhost', 'root', '');
if (!$con) {
    die('can\'t connect to mysql server');
}

$db = mysql_select_db('week_data_test', $con);

$query = mysql_query('SELECT * FROM visibility_subdomain_201120 LIMIT 1', $con);
$query = mysql_query('SELECT * FROM visibility_subdomain_201120 LIMIT 1', $con);
mysql_close($con);
var_dump($con);
$query = mysql_query('SELECT * FROM visibilit_subdomain_201120 LIMIT 1', $con);

var_dump($analyser->countExecutedQueries(), $analyser->countUniqueQueries());
var_dump($analyser->getSqlQueries());
#var_dump($findQuery->getQueries());
var_dump($analyser->hasQuery('SELECT * FROM visibility_subdomain_201120 LIMIT 1'));
var_dump($analyser->hasQuery('SELECT * FROM visibility_subdomain_201120 LIMIT 2'));

$ref = new ReflectionClass($analyser);
#var_dump($ref->__toString());
