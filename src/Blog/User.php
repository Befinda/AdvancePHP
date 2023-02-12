<?php
namespace Beffi\advancephp\Blog;

use Beffi\advancephp\Person\Person;

class User
{
    private int $id;
    private Person $username;
    private string $login;



    /**
     * @param int $id
     * @param Person $username
     * @param string $login
     */
    public function __construct(int $id, Person $username, string $login)
    {
        $this->id = $id;
        $this->username = $username;
        $this->login = $login;
    }

    public function __toString(): string
    {
        return $this->username->getName();
    }
    public function id(): int
    {
        return $this->id;
    }
    public function setId(int $id): void
    {
        $this->id = $id;
    }
    public function getUsername(): string
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
    public function descriptionUser(): string
    {
        return "Пользователь №$this->id $this->username с логином $this->login" . PHP_EOL;
    }
}