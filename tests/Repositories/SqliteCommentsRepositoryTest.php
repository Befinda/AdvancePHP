<?php
namespace Beffi\advancephp\Blog\UnitTests\Repositories\CommentsRepository;

use Beffi\advancephp\Blog\Exceptions\CommentNotFoundException;
use Beffi\advancephp\Blog\Repositories\CommentsRepository\SqliteCommentsRepository;
use Beffi\advancephp\Blog\User;
use Beffi\advancephp\Blog\Post;
use Beffi\advancephp\Blog\Comment;
use Beffi\advancephp\Blog\UUID;
use Beffi\advancephp\Person\Name;
use Beffi\advancephp\Person\Person;
use \PDOStatement;

use PHPUnit\Framework\TestCase;
use \PDO;

class SqliteCommentsRepositoryTest extends TestCase
{
    public function testItThrowsAnExceptionWhenCommentNotFound(): void
    {
        $connectionStub = $this->createStub(PDO::class);
        $statementStub = $this->createStub(PDOStatement::class);
        $statementStub->method('fetch')->willReturn(false);
        $statementStub->method('execute')->with(['123e4567-e89b-12d3-a456-426614174000']);
        $connectionStub->method('prepare')->willReturn($statementStub);
        $repository = new SqliteCommentsRepository($connectionStub);
        $this->expectException(CommentNotFoundException::class);
        $this->expectExceptionMessage('Cannot get comment: 123e4567-e89b-12d3-a456-426614174000');
        $repository->get(new UUID("123e4567-e89b-12d3-a456-426614174000"));
    }
    public function testItSavesCommentToDatabase(): void
    {
        $connectionStub = $this->createStub(PDO::class);
        $statementMock = $this->createMock(PDOStatement::class);
        $statementMock
            ->expects($this->once())
            ->method('execute')
            ->with([
                ':uuid' => "123e4567-e89b-12d3-a456-426614174002",
                ':author_uuid' => "123e4567-e89b-12d3-a456-426614174000",
                ':post_uuid' => '123e4567-e89b-12d3-a456-426614174001',
                ':text' => 'textcomment',
            ]);
        $connectionStub->method('prepare')->willReturn($statementMock);
        $repository = new SqliteCommentsRepository($connectionStub);
        $user = new User(
            new UUID('123e4567-e89b-12d3-a456-426614174000'),
            new Person(
                new Name('first_name', 'last_name'),
                new \DateTimeImmutable
            ),
            'name'
        );
        $post = new Post(
            new UUID("123e4567-e89b-12d3-a456-426614174001"),
            $user,
            'head',
            'textpost'
        );
        $repository->save(
            new Comment(
                new UUID("123e4567-e89b-12d3-a456-426614174002"),
                $user,
                $post,
                'textcomment'
            )
        );

    }
    public function testItGetCommentByUuid(): void
    {
        $connectionStub = $this->createStub(PDO::class);
        $statementMock = $this->createMock(PDOStatement::class);
        $statementMock
            ->method('fetch')
            ->willReturn([
                'uuid' => "123e4567-e89b-12d3-a456-426614174002",
                'author_uuid' => "123e4567-e89b-12d3-a456-426614174000",
                'post_uuid' => '123e4567-e89b-12d3-a456-426614174001',
                'text' => 'textcomment',
                'title' => 'head',
                'text' => 'textpost',
                'username' => 'ivan123',
                'first_name' => 'Ivan',
                'last_name' => 'Nikitin',

            ]);
        $connectionStub->method('prepare')->willReturn($statementMock);
        $commentRepository = new SqliteCommentsRepository($connectionStub);
        $comment = $commentRepository->get(new UUID("123e4567-e89b-12d3-a456-426614174002"));


        $this->assertSame('123e4567-e89b-12d3-a456-426614174002', (string) $comment->uuid());
    }
}