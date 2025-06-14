<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    $logFile = 'C:/wamp64/www/PFE_erij/PFEEEEEEEEEEEEE/debug.log';
    file_put_contents($logFile, "Script started: " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);

    require_once("connect.php");

    $response = ['status' => 'error', 'message' => 'Une erreur est survenue.'];

    if (!isset($_POST['id_demande']) || !is_numeric($_POST['id_demande'])) {
        $response['message'] = 'ID de demande non fourni ou invalide.';
        file_put_contents($logFile, "Error: Invalid id_demande\n", FILE_APPEND);
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    $id_demande = (int)$_POST['id_demande'];
    file_put_contents($logFile, "id_demande: $id_demande\n", FILE_APPEND);

    $connect = new ClsConnect();
    $pdo = $connect->getConnection();
    $sql = "SELECT num_contrat FROM contrats WHERE id_demande = :id_demande";
    file_put_contents($logFile, "SQL Query: $sql\n", FILE_APPEND);
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id_demande' => $id_demande]);
    $contrat = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$contrat) {
        $response['message'] = 'Aucun contrat trouvé pour cet ID de demande.';
        file_put_contents($logFile, "Error: No contract found for id_demande=$id_demande\n", FILE_APPEND);
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
    file_put_contents($logFile, "Database query succeeded: num_contrat=" . $contrat['num_contrat'] . "\n", FILE_APPEND);

    $python_script = 'C:/wamp64/www/PFE_erij/PFEEEEEEEEEEEEE/app.py';
    $python_exec = 'python'; // Try 'C:/Python39/python.exe' if needed
    $id_demande_escaped = escapeshellarg($id_demande);
    $command = "$python_exec " . escapeshellarg($python_script) . " $id_demande_escaped 2>&1";
    file_put_contents($logFile, "Command: $command\n", FILE_APPEND);

    $output = [];
    $return_var = 0;
    exec($command, $output, $return_var);
    file_put_contents($logFile, "Python exec returned: code=$return_var, output=" . implode("\n", $output) . "\n", FILE_APPEND);

    if ($return_var !== 0) {
        $response['message'] = 'Erreur lors de l\'exécution du script Python : ' . implode("\n", $output);
        file_put_contents($logFile, "Python Error: " . implode("\n", $output) . "\n", FILE_APPEND);
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    $pdf_path = "C:/wamp64/www/PFE_erij/PFEEEEEEEEEEEEE/contrat_{$id_demande}.pdf";
    file_put_contents($logFile, "PDF Path: $pdf_path\n", FILE_APPEND);

    if (!file_exists($pdf_path)) {
        $response['message'] = 'Le fichier PDF n\'a pas été généré.';
        file_put_contents($logFile, "Error: PDF not found at $pdf_path\n", FILE_APPEND);
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="contrat_' . $id_demande . '.pdf"');
    header('Content-Length: ' . filesize($pdf_path));
    readfile($pdf_path);
    file_put_contents($logFile, "PDF sent successfully\n", FILE_APPEND);
} catch (Exception $e) {
    file_put_contents($logFile, "Fatal Error: " . $e->getMessage() . "\n", FILE_APPEND);
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Erreur fatale : ' . $e->getMessage()]);
}

exit;
?>