<?php
use Beffi\advancephp\Blog\Commands\CreateUserCommand;
use Beffi\advancephp\Blog\Exceptions\CommandException;
use Beffi\advancephp\Blog\Repositories\UsersRepository\SqliteUsersRepository;


include __DIR__ . "/vendor/autoload.php";

$connection = new PDO('sqlite:' . __DIR__ . '/blog.sqlite');
$usersRepository = new SqliteUsersRepository($connection);


$command = new CreateUserCommand($usersRepository);
try {
    // Запускаем команду
    $command->handle($argv);
} catch (CommandException $e) {
    echo "{$e->getMessage()}\n";
}