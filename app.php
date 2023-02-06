<?php

use GeekBrains\Blog\Post;
use GeekBrains\Person\Name;
use GeekBrains\Person\Person;

spl_autoload_register(function ($class) {
    $file = $class . ".php";
    $file = str_replace('\\', DIRECTORY_SEPARATOR, $file);
    $file = str_replace('GeekBrains', 'src', $file);
    //var_dump($file);
    $subfile = strrchr($file, DIRECTORY_SEPARATOR);
    $subfilenew = str_replace('_', DIRECTORY_SEPARATOR, $subfile);
    $file = str_replace($subfile, $subfilenew, $file);
    //var_dump($file);
    if (file_exists($file)) {
        require $file;
    }
});

$post = new Post(
    new Person(
        new Name('Иван', 'Никитин'),
        new DateTimeImmutable()
    ),
    'Всем привет!'
);
print $post;