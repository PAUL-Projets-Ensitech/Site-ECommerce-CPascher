<?php
declare(strict_types=1);

require_once __DIR__ . '/produit.php';

/**
 * Classe LivreAudio
 * 
 * Hérite de Produit et gère les livres enregistrés sous format audio
 * avec une majoration de 10% pour les coûts de production.
 */
class LivreAudio extends Produit {
    // Encapsulation : Propriétés privées spécifiques à LivreAudio
    private int $duree;         // en minutes
    private string $narrateur;  // voix off / lecteur

    /**
     * Constructeur de LivreAudio
     */
    public function __construct(
        string $titre, 
        string $auteur, 
        float $prix, 
        int $annee, 
        int $duree, 
        string $narrateur
    ) {
        parent::__construct($titre, $auteur, $prix, $annee);
        $this->setDuree($duree);
        $this->narrateur = $narrateur;
    }

    // --- GETTERS & SETTERS SPECIFIQUES ---

    public function getDuree(): int {
        return $this->duree;
    }

    public function setDuree(int $duree): void {
        if ($duree <= 0) {
            throw new InvalidArgumentException("La durée de l'audio doit être strictement supérieure à 0 minute.");
        }
        $this->duree = $duree;
    }

    public function getNarrateur(): string {
        return $this->narrateur;
    }

    public function setNarrateur(string $narrateur): void {
        $this->narrateur = trim($narrateur);
    }

    // --- IMPLÉMENTATION DES MÉTHODES ABSTRAITES HÉRITÉES ---

    /**
     * Prix final = Prix de base + 10% de majoration
     */
    public function getPrixFinal(): float {
        return $this->prix * 1.10;
    }

    /**
     * Description détaillée du livre audio
     */
    public function getDescription(): string {
        return sprintf(
            "Livre audio : \"%s\" par %s (%d) - Narrateur : %s, Durée : %d minutes (Majoration de 10%% appliquée)",
            $this->titre,
            $this->auteur,
            $this->annee,
            $this->narrateur,
            $this->duree
        );
    }

    /**
     * Type de produit
     */
    public function getType(): string {
        return "Livre Audio";
    }
}
