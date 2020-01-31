<?php

declare(strict_types=1);

namespace midorikocak\nanoauth;

interface AuthenticationInterface
{
    public function login(string $username, string $password): bool;

    public function logout(): void;

    public function isLogged(): bool;

    /**
     * @return mixed
     */
    public function getLoggedUser();
}
