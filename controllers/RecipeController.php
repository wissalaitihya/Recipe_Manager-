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
        $users_id = $_SESSION['user_id'] ?? null;
        if (!$users_id) {
            header("Location: ../views/auth/login.php");
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

            // Handle image upload
            $imageName = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $uploadDir = __DIR__ . '/../uploads/';

                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $imageName = time() . '_' . basename($_FILES['image']['name']);
                $targetPath = $uploadDir . $imageName;
                move_uploaded_file($_FILES['image']['tmp_name'], $targetPath);
            }

            if (!empty($title) && !empty($categories_id)) {
                if (empty($id)) {
                    $this->createRecipe($users_id, $title, $categories_id, $temp_de_production, $ingredient, $instructions, $portions, $imageName);
                    $_SESSION['success'] = "Recette créée avec succès!";
                } else {
                    $this->updateRecipe($id, $users_id, $title, $categories_id, $temp_de_production, $ingredient, $instructions, $portions, $imageName);
                    $_SESSION['success'] = "Recette modifiée avec succès!";
                }
            }
            header("Location: ../views/recipes/dashboard.php");
            exit();
        }
    }

    public function delete()
    {
        session_start();
        $users_id = $_SESSION['user_id'] ?? null;
        if (!$users_id) {
            header("Location: ../views/auth/login.php");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            if ($id) {
                $this->recipeModel->delete_recette($users_id, $id);
                $_SESSION['success'] = "Recette supprimée avec succès!";
            }
            header("Location: ../views/recipes/dashboard.php");
            exit();
        }
    }

    // Create a new recipe
    public function createRecipe($users_id, $title, $categories_id, $temp_de_production, $ingredient, $instructions, $portions, $image = null)
    {
        return $this->recipeModel->create_recette($users_id, $title, $categories_id, $temp_de_production, $ingredient, $instructions, $portions, $image);
    }

    public function updateRecipe($id, $users_id, $title, $categories_id, $temp_de_production, $ingredient, $instructions, $portions, $image = null)
    {
        return $this->recipeModel->update_recette($id, $users_id, $title, $categories_id, $temp_de_production, $ingredient, $instructions, $portions, $image);
    }

    public function handleRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            if ($action === 'delete') {
                $this->delete();
            } else {
                $this->save();
            }
        }
    }
    public function toggleFavorite()
    {
        require_once __DIR__ . '/../models/favorites.php';
        $favoriteModel = new Favorite_model();

        $user_id = $_SESSION['user_id'] ?? null;
        $recipe_id = $_GET['id'] ?? null;

        if ($user_id && $recipe_id) {
            if ($favoriteModel->isFavorite($user_id, $recipe_id)) {
                $favoriteModel->removeFavorite($user_id, $recipe_id);
            } else {
                $favoriteModel->addFavorite($user_id, $recipe_id);
            }
        }

        header("Location: " . ($_GET['redirect'] ?? '../views/recipes/favorites.php'));
        exit();
    }
}

// Handle requests
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recipeController = new RecipeController();
    $recipeController->handleRequest();
} elseif (isset($_GET['action']) && $_GET['action'] === 'toggleFavorite') {
    $recipeController = new RecipeController();
    $recipeController->toggleFavorite();
}
