<?php
/**
 * @package evas-php/evas-router
 */
namespace Evas\Router\Base;

/**
 * Расширение роутера поддержкой алиасов маршрутов.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 1.0
 */
trait RouterAliasesTrait
{
    /**
     * @var array маппинг алиасов
     */
    protected $aliases = [
        ':any' => '.*',
        ':int' => '[0-9]{1,}',
    ];

    /**
     * Добавление алиаса.
     * @param string алиас
     * @param string замена
     * @return self
     */
    public function alias(string $alias, string $value)
    {
        $this->aliases[$alias] = $value;
        return $this;
    }

    /**
     * Добавленеи алиасов.
     * @param array
     * @return self
     */
    public function aliases(array $aliases)
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
        foreach ($this->aliases as $alias => $value) {
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
        $path = str_replace('/', '\/', $path);
        $path = $this->applyAliases($path);
        $path = str_replace('?', '\?', $path);
        return "/^$path$/";
    }
}
