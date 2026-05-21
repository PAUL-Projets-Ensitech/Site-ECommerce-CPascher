<?php

class Client {
    private ?int $id_client;
    private string $nom;
    private string $prenom;
    private string $email;
    private string $mot_de_passe;
    private ?string $adresse_livraison;

    public function __construct(
        ?int $id_client,
        string $nom,
        string $prenom,
        string $email,
        string $mot_de_passe,
        ?string $adresse_livraison = null
    ) {
        $this->id_client = $id_client;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->mot_de_passe = $mot_de_passe;
        $this->adresse_livraison = $adresse_livraison;
    }

    // --- Getters et Setters ---

    public function getIdClient(): ?int {
        return $this->id_client;
    }

    public function setIdClient(?int $id_client): void {
        $this->id_client = $id_client;
    }

    public function getNom(): string {
        return $this->nom;
    }

    public function setNom(string $nom): void {
        $this->nom = $nom;
    }

    public function getPrenom(): string {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): void {
        $this->prenom = $prenom;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function setEmail(string $email): void {
        $this->email = $email;
    }

    public function getMotDePasse(): string {
        return $this->mot_de_passe;
    }

    public function setMotDePasse(string $mot_de_passe): void {
        $this->mot_de_passe = $mot_de_passe;
    }

    public function getAdresseLivraison(): ?string {
        return $this->adresse_livraison;
    }

    public function setAdresseLivraison(?string $adresse_livraison): void {
        $this->adresse_livraison = $adresse_livraison;
    }

    // --- Méthodes Métier et Accès Données ---

    /**
     * Enregistre le client actuel dans la base de données.
     * 
     * @param PDO $pdo L'instance PDO pour la base de données
     * @return bool True si l'enregistrement a réussi, sinon false
     */
    public function inscrire(PDO $pdo): bool {
        $stmt = $pdo->prepare('INSERT INTO CLIENT (prenom, nom, email, mot_de_passe, adresse_livraison) 
                               VALUES (:prenom, :nom, :email, :pwd, :adresse)');
        $success = $stmt->execute([
            ':prenom' => $this->prenom,
            ':nom' => $this->nom,
            ':email' => $this->email,
            ':pwd' => $this->mot_de_passe,
            ':adresse' => $this->adresse_livraison
        ]);
        if ($success) {
            $this->id_client = (int)$pdo->lastInsertId();
            return true;
        }
        return false;
    }

    /**
     * Récupère un client en base de données à partir de son email.
     * 
     * @param PDO $pdo L'instance PDO
     * @param string $email L'email recherché
     * @return Client|null Le client trouvé ou null
     */
    public static function trouverParEmail(PDO $pdo, string $email): ?Client {
        $stmt = $pdo->prepare('SELECT id_client, nom, prenom, email, mot_de_passe, adresse_livraison FROM CLIENT WHERE email = :email');
        $stmt->execute([':email' => $email]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            return new self(
                (int)$data['id_client'],
                $data['nom'],
                $data['prenom'],
                $data['email'],
                $data['mot_de_passe'],
                $data['adresse_livraison']
            );
        }
        return null;
    }

    /**
     * Récupère un client en base de données à partir de son ID.
     * 
     * @param PDO $pdo L'instance PDO
     * @param int $id_client L'ID recherché
     * @return Client|null Le client trouvé ou null
     */
    public static function trouverParId(PDO $pdo, int $id_client): ?Client {
        $stmt = $pdo->prepare('SELECT id_client, nom, prenom, email, mot_de_passe, adresse_livraison FROM CLIENT WHERE id_client = :id_client');
        $stmt->execute([':id_client' => $id_client]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            return new self(
                (int)$data['id_client'],
                $data['nom'],
                $data['prenom'],
                $data['email'],
                $data['mot_de_passe'],
                $data['adresse_livraison']
            );
        }
        return null;
    }

    /**
     * Méthode du diagramme UML : + passerCommande(): void
     * Crée une commande associée à ce client dans la base de données.
     * 
     * @param PDO $pdo L'instance PDO
     * @param float $prix_total Le prix total de la commande
     * @throws Exception Si l'insertion échoue ou si le client n'a pas d'ID
     */
    public function passerCommande(PDO $pdo, float $prix_total): void {
        $stmt = $pdo->prepare('INSERT INTO COMMANDE (id_client, statut_commande, prix_total, date_commande)
                               VALUES (:id_client, :statut, :prix, NOW())');
        $success = $stmt->execute([
            ':id_client' => $this->id_client,
            ':statut' => 'En attente',
            ':prix' => $prix_total
        ]);

        if (!$success) {
            throw new Exception("Erreur lors de la création de la commande.");
        }
    }
}
