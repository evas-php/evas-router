<?php
/**
 * @package evas-php/evas-router
 */
namespace Evas\Router\Routers;

use Evas\Router\Routers\BaseRouter;
use Evas\Router\Result\BaseRoutingResult;
use Evas\Router\Traits\MapRouterRestTrait;

/**
 * Маппинг роутер.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 1.0
 */
class MapRouter extends BaseRouter
{
    /**
     * Подключаем поддержку rest синтаксиса.
     */
    use MapRouterRestTrait;

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


    /**
     * Маршрутизация.
     * @param string метод
     * @param string путь
     * @param array аргументы для обработчика
     * @return BaseRoutingResult
     */
    public function routing(string $method, string $uri, array $args = []): BaseRoutingResult
    {
        if (empty($uri)) $uri = '/';
        $routes = $this->getRoutesByMethodWithAll($method);
        foreach ($routes as $path => $handler) {
            if (preg_match($this->preparePath($path), $uri, $matches)) {
                array_shift($matches);
                $args = array_merge($args, $matches);
                if ($handler instanceof BaseRouter) {
                    $handlerUri = array_pop($args) ?? '';
                    return $handler->routing($method, $handlerUri, $args);
                }
                return $this->newRoutingResult($handler, $args);
            }
        }
        return $this->newRoutingResult($this->getDefault(), $args); 
    }
}
