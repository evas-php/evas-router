<?php
/**
 * Интерфейс роутера.
 * @package evas-php\evas-router
 * @author Egor Vasyakin <egor@evas-php.com>
 */
namespace Evas\Router\Interfaces;

use Evas\Base\Interfaces\AppInterface;
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

    // /**
    //  * Роутинг по пути и методу.
    //  * @param string метод
    //  * @param string путь
    //  * @param array аргументы uri
    //  * @return RouterResultInterface
    //  */
    // public function routing(string $method, string $uri, array $args = null): ?RouterResultInterface;

    /**
     * Роутинг по объекту запроса.
     * @param RequestInterface объект запроса
     * @param AppInterface|null объект приложения
     * @return RouterResultInterface
     * @throws RouterException
     */
    public function routing(RequestInterface $request, AppInterface $app = null): ?RouterResultInterface;



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



    /**
     * Установка маршрута/маршрутов HTTP метода GET.
     * @param string|array путь или массив маршрутов
     * @param mixed|null обработчик пути
     * @return self
     */
    public function get($path, $handler = null): RouterInterface;

    /**
     * Установка маршрута/маршрутов HTTP метода POST.
     * @param string|array путь или массив маршрутов
     * @param mixed|null обработчик пути
     * @return self
     */
    public function post($path, $handler = null): RouterInterface;

    /**
     * Установка маршрута/маршрутов HTTP метода PUT.
     * @param string|array путь или массив маршрутов
     * @param mixed|null обработчик пути
     * @return self
     */
    public function put($path, $handler = null): RouterInterface;

    /**
     * Установка маршрута/маршрутов HTTP метода DELETE.
     * @param string|array путь или массив маршрутов
     * @param mixed|null обработчик пути
     * @return self
     */
    public function delete($path, $handler = null): RouterInterface;

    /**
     * Установка маршрута/маршрутов HTTP метода PATCH.
     * @param string|array путь или массив маршрутов
     * @param mixed|null обработчик пути
     * @return self
     */
    public function patch($path, $handler = null): RouterInterface;

    /**
     * Установка маршрута/маршрутов HTTP метода OPTIONS.
     * @param string|array путь или массив маршрутов
     * @param mixed|null обработчик пути
     * @return self
     */
    public function options($path, $handler = null): RouterInterface;

    /**
     * Установка маршрута/маршрутов для всех HTTP методов.
     * @param string|array путь или массив маршрутов
     * @param mixed|null обработчик пути
     * @return self
     */
    public function all($path, $handler = null): RouterInterface;



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



    /**
     * Добавление middleware.
     * @param mixed middleware
     * @return RouterInterface
     * @throws RouterException
     */
    public function middleware($middleware): RouterInterface;

    /**
     * Добавление middlewares.
     * @param array
     * @return RouterInterface
     */
    public function middlewares(array $middlewares): RouterInterface;

    /**
     * Получение middlewares.
     * @return array
     */
    public function getMiddlewares(): array;
}
