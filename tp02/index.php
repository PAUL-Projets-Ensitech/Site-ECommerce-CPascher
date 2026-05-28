<?php
declare(strict_types=1);

require_once __DIR__ . '/livre_physique.php';
require_once __DIR__ . '/livre_numerique.php';
require_once __DIR__ . '/livre_audio.php';

// Catalogue de produits
$catalogue = [
    new LivrePhysique("Les Misérables", "Victor Hugo", 25.00, 1862, 1.2, 4.50),
    new LivrePhysique("Le Petit Prince", "Antoine de Saint-Exupéry", 12.00, 1943, 0.25, 2.00),
    new LivreNumerique("1984", "George Orwell", 8.99, 1949, "EPUB"),
    new LivreNumerique("Le Seigneur des Anneaux", "J.R.R. Tolkien", 19.99, 1954, "PDF"),
    new LivreAudio("Dune", "Frank Herbert", 22.50, 1965, 1260, "Benjamin Jungers"),
    new LivreAudio("L'Étranger", "Albert Camus", 15.00, 1942, 210, "Bernard Giraudeau")
];

// Récupération et traitement du paramètre de tri (méthode GET dans l'URL)
$tri = $_GET['tri'] ?? 'aucun';

if ($tri === 'prix') {
    // Tri par prix final croissant
    usort($catalogue, fn(Produit $a, Produit $b) => $a->getPrixFinal() <=> $b->getPrixFinal());
} elseif ($tri === 'titre') {
    // Tri alphabétique par titre
    usort($catalogue, fn(Produit $a, Produit $b) => strcmp($a->getTitre(), $b->getTitre()));
} elseif ($tri === 'annee') {
    // Tri chronologique par année de publication
    usort($catalogue, fn(Produit $a, Produit $b) => $a->getAnnee() <=> $b->getAnnee());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReadMore — Catalogue Dynamique</title>
    <style>
        :root {
            --bg-color: #f8fafc;
            --text-color: #1e293b;
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --card-bg: #ffffff;
            --border-color: #e2e8f0;
            --badge-physique: #dbeafe;
            --badge-physique-txt: #1e40af;
            --badge-ebook: #fef3c7;
            --badge-ebook-txt: #92400e;
            --badge-audio: #e0f2fe;
            --badge-audio-txt: #0369a1;
        }

        body {
            font-family: 'Outfit', 'Inter', 'Segoe UI', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            margin: 0;
            padding: 2rem 1rem;
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
        }

        header {
            text-align: center;
            margin-bottom: 3rem;
        }

        h1 {
            color: var(--primary);
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            font-weight: 800;
        }

        .subtitle {
            color: #64748b;
            font-size: 1.1rem;
            margin: 0;
        }

        /* Barre de tri (Paramètre GET) */
        .sorting-bar {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            padding: 1rem 1.5rem;
            border-radius: 14px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            flex-wrap: wrap;
            gap: 1rem;
        }

        .sorting-title {
            font-weight: 600;
            color: #475569;
        }

        .sorting-links {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .sort-btn {
            text-decoration: none;
            background-color: #f1f5f9;
            color: #475569;
            padding: 0.5rem 1.25rem;
            border-radius: 10px;
            font-size: 0.9rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .sort-btn:hover {
            background-color: #e2e8f0;
            color: var(--text-color);
        }

        .sort-btn.active {
            background-color: var(--primary);
            color: #ffffff;
            box-shadow: 0 4px 10px rgba(79, 70, 229, 0.3);
        }

        /* Grille des produits */
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 2rem;
        }

        /* Cartes de livres */
        .card {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 1.75rem;
            box-shadow: 0 10px 20px -3px rgba(0, 0, 0, 0.04);
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 35px -5px rgba(79, 70, 229, 0.08);
        }

        .badge {
            align-self: flex-start;
            padding: 0.3rem 0.85rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 700;
            margin-bottom: 1.25rem;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .badge.physique {
            background-color: var(--badge-physique);
            color: var(--badge-physique-txt);
        }

        .badge.ebook {
            background-color: var(--badge-ebook);
            color: var(--badge-ebook-txt);
        }

        .badge.audio {
            background-color: var(--badge-audio);
            color: var(--badge-audio-txt);
        }

        .title {
            font-size: 1.35rem;
            font-weight: 800;
            margin: 0 0 0.5rem 0;
            line-height: 1.3;
            color: #0f172a;
        }

        .author {
            color: #64748b;
            font-size: 0.95rem;
            margin: 0 0 1.25rem 0;
            font-weight: 500;
        }

        .year {
            color: #94a3b8;
            font-weight: 400;
        }

        .description {
            font-size: 0.9rem;
            line-height: 1.6;
            color: #475569;
            margin: 0 0 1.75rem 0;
            flex-grow: 1;
        }

        .price-section {
            border-top: 1px solid var(--border-color);
            padding-top: 1.25rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .price-container {
            display: flex;
            flex-direction: column;
        }

        .base-price {
            text-decoration: line-through;
            color: #94a3b8;
            font-size: 0.85rem;
            margin-bottom: 0.1rem;
        }

        .final-price {
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--primary);
        }

        .action-indicator {
            background-color: #f8fafc;
            border: 1px solid var(--border-color);
            padding: 0.4rem 0.8rem;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 600;
            color: #64748b;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>ReadMore E-Catalogue</h1>
            <p class="subtitle">Modélisation POO & Polymorphisme en Action avec HTML5/CSS3</p>
        </header>

        <!-- Barre de tri (Transmission de paramètre par GET) -->
        <div class="sorting-bar">
            <span class="sorting-title">Options de tri (méthode GET) :</span>
            <div class="sorting-links">
                <a href="index.php" class="sort-btn <?= $tri === 'aucun' ? 'active' : '' ?>">Aucun</a>
                <a href="index.php?tri=titre" class="sort-btn <?= $tri === 'titre' ? 'active' : '' ?>">Titre</a>
                <a href="index.php?tri=prix" class="sort-btn <?= $tri === 'prix' ? 'active' : '' ?>">Prix Final</a>
                <a href="index.php?tri=annee" class="sort-btn <?= $tri === 'annee' ? 'active' : '' ?>">Année</a>
            </div>
        </div>

        <!-- Grille Responsive des Livres -->
        <div class="grid">
            <?php foreach ($catalogue as $produit): ?>
                <?php
                // Choix de la classe CSS du badge selon le type retourné dynamiquement (Polymorphisme)
                $type = $produit->getType();
                $badgeClass = match($type) {
                    'Livre Physique' => 'physique',
                    'Ebook'          => 'ebook',
                    'Livre Audio'    => 'audio',
                    default          => ''
                };
                ?>
                <div class="card">
                    <div>
                        <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($type) ?></span>
                        <h2 class="title"><?= htmlspecialchars($produit->getTitre()) ?></h2>
                        <p class="author">
                            Par <?= htmlspecialchars($produit->getAuteur()) ?> 
                            <span class="year">/ <?= $produit->getAnnee() ?></span>
                        </p>
                        <p class="description"><?= htmlspecialchars($produit->getDescription()) ?></p>
                    </div>

                    <div class="price-section">
                        <div class="price-container">
                            <?php 
                            // Si le prix final est différent du prix de base, on affiche le prix de base barré
                            if (abs($produit->getPrix() - $produit->getPrixFinal()) > 0.01): 
                            ?>
                                <span class="base-price">Prix : <?= number_format($produit->getPrix(), 2, ',', ' ') ?> €</span>
                            <?php endif; ?>
                            <span class="final-price"><?= number_format($produit->getPrixFinal(), 2, ',', ' ') ?> €</span>
                        </div>
                        <div class="action-indicator">
                            Dispo
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
