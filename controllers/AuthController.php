<?php
require_once __DIR__ . '/../models/user.php';

class AuthController
{
    public function login()
    {
        include __DIR__ . '/../views/auth/login.php';
    }

    public function register()
    {
        include __DIR__ . '/../views/auth/register.php';
    }

    public function handleLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $user = new User();
            $user->setEmail($email);
            $user->setPassword($password);

            if ($user->login()) {
                session_start();
                $_SESSION['user_id'] = $user->getId();
                $_SESSION['user_name'] = $user->getUsername();
                header("Location: " . BASE_URL . "/recipe/dashboard");
                exit();
            } else {
                header("Location: " . BASE_URL . "/auth/login?error=Identifiants+incorrects");
                exit();
            }
        }
    }

    public function handleRegister()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $password_confirm = $_POST['password_confirm'] ?? '';

            if ($password !== $password_confirm) {
                header("Location: " . BASE_URL . "/auth/register?error=Les+mots+de+passe+ne+correspondent+pas");
                exit();
            }

            $user = new User();
            $user->setUsername($username);
            $user->setEmail($email);
            $user->setPassword($password);

            if ($user->register()) {
                header("Location: " . BASE_URL . "/auth/login?success=Inscription+reussie.+Veuillez+vous+connecter.");
                exit();
            } else {
                header("Location: " . BASE_URL . "/auth/register?error=Email+deja+utilise+ou+erreur");
                exit();
            }
        }
    }

    public function handleLogout()
    {
        session_start();
        session_unset();
        session_destroy();
        header("Location: " . BASE_URL . "/auth/login");
        exit();
    }
}