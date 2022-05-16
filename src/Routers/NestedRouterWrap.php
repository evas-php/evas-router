<?php
/**
 * Обёртка вложенного роутера для отложенной сборки.
 * @package evas-php\evas-router
 * @author Egor Vasyakin <egor@evas-php.com>
 */
namespace Evas\Router\Routers;

use Evas\Router\Interfaces\RouterInterface;

class NestedRouterWrap
{
    /** @var \Closure колбэк отложенной сборки вложенного роутера */
    protected $callback;
    /** @var RouterInterface вложенный роутер */
    protected $router;

    /**
     * Конструктор.
     * @param \Closure колбэк настройки вложенного роутера
     * @param RouterInterface вложенный роутер
     */
    public function __construct(\Closure &$callback, RouterInterface &$router)
    {
        $this->callback = &$callback;
        $this->router = &$router;
    }

    /**
     * Сборка вложенного роутера.
     */
    public function build()
    {
        $callback = ($this->callback)->bindTo($this->router);
        $callback();
        return $this->router;
    }
}
