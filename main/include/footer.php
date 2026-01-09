<!-- ================= FOOTER ================= -->
<footer class="bg-dark text-white py-5" role="contentinfo">
    <div class="container-fluid">
        <div class="row g-4">

            <!-- À propos -->
            <div class="col-md-4 animate-fade-in">
                <h5 class="text-primary fw-bold mb-3">
                    <i class="fas fa-file-alt me-2"></i>CV Generator
                </h5>
                <p class="small text-white-50">
                    Créez un CV professionnel en quelques minutes.
                    Simple, rapide et optimisé pour le recrutement.
                </p>
                <span class="badge bg-success">
                    <i class="fas fa-shield-alt me-1"></i>Sécurisé SSL
                </span>
            </div>

            <!-- Liens rapides -->
            <div class="col-md-4">
                <h5 class="text-primary mb-3">
                    <i class="fas fa-link me-2"></i>Liens rapides
                </h5>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="index.php" class="text-white text-decoration-none"
                           data-bs-toggle="tooltip" title="Accueil">
                            <i class="fas fa-home me-2"></i>Accueil
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#generator" class="text-white text-decoration-none"
                           data-bs-toggle="tooltip" title="Générateur">
                            <i class="fas fa-cogs me-2"></i>Générateur de CV
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#templates" class="text-white text-decoration-none"
                           data-bs-toggle="tooltip" title="Modèles">
                            <i class="fas fa-th-large me-2"></i>Modèles
                        </a>
                    </li>
                    <li>
                        <a href="login.php" class="text-white text-decoration-none"
                           data-bs-toggle="tooltip" title="Connexion">
                            <i class="fas fa-user me-2"></i>Connexion
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Contact -->
            <div class="col-md-4">
                <h5 class="text-primary mb-3">
                    <i class="fas fa-envelope me-2"></i>Contact
                </h5>

                <p>
                    <i class="fas fa-envelope me-2 text-white-50"></i>
                    <a href="mailto:support@cv-generator.com" class="text-white">
                        support@cv-generator.com
                    </a>
                </p>

                <p>
                    <i class="fas fa-globe me-2 text-white-50"></i>
                    <a href="#" class="text-white">www.cv-generator.com</a>
                </p>

                <h6 class="text-primary mt-3">Suivez-nous</h6>
                <div class="d-flex gap-3">
                    <a href="#" class="text-white"><i class="fab fa-facebook-f fa-lg"></i></a>
                    <a href="#" class="text-white"><i class="fab fa-twitter fa-lg"></i></a>
                    <a href="#" class="text-white"><i class="fab fa-linkedin-in fa-lg"></i></a>
                </div>
            </div>
        </div>

        <!-- Newsletter -->
        <div class="row mt-4 text-center">
            <h6 class="text-primary mb-2">Restez informé</h6>
            <form id="newsletterForm" class="d-flex justify-content-center gap-2 flex-wrap">
                <input type="email" class="form-control w-auto" placeholder="Votre email" required>
                <button class="btn btn-primary">S'abonner</button>
            </form>
            <small class="text-white-50 mt-2">Pas de spam.</small>
        </div>

        <hr class="my-4 bg-secondary">

        <!-- Bas footer -->
        <div class="d-flex justify-content-between flex-wrap align-items-center">
            <small class="text-white-50">
                © <?php echo date("Y"); ?> CV Generator — Tous droits réservés
            </small>

            <div class="d-flex gap-2">
                <button id="themeToggle" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-moon"></i>
                </button>
                <button id="backToTop" class="btn btn-outline-light btn-sm" style="display:none;">
                    <i class="fas fa-arrow-up"></i>
                </button>
            </div>
        </div>
    </div>
</footer>

<!-- ================= SCRIPTS ================= -->

<!-- Font Awesome (OBLIGATOIRE pour les icônes) -->
<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<!-- Bootstrap JS (⚠️ SANS async sinon bug tooltips) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>

<!-- Script principal -->
<script src="assets/script.js" defer></script>

<!-- Script footer -->
<script>
document.addEventListener("DOMContentLoaded", function () {

    /* Tooltips */
    document.querySelectorAll('[data-bs-toggle="tooltip"]')
        .forEach(el => new bootstrap.Tooltip(el));

    /* Mode sombre */
    const toggle = document.getElementById("themeToggle");
    toggle.addEventListener("click", () => {
        document.body.classList.toggle("dark-mode");
        toggle.querySelector("i").classList.toggle("fa-sun");
        toggle.querySelector("i").classList.toggle("fa-moon");
    });

    /* Retour en haut */
    const backToTop = document.getElementById("backToTop");
    window.addEventListener("scroll", () => {
        backToTop.style.display = window.scrollY > 300 ? "block" : "none";
    });
    backToTop.addEventListener("click", () => {
        window.scrollTo({ top: 0, behavior: "smooth" });
    });

    /* Newsletter */
    document.getElementById("newsletterForm").addEventListener("submit", e => {
        e.preventDefault();
        alert("Merci pour votre inscription !");
        e.target.reset();
    });

});
</script>

