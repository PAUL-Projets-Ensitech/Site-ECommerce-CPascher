<?php
// Inclusion du fichier de configuration pour se connecter à la base de données
require_once 'config.php';

// On vérifie que la méthode du formulaire est bien "POST"
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 1. Récupération des données du formulaire
    $nom = '';
    if (isset($_POST['nom'])) {
        $nom = htmlspecialchars(trim($_POST['nom']));
    }
    
    $email = '';
    if (isset($_POST['email'])) {
        $email = htmlspecialchars(trim($_POST['email']));
    }
    
    $sujet = '';
    if (isset($_POST['sujet'])) {
        $sujet = htmlspecialchars($_POST['sujet']);
    }
    
    $message = '';
    if (isset($_POST['message'])) {
        $message = htmlspecialchars(trim($_POST['message']));
    }
    
    // 2. Vérification que tous les champs sont remplis
    if (empty($nom) || empty($email) || empty($sujet) || empty($message)) {
        // Un champ est vide on avertit l'utilisateur
        echo "<h1>Erreur</h1>";
        echo "<p>Tous les champs sont obligatoires.</p>";
        echo "<a href='../HTML/contact.html'>Retour au formulaire</a>";
        exit(); // On arrête le script ici
    } 
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // L'adresse e-mail n'est pas au bon format
        echo "<h1>Erreur</h1>";
        echo "<p>Adresse email invalide.</p>";
        echo "<a href='../HTML/contact.html'>Retour au formulaire</a>";
        exit();
    } 
    
    if (strlen($message) < 10) {
        // Le message est trop court
        echo "<h1>Erreur</h1>";
        echo "<p>Le message doit contenir au moins 10 caractères.</p>";
        echo "<a href='../HTML/contact.html'>Retour au formulaire</a>";
        exit();
    } 
    
    // 3. Enregistrement en base de données
    try {
        // On prépare la requête SQL (protection contre les injections SQL)
        $requeteSQL = 'INSERT INTO CONTACT (nom, email, sujet, message, date_contact) 
                       VALUES (:nom, :email, :sujet, :message, NOW())';
                       
        $stmt = $pdo->prepare($requeteSQL);
        
        // On exécute la requête avec nos variables
        $stmt->execute([
            ':nom' => $nom,
            ':email' => $email,
            ':sujet' => $sujet,
            ':message' => $message
        ]);
        
        // Tout s'est bien passé ! On affiche un message de réussite
        echo "<h1>Message envoyé avec succès !</h1>";
        echo "<p>Nous vous répondrons dans les 24 heures à l'adresse $email.</p>";
        echo "<a href='../index.html'>Retour à l'accueil</a>";
        
    } catch (Exception $e) {
        // Une erreur s'est produite lors de l'insertion en base
        echo "<h1>Erreur système</h1>";
        echo "<p>Une erreur est survenue lors de l'enregistrement en base. Veuillez réessayer.</p>";
        echo "<a href='../HTML/contact.html'>Retour au formulaire</a>";
    }
    
} else {
    // Si la page est appelée avec "GET" au lieu de "POST"
    echo "<h1>Méthode non autorisée</h1>";
    echo "<p>Veuillez utiliser le formulaire de contact pour accéder à cette page.</p>";
    echo "<a href='../HTML/contact.html'>Aller au formulaire</a>";
}
