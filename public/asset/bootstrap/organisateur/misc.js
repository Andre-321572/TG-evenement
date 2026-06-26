// Récupère l'URL de la page actuelle
var currentUrl = window.location.href;

// Sélectionne tous les liens de la barre de navigation
var navLinks = document.querySelectorAll('.nav-link');

// Parcourt chaque lien de la barre de navigation
navLinks.forEach(function(navLink) {
    // Récupère l'URL liée au lien
    var linkUrl = navLink.getAttribute('href');

    // Vérifie si l'URL de la page actuelle correspond à l'URL du lien
    if (currentUrl === linkUrl) {
        // Ajoute la classe 'active' au parent du lien (li)
        navLink.parentNode.classList.add('active');
    }
});
