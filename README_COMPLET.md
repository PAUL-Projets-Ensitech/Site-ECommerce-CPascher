# CPasCher - Plateforme E-Commerce Complète

## 🎯 Vue d'ensemble du projet

**CPasCher** est une plateforme de e-commerce profesionnelle développée dans le cadre d'une formation en développement web. CPasCher signifie "CDiscount en beaucoup moins cher".

### Points clés
- ✅ **12 pages HTML distinctes** (minimum 10 requis)
- ✅ **Formulaires dynamiques** avec validation PHP
- ✅ **Panier persistant** avec localStorage
- ✅ **Base de données** MySQL complète
- ✅ **Interfaces interactives** avec JavaScript
- ✅ **Design responsive** et moderne

---

## 📱 Pages disponibles

### Pages principales
1. **index.html** - Accueil avec présentation et ventes flash
2. **categorie.html** - Catalogue organisé par catégories
3. **produit.html** - Détail complet d'un produit
4. **panier.html** - Gestion du panier d'achat
5. **commande.html** - Formulaire de commande avec paiement
6. **confirmation.html** - Récapitulatif après commande

### Pages utilisateur
7. **connexion.html** - Formulaire de connexion
8. **inscription.html** - Création de compte client
9. **mon-compte.html** - Profil et historique utilisateur

### Pages informatives
10. **contact.html** - Formulaire de contact support
11. **a-propos.html** - Présentation de l'entreprise
12. **faq.html** - Questions fréquentes (accordéon dynamique)
13. **mentions-legales.html** - Informations légales RGPD
14. **conditions-generales.html** - CGV e-commerce

---

## 💻 Technologies utilisées

### Frontend
- HTML5 (sémantique W3C)
- CSS3 (Flexbox, Grid, variables)
- JavaScript ES6+ (localStorage, async/await)

### Backend
- PHP 7.4+ (OOP avec PDO)
- MySQL (base de données relationnelle)

### Design
- Figma (conception)
- Google Fonts (Plus Jakarta Sans, Inter)
- Responsive mobile-first

---

## 🎨 Fonctionnalités principales

### 🛒 Panier
- Ajout/suppression de produits
- Calcul automatique du total TTC
- Frais de livraison (gratuit si >50€)
- Persistance via localStorage
- Badge de nombre d'articles

### 👤 Authentification
- Inscription avec email unique
- Hachage sécurisé des mots de passe
- Connexion avec session
- Récupération d'adresse cliente

### 📋 Formulaires
- Contact avec sujet catégorisé
- Commande avec validation
- Inscription complète
- Tous validés côté client et serveur

### 📊 Interactions JavaScript
- Accordéon FAQ (toggle)
- Gestion dynamique du panier
- Barre de recherche focus/blur
- Redirection profil
- Envoi asynchrone formulaires

### 🗄️ Base de données
- 6 tables avec relations
- Requêtes préparées (sécurité)
- Données persistantes
- Gestion des commandes

---

## 📁 Structure de fichiers

```
├── HTML (14 fichiers)
│   ├── index.html
│   ├── categorie.html
│   ├── produit.html
│   ├── panier.html
│   ├── connexion.html
│   ├── inscription.html
│   ├── commande.html
│   ├── confirmation.html
│   ├── contact.html
│   ├── mon-compte.html
│   ├── a-propos.html
│   ├── faq.html
│   ├── mentions-legales.html
│   └── conditions-generales.html
│
├── CSS/JavaScript
│   ├── style.css (plus de 1000 lignes)
│   └── script.js (300+ lignes avec interactions)
│
├── PHP (5 fichiers)
│   ├── config.php
│   ├── traiter-contact.php
│   ├── traiter-connexion.php
│   ├── traiter-inscription.php
│   └── traiter-commande.php
│
├── Base de données
│   └── private/Site-ECommerce-CPascher.sql
│
└── Documentation
    └── README_COMPLET.md (ce fichier)
```

---

## 🔐 Sécurité

- ✅ Requêtes préparées (PDO) contre injections SQL
- ✅ Hachage bcrypt des mots de passe
- ✅ Validation HTML des données
- ✅ Structure de session PHP
- ✅ Respect RGPD données personnelles

---

## 🎓 Standards respectés

- W3C HTML5/CSS3 valides
- Sémantique HTML appropriée
- CSS externe (pas de styles inline)
- JavaScript non-intrusif
- Design responsive (mobile-first)
- Accessibilité basique

---

## ⚙️ Installation

### Prérequis
- PHP 7.4+
- MySQL 5.7+
- Serveur Apache/Nginx
- Navigateur web moderne

### Étapes
1. Télécharger/cloner le projet
2. Créer base de données MySQL
3. Importer `Site-ECommerce-CPascher.sql`
4. Configurer credentials dans `config.php`
5. Accéder via `http://localhost/Site-CPasCher/`

---

## 💡 Points clés du code

### localStorage JavaScript
```javascript
let panier = JSON.parse(localStorage.getItem('panier')) || [];
localStorage.setItem('panier', JSON.stringify(panier));
```

### Requête préparée PHP
```php
$stmt = $pdo->prepare('INSERT INTO CLIENT (...) VALUES (...)');
$stmt->execute([':email' => $email, ...]);
```

### Validation formulaire
```javascript
champRecherche.addEventListener('focus', () => {
    conteneurRecherche.style.boxShadow = '...';
});
```

---

## 📊 Données de test

**Adresse email test** : jean@test.com
**Mot de passe** : Test12345

**Produits test** : Mac, iPhone, Canapé...
**Prix test** : 999€, 1249€, 449€...

---

## 🚀 Déploiement

Site prêt pour production sur :
- Serveur partagé (hébergeur classique)
- VPS avec PHP/MySQL
- Cloud (Heroku, AWS, etc.)

---

## 📞 Support & Contact

- Email: contact@cpascher.fr
- Tél: 01 23 45 67 89
- GitHub: github.com/PAUL-Projets-Ensitech/Site-ECommerce-CPascher

---

## ✅ Checklist complétude

- [x] 12+ pages HTML distinctes
- [x] Navigation claire menu
- [x] HTML5 sémantique valide
- [x] CSS3 externe responsive
- [x] JavaScript interactions dynamiques
- [x] PHP traitement formulaires
- [x] Base de données MySQL
- [x] MCD et MLD documentés
- [x] Formulaires riches avec validation
- [x] Panier avec localStorage
- [x] Authentification client
- [x] Design moderne cohérent
- [x] Code propre et organisé
- [x] Requêtes sécurisées
- [x] README complète

---

© 2026 CPasCher - Tous droits pas trop réservés
