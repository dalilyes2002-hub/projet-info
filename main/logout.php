<?php
// logout.php
// Script de déconnexion sécurisé

// Démarrer la session si elle n'est pas déjà active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Constante pour la redirection après déconnexion
const REDIRECT_URL = 'index.php';

// Vérifier si l'utilisateur est connecté
if (isset($_SESSION['utilisateur_id'])) {
    $role = $_SESSION['role'] ?? 'utilisateur';

    try {
        // Régénérer l'ID de session pour éviter les attaques de fixation
        session_regenerate_id(true);

        // Vider toutes les variables de session
        $_SESSION = [];

        // Détruire la session côté serveur
        if (session_destroy()) {
            // Supprimer le cookie de session côté client (si présent)
            if (ini_get('session.use_cookies')) {
                $params = session_get_cookie_params();
                setcookie(
                    session_name(),
                    '',
                    time() - 42000,  // Expiration dans le passé
                    $params['path'],
                    $params['domain'],
                    $params['secure'],
                    $params['httponly']
                );
            }

            // Redirection avec message selon le rôle
            $message = ($role === 'admin') ? 'admin_deconnecte' : 'deconnecte';
            header('Location: ' . REDIRECT_URL . '?' . http_build_query(['message' => $message]));
            exit;
        } else {
            // En cas d'échec de destruction, redirection avec erreur
            header('Location: ' . REDIRECT_URL . '?' . http_build_query(['message' => 'erreur_deconnexion']));
            exit;
        }
    } catch (Exception $e) {
        // Log de l'erreur pour debug en prod
        error_log('Erreur lors de la déconnexion : ' . $e->getMessage());
        header('Location: ' . REDIRECT_URL . '?' . http_build_query(['message' => 'erreur_deconnexion']));
        exit;
    }
} else {
    // Si l'utilisateur n'était pas connecté, simple redirection
    header('Location: ' . REDIRECT_URL);
    exit;
}
?>

