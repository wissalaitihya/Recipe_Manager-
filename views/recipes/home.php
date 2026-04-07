<?php
// Public homepage - no authentication required
// This page displays all recipes for visitors

// Example categories
$categories = [
    ['id' => 1, 'name' => 'Entrees'],
    ['id' => 2, 'name' => 'Plats'],
    ['id' => 3, 'name' => 'Desserts'],
    ['id' => 4, 'name' => 'Boissons'],
];

// Example recipes (replace with database query)
$recipes = [
    [
        'id' => 1,
        'title' => 'Tajine de Poulet aux Olives',
        'description' => 'Un classique de la cuisine marocaine avec des olives et du citron confit.',
        'ingredients' => "1 poulet coupe en morceaux\n200g d'olives vertes\n2 citrons confits\n1 oignon\n3 gousses d'ail\nGingembre, safran, curcuma\nHuile d'olive\nSel et poivre",
        'instructions' => "1. Faire revenir le poulet avec les epices.\n2. Ajouter l'oignon et l'ail.\n3. Couvrir d'eau et laisser mijoter 30 min.\n4. Ajouter les olives et citrons confits.\n5. Cuire encore 15 min jusqu'a ce que la sauce epaississe.",
        'prep_time' => 45,
        'portions' => 4,
        'category_id' => 2,
        'category_name' => 'Plats',
        'created_at' => '2024-01-15',
    ],
    [
        'id' => 2,
        'title' => 'Harira',
        'description' => 'Soupe traditionnelle aux tomates, lentilles et pois chiches.',
        'ingredients' => "200g de lentilles\n200g de pois chiches\n500g de tomates\n1 oignon\nCeleri, persil, coriandre\nFarine\nSel, poivre, gingembre",
        'instructions' => "1. Faire tremper les pois chiches la veille.\n2. Faire revenir l'oignon et la viande.\n3. Ajouter les tomates et les epices.\n4. Ajouter l'eau, les lentilles et pois chiches.\n5. Cuire 45 min, puis ajouter les herbes.",
        'prep_time' => 60,
        'portions' => 6,
        'category_id' => 1,
        'category_name' => 'Entrees',
        'created_at' => '2024-01-10',
    ],
    [
        'id' => 3,
        'title' => 'Cornes de Gazelle',
        'description' => 'Patisserie en forme de croissant fourree aux amandes.',
        'ingredients' => "500g de pate d'amandes\n300g de farine\n100g de beurre\nEau de fleur d'oranger\nSucre glace",
        'instructions' => "1. Preparer la pate avec farine et beurre.\n2. Faire la farce aux amandes.\n3. Former des petits rouleaux de farce.\n4. Envelopper dans la pate en croissant.\n5. Cuire au four 15 min a 180°C.",
        'prep_time' => 90,
        'portions' => 20,
        'category_id' => 3,
        'category_name' => 'Desserts',
        'created_at' => '2024-01-08',
    ],
    [
        'id' => 4,
        'title' => 'The a la Menthe',
        'description' => 'Le celebre the vert marocain parfume a la menthe fraiche.',
        'ingredients' => "2 cuilleres de the vert\n1 bouquet de menthe fraiche\nSucre selon le gout\nEau bouillante",
        'instructions' => "1. Rincer le the avec un peu d'eau bouillante.\n2. Ajouter le the dans la theiere.\n3. Ajouter la menthe et le sucre.\n4. Verser l'eau bouillante.\n5. Laisser infuser 3-4 min.",
        'prep_time' => 10,
        'portions' => 4,
        'category_id' => 4,
        'category_name' => 'Boissons',
        'created_at' => '2024-01-05',
    ],
    [
        'id' => 5,
        'title' => 'Couscous Royal',
        'description' => 'Le plat emblematique avec legumes et viandes variees.',
        'ingredients' => "500g de semoule\n300g de poulet\n300g d'agneau\nCarottes, courgettes, navets\nPois chiches\nRas el hanout, cannelle",
        'instructions' => "1. Cuire les viandes avec les epices.\n2. Ajouter les legumes par ordre de cuisson.\n3. Preparer la semoule a la vapeur.\n4. Dresser avec les viandes et legumes.\n5. Arroser de bouillon.",
        'prep_time' => 120,
        'portions' => 8,
        'category_id' => 2,
        'category_name' => 'Plats',
        'created_at' => '2024-01-03',
    ],
    [
        'id' => 6,
        'title' => 'Briouates aux Amandes',
        'description' => 'Petits triangles croustillants fourres aux amandes et miel.',
        'ingredients' => "Feuilles de brick\n250g d'amandes\n100g de sucre\nMiel\nEau de fleur d'oranger\nHuile pour friture",
        'instructions' => "1. Mixer les amandes avec le sucre.\n2. Ajouter l'eau de fleur d'oranger.\n3. Former des triangles avec la farce.\n4. Frire jusqu'a doré.\n5. Tremper dans le miel chaud.",
        'prep_time' => 45,
        'portions' => 15,
        'category_id' => 3,
        'category_name' => 'Desserts',
        'created_at' => '2024-01-01',
    ],
];

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
