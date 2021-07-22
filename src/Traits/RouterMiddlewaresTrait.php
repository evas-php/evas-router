<?php
/**
 * Расширение роутера поддержкой middlewares.
 * @package evas-php\evas-router
 * @author Egor Vasyakin <egor@evas-php.com>
 */
namespace Evas\Router\Traits;

use \InvalidArgumentException;
use Evas\Router\Interfaces\RouterInterface;

trait RouterMiddlewaresTrait
{
    /** @var array middlewares */
    protected $middlewares = [];

    /**
     * Добавление middleware.
     * @param mixed middleware
     * @return RouterInterface
     * @throws InvalidArgumentException
     */
    public function middleware($middleware): RouterInterface
    {
        if (!$this->isCorrectHandlerType($middleware)) {
            throw new InvalidArgumentException(sprintf(
                'Argument 1 $middleware must be a string or an array or a \Closure
                , %s given',
                gettype($middleware)
            ));
        }
        $this->middlewares[] = $middleware;
        return $this;
    }

    /**
     * Добавление middlewares.
     * @param array
     * @return RouterInterface
     */
    public function middlewares(array $middlewares): RouterInterface
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
