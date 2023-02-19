<?php
namespace Beffi\advancephp\Blog;

class Comment
{
    private UUID $uuid;
    private User $user;
    private Post $post;
    private string $comText;


    /**
     * @param UUID $uuid
     * @param User $user
     * @param Post $post
     * @param string $comText
     */
    public function __construct(UUID $uuid, User $user, Post $post, string $comText)
    {
        $this->uuid = $uuid;
        $this->user = $user;
        $this->post = $post;
        $this->comText = $comText;
    }

    public function __toString()
    {
        return "Пользователь $this->user под статьей \"{$this->post->getTitle()}\" оставил комментарий: $this->comText" . PHP_EOL;
    }

    /**
     * @return UUID
     */
    public function uuid(): UUID
    {
        return $this->uuid;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->comText;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return Post
     */
    public function getPost(): Post
    {
        return $this->post;
    }
}