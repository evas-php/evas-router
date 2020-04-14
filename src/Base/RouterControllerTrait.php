<?php
/**
 * @package evas-php/evas-router
 */
namespace Evas\Router\Base;

use Evas\Mvc\InitControllerTrait;

/**
 * Трейт подключения контроллер-класса резултьта роутера.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 1.0
 */
trait RouterControllerTrait
{
    /**
     * Подключаем расширение инициализации класса контроллера.
     */
    use InitControllerTrait;
}
