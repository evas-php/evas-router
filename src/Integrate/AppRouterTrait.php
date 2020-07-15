<?php
/**
 * @package evas-php\evas-router
 */
namespace Evas\Router\Integrate;

use Evas\Router\Router;

/**
 * Константы для свойств трейта по умолчанию.
 */
if (!defined('EVAS_ROUTER_CLASS')) define('EVAS_ROUTER_CLASS', Router::class);

/**
 * Расширение класса приложения поддержкой router.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 1.0
 */
trait AppRouterTrait
{
    /**
     * Установка имени класса роутера.
     * @param string
     * @return self
     */
    public static function setRouterClass(string $routerClass)
    {
        return static::set('routerClass', $routerClass);
    }

    /**
     * Получение имени класса роутера.
     * @return string
     */
    public static function getRouterClass(): string
    {
        if (!static::has('routerClass')) {
            static::set('routerClass', EVAS_ROUTER_CLASS);
        }
        return static::get('routerClass');
    }

    /**
     * Установка роутера.
     * @param object
     */
    public static function setRouter(object &$router)
    {
        $routerClass = static::getRouterClass();
        assert($router instanceof $routerClass);
        static::set('router', $router);
    }

    /**
     * Получение роутера.
     * @return object
     */
    public static function getRouter(): object
    {
        if (!static::has('router')) {
            $routerClass = static::getRouterClass();
            $router = new $routerClass;
            static::set('router', $router);
        }
        return static::get('router');
    }

    /**
     * Получение/установка роутера.
     * @param object|null
     * @return object
     */
    public static function router(object &$router = null): object
    {
        if ($router) static::setRouter($router);
        return static::getRouter();
    }
}
