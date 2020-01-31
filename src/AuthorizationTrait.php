<?php

declare(strict_types=1);

namespace midorikocak\nanoauth;

trait AuthorizationTrait
{
    private ?AuthenticationInterface $authentication = null;

    public function checkLogin(): bool
    {
        if ($this->authentication && !$this->authentication->isLogged()) {
            throw new UnauthorizedException();
        }

        return $this->authentication->isLogged();
    }

    public function getAuthentication(): ?AuthenticationInterface
    {
        return $this->authentication;
    }

    public function setAuthentication(AuthenticationInterface $auth)
    {
        $this->authentication = $auth;
    }
}
