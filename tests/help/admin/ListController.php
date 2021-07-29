<?php
/**
 * Тестовый контроллер списков для админа.
 */
namespace Evas\Router\tests\help\admin;

class ListController
{
    public function usersList()
    {
        return 'users list';
    }

    public function adminsList()
    {
        return 'admins list';
    }
}
