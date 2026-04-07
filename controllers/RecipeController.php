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
        session_start();
        $users_id = $_SESSION['user_id'] ?? null;
        if (!$users_id) {
            header("Location: ../views/auth/login.php");
            exit();
        }

        $action = $_POST['action'] ?? $_GET['action'] ?? null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($action === 'delete') {
                $id = $_POST['id'] ?? null;
                if ($id) {
                    $this->deleteRecipe($users_id, $id);
                }
            } else {
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
                        $_SESSION['success'] = "Recette créée avec succès!";
                    } else {
                        $this->updateRecipe($id, $users_id, $title, $categories_id, $temp_de_production, $ingredient, $instructions, $portions);
                        $_SESSION['success'] = "Recette modifiée avec succès!";
                    }
                }
                header("Location: ../views/recipes/dashboard.php");
                exit();
            }
        }
    }

    // Create a new recipe
    public function createRecipe($users_id, $title, $categories_id, $temp_de_production, $ingredient, $instructions, $portions)
    {
        return $this->recipeModel->create_recette($users_id, $title, $categories_id, $temp_de_production, $ingredient, $instructions, $portions);
    }

    public function updateRecipe($id, $users_id, $title, $categories_id, $temp_de_production, $ingredient, $instructions, $portions)
    {
        return $this->recipeModel->update_recette($id, $users_id, $title, $categories_id, $temp_de_production, $ingredient, $instructions, $portions);
    }
    public function deleteRecipe($users_id, $id)
    {
        $this->recipeModel->delete_recette($users_id, $id);
        $_SESSION['success'] = "Recette supprimée avec succès!";
        header("Location: ../views/recipes/dashboard.php");
        exit();
    }

    


}

$recipeController = new RecipeController();
$recipeController->handleRequest();
