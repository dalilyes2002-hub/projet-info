// ==========================================
// Générateur de CV – Script JavaScript Pro
// ==========================================

document.addEventListener("DOMContentLoaded", () => {

    /* ===============================
       UTILITAIRES
    ================================ */
    const $ = (id) => document.getElementById(id);
    const $$ = (selector) => document.querySelectorAll(selector);

    const debounce = (func, delay = 300) => {
        let timeout;
        return (...args) => {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), delay);
        };
    };

    const showMessage = (text, type = "success") => {
        const msg = document.createElement("div");
        msg.className = `message ${type}`;
        msg.textContent = text;
        document.body.appendChild(msg);
        setTimeout(() => msg.remove(), 3000);
    };

    /* ===============================
       1. Smooth Scroll
    ================================ */
    $$('a[href^="#"]').forEach(link => {
        link.addEventListener("click", e => {
            e.preventDefault();
            const target = document.querySelector(link.getAttribute("href"));
            if (target) {
                window.scrollTo({
                    top: target.offsetTop - 70,
                    behavior: "smooth"
                });
                target.focus();
            }
        });
    });

    /* ===============================
       2. Live Preview du CV
    ================================ */
    $$(".cv-input").forEach(input => {
        const updatePreview = debounce(() => {
            const target = $(input.dataset.preview);
            if (target) {
                target.textContent = input.value.trim() || "—";
                target.classList.add("updated");
                setTimeout(() => target.classList.remove("updated"), 300);
            }
        });
        input.addEventListener("input", updatePreview);
    });

    /* ===============================
       3. Gestion des Compétences
    ================================ */
    const skillsInput = $("skills");
    const skillsPreview = $("cv-skills");
    const addSkillBtn = $("addSkill");

    const updateSkills = () => {
        if (!skillsPreview || !skillsInput) return;
        skillsPreview.innerHTML = "";

        const skills = skillsInput.value
            .split(",")
            .map(s => s.trim())
            .filter(Boolean);

        skills.forEach((skill, index) => {
            const li = document.createElement("li");
            li.textContent = skill;

            const removeBtn = document.createElement("button");
            removeBtn.textContent = "×";
            removeBtn.className = "remove-skill";
            removeBtn.onclick = () => {
                skills.splice(index, 1);
                skillsInput.value = skills.join(", ");
                updateSkills();
            };

            li.appendChild(removeBtn);
            skillsPreview.appendChild(li);
        });
    };

    if (addSkillBtn) {
        addSkillBtn.addEventListener("click", () => {
            const value = skillsInput.value.trim();
            if (value) {
                skillsInput.value += ", ";
                updateSkills();
                skillsInput.value = "";
            }
        });
        skillsInput.addEventListener("input", updateSkills);
    }

    /* ===============================
       4. Photo de Profil
    ================================ */
    const photoInput = $("photo");
    const photoPreview = $("cv-photo");

    if (photoInput && photoPreview) {
        photoInput.addEventListener("change", () => {
            const file = photoInput.files[0];
            if (!file) return;

            const validTypes = ["image/jpeg", "image/png"];
            if (!validTypes.includes(file.type)) {
                showMessage("Format invalide (JPG ou PNG)", "error");
                return;
            }

            if (file.size > 5 * 1024 * 1024) {
                showMessage("Image trop grande (max 5MB)", "error");
                return;
            }

            photoPreview.src = URL.createObjectURL(file);
        });
    }

    /* ===============================
       5. Réinitialisation
    ================================ */
    const resetBtn = $("resetCV");
    const form = $("cvForm");

    if (resetBtn && form) {
        resetBtn.addEventListener("click", () => {
            if (confirm("Réinitialiser le CV ?")) {
                form.reset();
                $$(".cv-preview span, .cv-preview p").forEach(el => el.textContent = "—");
                if (photoPreview) photoPreview.src = "";
                if (skillsPreview) skillsPreview.innerHTML = "";
            }
        });
    }

    /* ===============================
       6. Responsive Mobile
    ================================ */
    const adjustLayout = () => {
        const container = document.querySelector(".cv-container");
        if (!container) return;

        container.classList.toggle("mobile", window.innerWidth < 768);
    };

    adjustLayout();
    window.addEventListener("resize", adjustLayout);

    /* ===============================
       7. Lazy Loading Images
    ================================ */
    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove("lazy");
                observer.unobserve(img);
            }
        });
    });

    $$("img.lazy").forEach(img => observer.observe(img));

    /* ===============================
       8. Export PDF
    ================================ */
    const exportBtn = $("exportPDF");

    if (exportBtn) {
        exportBtn.addEventListener("click", () => {
            const element = document.querySelector(".cv-preview");
            if (!element) return;

            html2pdf().set({
                margin: 0.5,
                filename: "mon-cv.pdf",
                html2canvas: { scale: 2 },
                jsPDF: { format: "a4", orientation: "portrait" }
            }).from(element).save();
        });
    }

    /* ===============================
       9. Validation Formulaire
    ================================ */
    if (form) {
        form.addEventListener("submit", e => {
            e.preventDefault();
            const required = ["name", "email"];
            let valid = true;

            required.forEach(id => {
                const field = $(id);
                if (!field.value.trim()) {
                    field.classList.add("error");
                    valid = false;
                } else {
                    field.classList.remove("error");
                }
            });

            valid
                ? showMessage("CV validé, prêt à exporter 🎉")
                : showMessage("Champs obligatoires manquants", "error");
        });
    }

});

 