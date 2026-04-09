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
<title>Recettes Communautaires</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:wght@600&display=swap" rel="stylesheet">

<style>
:root{
  --bg:#f6f7fb;
  --card:#ffffff;
  --primary:#e67e22;
  --text:#2d3436;
  --muted:#7f8c8d;
  --radius:14px;
}

*{box-sizing:border-box;margin:0;padding:0}

body{
  font-family:'Inter',sans-serif;
  background:var(--bg);
  color:var(--text);
}

/* HEADER */
header{
  display:flex;
  justify-content:space-between;
  align-items:center;
  padding:20px 6%;
  background:#fff;
  box-shadow:0 4px 20px rgba(0,0,0,0.05);
  position:sticky;
  top:0;
  z-index:100;
}

.logo{
  font-family:'Playfair Display',serif;
  font-size:24px;
  text-decoration:none;
  color:var(--text);
}

.nav a{
  margin-left:15px;
  text-decoration:none;
  font-weight:500;
}

.btn{
  background:var(--primary);
  color:#fff;
  padding:8px 18px;
  border-radius:30px;
}

/* HERO */
.hero{
  text-align:center;
  padding:70px 20px 50px;
}

.hero h1{
  font-family:'Playfair Display',serif;
  font-size:42px;
  margin-bottom:10px;
}

.hero p{
  color:var(--muted);
  max-width:600px;
  margin:auto;
}

/* FILTER */
.category-filter{
  margin:40px 0;
  display:flex;
  justify-content:center;
  flex-wrap:wrap;
  gap:10px;
}

.category-btn{
  padding:8px 18px;
  border-radius:30px;
  border:1px solid #ddd;
  text-decoration:none;
  color:var(--text);
  background:#fff;
  font-size:14px;
}

.category-btn.active,
.category-btn:hover{
  background:var(--primary);
  color:#fff;
  border-color:var(--primary);
}

/* GRID */
.recipes-grid{
  padding:0 6% 80px;
  display:grid;
  grid-template-columns:repeat(auto-fill,minmax(280px,1fr));
  gap:25px;
}

/* CARD */
.recipe-card-public{
  background:var(--card);
  border-radius:var(--radius);
  padding:20px;
  cursor:pointer;
  transition:.25s;
  border:1px solid #eee;
}

.recipe-card-public:hover{
  transform:translateY(-6px);
  box-shadow:0 10px 30px rgba(0,0,0,0.08);
}

.recipe-category{
  font-size:12px;
  color:var(--primary);
  font-weight:600;
  margin-bottom:6px;
}

.recipe-title{
  font-size:18px;
  margin-bottom:10px;
}

.recipe-description{
  font-size:14px;
  color:var(--muted);
  margin-bottom:15px;
}

.recipe-meta{
  font-size:13px;
  display:flex;
  justify-content:space-between;
  color:var(--muted);
}

/* FOOTER */
footer{
  text-align:center;
  padding:40px 20px;
  color:var(--muted);
}

/* MODAL */
.modal-overlay{
  position:fixed;
  inset:0;
  background:rgba(0,0,0,0.5);
  display:none;
  justify-content:center;
  align-items:center;
  z-index:999;
}

.modal-overlay.active{display:flex}

.modal{
  background:#fff;
  width:95%;
  max-width:650px;
  border-radius:var(--radius);
  padding:25px;
  max-height:90vh;
  overflow:auto;
}

.modal h2{
  font-family:'Playfair Display',serif;
  margin-bottom:10px;
}

.close{
  float:right;
  cursor:pointer;
  font-size:20px;
}
</style>
</head>
<body>

<header>
  <a href="<?php echo BASE_URL; ?>/recipe/home" class="logo">Recettes Communautaires</a>
  <div class="nav">
    <?php if ($is_logged_in): ?>
      <a href="<?php echo BASE_URL; ?>/recipe/dashboard" class="btn">Mon Espace</a>
    <?php else: ?>
      <a href="<?php echo BASE_URL; ?>/auth/login">Connexion</a>
      <a href="<?php echo BASE_URL; ?>/auth/register" class="btn">Inscription</a>
    <?php endif; ?>
  </div>
</header>

<section class="hero">
  <h1>Partagez vos recettes. Découvrez des favoris.</h1>
  <p>Une plateforme où les passionnés de cuisine créent, partagent et sauvegardent leurs recettes préférées.</p>
</section>

<div class="category-filter">
  <a href="<?php echo BASE_URL; ?>/recipe/home#menu" class="category-btn <?php echo !$filter_category ? 'active' : ''; ?>">Toutes</a>
  <?php foreach ($categories as $cat): ?>
    <a href="<?php echo BASE_URL; ?>/recipe/home?category=<?php echo $cat['id']; ?>#menu"
       class="category-btn <?php echo $filter_category === $cat['id'] ? 'active' : ''; ?>">
       <?php echo htmlspecialchars($cat['name']); ?>
    </a>
  <?php endforeach; ?>
</div>

<?php if (empty($filtered_recipes)): ?>
  <p style="text-align:center;color:var(--muted)">Aucune recette trouvée.</p>
<?php else: ?>
<div class="recipes-grid">
  <?php foreach ($filtered_recipes as $recipe): ?>
  <div class="recipe-card-public"
       onclick='openRecipeDetail(<?php echo json_encode($recipe, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>)'>
       
    <div class="recipe-category"><?php echo htmlspecialchars($recipe['category_name']); ?></div>
    <div class="recipe-title"><?php echo htmlspecialchars($recipe['title']); ?></div>
    <div class="recipe-description">
      <?php echo htmlspecialchars(substr($recipe['ingredient'] ?? '',0,90)); ?>...
    </div>
    <div class="recipe-meta">
      <span>⏱ <?php echo $recipe['temp_de_production']; ?></span>
      <span>🍽 <?php echo $recipe['portions']; ?> pers.</span>
    </div>
  </div>
  <?php endforeach; ?>
</div>
<?php endif; ?>

<footer>
  © <?php echo date('Y'); ?> — Plateforme de partage de recettes
</footer>

<!-- MODAL -->
<div class="modal-overlay" id="recipeDetailModal">
  <div class="modal">
    <span class="close" onclick="closeModal()">&times;</span>
    <h2 id="detailTitle"></h2>
    <p><strong>Temps:</strong> <span id="detailTime"></span></p>
    <p><strong>Portions:</strong> <span id="detailPortions"></span></p>
    <p><strong>Catégorie:</strong> <span id="detailCategory"></span></p>
    <hr>
    <h3>Ingrédients</h3>
    <p id="detailIngredients"></p>
    <h3>Instructions</h3>
    <p id="detailInstructions"></p>
    <p id="detailChef" style="margin-top:15px;color:var(--primary)"></p>
  </div>
</div>

<script>
function openRecipeDetail(recipe){
  detailTitle.textContent = recipe.title;
  detailTime.textContent = recipe.temp_de_production;
  detailPortions.textContent = recipe.portions;
  detailCategory.textContent = recipe.category_name;
  detailIngredients.textContent = recipe.ingredient;
  detailInstructions.textContent = recipe.instructions;
  detailChef.textContent = recipe.chef_name ? "Proposée par " + recipe.chef_name : "";
  recipeDetailModal.classList.add('active');
}
function closeModal(){
  recipeDetailModal.classList.remove('active');
}
</script>

</body>
</html>