<?php
declare(strict_types=1);

require_once __DIR__ . '/produit.php';

/**
 * Classe LivreNumerique (Ebook)
 * 
 * Hérite de Produit et gère les livres dématérialisés
 * avec une réduction systématique de 15%.
 */
class LivreNumerique extends Produit {
    // Encapsulation : Propriété privée spécifique à LivreNumerique
    private string $format; // 'PDF', 'EPUB', 'MOBI'

    /**
     * Constructeur de LivreNumerique
     */
    public function __construct(
        string $titre, 
        string $auteur, 
        float $prix, 
        int $annee, 
        string $format
    ) {
        parent::__construct($titre, $auteur, $prix, $annee);
        $this->setFormat($format);
    }

    // --- GETTERS & SETTERS SPECIFIQUES ---

    public function getFormat(): string {
        return $this->format;
    }

    public function setFormat(string $format): void {
        $allowedFormats = ['PDF', 'EPUB', 'MOBI'];
        $formatUpper = strtoupper(trim($format));
        
        if (!in_array($formatUpper, $allowedFormats, true)) {
            throw new InvalidArgumentException(
                "Format invalide. Les formats autorisés sont : " . implode(', ', $allowedFormats)
            );
        }
        
        $this->format = $formatUpper;
    }

    // --- IMPLÉMENTATION DES MÉTHODES ABSTRAITES HÉRITÉES ---

    /**
     * Prix final = Prix de base - 15% de réduction
     */
    public function getPrixFinal(): float {
        return $this->prix * 0.85;
    }

    /**
     * Description détaillée du livre numérique
     */
    public function getDescription(): string {
        return sprintf(
            "Livre numérique (Ebook) : \"%s\" par %s (%d) - Format : %s (Remise de 15%% incluse)",
            $this->titre,
            $this->auteur,
            $this->annee,
            $this->format
        );
    }

    /**
     * Type de produit
     */
    public function getType(): string {
        return "Ebook";
    }
}
