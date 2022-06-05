<?php
/**
 * Трейт подключения контроллер-класса резултьта роутера.
 * @package evas-php\evas-router
 * @author Egor Vasyakin <egor@evas-php.com>
 */
namespace Evas\Router\Traits;

use Evas\Base\App;
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

    /** @var string директория отображения */
    protected $viewsDir;
    
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
     * Установка view директории контроллера.
     * @param string|null директория или путь
     */
    public function viewsDir(string $viewsDir = null)
    {
        if ($viewsDir) $this->viewsDir = App::resolveByApp($viewsDir);
        return $this;
    }

    /**
     * Получение view директории контроллера.
     * @return string|null директория или путь
     */
    public function getViewsDir(): ?string
    {
        return $this->viewsDir;
    }
}
