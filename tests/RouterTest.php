<?php
/**
 * Тест роутера.
 * @package evas-php\evas-router
 * @author Egor Vasyakin <egor@evas-php.com>
 */
namespace Evas\Router\tests;

use Codeception\Util\Autoload;
use Evas\Http\HttpRequest;
use Evas\Router\Exceptions\RouterException;
use Evas\Router\Router;
use Evas\Router\RouterResult;
use Evas\Router\tests\help\RoutingHelperTrait;

use Evas\Router\tests\help\admin\ListController;
use Evas\Router\tests\help\cabinet\ProfileController;
use Evas\Router\tests\help\CustomController;
use Evas\Router\tests\help\middlewares\Access;
use Evas\Router\tests\help\models\User;

Autoload::addNamespace('Evas\\Router', 'vendor/evas-php/evas-router/src');
Autoload::addNamespace('Evas\\Router\\tests', 'vendor/evas-php/evas-router/tests');

class RouterTest extends \Codeception\Test\Unit
{

    /**
     * Устверждение возвращения обработки результата роутинга.
     * @param mixed ожидание
     * @param string uri
     * @param string|null метод
     */
    private function assertReturned($expected, string $uri, string $method = null)
    {
        $actual = $this->router->routing($uri, $method)->returned;
        $this->assertEquals($expected, $actual);
    }

    /**
     * Получение вывода обработчика результата роутинга.
     * @param string uri
     * @param string|null метод
     */
    private function getRoutingOutput(string $uri, string $method = null)
    {
        ob_start();
        $this->router->routing($uri, $method);
        return ob_get_clean();
    }

    /**
     * Утверждение страницы.
     * @param string страница
     * @param string uri
     * @param string|null метод
     */
    private function assertPage(string $page, string $uri, string $method = null)
    {
        $expected = file_get_contents(__DIR__ . "/help/pages/$page.php");
        $actual = $this->getRoutingOutput($uri, $method);
        $this->assertEquals($expected, $actual);
    }

    /**
     * Устанавливаем роутер в хуке _before.
     */
    protected function _before()
    {
        $this->router = (new Router)
        // ->default(__DIR__ . '/help/pages/404.php')
        ->viewDir(__DIR__ . '/help/pages/')
        ->default('404.php')
        ->autoByMethod('/profile/', function () {
            $this->middleware([Access::class => 'isLogin'])
            ->classCustom(ProfileController::class)
            ->methodPostfix('Action');
        })
        ->alias(':id', '\d+')
        ->map('/users/', function () {
            $this
            ->get('(:id)', function (int $id) {
                return "user $id";
            });
        })
        ->map('/admin/', function () {
            $this->middleware([Access::class => 'isAdmin'])
            ->get('', function () { return 'admin panel'; })
            ->autoByMethod('list/', function () {
                $this->classCustom(ListController::class)
                ->methodPostfix('List');
            });
        })
        ->autoByClass('/auth/', 'POST', function () {
            $this->classPrefix('Evas\\Router\\tests\\help\\auth\\')
            ->classPostfix('Action');
        })
        ->map('/custom/', function () {
            $this->controllerClass(CustomController::class)
            ->get('', function () {return get_called_class();});
        })
        ->autoByFile('/', function () {
            $this
            // $this->filePrefix(__DIR__ . '/help/pages/')
            ->filePostfix('.php');
        });
    }

    /**
     * Тест обработчика по умолчанию
     */
    public function testDefault()
    {
        $this->assertPage('404', '/random_page');
    }

    /**
     * Тест алиасов.
     */
    public function testAliases()
    {
        // тест алиасов и подстановки аргументов из uri
        $this->assertReturned('user 0', '/users/0');
        $this->assertReturned('user 1', '/users/1');
        $this->assertReturned('user 1000', '/users/1000');
    }

    /**
     * Тест кастомного контроллера файлов и функций
     */
    public function testCustomFilesAndFunctionsController()
    {
        $this->assertReturned(CustomController::class, '/custom/');
    }

    /**
     * Тест автороутинга по файлу.
     */
    public function testAutoByFile()
    {
        // тест страниц
        $this->assertPage('index', '/');
        $this->assertPage('about', '/about');
    }

    /**
     * Тест автороутинга по классу с ограницением по REST-методу.
     */
    public function testAutoByClassWithPostMethodOnly()
    {
        // тест доступа к действиям входа и регистрации через метод POST
        $this->assertPage('404', '/auth/registration');
        $this->assertPage('404', '/auth/login');
        $this->assertReturned('user registration', '/auth/registration', 'POST');
        $this->assertReturned('user login', '/auth/login', 'POST');
    }

    /**
     * Тест автороутинга по методу.
     */
    public function testAutoByMethod()
    {
        global $loggedUser;
        $loggedUser = User::createExampleUser();
        $this->assertReturned('show profile', '/profile/');
        $this->assertReturned('edit profile', '/profile/edit');
        $this->assertReturned('save profile', '/profile/save');
        $loggedUser = null;
    }

    /**
     * Тест middlewares.
     */
    public function testMiddlewares()
    {   
        // тест отсутствия доступа к профилю без авторизации
        $this->assertReturned(false, '/profile/');
        // тест отсутствия доступа к админке без авторизации
        $this->assertReturned(false, '/admin/');

        // тест доступа пользователя
        global $loggedUser;
        $loggedUser = User::createExampleUser();
        $this->assertReturned('show profile', '/profile/');
        $this->assertReturned('edit profile', '/profile/edit');
        $this->assertReturned('save profile', '/profile/save');

        // тест отсутствия доступа пользователя к админке
        $this->assertReturned(false, '/admin/');
        $this->assertReturned(false, '/admin/list/users');
        $this->assertReturned(false, '/admin/list/admins');

        // тест доступа админа к админке
        $loggedUser = User::createExampleAdmin();
        $this->assertReturned('admin panel', '/admin/');
        $this->assertReturned('users list', '/admin/list/users');
        $this->assertReturned('admins list', '/admin/list/admins');
    }
}
