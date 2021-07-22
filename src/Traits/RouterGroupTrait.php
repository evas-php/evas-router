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
    public function map(string $path, callable $callback): RouterInterface
    {
        return $this->bindChild((new MapRouter($this)), $path, $callback);
    }

    /**
     * Создание вложенного автороутера по файлу.
     * @param string путь
     * @param callable функция описания роутера
     * @return RouterInterface
     */
    public function autoByFile(string $path, callable $callback): RouterInterface
    {
        return $this->bindChild((new AutoRouterByFile($this)), $path, $callback);
    }

    /**
     * Создание вложенного автороутера по кастомной функции.
     * @param string путь
     * @param callable функция описания роутера
     * @return RouterInterface
     */
    public function autoByFunc(string $path, callable $callback): RouterInterface
    {
        return $this->bindChild((new AutoRouterByFunc($this)), $path, $callback);
    }

    /**
     * Создание вложенного автороутера по классу.
     * @param string путь
     * @param callable функция описания роутера
     * @return RouterInterface
     */
    public function autoByClass(string $path, callable $callback): RouterInterface
    {
        return $this->bindChild((new AutoRouterByClass($this)), $path, $callback);
    }

    /**
     * Создание вложенного автороутера по методу класса.
     * @param string путь
     * @param callable функция описания роутера
     * @return RouterInterface
     */
    public function autoByMethod(string $path, callable $callback): RouterInterface
    {
        return $this->bindChild((new AutoRouterByClassMethod($this)), $path, $callback);
    }

    /**
     * Создание и монтирование вложенного роутера.
     * @param RouterInterface вложенный роутер
     * @param string путь
     * @param callable функция описания роутера
     * @return RouterInterface
     */
    public function bindChild(RouterInterface $router, string $path, callable $callback): RouterInterface
    {
        $callback = $callback->bindTo($router);
        $callback();
        return $this->all($path . '(:any)', $router);
    }
}
