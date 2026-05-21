<?php
require_once 'config.php';
require_once 'OO/Client.php';

$response = array('success' => false, 'message' => '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données
    $prenom = isset($_POST['prenom']) ? htmlspecialchars(trim($_POST['prenom'])) : '';
    $nom = isset($_POST['nom']) ? htmlspecialchars(trim($_POST['nom'])) : '';
    $email = isset($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : '';
    $mot_de_passe = isset($_POST['mot_de_passe']) ? $_POST['mot_de_passe'] : '';
    $confirmer_mdp = isset($_POST['confirmer_mdp']) ? $_POST['confirmer_mdp'] : '';
    $adresse = isset($_POST['adresse']) ? htmlspecialchars(trim($_POST['adresse'])) : '';
    
    // Validations
    if (empty($prenom) || empty($nom) || empty($email) || empty($mot_de_passe)) {
        $response['message'] = 'Tous les champs obligatoires doivent être remplis.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Adresse email invalide.';
    } elseif (strlen($mot_de_passe) < 8) {
        $response['message'] = 'Le mot de passe doit contenir au minimum 8 caractères.';
    } elseif ($mot_de_passe !== $confirmer_mdp) {
        $response['message'] = 'Les mots de passe ne correspondent pas.';
    } else {
        try {
            // Vérification que l'email n'existe pas déjà en utilisant la POO
            $clientExistant = Client::trouverParEmail($pdo, $email);
            
            if ($clientExistant !== null) {
                $response['message'] = 'Un compte existe déjà avec cet email.';
            } else {
                // Instanciation de l'objet Client
                $nouveauClient = new Client(null, $nom, $prenom, $email, $mot_de_passe, $adresse);
                
                // Inscription via la méthode de la classe Client
                if ($nouveauClient->inscrire($pdo)) {
                    $response['success'] = true;
                    $response['message'] = 'Inscription reussie.';
                    $response['redirect'] = '../HTML/connexion.html';
                } else {
                    $response['message'] = 'Erreur lors de l\'enregistrement de l\'utilisateur.';
                }
            }
        } catch (Exception $e) {
            $response['message'] = 'Erreur lors inscription.';
        }
    }
    
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($response);
} else {
    header('HTTP/1.0 405 Method Not Allowed');
    die('Non autorise');
}