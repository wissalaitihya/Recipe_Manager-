<?php
require_once __DIR__ . '/../config/db.php';

class User
{
    private $id;
    private $username;
    private $email;
    private $password;
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function setId($id) { $this->id = $id; }
    public function getId() { return $this->id; }

    public function setUsername($username) { $this->username = $username; }
    public function getUsername() { return $this->username; }

    public function setEmail($email) { $this->email = $email; }
    public function getEmail() { return $this->email; }

    public function setPassword($password) { $this->password = $password; }
    public function getPassword() { return $this->password; }

    public function register()
    {
        if ($this->emailExists()) {
            return false;
        }

        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $hashedPassword = password_hash($this->password, PASSWORD_BCRYPT);
        return $stmt->execute([$this->username, $this->email, $hashedPassword]);
    }

    public function login()
    {
        $sql = "SELECT id, username, password FROM users WHERE email = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$this->email]);
        $userRow = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($userRow && password_verify($this->password, $userRow['password'])) {
            $this->id = $userRow['id'];
            $this->username = $userRow['username'];
            return true;
        }
        return false;
    }

    private function emailExists()
    {
        $sql = "SELECT id FROM users WHERE email = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$this->email]);
        return $stmt->rowCount() > 0;
    }
}
