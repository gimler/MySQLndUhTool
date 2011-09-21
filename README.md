MySQLndUhTool, locate change control your mysql commands
================================

MySQLndUhTool is proxy that add events to the mysql commands. So you can not only log queries you get the line, file and server on which the spocky query was executed.

Requirements
------------

MySQLndUhTool requires mysqlnd and the pecl extension mysqlnd_uh [2].

Installation
------------

Installing MySQLndUhTool is as easy as it can get. Download the [`mysqlnduhtool.phar`][1]
file and you're done!

Usage
-----

Require the MySQLndUhTool phar file to use MySQLndUhTool in a script:

    require_once '/path/to/MySQLndUhTool.phar';

Create a Symfony EventDispatcher instance:

    use Symfony\Component\EvenDispatcher\EvenDispatcher;

    $eventDispatcher = new EvenDispatcher();

Create a MySQLndUhTool Proxy instance with the dispatcher (which extends
`MySQLndUhConnection`):

    use MySQLndUhTool\Proxy;

    $proxy = new Proxy($eventDispatcher);

Get build in informations
-------------------------

How many query were executed:

    $proxy->countExecutedQueries();

    // unique queries
    $proxy->countUniqueQueries());

Which queries were executed:

    // get array with sql => backtrace
    $proxy->getSqlQueries();

Check for sepcific query:

    $proxy->hasQuery($sql);

Add listener to the events
--------------------------

1. create a callback (closure, static function ...)

        $callback = function(MySQLndUhTool\Event\Query $event) {
            $backtrace = debug_backtrace();
            $file      = $backtrace[5]['file'];
            $line      = $backtrace[5]['line'];
            error_log(sprintf('%s line %u bullshit %s', $file, $line, $event->getQuery()));
        };

2. add the callback for specific event to the dispatcher

        $eventDispatcher->addListener(MySQLndUhTool\QueryEvents::FAIL, $callback[, $priority]);

Automatically integration in every php process
-----------------------------------------------

There are two usefull php ini option with that you can prepend the bootstrap of MySQLndUhTool `auto_prepend_file` and append option `auto_append_file` for log/output build in informations or do other stuff.

Technical Information
---------------------

MySQLndUhTool is a proxy around the MySQLndUhConnection class it use the
following fine PHP libraries:

 * Symfony Components: ClassLoader, EventDispatcher

And the following pecl extension:

 * mysqlnd_uh

License
-------

MySQLndUhTool is licensed under the MIT license.

[1]: https://raw.github.com/gimler/MySQLndUhTool/master/mysqlnduhtool.phar
[2]: http://pecl.php.net/package/mysqlnd_uh
