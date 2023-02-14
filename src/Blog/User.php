<?php
namespace Beffi\advancephp\Blog;

use Beffi\advancephp\Person\Person;

class User
{
    private UUID $uuid;

    private Person $username;
    private string $login;



    /**
     * @param UUID $uuid
     * @param Person $username
     * @param string $login
     */
    public function __construct(UUID $uuid, Person $username, string $login)
    {
        $this->uuid = $uuid;
        $this->username = $username;
        $this->login = $login;
    }

    // public function __toString(): string
    // {
    //     return $this->username->getName();
    // }
    public function name(): Person
    {
        return $this->username;
    }
    public function setUsername(Person $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @param string $login 
     */
    public function setLogin(string $login): void
    {
        $this->login = $login;

    }
    //public function descriptionUser(): string
    public function __toString(): string
    {
        return "Пользователь №$this->uuid $this->username с логином $this->login" . PHP_EOL;
    }




    /**
     * @return UUID
     */
    public function uuid(): UUID
    {
        return $this->uuid;
    }
}