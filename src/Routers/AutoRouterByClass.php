<?php
/**
 * @package evas-php/evas-router
 */
namespace Evas\Router\Routers;

use Evas\Router\Routers\BaseAutoRouter;

/**
 * Константы для свойств класса по умолчанию.
 */
if (!defined('EVAS_AUTOROUTER_CLASS_METHOD')) define('EVAS_AUTOROUTER_CLASS_METHOD', 'auto');

if (!defined('EVAS_AUTOROUTER_CLASS_PREFIX')) define('EVAS_AUTOROUTER_CLASS_PREFIX', '');
if (!defined('EVAS_AUTOROUTER_CLASS_POSTFIX')) define('EVAS_AUTOROUTER_CLASS_POSTFIX', '');

/**
 * Автороутер по классу.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 1.0
 */
class AutoRouterByClass extends BaseAutoRouter
{
    /**
     * @var string дефолтный метод класса
     */
    public $classMethod = EVAS_AUTOROUTER_CLASS_METHOD;

    /**
     * @var string префикс класса
     */
    public $classPrefix = EVAS_AUTOROUTER_CLASS_PREFIX;

    /**
     * @var string постфикс класса
     */
    public $classPostfix = EVAS_AUTOROUTER_CLASS_POSTFIX;

    /**
     * Установка дефолтного метода для авторотации по классу.
     * @param string|null
     * @return self
     */
    public function classMethod(string $value = null)
    {
        $this->classMethod = $value;
        return $this;
    }

    /**
     * Установка/сброс префикса класса.
     * @param string|null
     * @return self
     */
    public function classPrefix(string $value = null)
    {
        $this->classPrefix = $value;
        return $this;
    }

    /**
     * Установка/сброс постфикса класса.
     * @param string|null
     * @return self
     */
    public function classPostfix(string $value = null)
    {
        $this->classPostfix = $value;
        return $this;
    }

    /**
     * Генерация обработчика вида сгенерированный класс => дефолтный метод
     * @param string путь
     * @return array [class => method]
     */
    public function generateHandler(string $path): array
    {
        $parts = explode('/', $path);
        $partsNum = count($parts);
        if ($partsNum > 0) {
            if ($partsNum > 1 && empty($parts[0])) {
                array_shift($parts);
                $partsNum--;
            }
            if ($partsNum > 1 && empty($parts[$partsNum - 1])) {
                array_pop($parts);
            }
        }
        foreach ($parts as &$part) {
            if (empty($part)) $part = 'index';
            $part = ucfirst($part);
        }
        $class = $this->classPrefix . implode('\\', $parts) . $this->classPostfix;
        return [$class => $this->classMethod];
    }
}
