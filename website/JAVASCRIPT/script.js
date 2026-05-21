// Attend que le contenu HTML soit complètement chargé
document.addEventListener('DOMContentLoaded', () => {
    
    // --- 1. Gestion du panier (localStorage) ---
    let panier = JSON.parse(localStorage.getItem('panier')) || [];
    const badgePanier = document.getElementById('panier-badge');
    
    // Mise à jour du badge du panier
    function mettreAJourBadgePanier() {
        const nombreArticles = panier.reduce((total, item) => total + item.quantite, 0);
        if (badgePanier) {
            badgePanier.textContent = nombreArticles;
        }
    }
    
    // Initialiser le badge
    mettreAJourBadgePanier();
    
    // Boutons "Ajouter au panier"
    const boutonsAjoutPanier = document.querySelectorAll('.bouton-panier');
    boutonsAjoutPanier.forEach(bouton => {
        bouton.addEventListener('click', (e) => {
            e.preventDefault();
            
            const carte = bouton.closest('.carte-produit');
            const nomProduit = carte ? carte.querySelector('.nom-produit').textContent : 'Produit';
            const prix = carte ? parseFloat(carte.querySelector('.prix').textContent) : 0;
            
            const produitExistant = panier.find(item => item.nom === nomProduit);
            if (produitExistant) {
                produitExistant.quantite++;
            } else {
                panier.push({
                    id: Date.now(),
                    nom: nomProduit,
                    prix: prix,
                    quantite: 1
                });
            }
            
            localStorage.setItem('panier', JSON.stringify(panier));
            mettreAJourBadgePanier();
            
            alert('Produit ajoute au panier !');
        });
    });
    
    // Bouton "Ajouter au panier" sur page produit
    const boutonAjouterProduit = document.getElementById('ajouter-panier');
    if (boutonAjouterProduit) {
        boutonAjouterProduit.addEventListener('click', (e) => {
            e.preventDefault();
            const quantite = parseInt(document.getElementById('quantite-produit').value);
            const titre = document.querySelector('.detail-produit h1').textContent;
            const prix = parseFloat(document.querySelector('.produit-prix .prix-actuel').textContent);
            
            for (let i = 0; i < quantite; i++) {
                panier.push({
                    id: Date.now() + i,
                    nom: titre,
                    prix: prix,
                    quantite: 1
                });
            }
            
            localStorage.setItem('panier', JSON.stringify(panier));
            mettreAJourBadgePanier();
            alert('Produit ajoute au panier !');
        });
    }

    // --- 2. Affichage du panier sur page panier.html ---
    const tableauPanier = document.getElementById('tableau-panier');
    const panierVide = document.getElementById('panier-vide');
    const panierResume = document.getElementById('panier-resume');
    const articlesPanier = document.getElementById('articles-panier');
    
    if (tableauPanier && articlesPanier) {
        if (panier.length === 0) {
            tableauPanier.style.display = 'none';
            if (panierResume) panierResume.style.display = 'none';
        } else {
            panierVide.style.display = 'none';
            tableauPanier.style.display = 'table';
            if (panierResume) panierResume.style.display = 'block';
            
            let sousTotal = 0;
            articlesPanier.innerHTML = '';
            
            panier.forEach((article, index) => {
                const total = article.prix * article.quantite;
                sousTotal += total;
                
                const ligne = `
                    <tr>
                        <td>${article.nom}</td>
                        <td>${article.prix.toFixed(2)} EUR</td>
                        <td>${article.quantite}</td>
                        <td>${total.toFixed(2)} EUR</td>
                        <td><button class="btn-supprimer" data-index="${index}">Supprimer</button></td>
                    </tr>
                `;
                articlesPanier.insertAdjacentHTML('beforeend', ligne);
            });
            
            // Calculer frais de livraison
            const fraisLivraison = sousTotal > 50 ? 0 : 9.99;
            const totalTTC = sousTotal + fraisLivraison;
            
            if (document.getElementById('sous-total')) {
                document.getElementById('sous-total').textContent = sousTotal.toFixed(2) + ' EUR';
            }
            if (document.getElementById('frais-livraison')) {
                document.getElementById('frais-livraison').textContent = fraisLivraison === 0 ? 'Gratuit' : fraisLivraison.toFixed(2) + ' EUR';
            }
            if (document.getElementById('total-ttc')) {
                document.getElementById('total-ttc').textContent = totalTTC.toFixed(2) + ' EUR';
            }
            
            // Boutons supprimer
            document.querySelectorAll('.btn-supprimer').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const index = parseInt(e.target.getAttribute('data-index'));
                    panier.splice(index, 1);
                    localStorage.setItem('panier', JSON.stringify(panier));
                    location.reload();
                });
            });
        }
    }

    // --- 3. Animation de la barre de recherche ---
    const champRecherche = document.querySelector('.champ-recherche');
    const conteneurRecherche = document.querySelector('.conteneur-recherche');
    
    if (champRecherche && conteneurRecherche) {
        champRecherche.addEventListener('focus', () => {
            conteneurRecherche.style.boxShadow = '0 0 10px rgba(0, 0, 0, 0.15)';
        });
        
        champRecherche.addEventListener('blur', () => {
            conteneurRecherche.style.boxShadow = 'none';
        });
    }
    
    // --- 4. Interaction sur le bouton Profil ---
    const boutonProfil = document.getElementById('bouton-profil');
    if (boutonProfil) {
        const isInHtmlFolder = window.location.pathname.includes('/HTML/');
        const fetchUrl = isInHtmlFolder ? '../PHP/traiter-connexion.php?action=profil' : 'PHP/traiter-connexion.php?action=profil';
        
        fetch(fetchUrl)
            .then(res => res.json())
            .then(data => {
                if (data.success && data.client && data.client.prenom) {
                    boutonProfil.innerHTML = `👤<span style="font-size: 13px; font-weight: 600; margin-left: 2px;">${data.client.prenom}</span>`;
                    boutonProfil.addEventListener('click', () => {
                        window.location.href = isInHtmlFolder ? 'mon-compte.html' : 'HTML/mon-compte.html';
                    });
                } else {
                    boutonProfil.innerHTML = `👤<span style="font-size: 13px; font-weight: 600; margin-left: 2px;">Connexion</span>`;
                    boutonProfil.addEventListener('click', () => {
                        window.location.href = isInHtmlFolder ? 'connexion.html' : 'HTML/connexion.html';
                    });
                }
            })
            .catch(() => {
                boutonProfil.innerHTML = `👤<span style="font-size: 13px; font-weight: 600; margin-left: 2px;">Connexion</span>`;
                boutonProfil.addEventListener('click', () => {
                    window.location.href = isInHtmlFolder ? 'connexion.html' : 'HTML/connexion.html';
                });
            });
    }
    
    // --- 5. Accordeon FAQ ---
    const faqItems = document.querySelectorAll('.faq-item');
    faqItems.forEach(item => {
        const titre = item.querySelector('.faq-titre');
        const contenu = item.querySelector('.faq-contenu');
        
        if (titre && contenu) {
            titre.addEventListener('click', () => {
                const estOuvert = contenu.style.display === 'block';
                
                faqItems.forEach(i => {
                    const c = i.querySelector('.faq-contenu');
                    if (c) c.style.display = 'none';
                });
                
                contenu.style.display = estOuvert ? 'none' : 'block';
                titre.classList.toggle('active');
            });
        }
    });
    
    // --- 7. Validation formulaire inscription ---
    const formInscription = document.getElementById('form-inscription');
    if (formInscription) {
        formInscription.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const mdp = document.getElementById('mot_de_passe_inscription').value;
            const confirmer = document.getElementById('confirmer_mdp').value;
            
            if (mdp !== confirmer) {
                alert('Les mots de passe ne correspondent pas');
                return;
            }
            
            const formData = new FormData(formInscription);
            const actionUrl = formInscription.getAttribute('action') || '../PHP/traiter-inscription.php';
            try {
                const response = await fetch(actionUrl, {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                if (result.success) {
                    alert(result.message);
                    const isInHtmlFolder = window.location.pathname.includes('/HTML/');
                    let redirectPage = result.redirect || 'connexion.html';
                    window.location.href = isInHtmlFolder ? redirectPage : 'HTML/' + redirectPage;
                } else {
                    alert(result.message);
                }
            } catch (error) {
                console.error('Erreur:', error);
            }
        });
    }

    // --- 8. Validation formulaire connexion ---
    const formConnexion = document.getElementById('form-connexion');
    if (formConnexion) {
        formConnexion.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData(formConnexion);
            const actionUrl = formConnexion.getAttribute('action') || '../PHP/traiter-connexion.php';
            try {
                const response = await fetch(actionUrl, {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                if (result.success) {
                    const isInHtmlFolder = window.location.pathname.includes('/HTML/');
                    let redirectPage = result.redirect || 'mon-compte.html';
                    window.location.href = isInHtmlFolder ? redirectPage : 'HTML/' + redirectPage;
                } else {
                    alert(result.message);
                }
            } catch (error) {
                console.error('Erreur:', error);
                alert('Une erreur est survenue lors de la connexion.');
            }
        });
    }

    // --- 9. Récupération des infos du profil utilisateur ---
    const pageMonCompte = document.querySelector('.mon-compte-section');
    if (pageMonCompte) {
        const isInHtmlFolder = window.location.pathname.includes('/HTML/');
        const fetchUrl = isInHtmlFolder ? '../PHP/traiter-connexion.php?action=profil' : 'PHP/traiter-connexion.php?action=profil';
        
        fetch(fetchUrl)
            .then(res => res.json())
            .then(data => {
                if (data.success && data.client) {
                    const prenomEl = document.getElementById('profil-prenom');
                    const nomEl = document.getElementById('profil-nom');
                    const emailEl = document.getElementById('profil-email');
                    const adresseEl = document.getElementById('profil-adresse');
                    
                    if (prenomEl) prenomEl.textContent = data.client.prenom || '';
                    if (nomEl) nomEl.textContent = data.client.nom || '';
                    if (emailEl) emailEl.textContent = data.client.email || '';
                    if (adresseEl) {
                        const adresseFormattee = data.client.adresse_livraison 
                            ? data.client.adresse_livraison.replace(/\n/g, '<br/>') 
                            : 'Aucune adresse renseignée';
                        adresseEl.innerHTML = `${data.client.prenom || ''} ${data.client.nom || ''}<br/>${adresseFormattee}`;
                    }
                } else {
                    // Utilisateur non connecté, rediriger vers connexion
                    window.location.href = window.location.pathname.includes('/HTML/') ? 'connexion.html' : 'HTML/connexion.html';
                }
            })
            .catch(error => {
                console.error('Erreur profil:', error);
            });
    }
});