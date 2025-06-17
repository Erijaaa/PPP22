<?php
// Démarrer la session pour accéder aux variables de session
session_start();

// Supprimer toutes les variables de session
$_SESSION = [];

// Détruire la session complètement
session_destroy();

// (Optionnel) Supprimer les cookies de session si utilisés
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Rediriger vers la page de connexion ou d'accueil
header("Location: index2.php");
exit();
?>
