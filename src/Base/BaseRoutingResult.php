<?php
/**
 * @package evas-php/evas-router
 */
namespace Evas\Router\Base;

use Evas\Router\Base\RouterControllerTrait;

/**
 * Базовый класс результата роутинга.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 1.0
 */
class BaseRoutingResult
{
    /**
     * Подключаем поддержку контроллера результата роутинга.
     */
    use RouterControllerTrait;

    /**
     * @var array middlewares
     */
    public $middlewares;
    
    /**
     * @var mixed обработчик
     */
    public $handler;

    /**
     * @var array аргументы, разобранные в роутере
     */
    public $args = [];

    /**
     * Конструктор.
     * @param array middlewares
     * @param mixed|null обработчик
     * @param array|null аргументы обработчика
     */
    public function __construct(array $middlewares = [], $handler = null, array $args = null, string $controllerClass = null)
    {
        $this->middlewares = &$middlewares;
        $this->handler = &$handler;
        if (!empty($args)) $this->args = &$args;
        if (!empty($controllerClass)) $this->controllerClass($controllerClass);
    }
}
