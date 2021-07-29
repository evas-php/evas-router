<?php
/**
 * Расширение маппинг роутера поддержкой REST синтаксиса.
 * @package evas-php\evas-route
 * @author Egor Vasyakin <egor@evas-php.com>
 */
namespace Evas\Router\Traits;

use Evas\Base\Help\PhpHelp;
use Evas\Router\Interfaces\RouterInterface;

// список доступных REST-методов
if (!defined('EVAS_ROUTER_REST_METHODS')) {
    define('EVAS_ROUTER_REST_METHODS', [
        'GET', 'POST', 'PUT', 'DELETE', 'PATCH', 'OPTIONS'
    ]);
} else {
    if (!PhpHelp::isNumericArray(EVAS_ROUTER_REST_METHODS)) {
        throw new \RuntimeException(sprintf(
            'constant EVAS_ROUTER_REST_METHODS must be a numeric array, %s given',
            PhpHelp::getType(EVAS_ROUTER_REST_METHODS, true)
        ));
    }
}

trait RouterRestTrait
{
    /** @static array список доступных REST-методов */
    protected static $restMethods = EVAS_ROUTER_REST_METHODS;

    /** @static bool была ли подготовка доступных REST-методов */
    protected static $restMethodsPrepared = false;

    /**
     * Установка маршрута/маршрутов.
     * @param string метод
     * @param string|array путь или массив маршрутов
     * @param mixed|null обработчик пути
     * @return self
     * @throws \InvalidArgumentException
     */
    protected function restRoute(string $method, $path, $handler = null)
    {
        if (!is_string($path) && !is_array($path)) {
            throw new \InvalidArgumentException(sprintf(
                'Argument 1 passed to %s() must be string or assoc array, %s given',
                __METHOD__, PhpHelp::getType($path, true)
            ));
        }
        if (is_string($path) && null !== $handler) {
            $this->route($method, $path, $handler);
        }
        if (is_array($path)) foreach ($path as $subpath => $handler) {
            $this->restRoute($method, $subpath, $handler);
        }
        return $this;
    }

    /**
     * Получение поддерживаемых REST-методов.
     * @return array
     */
    public static function getRestMethods(): array
    {
        if (!static::$restMethodsPrepared) {
            array_walk(static::$restMethods, function (&$value) {
                $value = strtolower($value);
            });
            static::$restMethods[] = 'all';
            static::$restMethods = array_unique(static::$restMethods);
            static::$restMethodsPrepared = true;
        }
        return static::$restMethods;
    }

    /**
     * Проверка поддержки REST-метода.
     * @param string метод
     * @return bool
     */
    public static function isSupportRestMethod(string $method): bool
    {
        return in_array(strtolower($method), static::getRestMethods());
    }


    /**
     * Магия для установка маршрута/маршрутов доступного REST-метода.
     * @param string имя REST-метода
     * @param array|null параметры маршрута
     * @return self
     * @throws \BadMethodCallException
     */
    public function __call(string $name, array $args = null)
    {
        if (static::isSupportRestMethod($name)) {
            return $this->restRoute($name, ...$args);
        }
        throw new \BadMethodCallException(sprintf(
            'Call to undefined method %s::%s()',
            get_called_class(), $name
        ));
    }
}
