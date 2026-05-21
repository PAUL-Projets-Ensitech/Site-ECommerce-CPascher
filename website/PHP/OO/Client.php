<?php

class Client
{
    // Les propriétés privées (définies avec "-" dans le diagramme UML)
    private ?int $id_client;
    private string $nom;
    private string $prenom;
    private string $email;
    private string $mot_de_passe;
    private ?string $adresse_livraison;

    // Le constructeur pour initialiser un objet Client
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

    // --- Les accesseurs (Getters) pour lire les propriétés privées ---

    public function getIdClient(): ?int
    {
        return $this->id_client;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getMotDePasse(): string
    {
        return $this->mot_de_passe;
    }

    public function getAdresseLivraison(): ?string
    {
        return $this->adresse_livraison;
    }

    // --- Les mutateurs (Setters) pour modifier les propriétés privées ---

    public function setIdClient(?int $id_client): void
    {
        $this->id_client = $id_client;
    }

    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    public function setPrenom(string $prenom): void
    {
        $this->prenom = $prenom;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setMotDePasse(string $mot_de_passe): void
    {
        $this->mot_de_passe = $mot_de_passe;
    }

    public function setAdresseLivraison(?string $adresse_livraison): void
    {
        $this->adresse_livraison = $adresse_livraison;
    }

    // --- Méthode du diagramme UML : + passerCommande(): void ---

    public function passerCommande(PDO $pdo, float $prix_total): void
    {
        // Préparation de la requête SQL d'insertion de la commande
        $stmt = $pdo->prepare('INSERT INTO COMMANDE (id_client, statut_commande, prix_total, date_commande)
                               VALUES (:id_client, :statut, :prix, NOW())');

        // Exécution de la requête en transmettant l'ID du client actuel ($this->id_client)
        $stmt->execute([
            ':id_client' => $this->id_client,
            ':statut' => 'En attente',
            ':prix' => $prix_total
        ]);
    }
}
