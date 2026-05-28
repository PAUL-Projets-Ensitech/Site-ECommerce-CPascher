<?php
declare(strict_types=1);

/**
 * Interface Exportable
 * 
 * Impose à toutes les classes qui l'implémentent de fournir une méthode
 * permettant d'exporter les données du produit au format spécifié.
 * Le paramètre $format prend généralement les valeurs 'json' ou 'csv'.
 */
interface Exportable {
    public function exporter(string $format): string;
}

/**
 * Interface Triable
 * 
 * Impose une méthode getValeurTri() qui retourne la valeur utilisée
 * lors d'un tri du catalogue (ex: prix final, titre, année...).
 */
interface Triable {
    public function getValeurTri(): mixed;
}
