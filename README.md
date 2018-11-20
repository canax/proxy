Anax Proxy
===========================

[![Latest Stable Version](https://poser.pugx.org/anax/proxy/v/stable)](https://packagist.org/packages/anax/proxy)
[![Join the chat at https://gitter.im/canax/proxy](https://badges.gitter.im/canax/proxy.svg)](https://gitter.im/canax/proxy?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

[![Build Status](https://travis-ci.org/canax/proxy.svg?branch=master)](https://travis-ci.org/canax/proxy)
[![CircleCI](https://circleci.com/gh/canax/proxy.svg?style=svg)](https://circleci.com/gh/canax/proxy)

[![Build Status](https://scrutinizer-ci.com/g/canax/proxy/badges/build.png?b=master)](https://scrutinizer-ci.com/g/canax/proxy/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/canax/proxy/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/canax/proxy/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/canax/proxy/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/canax/proxy/?branch=master)

[![Maintainability](https://api.codeclimate.com/v1/badges/8705e9bc0a597e6dfb9a/maintainability)](https://codeclimate.com/github/canax/proxy/maintainability)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/c3d60f33c0b947a3af127788e800b402)](https://www.codacy.com/app/mosbth/proxy?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=canax/proxy&amp;utm_campaign=Badge_Grade)

Anax module for static proxy to access framework resources, you can compare to the implementation of Laravel Facade.

This is an intermediate test implementation, it works though, just for test and showing that it is a feasable implementation.



Implementation
--------------------------

You start by initiating the proxy in the frontcontroller `index.php`.

```php
Proxy::init($di);
```

The service container `$di` is injected and an autoloader is created to catch and dynamiccaly create classes for the Proxy class to map the service in `$di`.

You start by defining the proxy class through `use \Anax\Proxy\DI\Db;`. You can then use it through static access `Db::connect()` which behind the scenes translates to `$di->get("db")->connect()`.

This is how it can be used with a route. 

```php
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
Router::get("movie", function () {
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

Here is the same route implemented, with `$app` style programming and dependency to the (globally) scoped variable `$app`.

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
