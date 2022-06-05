<?php
/**
 * Интерфейс контроллера.
 * @package evas-php\evas-router
 * @author Egor Vasyakin <egor@evas-php.com>
 */
namespace Evas\Router\Interfaces;

interface ControllerInterface
{
    /**
     * Открытие файла.
     * @param string имя файла относительное директории отображений
     * @param array|null аргументы файла
     */
    public function view(string $filename, array $args = null);

    /**
     * Выбрасывание исключения в случае невозможности открытия файла отображения.
     * @param string имя файла относительное директории отображений
     */
    public function throwIfNotCanView(string $filename);
}
