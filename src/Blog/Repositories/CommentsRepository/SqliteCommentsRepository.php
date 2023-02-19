<?php
namespace Beffi\advancephp\Blog\Repositories\CommentsRepository;

use Beffi\advancephp\Blog\Comment;
use Beffi\advancephp\Blog\Exceptions\CommentNotFoundException;
use Beffi\advancephp\Blog\Repositories\CommentsRepository\CommentsRepositoryInterface;
use Beffi\advancephp\Blog\Repositories\PostsRepository\SqlitePostsRepository;
use Beffi\advancephp\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use Beffi\advancephp\Blog\UUID;
use PDO;

class SqliteCommentsRepository implements CommentsRepositoryInterface
{


    /**
     */
    public function __construct(private PDO $connection)
    {
    }
    /**
     * @param Comment $comment
     */
    public function save(Comment $comment): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO comments (uuid, author_uuid, post_uuid, text) VALUES (:uuid, :author_uuid, :post_uuid, :text)'
        );
        $statement->execute([
            ':uuid' => (string) $comment->uuid(),
            ':author_uuid' => (string) $comment->getUser()->uuid(),
            ':post_uuid' => (string) $comment->getPost()->uuid(),
            ':text' => $comment->getText(),
        ]);
    }

    /**
     *
     * @param UUID $uuid
     * @return Comment
     */
    public function get(UUID $uuid): Comment
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM comments WHERE uuid = ?'
        );
        $statement->execute([(string) $uuid]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        if (false === $result) {
            throw new CommentNotFoundException(
                "Cannot get comment: $uuid"
            );
        }
        $usersRepository = new SqliteUsersRepository($this->connection);
        $postsRepository = new SqlitePostsRepository($this->connection);
        return new Comment(
            new UUID($result['uuid']),
            $usersRepository->get(new UUID($result['author_uuid'])),
            $postsRepository->get(new UUID($result['post_uuid'])),
            $result['text']
        );
        ;
    }
}