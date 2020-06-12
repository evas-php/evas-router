<?php
/**
 * @package evas-php/evas-router
 */
namespace Evas\Router\Routers;

use Evas\Router\Routers\BaseAutoRouter;

/**
 * Константы для свойств класса по умолчанию.
 */
if (!defined('EVAS_AUTOROUTER_FILE_PREFIX')) define('EVAS_AUTOROUTER_FILE_PREFIX', '');
if (!defined('EVAS_AUTOROUTER_FILE_POSTFIX')) define('EVAS_AUTOROUTER_FILE_POSTFIX', '.php');

/**
 * Автороутер по файлу.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 1.0
 */
class AutoRouterByFile extends BaseAutoRouter
{
    /**
     * @var string префикс файла
     */
    public $filePrefix = EVAS_AUTOROUTER_FILE_PREFIX;

    /**
     * @var string постфикс файла
     */
    public $filePostfix = EVAS_AUTOROUTER_FILE_POSTFIX;

    /**
     * Установка/сброс префикса файлов.
     * @param string|null
     * @return self
     */
    public function filePrefix(string $value = null)
    {
        $this->filePrefix = $value;
        return $this;
    }

    /**
     * Установка/сброс постфикса файлов.
     * @param string|null
     * @return self
     */
    public function filePostfix(string $value = null)
    {
        $this->filePostfix = $value;
        return $this;
    }

    /**
     * Генерация обработчика вида: имя файла
     * @param string путь
     * @return string filename
     */
    public function generateHandler(string $path): string
    {
        if ($path == '/') $path = '/index';
        return $this->filePrefix . $path . $this->filePostfix;
    }
}
