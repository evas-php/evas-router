<?php
/**
 * @package evas-php\evas-router
 */
namespace Evas\Router\Routers;

use Evas\Router\Routers\BaseAutoRouter;

/**
 * Константы для свойств класса по умолчанию.
 */
if (!defined('EVAS_AUTOROUTER_CLASS_PREFIX')) define('EVAS_AUTOROUTER_CLASS_PREFIX', '');
if (!defined('EVAS_AUTOROUTER_CLASS_POSTFIX')) define('EVAS_AUTOROUTER_CLASS_POSTFIX', '');
if (!defined('EVAS_AUTOROUTER_CLASS_CUSTOM')) define('EVAS_AUTOROUTER_CLASS_CUSTOM', '');

if (!defined('EVAS_AUTOROUTER_METHOD_PREFIX')) define('EVAS_AUTOROUTER_METHOD_PREFIX', '');
if (!defined('EVAS_AUTOROUTER_METHOD_POSTFIX')) define('EVAS_AUTOROUTER_METHOD_POSTFIX', '');

/**
 * Автороутер по классу.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 1.0
 */
class AutoRouterByClassMethod extends BaseAutoRouter
{
    /**
     * @var string префикс класса
     */
    public $classPrefix = EVAS_AUTOROUTER_CLASS_PREFIX;

    /**
     * @var string постфикс класса
     */
    public $classPostfix = EVAS_AUTOROUTER_CLASS_POSTFIX;

    /**
     * @var string кастомный класс для метода
     */
    public $classCustom = EVAS_AUTOROUTER_CLASS_CUSTOM;

    /**
     * @var string префикс метода
     */
    public $methodPrefix = EVAS_AUTOROUTER_METHOD_PREFIX;

    /**
     * @var string постфикс метода
     */
    public $methodPostfix = EVAS_AUTOROUTER_METHOD_POSTFIX;

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
     * Установка/сброс кастомного класса.
     * @param string|null
     * @return self
     */
    public function classCustom(string $value = null)
    {
        $this->classCustom = $value;
        return $this;
    }

    /**
     * Установка/сброс префикса метода.
     * @param string|null
     * @return self
     */
    public function methodPrefix(string $value = null)
    {
        $this->methodPrefix = $value;
        return $this;
    }

    /**
     * Установка/сброс постфикса метода.
     * @param string|null
     * @return self
     */
    public function methodPostfix(string $value = null)
    {
        $this->methodPostfix = $value;
        return $this;
    }

    /**
     * Генерация обработчика сгенерированный класс => сгенерированный extends BaseAutoRouter метод.
     * @param string путь
     * @return array [class => method]
     */
    public function generateHandler(string $path): array
    {
        $parts = explode('/', $path);
        if (empty($parts[0])) array_shift($parts);
        foreach ($parts as &$part) {
            if (empty($part)) $part = 'Index';
            $part = ucfirst($part);
        }
        if (empty($this->classCustom)) {
            $method = array_pop($parts);
            $class = implode('\\', $parts);
            if (empty($class)) $class = 'Index';
            $class = $this->classPrefix . $class . $this->classPostfix;
        } else {
            $method = implode('', $parts);
            $class = $this->classCustom;
        }
        $method = empty($method) ? 'index' : lcfirst($method);
        $method = $this->methodPrefix . $method . $this->methodPostfix;
        return [$class => $method];
    }
}
