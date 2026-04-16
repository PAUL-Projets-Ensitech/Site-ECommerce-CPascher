<?php
require_once '/PHP/config.php';

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
            
            $stmt = $pdo->prepare('INSERT INTO COMMANDE (id_client, statut_commande, prix_total, date_commande)
                                   VALUES (:id_client, :statut, :prix, NOW())');
            $stmt->execute([
                ':id_client' => $id_client,
                ':statut' => 'En attente',
                ':prix' => $prix_total
            ]);
            
            $response['success'] = true;
            $response['message'] = 'Commande validee avec succes !';
            $response['redirect'] = 'confirmation.html';
            
        } catch (Exception $e) {
            $response['message'] = 'Erreur lors traitement commande.';
        }
    }
    
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($response);
} else {
    header('HTTP/1.0 405 Method Not Allowed');
    die('Non autorise');
}