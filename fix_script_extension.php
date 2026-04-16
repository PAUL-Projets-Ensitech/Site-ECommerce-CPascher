<?php
$html_folder = __DIR__ . '/website/HTML/';

if (is_dir($html_folder)) {
    $html_files = glob($html_folder . '*.html');
    foreach ($html_files as $file) {
        $content = file_get_contents($file);
        
        // Corriger l'erreur d'extension pour le script JavaScript
        $content = str_replace('src="../../JAVASCRIPT/script.css"', 'src="../JAVASCRIPT/script.js"', $content);
        $content = str_replace('src="../JAVASCRIPT/script.css"', 'src="../JAVASCRIPT/script.js"', $content);
        
        file_put_contents($file, $content);
    }
    echo "Les liens Javascript ont ete repares !";
} else {
    echo "Dossier introuvable.";
}
?>