<?php
/**
 * Расширение роутера поддержкой алиасов маршрутов.
 * @package evas-php\evas-router
 * @author Egor Vasyakin <egor@evas-php.com>
 */
namespace Evas\Router\Traits;

use Evas\Router\Interfaces\RouterInterface;

trait RouterAliasesTrait
{
    /** @var array маппинг алиасов */
    protected $aliases = [
        ':any' => '.*',
        ':int' => '[0-9]{1,}',
    ];

    /**
     * Добавление алиаса.
     * @param string алиас
     * @param string замена
     * @return RouterInterface
     */
    public function alias(string $alias, string $value): RouterInterface
    {
        $this->aliases[$alias] = $value;
        return $this;
    }

    /**
     * Добавленеи алиасов.
     * @param array
     * @return RouterInterface
     */
    public function aliases(array $aliases): RouterInterface
    {
        foreach ($aliases as $alias => $value) {
            $this->alias($alias, $value);
        }
        return $this;
    }

    /**
     * Получение алиасов.
     * @return array
     */
    public function getAliases(): array
    {
        return $this->aliases;
    }

    /**
     * Применение алиасов.
     * @param string значение
     * @return string значение с заменой алиасов
     */
    public function applyAliases(string $val): string
    {
        $aliases = array_merge($this->aliases, ['/' => '\/']);
        foreach ($aliases as $alias => $value) {
            $val = str_replace($alias, $value, $val);
        }
        return $val;
    }

     /**
     * Подготовка пути к проверке регуляркой.
     * @param string путь
     * @return string путь
     */
    public function preparePath(string $path): string
    {
        $path = $this->applyAliases($path);
        return "/^$path$/";
    }
}
