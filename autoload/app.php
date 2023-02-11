<?php
use Geekbrains\advancephp\Person\Person;
use Geekbrains\advancephp\Person\Name;

set_include_path(__DIR__);
spl_autoload_register(function ($class) {
    $file = $class . ".php";
    //var_dump($file);
    $file = str_replace('\\', DIRECTORY_SEPARATOR, $file);
    //var_dump($file);
    $file = __DIR__ . str_replace('Geekbrains\advancephp', '', $file);
    // var_dump($file);
    $subfile = strrchr($file, DIRECTORY_SEPARATOR);
    $subfilenew = str_replace('_', DIRECTORY_SEPARATOR, $subfile);
    $file = str_replace($subfile, $subfilenew, $file);
    if (file_exists($file)) {
        require $file;
    }
});

$name = new Person(
    new Name("Иван", "Сидоров"),
    new DateTimeImmutable()
);
echo $name;