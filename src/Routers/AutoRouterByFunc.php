<?php
/**
 * Автороутер по кастомной функции.
 * @package evas-php\evas-router
 * @author Egor Vasyakin <egor@evas-php.com>
 */
namespace Evas\Router\Routers;

use Evas\Router\Exceptions\RouterException;
use Evas\Router\Routers\AbstractAutoRouter;

class AutoRouterByFunc extends AbstractAutoRouter
{
    /** @var callable кастомная функция генерации обработчика */
    protected $routingFunc;

    /**
     * Установка кастомной функции генерации обработчика.
     * @param callable колбек функция генерации обработчика
     * @return self
     */
    public function routingFunc(callable $callback): AutoRouterByFunc
    {
        $this->routingFunc = &$callback;
        return $this;
    }


    /**
     * Генерация обработчика результата роутинга.
     * @param string путь
     * @return mixed обработчик сгенерированный кастомной функцией
     * @throws RouterException
     */
    public function generateHandler(string $path)
    {
        if (empty($this->routingFunc) || !is_callable($this->routingFunc)) {
            throw new RouterException('Undefined autorouting function');
        }
        return call_user_func($this->routingFunc, $path);
    }
}
