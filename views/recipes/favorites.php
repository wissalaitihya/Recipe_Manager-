<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}
require_once __DIR__ . '/../../models/favorites.php';
$favoriteModel = new Favorite_model();
$recipes = $favoriteModel->getFavorite($_SESSION['user_id']);
$user_name = $_SESSION['user_name'] ?? 'Utilisateur';
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Favoris - Marrakech Food Lovers</title>

    <!-- Add elegant Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Poppins:wght@300;400;500;600&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="../css/style.css">
    <style>
        :root {
            /* Restaurant palette */
            --primary-dark: #2F241F;
            --gold: #D6A848;
            --light-gold: #FDF9F1;
            --text-main: #333333;
            --text-muted: #777777;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #FAFAFA;
            color: var(--text-main);
            margin: 0;
            padding: 0;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        .logo {
            font-family: 'Playfair Display', serif;
        }

        /* Header */
        .header-public {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 5%;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.05);
        }

        .header-public .logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-dark);
            text-decoration: none;
            letter-spacing: 1px;
        }

        .header-nav {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .user-name {
            color: var(--text-main);
            font-weight: 500;
        }

        .btn-gold {
            background-color: var(--gold);
            color: #fff;
            padding: 0.6rem 1.5rem;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            font-size: 0.9rem;
        }

        .btn-gold:hover {
            background-color: #bfa140;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(214, 168, 72, 0.3);
        }

        .btn-secondary {
            background-color: transparent;
            color: var(--primary-dark);
            padding: 0.6rem 1.5rem;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            border: 1px solid var(--primary-dark);
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            font-size: 0.9rem;
        }

        .btn-secondary:hover {
            background-color: var(--primary-dark);
            color: #fff;
        }

        /* Hero Section */
        .hero {
            position: relative;
            text-align: center;
            padding: 6rem 2rem;
            background: linear-gradient(rgba(47, 36, 31, 0.7), rgba(47, 36, 31, 0.7)), url('https://images.unsplash.com/photo-1495521821757-a1efb6729352?auto=format&fit=crop&q=80&w=1920&h=600');
            background-size: cover;
            background-position: center;
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .hero-title {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--gold);
            text-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        /* Recipe Grid */
        .recipes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 2rem;
            padding: 3rem 5%;
            margin-bottom: 4rem;
        }

        .recipe-card {
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #eaeaea;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .recipe-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.08);
            border-color: transparent;
        }

        .recipe-card-header {
            padding: 1.5rem;
            background: var(--light-gold);
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .recipe-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.4rem;
            color: var(--primary-dark);
            margin: 0;
        }

        .recipe-meta {
            display: flex;
            justify-content: space-between;
            padding: 1rem 1.5rem;
            border-top: 1px solid #f0f0f0;
            border-bottom: 1px solid #f0f0f0;
            font-size: 0.85rem;
            color: var(--text-main);
            font-weight: 500;
        }

        .recipe-description {
            font-size: 0.9rem;
            color: var(--text-muted);
            line-height: 1.6;
            padding: 1rem 1.5rem;
            flex-grow: 1;
        }

        .recipe-actions {
            padding: 1rem 1.5rem;
            display: flex;
            gap: 1rem;
        }

        .btn-danger {
            background-color: #dc3545;
            color: #fff;
            padding: 0.6rem 1rem;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            font-size: 0.85rem;
            flex: 1;
            text-align: center;
        }

        .btn-danger:hover {
            background-color: #c82333;
            transform: translateY(-2px);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
        }

        .empty-state-icon {
            font-size: 4rem;
            color: var(--gold);
            margin-bottom: 1rem;
        }

        .empty-state-text {
            font-size: 1.2rem;
            color: var(--text-muted);
            font-family: 'Playfair Display', serif;
        }

        .footer {
            background: var(--primary-dark);
            color: #fff;
            text-align: center;
            padding: 3rem 0;
            margin-top: 4rem;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .header-public {
                flex-direction: column;
                padding: 1rem;
                gap: 1rem;
            }

            .user-info {
                flex-direction: column;
                width: 100%;
            }

            .user-info a {
                width: 100%;
                text-align: center;
            }

            .hero {
                padding: 4rem 1rem;
            }

            .hero-title {
                font-size: 2.2rem;
            }

            .recipes-grid {
                grid-template-columns: 1fr;
                padding: 2rem 1rem;
                gap: 1.5rem;
            }

            .recipe-meta {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>

    <!-- Header -->
    <header class="header-public">
        <a href="home.php" class="logo">Marrakech Food Lovers</a>
        <div class="user-info">
            <span class="user-name">♦ <?php echo htmlspecialchars($user_name); ?></span>
            <a href="dashboard.php" class="btn-secondary">← Mes Recettes</a>
            <a href="../../controllers/AuthController.php?action=logout" class="btn-secondary">Déconnexion</a>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <h1 class="hero-title">♥ Mes Favoris</h1>
    </section>

    <!-- Main Content -->
    <main>
        <?php if (empty($recipes)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">♡</div>
                <p class="empty-state-text">Aucune recette favorite pour le moment.</p>
                <p style="color: var(--text-muted); margin-top: 1rem;">
                    <a href="home.php" style="color: var(--gold); text-decoration: none; font-weight: 500;">Découvrez nos
                        recettes →</a>
                </p>
            </div>
        <?php else: ?>
            <div class="recipes-grid">
                <?php foreach ($recipes as $recipe): ?>
                    <div class="recipe-card">
                        <div class="recipe-card-header">
                            <h3 class="recipe-title"><?php echo htmlspecialchars($recipe['title']); ?></h3>
                        </div>
                        <div class="recipe-meta">
                            <span>⏲ <?php echo htmlspecialchars($recipe['temp_de_production']); ?></span>
                            <span>🥄 <?php echo htmlspecialchars($recipe['portions']); ?> portions</span>
                        </div>
                        <p class="recipe-description">
                            <?php echo htmlspecialchars(substr($recipe['ingredient'] ?? 'Recette du chef...', 0, 100)) . '...'; ?>
                        </p>
                        <div class="recipe-actions">
                            <a href="../../controllers/RecipeController.php?action=toggleFavorite&id=<?php echo $recipe['id']; ?>" class="btn-danger">♥
                                Retirer des favoris</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <h2 style="margin-top: 0; color: var(--gold); font-size: 2rem;">Marrakech Food Lovers</h2>
        <p style="opacity: 0.7; font-size: 0.9rem; max-width: 400px; margin: 1rem auto;">L'expérience culinaire
            marocaine authentique.</p>
        <div style="margin-top: 2rem; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 2rem; opacity: 0.5;">
            &copy; <?php echo date('Y'); ?> Marrakech Food Lovers. Tous droits réservés.
        </div>
    </footer>

</body>

</html>






?>