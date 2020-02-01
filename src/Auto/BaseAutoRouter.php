<?php
/**
 * @package evas-php/evas-router
 */
namespace Evas\Router\Auto;

use Evas\Router\Base\BaseRouter;
use Evas\Router\Base\BaseRoutingResult;
use Evas\Router\Result\Exception\RoutingResultHandleHandlerException;

/**
 * Абстрактный базовый класс автороутера.
 * @author Egor Vasyakin <e.vasyakin@itevas.ru>
 * @since 1.0
 */
abstract class BaseAutoRouter extends BaseRouter
{
    /**
     * Абстрактная генерация обработчика маршрута.
     * @param string путь
     * @return mixed
     */
    abstract public function generateHandler(string $path);

    /**
     * Автороутинг.
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
            }
        }
        $handler = $this->generateHandler($uri);
        try {
            $route = $this->newRoutingResult($handler, $args);
            return $route->prepare();
        } catch (RoutingResultHandleHandlerException $e) {
            return $this->newRoutingResult($this->getDefault(), $args);
        }
    }
}
