<?php
declare(strict_types=1);

require_once __DIR__ . '/interfaces.php';

/**
 * Classe abstraite Produit
 * 
 * Représente le socle commun de tous les produits de la librairie ReadMore.
 * Implémente les interfaces Exportable et Triable.
 * Ne peut pas être instanciée directement.
 */
abstract class Produit implements Exportable, Triable {
    // Encapsulation : Attributs protégés pour être accessibles dans les classes dérivées (héritage)
    protected string $titre;
    protected string $auteur;
    protected float $prix;
    protected int $annee;

    /**
     * Constructeur de la classe Produit
     */
    public function __construct(string $titre, string $auteur, float $prix, int $annee) {
        $this->titre = $titre;
        $this->auteur = $auteur;
        $this->prix = $prix;
        $this->annee = $annee;
    }

    // --- GETTERS & SETTERS ---

    public function getTitre(): string {
        return $this->titre;
    }

    public function getAuteur(): string {
        return $this->auteur;
    }

    public function getPrix(): float {
        return $this->prix;
    }

    public function getAnnee(): int {
        return $this->annee;
    }

    public function setPrix(float $prix): void {
        if ($prix < 0) {
            throw new InvalidArgumentException("Le prix ne peut pas être négatif.");
        }
        $this->prix = $prix;
    }

    // --- MÉTHODES ABSTRAITES ---
    // Ces méthodes devront obligatoirement être implémentées par les classes enfants.

    /**
     * Retourne le prix final du produit après application des règles de calcul spécifiques.
     */
    abstract public function getPrixFinal(): float;

    /**
     * Retourne une description textuelle complète et adaptée au type de produit.
     */
    abstract public function getDescription(): string;

    /**
     * Retourne la dénomination textuelle du type de produit (ex: "Livre Physique").
     */
    abstract public function getType(): string;

    // --- MÉTHODES CONCRÈTES ISSUES DES INTERFACES ---

    /**
     * Exporte les données du produit au format spécifié ('json' ou 'csv').
     * Illustre le Polymorphisme en appelant getType(), getDescription() et getPrixFinal().
     */
    public function exporter(string $format): string {
        $data = [
            'type' => $this->getType(),
            'titre' => $this->getTitre(),
            'auteur' => $this->getAuteur(),
            'annee' => $this->getAnnee(),
            'prix_base' => $this->getPrix(),
            'prix_final' => $this->getPrixFinal(),
            'description' => $this->getDescription()
        ];

        $formatNormalized = strtolower(trim($format));

        if ($formatNormalized === 'json') {
            return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        } elseif ($formatNormalized === 'csv') {
            // Traitement pour formater en CSV propre (séparateur point-virgule)
            $csvFields = [];
            foreach ($data as $value) {
                if (is_string($value)) {
                    // Échappement des guillemets doubles en doublant les guillemets
                    $escaped = str_replace('"', '""', $value);
                    $csvFields[] = '"' . $escaped . '"';
                } else {
                    $csvFields[] = $value;
                }
            }
            return implode(';', $csvFields);
        }

        throw new InvalidArgumentException("Format d'export non supporté : " . $format);
    }

    /**
     * Retourne la valeur de tri du produit (ici le prix final par défaut).
     */
    public function getValeurTri(): mixed {
        return $this->getPrixFinal();
    }
}
