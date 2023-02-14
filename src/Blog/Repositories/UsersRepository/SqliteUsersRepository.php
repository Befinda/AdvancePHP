<?php
namespace Beffi\advancephp\Blog\Repositories\UsersRepository;

use Beffi\advancephp\Blog\User;
use \PDO;

class SqliteUsersRepository
{
    public function __construct(
        private PDO $connection
    )
    {
    }
    public function save(User $user): void
    {
        // Подготавливаем запрос
        $statement = $this->connection->prepare(
            'INSERT INTO users (first_name, last_name) VALUES (:first_name, :last_name)'
        );
        // Выполняем запрос с конкретными значениями
        $statement->execute([
            ':first_name' => $user->name()->getName()->first(),
            ':last_name' => $user->name()->getName()->last(),
        ]);

    }
    public function get(int $id)
    {

    }
}