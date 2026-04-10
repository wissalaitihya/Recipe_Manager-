<?php
require_once __DIR__ . '/../config/db.php';

class Favorite_model
{
    private $pdo;
    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function addFavorite($user_id, $recette_id)
    {
        $sql = "INSERT INTO favorites (user_id, recette_id) VALUES (?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$user_id, $recette_id]);
    }
    public function removeFavorite($user_id, $recette_id)
    {
        $sql = "DELETE FROM favorites WHERE user_id = ? AND recette_id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$user_id, $recette_id]);
    }
    public function isFavorite($user_id, $recette_id)
    {
        $sql = "SELECT * FROM favorites WHERE user_id = ? AND recette_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$user_id, $recette_id]);
        return $stmt->rowCount() > 0;
    }
    public function getFavorite($user_id)
    {
        $sql = "SELECT r.*, c.name as category_name FROM favorites f JOIN recette r ON f.recette_id = r.id LEFT JOIN categories c ON r.categories_id = c.id WHERE f.user_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}