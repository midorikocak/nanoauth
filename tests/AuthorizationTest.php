<?php

declare(strict_types=1);

namespace midorikocak\nanoauth;

use midorikocak\nanodb\Database;
use PDO;
use PHPUnit\Framework\TestCase;

final class AuthorizationTest extends TestCase
{
    private Authentication $auth;
    private App $app;
    private UserRepository $userRepository;
    private PDO $pdo;
    private Database $db;
    private array $userData;

    public function setUp(): void
    {
        parent::setUp();

        $this->pdo = new PDO('sqlite::memory:');
        $this->db = new Database($this->pdo);

        $this->createUsersTable();
        $this->createEntriesTable();

        $this->userData = [
            'username' => 'newuser',
            'email' => 'email@email.com',
            'password' => '87654321',
        ];

        $this->entryData = [
            'content' => 'Bugün güzel şeyler oldu. Daha da güzel şeyler olacak.',
        ];

        $this->insertUser($this->userData['email'], $this->userData['username'], $this->userData['password']);
        $this->insertEntry($this->entryData['content']);

        $this->userRepository = new UserRepository($this->db);

        $this->auth = new Authentication($this->userRepository);
        $this->app = new App(new Journal($this->db));

        $this->app->setAuthentication($this->auth);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->app, $this->auth, $this->pdo, $this->db);
    }

    private function createUsersTable(): void
    {
        try {
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //Error Handling
            $sql = "CREATE table users(
     id INTEGER PRIMARY KEY,
     username TEXT NOT NULL UNIQUE,
     email TEXT NOT NULL UNIQUE,
     password TEXT NOT NULL);";
            $this->pdo->exec($sql);
        } catch (PDOException $e) {
            echo $e->getMessage(); //Remove or change message in production code
        }
    }

    private function createEntriesTable(): void
    {
        try {
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //Error Handling
            $sql = "CREATE table entries(
     id INTEGER PRIMARY KEY,
     content TEXT NOT NULL,
     created DATETIME DEFAULT CURRENT_TIMESTAMP);";
            $this->pdo->exec($sql);
        } catch (PDOException $e) {
            echo $e->getMessage(); //Remove or change message in production code
        }
    }

    private function insertEntry($content, $created = null): void
    {
        $sql = "INSERT INTO entries (content, created) VALUES (?,?)";
        $statement = $this->pdo->prepare($sql);
        $statement->execute([$content, $created]);
    }

    private function insertUser($email, $username, $password): void
    {
        $sql = "INSERT INTO users (email, username, password) VALUES (?,?,?)";
        $statement = $this->pdo->prepare($sql);
        $statement->execute([$email, $username, $password]);
    }

    public function testAuthSuccess(): void
    {
        $this->auth->login($this->userData['username'], $this->userData['password']);
        $this->assertTrue($this->auth->isLogged());

        $this->app->addEntry('some entries');

        $this->assertNotEmpty($this->app->getEntries());
    }

    public function testAuthFail(): void
    {
        $this->expectException(UnauthorizedException::class);
        $this->app->getAuthentication()->logout();
        $this->app->getEntries();
    }
}
