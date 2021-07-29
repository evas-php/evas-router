<?php
/**
 * Тестовая модель пользователя.
 */
namespace Evas\Router\tests\help\models;

class User
{
    const ROLE_DEFAULT = 0;
    const ROLE_ADMIN = 1;

    public $name;
    public $role = self::ROLE_DEFAULT;

    public function __construct(array $props = null)
    {
        if (!empty($props)) foreach ($props as $name => $value) {
            $this->$name = $value;
        }
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public static function createExampleUser()
    {
        return new static([
            'name' => 'Egor',
        ]);
    }

    public static function createExampleAdmin()
    {
        return new static([
            'name' => 'Egor',
            'role' => self::ROLE_ADMIN
        ]);
    }
}
