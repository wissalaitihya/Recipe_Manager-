<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inscription - Marrakech Food Lovers</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
  <div class="auth-container">
    <div class="card auth-card">
      <div class="auth-header">
        <h1 class="auth-title">Marrakech Food Lovers</h1>
        <p class="auth-subtitle">Creez votre compte pour partager vos recettes</p>
      </div>

      <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error">
          <?php echo htmlspecialchars($_SESSION['error']); ?>
          <?php unset($_SESSION['error']); ?>
        </div>
      <?php endif; ?>

      <form action="../../controllers/AuthController.php?action=register" method="POST">
        <div class="form-group">
          <label class="form-label" for="name">Nom complet</label>
          <input 
            type="text" 
            id="name" 
            name="name" 
            class="form-input" 
            placeholder="Votre nom"
            required
          >
        </div>

        <div class="form-group">
          <label class="form-label" for="email">Email</label>
          <input 
            type="email" 
            id="email" 
            name="email" 
            class="form-input" 
            placeholder="votre@email.com"
            required
          >
        </div>

        <div class="form-group">
          <label class="form-label" for="password">Mot de passe</label>
          <input 
            type="password" 
            id="password" 
            name="password" 
            class="form-input" 
            placeholder="Minimum 6 caracteres"
            minlength="6"
            required
          >
        </div>

        <div class="form-group">
          <label class="form-label" for="password_confirm">Confirmer le mot de passe</label>
          <input 
            type="password" 
            id="password_confirm" 
            name="password_confirm" 
            class="form-input" 
            placeholder="Confirmez votre mot de passe"
            minlength="6"
            required
          >
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%;">
          Creer un compte
        </button>
      </form>

      <p class="auth-link">
        Deja un compte ? <a href="login.php">Connectez-vous</a>
      </p>
    </div>
  </div>
</body>
</html>
