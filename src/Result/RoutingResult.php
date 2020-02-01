<?php
/**
 * @package evas-php/evas-router
 */
namespace Evas\Router\Result;

use Evas\Router\Base\BaseRoutingResult;
use Evas\Router\Result\RoutingResultHandleTrait;

/**
 * Класс результата роутинга.
 * @author Egor Vasyakin <e.vasyakin@itevas.ru>
 * @since 1.0
 */
class RoutingResult extends BaseRoutingResult
{
    /**
     * Подключаем поддержку обработки маршрута.
     */
    use RoutingResultHandleTrait;
}
