<?php
/**
 * Тестовый контроллер профиля пользователя.
 */
namespace Evas\Router\tests\help\cabinet;

class ProfileController
{
    public function indexAction()
    {
        return 'show profile';
    }

    public function editAction()
    {
        return 'edit profile';
    }

    public function saveAction()
    {
        return 'save profile';
    }
}
