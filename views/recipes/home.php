<?php


require_once __DIR__ . '/../../models/category.php';
require_once __DIR__ . '/../../models/recipe.php';

session_start();
$is_logged_in = isset($_SESSION['user_id']);

$categoryModel = new Category_model();
$categories = $categoryModel->getCategories();

$recipeModel = new Recette_model();

// Filter by category if set
$filter_category = isset($_GET['category']) ? (int) $_GET['category'] : null;
$filtered_recipes = $recipeModel->getAllRecipes($filter_category);

?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Matbakhi</title>
  <link
    href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=DM+Sans:wght@300;400;500&display=swap"
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
      --teal: #2a5f6e;
      --text: #2a1f1a;
      --muted: #7a6a60;
      --border: #e0d4c5;
      --radius: 12px;
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
      font-size: 18px;
      color: #fff;
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

    .btn-outline {
      background: transparent;
      color: var(--teal);
      border: 2px solid var(--teal);
      padding: 9px 22px;
      border-radius: 6px;
      font-size: 14px;
      font-weight: 500;
      cursor: pointer;
      text-decoration: none;
      transition: background .2s, color .2s;
    }

    .btn-outline:hover {
      background: var(--teal);
      color: #fff;
    }

    /* ── HERO ── */
    .hero-wrapper {
      background-color: #faf6f0;
      background-image:
        linear-gradient(var(--cross-color, #dfd5c8) 1.5px, transparent 1.5px),
        linear-gradient(90deg, var(--cross-color, #dfd5c8) 1.5px, transparent 1.5px);
      background-size: 32px 32px;
      background-position: center center;
      --cross-color: #e2d5c9;
      position: relative;
    }

    .hero-wrapper::before {
      content: '';
      position: absolute;
      inset: 0;
      background:
        radial-gradient(ellipse 70% 60% at 50% 50%, transparent 40%, #faf6f0 100%);
      pointer-events: none;
      z-index: 0;
    }

    .hero {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 60px;
      align-items: center;
      padding: 80px 6% 70px;
      max-width: 1300px;
      margin: 0 auto;
      position: relative;
      z-index: 1;
    }

    .hero-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: #dff0f5;
      color: var(--teal);
      padding: 6px 14px;
      border-radius: 999px;
      font-size: 13px;
      font-weight: 500;
      margin-bottom: 20px;
    }

    .hero h1 {
      font-family: 'Cormorant Garamond', serif;
      font-size: clamp(40px, 5vw, 62px);
      line-height: 1.1;
      font-weight: 700;
      margin-bottom: 20px;
    }

    .hero h1 span {
      color: var(--primary);
    }

    .hero p {
      color: var(--muted);
      font-size: 16px;
      max-width: 500px;
      margin-bottom: 32px;
    }

    .hero-ctas {
      display: flex;
      gap: 14px;
      flex-wrap: wrap;
      margin-bottom: 40px;
    }

    .hero-stats {
      display: flex;
      gap: 40px;
      padding-top: 24px;
      border-top: 1px solid var(--border);
    }

    .stat-num {
      font-family: 'Cormorant Garamond', serif;
      font-size: 28px;
      font-weight: 700;
      color: var(--text);
    }

    .stat-label {
      font-size: 12px;
      color: var(--muted);
    }

    .hero-image-wrap {
      position: relative;
    }

    .hero-img {
      width: 100%;
      height: 480px;
      object-fit: cover;
      border-radius: 16px;
    }

    .hero-badge-float {
      position: absolute;
      bottom: 24px;
      right: 24px;
      background: #fff;
      border-radius: 12px;
      padding: 14px 18px;
      display: flex;
      align-items: center;
      gap: 12px;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    }

    .badge-icon {
      width: 36px;
      height: 36px;
      background: #fdecea;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 16px;
    }

    .badge-count {
      font-weight: 700;
      font-size: 15px;
    }

    .badge-sub {
      font-size: 11px;
      color: var(--muted);
    }

    /* ── FEATURES ── */
    .features {
      background: #ece6db;
      padding: 80px 6%;
      text-align: center;
    }

    .features-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 40px;
      max-width: 1000px;
      margin: 0 auto;
    }

    .feature-icon {
      width: 64px;
      height: 64px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 26px;
      margin: 0 auto 16px;
    }

    .feature-icon.orange {
      background: #fde8e0;
    }

    .feature-icon.blue {
      background: #d5eaf0;
    }

    .feature-icon.peach {
      background: #fde8e0;
    }

    .feature h3 {
      font-family: 'Cormorant Garamond', serif;
      font-size: 20px;
      font-weight: 700;
      margin-bottom: 8px;
    }

    .feature p {
      font-size: 14px;
      color: var(--muted);
      max-width: 220px;
      margin: auto;
    }

    /* ── RECENT RECIPES ── */
    .recent-section {
      padding: 80px 6%;
      max-width: 1300px;
      margin: 0 auto;
    }

    .section-header {
      text-align: center;
      margin-bottom: 50px;
    }

    .section-header h2 {
      font-family: 'Cormorant Garamond', serif;
      font-size: 40px;
      font-weight: 700;
      margin-bottom: 10px;
    }

    .section-header p {
      color: var(--muted);
      font-size: 15px;
    }

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
    }

    .recipe-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 36px rgba(0, 0, 0, 0.09);
    }

    .recipe-thumb {
      width: 100%;
      height: 200px;
      object-fit: cover;
      display: block;
      background: #e0d4c5;
    }

    .recipe-thumb-placeholder {
      width: 100%;
      height: 200px;
      background: linear-gradient(135deg, #e8ddd0 0%, #d4c8b8 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 48px;
    }

    .recipe-category-badge {
      display: inline-block;
      font-size: 11px;
      color: var(--primary);
      background: #fdecea;
      padding: 3px 10px;
      border-radius: 999px;
      font-weight: 500;
      margin-bottom: 4px;
    }

    .recipe-body {
      padding: 18px 20px 20px;
    }

    .recipe-title {
      font-family: 'Cormorant Garamond', serif;
      font-size: 20px;
      font-weight: 700;
      margin-bottom: 10px;
      color: var(--text);
    }

    .recipe-meta {
      display: flex;
      justify-content: space-between;
      font-size: 13px;
      color: var(--muted);
    }

    /* ── CTA BANNER ── */
    .cta-banner {
      background: var(--primary);
      color: #fff;
      text-align: center;
      padding: 80px 6%;
    }

    .cta-banner h2 {
      font-family: 'Cormorant Garamond', serif;
      font-size: 40px;
      font-weight: 700;
      margin-bottom: 12px;
    }

    .cta-banner p {
      font-size: 15px;
      opacity: .85;
      margin-bottom: 30px;
    }

    .btn-white {
      background: #fff;
      color: var(--primary);
      border: none;
      padding: 12px 30px;
      border-radius: 8px;
      font-size: 15px;
      font-weight: 600;
      cursor: pointer;
      text-decoration: none;
      transition: opacity .2s;
    }

    .btn-white:hover {
      opacity: .9;
    }

    /* ── CATEGORY FILTER ── */
    .filter-section {
      padding: 0 6% 20px;
      max-width: 1300px;
      margin: 0 auto;
    }

    .category-filter {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      margin-bottom: 10px;
    }

    .category-btn {
      padding: 7px 18px;
      border-radius: 999px;
      border: 1px solid var(--border);
      background: #fff;
      color: var(--text);
      font-size: 13px;
      font-weight: 500;
      text-decoration: none;
      transition: all .2s;
    }

    .category-btn:hover,
    .category-btn.active {
      background: var(--primary);
      color: #fff;
      border-color: var(--primary);
    }

    /* ── FOOTER ── */
    footer {
      text-align: center;
      padding: 30px 20px;
      color: var(--muted);
      font-size: 13px;
      border-top: 1px solid var(--border);
    }

    /* ── MODAL ── */
    .modal-overlay {
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.5);
      display: none;
      justify-content: center;
      align-items: center;
      z-index: 999;
      padding: 20px;
    }

    .modal-overlay.active {
      display: flex;
    }

    .modal {
      background: #fff;
      width: 95%;
      max-width: 600px;
      border-radius: 16px;
      max-height: 90vh;
      overflow-y: auto;
    }

    .modal-header {
      padding: 22px 26px 18px;
      border-bottom: 1px solid var(--border);
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
    }

    .modal-header h2 {
      font-family: 'Cormorant Garamond', serif;
      font-size: 26px;
      font-weight: 700;
    }

    .modal-close {
      background: none;
      border: none;
      font-size: 22px;
      cursor: pointer;
      color: var(--muted);
      line-height: 1;
    }

    .modal-body {
      padding: 22px 26px;
    }

    .modal-body h3 {
      font-family: 'Cormorant Garamond', serif;
      font-size: 18px;
      margin: 16px 0 8px;
    }

    .modal-body p {
      font-size: 14px;
      color: var(--muted);
    }

    .modal-body hr {
      border: none;
      border-top: 1px solid var(--border);
      margin: 14px 0;
    }

    .modal-meta {
      display: flex;
      gap: 20px;
      font-size: 14px;
      color: var(--muted);
      margin-bottom: 8px;
    }

    /* ── RESPONSIVE ── */
    @media (max-width: 768px) {
      .hero {
        grid-template-columns: 1fr;
        gap: 40px;
      }

      .hero-image-wrap {
        order: -1;
      }

      .features-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>

<body>

  <!-- HEADER -->
  <header>
    <a href="home.php" class="logo-wrap">
      <div class="logo-icon">🍳</div>
      <span class="logo-text">Matbakhi</span>
    </a>
    <nav class="nav">
      <?php if ($is_logged_in): ?>
        <a href="dashboard.php" class="btn-primary">Mon Espace</a>
      <?php else: ?>
        <a href="../auth/login.php" class="nav-link">Se Connecter</a>
        <a href="../auth/register.php" class="btn-primary">Rejoindre</a>
      <?php endif; ?>
    </nav>
  </header>

  <!-- HERO -->
  <div class="hero-wrapper">
    <section class="hero">
      <div class="hero-content">
        <div class="hero-badge">✨ Préservez votre héritage culinaire</div>
        <h1>Partagez vos recettes <span>de famille</span></h1>
        <p>Rejoignez notre communauté de passionnés et transformez vos recettes traditionnelles en trésors numériques à
          partager avec les générations futures.</p>
        <div class="hero-ctas">
          <?php if ($is_logged_in): ?>
            <a href="dashboard.php" class="btn-primary">Mon Espace →</a>
          <?php else: ?>
            <a href="../auth/register.php" class="btn-primary">Rejoindre la Communauté →</a>
            <a href="#menu" class="btn-outline">Explorer les recettes</a>
          <?php endif; ?>
        </div>
        <div class="hero-stats">
          <div>
            <div class="stat-num">2 400+</div>
            <div class="stat-label">Recettes</div>
          </div>
          <div>
            <div class="stat-num">850+</div>
            <div class="stat-label">Familles</div>
          </div>
          <div>
            <div class="stat-num">12</div>
            <div class="stat-label">Régions</div>
          </div>
        </div>
      </div>
      <div class="hero-image-wrap">
        <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=800&q=80" alt="Plats traditionnels"
          class="hero-img" onerror="this.style.background='#e0d4c5';this.removeAttribute('src')">
        <div class="hero-badge-float">
          <div class="badge-icon">❤️</div>
          <div>
            <div class="badge-count">+150 recettes</div>
            <div class="badge-sub">ajoutées cette semaine</div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <!-- FEATURES -->
  <section class="features">
    <div class="features-grid">
      <div class="feature">
        <div class="feature-icon orange">🍽️</div>
        <h3>Organisez vos recettes</h3>
        <p>Classez vos recettes par catégorie et retrouvez-les facilement</p>
      </div>
      <div class="feature">
        <div class="feature-icon blue">🩵</div>
        <h3>Sauvegardez vos favoris</h3>
        <p>Marquez vos recettes préférées pour y accéder rapidement</p>
      </div>
      <div class="feature">
        <div class="feature-icon peach">👥</div>
        <h3>Partagez en famille</h3>
        <p>Transmettez vos recettes aux générations futures</p>
      </div>
    </div>
  </section>

  <!-- RECENT RECIPES -->
  <section class="recent-section" id="menu">
    <div class="section-header">
      <h2>Recettes Récentes</h2>
      <p>Découvrez les dernières recettes partagées par notre communauté de gourmets</p>
    </div>

    <!-- Category filter -->
    <div class="filter-section" style="padding-left:0;padding-right:0;">
      <div class="category-filter">
        <a href="home.php#menu"
          class="category-btn <?php echo !$filter_category ? 'active' : ''; ?>">Toutes</a>
        <?php foreach ($categories as $cat): ?>
          <a href="home.php?category=<?php echo $cat['id']; ?>#menu"
            class="category-btn <?php echo $filter_category === (int) $cat['id'] ? 'active' : ''; ?>">
            <?php echo htmlspecialchars($cat['name']); ?>
          </a>
        <?php endforeach; ?>
      </div>
    </div>

    <?php if (empty($filtered_recipes)): ?>
      <p style="text-align:center;color:var(--muted);padding:40px 0">Aucune recette trouvée.</p>
    <?php else: ?>
      <div class="recipes-grid">
        <?php foreach ($filtered_recipes as $recipe): ?>
          <div class="recipe-card"
            onclick='openRecipeDetail(<?php echo json_encode($recipe, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>)'>
            <div class="recipe-thumb-placeholder">🍲</div>
            <div class="recipe-body">
              <span class="recipe-category-badge"><?php echo htmlspecialchars($recipe['category_name']); ?></span>
              <div class="recipe-title"><?php echo htmlspecialchars($recipe['title']); ?></div>
              <div class="recipe-meta">
                <span>⏱ <?php echo htmlspecialchars($recipe['temp_de_production']); ?></span>
                <span>🍽 <?php echo htmlspecialchars($recipe['portions']); ?> portions</span>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </section>

  <!-- CTA BANNER -->
  <section class="cta-banner">
    <h2>Prêt à préserver vos recettes?</h2>
    <p>Rejoignez des centaines de familles qui digitalisent leur héritage culinaire</p>
    <a href="../auth/register.php" class="btn-white">Commencer gratuitement →</a>
  </section>

  <footer>
    © <?php echo date('Y'); ?> — Plateforme de partage de recettes
  </footer>

  <!-- MODAL -->
  <div class="modal-overlay" id="recipeDetailModal">
    <div class="modal">
      <div class="modal-header">
        <h2 id="detailTitle"></h2>
        <button class="modal-close" onclick="closeModal()">×</button>
      </div>
      <div class="modal-body">
        <div class="modal-meta">
          <span>⏱ <span id="detailTime"></span></span>
          <span>🍽 <span id="detailPortions"></span> portions</span>
          <span>🏷 <span id="detailCategory"></span></span>
        </div>
        <hr>
        <h3>Ingrédients</h3>
        <p id="detailIngredients"></p>
        <h3>Instructions</h3>
        <p id="detailInstructions"></p>
        <p id="detailChef" style="margin-top:16px;color:var(--primary);font-size:13px;font-weight:500;"></p>
      </div>
    </div>
  </div>

  <script>
    function openRecipeDetail(recipe) {
      document.getElementById('detailTitle').textContent = recipe.title;
      document.getElementById('detailTime').textContent = recipe.temp_de_production;
      document.getElementById('detailPortions').textContent = recipe.portions;
      document.getElementById('detailCategory').textContent = recipe.category_name;
      document.getElementById('detailIngredients').textContent = recipe.ingredient;
      document.getElementById('detailInstructions').textContent = recipe.instructions;
      document.getElementById('detailChef').textContent = recipe.chef_name ? "Proposée par " + recipe.chef_name : "";
      document.getElementById('recipeDetailModal').classList.add('active');
    }

    function closeModal() {
      document.getElementById('recipeDetailModal').classList.remove('active');
    }

    document.getElementById('recipeDetailModal').addEventListener('click', function (e) {
      if (e.target === this) closeModal();
    });
  </script>

</body>

</html>