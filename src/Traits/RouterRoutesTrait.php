<?php
/**
 * @package evas-php\evas-router
 */
namespace Evas\Router\Traits;

use Evas\Router\Result\RoutingResultInterface;
use Evas\Router\Routers\BaseRouter;

/**
 * Расширение роутера для работы с маппингом маршрутов.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 1.0
 */
trait RouterRoutesTrait
{
    /**
     * @var array маппинг обработчиков по методу и пути
     */
    protected $routes = [
        'ALL' => [],
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'DELETE' => [],
        'PATCH' => [],
    ];

    /**
     * Установка маршрута.
     * @param string|array метод или массив методов
     * @param string путь
     * @param mixed обработчик
     * @return self
     */
    public function route($method, string $path, $handler)
    {
        assert(is_string($method) || is_array($method));
        if (is_string($method)) {
            $method = strtoupper($method);
            $this->routes[$method][$path] = $handler;
        } else foreach ($method as &$submethod) {
            $this->route($submethod, $path, $handler);
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
     * Получение группы маршрутов по методу вместе с группой ALL.
     * @param string метод
     * @return array
     */
    public function getRoutesByMethodWithAll(string $method): array
    {
        $method = strtoupper($method);
        return array_merge($this->routes['ALL'] ?? [], $this->routes[$method] ?? []);
    }

    /**
     * Роутинг по маршрутам.
     * @param string метод
     * @param string путь
     * @param array|null параметры пути
     * @return RoutingResultInterface|null
     */
    public function mapRouting(string $method, string $uri, array $args = null): ?RoutingResultInterface
    {
        if (empty($uri)) $uri = '/';
        $routes = $this->getRoutesByMethodWithAll($method);
        foreach ($routes as $path => $handler) {
            if (preg_match($this->preparePath($path), $uri, $matches)) {
                array_shift($matches);
                $args = array_merge($args ?? [], $matches);
                if ($handler instanceof BaseRouter) {
                    $handlerUri = array_pop($args) ?? '';
                    return $handler->routing($method, $handlerUri, $args);
                }
                return $this->newRoutingResult($handler, $args);
            }
        }
        return null;
    }
}
