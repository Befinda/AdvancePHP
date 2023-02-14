<?php
namespace Beffi\advancephp\Blog\Commands;

use Beffi\advancephp\Blog\Exceptions\CommandException;
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
    public function handle(array $rawInput): void
    {
        $input = $this->parseRawInput($rawInput);
        $username = $input['username'];
        // Проверяем, существует ли пользователь в репозитории
        if ($this->userExists($username)) {
            // Бросаем исключение, если пользователь уже существует
            throw new CommandException("User already exists: $username");
        }
        // Сохраняем пользователя в репозиторий
        $this->usersRepository->save(
            new User(
                Uuid::random(),
                new Person(new Name($input['first_name'], $input['last_name']), new \DateTimeImmutable()),
                $username,
            )
        );
    }
    private function parseRawInput(array $rawInput): array
    {
        $input = [];
        foreach ($rawInput as $argument) {
            $parts = explode('=', $argument);
            if (count($parts) !== 2) {
                continue;
            }
            $input[$parts[0]] = $parts[1];
        }
        foreach (['username', 'first_name', 'last_name'] as $argument) {
            if (!array_key_exists($argument, $input)) {
                throw new CommandException(
                    "No required argument provided: $argument"
                );
            }
            if (empty($input[$argument])) {
                throw new CommandException(
                    "Empty argument provided: $argument"
                );
            }
        }
        return $input;
    }
    private function userExists(string $login): bool
    {
        try {
            // Пытаемся получить пользователя из репозитория
            $this->usersRepository->getByLogin($login);
        } catch (UserNotFoundException) {
            return false;
        }
        return true;
    }
}