<?php

require_once __DIR__ . '/../config/db.php';

class Category_model
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function getCategories()
    {
        $stmt = $this->pdo->query("SELECT id, name FROM categories");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
