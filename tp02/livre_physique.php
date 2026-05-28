<?php
declare(strict_types=1);

require_once __DIR__ . '/produit.php';

/**
 * Classe LivrePhysique
 * 
 * Hérite de Produit et spécialise la gestion des livres imprimés
 * avec prise en compte du poids et des frais de port.
 */
class LivrePhysique extends Produit {
    // Encapsulation : Propriétés privées spécifiques à LivrePhysique
    private float $poids;      // en kg
    private float $fraisPort;  // en EUR

    /**
     * Constructeur de LivrePhysique
     */
    public function __construct(
        string $titre, 
        string $auteur, 
        float $prix, 
        int $annee, 
        float $poids, 
        float $fraisPort = 3.90
    ) {
        // Appel au constructeur de la classe parente (Produit)
        parent::__construct($titre, $auteur, $prix, $annee);
        
        $this->poids = $poids;
        $this->fraisPort = $fraisPort;
    }

    // --- GETTERS & SETTERS SPECIFIQUES ---

    public function getPoids(): float {
        return $this->poids;
    }

    public function setPoids(float $poids): void {
        if ($poids < 0) {
            throw new InvalidArgumentException("Le poids ne peut pas être négatif.");
        }
        $this->poids = $poids;
    }

    public function getFraisPort(): float {
        return $this->fraisPort;
    }

    public function setFraisPort(float $fraisPort): void {
        if ($fraisPort < 0) {
            throw new InvalidArgumentException("Les frais de port ne peuvent pas être négatifs.");
        }
        $this->fraisPort = $fraisPort;
    }

    // --- IMPLÉMENTATION DES MÉTHODES ABSTRAITES HÉRITÉES ---

    /**
     * Prix final = Prix de base + Frais de port
     */
    public function getPrixFinal(): float {
        // Utilisation directe de la propriété protégée $this->prix héritée de Produit
        return $this->prix + $this->fraisPort;
    }

    /**
     * Description détaillée du livre physique
     */
    public function getDescription(): string {
        return sprintf(
            "Livre physique : \"%s\" par %s (%d) - Poids : %s kg, Frais de port : %s €",
            $this->titre,
            $this->auteur,
            $this->annee,
            number_format($this->poids, 2, ',', ' '),
            number_format($this->fraisPort, 2, ',', ' ')
        );
    }

    /**
     * Type de produit
     */
    public function getType(): string {
        return "Livre Physique";
    }
}
