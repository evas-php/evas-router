<?php
namespace Evas\Router\Result;

/**
 * Интерфейс результата роутинга.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 1.2
 */
interface RoutingResultInterface
{
    public function __construct(array $middlewares = [], $handler = null, array $args = null, string $controllerClass = null);
    public function handle();
}
