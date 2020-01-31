<?php

declare(strict_types=1);

namespace midorikocak\nanoauth;

interface RegisterableInterface
{
    public function register(string $username, string $email, string $password): bool;
}
