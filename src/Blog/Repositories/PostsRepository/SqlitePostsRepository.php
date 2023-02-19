<?php
namespace Beffi\advancephp\Blog\Repositories\PostsRepository;

use Beffi\advancephp\Blog\Exceptions\PostNotFoundException;
use Beffi\advancephp\Blog\Post;
use Beffi\advancephp\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use Beffi\advancephp\Blog\UUID;
use \PDO;
use \PDOStatement;

class SqlitePostsRepository implements PostsRepositoryInterface
{
    public function __construct(private PDO $connection)
    {

    }
    /**
     * @param \Beffi\advancephp\Blog\Post $post
     */
    public function save(Post $post): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO posts (uuid, author_uuid, title, text) VALUES (:uuid, :author_uuid, :title, :text)'
        );
        $statement->execute([
            ':uuid' => (string) $post->uuid(),
            ':author_uuid' => (string) $post->author()->uuid(),
            ':title' => $post->getTitle(),
            ':text' => $post->getText(),
        ]);
    }

    /**
     *
     * @param \Beffi\advancephp\Blog\UUID $uuid
     * @return \Beffi\advancephp\Blog\Post
     */
    public function get(UUID $uuid): Post
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM posts WHERE uuid = ?'
        );
        $statement->execute([(string) $uuid]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        if (false === $result) {
            throw new PostNotFoundException(
                "Cannot get post: $uuid"
            );
        }
        $usersRepository = new SqliteUsersRepository($this->connection);
        return new Post(
            new UUID($result['uuid']),
            $usersRepository->get(new UUID($result['author_uuid'])),
            $result['title'],
            $result['text']
        );
        ;
    }


}