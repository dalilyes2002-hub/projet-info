<?php
session_start();
$basePath = __DIR__ . '/include/';
require_once $basePath . 'db.php'; // Connexion PDO

?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CV Generator | Créez votre CV professionnel</title>
<meta name="description" content="Générez un CV professionnel en ligne rapidement. Modèles modernes, aperçu en temps réel et export PDF.">
<meta name="keywords" content="CV, générateur de CV, CV en ligne, CV PDF, curriculum vitae">
<link rel="icon" href="assets/logo.png" type="image/png">

<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

<!-- CSS personnalisé -->
<link rel="stylesheet" href="assets/style.css">

<style>
/* Styles spécifiques à l'index */
body { font-family: 'Inter', sans-serif; scroll-behavior: smooth; }
.btn-hover { transition: transform 0.3s ease, box-shadow 0.3s ease; }
.btn-hover:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.2); }
.card-hover { transition: transform 0.3s ease; }
.card-hover:hover { transform: scale(1.05); }
.step-item { padding: 1rem; }
#back-to-top { z-index: 1050; }
.green-underline { width: 80px; height: 3px; background: #28a745; border-radius: 2px; }
</style>
</head>
<body class="bg-light text-dark">

<!-- ========================= -->
<!-- NAVBAR -->
<!-- ========================= -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php">
      <i class="fas fa-file-alt text-success me-2"></i>CV Generator
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav mx-auto">
        <li class="nav-item"><a class="nav-link" href="#features">Fonctionnalités</a></li>
        <li class="nav-item"><a class="nav-link" href="#how-it-works">Comment ça marche</a></li>
        <li class="nav-item"><a class="nav-link" href="#cta">Commencer</a></li>
      </ul>
      <ul class="navbar-nav">
        <?php if(isset($_SESSION['utilisateur_id'])): ?>
          <li class="nav-item"><a class="nav-link btn btn-success btn-sm text-white" href="dashboard.php">Mes CV</a></li>
          <li class="nav-item ms-2"><a class="nav-link btn btn-outline-danger btn-sm" href="logout.php">Déconnexion</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link btn btn-success btn-sm text-white" href="inscription.php">Inscription</a></li>
          <li class="nav-item ms-2"><a class="nav-link btn btn-outline-secondary btn-sm" href="login.php">Connexion</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<!-- ========================= -->
<!-- HERO -->
<!-- ========================= -->
<section class="py-5 bg-light text-center" style="margin-top:70px;">
  <div class="container">
    <h1 class="display-4 fw-bold mb-3">Créez votre <span class="text-success">CV professionnel</span> rapidement</h1>
    <p class="lead text-muted mb-4">Modèles modernes, aperçu en temps réel et export PDF gratuit.</p>
    <?php if(isset($_SESSION['utilisateur_id'])): ?>
      <a href="dashboard.php" class="btn btn-success btn-lg rounded-pill btn-hover"><i class="fas fa-file-alt me-2"></i>Mes CV</a>
    <?php else: ?>
      <a href="inscription.php" class="btn btn-success btn-lg rounded-pill btn-hover me-3">Commencer gratuitement</a>
      <a href="login.php" class="btn btn-outline-secondary btn-lg rounded-pill btn-hover">Connexion</a>
    <?php endif; ?>
  </div>
</section>

<!-- ========================= -->
<!-- FONCTIONNALITÉS -->
<!-- ========================= -->
<section id="features" class="py-5 text-center">
  <div class="container">
    <h2 class="display-5 fw-bold mb-2">Fonctionnalités</h2>
    <hr class="green-underline mx-auto mb-5">
    <div class="row">
      <div class="col-md-4 mb-4">
        <div class="card p-4 shadow-sm card-hover">
          <i class="fas fa-edit display-4 text-success mb-3"></i>
          <h5>Édition simple</h5>
          <p class="text-muted">Remplissez vos expériences, formations et compétences facilement via un formulaire guidé.</p>
        </div>
      </div>
      <div class="col-md-4 mb-4">
        <div class="card p-4 shadow-sm card-hover">
          <i class="fas fa-layer-group display-4 text-success mb-3"></i>
          <h5>Templates modernes</h5>
          <p class="text-muted">Choisissez parmi plusieurs modèles professionnels adaptés à votre profil.</p>
        </div>
      </div>
      <div class="col-md-4 mb-4">
        <div class="card p-4 shadow-sm card-hover">
          <i class="fas fa-file-pdf display-4 text-success mb-3"></i>
          <h5>Export PDF</h5>
          <p class="text-muted">Téléchargez votre CV prêt à envoyer aux recruteurs.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ========================= -->
<!-- COMMENT ÇA MARCHE -->
<!-- ========================= -->
<section id="how-it-works" class="py-5 bg-light text-center">
  <div class="container">
    <h2 class="display-5 fw-bold mb-2">Comment ça marche ?</h2>
    <hr class="green-underline mx-auto mb-5">
    <div class="row">
      <div class="col-md-3 mb-4"><i class="fas fa-user-plus display-5 text-success mb-2"></i><h6>1. Inscription</h6><p class="text-muted small">Créez un compte sécurisé.</p></div>
      <div class="col-md-3 mb-4"><i class="fas fa-pencil-alt display-5 text-success mb-2"></i><h6>2. Remplissez</h6><p class="text-muted small">Ajoutez vos informations personnelles et professionnelles.</p></div>
      <div class="col-md-3 mb-4"><i class="fas fa-eye display-5 text-success mb-2"></i><h6>3. Prévisualisez</h6><p class="text-muted small">Visualisez votre CV en temps réel.</p></div>
      <div class="col-md-3 mb-4"><i class="fas fa-download display-5 text-success mb-2"></i><h6>4. Téléchargez</h6><p class="text-muted small">Exportez en PDF facilement.</p></div>
    </div>
  </div>
</section>

<!-- ========================= -->
<!-- CTA -->
<!-- ========================= -->
<section id="cta" class="py-5 text-center">
  <div class="container">
    <h2 class="fw-bold mb-3">Prêt à créer votre CV ?</h2>
    <p class="text-muted mb-4">Rejoignez des centaines d'utilisateurs satisfaits !</p>
    <a href="inscription.php" class="btn btn-success btn-lg rounded-pill btn-hover">Créer mon CV maintenant</a>
  </div>
</section>

<!-- ========================= -->
<!-- BOUTON BACK TO TOP -->
<!-- ========================= -->
<button id="back-to-top" class="btn btn-success position-fixed bottom-0 end-0 m-3 d-none">
  <i class="fas fa-arrow-up"></i>
</button>

<!-- ========================= -->
<!-- FOOTER -->
<!-- ========================= -->
<footer class="bg-dark text-white py-4 mt-5">
  <div class="container text-center">
    <p>&copy; <?= date("Y") ?> CV Generator. Tous droits réservés.</p>
    <p>
      <a href="privacy.php" class="text-white text-decoration-none">Politique de confidentialité</a> |
      <a href="terms.php" class="text-white text-decoration-none">Conditions d'utilisation</a>
    </p>
  </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Script Back to top et smooth scroll -->
<script>
document.addEventListener('DOMContentLoaded', function(){
  const backBtn = document.getElementById('back-to-top');
  window.addEventListener('scroll', () => { backBtn.classList.toggle('d-none', window.scrollY < 300); });
  backBtn.addEventListener('click', () => { window.scrollTo({top:0, behavior:'smooth'}); });
  document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', function(e){
      e.preventDefault();
      document.querySelector(this.getAttribute('href')).scrollIntoView({behavior:'smooth'});
    });
  });
});
</script>
</body>
</html>
