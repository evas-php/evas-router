<?php
/**
 * Расширение маппинг роутера поддержкой REST синтаксиса.
 * @package evas-php\evas-route
 * @author Egor Vasyakin <egor@evas-php.com>
 */
namespace Evas\Router\Traits;

use Evas\Router\Interfaces\RouterInterface;

trait RouterRestTrait
{
    /**
     * Установка маршрута/маршрутов HTTP метода GET.
     * @param string|array путь или массив маршрутов
     * @param mixed|null обработчик пути
     * @return self
     */
    public function get($path, $handler = null): RouterInterface
    {
        return $this->restRoute('GET', $path, $handler);
    }

    /**
     * Установка маршрута/маршрутов HTTP метода POST.
     * @param string|array путь или массив маршрутов
     * @param mixed|null обработчик пути
     * @return self
     */
    public function post($path, $handler = null): RouterInterface
    {
        return $this->restRoute('POST', $path, $handler);
    }

    /**
     * Установка маршрута/маршрутов HTTP метода PUT.
     * @param string|array путь или массив маршрутов
     * @param mixed|null обработчик пути
     * @return self
     */
    public function put($path, $handler = null): RouterInterface
    {
        return $this->restRoute('PUT', $path, $handler);
    }

    /**
     * Установка маршрута/маршрутов HTTP метода DELETE.
     * @param string|array путь или массив маршрутов
     * @param mixed|null обработчик пути
     * @return self
     */
    public function delete($path, $handler = null): RouterInterface
    {
        return $this->restRoute('DELETE', $path, $handler);
    }

    /**
     * Установка маршрута/маршрутов HTTP метода PATCH.
     * @param string|array путь или массив маршрутов
     * @param mixed|null обработчик пути
     * @return self
     */
    public function patch($path, $handler = null): RouterInterface
    {
        return $this->restRoute('PATCH', $path, $handler);
    }

    /**
     * Установка маршрута/маршрутов HTTP метода OPTIONS.
     * @param string|array путь или массив маршрутов
     * @param mixed|null обработчик пути
     * @return self
     */
    public function options($path, $handler = null): RouterInterface
    {
        return $this->restRoute('OPTIONS', $path, $handler);
    }

    /**
     * Установка маршрута/маршрутов для всех HTTP методов.
     * @param string|array путь или массив маршрутов
     * @param mixed|null обработчик пути
     * @return self
     */
    public function all($path, $handler = null): RouterInterface
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
