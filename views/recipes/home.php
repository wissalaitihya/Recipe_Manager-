<?php
require_once __DIR__ . '/../../models/category.php';
require_once __DIR__ . '/../../models/recipe.php';

session_start();
$is_logged_in = isset($_SESSION['user_id']);

$categoryModel = new Category_model();
$categories = $categoryModel->getCategories();

$recipeModel = new Recette_model();

// Filter by category if set
$filter_category = isset($_GET['category']) ? (int)$_GET['category'] : null;
$filtered_recipes = $recipeModel->getAllRecipes($filter_category);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Marrakech Food Lovers - Restaurant & Recipes</title>
  
  <!-- Add elegant Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
  
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

    h1, h2, h3, h4, h5, .logo {
      font-family: 'Playfair Display', serif;
    }

    /* Public Header */
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
      box-shadow: 0 2px 20px rgba(0,0,0,0.05);
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
    }
    
    .btn-gold:hover {
      background-color: #bfa140;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(214, 168, 72, 0.3);
    }
    
    /* Hero Section */
    .hero {
      position: relative;
      text-align: center;
      padding: 8rem 2rem;
      background-image: linear-gradient(rgba(47, 36, 31, 0.7), rgba(47, 36, 31, 0.7)), url('https://images.unsplash.com/photo-1514933651103-005eec06c04b?auto=format&fit=crop&q=80&w=1920&h=1080');
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
      color: #fff;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }
    
    .hero-title {
      font-size: 3.5rem;
      font-weight: 700;
      margin-bottom: 1rem;
      color: var(--gold);
      text-shadow: 0 4px 12px rgba(0,0,0,0.3);
    }
    
    .hero-subtitle {
      font-size: 1.2rem;
      max-width: 600px;
      margin: 0 auto 2rem auto;
      font-weight: 300;
      line-height: 1.6;
    }

    /* Filters */
    .category-filter {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 1rem;
      margin: 3rem 0;
    }

    .category-btn {
      padding: 0.5rem 1.5rem;
      background: #fff;
      border: 1px solid #ddd;
      border-radius: 30px;
      text-decoration: none;
      color: var(--text-main);
      transition: all 0.3s;
      font-weight: 500;
    }

    .category-btn:hover {
      border-color: var(--gold);
      color: var(--gold);
    }

    .category-btn.active {
      background: var(--gold);
      color: #fff;
      border-color: var(--gold);
      box-shadow: 0 4px 10px rgba(214, 168, 72, 0.3);
    }

    /* Clickable Recipe Card */
    .recipes-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 2rem;
      padding: 0 5%;
      margin-bottom: 4rem;
    }

    .recipe-card-public {
      background: #fff;
      border-radius: 8px;
      overflow: hidden;
      cursor: pointer;
      border: 1px solid #eaeaea;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      display: flex;
      flex-direction: column;
    }
    
    .recipe-card-public:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.08);
      border-color: transparent;
    }

    .recipe-img-placeholder {
      height: 200px;
      background: var(--light-gold);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 3rem;
      border-bottom: 1px solid #f0f0f0;
    }
    
    .recipe-content {
      padding: 1.5rem;
      flex-grow: 1;
      display: flex;
      flex-direction: column;
    }

    .recipe-title {
      font-family: 'Playfair Display', serif;
      font-size: 1.4rem;
      color: var(--primary-dark);
      margin: 0 0 0.5rem 0;
    }
    
    .recipe-category {
      font-size: 0.8rem;
      text-transform: uppercase;
      letter-spacing: 1px;
      color: var(--gold);
      font-weight: 600;
      margin-bottom: 1rem;
    }
    
    .recipe-description {
      font-size: 0.9rem;
      color: var(--text-muted);
      line-height: 1.6;
      margin-bottom: 1.5rem;
      flex-grow: 1;
    }

    .recipe-meta {
      display: flex;
      justify-content: space-between;
      border-top: 1px solid #f0f0f0;
      padding-top: 1rem;
      font-size: 0.85rem;
      color: var(--primary-dark);
      font-weight: 500;
    }
    
    /* Notice info */
    .chef-name {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      margin-top: 1rem;
      font-size: 0.85rem;
      color: var(--text-muted);
      font-style: italic;
    }

    /* Modal Styling Adjustments */
    .modal-overlay {
      z-index: 999 !important; /* Ensure it covers the z-index:100 header */
      padding-top: 2rem; /* Add top padding to prevent hugging top of screen */
    }

    .modal {
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 25px 50px rgba(0,0,0,0.15);
      /* Fix height and allow body scrolling so header is always visible */
      display: flex;
      flex-direction: column;
      max-height: 85vh;
      margin: auto;
    }
    
    .modal-body {
      overflow-y: auto;
    }
    
    .modal-header, .modal-footer {
      flex-shrink: 0;
    }
    
    .modal-header {
      background: var(--light-gold);
      padding: 1.5rem;
      border-bottom: 1px solid #f0eedf;
    }

    .modal-title {
      font-family: 'Playfair Display', serif;
      color: var(--primary-dark);
      font-size: 1.8rem;
    }

    .recipe-detail-meta {
      display: flex;
      gap: 1rem;
      justify-content: space-around;
      padding: 1.5rem;
      background: #FAFAFA;
      border-radius: 8px;
      margin-bottom: 2rem;
      border: 1px solid #eaeaea;
    }

    .recipe-detail-meta-item {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }

    .recipe-detail-meta-value {
      font-family: 'Playfair Display', serif;
      font-size: 1.5rem;
      color: var(--gold);
    }

    .recipe-detail-title {
      font-family: 'Playfair Display', serif;
      font-size: 1.3rem;
      color: var(--primary-dark);
      margin-bottom: 0.8rem;
      border-bottom: 2px solid var(--light-gold);
      padding-bottom: 0.5rem;
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
      
      .hero {
        padding: 4rem 1rem;
      }
      
      .hero-title {
        font-size: 2.2rem;
      }
      
      .hero-subtitle {
        font-size: 1rem;
      }

      .recipes-grid {
        grid-template-columns: 1fr;
        padding: 0 1rem;
        gap: 1.5rem;
      }
      
      .recipe-detail-meta {
        flex-direction: column;
        align-items: stretch;
      }
      
      .recipe-detail-meta-item {
        padding: 0.5rem 0;
        border-bottom: 1px solid #eaeaea;
      }
      
      .recipe-detail-meta-item:last-child {
        border-bottom: none;
      }
      
      .modal-body {
        padding: 1.5rem !important;
      }
      
      .modal-title {
        font-size: 1.4rem;
      }
    }
  </style>
</head>
<body>

  <!-- Header -->
  <header class="header-public">
    <a href="home.php" class="logo">Marrakech Food Lovers</a>
    <nav class="header-nav">
      <?php if ($is_logged_in): ?>
        <a href="dashboard.php" class="btn-gold">Mon Espace</a>
      <?php else: ?>
        <a href="../auth/login.php" class="btn-gold">Connexion</a>
        <a href="../auth/register.php" style="color: var(--primary-dark); font-weight: 500; text-decoration: none;">Inscription</a>
      <?php endif; ?>
    </nav>
  </header>

  <!-- Hero Section -->
  <section class="hero">
    <h1 class="hero-title">L'Art Culinaire Marocain</h1>
    <p class="hero-subtitle">Plongez dans les saveurs authentiques du Maroc. Découvrez les meilleures recettes traditionnelles partagées par notre talentueuse communauté de chefs.</p>
    <a href="#menu" class="btn-gold" style="font-size: 1.1rem; padding: 0.8rem 2rem;">Découvrir Le Menu</a>
  </section>

  <!-- Main Content -->
  <main id="menu">
    <!-- Category Filter -->
    <div class="category-filter">
      <a href="home.php#menu" class="category-btn <?php echo !$filter_category ? 'active' : ''; ?>">
        Tous les Plats
      </a>
      <?php foreach ($categories as $cat): ?>
        <a 
          href="home.php?category=<?php echo $cat['id']; ?>#menu" 
          class="category-btn <?php echo $filter_category === $cat['id'] ? 'active' : ''; ?>"
        >
          <?php echo htmlspecialchars($cat['name']); ?>
        </a>
      <?php endforeach; ?>
    </div>

    <!-- Recipes Grid -->
    <?php if (empty($filtered_recipes)): ?>
      <div class="empty-state" style="text-align: center; padding: 4rem 2rem;">
        <div style="font-size: 4rem; color: var(--gold); margin-bottom: 1rem;">&#127858;</div>
        <p style="font-size: 1.2rem; color: var(--text-muted); font-family: 'Playfair Display', serif;">Aucune recette dans ce menu pour le moment.</p>
      </div>
    <?php else: ?>
      <div class="recipes-grid">
        <?php foreach ($filtered_recipes as $recipe): ?>
          <div class="recipe-card-public" onclick='openRecipeDetail(<?php echo json_encode($recipe, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>)'>
            <div class="recipe-img-placeholder">
               <?php 
                  // Fun static emoji map based on category words if available
                  $emoji = '🍲';
                  if ($recipe['category_name'] === 'Desserts') $emoji = '🧁';
                  if ($recipe['category_name'] === 'Boissons') $emoji = '🍵';
                  if ($recipe['category_name'] === 'Entrées') $emoji = '🥗';
                  echo $emoji;
               ?>
            </div>
            <div class="recipe-content">
              <span class="recipe-category"><?php echo htmlspecialchars($recipe['category_name']); ?></span>
              <h3 class="recipe-title"><?php echo htmlspecialchars($recipe['title']); ?></h3>
              
              <p class="recipe-description">
                <?php echo htmlspecialchars(substr($recipe['ingredient'] ?? 'Recette secrète du chef...', 0, 80)) . '...'; ?>
              </p>
              
              <div class="recipe-meta">
                <span>⏱ <?php echo htmlspecialchars($recipe['temp_de_production'] ?? ''); ?></span>
                <span>🍽 <?php echo htmlspecialchars($recipe['portions'] ?? ''); ?> portions</span>
              </div>
              
              <?php if (!empty($recipe['chef_name'])): ?>
              <div class="chef-name">
                <span style="font-size: 1.2rem;">👨‍🍳</span> Par le chef <?php echo htmlspecialchars($recipe['chef_name']); ?>
              </div>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </main>

  <!-- Footer -->
  <footer class="footer">
    <h2 style="margin-top: 0; color: var(--gold); font-size: 2rem;">Marrakech Food Lovers</h2>
    <p style="opacity: 0.7; font-size: 0.9rem; max-width: 400px; margin: 1rem auto;">L'expérience culinaire marocaine authentique.</p>
    <div style="margin-top: 2rem; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 2rem; opacity: 0.5;">
      &copy; <?php echo date('Y'); ?> Marrakech Food Lovers. Tous droits réservés.
    </div>
  </footer>

  <!-- Recipe Detail Modal -->
  <div class="modal-overlay" id="recipeDetailModal">
    <div class="modal" style="max-width: 650px;">
      <div class="modal-header">
        <h2 class="modal-title" id="detailTitle">Titre de la Recette</h2>
        <button class="modal-close" onclick="closeModal()" style="font-size: 1.5rem; background: none; border: none; cursor: pointer;">&times;</button>
      </div>
      <div class="modal-body" style="padding: 2rem;">
        <div class="recipe-detail-meta">
          <div class="recipe-detail-meta-item">
            <div class="recipe-detail-meta-value" id="detailTime">45</div>
            <div class="recipe-detail-meta-label" style="text-transform: uppercase; font-size: 0.8rem; color: var(--text-muted); letter-spacing: 1px;">Temps</div>
          </div>
          <div class="recipe-detail-meta-item">
            <div class="recipe-detail-meta-value" id="detailPortions">4</div>
            <div class="recipe-detail-meta-label" style="text-transform: uppercase; font-size: 0.8rem; color: var(--text-muted); letter-spacing: 1px;">Portions</div>
          </div>
          <div class="recipe-detail-meta-item">
            <div class="recipe-detail-meta-value" id="detailCategory">Plats</div>
            <div class="recipe-detail-meta-label" style="text-transform: uppercase; font-size: 0.8rem; color: var(--text-muted); letter-spacing: 1px;">Catégorie</div>
          </div>
        </div>
        
        <div class="recipe-detail-section" style="margin-bottom: 2rem;">
          <h3 class="recipe-detail-title">Ingrédients</h3>
          <p class="recipe-detail-content" id="detailIngredients" style="line-height: 1.8; color: var(--text-main); white-space: pre-line;"></p>
        </div>
        
        <div class="recipe-detail-section">
          <h3 class="recipe-detail-title">Instructions de préparation</h3>
          <p class="recipe-detail-content" id="detailInstructions" style="line-height: 1.8; color: var(--text-main); white-space: pre-line;"></p>
        </div>
        
        <div id="detailChef" style="margin-top: 2rem; font-style: italic; color: var(--gold); font-family: 'Playfair Display', serif; text-align: right;"></div>
      </div>
      <div class="modal-footer" style="padding: 1.5rem; background: #FAFAFA; border-top: 1px solid #eaeaea; text-align: right;">
        <button class="btn-gold" onclick="closeModal()">Bon Appétit !</button>
      </div>
    </div>
  </div>

  <script>
    function openRecipeDetail(recipe) {
      document.getElementById('detailTitle').textContent = recipe.title;
      document.getElementById('detailTime').textContent = recipe.temp_de_production || 'N/A';
      document.getElementById('detailPortions').textContent = recipe.portions || 'N/A';
      document.getElementById('detailCategory').textContent = recipe.category_name;
      
      document.getElementById('detailIngredients').textContent = recipe.ingredient || 'Ingrédients non spécifiés';
      document.getElementById('detailInstructions').textContent = recipe.instructions || 'Instructions non spécifiées';
      
      const chefElem = document.getElementById('detailChef');
      if (recipe.chef_name) {
          chefElem.textContent = "— Proposée par le chef " + recipe.chef_name;
      } else {
          chefElem.textContent = "";
      }
      
      document.getElementById('recipeDetailModal').classList.add('active');
    }

    function closeModal() {
      document.getElementById('recipeDetailModal').classList.remove('active');
    }

    // Close modal on overlay click
    document.getElementById('recipeDetailModal').addEventListener('click', (e) => {
      if (e.target.id === 'recipeDetailModal') closeModal();
    });

    // Close modal on Escape key
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') closeModal();
    });
  </script>
</body>
</html>
