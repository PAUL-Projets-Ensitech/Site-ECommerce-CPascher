<?php
require_once 'config.php';

$response = array('success' => false, 'message' => '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération et validation des données
    $nom = isset($_POST['nom']) ? htmlspecialchars(trim($_POST['nom'])) : '';
    $email = isset($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : '';
    $sujet = isset($_POST['sujet']) ? htmlspecialchars($_POST['sujet']) : '';
    $message = isset($_POST['message']) ? htmlspecialchars(trim($_POST['message'])) : '';
    
    // Vérification que tous les champs sont remplis
    if (empty($nom) || empty($email) || empty($sujet) || empty($message)) {
        $response['message'] = 'Tous les champs sont obligatoires.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Validation de l'email
        $response['message'] = 'Adresse email invalide.';
    } elseif (strlen($message) < 10) {
        // Vérification de la longueur du message
        $response['message'] = 'Le message doit contenir au moins 10 caractères.';
    } else {
        // Traitement métier : ici on pourrait envoyer un email
        // Pour cet exemple, on va simuler un enregistrement en base de données
        
        try {
            $stmt = $pdo->prepare('INSERT INTO CONTACT (nom, email, sujet, message, date_contact) 
                                   VALUES (:nom, :email, :sujet, :message, NOW())');
            $stmt->execute([
                ':nom' => $nom,
                ':email' => $email,
                ':sujet' => $sujet,
                ':message' => $message
            ]);
            
            $response['success'] = true;
            $response['message'] = 'Votre message a été envoyé avec succès. Nous vous répondrons dans les 24 heures.';
            
            // On pourrait ici envoyer un email au client de confirmation
            // mail($email, 'Confirmation de contact - CPasCher', 'Merci pour votre message...');
            
        } catch (Exception $e) {
            $response['message'] = 'Une erreur est survenue. Veuillez réessayer.';
        }
    }
    
    // Retour en JSON pour les requêtes AJAX
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($response);
} else {
    // Gestion des accès non POST
    header('HTTP/1.0 405 Method Not Allowed');
    die('Méthode non autorisée');
}
