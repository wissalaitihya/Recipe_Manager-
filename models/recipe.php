<?php

include "../config/db.php";

class Recette_model
{
    private $pdo;

    function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();
    }

    function create_recette($users_id, $title, $categories_id, $temp_de_production, $ingredient, $instructions, $portions)
    {
        $sql = "INSERT INTO recette (users_id, categories_id, title, temp_de_production, ingredient, instructions, portions) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$users_id, $categories_id, $title, $temp_de_production, $ingredient, $instructions, $portions]);
    }

    public function getRecipesByUser($user_id, $category_id = null)
    {
        $sql = "SELECT r.*, c.name as category_name 
                FROM recette r 
                LEFT JOIN categories c ON r.categories_id = c.id 
                WHERE r.users_id = ?";
        $params = [$user_id];

        if ($category_id) {
            $sql .= " AND r.categories_id = ?";
            $params[] = $category_id;
        }

        $sql .= " ORDER BY r.create_time DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}