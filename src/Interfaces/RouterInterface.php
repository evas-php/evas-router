<?php
/**
 * Интерфейс роутера.
 * @package evas-php\evas-router
 * @author Egor Vasyakin <egor@evas-php.com>
 */
namespace Evas\Router\Interfaces;

use Evas\Http\Interfaces\RequestInterface;
use Evas\Router\Interfaces\RouterResultInterface;

interface RouterInterface
{
    /**
     * Конструктор.
     * @param RouterInterface|null родительский роутер
     */
    public function __construct(RouterInterface &$parent = null);

    /**
     * Установка обработчика по умолчанию
     * @param mixed обработчик
     * @return self
     */
    public function default($handler): RouterInterface;

    /**
     * Роутинг по uri и методу.
     * @param string uri
     * @param string|null метод
     * @param array аргументы uri
     * @return RouterResultInterface
     */
    public function routing(string $uri, string $method = null, array $args = null): ?RouterResultInterface;

    /**
     * Роутинг по объекту запроса.
     * @param RequestInterface объект запроса
     * @return RouterResultInterface
     * @throws RouterException
     */
    public function requestRouting(RequestInterface $request): ?RouterResultInterface;


    // RouterRoutesTrait

    /**
     * Установка маршрута.
     * @param string метод
     * @param string путь
     * @param mixed обработчик
     * @return RouterInterface
     * @throws RouterException
     */
    public function route(string $method, string $path, $handler): RouterInterface;

    /**
     * Установка маршрута с несколькими методами.
     * @param array методы
     * @param string путь
     * @param mixed обработчик
     * @return RouterInterface
     */
    public function mergeRoute(array $methods, string $path, $handler): RouterInterface;

    /**
     * Получение маршрутов сгруппированных по методам.
     * @return array
     */
    public function getRoutes(): array;


    // RouterAliasesTrait

    /**
     * Добавление алиаса.
     * @param string алиас
     * @param string замена
     * @return RouterInterface
     */
    public function alias(string $alias, string $value): RouterInterface;

    /**
     * Добавленеи алиасов.
     * @param array
     * @return RouterInterface
     */
    public function aliases(array $aliases): RouterInterface;

    /**
     * Получение алиасов.
     * @return array
     */
    public function getAliases(): array;


    // RouterMiddlewaresTrait

    /**
     * Добавление middleware/middlewares.
     * @param string|\Closure|array ...middleware
     * @return RouterInterface
     * @throws RouterException
     */
    public function middleware(...$middlewares): RouterInterface;

    /**
     * Получение middlewares.
     * @return array
     */
    public function getMiddlewares(): array;
}
