<?php
namespace Evas\Router\Result;

/**
 * Интерфейс результата роутинга.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 1.2
 */
interface RoutingResultInterface
{
    /**
     * Конструктор.
     * @param mixed|null обработчик
     * @param array|null аргументы обработчика
     * @param array|null middlewares
     * @param array|null класс контроллера
     */
    public function __construct($handler = null, array $args = null, array $middlewares = null, string $controllerClass = null);

    /**
     * Вызов обработчика маршрута.
     */
    public function handle();
}
