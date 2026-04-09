<?php
define('BASE_URL', str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])));

require_once __DIR__ . '/../core/routes.php';

$router = new Router();
$router->run();
// require_once __DIR__ . '/../core/routes.php';
// require_once __DIR__ . '/../controllers/AuthController.php';
// require_once __DIR__ . '/../controllers/RecipeController.php';

// $url = $_GET['url'] ?? 'home';

// $url = explode('/', trim($url, '/'));

// $route = $url[0];

// switch ($route) {

//     case 'login':
//         $controller = new AuthController();
//         $controller->handleLogin();
//         break;

//     case 'register':
//         $controller = new AuthController();
//         $controller->handleRegister();
//         break;

//     case 'recipe':
//         $controller = new RecipeController();
//         $controller->show($url[1] ?? null);
//         break;

//     case 'Favorite-recipes':
//         $controller = new RecipeController();
//         $controller->create();
//         break;

//     default:
//         echo "404 Not Found";
// }

?>
