<?php
/**
 * @package evas-php\evas-router
 */
namespace Evas\Router;

use Evas\Base\App;

/**
 * Константы для свойств класса по умолчанию.
 */
if (!defined('EVAS_APP_CLASS')) define('EVAS_APP_CLASS', App::class);
if (!defined('EVAS_VIEW_PATH')) define('EVAS_VIEW_PATH', 'view/');

/**
 * Controller.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 1.0
 */
class Controller
{
    /**
     * @var string класс приложения
     */
    public $appClass = EVAS_APP_CLASS;

    /**
     * @var string директория файлов отображения
     */
    public $viewPath = EVAS_VIEW_PATH;

    /**
     * Получение полного имени файла.
     * @param string относительный путь к файлу
     * @return string полный путь к файлу
     */
    public function filename(string $filename): string
    {
        return $this->appClass::getDir() . $this->viewPath . $filename;
    }

    /**
     * Рендер файла/шаблона.
     * @param string имя файла/шаблона
     * @param array аргументы для файла
     * @throws Evas\Base\Exception\FileNotFoundException
     * @return mixed|null возвращаемый результат файла
     */
    public function render(string $filename, array $args = null)
    {
        return $this->appClass::load($this->filename($filename), $args, $this);
    }

    /**
     * Проверка на возможность рендера.
     * @param string имя файла/шаблона
     * @return bool удалось ли прочитать файл
     */
    public function canRender(string $filename): bool
    {
        return $this->appClass::canLoad($this->filename($filename));
    }
}
