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

    /** @var string директория файлов отображения */
    public $viewDir = EVAS_VIEW_PATH;
    /** @var RequestInterface объект запроса */
    public $request;

    /**
     * Конструктор.
     * @param RequestInterface объект запроса
     * @param string|null директория файлов отображения
     */
    public function __construct(RequestInterface &$request, string $viewDir = null)
    {
        $this->request = &$request;
        $this->viewDir = App::resolveByApp($viewDir ?? $this->viewDir);
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
        if ($this->canInclude($filename)) {
            return $filename;
        }
        $filename = App::relativePathByApp($filename);
        $filename = App::resolveByApp($this->viewDir . $filename);
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
        $this->include($this->resolveViewPath($filename), $args, $context);
    }

    /**
     * Проверка возможности открытия файла.
     * @param string имя файла относительное директории отображений
     * @return bool
     */
    public function canView(string $filename): bool
    {
        return $this->canInclude($this->resolveViewPath($filename));
    }

    /**
     * Выбрасывание исключения в случае невозможности открытия файла отображения.
     * @param string имя файла относительное директории отображений
     */
    public function throwIfNotCanView(string $filename)
    {
        return $this->throwIfNotCanInclude($this->resolveViewPath($filename));
    }
}
