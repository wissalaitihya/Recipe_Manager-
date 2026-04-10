<?php
if (session_status() === PHP_SESSION_NONE)
    session_start();
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
    <title>Mes Favoris - Matbakhi</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap"
        rel="stylesheet">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --bg: #f5f0e8;
            --card: #ffffff;
            --primary: #b5503a;
            --primary-hover: #9a3f2b;
            --accent: #d4a574;
            --text: #2a1f1a;
            --muted: #7a6a60;
            --border: #e0d4c5;
            --radius: 12px;
            --heart: #e74c3c;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            line-height: 1.6;
        }

        /* ── HEADER ── */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 6%;
            background: var(--bg);
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 200;
        }

        .logo-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .logo-icon {
            width: 38px;
            height: 38px;
            background: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
        }

        .logo-icon svg {
            width: 20px;
            height: 20px;
            stroke: white;
            stroke-width: 1.5;
        }

        .logo-text {
            font-family: 'DM Sans', sans-serif;
            font-weight: 600;
            font-size: 24px;
            color: var(--text);
        }

        .nav {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .nav-link {
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            color: var(--text);
            transition: color .2s;
        }

        .nav-link:hover {
            color: var(--primary);
        }

        .btn-primary {
            background: var(--primary);
            color: #fff;
            border: none;
            padding: 9px 22px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            transition: background .2s;
        }

        .btn-primary:hover {
            background: var(--primary-hover);
        }

        /* ── HERO SECTION ── */
        .hero {
            background: linear-gradient(135deg, rgba(231, 76, 60, 0.1) 0%, rgba(212, 165, 116, 0.1) 100%);
            padding: 60px 6%;
            text-align: center;
            border-bottom: 2px solid var(--border);
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '❤️';
            position: absolute;
            font-size: 120px;
            opacity: 0.08;
            top: -20px;
            right: -20px;
        }

        .hero::after {
            content: '❤️';
            position: absolute;
            font-size: 80px;
            opacity: 0.06;
            bottom: -10px;
            left: 20px;
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(231, 76, 60, 0.1);
            color: var(--heart);
            padding: 8px 16px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 16px;
            letter-spacing: 0.5px;
        }

        .hero-badge svg {
            stroke: var(--heart);
            margin-right: 8px;
            flex-shrink: 0;
        }

        .hero h1 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 48px;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 12px;
        }

        .hero p {
            font-size: 16px;
            color: var(--muted);
            max-width: 600px;
            margin: 0 auto 24px;
        }

        .hero-stats {
            display: flex;
            justify-content: center;
            gap: 60px;
            margin-top: 32px;
        }

        .stat {
            text-align: center;
        }

        .stat-number {
            font-family: 'Cormorant Garamond', serif;
            font-size: 32px;
            font-weight: 700;
            color: var(--primary);
        }

        .stat-label {
            font-size: 12px;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* ── MAIN CONTENT ── */
        .main-section {
            padding: 60px 6%;
            max-width: 1300px;
            margin: 0 auto;
        }

        /* ── RECIPE GRID ── */
        .recipes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 28px;
        }

        .recipe-card {
            background: var(--card);
            border-radius: var(--radius);
            overflow: hidden;
            border: 1px solid var(--border);
            cursor: pointer;
            transition: transform .25s, box-shadow .25s;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .recipe-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.12);
        }

        .recipe-image-wrapper {
            position: relative;
            width: 100%;
            height: 220px;
            background: linear-gradient(135deg, #e8ddd0 0%, #d4c8b8 100%);
            overflow: hidden;
        }

        .recipe-image-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.35s ease;
        }

        .recipe-card:hover .recipe-image-wrapper img {
            transform: scale(1.06);
        }

        .recipe-fav-btn {
            position: absolute;
            top: 12px;
            right: 12px;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #fff;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: all 0.2s;
            z-index: 10;
        }

        .recipe-fav-btn svg {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
        }

        .recipe-fav-btn:hover {
            transform: scale(1.15);
            box-shadow: 0 6px 16px rgba(231, 76, 60, 0.3);
        }

        .recipe-body {
            padding: 20px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .recipe-category {
            display: inline-block;
            font-size: 11px;
            color: var(--primary);
            background: rgba(181, 80, 58, 0.08);
            padding: 4px 12px;
            border-radius: 999px;
            font-weight: 600;
            margin-bottom: 12px;
            width: fit-content;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .recipe-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 20px;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 10px;
            line-height: 1.3;
        }

        .recipe-meta {
            display: flex;
            gap: 20px;
            font-size: 13px;
            color: var(--muted);
            margin-bottom: 12px;
        }

        .recipe-meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .recipe-meta-item svg {
            stroke: var(--muted);
            flex-shrink: 0;
        }

        .recipe-actions {
            display: flex;
            gap: 10px;
            margin-top: auto;
        }

        .btn-action {
            flex: 1;
            padding: 10px 12px;
            border: 1px solid var(--border);
            background: transparent;
            color: var(--text);
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
        }

        .btn-action svg {
            stroke: var(--text);
            stroke-width: 1.5;
            flex-shrink: 0;
        }

        .btn-action:hover {
            background: var(--bg);
            border-color: var(--primary);
            color: var(--primary);
        }

        .btn-action:hover svg {
            stroke: var(--primary);
        }

        .btn-remove {
            background: rgba(231, 76, 60, 0.1);
            color: var(--heart);
            border-color: rgba(231, 76, 60, 0.2);
        }

        .btn-remove svg {
            stroke: var(--heart);
        }

        .btn-remove:hover {
            background: var(--heart);
            color: #fff;
            border-color: var(--heart);
        }

        .btn-remove:hover svg {
            stroke: #fff;
        }

        /* ── EMPTY STATE ── */
        .empty-state {
            text-align: center;
            padding: 80px 40px;
            background: linear-gradient(135deg, rgba(181, 80, 58, 0.05) 0%, rgba(212, 165, 116, 0.05) 100%);
            border-radius: var(--radius);
            border: 2px dashed var(--border);
        }

        .empty-icon {
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .empty-icon svg {
            stroke: var(--heart);
        }

        .empty-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 28px;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 10px;
        }

        .empty-text {
            color: var(--muted);
            margin-bottom: 30px;
            font-size: 15px;
        }

        .empty-cta {
            display: inline-block;
            background: var(--primary);
            color: #fff;
            padding: 12px 30px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            transition: background 0.2s;
        }

        .empty-cta:hover {
            background: var(--primary-hover);
        }

        /* ── FOOTER ── */
        footer {
            text-align: center;
            padding: 40px 20px;
            color: var(--muted);
            font-size: 13px;
            border-top: 1px solid var(--border);
            margin-top: 60px;
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 768px) {
            header {
                flex-direction: column;
                gap: 16px;
                padding: 14px 4%;
            }

            .hero {
                padding: 40px 4%;
            }

            .hero h1 {
                font-size: 32px;
            }

            .hero-stats {
                flex-direction: column;
                gap: 30px;
            }

            .recipes-grid {
                grid-template-columns: 1fr;
            }

            .main-section {
                padding: 40px 4%;
            }

            .empty-state {
                padding: 50px 20px;
            }
        }
    </style>
</head>

<body>

    <!-- HEADER -->
    <header>
        <a href="home.php" class="logo-wrap">
            <div class="logo-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 13.87A4 4 0 0 1 7.41 6.64a2 2 0 0 1 3.09-2.5a2 2 0 0 1 3.09 2.5A4 4 0 0 1 18 13.87" />
                    <line x1="6" y1="17" x2="18" y2="17" />
                    <line x1="6" y1="13.87" x2="18" y2="13.87" />
                </svg>
            </div>
            <span class="logo-text">Matbakhi</span>
        </a>
        <nav class="nav">
            <a href="home.php" class="nav-link">Accueil</a>
            <a href="dashboard.php" class="nav-link">Mes Recettes</a>
            <a href="../../controllers/AuthController.php?action=logout" class="btn-primary">Déconnexion</a>
        </nav>
    </header>

    <!-- HERO SECTION -->
    <section class="hero">
        <div class="hero-content">
            <div class="hero-badge">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"
                    style="margin-right: 6px; vertical-align: middle;">
                    <path
                        d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                </svg>
                Vos Recettes Chéries
            </div>
            <h1>Mes Favoris</h1>
            <p>Retrouvez toutes les recettes que vous avez marquées comme favoris. Vos trésors culinaires préférés en un
                seul endroit.</p>
            <div class="hero-stats">
                <div class="stat">
                    <div class="stat-number"><?= count($recipes) ?></div>
                    <div class="stat-label">Recettes Favoris</div>
                </div>
                <div class="stat">
                    <div class="stat-number">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
                            fill="currentColor" stroke="var(--primary)">
                            <polygon
                                points="12 2 15.09 10.26 24 10.5 17.55 16.16 19.64 24.5 12 19.27 4.36 24.5 6.45 16.16 0 10.5 8.91 10.26" />
                        </svg>
                    </div>
                    <div class="stat-label">Vos Préférées</div>
                </div>
            </div>
        </div>
    </section>

    <!-- MAIN CONTENT -->
    <section class="main-section">
        <?php if (empty($recipes)): ?>
            <div class="empty-state">
                <div class="empty-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none"
                        stroke="var(--heart)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path
                            d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                    </svg>
                </div>
                <h2 class="empty-title">Aucune recette favorites</h2>
                <p class="empty-text">Vous n'avez pas encore ajouté de recettes à vos favoris. Explorez et sauvegardez vos
                    recettes préférées!</p>
                <a href="home.php" class="empty-cta">Découvrir les Recettes →</a>
            </div>
        <?php else: ?>
            <div class="recipes-grid">
                <?php foreach ($recipes as $recipe): ?>
                    <?php
                    $recipePhoto = !empty($recipe['image'])
                        ? '../../uploads/' . htmlspecialchars($recipe['image'])
                        : $food_photos[$recipe['id'] % count($food_photos)];
                    ?>
                    <div class="recipe-card">
                        <div class="recipe-image-wrapper">
                            <img src="<?= $recipePhoto ?>" alt="<?= htmlspecialchars($recipe['title']) ?>" loading="lazy">
                            <a href="../../controllers/RecipeController.php?action=toggleFavorite&id=<?= $recipe['id'] ?>"
                                class="recipe-fav-btn" title="Retirer des favoris">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                    fill="var(--heart)">
                                    <path
                                        d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                                </svg>
                            </a>
                        </div>
                        <div class="recipe-body">
                            <span
                                class="recipe-category"><?= htmlspecialchars($recipe['category_name'] ?? 'Sans catégorie') ?></span>
                            <h3 class="recipe-title"><?= htmlspecialchars($recipe['title']) ?></h3>
                            <div class="recipe-meta">
                                <div class="recipe-meta-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10" />
                                        <polyline points="12 6 12 12 16 14" />
                                    </svg>
                                    <span><?= htmlspecialchars($recipe['temp_de_production']) ?> min</span>
                                </div>
                                <div class="recipe-meta-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M9 6h6v2H9z" />
                                        <path d="M10 9h4v8c0 1.1-.9 2-2 2s-2-.9-2-2z" />
                                        <circle cx="12" cy="3" r="1" />
                                    </svg>
                                    <span><?= htmlspecialchars($recipe['portions']) ?> portions</span>
                                </div>
                            </div>
                            <div class="recipe-actions">
                                <a href="../../controllers/RecipeController.php?action=toggleFavorite&id=<?= $recipe['id'] ?>"
                                    class="btn-action btn-remove" style="flex: 1;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" style="margin-right: 4px; vertical-align: middle;">
                                        <line x1="18" y1="6" x2="6" y2="18" />
                                        <line x1="6" y1="6" x2="18" y2="18" />
                                    </svg>
                                    Retirer des favoris
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <!-- FOOTER -->
    <footer>
        © <?= date('Y') ?> — Matbakhi • Préservez votre héritage culinaire
    </footer>

</body>

</html>