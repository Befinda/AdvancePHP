<?php

namespace Beffi\advancephp\Blog\Repositories\CommentsRepository;


use Beffi\advancephp\Blog\Comment;
use Beffi\advancephp\Blog\UUID;

interface CommentsRepositoryInterface
{

    public function save(Comment $comment): void;
    public function get(UUID $uuid): Comment;
}