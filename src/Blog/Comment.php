<?php
namespace Beffi\advancephp\Blog;

class Comment
{
    private int $id;
    private User $user;
    private Post $post;
    private string $comText;


    /**
     * @param int $id
     * @param User $user
     * @param Post $post
     * @param string $comText
     */
    public function __construct(int $id, User $user, Post $post, string $comText)
    {
        $this->id = $id;
        $this->user = $user;
        $this->post = $post;
        $this->comText = $comText;
    }

    public function __toString()
    {
        return "Пользователь $this->user под статьей \"{$this->post->getTitle()}\" оставил комментарий: $this->comText" . PHP_EOL;
    }
}