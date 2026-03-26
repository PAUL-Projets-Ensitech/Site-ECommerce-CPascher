// Attend que le contenu HTML soit complètement chargé
document.addEventListener('DOMContentLoaded', () => {
    
    // --- 1. Gestion du panier ---
    const boutonsAjoutPanier = document.querySelectorAll('.bouton-panier');
    const badgePanier = document.getElementById('panier-badge');
    let nombreArticles = 0;

    boutonsAjoutPanier.forEach(bouton => {
        bouton.addEventListener('click', () => {
            // Incrémente le nombre d'articles
            nombreArticles++;
            // Met à jour le texte du badge
            badgePanier.textContent = nombreArticles;
            
            // Affiche une confirmation simple
            alert('Produit ajouté au panier !');
        });
    });

    // --- 2. Animation de la barre de recherche ---
    const champRecherche = document.querySelector('.champ-recherche');
    const conteneurRecherche = document.querySelector('.conteneur-recherche');
    
    champRecherche.addEventListener('focus', () => {
        conteneurRecherche.style.boxShadow = '0 0 10px rgba(0, 0, 0, 0.15)';
    });
    
    champRecherche.addEventListener('blur', () => {
        conteneurRecherche.style.boxShadow = 'none';
    });
    
    // --- 3. Interaction sur le bouton Profil ---
    const boutonProfil = document.getElementById('bouton-profil');
    if(boutonProfil) {
        boutonProfil.addEventListener('click', () => {
            alert('Connexion / Inscription (Fonctionnalité à venir)');
        });
    }

});