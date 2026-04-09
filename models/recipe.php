<?php

require_once __DIR__ . '/../config/db.php';

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

    public function getAllRecipes($category_id = null)
    {
        $sql = "SELECT r.*, c.name as category_name, u.username as chef_name
                FROM recette r 
                LEFT JOIN categories c ON r.categories_id = c.id
                LEFT JOIN users u ON r.users_id = u.id";
        $params = [];

        if ($category_id) {
            $sql .= " WHERE r.categories_id = ?";
            $params[] = $category_id;
        }

        $sql .= " ORDER BY r.create_time DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRecipesByUser($user_id, $category_id = null, $search = null)
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
        if ($search) {
        $sql .= " AND (r.title LIKE ? OR r.ingredient LIKE ?)";
        $params[] = '%' . $search . '%';
        $params[] = '%' . $search . '%';
    }
        $sql .= " ORDER BY r.create_time DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

     public function update_recette($id, $users_id, $title, $categories_id, $temp_de_production, $ingredient, $instructions, $portions)
     {
        $sql = "UPDATE recette SET title = ?, categories_id = ?, temp_de_production = ?, ingredient = ?, instructions = ?, portions = ? 
                WHERE id = ? AND users_id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$title, $categories_id, $temp_de_production, $ingredient, $instructions, $portions, $id, $users_id]);
     }

    public function delete_recette($users_id, $id)
    {
        $sql = "DELETE FROM recette WHERE users_id = ? AND id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$users_id, $id]);
    }
}