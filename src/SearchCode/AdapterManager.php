<?php

namespace SearchCode;

use SearchCode\Adapter\GithubAdapter;
use SearchCode\Adapter\BitbucketAdapter;
use SearchCode\Exception\AdapterException;
use Symfony\Component\HttpFoundation\Request;

abstract class AdapterManager
{
    protected static $adapters = [];

    protected static $aliases = [
        'github' => Adapter\GithubAdapter::class
    ];

    public static function getAdapter($adapterName) {

        $adapterName = strtolower($adapterName);
        
        if (isset(static::$adapters[$adapterName])) {
            return static::$adapters[$adapterName];
        }

        if (!array_key_exists($adapterName, static::$aliases)) {
            throw new AdapterException(sprintf(
                'The requested adapter "%s" does not exists or is not registered in AdapterManager', $adapterName
            ));
        }

        $adapterClass = static::$aliases[$adapterName];
        $adapter      = new $adapterClass();
        $adapter->configure()->authenticate();

        static::$adapters[$adapterName] = $adapter;

        return $adapter;
    }
}