<?php
use Beffi\advancephp\Blog\Comment;

//use Beffi\advancephp\Blog\Exceptions\UserNotFoundException;

set_include_path(__DIR__);


use Beffi\advancephp\Blog\Post;
use Beffi\advancephp\Blog\User;
use Beffi\advancephp\Person\Name;
use Beffi\advancephp\Person\Person;

//use Beffi\advancephp\Blog\Repositories\InMemoryUsersRepository;

require_once __DIR__ . "/vendor/autoload.php";
// spl_autoload_register(function ($class) {
//     $file = $class . ".php";
//     $file = str_replace('\\', DIRECTORY_SEPARATOR, $file);
//     $file = str_replace('GeekBrains\advancephp', 'src', $file);
//     var_dump($file);
//     $subfile = strrchr($file, DIRECTORY_SEPARATOR);
//     $subfilenew = str_replace('_', DIRECTORY_SEPARATOR, $subfile);
//     $file = str_replace($subfile, $subfilenew, $file);
//     var_dump($file);
//     if (file_exists($file)) {
//         require $file;
//     }
// });

$faker = Faker\Factory::create('ru_RU');

$name = new Person(
    new Name($faker->firstName(), $faker->lastName()),
    new DateTimeImmutable()
);
$user = new User(1, $name, "Admin");

$post = new Post(
    1,
    $user,
    $faker->sentence(),
    $faker->paragraph()
);

$name2 = new Person(new Name($faker->firstName(), $faker->lastName()), new DateTimeImmutable());
$user2 = new User(2, $name2, "Moderator");
$comment = new Comment(
    1,
    $user2,
    $post,
    $faker->sentence(8)
);
switch ($argv[1]) {
    case "user":
        echo $user->descriptionUser();
        break;
    case "post":
        print $post;
        echo $post->getPost(1);
        break;
    case "comment":
        echo $comment;
        break;
    default:
        echo "The End";
}

// $userRepository = new InMemoryUsersRepository();
// $userRepository->save($user);
// $userRepository->save($user2);

// try {
//     echo $userRepository->get(1)->descriptionUser();
//     echo $userRepository->get(2)->descriptionUser();
//     echo $userRepository->get(3)->descriptionUser();
// } catch (UserNotFoundException $e) {
//     echo $e->getMessage();
// } catch (Exception $e) {
//     echo "I don't know";
//     echo $e->getMessage();
//}