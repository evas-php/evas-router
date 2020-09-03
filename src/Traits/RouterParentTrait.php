<?php
/**
 * @package evas-php\evas-router
 */
namespace Evas\Router\Traits;

use Evas\Router\Routers\BaseRouter;

/**
 * Расширение роутера поддержкой родительского роутера.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 1.0
 */
trait RouterParentTrait
{
    /**
     * @var BaseRouter|null родительский роутер
     */
    protected $parent;

    /**
     * Конструктор.
     * @param BaseRouter|null родительский роутер
     */
    public function __construct(BaseRouter &$parent = null)
    {
        if (!empty($parent)) {
            $this->parent = &$parent;
            $this->aliases($parent->getAliases());
            $this->middlewares($parent->getMiddlewares());
            $this->controllerClass($parent->getControllerClass());
            $this->routingResultClass($parent->getRoutingResultClass());
        }
    }

    /**
     * Получение родительского роутера.
     * @return BaseRouter|null
     */
    public function parent(): ?BaseRouter
    {
        return $this->parent;
    }
}
