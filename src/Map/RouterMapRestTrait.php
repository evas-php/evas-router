<?php
/**
 * @package evas-php/evas-route
 */
namespace Evas\Router\Map;

/**
 * Расширение маппинг роутера поддержкой REST синтаксиса.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 1.0
 */
trait RouterMapRestTrait
{
    /**
     * Установка маршрута/маршрутов GET.
     * @param string|array путь или массив маршрутов
     * @param mixed|null обработчик пути
     * @return self
     */
    public function get($path, $handler = null)
    {
        return $this->_setRestRoute('GET', $path, $handler);
    }

    /**
     * Установка маршрута/маршрутов POST.
     * @param string|array путь или массив маршрутов
     * @param mixed|null обработчик пути
     * @return self
     */
    public function post($path, $handler = null)
    {
        return $this->_setRestRoute('POST', $path, $handler);
    }

    /**
     * Установка маршрута/маршрутов PUT.
     * @param string|array путь или массив маршрутов
     * @param mixed|null обработчик пути
     * @return self
     */
    public function put($path, $handler = null)
    {
        return $this->_setRestRoute('PUT', $path, $handler);
    }

    /**
     * Установка маршрута/маршрутов DELETE.
     * @param string|array путь или массив маршрутов
     * @param mixed|null обработчик пути
     * @return self
     */
    public function delete($path, $handler = null)
    {
        return $this->_setRestRoute('DELETE', $path, $handler);
    }

    /**
     * Установка маршрута/маршрутов PATCH.
     * @param string|array путь или массив маршрутов
     * @param mixed|null обработчик пути
     * @return self
     */
    public function patch($path, $handler = null)
    {
        return $this->_setRestRoute('PATCH', $path, $handler);
    }

    /**
     * Установка маршрута/маршрутов для всех методов.
     * @param string|array путь или массив маршрутов
     * @param mixed|null обработчик пути
     * @return self
     */
    public function all($path, $handler = null)
    {
        return $this->_setRestRoute('ALL', $path, $handler);
    }

    /**
     * Установка маршрута/маршрутов.
     * @param string метод
     * @param string|array путь или массив маршрутов
     * @param mixed|null обработчик пути
     * @return self
     */
    private function _setRestRoute(string $method, $path, $handler = null)
    {
        assert(is_string($path) || is_array($path));
        if (is_string($path) && null !== $handler) {
            $this->route($method, $path, $handler);
        }
        if (is_array($path)) foreach ($path as $subpath => $handler) {
            $this->_setRestRoute($method, $subpath, $handler);
        }
        return $this;
    }
}
