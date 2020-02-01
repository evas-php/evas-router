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
     * @var string имя класса router
     */
    protected $routerClass = EVAS_ROUTER_CLASS;

    /**
     * @var Router объект router
     */
    protected $router;

    /**
     * Установка имени класса router.
     * @param string
     * @return self
     */
    public static function setRouterClass(string $routerClass)
    {
        return static::instanceSet('routerClass', $routerClass);
    }

    /**
     * Получение объекта router.
     * @return Router
     */
    public static function router()
    {
        if (!static::instanceHas('router')) {
            $routerClass = static::instanceGet('routerClass');
            $router = new $routerClass;
            static::instanceSet('router', $router);
        }
        return static::instanceGet('router');
    }
}
