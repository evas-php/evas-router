<?php
/**
 * @package evas-php/evas-router
 */
namespace Evas\Router\Base;

use Evas\Router\Auto\AutoRouterByClass;
use Evas\Router\Auto\AutoRouterByClassMethod;
use Evas\Router\Auto\AutoRouterByFile;
use Evas\Router\Auto\AutoRouterByFunc;
use Evas\Router\Base\BaseRouter;
use Evas\Router\Map\MapRouter;

/**
 * Расширение роутера поддержкой группировки роутеров.
 * @author Egor Vasyakin <e.vasyakin@itevas.ru>
 * @since 1.0
 */
trait RouterGroupTrait
{
    /**
     * Установка вложенного такого же роутера для группы маршрутов.
     * @param string путь
     * @param array|null маршруты
     * @return BaseRouter
     */
    public function group(string $path, string $method = null): BaseRouter
    {
        return $this->child(static::class, $path, $method);
    }

    /**
     * Установка вложенного маппинг-роутера для группы маршрутов.
     * @param string путь
     * @param array|null маршруты
     * @return MapRouter
     */
    public function map(string $path, string $method = null): MapRouter
    {
        return $this->child(MapRouter::class, $path, $method);
    }

    /**
     * Установка автороутера.
     * @param string|null путь
     * @param string|null метод
     * @return AutoRouter
     */
    public function auto(string $path = null, string $method = null): AutoRouter
    {
        return $this->child(AutoRouter::class, $path, $method);
    }

    /**
     * Установка автороутера по классу.
     * @param string|null путь
     * @param string|null метод
     * @return AutoRouter
     */
    public function autoByClass(string $path = null, string $method = null): AutoRouterByClass
    {
        return $this->child(AutoRouterByClass::class, $path, $method);
    }

    /**
     * Установка автороутера по методу класса.
     * @param string|null путь
     * @param string|null метод
     * @return AutoRouter
     */
    public function autoByClassMethod(string $path = null, string $method = null): AutoRouterByClassMethod
    {
        return $this->child(AutoRouterByClassMethod::class, $path, $method);
    }

    /**
     * Установка автороутера по файла.
     * @param string|null путь
     * @param string|null метод
     * @return AutoRouter
     */
    public function autoByFile(string $path = null, string $method = null): AutoRouterByFile
    {
        return $this->child(AutoRouterByFile::class, $path, $method);
    }

    /**
     * Установка автороутера по функции.
     * @param callable обработчик
     * @param string|null путь
     * @param string|null метод
     * @return AutoRouter
     */
    public function autoBuFunc(callable $func, string $path = null, string $method = null): AutoRouterByFunc
    {
        return $this->child(AutoRouterByFunc::class, $path, $method)->routingFunc($func);
    }

    /**
     * Установка дочернего роутера.
     * @param string имя класса дочернего ротура
     * @param string|null путь
     * @param string|null метод
     * @return BaseRouter дочерний роутер
     */
    protected function child(string $childClass, string $path = null, string $method = null): BaseRouter
    {
        $router = new $childClass($this);
        assert($router instanceof BaseRouter);
        $this->route($method ?? 'ALL', "$path(:any)", $router); // устанавливаем ребенка обработчиком
        return $router
            ->routingResultClass($this->getRoutingResultClass()) // копируем имя класса роута ребенку
            ->middlewares($this->getMiddlewares()) // копируем middlewares ребенку
            ->aliases($this->getAliases()) // копируем шаблоны ребенку
            ->default($this->getDefault()); // копируем дефолтный обработчик ребенку
    }
}
