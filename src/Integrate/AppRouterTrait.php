<?php
/**
 * @package evas-php/evas-router
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
     * @var string имя класса роутера
     */
    protected $routerClass = EVAS_ROUTER_CLASS;

    /**
     * @var Router роутер
     */
    protected $router;

    /**
     * Установка имени класса роутера.
     * @param string
     * @return self
     */
    public static function setRouterClass(string $routerClass)
    {
        return static::instanceSet('routerClass', $routerClass);
    }

    /**
     * Установка роутера.
     * @param object
     */
    public static function setRouter(object &$router)
    {
        $routerClass = static::instanceGet('routerClass');
        assert($router instanceof $routerClass);
        static::instanceSet('router', $router);
    }

    /**
     * Получение роутера.
     * @return object
     */
    public static function getRouter(): object
    {
        if (!static::instanceHas('router')) {
            $routerClass = static::instanceGet('routerClass');
            $router = new $routerClass;
            static::instanceSet('router', $router);
        }
        return static::instanceGet('router');
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
