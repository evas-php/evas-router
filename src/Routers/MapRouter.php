<?php
/**
 * Роутер по маппингу маршрутов.
 * @package evas-php\evas-router
 * @author Egor Vasyakin <egor@evas-php.com>
 */
namespace Evas\Router\Routers;

use \Exception;
use Evas\Http\HttpRequest;
use Evas\Http\Interfaces\RequestInterface;

use Evas\Router\Exceptions\RouterException;
use Evas\Router\Exceptions\RouterResultException;
use Evas\Router\Interfaces\RouterInterface;
use Evas\Router\Interfaces\RouterResultInterface;
use Evas\Router\RouterResult;
use Evas\Router\Routers\NestedRouterWrap;


use Evas\Router\Traits\RouterAliasesTrait;
use Evas\Router\Traits\RouterControllerTrait;
use Evas\Router\Traits\RouterGroupTrait;
use Evas\Router\Traits\RouterMiddlewaresTrait;
use Evas\Router\Traits\RouterRestTrait;
use Evas\Router\Traits\RouterRoutesTrait;

class MapRouter implements RouterInterface
{
    use RouterAliasesTrait, RouterControllerTrait, RouterGroupTrait,
    RouterMiddlewaresTrait, RouterRestTrait, RouterRoutesTrait;

    /** @var RouterInterface|null родительский роутер */
    protected $parent;
    /** @var mixed обработчик маршрута по умолчанию */
    protected $default;

    /**
     * Конструктор.
     * @param RouterInterface|null родительский роутер
     */
    public function __construct(RouterInterface &$parent = null)
    {
        if (!empty($parent)) {
            $this->parent = &$parent;
            $this->aliases($parent->getAliases());
            $this->controllerClass($parent->getControllerClass());
            $this->viewDir($parent->getViewDir());
        }
    }

    /**
     * Установка обработчика маршрута по умолчанию.
     * @param mixed обработчик
     * @return self
     */
    public function default($handler): RouterInterface
    {
        $this->default = &$handler;
        return $this;
    }

    /**
     * Получение объекта результата роутинга.
     * @param mixed обработчик
     * @param array|null ааргументы обработчика
     * @return RouterResultInterface результат роутинга
     */
    public function newResult($handler, array $args = null): RouterResultInterface
    {
        $result = new RouterResult($handler, $args, $this->getMiddlewares());
        $result->controllerClass($this->controllerClass);
        $result->withRequest($this->request);
        $result->viewDir($this->viewDir);
        $result->resolve();
        return $result;
        // try {
        //     $result->resolve();
        //     return $result;
        // } catch (Exception $e) {
        //     return null;
        // }
    }

    /**
     * Роутинг по маппингу маршрутов.
     * @param string метод
     * @param string uri
     * @param array|null аргументы обработчика результата
     * @return RouterResultInterface|null результат роутинга
     */
    protected function mapRouting(string $method, string $uri, array $args = null): ?RouterResultInterface
    {
        $routes = $this->getRoutesByMethodWithAll($method);
        foreach ($routes as $path => &$handler) {
            if (preg_match($this->preparePath($path), $uri, $matches)) {
                array_shift($matches);
                $args = array_merge($args ?? [], $matches);
                // отложенная сборка и вызов вложенного роутера
                if ($handler instanceof NestedRouterWrap) {
                    $handler = $handler->build();
                    $handlerUri = array_pop($args) ?? '';
                    $handler->withRequest($this->request);
                    $result = $handler->routing($handlerUri, $method, $args);
                    if (empty($result)) continue;
                    else return $result;
                }
                return $this->newResult($handler, $args);
            }
        }
        return null;
    }

    /**
     * Роутинг по uri и методу.
     * @param string uri
     * @param string|null метод
     * @param array|null аргументы обработчика результата
     * @return RouterResultInterface|null результат
     * @throws RouterException
     */
    public function routing(string $uri, string $method = null, array $args = null): ?RouterResultInterface
    {
        if (empty($method)) $method = 'GET';
        if (empty($this->request)) {
            $request = (new HttpRequest)->withMethod($method)->withUri($uri);
            $this->withRequest($request);
        }
        try {
            $result = $this->mapRouting($method, $uri, $args);
        } catch (RouterResultException $e) {
            if (!empty($this->default)) {
                return $this->newResult($this->default, ['exception' => $e]);
            }
            throw new RouterResultException($e->getMessage(), $e->getCode(), $e->getPrevious());
        }
        if (empty($result) && method_exists($this, 'autoRouting')) {
            $result = $this->autoRouting($uri, $args);
        }
        if (!empty($result)) return $result;
        if (!empty($this->default)) return $this->newResult($this->default);
        if (!empty($this->parent)) return null;
        throw new RouterException('404. Not Found');
    }

    /**
     * Роутинг по объекту запроса.
     * @param RequestInterface объект запроса
     * @return RouterResultInterface|null результат
     */
    public function requestRouting(RequestInterface $request): ?RouterResultInterface
    {
        $this->withRequest($request);
        return $this->routing($request->getPath(), $request->getMethod());
    }
}
