<?php
require_once __DIR__ . '/../../models/recipe.php';
// Filter by category if set
$filter_category = isset($_GET['category']) ? (int)$_GET['category'] : null;
$filtered_recipes = $recipes;
if ($filter_category) {
    $filtered_recipes = array_filter($recipes, fn($r) => $r['category_id'] === $filter_category);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Marrakech Food Lovers - Recettes Marocaines</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    /* Hero Section */
    .hero {
      text-align: center;
      padding: 3rem 1rem;
      background: linear-gradient(to bottom, var(--secondary), var(--background));
      margin: -1rem -1rem 2rem -1rem;
    }
    
    .hero-title {
      font-size: 2rem;
      font-weight: 700;
      color: var(--primary);
      margin-bottom: 0.5rem;
    }
    
    .hero-subtitle {
      color: var(--muted);
      font-size: 1rem;
      max-width: 500px;
      margin: 0 auto;
    }
    
    @media (min-width: 640px) {
      .hero {
        padding: 4rem 1rem;
      }
      .hero-title {
        font-size: 2.5rem;
      }
    }
    
    /* Public Header */
    .header-public {
      justify-content: center;
      border-bottom: none;
      margin-bottom: 0;
    }
    
    .header-public .logo {
      font-size: 1rem;
    }
    
    .header-nav {
      display: flex;
      align-items: center;
      gap: 1rem;
    }
    
    /* Clickable Recipe Card */
    .recipe-card-public {
      cursor: pointer;
      transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .recipe-card-public:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
    }
    
    /* Recipe Detail Modal */
    .recipe-detail-section {
      margin-bottom: 1.5rem;
    }
    
    .recipe-detail-section:last-child {
      margin-bottom: 0;
    }
    
    .recipe-detail-title {
      font-size: 0.875rem;
      font-weight: 600;
      color: var(--primary);
      margin-bottom: 0.5rem;
      text-transform: uppercase;
      letter-spacing: 0.05em;
    }
    
    .recipe-detail-content {
      font-size: 0.875rem;
      color: var(--foreground);
      line-height: 1.7;
      white-space: pre-line;
    }
    
    .recipe-detail-meta {
      display: flex;
      gap: 1.5rem;
      padding: 1rem;
      background: var(--secondary);
      border-radius: var(--radius);
      margin-bottom: 1.5rem;
    }
    
    .recipe-detail-meta-item {
      text-align: center;
    }
    
    .recipe-detail-meta-value {
      font-size: 1.25rem;
      font-weight: 600;
      color: var(--primary);
    }
    
    .recipe-detail-meta-label {
      font-size: 0.75rem;
      color: var(--muted);
    }
    
    /* Section Title */
    .section-title {
      font-size: 1.125rem;
      font-weight: 600;
      margin-bottom: 1rem;
      color: var(--foreground);
    }
  </style>
</head>
<body>
  <div class="container">
    <!-- Header -->
    <header class="header header-public">
      <nav class="header-nav">
        <a href="home.php" class="logo">Marrakech Food Lovers</a>
        <a href="login.php" class="btn btn-primary btn-sm">Connexion</a>
      </nav>
    </header>

    <!-- Hero -->
    <section class="hero">
      <h1 class="hero-title">Saveurs du Maroc</h1>
      <p class="hero-subtitle">Decouvrez les meilleures recettes traditionnelles marocaines partagees par notre communaute</p>
    </section>

    <!-- Main Content -->
    <main>
      <!-- Category Filter -->
      <div class="category-filter">
        <a href="home.php" class="category-btn <?php echo !$filter_category ? 'active' : ''; ?>">
          Toutes
        </a>
        <?php foreach ($categories as $cat): ?>
          <a 
            href="home.php?category=<?php echo $cat['id']; ?>" 
            class="category-btn <?php echo $filter_category === $cat['id'] ? 'active' : ''; ?>"
          >
            <?php echo htmlspecialchars($cat['name']); ?>
          </a>
        <?php endforeach; ?>
      </div>

      <!-- Recipes Grid -->
      <?php if (empty($filtered_recipes)): ?>
        <div class="empty-state">
          <div class="empty-state-icon">&#127858;</div>
          <p class="empty-state-text">Aucune recette dans cette categorie pour le moment.</p>
        </div>
      <?php else: ?>
        <div class="recipes-grid">
          <?php foreach ($filtered_recipes as $recipe): ?>
            <div class="recipe-card recipe-card-public" onclick="openRecipeDetail(<?php echo htmlspecialchars(json_encode($recipe)); ?>)">
              <div class="recipe-card-header">
                <h3 class="recipe-title"><?php echo htmlspecialchars($recipe['title']); ?></h3>
                <span class="recipe-category"><?php echo htmlspecialchars($recipe['category_name']); ?></span>
              </div>
              <div class="recipe-meta">
                <span><?php echo $recipe['prep_time']; ?> min</span>
                <span><?php echo $recipe['portions']; ?> portions</span>
              </div>
              <p class="recipe-description"><?php echo htmlspecialchars($recipe['description']); ?></p>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </main>

    <!-- Footer -->
    <footer style="text-align: center; padding: 2rem 0; margin-top: 3rem; border-top: 1px solid var(--border); color: var(--muted); font-size: 0.875rem;">
      Marrakech Food Lovers &copy; <?php echo date('Y'); ?>
    </footer>
  </div>

  <!-- Recipe Detail Modal -->
  <div class="modal-overlay" id="recipeDetailModal">
    <div class="modal" style="max-width: 600px;">
      <div class="modal-header">
        <h2 class="modal-title" id="detailTitle">Titre de la Recette</h2>
        <button class="modal-close" onclick="closeModal()">&times;</button>
      </div>
      <div class="modal-body">
        <div class="recipe-detail-meta">
          <div class="recipe-detail-meta-item">
            <div class="recipe-detail-meta-value" id="detailTime">45</div>
            <div class="recipe-detail-meta-label">minutes</div>
          </div>
          <div class="recipe-detail-meta-item">
            <div class="recipe-detail-meta-value" id="detailPortions">4</div>
            <div class="recipe-detail-meta-label">portions</div>
          </div>
          <div class="recipe-detail-meta-item">
            <div class="recipe-detail-meta-value" id="detailCategory">Plats</div>
            <div class="recipe-detail-meta-label">categorie</div>
          </div>
        </div>
        
        <div class="recipe-detail-section">
          <h3 class="recipe-detail-title">Description</h3>
          <p class="recipe-detail-content" id="detailDescription">Description de la recette</p>
        </div>
        
        <div class="recipe-detail-section">
          <h3 class="recipe-detail-title">Ingredients</h3>
          <p class="recipe-detail-content" id="detailIngredients">Ingredients de la recette</p>
        </div>
        
        <div class="recipe-detail-section">
          <h3 class="recipe-detail-title">Instructions</h3>
          <p class="recipe-detail-content" id="detailInstructions">Instructions de la recette</p>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" onclick="closeModal()">Fermer</button>
      </div>
    </div>
  </div>

  <script>
    function openRecipeDetail(recipe) {
      document.getElementById('detailTitle').textContent = recipe.title;
      document.getElementById('detailTime').textContent = recipe.prep_time;
      document.getElementById('detailPortions').textContent = recipe.portions;
      document.getElementById('detailCategory').textContent = recipe.category_name;
      document.getElementById('detailDescription').textContent = recipe.description || 'Aucune description';
      document.getElementById('detailIngredients').textContent = recipe.ingredients || 'Ingredients non specifies';
      document.getElementById('detailInstructions').textContent = recipe.instructions || 'Instructions non specifiees';
      
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
