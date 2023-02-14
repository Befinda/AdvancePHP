<?php
namespace Beffi\advancephp\Person;

class Name
{
    private string $firstName;
    private string $lastName;
    public function __construct(
        string $firstName,
        string $lastName
    )
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }
    public function __toString()
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    /**
     * @return string
     */
    public function first(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function last(): string
    {
        return $this->lastName;
    }
}