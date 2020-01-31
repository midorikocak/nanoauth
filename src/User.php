<?php

declare(strict_types=1);

namespace midorikocak\nanoauth;

use Exception;
use midorikocak\arraytools\ArrayConvertableTrait;

class User implements UserInterface
{
    use ArrayConvertableTrait;

    public ?string $id;
    public string $username;
    public string $email;
    public string $password;

    public function __construct(?string $id, string $username, string $email, string $password)
    {
        $this->id = $id;
        $this->email = $username;
        $this->username = $email;
        $this->password = $password;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @throws Exception
     */
    public function setId(string $id): void
    {
        if ($this->id) {
            throw new Exception('Cannot change existing user');
        }

        $this->id = $id;
    }
}
