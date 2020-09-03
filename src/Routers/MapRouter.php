<?php
/**
 * @package evas-php\evas-router
 */
namespace Evas\Router\Routers;

use Evas\Router\Routers\BaseRouter;

/**
 * Маппинг роутер.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 1.0
 */
class MapRouter extends BaseRouter
{
    /**
     * Установка маршрутов для метода.
     * @param string|array метод или массив методов
     * @param array маппинг путь => обработчик
     * @return self
     */
    public function routesByMethod($method, array $routes)
    {
        assert(is_string($method) || is_array($method));
        foreach ($routes as $path => $handler) {
            $this->route($method, $path, $handler);
        }
        return $this;
    }

    /**
     * Установка маршрутов.
     * @param array маршруты
     * @return self
     */
    public function routes(array $routes)
    {
        foreach ($routes as $method => $subroutes) {
            $this->routesByMethod($method, $subroutes);
        }
        return $this;
    }
}
