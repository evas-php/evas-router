<?php
/**
 * @package evas-php\evas-router
 */
namespace Evas\Router\Traits;

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
        $routes = array_merge($this->routes['ALL'] ?? [], $this->routes[$method] ?? []);
        $this->sortRoutes($routes);
        return $routes;
    }

    /**
     * Сортировка маршрутов.
     * @param array маршруты для сортировки
     */
    public function sortRoutes(array &$routes)
    {
        uksort($routes, function ($cur, $next) {
            $curLength = strlen($cur);
            $nextLength = strlen($next);
            return $curLength === $nextLength ? 0 : ($curLength < $nextLength ? 1 : -1);
        });
    }
}
