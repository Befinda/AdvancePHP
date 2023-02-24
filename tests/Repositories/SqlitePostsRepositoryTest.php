<?php
namespace Beffi\advancephp\Blog\UnitTests\Repositories\PostsRepository;

use Beffi\advancephp\Blog\Exceptions\PostNotFoundException;
use Beffi\advancephp\Blog\Repositories\PostsRepository\SqlitePostsRepository;
use Beffi\advancephp\Blog\User;
use Beffi\advancephp\Blog\Post;
use Beffi\advancephp\Blog\UUID;
use Beffi\advancephp\Person\Name;
use Beffi\advancephp\Person\Person;
use \PDOStatement;
use PHPUnit\Framework\TestCase;
use \PDO;

class SqlitePostsRepositoryTest extends TestCase
{
    public function testItThrowsAnExceptionWhenPostNotFound(): void
    {
        $connectionStub = $this->createStub(PDO::class);
        $statementStub = $this->createStub(PDOStatement::class);
        $statementStub->method('fetch')->willReturn(false);
        $statementStub->method('execute')->with(['123e4567-e89b-12d3-a456-426614174000']);
        $connectionStub->method('prepare')->willReturn($statementStub);
        $repository = new SqlitePostsRepository($connectionStub);
        $this->expectException(PostNotFoundException::class);
        $this->expectExceptionMessage('Cannot get post: 123e4567-e89b-12d3-a456-426614174000');
        $repository->get(new UUID("123e4567-e89b-12d3-a456-426614174000"));
    }
    public function testItSavesPostToDatabase(): void
    {
        $connectionStub = $this->createStub(PDO::class);
        $statementMock = $this->createMock(PDOStatement::class);
        $statementMock
            ->expects($this->once())
            ->method('execute')
            ->with([
                ':uuid' => "123e4567-e89b-12d3-a456-426614174001",
                ':author_uuid' => "123e4567-e89b-12d3-a456-426614174000",
                ':title' => 'head',
                ':text' => 'textpost',
            ]);
        $connectionStub->method('prepare')->willReturn($statementMock);
        $repository = new SqlitePostsRepository($connectionStub);
        $user = new User(
            new UUID('123e4567-e89b-12d3-a456-426614174000'),
            new Person(
                new Name('first_name', 'last_name'),
                new \DateTimeImmutable
            ),
            'name'
        );
        $repository->save(
            new Post(
                new UUID("123e4567-e89b-12d3-a456-426614174001"),
                $user,
                'head',
                'textpost'
            )
        );

    }
    public function testItGetPostByUuid(): void
    {
        $connectionStub = $this->createStub(PDO::class);
        $statementMock = $this->createMock(PDOStatement::class);
        $statementMock
            ->method('fetch')
            ->willReturn([
                'uuid' => '123e4567-e89b-12d3-a456-426614174001',
                'author_uuid' => '123e4567-e89b-12d3-a456-426614174000',
                'title' => 'head',
                'text' => 'textpost',
                'username' => 'ivan123',
                'first_name' => 'Ivan',
                'last_name' => 'Nikitin',

            ]);
        $connectionStub->method('prepare')->willReturn($statementMock);
        $postRepository = new SqlitePostsRepository($connectionStub);
        $post = $postRepository->get(new UUID("123e4567-e89b-12d3-a456-426614174001"));


        $this->assertSame('123e4567-e89b-12d3-a456-426614174001', (string) $post->uuid());
    }
}