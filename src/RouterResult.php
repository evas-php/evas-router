<?php
/**
 * Класс результата роутинга.
 * @package evas-php\evas-router
 * @author Egor Vasyakin <egor@evas-php.com>
 */
namespace Evas\Router;

use Evas\Base\App;
use Evas\Base\Exceptions\FileNotFoundException;
use Evas\Base\Help\PhpHelp;
use Evas\Http\Interfaces\RequestInterface;
use Evas\Router\Exceptions\RouterResultException;
use Evas\Router\Interfaces\RouterResultInterface;
use Evas\Router\Traits\RouterControllerTrait;

class RouterResult implements RouterResultInterface
{
    /**
     * Подключаем поддержку контроллера результата роутинга.
     */
    use RouterControllerTrait {
        RouterControllerTrait::controllerClass as private _controllerClass;
    }
    
    /** @var mixed обработчик */
    public $handler;

    /** @var array аргументы uri, разобранные в роутере */
    public $args = [];

    /** @var array middlewares */
    public $middlewares;

    /** @var array подготовленные обработчики */
    protected $preparedHandlers;

    /** @var array маппинг контроллеров для избежания дубликатов */
    protected $controllersMap = [];

    /** @var mixed возвращенный результат последнего обработчика */
    public $returned;

    /**
     * Конструктор.
     * @param mixed|null обработчик
     * @param array|null аргументы обработчика
     * @param array|null middlewares
     */
    public function __construct($handler = null, array $args = null, array $middlewares = null)
    {
        $this->handler = &$handler;
        if (!empty($args)) $this->args = &$args;
        if (!empty($middlewares)) $this->middlewares = &$middlewares;
    }

    /**
     * Переопределяем установку имени класса контроллера.
     * @param string имя класса
     * @return self
     */
    public function controllerClass(string $controllerClass): RouterResult
    {
        $this->controllerClass = &$controllerClass;
        return $this;
    }

    /**
     * Создание экземпляра класса контроллера.
     * @param string|null имя класса контроллера
     * @return object
     * @throws FileNotFoundException
     */
    public function newController(string $controllerClass = null): object
    {
        if (empty($controllerClass)) {
            $controllerClass = $this->getControllerClass();
        }
        if (!empty($this->controllersMap[$controllerClass])) {
            return $this->controllersMap[$controllerClass];
        }
        if (!class_exists($controllerClass, true)) {
            throw new FileNotFoundException("Controller class \"$controllerClass\" not found");
        }
        $controller = new $controllerClass($this->request, $this->viewsDir);
        $this->controllersMap[$controllerClass] = &$controller;
        return $controller;
    }

    /**
     * Подготовка обработчика в виде пути к файлу.
     * @param string путь к файлу
     * @throws FileNotFoundException
     */
    protected function prepareFile(string &$handler)
    {
        $class = $this->newController();
        if (!($class instanceof Controller)) {
            throw new RouterResultException(sprintf(
                'Controller class for file or \Closure must be 
                instance or child of the %s, %s given',
                Controller::class,
                $this->getControllerClass()
            ));
        }
        $class->throwIfNotCanView($handler);
        $this->preparedHandlers[] = [[$class, 'view'], [$handler, $this->args]];
    }

    /**
     * Подготовка обработчика в виде замыкания.
     * @param \Closure замыкание
     */
    protected function prepareClosure(\Closure &$handler)
    {
        $class = $this->newController();
        $method = $handler->bindTo($class);
        $this->preparedHandlers[] = [$method, $this->args];
    }

    /**
     * Подготовка обработчика в виде метода класса.
     * @param array обработчик вида ['class' => 'method']
     * @throws FileNotFoundException
     * @throws RouterResultException
     */
    protected function prepareClass(array &$handler)
    {
        foreach ($handler as $className => $method) {
            if (!class_exists($className, true)) {
                throw new FileNotFoundException("Not found class $className");
            }
            $class = $this->newController($className);
            if (!method_exists($class, $method)) {
                throw new RouterResultException("Class $className does not contain method $method");
            }
            $handler = [$class, $method];
            if (!is_callable($handler)) {
                throw new RouterResultException(sprintf(
                    'Is not callable class method %s::%s', get_class($class), $method
                ));
            }
            $this->preparedHandlers[] = [$handler, $this->args];
            return;
        }
    }

    /**
     * Подготовка единичного обработчика.
     * @param mixed обработчик
     */
    protected function prepareSingle(&$handler)
    {
        if ($handler instanceof \Closure) $this->prepareClosure($handler);
        else if (is_string($handler)) $this->prepareFile($handler);
        else if (PhpHelp::isAssoc($handler)) $this->prepareClass($handler);
    }

    /**
     * Подготовка обработчика в виде списка обработчиков.
     * @param array обработчики
     */
    protected function prepareList(array &$handlers)
    {
        foreach ($handlers as &$handler) {
            $this->prepareSingle($handler);
        }
    }


    /**
     * Подготовка обработчика/обработчиков.
     * @return self
     * @throws RouterResultException
     */
    public function prepare()
    {
        $this->preparedHandlers = [];
        if (!empty($this->middlewares)) {
            try {
                $this->prepareList($this->middlewares);
            } catch (\Exception $e) {
                throw new RouterResultException(sprintf(
                    'The route middleware could not be resolved: %s', 
                    $e->getMessage()), $e->getCode(), $e->getPrevious()
                );
            }
        }
        try {
            if (PhpHelp::isNumericArray($this->handler)) 
                $this->prepareList($this->handler);
            else 
                $this->prepareSingle($this->handler);
        } catch (\Exception $e) {
            throw new RouterResultException(sprintf(
                'The route handler could not be resolved: %s', 
                $e->getMessage()), $e->getCode(), $e->getPrevious()
            );
        }
        return $this;
    }


    /**
     * Вызов обработчика маршрута.
     */
    public function resolve()
    {
        // подготовка обработчика/обработчиков
        if (!is_array($this->preparedHandlers)) $this->prepare();
        // запуск обработчика
        $returned = null;
        foreach ($this->preparedHandlers as &$handler) {
            list($method, $args) = $handler;
            $returned = call_user_func_array($method, $args);
            // if (false === $returned) break;
            if (false === $returned) {
                throw new RouterResultException('Route handler returned false');
            }
        }
        $this->returned = $returned;
    }
}
