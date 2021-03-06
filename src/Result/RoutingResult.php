<?php
/**
 * @package evas-php\evas-router
 */
namespace Evas\Router\Result;

use \Closure;
use Evas\Base\Helpers\PhpHelper;
use Evas\Router\Result\Exception\RoutingResultHandleException;
use Evas\Router\Result\Exception\RoutingResultHandleHandlerException;
use Evas\Router\Result\Exception\RoutingResultHandleMiddlewareException;
use Evas\Router\Result\RoutingResultInterface;
use Evas\Router\Traits\RouterControllerTrait;

/**
 * Класс результата роутинга с обработкой результата.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 1.0
 */
class RoutingResult implements RoutingResultInterface
{
    /**
     * Подключаем поддержку контроллера результата роутинга.
     */
    use RouterControllerTrait;

    /**
     * @var array middlewares
     */
    public $middlewares;
    
    /**
     * @var mixed обработчик
     */
    public $handler;

    /**
     * @var array аргументы, разобранные в роутере
     */
    public $args = [];

    /**
     * @var array подготовленные обработчики
     */
    protected $preparedHandlers;

    /**
     * Конструктор.
     * @param mixed|null обработчик
     * @param array|null аргументы обработчика
     * @param array|null middlewares
     * @param array|null класс контроллера
     */
    public function __construct($handler = null, array $args = null, array $middlewares = null, string $controllerClass = null)
    {
        $this->handler = &$handler;
        if (!empty($args)) $this->args = &$args;
        if (!empty($middlewares)) $this->middlewares = &$middlewares;
        if (!empty($controllerClass)) $this->controllerClass($controllerClass);
    }

    /**
     * Подготовка обработчика в виде пути к файлу.
     * @param string путь к файлу
     * @throws RoutingResultHandleException
     */
    protected function prepareFile(string $handler)
    {
        $class = $this->newController();
        if (false === $class->canRender($handler)) {
            throw new RoutingResultHandleException("File \"$handler\" not found");
        }
        $this->preparedHandlers[] = [[$class, 'render'], [$handler, $this->args]];
    }

    /**
     * Подготовка обработчика в виде замыкания.
     * @param Closure замыкание
     */
    protected function prepareClosure(Closure $handler)
    {
        $class = $this->newController();
        $method = $handler->bindTo($class);
        $this->preparedHandlers[] = [$method, $this->args];
    }

    /**
     * Подготовка обработчика в виде имени класса.
     * @param array [Класс, Метод] или [Класс]
     * @throws RoutingResultHandleException
     */
    protected function prepareClass(array $handler)
    {
        assert(PhpHelper::isAssoc($handler));
        foreach ($handler as $className => $method) {
            if (!class_exists($className, true)) {
                throw new RoutingResultHandleException("Class \"$className\" not found");
            }
            $class = $this->newController($className);
            if (!method_exists($class, $method)) {
                throw new RoutingResultHandleException("Method \"$method\" in class \"$className\" not found");
            }
            $this->preparedHandlers[] = [[$class, $method], $this->args];
            return;
        }
    }

    /**
     * Подготовка единичного обработчика.
     * @throws RoutingResultHandleException
     */
    protected function prepareSingle($handler)
    {
        if ($handler instanceof \Closure) $this->prepareClosure($handler);
        else if (is_string($handler)) $this->prepareFile($handler);
        else if (PhpHelper::isAssoc($handler)) $this->prepareClass($handler);
    }

    /**
     * Подготовка обработчика в виде списка обработчиков.
     * @throws RoutingResultHandleException
     * @param array обработчики
     */
    protected function prepareList(array $handler)
    {
        foreach ($handler as &$subHandler) {
            $this->prepareSingle($subHandler);
        }
    }


    /**
     * Подготовка обработчика.
     * @throws RoutingResultHandleMiddlewareException
     * @throws RoutingResultHandleHandlerException
     * @return self
     */
    public function prepare()
    {
        $this->preparedHandlers = [];
        if (!empty($this->middlewares)) {
            try {
                $this->prepareList($this->middlewares);
            } catch (RoutingResultHandleException $e) {
                throw new RoutingResultHandleMiddlewareException($e->getMessage(), $e->getCode());
            }
        }
        try {
            if (PhpHelper::isNumericArray($this->handler)) $this->prepareList($this->handler);
            else $this->prepareSingle($this->handler);
        } catch (RoutingResultHandleException $e) {
            throw new RoutingResultHandleHandlerException($e->getMessage(), $e->getCode());
        }
        return $this;
    }


    /**
     * Вызов обработчика маршрута.
     */
    public function handle()
    {
        // подготовка обработчика
        if (! is_array($this->preparedHandlers)) $this->prepare();
        // запуск обработчика
        foreach ($this->preparedHandlers as &$handler) {
            list($method, $params) = $handler;
            if (false === call_user_func_array($method, $params)) break;
        }
    }
}
