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
            // Vérification simple que l'email n'existe pas déjà
            $stmt = $pdo->prepare('SELECT id_client FROM CLIENT WHERE email = :email');
            $stmt->execute([':email' => $email]);

            if ($stmt->fetch()) {
                $response['message'] = 'Un compte existe déjà avec cet email.';
            } else {
                // Instanciation de l'objet Client pour y stocker les informations (POO)
                $nouveauClient = new Client(null, $nom, $prenom, $email, $mot_de_passe, $adresse);

                // Insertion en base de données à l'aide des accesseurs (getters) de notre objet Client
                $stmt = $pdo->prepare('INSERT INTO CLIENT (prenom, nom, email, mot_de_passe, adresse_livraison) 
                                       VALUES (:prenom, :nom, :email, :pwd, :adresse)');

                $success = $stmt->execute([
                    ':prenom' => $nouveauClient->getPrenom(),
                    ':nom'    => $nouveauClient->getNom(),
                    ':email'  => $nouveauClient->getEmail(),
                    ':pwd'    => $nouveauClient->getMotDePasse(),
                    ':adresse' => $nouveauClient->getAdresseLivraison()
                ]);

                if ($success) {
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
