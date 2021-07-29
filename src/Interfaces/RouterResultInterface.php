<?php
/**
 * Интерфейс результата роутинга.
 * @package evas-php\evas-router
 * @author Egor Vasyakin <egor@evas-php.com>
 */
namespace Evas\Router\Interfaces;

interface RouterResultInterface
{
    /**
     * Конструктор.
     * @param mixed|null обработчик
     * @param array|null аргументы обработчика
     * @param array|null middlewares
     */
    public function __construct($handler = null, array $args = null, array $middlewares = null);

    /**
     * Обработка маршрута.
     */
    // public function resolve();
}
