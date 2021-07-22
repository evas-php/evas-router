<?php
/**
 * Трейт подключения контроллер-класса резултьта роутера.
 * @package evas-php\evas-router
 * @author Egor Vasyakin <egor@evas-php.com>
 */
namespace Evas\Router\Traits;

use Evas\Base\Interfaces\AppInterface;
use Evas\Http\Interfaces\RequestInterface;
use Evas\Router\Controller;
use Evas\Router\Interfaces\RouterInterface;

/**
 * Константы для свойств трейта по умолчанию.
 */
if (!defined('EVAS_CONTROLLER_CLASS')) define('EVAS_CONTROLLER_CLASS', Controller::class);

trait RouterControllerTrait
{
    /** @var string имя класса контроллера */
    public $controllerClass = EVAS_CONTROLLER_CLASS;

    /** @var RequestInterface объект запроса */
    protected $request;
    /** @var object|null объект приложения */
    protected $app;
    
    /**
     * Установка имени класса контроллера.
     * @param string имя класса
     * @return self
     */
    public function controllerClass(string $controllerClass): RouterInterface
    {
        $this->controllerClass = &$controllerClass;
        return $this;
    }

    /**
     * Получение имени класса контроллера.
     * @return string|null
     */
    public function getControllerClass(): ?string
    {
        return $this->controllerClass;
    }

    /**
     * Установка объекта запроса.
     * @param RequestInterface объект запроса
     * @return self
     */
    public function withRequest(RequestInterface &$request)
    {
        $this->request = &$request;
        return $this;
    }

    /**
     * Установка объекта приложения.
     * @param AppInterface объект приложения
     * @return self
     */
    public function withApp(AppInterface &$app)
    {
        $this->app = &$app;
        return $this;
    }
}
