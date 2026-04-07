<?php
require_once __DIR__ . '/../models/recipe.php';
require_once __DIR__ . '/../config/db.php';

class RecipeController
{
    private $recipeModel;

    public function __construct()
    {
        $this->recipeModel = new Recette_model();
    }

    public function handleRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
            $users_id = $_SESSION['user_id'] ?? null;
            if (!$users_id) {
                header("Location: ../views/auth/login.php");
                exit();
            }

            $id = $_POST['id'] ?? null;
            $title = $_POST['title'] ?? '';
            $categories_id = $_POST['category_id'] ?? null;
            $temp_de_production = $_POST['prep_time'] ?? '';
            $ingredient = $_POST['ingredients'] ?? '';
            $instructions = $_POST['instructions'] ?? '';
            $portions = $_POST['portions'] ?? '';

            if (!empty($title) && !empty($categories_id)) {
                if (empty($id)) {
                    $this->createRecipe($users_id, $title, $categories_id, $temp_de_production, $ingredient, $instructions, $portions);
                } else {
                    // Update recipe placeholder
                }
            }
            header("Location: ../views/recipes/dashboard.php?success=Recette+cree");
            exit();
        }
    }

    // Create a new recipe
    public function createRecipe($users_id, $title, $categories_id, $temp_de_production, $ingredient, $instructions, $portions)
    {
        return $this->recipeModel->create_recette($users_id, $title, $categories_id, $temp_de_production, $ingredient, $instructions, $portions);
    }
}

$recipeController = new RecipeController();
$recipeController->handleRequest();
