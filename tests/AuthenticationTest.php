<?php

declare(strict_types=1);

namespace midorikocak\nanoauth;

use midorikocak\nanodb\Database;
use PDO;
use PHPUnit\Framework\TestCase;

use function password_hash;

final class AuthenticationTest extends TestCase
{
    private Authentication $auth;
    private UserRepository $userRepository;
    private PDO $pdo;
    private Database $db;
    private array $userData;

    public function setUp(): void
    {
        parent::setUp();

        $this->pdo = new PDO('sqlite::memory:');
        $this->db = new Database($this->pdo);

        $this->createTable();

        $this->userData = [
            'username' => 'newuser',
            'email' => 'email@email.com',
            'password' => '87654321',
        ];

        $this->insertUser(
            $this->userData['email'],
            $this->userData['username'],
            password_hash($this->userData['password'], PASSWORD_DEFAULT)
        );

        $this->userRepository = new UserRepository($this->db);

        $this->auth = new Authentication($this->userRepository);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->app, $this->auth, $this->pdo, $this->db);
    }

    private function createTable(): void
    {
        try {
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //Error Handling
            $sql = "CREATE table users(
     id INTEGER PRIMARY KEY,
     username TEXT NOT NULL UNIQUE,
     email TEXT NOT NULL UNIQUE,
     password VARCHAR(255) NOT NULL);";
            $this->pdo->exec($sql);
        } catch (PDOException $e) {
            echo $e->getMessage(); //Remove or change message in production code
        }
    }

    private function insertUser($email, $username, $password): void
    {
        $sql = "INSERT INTO users (email, username, password) VALUES (?,?,?)";
        $statement = $this->pdo->prepare($sql);
        $statement->execute([$email, $username, $password]);
    }

    public function testLogin(): void
    {
        $this->auth->login($this->userData['username'], $this->userData['password']);
        $this->assertTrue($this->auth->isLogged());
    }

    public function testLogout(): void
    {
        $this->auth->logout();
        $this->assertFalse($this->auth->isLogged());
    }

    public function testGetLoggedUser(): void
    {
        $this->auth->login($this->userData['username'], $this->userData['password']);
        $this->assertNotEmpty($this->auth->getLoggedUser());
    }

    public function testRegister(): void
    {
        $this->auth->register('username2', 'email@email2.com', 'password2');
        $this->auth->login('username2', 'password2');
        $this->assertTrue($this->auth->isLogged());
        $this->assertNotEmpty($this->auth->getLoggedUser());
    }
}
