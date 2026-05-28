<?php
declare(strict_types=1);

require_once __DIR__ . '/livre_physique.php';
require_once __DIR__ . '/livre_numerique.php';
require_once __DIR__ . '/livre_audio.php';

// 16 & 17. Instanciation des produits et stockage dans un tableau typé (via Docblock)
/** @var Produit[] $catalogue */
$catalogue = [
    new LivrePhysique("Les Misérables", "Victor Hugo", 25.00, 1862, 1.2, 4.50),
    new LivrePhysique("Le Petit Prince", "Antoine de Saint-Exupéry", 12.00, 1943, 0.25, 2.00),
    new LivreNumerique("1984", "George Orwell", 8.99, 1949, "EPUB"),
    new LivreNumerique("Le Seigneur des Anneaux", "J.R.R. Tolkien", 19.99, 1954, "PDF"),
    new LivreAudio("Dune", "Frank Herbert", 22.50, 1965, 1260, "Benjamin Jungers"),
    new LivreAudio("L'Étranger", "Albert Camus", 15.00, 1942, 210, "Bernard Giraudeau")
];

echo "====================================================================\n";
echo "    AFFICHAGE INITIAL DU CATALOGUE (Illustration du Polymorphisme)  \n";
echo "====================================================================\n";

// 18. Parcours du tableau et affichage des caractéristiques (polymorphisme en action)
foreach ($catalogue as $index => $produit) {
    echo "Produit n°" . ($index + 1) . " :\n";
    echo "  - Type : " . $produit->getType() . "\n";
    echo "  - Description : " . $produit->getDescription() . "\n";
    echo "  - Prix de base : " . number_format($produit->getPrix(), 2, ',', ' ') . " €\n";
    echo "  - Prix final (calculé) : " . number_format($produit->getPrixFinal(), 2, ',', ' ') . " €\n";
    echo "--------------------------------------------------------------------\n";
}

echo "\n====================================================================\n";
echo "    TRI DU CATALOGUE PAR PRIX CROISSANT (via usort & getValeurTri)  \n";
echo "====================================================================\n";

// 19. Tri du tableau par prix croissant avec usort() et getValeurTri()
usort($catalogue, function (Produit $a, Produit $b) {
    $valA = $a->getValeurTri();
    $valB = $b->getValeurTri();
    
    if ($valA === $valB) {
        return 0;
    }
    return ($valA < $valB) ? -1 : 1;
});

foreach ($catalogue as $index => $produit) {
    echo sprintf(
        "[%s] %s : %s € (prix de base : %s €)\n",
        str_pad($produit->getType(), 14, " "),
        str_pad($produit->getTitre(), 26, " "),
        number_format($produit->getPrixFinal(), 2, ',', ' '),
        number_format($produit->getPrix(), 2, ',', ' ')
    );
}

echo "\n====================================================================\n";
echo "         EXPORTATION DES PRODUITS (JSON & CSV via exporter)         \n";
echo "====================================================================\n";

// 20. Export de chaque produit en JSON et CSV
foreach ($catalogue as $produit) {
    echo "--- Produit : " . $produit->getTitre() . " ---\n";
    
    echo "[FORMAT JSON]\n";
    echo $produit->exporter('json') . "\n\n";
    
    echo "[FORMAT CSV]\n";
    echo $produit->exporter('csv') . "\n";
    echo "--------------------------------------------------------------------\n";
}
