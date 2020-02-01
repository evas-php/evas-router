<?php
/**
 * @package evas-php/evas-router
 */
namespace Evas\Router\Base;

/**
 * Базовый класс результата роутинга.
 * @author Egor Vasyakin <e.vasyakin@itevas.ru>
 * @since 1.0
 */
class BaseRoutingResult
{
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
    public function __construct(array $middlewares = [], $handler = null, array $args = null)
    {
        $this->middlewares = &$middlewares;
        $this->handler = &$handler;
        if (!empty($args)) $this->args = &$args;
    }
}
