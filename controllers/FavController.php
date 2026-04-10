<?php
session_start();

require_once __DIR__ . '/../models/favorites.php';

class favoriteController
{
    private $favoriteModel;
    function __construct()
    {
        $this->favoriteModel = new Favorite_model();
    }
    public function toggleFavoriteFav()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: ../views/auth/login.php");
            exit();
        }
        $user_id = $_SESSION['user_id'];
        $recette_id = $_GET['id'] ?? null;
        if (!$recette_id) {
            header("Location: ../views/recipes/dashboard.php");
            exit();
        }

        if ($this->favoriteModel->isFavorite($user_id, $recette_id)) {
            $this->favoriteModel->removeFavorite($user_id, $recette_id);
            header("Location: ../views/recipes/favorites.php");
        }
        exit();
    }
    public function toggleFavoriteDash()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: ../views/auth/login.php");
            exit();
        }
        $user_id = $_SESSION['user_id'];
        $recette_id = $_GET['id'] ?? null;
        if (!$recette_id) {
            header("Location: ../views/recipes/dashboard.php");
            exit();
        }

        if ($this->favoriteModel->isFavorite($user_id, $recette_id)) {
            $this->favoriteModel->removeFavorite($user_id, $recette_id);
            header("Location: ../views/recipes/dashboard.php");
        } else {
            $this->favoriteModel->addFavorite($user_id, $recette_id);
            header("Location: ../views/recipes/dashboard.php");
        }
        exit();
    }
}

$action = $_GET['action'] ?? null;
if ($action) {
    require_once __DIR__ . '/../models/favorites.php';
    $controller = new favoriteController();
    if ($action === 'toggleFavoriteFav') {
        $controller->toggleFavoriteFav();
    } if ($action === 'toggleFavoriteDash') {
        $controller->toggleFavoriteDash();
    }
}
