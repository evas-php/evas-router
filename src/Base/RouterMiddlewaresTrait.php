<?php
/**
 * @package evas-php/evas-router
 */
namespace Evas\Router\Base;

/**
 * Расширение роутера поддержкой middlewares.
 * @author Egor Vasyakin <e.vasyakin@itevas.ru>
 * @since 1.0
 */
trait RouterMiddlewaresTrait
{
    /**
     * @var array middlewares
     */
    protected $middlewares = [];

    /**
     * Добавление middleware.
     * @param string|array|callable
     * @return self
     */
    public function middleware($middleware)
    {
        assert(is_string($middleware) || is_array($middleware) || is_callable($middleware));
        $this->middlewares[] = $middleware;
        return $this;
    }

    /**
     * Добавление middlewares.
     * @param array
     * @return self
     */
    public function middlewares(array $middlewares)
    {
        foreach ($middlewares as &$middleware) {
            $this->middleware($middleware);
        }
        return $this;
    }

    /**
     * Получение middlewares.
     * @return array
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }
}
