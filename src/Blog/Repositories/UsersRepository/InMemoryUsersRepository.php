<?php

namespace Beffi\advancephp\Blog\Repositories;

use Beffi\advancephp\Blog\Exceptions\UserNotFoundException;
use Beffi\advancephp\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use Beffi\advancephp\Blog\User;

class InMemoryUsersRepository implements UsersRepositoryInterface
{
    private array $users = [];
    public function save(User $user): void
    {
        $this->users[] = $user;
    }
    public function get(int $id): User
    {
        foreach ($this->users as $user) {
            if ($user->id() === $id) {
                return $user;
            }
        }
        throw new UserNotFoundException("User not found: $id");
    }
}