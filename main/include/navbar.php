<!-- ========================= -->
<!-- NAVBAR PRINCIPALE -->
<!-- ========================= -->
<nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNavbar" role="navigation" aria-label="Navigation principale">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand d-flex align-items-center" href="index.php" aria-label="Accueil CV Generator">
            <i class="fas fa-file-alt text-primary me-2" aria-hidden="true"></i>
            <span class="fw-bold text-dark">CV Generator</span>
        </a>

        <!-- Toggle mobile -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav" aria-controls="navbarNav"
                aria-expanded="false" aria-label="Ouvrir le menu de navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menu -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item mx-2">
                    <a class="nav-link fw-medium <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php' && !isset($_GET['section'])) ? 'active' : ''; ?>" 
                       href="index.php" aria-current="<?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'page' : 'false'; ?>">Accueil</a>
                </li>
                <li class="nav-item mx-2">
                    <a class="nav-link fw-medium <?php echo (isset($_GET['section']) && $_GET['section'] == 'generator') ? 'active' : ''; ?>" 
                       href="index.php#generator">Générateur</a>
                </li>
                <li class="nav-item mx-2">
                    <a class="nav-link fw-medium <?php echo (isset($_GET['section']) && $_GET['section'] == 'templates') ? 'active' : ''; ?>" 
                       href="index.php#templates">Modèles</a>
                </li>
                <li class="nav-item mx-2">
                    <a class="nav-link fw-medium <?php echo (isset($_GET['section']) && $_GET['section'] == 'export') ? 'active' : ''; ?>" 
                       href="index.php#export">Export PDF</a>
                </li>
            </ul>

            <!-- Utilisateur -->
            <ul class="navbar-nav">
                <?php if (isset($_SESSION['utilisateur_id'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle fw-medium" href="#" id="navbarDropdown"
                           role="button" data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
                            <i class="fas fa-user me-1" aria-hidden="true"></i>
                            <?php echo htmlspecialchars($_SESSION['nom_complet'] ?? 'Utilisateur'); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="navbarDropdown">
                            <li>
                                <a class="dropdown-item" href="dashboard.php">
                                    <i class="fas fa-id-card me-2"></i>Mes CV
                                </a>
                            </li>
                            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                <li>
                                    <a class="dropdown-item" href="admin.php">
                                        <i class="fas fa-cog me-2"></i>Administration
                                    </a>
                                </li>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="logout.php">
                                    <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link fw-medium" href="login.php">Connexion</a>
                    </li>
                    <li class="nav-item ms-2">
                        <a class="btn btn-primary btn-sm px-3 rounded-pill" href="inscription.php">
                            Créer un compte
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- ========================= -->
<!-- STYLES NAVBAR -->
<!-- ========================= -->
<style>
/* Navbar fixe avec blur */
#mainNavbar {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border-bottom: 1px solid rgba(0, 0, 0, 0.08);
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    padding: 0.75rem 0;
    transition: all 0.3s ease;
    z-index: 1030;
}

/* Navbar après scroll */
#mainNavbar.scrolled {
    background: rgba(255, 255, 255, 0.98);
    box-shadow: 0 4px 25px rgba(0, 0, 0, 0.15);
    padding: 0.5rem 0;
}

.navbar-brand {
    font-size: 1.5rem;
    transition: transform 0.2s ease;
}

.navbar-brand:hover,
.navbar-brand:focus {
    transform: translateY(-1px);
    outline: 2px solid #2563eb;
    outline-offset: 2px;
}

.nav-link {
    color: #2d3748 !important;
    font-weight: 500;
    position: relative;
    transition: all 0.3s ease;
    padding: 0.5rem 1rem !important;
}

.nav-link:hover,
.nav-link:focus {
    color: #2563eb !important;
    transform: translateY(-1px);
    outline: 2px solid #2563eb;
    outline-offset: 2px;
}

.nav-link.active {
    color: #2563eb !important;
    font-weight: 600;
}

.nav-link::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    width: 0;
    height: 2px;
    background: linear-gradient(90deg, #2563eb, #3b82f6);
    transition: all 0.3s ease;
    transform: translateX(-50%);
}

.nav-link:hover::after,
.nav-link.active::after {
    width: 80%;
}

.btn-primary {
    background: linear-gradient(135deg, #2563eb, #3b82f6);
    border: none;
    transition: all 0.3s ease;
}

.btn-primary:hover,
.btn-primary:focus {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.35);
    outline: 2px solid #2563eb;
    outline-offset: 2px;
}

.dropdown-menu {
    border-radius: 8px;
    border: none;
    box-shadow: 0 4px 25px rgba(0, 0, 0, 0.15);
    animation: fadeIn 0.2s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

.dropdown-item:hover,
.dropdown-item:focus {
    background-color: #f8f9fa;
    color: #2563eb;
    transform: translateX(2px);
    outline: 2px solid #2563eb;
    outline-offset: -2px;
}

@media (max-width: 991.98px) {
    .navbar-collapse {
        background: white;
        border-radius: 0 0 12px 12px;
        padding: 1.5rem;
        margin-top: 1rem;
        box-shadow: 0 4px 25px rgba(0, 0, 0, 0.15);
    }

    .nav-link {
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        padding: 0.75rem 0 !important;
    }

    .btn-primary {
        width: 100%;
        margin-top: 1rem;
    }

    .dropdown-menu {
        position: static;
        float: none;
        width: 100%;
        box-shadow: none;
        border: 1px solid rgba(0, 0, 0, 0.1);
    }
}

@media (prefers-color-scheme: dark) {
    #mainNavbar {
        background: rgba(0, 0, 0, 0.95);
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .nav-link {
        color: #e2e8f0 !important;
    }

    .nav-link:hover {
        color: #60a5fa !important;
    }
}
</style>

<!-- ========================= -->
<!-- SCRIPT NAVBAR -->
<!-- ========================= -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const navbar = document.getElementById('mainNavbar');
    if (!navbar) return;

    // Changer style au scroll
    window.addEventListener('scroll', () => {
        navbar.classList.toggle('scrolled', window.scrollY > 50);
    });

    // Smooth scroll pour ancres
    document.querySelectorAll('a[href^="#"]').forEach(link => {
        link.addEventListener('click', e => {
            const href = link.getAttribute('href');
            const target = document.querySelector(href);
            if (!target) return;
            e.preventDefault();

            const offsetTop = target.offsetTop - navbar.offsetHeight;
            window.scrollTo({ top: offsetTop, behavior: 'smooth' });

            // Fermer menu mobile
            const collapse = document.getElementById('navbarNav');
            if (collapse.classList.contains('show')) {
                const bsCollapse = new bootstrap.Collapse(collapse, { toggle: false });
                bsCollapse.hide();
            }
        });
    });

    // Accessibilité clavier pour nav-link & dropdown
    document.querySelectorAll('.nav-link, .dropdown-item').forEach(item => {
        item.addEventListener('keydown', e => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                item.click();
            }
        });
    });
});
</script>

