<?php
session_start();
$user_name = $_SESSION['user_name'] ?? 'Utilisateur';

require_once __DIR__ . '/../../models/favorites.php';
$favoriteModel = new Favorite_model();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/../views/css/style.css">
</head>
<body>
    <div class="container">
        <header class="header">
            <div class="logo">Marrakech Food Lovers</div>
            <div class="user-info">
                <span class="user-name"><?php echo htmlspecialchars($user_name); ?></span>
                <a href="<?php echo BASE_URL; ?>/recipe/favorites" class="btn btn-ghost btn-sm">❤️ Mes Favoris</a>
                <a href="<?php echo BASE_URL; ?>/auth/handleLogout"
                    class="btn btn-ghost btn-sm">Deconnexion</a>
            </div>
        </header>
    </div>
</body>
</html>