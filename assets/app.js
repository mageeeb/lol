import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

    document.addEventListener("visibilitychange", function() {
    if (document.hidden) {
    console.log("L'utilisateur a changé d'onglet.");
    // Arrêter ici certaines fonctionnalités
} else {
    console.log("L'utilisateur est revenu sur cet onglet.");
    // Reprendre les actions vitales ici
}
});

