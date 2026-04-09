<?php
include_once __DIR__ . '/../includes/headre.php';
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
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Recettes - Marrakech Food Lovers</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <div class="container">


        <!-- Main Content -->
        <main>
            <!-- Recipes Header -->
            <div class="recipes-header">
                <h1 class="recipes-title">Mes Recettes</h1>
                <button class="btn btn-primary" onclick="openModal('add')">
                    + Nouvelle Recette
                </button>
            </div>
            <!--search  bar -->
            <div>
                <form method="GET" action="<?php echo BASE_URL; ?>/recipe/dashboard" class="search-form">
                    <?php if ($filter_category): ?>
                        <input type="hidden" name="category" value="<?php echo htmlspecialchars($filter_category); ?>">
                    <?php endif; ?>
                    <input type="text" name="search" class="form-input"
                        placeholder="Rechercher par titre ou ingredient..."
                        value="<?php echo htmlspecialchars($search ?? ''); ?>">
                    <button type="submit" class="btn btn-primary">Rechercher</button>
                    <?php if ($search): ?>
                        <a href="<?php echo BASE_URL; ?>/recipe/dashboard<?php echo $filter_category ? '?category=' . $filter_category : ''; ?>"
                            class="btn btn-ghost">Effacer</a>
                    <?php endif; ?>
                </form>
            </div>
            <!-- Category Filter -->
            <div class="category-filter">
                <a href="<?php echo BASE_URL; ?>/recipe/dashboard" class="category-btn <?php echo !$filter_category ? 'active' : ''; ?>">
                    Toutes
                </a>
                <?php foreach ($categories as $cat): ?>
                    <a href="<?php echo BASE_URL; ?>/recipe/dashboard?category=<?php echo $cat['id']; ?>"
                        class="category-btn <?php echo $filter_category === $cat['id'] ? 'active' : ''; ?>">
                        <?php echo htmlspecialchars($cat['name']); ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- Recipes Grid -->
            <?php if (empty($recipes)): ?>
                <div class="empty-state">
                    <div class="empty-state-icon">&#127858;</div>
                    <p class="empty-state-text">Aucune recette trouvee. Ajoutez votre premiere recette!</p>
                </div>
            <?php else: ?>
                <div class="recipes-grid">
                    <?php foreach ($recipes as $recipe): ?>
                        <div class="recipe-card">
                            <div class="recipe-card-header">
                                <h3 class="recipe-title"><?php echo htmlspecialchars($recipe['title']); ?></h3>
                                <span class="recipe-category"><?php echo htmlspecialchars($recipe['category_name']); ?></span>
                            </div>
                            <div class="recipe-meta">
                                <span><?php echo htmlspecialchars($recipe['temp_de_production']); ?></span>
                                <span><?php echo htmlspecialchars($recipe['portions']); ?> portions</span>
                            </div>
                            <p class="recipe-description"><?php echo htmlspecialchars($recipe['ingredient'] ?? ''); ?></p>
                            <div class="recipe-actions">

                                <a href="<?php echo BASE_URL; ?>/recipe/toggleFavorite?id=<?php echo $recipe['id']; ?>"
                                    class="btn btn-sm <?php echo $favoriteModel->isFavorite($_SESSION['user_id'], $recipe['id']) ? 'btn-primary' : 'btn-ghost'; ?>">
                                    <?php echo $favoriteModel->isFavorite($_SESSION['user_id'], $recipe['id']) ? '❤️ Favori' : '🤍 Favori'; ?>
                                </a>
                                <button class="btn btn-secondary btn-sm"
                                    onclick="openModal('edit', <?php echo htmlspecialchars(json_encode($recipe)); ?>)">
                                    Modifier
                                </button>
                                <button class="btn btn-ghost btn-sm"
                                    onclick="openModal('delete', <?php echo $recipe['id']; ?>)">
                                    Supprimer
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </main>
    </div>

    <!-- Add/Edit Recipe Modal -->
    <div class="modal-overlay" id="recipeModal">
        <div class="modal">
            <div class="modal-header">
                <h2 class="modal-title" id="modalTitle">Nouvelle Recette</h2>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <form action="<?php echo BASE_URL; ?>/recipe/save" method="POST">
                <input type="hidden" name="id" id="recipeId">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label" for="title">Titre</label>
                        <input type="text" id="title" name="title" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="category">Categorie</label>
                        <select id="category" name="category_id" class="form-select" required>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label class="form-label" for="prep_time">Temps (min)</label>
                            <input type="number" id="prep_time" name="prep_time" class="form-input" min="1" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="portions">Portions</label>
                            <input type="number" id="portions" name="portions" class="form-input" min="1" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="ingredients">Ingredients</label>
                        <textarea id="ingredients" name="ingredients" class="form-textarea" rows="4"
                            placeholder="Un ingredient par ligne"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="instructions">Instructions</label>
                        <textarea id="instructions" name="instructions" class="form-textarea" rows="4"
                            placeholder="Les etapes de preparation"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal-overlay" id="deleteModal">
        <div class="modal" style="max-width: 400px;">
            <div class="modal-header">
                <h2 class="modal-title">Supprimer la recette</h2>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body">
                <p>Etes-vous sur de vouloir supprimer cette recette ? Cette action est irreversible.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Annuler</button>
                <form action="<?php echo BASE_URL; ?>/recipe/delete" method="POST" style="display: inline;">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" id="deleteRecipeId">
                    <button type="submit" class="btn btn-destructive">Supprimer</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openModal(type, data = null) {
            if (type === 'delete') {
                document.getElementById('deleteRecipeId').value = data;
                document.getElementById('deleteModal').classList.add('active');
            } else {
                const modal = document.getElementById('recipeModal');
                const title = document.getElementById('modalTitle');

                if (type === 'edit' && data) {
                    title.textContent = 'Modifier la Recette';
                    document.getElementById('recipeId').value = data.id;
                    document.getElementById('title').value = data.title;
                    document.getElementById('category').value = data.categories_id;
                    document.getElementById('prep_time').value = data.temp_de_production;
                    document.getElementById('portions').value = data.portions;
                    document.getElementById('ingredients').value = data.ingredient || '';
                    document.getElementById('instructions').value = data.instructions || '';
                } else {
                    title.textContent = 'Nouvelle Recette';
                    document.getElementById('recipeId').value = '';
                    document.querySelector('#recipeModal form').reset();
                }

                modal.classList.add('active');
            }
        }

        function closeModal() {
            document.getElementById('recipeModal').classList.remove('active');
            document.getElementById('deleteModal').classList.remove('active');
        }

        // Close modal on overlay click
        document.querySelectorAll('.modal-overlay').forEach(overlay => {
            overlay.addEventListener('click', (e) => {
                if (e.target === overlay) closeModal();
            });
        });

        // Close modal on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeModal();
        });
    </script>
</body>

</html>