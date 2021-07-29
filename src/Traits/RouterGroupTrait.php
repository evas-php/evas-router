<?php
/**
 * Расширение роутера поддержкой группировки вложенных роутеров.
 * @package evas-php\evas-router
 * @author Egor Vasyakin <egor@evas-php.com>
 */
namespace Evas\Router\Traits;

use Evas\Router\Interfaces\RouterInterface;
use Evas\Router\Routers\AutoRouterByClass;
use Evas\Router\Routers\AutoRouterByClassMethod;
use Evas\Router\Routers\AutoRouterByFile;
use Evas\Router\Routers\AutoRouterByFunc;
use Evas\Router\Routers\MapRouter;

trait RouterGroupTrait
{
    /**
     * Создание вложенного мапроутера.
     * @param string путь
     * @param callable функция описания роутера
     * @return RouterInterface
     */
    public function map(string $path, $method, callable $callback = null): RouterInterface
    {
        return $this->bindChild((new MapRouter($this)), $path, $method, $callback);
    }

    /**
     * Создание вложенного автороутера по файлу.
     * @param string путь
     * @param callable функция описания роутера
     * @return RouterInterface
     */
    public function autoByFile(string $path, $method, callable $callback = null): RouterInterface
    {
        return $this->bindChild((new AutoRouterByFile($this)), $path, $method, $callback);
    }

    /**
     * Создание вложенного автороутера по кастомной функции.
     * @param string путь
     * @param callable функция описания роутера
     * @return RouterInterface
     */
    public function autoByFunc(string $path, $method, callable $callback = null): RouterInterface
    {
        return $this->bindChild((new AutoRouterByFunc($this)), $path, $method, $callback);
    }

    /**
     * Создание вложенного автороутера по классу.
     * @param string путь
     * @param callable функция описания роутера
     * @return RouterInterface
     */
    public function autoByClass(string $path, $method, callable $callback = null): RouterInterface
    {
        return $this->bindChild((new AutoRouterByClass($this)), $path, $method, $callback);
    }

    /**
     * Создание вложенного автороутера по методу класса.
     * @param string путь
     * @param callable функция описания роутера
     * @return RouterInterface
     */
    public function autoByMethod(string $path, $method, callable $callback = null): RouterInterface
    {
        return $this->bindChild((new AutoRouterByClassMethod($this)), $path, $method, $callback);
    }

    /**
     * Создание и монтирование вложенного роутера.
     * @param RouterInterface вложенный роутер
     * @param string путь
     * @param callable функция описания роутера
     * @return RouterInterface
     */
    public function bindChild(
        RouterInterface $router, string $path, 
        $method, callable $callback = null
    ): RouterInterface
    {

        if (null === $callback) {
            // callback передан 3 аргументом, вместо метода
            if (!is_callable($method)) {
                throw new \InvalidArgumentException(sprintf(
                    'Argument 3 passed to %s() if usage without route method must be callable, %s given',
                    __METHOD__, gettype($method)
                ));
            }
            $callback = $method;
            $method = 'all';
        } else {
            // callback передан 4 аргументом, а метод - 3 аргументом
            if (is_array($method)) {
                array_walk($method, function (&$value) {
                    $value = strtolower($value);
                });
                if (in_array('all', $method)) {
                    $method = 'all';
                } else {
                    $method = array_filter($method, function ($value) {
                        return static::isSupportRestMethod($value);
                    });
                }
            }
            else if (!static::isSupportRestMethod($method)) {
                $method = 'all';
            }
        }

        $callback = $callback->bindTo($router);
        $callback();

        if (!is_array($method)) $method = [$method];
        foreach ($method as $sub) {
            $this->$sub($path . '(:any)', $router);
        }
        return $this;
    }
}
