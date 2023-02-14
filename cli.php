<?php
use Beffi\advancephp\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use Beffi\advancephp\Blog\User;
use Beffi\advancephp\Blog\UUID;
use Beffi\advancephp\Person\Name;
use Beffi\advancephp\Person\Person;

include __DIR__ . "/vendor/autoload.php";

$connection = new PDO('sqlite:' . __DIR__ . '/blog.sqlite');
$usersRepository = new SqliteUsersRepository($connection);
//Добавляем в репозиторий несколько пользователей
$usersRepository->save(new User(UUID::random(), new Person(new Name('Ivan', 'Nikitin'), new DateTimeImmutable()), "admin"));
$usersRepository->save(new User(UUID::random(), new Person(new Name('Anna', 'Petrova'), new DateTimeImmutable()), "admin"));