<?php
// 1. Récupérer l'id_demande depuis POST
$id = $_POST['id_demande'] ?? null;

if (!$id) {
    die("Erreur : id_demande manquant.");
}

// 2. Construire la commande sécurisée
$commande = escapeshellcmd("python3 C:/wamp64/www/PFE_erij/PFEEEEEEEEEEEEE/app.py " . escapeshellarg($id));

// 3. Exécuter la commande
$output = shell_exec($commande);

// 4. Gérer le PDF généré (exemple)
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


