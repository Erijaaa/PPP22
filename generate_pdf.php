<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("connect.php");
$connect = new ClsConnect();
$pdo = $connect->getConnection();

$id_demande = $_GET['id_demande'] ?? null;

if ($id_demande) {
    $contrat = $connect->getContratDetailsById($id_demande);

    if ($contrat) {
        // afficher les détails
        echo "Sujet : " . htmlspecialchars($contrat['sujet_contrat']);
    } else {
        echo "Aucun contrat trouvé.";
    }
} else {
    echo "رقم المطلب غير متوفر.";
}

$id_demande = $_POST['id_demande'] ?? null;

if (!$id_demande) {
    echo "رقم المطلب غير متوفر";
    exit;
}

// Exécution du script Python
$command = escapeshellcmd("python C:/wamp64/www/PFE_erij/PFEEEEEEEEEEEEE/app.py $id_demande");
$output = shell_exec($command);

// Chemin du PDF
$pdf_path = "C:/wamp64/www/PFE_erij/PFEEEEEEEEEEEEE/contrat_$id_demande.pdf";
$pdf_url = "/PFE_erij/PFEEEEEEEEEEEEE/contrat_$id_demande.pdf";

// Vérification
if (file_exists($pdf_path)) {
    header("Location: $pdf_url"); // Affiche le PDF dans le navigateur
    exit;
} else {
    echo "PDF غير موجود. Résultat Python : <pre>$output</pre>";
}
?>
