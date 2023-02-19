<?php

namespace Beffi\advancephp\Blog\Repositories\PostsRepository;

use Beffi\advancephp\Blog\Post;
use Beffi\advancephp\Blog\UUID;

interface PostsRepositoryInterface
{

    public function save(Post $post): void;
    public function get(UUID $uuid): Post;
}