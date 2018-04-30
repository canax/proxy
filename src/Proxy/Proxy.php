<?php

namespace Anax\Proxy;

use \Anax\DI\DIInterface;

/**
 * Create Proxy applications.
 */
class Proxy
{
    /**
     * Initiate the Proxy\DI to proxy all services available in $di.
     *
     * @param DIInterface $di The service container holding framework
     *                        services.
     *
     * @return void.
     */
    public static function init(DIInterface $di)
    {
        ProxyDI::injectDI($di);
        spl_autoload_register(__CLASS__ . "::autoloader");
    }



    /**
     * Autoloader for Proxy\DI realtime static proxy access to $di.
     *
     * @param string $class The name of the class to create, the
     *                      classname must be a direct sublass of
     *                      \Anax\Proxy\DI\.
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.evalExpression)
     */
    public static function autoloader($class)
    {
        $prefix = "Anax\\Proxy\\DI";

        // Check if it is a class below namespace $prefix
        if (strncmp($prefix, $class, strlen($prefix))) {
            return;
        }

        // Get the classname after the $prefix
        $relativeClass = substr($class, strlen($prefix) + 1);

        $classDefinition = <<< EOD
namespace Anax\Proxy\DI;

use \Anax\Proxy\ProxyDI;
use \Anax\Proxy\ProxyInterface;

class $relativeClass extends ProxyDI implements ProxyInterface
{
    public static function getServiceName()
    {
        return lcfirst("$relativeClass");
    }
}

EOD;

        eval($classDefinition);
    }
}
