Anax Proxy
===========================

Anax module for static proxy to access framework resources, you can compare to the implementation of Laravel Facade.

This is an intermediate test implementation, it works though, just for test and showing that it is a feasable implementation.



Implementation
--------------------------

You start by initiating the proxy in the frntcontroller `index.php`.

```php
Proxy::init($di);
```

The service container `$di` is injected and an autoloader is created to catch and dynamiccaly create classes for the Proxy class to map the service in `$di`.

You start by defining the proxy class through `use \Anax\Proxy\DI\Db;`. You can then use it through static access `Db::connect()` which behind the scenes translates to `$di->get("db")->connect()`.

This is how it can be used with a route. 

```php
<?php
/**
 * App specific routes.
 */
use \Anax\Proxy\DI\Db;
use \Anax\Proxy\DI\Router;
use \Anax\Proxy\DI\View;
use \Anax\Proxy\DI\Page;

/**
 * Show all movies.
 */
Router::get("proxy", function () use ($app) {
    $data = [
        "title"  => "Movie database | oophp",
    ];

    Db::connect();

    $sql = "SELECT * FROM movie;";
    $res = Db::executeFetchAll($sql);

    $data["res"] = $res;

    View::add("movie/index", $data);
    Page::render($data);
});
```

Here is the same route implemented, with `$app` style programming.

```php
<?php
/**
 * App specific routes.
 */

/**
 * Show all movies.
 */
$app->router->get("movie", function () use ($app) {
    $data = [
        "title"  => "Movie database | oophp",
    ];

    $app->db->connect();

    $sql = "SELECT * FROM movie;";
    $res = $app->db->executeFetchAll($sql);

    $data["res"] = $res;

    $app->view->add("movie/index", $data);
    $app->page->render($data);
});
```



Related design patterns
--------------------------

Laravel calls their implementation Laravel Facade. This might indicate they relate to the design pattern [`Facade design pattern`](https://en.wikipedia.org/wiki/Facade_pattern).

People have argued that the implementation is more of the design pattern [`Proxy design pattern`](https://en.wikipedia.org/wiki/Proxy_pattern).

People have also argued that it is an implementation of the design pattern [`Singleton design pattern`](https://en.wikipedia.org/wiki/Singleton_pattern).



```
 .  
..:  Copyright (c) 2018 Mikael Roos, mos@dbwebb.se
```
