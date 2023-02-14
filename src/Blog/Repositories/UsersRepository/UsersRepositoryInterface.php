<?php
namespace Beffi\advancephp\Blog\Repositories\UsersRepository;

use Beffi\advancephp\Blog\User;
use Beffi\advancephp\Blog\UUID;

interface UsersRepositoryInterface
{
    public function save(User $user): void;
    public function get(UUID $uuid): User;
}