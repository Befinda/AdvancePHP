<?php
namespace Beffi\advancephp\Person;

use \DateTimeImmutable;
use Beffi\advancephp\Person\Name;


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
    public function getName(): string
    {
        return $this->name;
    }

}