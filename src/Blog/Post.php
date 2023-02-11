<?php
namespace Beffi\advancephp\Blog;

class Post
{
    public int $id;
    private User $author;
    private string $text;
    private string $title;
    public function __construct(
        int $id,
        User $author,
        string $title,
        string $text
    )
    {
        $this->id = $id;
        $this->author = $author;
        $this->title = $title;
        $this->text = $text;
    }
    public function __toString()
    {
        return $this->author . ' написал статью с заголовком: ' . $this->title . PHP_EOL;
    }

    public function getPost(int $id): string
    {
        return $this->title . PHP_EOL . $this->text . PHP_EOL;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }
}