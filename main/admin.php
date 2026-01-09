<?php
session_start();

/* =========================================
   CONSTANTES ET CONFIGURATIONS
========================================= */
const USERS_PER_PAGE = 10; // Nombre d'utilisateurs par page

/* =========================================
   CONNEXION À LA BASE DE DONNÉES
========================================= */
require_once '../include/db.php'; // Ton fichier de connexion PDO

/* =========================================
   SÉCURITÉ : ADMIN UNIQUEMENT
========================================= */
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin' || session_id() !== $_SESSION['session_id']) {
    header('Location: login.php');
    exit;
}

/* =========================================
   FONCTIONS UTILITAIRES
========================================= */
function getUsers($pdo, $search = '', $offset = 0, $limit = USERS_PER_PAGE) {
    $search = trim($search);
    $params = [];
    $searchCondition = '';

    if (!empty($search)) {
        $searchCondition = " AND (u.nom LIKE :search OR u.prenom LIKE :search OR u.email LIKE :search)";
        $params[':search'] = '%' . $search . '%';
    }

    // CAST pour éviter injection sur LIMIT/OFFSET
    $offset = (int)$offset;
    $limit = (int)$limit;

    try {
        $stmt = $pdo->prepare("
            SELECT 
                u.id,
                u.nom,
                u.prenom,
                u.email,
                u.role,
                u.date_inscription,
                COUNT(c.id) AS total_cvs
            FROM utilisateurs u
            LEFT JOIN cvs c ON u.id = c.utilisateur_id
            WHERE 1=1 $searchCondition
            GROUP BY u.id
            ORDER BY u.date_inscription DESC
            LIMIT $limit OFFSET $offset
        ");
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erreur SQL : " . $e->getMessage());
        die("Une erreur est survenue. Veuillez réessayer plus tard.");
    }
}

function getTotalUsers($pdo, $search = '') {
    $search = trim($search);
    $params = [];
    $searchCondition = '';

    if (!empty($search)) {
        $searchCondition = " WHERE nom LIKE :search OR prenom LIKE :search OR email LIKE :search";
        $params[':search'] = '%' . $search . '%';
    }

    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) AS total FROM utilisateurs $searchCondition");
        $stmt->execute($params);
        return $stmt->fetch()['total'];
    } catch (PDOException $e) {
        error_log("Erreur SQL : " . $e->getMessage());
        return 0;
    }
}

/* =========================================
   GESTION DES PARAMÈTRES GET
========================================= */
$search = $_GET['search'] ?? '';
$page = max(1, (int)($_GET['page'] ?? 1));
$offset = ($page - 1) * USERS_PER_PAGE;

$users = getUsers($pdo, $search, $offset, USERS_PER_PAGE);
$totalUsers = getTotalUsers($pdo, $search);
$totalPages = ceil($totalUsers / USERS_PER_PAGE);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Administration | Générateur de CV</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Page d'administration pour gérer les utilisateurs du générateur de CV.">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-shield-check"></i> Administration – Utilisateurs</h2>
        <div>
            <a href="dashboard.php" class="btn btn-secondary btn-sm me-2">Retour</a>
            <a href="logout.php" class="btn btn-danger btn-sm">Déconnexion</a>
        </div>
    </div>

    <!-- Barre de recherche -->
    <form method="GET" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Rechercher par nom, prénom ou email..." value="<?= htmlspecialchars($search, ENT_QUOTES) ?>">
            <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Rechercher</button>
        </div>
    </form>

    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <p class="text-muted mb-3">Total : <?= $totalUsers ?> utilisateur(s) trouvé(s).</p>

            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th>ID</th>
                        <th>Nom complet</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>CV créés</th>
                        <th>Date d'inscription</th>
                    </tr>
                </thead>

                <tbody>
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="bi bi-info-circle"></i> Aucun utilisateur trouvé. Essayez une autre recherche.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $user): ?>
                        <tr class="text-center">
                            <td><?= htmlspecialchars($user['id'], ENT_QUOTES) ?></td>
                            <td><?= htmlspecialchars($user['prenom'] . ' ' . $user['nom'], ENT_QUOTES) ?></td>
                            <td><?= htmlspecialchars($user['email'], ENT_QUOTES) ?></td>
                            <td>
                                <span class="badge <?= $user['role'] === 'admin' ? 'bg-danger' : 'bg-primary' ?>">
                                    <i class="bi <?= $user['role'] === 'admin' ? 'bi-shield' : 'bi-person' ?>"></i> <?= htmlspecialchars($user['role'], ENT_QUOTES) ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-success">
                                    <i class="bi bi-file-earmark-text"></i> <?= htmlspecialchars($user['total_cvs'], ENT_QUOTES) ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($user['date_inscription'], ENT_QUOTES) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <nav aria-label="Pagination">
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>">Précédent</a>
                        </li>
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>">Suivant</a>
                        </li>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </div>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

