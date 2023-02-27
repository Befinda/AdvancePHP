<?php

use Beffi\advancephp\Blog\Exceptions\AppException;
use Beffi\advancephp\Blog\Exceptions\HttpException;
use Beffi\advancephp\Blog\Http\Actions\Users\CreateUser;
use Beffi\advancephp\Blog\Http\Actions\Users\FindByUsername;
use Beffi\advancephp\Blog\Http\ErrorResponse;
use Beffi\advancephp\Blog\Http\Request;
use Beffi\advancephp\Blog\Http\SuccessfulResponse;
use Beffi\advancephp\Blog\Repositories\UsersRepository\SqliteUsersRepository;


require_once __DIR__ . '/vendor/autoload.php';
$request = new Request($_GET, $_SERVER, file_get_contents('php://input'));
$routes = [
// Добавили ещё один уровень вложенности
// для отделения маршрутов,
// применяемых к запросам с разными методами
'GET' => [
    '/users/show' => new FindByUsername(
    new SqliteUsersRepository(
    //new PDO( __DIR__ . '/blog.sqlite'))),
    new PDO('sqlite:' . __DIR__ . '/blog.sqlite'))),
    // '/posts/show' => new FindByUuid(
    // new SqlitePostsRepository(
    // new PDO('sqlite:' . __DIR__ . '/blog.sqlite'))),
    ],
'POST' => [
    '/users/create'=>new CreateUser(
        new SqliteUsersRepository(
            new PDO('sqlite:'. __DIR__ . '/blog.sqlite')
        )
    ),
],
    ];
try {
    // Пытаемся получить путь из запроса
    $path = $request->path();
    } catch (HttpException) {
    // Отправляем неудачный ответ,
    // если по какой-то причине
    // не можем получить путь
    (new ErrorResponse)->send();
    // Выходим из программы
    return;
    }
try {
    // Пытаемся получить HTTP-метод запроса
    $method = $request->method();
    } catch (HttpException) {
    // Возвращаем неудачный ответ,
    // если по какой-то причине
    // не можем получить метод
    (new ErrorResponse)->send();
    return;
 }

// Если у нас нет маршрутов для метода запроса -
// возвращаем неуспешный ответ
if (!array_key_exists($method, $routes)) {
    (new ErrorResponse('Not found'))->send();
    return;
    }
    // Ищем маршрут среди маршрутов для этого метода
    if (!array_key_exists($path, $routes[$method])) {
    (new ErrorResponse('Not found'))->send();
    return;
    }
    // Выбираем действие по методу и пути
    $action = $routes[$method][$path];
    try {
    $response = $action->handle($request);
    } catch (Exception $e) {
    (new ErrorResponse($e->getMessage()))->send();
    }
    $response->send();