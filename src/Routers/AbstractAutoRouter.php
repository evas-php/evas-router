<?php
/**
 * Абстрактный базовый класс автороутера.
 * @package evas-php\evas-router
 * @author Egor Vasyakin <egor@evas-php.com>
 */
namespace Evas\Router\Routers;

use Evas\Router\Exceptions\RouterResultException;
use Evas\Router\Interfaces\RouterResultInterface;
use Evas\Router\Routers\MapRouter;

abstract class AbstractAutoRouter extends MapRouter
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
     * @return RouterResultInterface
     */
    public function autoRouting(string $path, array $args = null): ?RouterResultInterface
    {
        $handler = $this->generateHandler($path);
        try {
            return $this->newResult($handler, $args);
        } catch (RouterResultException $e) {
            return null;
        }
    }
}
