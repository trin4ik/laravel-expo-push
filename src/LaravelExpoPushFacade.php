<?php

namespace Trin4ik\LaravelExpoPush;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Trin4ik\LaravelExpoPush\Skeleton\SkeletonClass
 */
class LaravelExpoPushFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-expo-push';
    }
}
