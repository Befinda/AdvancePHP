<?php
namespace Geekbrains\advancephp\Person;

use \DateTimeImmutable;


class Person
{
    //private Name $name;
    public function __construct(
        private Name $name,
        private DateTimeImmutable $registeredOn
    )
    {

    }
    public function __toString()
    {
        return $this->name . ' (на сайте с ' . $this->registeredOn->format('Y-m-d') . ')';
    }
}