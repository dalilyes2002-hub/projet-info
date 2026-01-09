<?php
session_start();
require_once __DIR__ . '/include/db.php'; // connexion PDO

$errors = [];
$success = '';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    // Validation
    if (!$nom) $errors[] = 'Le nom est requis.';
    if (!$prenom) $errors[] = 'Le prénom est requis.';
    if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email invalide.';
    if (!$password) $errors[] = 'Le mot de passe est requis.';
    if ($password !== $password_confirm) $errors[] = 'Les mots de passe ne correspondent pas.';
    if (strlen($password) < 6) $errors[] = 'Le mot de passe doit faire au moins 6 caractères.';

    // Vérifier si l'email existe déjà
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = :email");
        $stmt->execute([':email' => $email]);
        if ($stmt->fetch()) {
            $errors[] = 'Cet email est déjà utilisé.';
        }
    }

    // Insertion
    if (empty($errors)) {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe) VALUES (:nom, :prenom, :email, :mot_de_passe)");
        $stmt->execute([
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':email' => $email,
            ':mot_de_passe' => $hash
        ]);
        $success = 'Inscription réussie ! Vous pouvez maintenant vous connecter.';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription | Générateur de CV</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Inscrivez-vous gratuitement pour créer votre CV professionnel en ligne.">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        body { background: #f8f9fa; }
        .form-container { max-width: 500px; margin: 50px auto; padding: 30px; background: #fff; border-radius: 10px; box-shadow: 0 0 15px rgba(0,0,0,0.1);}
        .transition-hover { transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .transition-hover:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.2);}
    </style>
</head>
<body>
<div class="form-container">
    <h2 class="text-center mb-4"><i class="fas fa-user-plus me-2"></i>Créer un compte</h2>

    <?php if ($errors): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $err) echo "<li>".htmlspecialchars($err)."</li>"; ?>
            </ul>
        </div>
    <?php elseif ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST" novalidate>
        <div class="mb-3">
            <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="nom" name="nom" required value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label for="prenom" class="form-label">Prénom <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="prenom" name="prenom" required value="<?= htmlspecialchars($_POST['prenom'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
            <input type="email" class="form-control" id="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe <span class="text-danger">*</span></label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="mb-3">
            <label for="password_confirm" class="form-label">Confirmer le mot de passe <span class="text-danger">*</span></label>
            <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
        </div>
        <button type="submit" class="btn btn-success w-100 transition-hover"><i class="fas fa-user-plus me-2"></i>S'inscrire</button>
        <p class="text-center mt-3">Déjà inscrit ? <a href="login.php">Connexion</a></p>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
