<!DOCTYPE html>
<html lang="fr">
<head>
    <!-- META -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CV Generator | Créez votre CV professionnel</title>

    <!-- SEO -->
    <meta name="description" content="Créez un CV professionnel moderne en quelques minutes. Aperçu en temps réel et export PDF.">
    <meta name="keywords" content="CV, générateur de CV, CV PDF, curriculum vitae">
    <meta name="author" content="CV Generator">

    <!-- FAVICON -->
    <link rel="icon" href="assets/logo.png">

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FONT AWESOME -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">

    <!-- GOOGLE FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>

<!-- ================= HEADER ================= -->
<header class="hero text-white">
    <nav class="navbar navbar-expand-lg navbar-dark container">
        <a class="navbar-brand d-flex align-items-center" href="#">
            <img src="assets/logo.png" width="40" class="me-2" alt="Logo">
            CV Generator
        </a>
    </nav>

    <div class="container text-center py-5">
        <h1 class="fw-bold display-5">
            Créez votre <span class="gradient-text">CV professionnel</span>
        </h1>
        <p class="lead mt-3">
            Modèles modernes • Aperçu en temps réel • Export PDF
        </p>
        <a href="#cv-generator" class="btn btn-light btn-lg mt-4">
            Commencer maintenant
        </a>
    </div>
</header>

<!-- ================= MAIN ================= -->
<main class="container my-5" id="cv-generator">

    <div class="row g-4">
        <!-- FORMULAIRE -->
        <div class="col-lg-7">
            <div class="card shadow-sm p-4">
                <h2 class="h4 mb-4">
                    <i class="fas fa-user-edit me-2 text-primary"></i>
                    Informations personnelles
                </h2>

                <form id="cv-form">
                    <div class="mb-3">
                        <label class="form-label">Nom complet</label>
                        <input type="text" class="form-control" id="name" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Téléphone</label>
                        <input type="text" class="form-control" id="phone">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Compétences</label>
                        <textarea class="form-control" id="skills" rows="3"
                            placeholder="HTML, CSS, JavaScript..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        Générer le CV
                    </button>
                </form>
            </div>
        </div>

        <!-- PREVIEW -->
        <div class="col-lg-5">
            <div class="card shadow-sm p-4 h-100">
                <h2 class="h4 mb-3">
                    <i class="fas fa-eye me-2 text-primary"></i>
                    Aperçu du CV
                </h2>

                <div id="cv-preview" class="preview-box">
                    <p class="text-muted">Remplissez le formulaire pour voir l’aperçu.</p>
                </div>
            </div>
        </div>
    </div>

</main>

<!-- ================= FOOTER ================= -->
<footer class="footer text-white mt-5">
    <div class="container text-center py-4">
        <p class="mb-1">© <?php echo date("Y"); ?> CV Generator</p>
        <small class="text-white-50">
            Simple • Rapide • Professionnel
        </small>
    </div>
</footer>

<!-- ================= SCRIPTS ================= -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/script.js"></script>

</body>
</html>

