<?php
namespace Beffi\advancephp\Blog\Commands;

use Beffi\advancephp\Blog\Exceptions\CommandException;
use Beffi\advancephp\Blog\Exceptions\UserNotFoundException;
use Beffi\advancephp\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use Beffi\advancephp\Blog\User;
use Beffi\advancephp\Person\Person;
use Beffi\advancephp\Person\Name;
use Beffi\advancephp\Blog\UUID;

class CreateUserCommand
{
    // Команда зависит от контракта репозитория пользователей,
// а не от конкретной реализации
    public function __construct(
        private UsersRepositoryInterface $usersRepository
    )
    {
    }
    public function handle(Arguments $arguments): void
    {
        $username = $arguments->get("username");
        // Проверяем, существует ли пользователь в репозитории
        if ($this->userExists($username)) {
            // Бросаем исключение, если пользователь уже существует
            throw new CommandException("User already exists: $username");
        }
        // Сохраняем пользователя в репозиторий
        $this->usersRepository->save(
            new User(
                UUID::random(),
                new Person(
                    new Name(
                        $arguments->get('first_name'),
                        $arguments->get('last_name')
                    ),
                    new \DateTimeImmutable()
                ),
                $username,
            )
        );
    }

    private function userExists(string $username): bool
    {
        try {
            // Пытаемся получить пользователя из репозитория
            $this->usersRepository->getByUsername($username);
        } catch (UserNotFoundException) {
            return false;
        }
        return true;
    }
}