<?php
require_once 'config.php';
require_once 'OO/Client.php';

$response = array('success' => false, 'message' => '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données
    $email = isset($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : '';
    $mot_de_passe = isset($_POST['mot_de_passe']) ? $_POST['mot_de_passe'] : '';

    // Validation
    if (empty($email) || empty($mot_de_passe)) {
        $response['message'] = 'Email et mot de passe requis.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Email invalide.';
    } else {
        try {
            // Recherche du client dans la base de données (SQL classique)
            $stmt = $pdo->prepare('SELECT id_client, nom, prenom, email, mot_de_passe, adresse_livraison FROM CLIENT WHERE email = :email');
            $stmt->execute([':email' => $email]);
            $clientData = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($clientData && $mot_de_passe === $clientData['mot_de_passe']) {
                // Instanciation de l'objet Client avec les données de la base (POO)
                $client = new Client(
                    (int)$clientData['id_client'],
                    $clientData['nom'],
                    $clientData['prenom'],
                    $clientData['email'],
                    $clientData['mot_de_passe'],
                    $clientData['adresse_livraison']
                );

                // Connexion réussie, on remplit la session à l'aide des accesseurs de l'objet
                $_SESSION['client_id'] = $client->getIdClient();
                $_SESSION['client_email'] = $client->getEmail();

                $response['success'] = true;
                $response['message'] = 'Connexion réussie.';
                $response['redirect'] = '../HTML/mon-compte.html';
            } else {
                $response['message'] = 'Email ou mot de passe incorrect.';
            }
        } catch (Exception $e) {
            $response['message'] = 'Erreur lors de la connexion.';
        }
    }

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($response);
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'deconnexion') {
    // Nettoyage de la session
    $_SESSION = array();

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }

    if (isset($_COOKIE['se_souvenir'])) {
        setcookie('se_souvenir', '', time() - 3600, '/');
    }

    session_destroy();

    $response = [
        'success' => true,
        'message' => 'Déconnexion réussie.',
        'redirect' => '../HTML/connexion.html'
    ];

    $acceptHeader = isset($_SERVER['HTTP_ACCEPT']) ? $_SERVER['HTTP_ACCEPT'] : '';
    $isAjaxRequest = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

    if ($isAjaxRequest || strpos($acceptHeader, 'application/json') !== false) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($response);
    } else {
        header('Location: ../HTML/connexion.html');
    }
    exit;
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'profil') {
    if (isset($_SESSION['client_id'])) {
        // Recherche des données du profil (SQL classique)
        $stmt = $pdo->prepare('SELECT id_client, nom, prenom, email, mot_de_passe, adresse_livraison FROM CLIENT WHERE id_client = ?');
        $stmt->execute([$_SESSION['client_id']]);
        $clientData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($clientData) {
            // Instanciation de l'objet Client (POO)
            $client = new Client(
                (int)$clientData['id_client'],
                $clientData['nom'],
                $clientData['prenom'],
                $clientData['email'],
                $clientData['mot_de_passe'],
                $clientData['adresse_livraison']
            );

            $response = [
                'success' => true,
                'client' => [
                    'nom' => $client->getNom(),
                    'prenom' => $client->getPrenom(),
                    'email' => $client->getEmail(),
                    'adresse_livraison' => $client->getAdresseLivraison()
                ]
            ];
        } else {
            $response['message'] = 'Utilisateur introuvable';
        }
    } else {
        $response['message'] = 'Non connecté';
    }
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($response);
} else {
    header('HTTP/1.0 405 Method Not Allowed');
    die('Méthode non autorisée');
}
