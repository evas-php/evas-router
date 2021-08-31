<?php
/**
 * Автороутер по файлу.
 * @package evas-php\evas-router
 * @author Egor Vasyakin <egor@evas-php.com>
 */
namespace Evas\Router\Routers;

use Evas\Router\Routers\AbstractAutoRouter;

/**
 * Константы для свойств класса по умолчанию.
 */
if (!defined('EVAS_AUTOROUTER_FILE_PREFIX')) 
    define('EVAS_AUTOROUTER_FILE_PREFIX', '');

if (!defined('EVAS_AUTOROUTER_FILE_POSTFIX')) 
    define('EVAS_AUTOROUTER_FILE_POSTFIX', '.php');


class AutoRouterByFile extends AbstractAutoRouter
{
    /** @var string префикс файла */
    public $filePrefix = EVAS_AUTOROUTER_FILE_PREFIX;

    /** @var string постфикс файла */
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
     * Генерация обработчика результата роутинга.
     * @param string путь
     * @return string обработчик вида 'filename'
     */
    public function generateHandler(string $path): string
    {
        if (empty($path)) $path = '/index';
        else if (mb_strlen($path) - 1 === strrpos($path, '/')) $path .= 'index';
        if (strpos($path, '/') === 0 && mb_strlen($this->filePrefix) - 1 === strrpos($this->filePrefix, '/')) {
            $path = substr($path, 1);
        }
        $path = $this->filePrefix . $path . $this->filePostfix;
        return $path;
    }
}
