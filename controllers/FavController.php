<?php
session_start();

require_once __DIR__ . '/../models/favorites.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/auth/login.php");
    exit();
}
$user_id = $_SESSION['user_id'];
$recette_id = isset($_GET['id']) ? $_GET['id'] : null;
if (!$recette_id) {
    header("Location: ../views/recipes/dashboard.php");
    exit();
}
$favoriteModel = new Favorite_model();

if ($favoriteModel->isFavorite($user_id, $recette_id)) {
    //  already favorite remove it
    $favoriteModel->removeFavorite($user_id, $recette_id);
    header("Location: ../views/recipes/dashboard.php");
} else {
    // not favorite add it
    $favoriteModel->addFavorite($user_id, $recette_id);
    header("Location: ../views/recipes/dashboard.php");
}



?>