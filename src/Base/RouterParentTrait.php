<?php
/**
 * @package evas-php/evas-router
 */
namespace Evas\Router\Base;

use Evas\Router\Base\BaseRouter;

/**
 * Расширение роутера поддержкой родительского роутера.
 * @author Egor Vasyakin <e.vasyakin@itevas.ru>
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
        $this->parent = &$parent;
    }

    /**
     * Установка родительского роутера.
     * @param BaseRouter
     * @return self
     */
    public function setParent(BaseRouter &$parent)
    {
        $this->parent = &$parent;
        return $this;
    }

    /**
     * Получение родительского роутера.
     * @return BaseRouter|null
     */
    public function getParent(): ?BaseRouter
    {
        return $this->parent;
    }

    /**
     * Переход к родительскому роутеру.
     * @return BaseRouter|null
     */
    public function next(): ?BaseRouter
    {
        return $this->getParent();
    }
}
