<?php
file_put_contents('debug.txt', print_r($_POST, true));

// Exécuter le script Python
$commande = escapeshellcmd("python3 C:/wamp64/www/PFE_erij/PFEEEEEEEEEEEEE/app.py " . escapeshellarg($id));
$output = shell_exec($commande);

// Chemin vers le PDF généré
$pdfFile = "C:/wamp64/www/PFE_erij/PFEEEEEEEEEEEEE/lettre_contrat_$id.pdf";

if (file_exists($pdfFile)) {
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="contrat_'.$id.'.pdf"');
    readfile($pdfFile);
    exit;
} else {
    http_response_code(500);
    echo "خطأ : لم يتم العثور على العقد.";
}
?>
