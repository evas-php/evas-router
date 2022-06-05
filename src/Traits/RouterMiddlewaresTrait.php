<?php
/**
 * Расширение роутера поддержкой middlewares.
 * @package evas-php\evas-router
 * @author Egor Vasyakin <egor@evas-php.com>
 */
namespace Evas\Router\Traits;

use \InvalidArgumentException;
use Evas\Router\Exceptions\RouterException;
use Evas\Router\Interfaces\RouterInterface;

// Максимальный рекурсивный лимит получения middlewares каскадом вверх
if (!defined('EVAS_ROUTER_MIDDLEWARES_DEEP_LIMIT')) {
    define('EVAS_ROUTER_MIDDLEWARES_DEEP_LIMIT', 5);
}

trait RouterMiddlewaresTrait
{
    /** @var array middlewares */
    public $middlewares = [];

    /**
     * Добавление middleware/middlewares.
     * @param string|\Closure|array ...middlewares
     * @return RouterInterface
     * @throws InvalidArgumentException
     */
    public function middleware(...$middlewares): RouterInterface
    {
        var_dump($middlewares);
        foreach ($middlewares as &$middleware) {
            if (!$this->isCorrectHandlerType($middleware)) {
                throw new InvalidArgumentException(sprintf(
                    'Argument 1 $middleware must be a string or an array or a \Closure
                    , %s given',
                    gettype($middleware)
                ));
            }
            $this->middlewares[] = $middleware;
        }
        return $this;
    }

    /**
     * Рекурсивное получение middlewares каскадом вверх.
     * @return array
     */
    public function getMiddlewares(): array
    {
        $middlewares = $this->middlewares;
        $parent = $this;
        $tries = 1;
        while ($parent = $parent->parent) {
            $middlewares = array_merge($middlewares, $parent->middlewares);
            $tries++;
            if ($tries > EVAS_ROUTER_MIDDLEWARES_DEEP_LIMIT) {
                throw new RouterException('Router middleware deep limit exceeded');
            }
        }
        return $middlewares;
    }
}
