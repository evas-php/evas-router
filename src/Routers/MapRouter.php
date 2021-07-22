<?php
/**
 * Роутер по маппингу маршрутов.
 * @package evas-php\evas-router
 * @author Egor Vasyakin <egor@evas-php.com>
 */
namespace Evas\Router\Routers;

use \Exception;
use Evas\Base\Interfaces\AppInterface;
use Evas\Http\Interfaces\RequestInterface;
use Evas\Router\Exceptions\RouterException;
use Evas\Router\Exceptions\RouterResultException;
use Evas\Router\Interfaces\RouterInterface;
use Evas\Router\Interfaces\RouterResultInterface;
use Evas\Router\RouterResult;
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

    protected $parent;
    protected $default;
    protected $request;
    protected $app;

    public function __construct(RouterInterface &$parent = null)
    {
        if (!empty($parent)) {
            $this->parent = &$parent;
            $this->aliases($parent->getAliases());
            $this->controllerClass($parent->getControllerClass());
            $this->middlewares($parent->getMiddlewares());
        }
    }

    public function default($handler): RouterInterface
    {
        $this->default = &$handler;
        return $this;
    }

    public function routing(RequestInterface $request, AppInterface $app = null): ?RouterResultInterface
    {
        $this->withRequest($request);
        if (!empty($app)) $this->withApp($app);
        if (empty($this->app)) {
            throw new RouterException('Router not has app');
        }
        return $this->realRouting($request->getMethod(), $request->getPath());
    }

    public function newResult($handler, array $args = null): ?RouterResultInterface
    {
        $result = new RouterResult($handler, $args, $this->middlewares);
        $result->controllerClass($this->controllerClass);
        $result->withApp($this->app);
        $result->withRequest($this->request);
        $result->resolve();
        return $result;
        // try {
        //     $result->resolve();
        //     return $result;
        // } catch (Exception $e) {
        //     return null;
        // }
    }

    protected function realRouting(string $method, string $uri, array $args = null): ?RouterResultInterface
    {
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

    protected function mapRouting(string $method, string $uri, array $args = null): ?RouterResultInterface
    {
        $routes = $this->getRoutesByMethodWithAll($method);
        foreach ($routes as $path => &$handler) {
            if (preg_match($this->preparePath($path), $uri, $matches)) {
                array_shift($matches);
                $args = array_merge($args ?? [], $matches);
                if ($handler instanceof RouterInterface) {
                    $handlerUri = array_pop($args) ?? '';
                    $handler->withApp($this->app);
                    $handler->withRequest($this->request);
                    $result = $handler->realRouting($method, $handlerUri, $args);
                    if (empty($result)) continue;
                    else return $result;
                }
                return $this->newResult($handler, $args);
            }
        }
        return null;
    }
}
