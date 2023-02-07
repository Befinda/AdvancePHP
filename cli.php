<?php
set_include_path(__DIR__);


use Beffi\advancephp\Blog\Post;
use Beffi\advancephp\Person\Name;
use Beffi\advancephp\Person\Person;

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
//require_once "vendor/autoload.php";

$post = new Post(
    new Person(
        new Name('Иван', 'Никитин'),
        new DateTimeImmutable()
    ),
    'Всем привет!'
);
print $post;