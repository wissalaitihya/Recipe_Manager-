<?php
include __DIR__ . '/../includes/headre.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

require_once __DIR__ . '/../../models/category.php';
require_once __DIR__ . '/../../models/recipe.php';

$categoryModel = new Category_model();
$categories = $categoryModel->getCategories();

$recipeModel = new Recette_model();
$filter_category = isset($_GET['category']) ? $_GET['category'] : null;
$search = isset($_GET["search"]) ? $_GET["search"] : null;
$recipes = $recipeModel->getRecipesByUser($_SESSION['user_id'], $filter_category, $search);
$user_name = $_SESSION['user_name'] ?? 'Utilisateur';

require_once __DIR__ . '/../../models/favorites.php';
$favoriteModel = new Favorite_model();

// Unsplash food photos — rotates through cards
$food_photos = [
    'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=600&q=80',
    'https://images.unsplash.com/photo-1574071318508-1cdbab80d002?w=600&q=80',
    'https://images.unsplash.com/photo-1547592166-23ac45744acd?w=600&q=80',
    'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=600&q=80',
    'https://images.unsplash.com/photo-1553530666-ba2a8e36ba70?w=600&q=80',
    'https://images.unsplash.com/photo-1482049016688-2d3e1b311543?w=600&q=80',
    'https://images.unsplash.com/photo-1467003909585-2f8a72700288?w=600&q=80',
    'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?w=600&q=80',
    'https://images.unsplash.com/photo-1540189549336-e6e99c3679fe?w=600&q=80',
];
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Recettes - Matbakhi</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600&display=swap"
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
            --bg: #f5ede0;
            --sidebar-bg: #ffffff;
            --sidebar-w: 270px;
            --red: #b63a2b;
            --red-h: #9c3023;
            --navy: #1e4d7b;
            --text: #1a1a1a;
            --mid: #555;
            --muted: #999;
            --border: #e8ddd0;
            --r: 14px;
            --topbar-h: 60px;
            --serif: 'Playfair Display', Georgia, serif;
            --sans: 'DM Sans', sans-serif;
        }

        html,
        body {
            height: 100%;
            background: var(--bg);
            font-family: var(--sans);
            color: var(--text);
            font-size: 15px;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* SIDEBAR */
        .sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            width: var(--sidebar-w);
            background: #fff;
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            z-index: 200;
            overflow-y: auto;
        }

        .s-logo {
            display: flex;
            align-items: center;
            gap: .9rem;
            padding: 1.5rem 1.35rem 1.35rem;
            border-bottom: 1px solid var(--border);
        }

        .s-logo-icon {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            background: var(--red);
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 14px rgba(182, 58, 43, .3);
        }

        .s-logo-icon svg {
            width: 22px;
            height: 22px;
            stroke: #fff;
            stroke-width: 1.6;
        }

        .s-logo-text strong {
            display: block;
            font-family: var(--serif);
            font-size: 1.05rem;
            font-weight: 600;
            line-height: 1.2;
        }

        .s-logo-text span {
            font-size: .71rem;
            color: var(--muted);
        }

        .s-nav {
            padding: 1rem .8rem 0;
        }

        .s-item {
            display: flex;
            align-items: center;
            gap: .8rem;
            padding: .62rem 1rem;
            border-radius: 10px;
            font-size: .88rem;
            font-weight: 500;
            color: var(--mid);
            transition: background .13s, color .13s;
            margin-bottom: 2px;
            cursor: pointer;
        }

        .s-item svg {
            width: 18px;
            height: 18px;
            stroke: var(--muted);
            stroke-width: 1.5;
            flex-shrink: 0;
        }

        .s-item:hover {
            background: var(--bg);
            color: var(--text);
        }

        .s-item:hover svg {
            stroke: var(--text);
        }

        .s-item.active {
            background: var(--navy);
            color: #fff;
        }

        .s-item.active svg {
            stroke: #fff;
        }

        .s-section {
            padding: 1.35rem 1.4rem .4rem;
            font-size: .62rem;
            font-weight: 700;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: var(--muted);
        }

        .spacer {
            flex: 1;
            min-height: 1rem;
        }

        .s-new-btn {
            margin: 0 .8rem 1.3rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .55rem;
            background: var(--red);
            color: #fff;
            border: none;
            padding: .92rem 1rem;
            border-radius: 10px;
            font-family: var(--sans);
            font-size: .9rem;
            font-weight: 600;
            cursor: pointer;
            width: calc(100% - 1.6rem);
            box-shadow: 0 4px 16px rgba(182, 58, 43, .3);
            transition: background .13s, transform .12s, box-shadow .13s;
        }

        .s-new-btn svg {
            width: 17px;
            height: 17px;
            stroke: #fff;
            stroke-width: 2.4;
        }

        .s-new-btn:hover {
            background: var(--red-h);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(182, 58, 43, .38);
        }

        /* TOPBAR */
        .topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-w);
            right: 0;
            height: var(--topbar-h);
            background: #fff;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding: 0 2.5rem;
            gap: 1.25rem;
            z-index: 100;
        }

        .t-name {
            font-size: .83rem;
            font-weight: 600;
            color: var(--mid);
        }

        .t-sep {
            width: 1px;
            height: 18px;
            background: var(--border);
        }

        .topbar a {
            font-size: .82rem;
            font-weight: 500;
            color: var(--mid);
            padding: .3rem .65rem;
            border-radius: 8px;
            transition: background .13s, color .13s;
        }

        .topbar a:hover {
            background: var(--bg);
            color: var(--text);
        }

        .topbar a.red {
            color: var(--red);
        }

        /* MAIN */
        main {
            margin-left: var(--sidebar-w);
            padding: calc(var(--topbar-h) + 1.75rem) 2.75rem 3.5rem;
            min-height: 100vh;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            font-size: .88rem;
            font-weight: 500;
            color: var(--mid);
            margin-bottom: 1.6rem;
            transition: color .13s;
        }

        .back-link:hover {
            color: var(--text);
        }

        .back-link svg {
            width: 17px;
            height: 17px;
            stroke: currentColor;
            stroke-width: 2.1;
        }

        h1.pg-title {
            font-family: var(--serif);
            font-size: 2.65rem;
            font-weight: 700;
            color: var(--text);
            line-height: 1.15;
            margin-bottom: .25rem;
        }

        .pg-sub {
            font-size: .9rem;
            color: var(--muted);
            margin-bottom: 1.9rem;
        }

        /* Search */
        .search-row {
            display: flex;
            gap: .7rem;
            max-width: 620px;
            margin-bottom: 1.6rem;
        }

        .search-wrap {
            position: relative;
            flex: 1;
        }

        .search-wrap svg {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            width: 16px;
            height: 16px;
            stroke: #bbb;
            stroke-width: 2;
            pointer-events: none;
        }

        .search-input {
            width: 100%;
            padding: .78rem 1rem .78rem 2.65rem;
            border: 1.5px solid var(--border);
            border-radius: 999px;
            background: #fff;
            font-family: var(--sans);
            font-size: .9rem;
            color: var(--text);
            outline: none;
            box-shadow: 0 1px 5px rgba(0, 0, 0, .05);
            transition: border-color .13s, box-shadow .13s;
        }

        .search-input:focus {
            border-color: var(--red);
            box-shadow: 0 0 0 3px rgba(182, 58, 43, .08);
        }

        .search-input::placeholder {
            color: #bbb;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .4rem;
            padding: .58rem 1.15rem;
            border-radius: 9px;
            font-family: var(--sans);
            font-size: .84rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: background .13s, transform .1s;
            line-height: 1;
            white-space: nowrap;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .btn-primary {
            background: var(--red);
            color: #fff;
            box-shadow: 0 3px 10px rgba(182, 58, 43, .22);
        }

        .btn-primary:hover {
            background: var(--red-h);
        }

        .btn-secondary {
            background: #efe8df;
            color: var(--text);
        }

        .btn-secondary:hover {
            background: #e4dbd0;
        }

        .btn-ghost {
            background: transparent;
            color: var(--mid);
            border: 1.5px solid var(--border);
        }

        .btn-ghost:hover {
            background: var(--bg);
            border-color: #ccc;
        }

        .btn-danger {
            background: var(--red);
            color: #fff;
        }

        .btn-danger:hover {
            background: var(--red-h);
        }

        .btn-sm {
            padding: .36rem .82rem;
            font-size: .78rem;
            border-radius: 7px;
        }

        /* Category pills */
        .pills {
            display: flex;
            gap: .5rem;
            flex-wrap: wrap;
            margin-bottom: 2.1rem;
        }

        .pill {
            padding: .4rem 1.1rem;
            border-radius: 999px;
            border: 1.5px solid var(--border);
            background: #fff;
            color: var(--mid);
            font-size: .81rem;
            font-weight: 500;
            cursor: pointer;
            transition: all .13s;
        }

        .pill:hover {
            border-color: var(--red);
            color: var(--red);
        }

        .pill.active {
            background: var(--red);
            border-color: var(--red);
            color: #fff;
            box-shadow: 0 3px 10px rgba(182, 58, 43, .22);
        }

        /* GRID */
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(290px, 1fr));
            gap: 1.85rem;
        }

        /* CARD */
        .card {
            background: #fff;
            border-radius: var(--r);
            overflow: hidden;
            border: 1px solid var(--border);
            box-shadow: 0 2px 10px rgba(0, 0, 0, .06);
            display: flex;
            flex-direction: column;
            transition: transform .22s, box-shadow .22s;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 40px rgba(0, 0, 0, .13);
        }

        .card-img {
            position: relative;
            height: 218px;
            overflow: hidden;
            background: #ddd;
        }

        .card-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform .35s ease;
        }

        .card:hover .card-img img {
            transform: scale(1.04);
        }

        .card-cat {
            position: absolute;
            bottom: .75rem;
            left: .75rem;
            z-index: 2;
            background: rgba(255, 255, 255, .88);
            backdrop-filter: blur(8px) saturate(1.4);
            color: var(--text);
            font-size: .68rem;
            font-weight: 700;
            padding: .22rem .65rem;
            border-radius: 999px;
            letter-spacing: .04em;
            text-transform: uppercase;
            box-shadow: 0 1px 6px rgba(0, 0, 0, .1);
            border: 1px solid rgba(255, 255, 255, .5);
        }

        .card-fav {
            position: absolute;
            top: .85rem;
            right: .85rem;
            z-index: 2;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .15);
            border: none;
            cursor: pointer;
            padding: 0;
            text-decoration: none;
            transition: transform .15s, box-shadow .15s;
        }

        .card-fav:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 14px rgba(0, 0, 0, .2);
        }

        .card-fav svg {
            width: 18px;
            height: 18px;
            stroke-width: 1.7;
        }

        .card-fav.on svg {
            fill: var(--red);
            stroke: var(--red);
        }

        .card-fav:not(.on) svg {
            stroke: #c0c0c0;
            fill: none;
        }

        .card-body {
            padding: 1.2rem 1.3rem .5rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .card-title {
            font-family: var(--serif);
            font-size: 1.22rem;
            font-weight: 700;
            color: var(--text);
            line-height: 1.35;
            margin-bottom: .75rem;
        }

        .card-meta {
            display: flex;
            gap: 1.6rem;
            margin-bottom: .55rem;
            font-size: .82rem;
            color: var(--muted);
            font-weight: 500;
        }

        .card-meta span {
            display: inline-flex;
            align-items: center;
            gap: .38rem;
        }

        .card-meta svg {
            width: 15px;
            height: 15px;
            stroke: var(--muted);
            stroke-width: 1.7;
            flex-shrink: 0;
        }

        .card-desc {
            font-size: .82rem;
            color: #999;
            line-height: 1.55;
            flex: 1;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .card-footer {
            display: flex;
            align-items: center;
            gap: .5rem;
            padding: .82rem 1.3rem;
            border-top: 1px solid var(--border);
            margin-top: .75rem;
        }

        /* Empty */
        .empty {
            text-align: center;
            padding: 5rem 2rem;
            background: #fff;
            border-radius: 20px;
            border: 1.5px dashed var(--border);
        }

        .empty-icon {
            font-size: 3.5rem;
            margin-bottom: 1rem;
        }

        .empty-text {
            color: var(--muted);
            font-size: .93rem;
        }

        /* MODALS */
        .overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .42);
            backdrop-filter: blur(4px);
            z-index: 300;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .overlay.open {
            display: flex;
        }

        .modal {
            background: #fff;
            border-radius: 20px;
            width: 100%;
            max-width: 540px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 28px 70px rgba(0, 0, 0, .2);
            animation: pop .22s cubic-bezier(.34, 1.56, .64, 1);
        }

        @keyframes pop {
            from {
                opacity: 0;
                transform: translateY(14px) scale(.97);
            }

            to {
                opacity: 1;
                transform: none;
            }
        }

        .modal-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.6rem 1.65rem 0;
        }

        .modal-head h2 {
            font-family: var(--serif);
            font-size: 1.3rem;
            font-weight: 700;
        }

        .modal-x {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #f3ede6;
            border: none;
            font-size: 1.2rem;
            color: var(--mid);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background .13s;
            line-height: 1;
        }

        .modal-x:hover {
            background: #e6ddd4;
        }

        .modal-body {
            padding: 1.3rem 1.65rem;
        }

        .modal-foot {
            display: flex;
            justify-content: flex-end;
            gap: .7rem;
            padding: 0 1.65rem 1.65rem;
        }

        .fg {
            margin-bottom: 1.05rem;
        }

        .fl {
            display: block;
            font-size: .72rem;
            font-weight: 700;
            color: var(--mid);
            margin-bottom: .32rem;
            text-transform: uppercase;
            letter-spacing: .07em;
        }

        .fi,
        .fsel,
        .fta {
            width: 100%;
            padding: .7rem .95rem;
            border: 1.5px solid var(--border);
            border-radius: 9px;
            background: #faf7f3;
            font-family: var(--sans);
            font-size: .88rem;
            color: var(--text);
            outline: none;
            transition: border-color .13s, box-shadow .13s;
        }

        .fi:focus,
        .fsel:focus,
        .fta:focus {
            border-color: var(--red);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(182, 58, 43, .08);
        }

        .fsel {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%23888' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right .9rem center;
            padding-right: 2.3rem;
            cursor: pointer;
        }

        .fta {
            resize: vertical;
            min-height: 88px;
            line-height: 1.55;
        }

        .frow {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        @media (max-width:780px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .topbar {
                left: 0;
            }

            main {
                margin-left: 0;
                padding-left: 1.2rem;
                padding-right: 1.2rem;
            }

            .grid {
                grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            }
        }
    </style>
</head>

<body>

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="s-logo">
            <div class="s-logo-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M6 13.87A4 4 0 0 1 7.41 6.64a2 2 0 0 1 3.09-2.5 2 2 0 0 1 3.09 2.5A4 4 0 0 1 18 13.87" />
                    <line x1="6" y1="17" x2="18" y2="17" />
                    <line x1="6" y1="13.87" x2="18" y2="13.87" />
                </svg>
            </div>
            <div class="s-logo-text">
                <strong>Matbakhi</strong>
                <span>Marrakech Food Lovers</span>
            </div>
        </div>

        <nav class="s-nav">
            <a href="dashboard.php" class="s-item active">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                    <polyline points="9 22 9 12 15 12 15 22" />
                </svg>
                Toutes les Recettes
            </a>
            <a href="favorites.php" class="s-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path
                        d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                </svg>
                Mes Favoris
            </a>
        </nav>

        <p class="s-section">Catégories</p>
        <nav class="s-nav">
            <?php
            function getCategoryIcon($n)
            {
                $n = strtolower(trim($n));
                if (str_contains($n, 'entr'))
                    return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg>';
                if (str_contains($n, 'plat'))
                    return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"><path d="M8 6h8a2 2 0 0 1 2 2v8a4 4 0 0 1-4 4h-4a4 4 0 0 1-4-4V8a2 2 0 0 1 2-2z"/><path d="M12 6v2"/><line x1="6" y1="14" x2="18" y2="14"/></svg>';
                if (str_contains($n, 'dessert'))
                    return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>';
                if (str_contains($n, 'boisson'))
                    return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"><path d="M6 4h12v2c0 1-1 3-3 5v6c0 1-.5 2-1.5 2h-3c-1 0-1.5-1-1.5-2v-6C8 9 7 7 7 6V4z"/><line x1="9" y1="21" x2="15" y2="21"/></svg>';
                return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/></svg>';
            }
            ?>
            <?php foreach ($categories as $cat): ?>
                <a href="dashboard.php?category=<?= $cat['id'] ?>"
                    class="s-item <?= $filter_category == $cat['id'] ? 'active' : '' ?>">
                    <?= getCategoryIcon($cat['name']) ?>
                    <?= htmlspecialchars($cat['name']) ?>
                </a>
            <?php endforeach; ?>
        </nav>

        <div class="spacer"></div>

        <button class="s-new-btn" onclick="openModal('add')">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19" />
                <line x1="5" y1="12" x2="19" y2="12" />
            </svg>
            Nouvelle Recette
        </button>
    </aside>

    <!-- TOPBAR -->
    <nav class="topbar">
        <span class="t-name"><?= htmlspecialchars($user_name) ?></span>
        <div class="t-sep"></div>
        <a href="favorites.php" class="red">❤️ Mes Favoris</a>
        <a href="home.php">Home</a>
        <a href="../../controllers/AuthController.php?action=logout">Déconnexion</a>
    </nav>

    <!-- MAIN -->
    <main>
        <a href="home.php" class="back-link">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12" />
                <polyline points="12 19 5 12 12 5" />
            </svg>
            Retour
        </a>

        <h1 class="pg-title">Toutes les Recettes</h1>
        <p class="pg-sub"><?= count($recipes) ?> recette<?= count($recipes) !== 1 ? 's' : '' ?>
            trouvée<?= count($recipes) !== 1 ? 's' : '' ?></p>

        <!-- Search -->
        <form method="GET" action="dashboard.php" class="search-row">
            <?php if ($filter_category): ?>
                <input type="hidden" name="category" value="<?= htmlspecialchars($filter_category) ?>">
            <?php endif; ?>
            <div class="search-wrap">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round"
                    stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8" />
                    <line x1="21" y1="21" x2="16.65" y2="16.65" />
                </svg>
                <input type="text" name="search" class="search-input"
                    placeholder="Rechercher par titre ou ingrédient..." value="<?= htmlspecialchars($search ?? '') ?>">
            </div>
            <button type="submit" class="btn btn-primary">Rechercher</button>
            <?php if ($search): ?>
                <a href="dashboard.php<?= $filter_category ? '?category=' . $filter_category : '' ?>"
                    class="btn btn-ghost">Effacer</a>
            <?php endif; ?>
        </form>

        <!-- Category pills -->
        <div class="pills">
            <a href="dashboard.php" class="pill <?= !$filter_category ? 'active' : '' ?>">Toutes</a>
            <?php foreach ($categories as $cat): ?>
                <a href="dashboard.php?category=<?= $cat['id'] ?>"
                    class="pill <?= $filter_category == $cat['id'] ? 'active' : '' ?>">
                    <?= htmlspecialchars($cat['name']) ?>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- Grid -->
        <?php if (empty($recipes)): ?>
            <div class="empty">
                <div class="empty-icon">🍽️</div>
                <p class="empty-text">Aucune recette trouvée. Ajoutez votre première recette!</p>
            </div>
        <?php else: ?>
            <div class="grid">
                <?php foreach ($recipes as $i => $recipe): ?>
                    <?php
                    $photo = !empty($recipe['image'])
                        ? '../../uploads/' . htmlspecialchars($recipe['image'])
                        : $food_photos[$recipe['id'] % count($food_photos)];
                    ?>
                    <div class="card">
                        <div class="card-img">
                            <img src="<?= $photo ?>" alt="<?= htmlspecialchars($recipe['title']) ?>" loading="lazy">
                            <span class="card-cat"><?= htmlspecialchars($recipe['category_name']) ?></span>
                            <a href="../../controllers/RecipeController.php?action=toggleFavorite&id=<?= $recipe['id'] ?>"
                                class="card-fav <?= $favoriteModel->isFavorite($_SESSION['user_id'], $recipe['id']) ? 'on' : '' ?>">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path
                                        d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                                </svg>
                            </a>
                        </div>
                        <div class="card-body">
                            <h3 class="card-title"><?= htmlspecialchars($recipe['title']) ?></h3>
                            <div class="card-meta">
                                <span>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10" />
                                        <polyline points="12 6 12 12 16 14" />
                                    </svg>
                                    <?= htmlspecialchars($recipe['temp_de_production']) ?> min
                                </span>
                                <span>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                        <line x1="16" y1="2" x2="16" y2="6" />
                                        <line x1="8" y1="2" x2="8" y2="6" />
                                        <line x1="3" y1="10" x2="21" y2="10" />
                                    </svg>
                                    <?= htmlspecialchars($recipe['portions']) ?>
                                </span>
                            </div>
                            <p class="card-desc"><?= htmlspecialchars($recipe['ingredient'] ?? '') ?></p>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-secondary btn-sm"
                                onclick="openModal('edit', <?= htmlspecialchars(json_encode($recipe)) ?>)">Modifier</button>
                            <button class="btn btn-ghost btn-sm"
                                onclick="openModal('delete', <?= $recipe['id'] ?>)">Supprimer</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <!-- MODAL: ADD / EDIT -->
    <div class="overlay" id="recipeModal">
        <div class="modal">
            <div class="modal-head">
                <h2 id="modalTitle">Nouvelle Recette</h2>
                <button class="modal-x" onclick="closeModal()">&times;</button>
            </div>
            <form action="../../controllers/RecipeController.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" id="recipeId">
                <div class="modal-body">
                    <div class="fg"><label class="fl">Titre</label>
                        <input type="text" id="title" name="title" class="fi" required>
                    </div>
                    <div class="fg"><label class="fl">Catégorie</label>
                        <select id="category" name="category_id" class="fsel" required>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="frow">
                        <div class="fg"><label class="fl">Temps (min)</label>
                            <input type="number" id="prep_time" name="prep_time" class="fi" min="1" required>
                        </div>
                        <div class="fg"><label class="fl">Portions</label>
                            <input type="number" id="portions" name="portions" class="fi" min="1" required>
                        </div>
                    </div>
                    <div class="fg"><label class="fl">Image de la recette</label>
                        <input type="file" name="image" id="imageInput" class="fi" accept="image/*">
                        <img id="preview" style="width:100%;margin-top:10px;display:none;border-radius:10px;">
                    </div>
                    <div class="fg"><label class="fl">Ingrédients</label>
                        <textarea id="ingredients" name="ingredients" class="fta"
                            placeholder="Un ingrédient par ligne"></textarea>
                    </div>
                    <div class="fg"><label class="fl">Instructions</label>
                        <textarea id="instructions" name="instructions" class="fta"
                            placeholder="Les étapes de préparation"></textarea>
                    </div>
                </div>
                <div class="modal-foot">
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: DELETE -->
    <div class="overlay" id="deleteModal">
        <div class="modal" style="max-width:420px">
            <div class="modal-head">
                <h2>Supprimer la recette</h2>
                <button class="modal-x" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body">
                <p style="color:var(--mid);font-size:.92rem;line-height:1.65">
                    Êtes-vous sûr de vouloir supprimer cette recette ? Cette action est irréversible.
                </p>
            </div>
            <div class="modal-foot">
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Annuler</button>
                <form action="../../controllers/RecipeController.php" method="POST" style="display:inline">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" id="deleteRecipeId">
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openModal(type, data = null) {
            if (type === 'delete') {
                document.getElementById('deleteRecipeId').value = data;
                document.getElementById('deleteModal').classList.add('open');
            } else {
                if (type === 'edit' && data) {
                    document.getElementById('modalTitle').textContent = 'Modifier la Recette';
                    document.getElementById('recipeId').value = data.id;
                    document.getElementById('title').value = data.title;
                    document.getElementById('category').value = data.categories_id;
                    document.getElementById('prep_time').value = data.temp_de_production;
                    document.getElementById('portions').value = data.portions;
                    document.getElementById('ingredients').value = data.ingredient || '';
                    document.getElementById('instructions').value = data.instructions || '';
                    document.getElementById('preview').style.display = 'none';
                } else {
                    document.getElementById('modalTitle').textContent = 'Nouvelle Recette';
                    document.getElementById('recipeId').value = '';
                    document.querySelector('#recipeModal form').reset();
                    document.getElementById('preview').style.display = 'none';
                }
                document.getElementById('recipeModal').classList.add('open');
            }
        }
        function closeModal() {
            document.querySelectorAll('.overlay').forEach(o => o.classList.remove('open'));
        }
        document.querySelectorAll('.overlay').forEach(o => {
            o.addEventListener('click', e => { if (e.target === o) closeModal(); });
        });
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

        // Image preview
        document.querySelector('input[name="image"]').addEventListener('change', function (e) {
            const file = e.target.files[0];
            const preview = document.getElementById('preview');
            if (file) {
                preview.src = URL.createObjectURL(file);
                preview.style.display = 'block';
            }
        });
    </script>
</body>

</html>