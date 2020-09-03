<?php
/**
 * @package evas-php\evas-router
 */
namespace Evas\Router\Traits;

use Evas\Router\Routers\AutoRouterByClass;
use Evas\Router\Routers\AutoRouterByClassMethod;
use Evas\Router\Routers\AutoRouterByFile;
use Evas\Router\Routers\AutoRouterByFunc;
use Evas\Router\Routers\BaseRouter;
use Evas\Router\Routers\MapRouter;

/**
 * Расширение роутера поддержкой группировки роутеров.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 1.0
 */
trait RouterGroupTrait
{
    /**
     * Создание вложенного мапроутера.
     * @param string путь
     * @param callable функция описания роутера
     * @return self
     */
    public function map(string $path, callable $callback): BaseRouter
    {
        return $this->bindChild(MapRouter::class, $path, $callback);
    }

    /**
     * Создание вложенного автороутера по файлу.
     * @param string путь
     * @param callable функция описания роутера
     * @return self
     */
    public function autoByFile(string $path, callable $callback): BaseRouter
    {
        return $this->bindChild(AutoRouterByFile::class, $path, $callback);
    }

    /**
     * Создание вложенного автороутера по кастомной функции.
     * @param string путь
     * @param callable функция описания роутера
     * @return self
     */
    public function autoByFunc(string $path, callable $callback): BaseRouter
    {
        return $this->bindChild(AutoRouterByFunc::class, $path, $callback);
    }

    /**
     * Создание вложенного автороутера по классу.
     * @param string путь
     * @param callable функция описания роутера
     * @return self
     */
    public function autoByClass(string $path, callable $callback): BaseRouter
    {
        return $this->bindChild(AutoRouterByClass::class, $path, $callback);
    }

    /**
     * Создание вложенного автороутера по методу класса.
     * @param string путь
     * @param callable функция описания роутера
     * @return self
     */
    public function autoByMethod(string $path, callable $callback): BaseRouter
    {
        return $this->bindChild(AutoRouterByClassMethod::class, $path, $callback);
    }

    /**
     * Создание и монтирование вложенного роутера.
     * @param string имя класса вложенного роутера
     * @param string путь
     * @param callable функция описания роутера
     * @return self
     */
    public function bindChild(string $className, string $path, callable $callback): BaseRouter
    {
        $router = new $className($this);
        $callback = $callback->bindTo($router);
        $callback();
        return $this->all($path . '(:any)', $router);
    }
}
