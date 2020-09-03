<?php
/**
 * @package evas-php\evas-router
 */
namespace Evas\Router\Routers;

use Evas\Router\Routers\BaseRouter;
use Evas\Router\Result\RoutingResultInterface;
use Evas\Router\Result\Exception\RoutingResultHandleHandlerException;

/**
 * Абстрактный базовый класс автороутера.
 * @author Egor Vasyakin <egor@evas-php.com>
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
     * @param string путь
     * @param array|null аргументы для обработчика
     * @return RoutingResultInterface
     */
    public function autoRouting(string $uri, array $args = null): ?RoutingResultInterface
    {
        $handler = $this->generateHandler($uri);
        try {
            return $this->newRoutingResult($handler, $args)->prepare();
        } catch (RoutingResultHandleHandlerException $e) {
            return null;
        }
    }
}
