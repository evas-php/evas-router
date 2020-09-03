<?php
/**
 * @package evas-php\evas-route
 */
namespace Evas\Router\Traits;

/**
 * Расширение маппинг роутера поддержкой REST синтаксиса.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 1.0
 */
trait RouterRestTrait
{
    /**
     * Установка маршрута/маршрутов GET.
     * @param string|array путь или массив маршрутов
     * @param mixed|null обработчик пути
     * @return self
     */
    public function get($path, $handler = null)
    {
        return $this->restRoute('GET', $path, $handler);
    }

    /**
     * Установка маршрута/маршрутов POST.
     * @param string|array путь или массив маршрутов
     * @param mixed|null обработчик пути
     * @return self
     */
    public function post($path, $handler = null)
    {
        return $this->restRoute('POST', $path, $handler);
    }

    /**
     * Установка маршрута/маршрутов PUT.
     * @param string|array путь или массив маршрутов
     * @param mixed|null обработчик пути
     * @return self
     */
    public function put($path, $handler = null)
    {
        return $this->restRoute('PUT', $path, $handler);
    }

    /**
     * Установка маршрута/маршрутов DELETE.
     * @param string|array путь или массив маршрутов
     * @param mixed|null обработчик пути
     * @return self
     */
    public function delete($path, $handler = null)
    {
        return $this->restRoute('DELETE', $path, $handler);
    }

    /**
     * Установка маршрута/маршрутов PATCH.
     * @param string|array путь или массив маршрутов
     * @param mixed|null обработчик пути
     * @return self
     */
    public function patch($path, $handler = null)
    {
        return $this->restRoute('PATCH', $path, $handler);
    }

    /**
     * Установка маршрута/маршрутов для всех методов.
     * @param string|array путь или массив маршрутов
     * @param mixed|null обработчик пути
     * @return self
     */
    public function all($path, $handler = null)
    {
        return $this->restRoute('ALL', $path, $handler);
    }

    /**
     * Установка маршрута/маршрутов.
     * @param string метод
     * @param string|array путь или массив маршрутов
     * @param mixed|null обработчик пути
     * @return self
     */
    protected function restRoute(string $method, $path, $handler = null)
    {
        assert(is_string($path) || is_array($path));
        if (is_string($path) && null !== $handler) {
            $this->route($method, $path, $handler);
        }
        if (is_array($path)) foreach ($path as $subpath => $handler) {
            $this->restRoute($method, $subpath, $handler);
        }
        return $this;
    }
}
