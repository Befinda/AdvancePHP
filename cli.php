<?php
use Beffi\advancephp\Blog\Commands\Arguments;
use Beffi\advancephp\Blog\Commands\CreateUserCommand;
use Beffi\advancephp\Blog\Comment;
use Beffi\advancephp\Blog\Exceptions\CommandException;
use Beffi\advancephp\Blog\Exceptions\PostNotFoundException;
use Beffi\advancephp\Blog\Exceptions\CommentNotFoundException;
use Beffi\advancephp\Blog\Post;
use Beffi\advancephp\Blog\Repositories\CommentsRepository\SqliteCommentsRepository;
use Beffi\advancephp\Blog\Repositories\PostsRepository\SqlitePostsRepository;
use Beffi\advancephp\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use Beffi\advancephp\Blog\UUID;


include __DIR__ . "/vendor/autoload.php";
//$str = 'sqlite:' . __DIR__ . '/blog.sqlite';
$connection = new PDO('sqlite:' . __DIR__ . '/blog.sqlite');
$usersRepository = new SqliteUsersRepository($connection);
$postsRepository = new SqlitePostsRepository($connection);
$commentsRepository = new SqliteCommentsRepository($connection);

// $commentsRepository->save(
//     new Comment(
//         UUID::random(),
//         $usersRepository->getByUsername("ivan"),
//         $postsRepository->get(new UUID("c4943dfd-ff78-4ef5-b36c-3d203245e67b")),
//         "My first comment. My name is Ivan."
//     )
// );
try {
    echo $commentsRepository->get(new UUID("801d34eb-13b2-4a2c-b8f5-70be2eff7cc5"));
} catch (CommentNotFoundException) {
    echo "Comment not found";
} catch (Exception $e) {
    echo "{$e->getMessage()}\n";
}

// $command = new CreateUserCommand($usersRepository);
// try {
//     // Запускаем команду
//     $command->handle(Arguments::fromArgv($argv));
// } catch (CommandException $e) {
//     echo "{$e->getMessage()}\n";
// }