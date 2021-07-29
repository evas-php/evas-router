<?php
/**
 * Тестовый middleware.
 */
namespace Evas\Router\tests\help\middlewares;

use Evas\Router\tests\help\models\User;

class Access
{
    public function isLogin(): bool
    {
        global $loggedUser;
        return $loggedUser instanceof User;
    }

    public function isAdmin(): bool
    {
        global $loggedUser;
        return $this->isLogin() && $loggedUser->isAdmin();
    }
}
