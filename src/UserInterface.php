<?php

declare(strict_types=1);

namespace midorikocak\nanoauth;

interface UserInterface
{
    public function __construct(?string $id, string $username, string $email, string $password);

    public function getPassword(): string;

    public function getUsername(): string;

    public function getEmail(): string;

    public function getId(): ?string;

    public function setId(string $id): void;
}
