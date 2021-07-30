<?php
/**
 * Расширение роутера для работы с маппингом маршрутов.
 * @package evas-php\evas-router
 * @author Egor Vasyakin <egor@evas-php.com>
 */
namespace Evas\Router\Traits;

use \InvalidArgumentException;
use Evas\Base\Help\PhpHelp;
use Evas\Router\Interfaces\RouterInterface;

trait RouterRoutesTrait
{
    /** @var array маппинг обработчиков по методу и пути */
    protected $routes = [];

    /**
     * Проверка типа обработчика на корректность типа данных.
     * @param mixed обработчик
     * @return bool
     */
    public function isCorrectHandlerType(&$handler): bool
    {
        return is_array($handler) || is_string($handler) || is_callable($handler);
    }

    /**
     * Установка маршрута.
     * @param string метод
     * @param string путь
     * @param mixed обработчик
     * @return RouterInterface
     * @throws InvalidArgumentException
     */
    public function route(string $method, string $path, $handler): RouterInterface
    {
        $method = strtoupper($method);
        if (!$this->isCorrectHandlerType($handler) && !$handler instanceof RouterInterface) {
            throw new InvalidArgumentException(sprintf(
                'Argument 3 $handler must be a string or an array 
                or a \Closure or an instance of $s, %s given',
                RouterInterface::class,
                PhpHelp::getType($handler)
            ));
        }
        $this->routes[$method][$path] = $handler;
        return $this;
    }

    /**
     * Установка маршрута с несколькими методами.
     * @param array методы
     * @param string путь
     * @param mixed обработчик
     * @return RouterInterface
     */
    public function mergeRoute(array $methods, string $path, $handler): RouterInterface
    {
        foreach ($methods as &$method) {
            $this->route($method, $path, $handler);
        }
        return $this;
    }

    /**
     * Получение маршрутов сгруппированных по методам.
     * @return array
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * Получение группы маршрутов по методу вместе с методом ALL.
     * @param string метод
     * @return array
     */
    public function getRoutesByMethodWithAll(string $method): array
    {
        $method = strtoupper($method);
        return array_merge($this->routes['ALL'] ?? [], $this->routes[$method] ?? []);
    }
}
