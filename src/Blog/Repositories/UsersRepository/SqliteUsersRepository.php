<?php
namespace Beffi\advancephp\Blog\Repositories\UsersRepository;

use Beffi\advancephp\Blog\Exceptions\UserNotFoundException;
use Beffi\advancephp\Blog\User;
use Beffi\advancephp\Blog\UUID;
use Beffi\advancephp\Person\Name;
use Beffi\advancephp\Person\Person;
use \PDO;
use \PDOStatement;

class SqliteUsersRepository implements UsersRepositoryInterface
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
            'INSERT INTO users (uuid, first_name, last_name) VALUES (:uuid, :first_name, :last_name)'
        );
        // Выполняем запрос с конкретными значениями
        $statement->execute([
            ':uuid' => (string) $user->uuid(),
            ':first_name' => $user->name()->getName()->first(),
            ':last_name' => $user->name()->getName()->last(),
        ]);

    }
    public function get(UUID $uuid): User
    {

        $statement = $this->connection->prepare(
            'SELECT * FROM users WHERE uuid = ?'
        );
        $statement->execute([(string) $uuid]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        // Бросаем исключение, если пользователь не найден
        if (false === $result) {
            throw new UserNotFoundException(
                "Cannot get user: $uuid"
            );
        }
        return new User(
            new UUID($result['uuid']),
            new Person(new Name($result['first_name'], $result['last_name']), new \DateTimeImmutable()),
            "admin"
        );
    }
    /**
     * @param string $logim
     * @return User
     */
    public function getByLogin(string $login): User
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM users WHERE login = :login'
        );
        $statement->execute([
            ':login' => $login,
        ]);
        return $this->getUser($statement, $login);

    }
    private function getUser(PDOStatsement $statement, string $login): User
    {
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        if (false === $result) {
            throw new UserNotFoundException(
                "Cannot find user: $login"
            );
        }
        // Создаём объект пользователя с полем username
        return new User(
            new UUID($result['uuid']),
            new Person(new Name($result['first_name'], $result['last_name']), new \DateTimeImmutable()),
            $result['username'],
        );
    }
}