<?php
/**
 * @package evas-php/evas-router
 */
namespace Evas\Router\Auto;

use Evas\Router\Auto\BaseAutoRouter;
use Evas\Router\RouterException;

/**
 * Автороутер по функции.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 1.0
 */
class AutoRouterByFunc extends BaseAutoRouter
{
    /**
     * @var callable кастомная функция генерации обработчика
     */
    protected $routingFunc;

    /**
     * Установка кастомной функции генерации обработчика.
     * @param callable
     * @return self
     */
    public function routingFunc(callable $callback)
    {
        $this->routingFunc = &$callback;
        return $this;
    }


    /**
     * Генерация обработчика по кастомной функции.
     * @param string путь
     * @throws RouterException
     * @return mixed
     */
    public function generateHandler(string $path)
    {
        if (empty($this->routingFunc) || !is_callable($this->routingFunc)) {
            throw new RouterException('Undefined autorouting function');
        }
        return call_user_func($this->routingFunc, $path);
    }
}
