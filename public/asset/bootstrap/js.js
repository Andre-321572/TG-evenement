const chargement = document.getElementById("chargement");
const contenu = document.getElementById("contenu");

// Affiche la page de chargement
setTimeout(() => {
  chargement.classList.add("d-none");
  contenu.classList.remove("d-none");
}, 200); // Durée de chargement simulée (2 secondes)

// Remplacez `2000` par le temps de chargement réel de votre page
const prevBtn = document.getElementById("prev-btn");
const nextBtn = document.getElementById("next-btn");

if (prevBtn && nextBtn) {
    let currentSlide = 0; // Index de l'élément actuellement affiché
    const slides = document.querySelectorAll(".row .col-md-4"); // Liste de tous les éléments

    if (slides.length > 0) {
        prevBtn.addEventListener("click", () => {
            if (currentSlide > 0) {
                currentSlide--;
                showSlide(currentSlide);
            }
        });

        nextBtn.addEventListener("click", () => {
            if (currentSlide < slides.length - 3) {
                currentSlide++;
                showSlide(currentSlide);
            }
        });

        function showSlide(index) {
            const container = document.querySelector(".row");
            const slideWidth = slides[0].offsetWidth;
            if (container && slides[0]) {
                container.style.transform = `translateX(-${index * slideWidth}px)`;
            }
        }

        showSlide(currentSlide); // Affiche l'élément initial
    }
}

