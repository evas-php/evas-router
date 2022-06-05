<?php
/**
 * Класс контроллера.
 * @package evas-php\evas-router
 * @author Egor Vasyakin <egor@evas-php.com>
 */
namespace Evas\Router;

use Evas\Base\App;
use Evas\Http\Interfaces\RequestInterface;
use Evas\Router\Interfaces\ControllerInterface;

/**
 * Константы для свойств класса по умолчанию.
 */
if (!defined('EVAS_VIEWS_PATH')) define('EVAS_VIEWS_PATH', 'views/');

class Controller implements ControllerInterface
{
    /** @var string директория файлов отображения */
    public $viewsDir = EVAS_VIEWS_PATH;
    /** @var RequestInterface объект запроса */
    public $request;

    /**
     * Конструктор.
     * @param RequestInterface объект запроса
     * @param string|null директория файлов отображения
     */
    public function __construct(RequestInterface &$request, string $viewsDir = null)
    {
        $this->request = &$request;
        $this->viewsDir = App::resolveByApp($viewsDir ?? $this->viewsDir);
        if (method_exists($this, '_before')) {
            $this->_before();
        }
    }

    /**
     * Получение абсолютного пути для файла отображения.
     * @param string относительный путь отображения
     * @return string абсолютный путь отображения
     */
    public function resolveViewPath(string $filename): string
    {
        if (App::canInclude($filename)) {
            return $filename;
        }
        $filename = App::relativePathByApp($filename);
        $filename = App::resolveByApp($this->viewsDir . $filename);
        return $filename;
    }

    /**
     * Открытие файла.
     * @param string имя файла относительное директории отображений
     * @param array|null аргументы файла
     * @param object|null контекст файла
     */
    public function view(string $filename, array $args = null, object &$context = null)
    {
        if (!$context) $context = &$this;
        App::include($this->resolveViewPath($filename), $args, $context);
    }

    /**
     * Проверка возможности открытия файла.
     * @param string имя файла относительное директории отображений
     * @return bool
     */
    public function canView(string $filename): bool
    {
        return App::canInclude($this->resolveViewPath($filename));
    }

    /**
     * Выбрасывание исключения в случае невозможности открытия файла отображения.
     * @param string имя файла относительное директории отображений
     */
    public function throwIfNotCanView(string $filename)
    {
        return App::throwIfNotCanInclude($this->resolveViewPath($filename));
    }
}
