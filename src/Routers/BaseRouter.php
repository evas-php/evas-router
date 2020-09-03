<?php
/**
 * @package evas-php\evas-router
 */
namespace Evas\Router\Routers;

use Evas\Base\PhpHelper;
use Evas\Http\RequestInterface;
use Evas\Router\Result\RoutingResult;
use Evas\Router\Result\RoutingResultInterface;
use Evas\Router\RouterException;
use Evas\Router\Traits\RouterAliasesTrait;
use Evas\Router\Traits\RouterControllerTrait;
use Evas\Router\Traits\RouterGroupTrait;
use Evas\Router\Traits\RouterMiddlewaresTrait;
use Evas\Router\Traits\RouterParentTrait;
use Evas\Router\Traits\RouterRestTrait;
use Evas\Router\Traits\RouterRoutesTrait;

/**
 * Константы для свойств класса по умолчанию.
 */
if (!defined('EVAS_ROUTER_REQUEST_INTERFACE')) {
    define('EVAS_ROUTER_REQUEST_INTERFACE', RequestInterface::class);
}

if (!defined('EVAS_ROUTER_RESULT_CLASS')) {
    define('EVAS_ROUTER_RESULT_CLASS', RoutingResult::class);
}

if (!defined('EVAS_ROUTER_DEFAULT_HANDLER')) {
    define('EVAS_ROUTER_DEFAULT_HANDLER', null);
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
     * Подключаем поддержку контроллера по умолчанию.
     * Подключаем поддержку группировки.
     * Подключаем поддержку middlewares.
     * Подключаем поддержку родительского роутера.
     * Подключаем поддержку маршрутов.
     */
    use RouterAliasesTrait, 
        RouterControllerTrait,
        RouterGroupTrait,
        RouterMiddlewaresTrait,
        RouterParentTrait,
        RouterRoutesTrait,
        RouterRestTrait;

    /**
     * @var string имя класса результата роутинга
     */
    protected $routingResultClass = EVAS_ROUTER_RESULT_CLASS;

    /**
     * @var mixed обработчик по умолчанию
     */
    protected $default = EVAS_ROUTER_DEFAULT_HANDLER;


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
     * @return RoutingResultInterface
     */
    public function newRoutingResult($handler = null, array $args = null): RoutingResultInterface
    {
        $middlewares = $this->getMiddlewares();
        return new $this->routingResultClass($middlewares, $handler, $args, $this->getControllerClass());
    }

    /**
     * Роутинг по пути и методу.
     * @param string метод
     * @param string путь
     * @param array аргументы uri
     * @return RoutingResultInterface
     */
    public function routing(string $method, string $uri, array $args = null): ?RoutingResultInterface
    {
        $result = $this->mapRouting($method, $uri, $args);
        if (null === $result && method_exists($this, 'autoRouting')) {
            $result = $this->autoRouting($uri, $args);
        }
        if (null === $result) {
            if (empty($this->default)) {
                if (null === $this->parent) {
                    throw new RouterException('404. Not Found.');
                } else {
                    return null;
                }
            } else {
                return $this->newRoutingResult($this->default);
            }
        }
        return $result;
    }

    /**
     * Роутинг по объекту запроса.
     * @param object
     * @return RoutingResultInterface
     */
    public function routingByRequest(object $request): ?RoutingResultInterface
    {
        $interface = EVAS_ROUTER_REQUEST_INTERFACE;
        if (!($request instanceof $interface)) {
            throw new RouterException("routingByRequest argument is not an instance of $interface");
        }
        return $this->routing($request->getMethod(), $request->getPath());
    }
}
