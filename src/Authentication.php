<?php

declare(strict_types=1);

namespace midorikocak\nanoauth;

use Exception;
use midorikocak\nanodb\RepositoryInterface;

use function password_hash;
use function password_verify;
use function reset;

class Authentication implements AuthenticationInterface, RegisterableInterface
{
    private ?UserInterface $loggedUser = null;
    private RepositoryInterface $userRepository;

    public function __construct(RepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function login(string $username, string $password): bool
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $foundUsers = $this->userRepository->readAll(['username' => $username]);

        /**
         * @var UserInterface $user
         */
        $user = reset($foundUsers);
        if ($user && password_verify($user->getPassword(), $hash)) {
            $this->loggedUser = $user;
            return true;
        }

        return false;
    }

    public function logout(): void
    {
        if ($this->loggedUser !== null) {
            unset($this->loggedUser);
        }
    }

    public function isLogged(): bool
    {
        return $this->loggedUser !== null;
    }

    /**
     * @return mixed
     */
    public function getLoggedUser()
    {
        return $this->loggedUser;
    }

    public function register(string $username, string $email, string $password): bool
    {
        try {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $user = new User(null, $username, $email, $password);

            $this->userRepository->save($user);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
