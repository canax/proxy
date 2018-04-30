<?php

namespace Anax\Proxy;

use \Anax\Proxy\ProxyException;
use \Anax\DI\Exception\NotFoundException;
use \Anax\DI\DIInterface;

/**
 * Baseclass for Proxy\\DI classes.
 */
class ProxyDI
{
    /**
     * @var object $di  The service container.
     */
    private static $di = null;



    /**
     * Inject $di and make it accessable for static proxy access.
     *
     * @param DIInterface $di The service container holding framework
     *                        services.
     *
     * @return void.
     */
    public static function injectDI(DIInterface $di)
    {
        self::$di = $di;
    }



    /**
     * Catch all calls to static methods and forward them as actual
     * calls to the $di container.
     *
     * @param string $name       The name of the static method called.
     * @param array  $arguments  The arguments sent to the method.
     *
     * @throws ProxyException when failing to forward the call to the
     *                        method in the $di service.
     *
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        if (is_null(self::$di)) {
            throw new ProxyException("Did you forget to inject \$di into the Proxy using Proxy::injectDi(\$di)?");
        }

        try {
            $serviceName = static::getServiceName();
            $serviceObject = self::$di->get($serviceName);
        } catch (NotFoundException $e) {
            throw new ProxyException("The Proxy is trying to reach service '$serviceName' but it is not loaded as a service in \$di.");
        }

        if (!is_callable([$serviceObject, $name])) {
            throw new ProxyException("The Proxy is trying to call method '$name' on service '$serviceName' but that method is not callable.");
        }

        return call_user_func_array([$serviceObject, $name], $arguments);
    }
}
