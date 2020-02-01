<?php
/**
 * @package evas-php/evas-router
 */
namespace Evas\Router\Base;

use Evas\Base\PhpHelper;
use Evas\Http\RequestInterface;
use Evas\Router\Base\RouterAliasesTrait;
use Evas\Router\Base\RouterGroupTrait;
use Evas\Router\Base\RouterMiddlewaresTrait;
use Evas\Router\Base\RouterParentTrait;
use Evas\Router\Base\RouterRoutesTrait;
use Evas\Router\Result\RoutingResult;

/**
 * Константы для свойств класса по умолчанию.
 */
if (!defined('EVAS_ROUTING_RESULT_CLASS')) define('EVAS_ROUTING_RESULT_CLASS', RoutingResult::class);

if (!defined('EVAS_ROUTING_RESULT_DEFAULT_HANDLER_CLASS')) {
    define('EVAS_ROUTING_RESULT_DEFAULT_HANDLER_CLASS', null);
}

/**
 * Базовый абстрактный класс роутера.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 1.0
 */
abstract class BaseRouter
{
    /**
     * Подключаем поддержку алиасов.
     * Подключаем поддержку группировки.
     * Подключаем поддержку middlewares.
     * Подключаем поддержку родительского роутера.
     * Подключаем поддержку маршрутов.
     */
    use RouterAliasesTrait, 
        RouterGroupTrait,
        RouterMiddlewaresTrait,
        RouterParentTrait,
        RouterRoutesTrait;

    /**
     * @var string имя класса результата роутинга
     */
    protected $routingResultClass = EVAS_ROUTING_RESULT_CLASS;

    /**
     * @var mixed обработчик по умолчанию
     */
    protected $default = EVAS_ROUTING_RESULT_DEFAULT_HANDLER_CLASS;


    /**
     * Установка имени класса результата роутинга.
     * @param string имя класса
     * @return self
     */
    public function routingResultClass(string $className)
    {
        $this->routingResultClass = $className;
        return $this;
    }

    /**
     * Установка обработчика по умолчанию
     * @param mixed обработчик
     * @return self
     */
    public function default($handler)
    {
        $this->default = &$handler;
        return $this;
    }


    /**
     * Получение имени класса результата роутинга.
     * @return string
     */
    public function getRoutingResultClass(): string
    {
        return $this->routingResultClass;
    }

    /**
     * Получение обработчика по умолчанию.
     * @return mixed
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * Создание объекта результата роутинга.
     * @param mixed|null обработчик маршрута
     * @param array|null аргументы обработчика
     * @return BaseRoutingResult
     */
    public function newRoutingResult($handler = null, array $args = null): BaseRoutingResult
    {
        $middlewares = $this->getMiddlewares();
        // // если есть middlewares
        // if (count($middlewares) > 0) {
        //     // если это не список обработчиков, трансформируем в список
        //     if (!PhpHelper::isNumericArray($handler)) $handler = [$handler];
        //     $handler = array_merge($middlewares, $handler);
        // }
        return new $this->routingResultClass($middlewares, $handler, $args);
    }

    /**
     * Роутинг по пути и методу.
     * @param string метод
     * @param string путь
     * @param array аргументы uri
     * @return BaseRoutingResult
     */
    abstract public function routing(string $method, string $uri, array $args = []): BaseRoutingResult;

    /**
     * Роутинг по объекту запроса.
     * @param RequestInterface
     * @return BaseRoutingResult
     */
    public function routingByRequest(RequestInterface $request): BaseRoutingResult
    {
        return $this->routing($request->getMethod(), $request->getPath());
    }
}
