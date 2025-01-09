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

document.addEventListener('DOMContentLoaded', () => {
    const collectionHolder = document.querySelector('#notes-collection');
    const addButton = document.querySelector('#add-note-btn');
    let index = collectionHolder.children.length;

    // Ajoute un événement au bouton "Ajouter une note"
    addButton.addEventListener('click', () => {
        const prototype = collectionHolder.dataset.prototype;
        const newForm = prototype.replace(/__name__/g, index);

        const newFormItem = document.createElement('div');
        newFormItem.classList.add('note-item', 'mb-3');
        newFormItem.innerHTML = newForm;

        collectionHolder.appendChild(newFormItem);
        index++;
    });
});

