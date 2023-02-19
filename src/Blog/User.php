<?php
namespace Beffi\advancephp\Blog;

use Beffi\advancephp\Person\Person;

class User
{
    private UUID $uuid;
    private Person $name;
    private string $username;

    /**
     * @param UUID $uuid
     * @param Person $name
     * @param string $username
     */
    public function __construct(UUID $uuid, Person $name, string $username)
    {
        $this->uuid = $uuid;
        $this->name = $name;
        $this->username = $username;
    }

    // public function __toString(): string
    // {
    //     return $this->name->getName();
    // }
    public function name(): Person
    {
        return $this->name;
    }
    public function setName(Person $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function username(): string
    {
        return $this->username;
    }

    /**
     * @param string $username 
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;

    }
    /**
     * @return UUID
     */
    public function uuid(): UUID
    {
        return $this->uuid;
    }
    //public function descriptionUser(): string
    /**
     * @return string
     */
    public function __toString(): string
    {
        return "Пользователь №$this->uuid $this->name с логином $this->username" . PHP_EOL;
    }

}