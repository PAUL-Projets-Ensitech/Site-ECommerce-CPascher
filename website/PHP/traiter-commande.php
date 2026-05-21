<?php
require_once 'config.php';
require_once 'OO/Client.php';

$response = array('success' => false, 'message' => '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prenom = isset($_POST['prenom']) ? htmlspecialchars(trim($_POST['prenom'])) : '';
    $nom = isset($_POST['nom']) ? htmlspecialchars(trim($_POST['nom'])) : '';
    $adresse = isset($_POST['adresse']) ? htmlspecialchars(trim($_POST['adresse'])) : '';
    $code_postal = isset($_POST['code_postal']) ? htmlspecialchars(trim($_POST['code_postal'])) : '';
    $ville = isset($_POST['ville']) ? htmlspecialchars(trim($_POST['ville'])) : '';
    $telephone = isset($_POST['telephone']) ? htmlspecialchars(trim($_POST['telephone'])) : '';
    $mode_paiement = isset($_POST['mode_paiement']) ? htmlspecialchars($_POST['mode_paiement']) : '';

    if (empty($prenom) || empty($nom) || empty($adresse) || empty($code_postal) || empty($ville) || empty($mode_paiement)) {
        $response['message'] = 'Tous les champs sont obligatoires.';
    } else {
        try {
            $id_client = isset($_SESSION['client_id']) ? $_SESSION['client_id'] : null;
            $prix_total = 2498.00;

            // Récupération ou création de l'objet Client
            if ($id_client !== null) {
                // Récupération simple des données du client en base
                $stmt = $pdo->prepare('SELECT id_client, nom, prenom, email, mot_de_passe, adresse_livraison FROM CLIENT WHERE id_client = ?');
                $stmt->execute([$id_client]);
                $clientData = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$clientData) {
                    throw new Exception("Client introuvable.");
                }

                // Instanciation de l'objet Client (POO)
                $client = new Client(
                    (int)$clientData['id_client'],
                    $clientData['nom'],
                    $clientData['prenom'],
                    $clientData['email'],
                    $clientData['mot_de_passe'],
                    $clientData['adresse_livraison']
                );
            } else {
                // Client invité (création d'un objet Client temporaire)
                $client = new Client(
                    null,
                    $nom,
                    $prenom,
                    '', // email vide pour un invité
                    '', // mot de passe vide
                    $adresse . ', ' . $code_postal . ' ' . $ville
                );
            }

            // Appel de la méthode UML : + passerCommande(): void
            $client->passerCommande($pdo, $prix_total);

            $response['success'] = true;
            $response['message'] = 'Commande validee avec succes !';
            $response['redirect'] = 'confirmation.html';
        } catch (Exception $e) {
            $response['message'] = 'Erreur lors traitement commande : ' . $e->getMessage();
        }
    }

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($response);
} else {
    header('HTTP/1.0 405 Method Not Allowed');
    die('Non autorise');
}
