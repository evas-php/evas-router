<?php
/**
 * Класс контроллера.
 * @package evas-php\evas-router
 * @author Egor Vasyakin <egor@evas-php.com>
 */
namespace Evas\Router;

use Evas\Base\App;
use Evas\Base\Traits\IncludeTrait;
use Evas\Http\Interfaces\RequestInterface;

/**
 * Константы для свойств класса по умолчанию.
 */
if (!defined('EVAS_VIEW_PATH')) define('EVAS_VIEW_PATH', 'view/');

class Controller
{
    /**
     * Подключаем поддержку подключения файла.
     */
    use IncludeTrait;

    /** @var string путь файлов отображения относительно приложения */
    public $viewPath = EVAS_VIEW_PATH;
    /** @var RequestInterface объект запроса */
    public $request;

    /**
     * Конструктор.
     * @param RequestInterface объект запроса
     */
    public function __construct(RequestInterface &$request)
    {
        $this->request = &$request;
        if (method_exists($this, '_before')) {
            $this->_before();
        }
    }

    /**
     * Получение абсолютного пути для файла отображения.
     * @param string относительный путь отображения
     * @return string абсолютный путь отображения
     */
    public function absoluteViewPath(string $filename): string
    {
        $filename = $this->viewPath . $filename;
        return App::absolutePathByApp($filename);
    }

    /**
     * Открытие файла.
     * @param string имя файла относительное директории отображений
     * @param array|null аргументы файла
     * @param object|null контекст файла
     */
    public function view(string $filename, array $args = null, object &$context = null)
    {
        return $this->include($this->absoluteViewPath($filename), $args, $context);
    }

    /**
     * Проверка возможности открытия файла.
     * @param string имя файла относительное директории отображений
     * @return bool
     */
    public function canView(string $filename): bool
    {
        return $this->canInclude($this->absoluteViewPath($filename));
    }

    /**
     * Выбрасывание исключения в случае невозможности открытия файла отображения.
     * @param string имя файла относительное директории отображений
     */
    public function throwIfNotCanView(string $filename)
    {
        return $this->throwIfNotCanInclude($this->absoluteViewPath($filename));
    }
}
