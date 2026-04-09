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

    public function favorites()
    {
        include __DIR__ . '/../views/recipes/favorites.php';
    }

    public function toggleFavorite()
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: " . BASE_URL . "/auth/login");
            exit();
        }
        $user_id = $_SESSION['user_id'];
        $recette_id = $_GET['id'] ?? null;
        if (!$recette_id) {
            header("Location: " . BASE_URL . "/recipe/dashboard");
            exit();
        }
        require_once __DIR__ . '/../models/favorites.php';
        $favoriteModel = new Favorite_model();

        if ($favoriteModel->isFavorite($user_id, $recette_id)) {
            $favoriteModel->removeFavorite($user_id, $recette_id);
            header("Location: " . BASE_URL . "/recipe/favorites");
        } else {
            $favoriteModel->addFavorite($user_id, $recette_id);
            header("Location: " . BASE_URL . "/recipe/dashboard");
        }
        exit();
    }

    public function dashboard()
    {
        include __DIR__ . '/../views/recipes/dashboard.php';
    }

    public function home()
    {
        include __DIR__ . '/../views/recipes/home.php';
    }

    public function save()
    {
        session_start();
        $users_id = $_SESSION['user_id'] ?? null;
        if (!$users_id) {
            header("Location: " . BASE_URL . "/auth/login");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
            header("Location: " . BASE_URL . "/recipe/dashboard");
            exit();
        }
    }

    public function delete()
    {
        session_start();
        $users_id = $_SESSION['user_id'] ?? null;
        if (!$users_id) {
            header("Location: " . BASE_URL . "/auth/login");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            if ($id) {
                $this->recipeModel->delete_recette($users_id, $id);
                $_SESSION['success'] = "Recette supprimée avec succès!";
            }
            header("Location: " . BASE_URL . "/recipe/dashboard");
            exit();
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
}
