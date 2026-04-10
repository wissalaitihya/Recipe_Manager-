<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inscription - Matbakhi</title>
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
      --error: #d32f2f;
      --success: #388e3c;
      --radius: 12px;
    }

    body {
      font-family: 'DM Sans', sans-serif;
      background-color: #faf6f0;
      background-image:
        linear-gradient(var(--cross-color, #dfd5c8) 1.5px, transparent 1.5px),
        linear-gradient(90deg, var(--cross-color, #dfd5c8) 1.5px, transparent 1.5px);
      background-size: 32px 32px;
      background-position: center center;
      --cross-color: #e2d5c9;
      position: relative;
      color: var(--text);
      line-height: 1.6;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    body::before {
      content: '';
      position: fixed;
      inset: 0;
      background:
        radial-gradient(ellipse 70% 60% at 50% 50%, transparent 40%, #faf6f0 100%);
      pointer-events: none;
      z-index: -1;
    }

    /* ── HEADER ── */
    .auth-header {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 30px;
      text-align: center;
      flex-direction: column;
    }

    .logo-icon {
      width: 48px;
      height: 48px;
      background: var(--primary);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
      color: #fff;
    }

    .auth-title {
      font-family: 'Cormorant Garamond', serif;
      font-size: 32px;
      font-weight: 700;
      color: var(--text);
      margin-bottom: 6px;
    }

    .auth-subtitle {
      font-size: 14px;
      color: var(--muted);
    }

    /* ── CARD ── */
    .auth-card {
      width: 100%;
      max-width: 420px;
      background: var(--card);
      border-radius: var(--radius);
      border: 1px solid var(--border);
      padding: 40px;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
    }

    /* ── ALERTS ── */
    .alert {
      padding: 14px 16px;
      border-radius: 8px;
      font-size: 13px;
      font-weight: 500;
      margin-bottom: 20px;
      line-height: 1.5;
    }

    .alert-error {
      background: #ffebee;
      color: var(--error);
      border: 1px solid #ffcdd2;
    }

    .alert-success {
      background: #e8f5e9;
      color: var(--success);
      border: 1px solid #c8e6c9;
    }

    /* ── FORM ELEMENTS ── */
    .form-group {
      margin-bottom: 22px;
    }

    .form-label {
      display: block;
      font-size: 13px;
      font-weight: 600;
      color: var(--text);
      margin-bottom: 8px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .form-input {
      width: 100%;
      padding: 12px 14px;
      border: 1.5px solid var(--border);
      border-radius: 8px;
      font-size: 14px;
      font-family: 'DM Sans', sans-serif;
      background: #fafafa;
      color: var(--text);
      transition: border-color .2s, background .2s;
    }

    .form-input:focus {
      outline: none;
      border-color: var(--primary);
      background: #fff;
      box-shadow: 0 0 0 3px rgba(181, 80, 58, 0.08);
    }

    .form-input::placeholder {
      color: var(--muted);
    }

    /* ── BUTTON ── */
    .btn {
      padding: 12px 24px;
      border: none;
      border-radius: 8px;
      font-size: 14px;
      font-weight: 600;
      cursor: pointer;
      transition: all .2s;
      font-family: 'DM Sans', sans-serif;
      text-decoration: none;
      display: inline-block;
      text-align: center;
    }

    .btn-primary {
      width: 100%;
      background: var(--primary);
      color: #fff;
    }

    .btn-primary:hover {
      background: var(--primary-hover);
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(181, 80, 58, 0.3);
    }

    .btn-primary:active {
      transform: translateY(0);
    }

    /* ── AUTH LINKS ── */
    .auth-link {
      text-align: center;
      font-size: 13px;
      color: var(--muted);
      margin-top: 18px;
    }

    .auth-link a {
      color: var(--primary);
      text-decoration: none;
      font-weight: 600;
      transition: color .2s;
    }

    .auth-link a:hover {
      color: var(--primary-hover);
      text-decoration: underline;
    }

    /* ── RESPONSIVE ── */
    @media (max-width: 480px) {
      .auth-card {
        padding: 30px 20px;
      }

      .auth-title {
        font-size: 28px;
      }
    }
  </style>
</head>

<body>

  <div class="auth-card">
    <div class="auth-header">
      <div class="logo-icon">🍳</div>
      <h1 class="auth-title">Matbakhi</h1>
      <p class="auth-subtitle">Créez votre compte pour partager vos recettes</p>
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
          placeholder="Minimum 6 caractères"
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

      <button type="submit" class="btn btn-primary">
        Créer un compte
      </button>
    </form>

    <p class="auth-link">
      Déjà un compte ? <a href="login.php">Connectez-vous</a>
    </p>
  </div>

</body>

</html>
