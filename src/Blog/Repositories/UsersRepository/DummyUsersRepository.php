<?php

namespace Beffi\advancephp\Blog\Repositories\UsersRepository;

use Beffi\advancephp\Blog\Exceptions\UserNotFoundException;
use Beffi\advancephp\Blog\User;
use Beffi\advancephp\Blog\UUID;
use Beffi\advancephp\Person\Name;
use Beffi\advancephp\Person\Person;


class DummyUsersRepository implements UsersRepositoryInterface
{

    /**
     * @param \Beffi\advancephp\Blog\User $user
     */
    public function save(User $user): void
    {
    }

    /**
     *
     * @param UUID $uuid
     * @return User
     */
    public function get(UUID $uuid): User
    {
        throw new UserNotFoundException("Not found");

    }

    /**
     *
     * @param string $username
     * @return User
     */
    public function getByUserName(string $username): User
    {
        return new User(
            UUID::random(),
            new Person(
                new Name("first", "last"),
                new \DateTimeImmutable()
            ),
            "user123",
        );
    }
}