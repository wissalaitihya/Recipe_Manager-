<?php session_start();?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion - Marrakech Food Lovers</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
  <div class="auth-container">
    <div class="card auth-card">
      <div class="auth-header">
        <h1 class="auth-title">Marrakech Food Lovers</h1>
        <p class="auth-subtitle">Connectez-vous pour acceder a vos recettes</p>
      </div>


      <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error">
          <?php echo htmlspecialchars($_SESSION['error']); ?>
          <?php unset($_SESSION['error']); ?>
        </div>
      <?php endif; ?>
      
      <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
          <?php echo htmlspecialchars($_SESSION['success']); ?> 
          <?php unset($_SESSION['success']); ?>
        </div>
      <?php endif; ?>

      <form action="auth/login.php" method="POST">
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
            placeholder="Votre mot de passe"
            required
          >
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%;">
          Se connecter
        </button>
      </form>

      <p class="auth-link">
        Pas encore de compte ? <a href="register.php">Inscrivez-vous</a>
      </p>
      <p class="auth-link" style="margin-top: 0.5rem;">
        <a href="home.php">&larr; Retour  l'accueil</a>
      </p>
    </div>
  </div>
</body>
</html>
