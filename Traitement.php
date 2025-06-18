<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("connect.php");
//require_once 'insert_data.php'; 
$db = new ClsConnect();
$pdo = $db->getConnection();

if (isset($_GET['id_demande']) && isset($_GET['num_recu'])) {
    $id_demande = $_GET['id_demande'];
    $num_recu = $_GET['num_recu'];
    $demande = $db->getDemandeById($id_demande); 
} else {
    echo "Paramètres manquants dans l'URL.";
    exit;
}




// Initialize variables for personneContratc
/*$prenom = '';
$numero_document_identite = '';
$nom = '';
$prenom_pere = '';
$date_emission_document = '';
$sexe = '';
$nationalite = '';
$adresse = '';
$profession = '';
$etat_civil = '';
$prenom_conjoint = '';
$nom_conjoint = '';
$prenom_pere_conjoint = '';
$prenom_grand_pere_conjoint = '';
$surnom_conjoint = '';
$date_naissance_conjoint = '';
$lieu_naissance_conjoint = '';
$nationalite_conjoint = '';
$numero_document_conjoint = '';
$date_document_conjoint = '';
$lieu_document_conjoint = '';
$vendeur_acheteur = '';
$nom_complet_personne = '';
$statut_contractant = '';
$notes = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    try {
        // Validate required fields
        if (!isset($_POST['statut_contractant']) || !is_array($_POST['statut_contractant']) || empty($_POST['statut_contractant'][0])) {
            echo "❌ Le statut du contractant est requis";
            exit;
        }

        // Extract POST data, handling arrays and trimming values
        $prenom = isset($_POST['prenom']) ? array_map('trim', (array)$_POST['prenom']) : [];
        $numero_document_identite = isset($_POST['numero_document_identite']) ? array_map('trim', (array)$_POST['numero_document_identite']) : [];
        $nom = isset($_POST['nom']) ? array_map('trim', (array)$_POST['nom']) : [];
        $prenom_pere = isset($_POST['prenom_pere']) ? array_map('trim', (array)$_POST['prenom_pere']) : [];
        $date_emission_document = isset($_POST['date_emission_document']) ? array_map('trim', (array)$_POST['date_emission_document']) : [];
        $sexe = isset($_POST['sexe']) ? array_map('trim', (array)$_POST['sexe']) : [];
        $nationalite = isset($_POST['nationalite']) ? array_map('trim', (array)$_POST['nationalite']) : [];
        $adresse = isset($_POST['adresse']) ? array_map('trim', (array)$_POST['adresse']) : [];
        $profession = isset($_POST['profession']) ? array_map('trim', (array)$_POST['profession']) : [];
        $etat_civil = isset($_POST['etat_civil']) ? array_map('trim', (array)$_POST['etat_civil']) : [];
        $prenom_conjoint = isset($_POST['prenom_conjoint']) ? array_map('trim', (array)$_POST['prenom_conjoint']) : [];
        $nom_conjoint = isset($_POST['nom_conjoint']) ? array_map('trim', (array)$_POST['nom_conjoint']) : [];
        $prenom_pere_conjoint = isset($_POST['prenom_pere_conjoint']) ? array_map('trim', (array)$_POST['prenom_pere_conjoint']) : [];
        $prenom_grand_pere_conjoint = isset($_POST['prenom_grand_pere_conjoint']) ? array_map('trim', (array)$_POST['prenom_grand_pere_conjoint']) : [];
        $surnom_conjoint = isset($_POST['surnom_conjoint']) ? array_map('trim', (array)$_POST['surnom_conjoint']) : [];
        $date_naissance_conjoint = isset($_POST['date_naissance_conjoint']) ? array_map('trim', (array)$_POST['date_naissance_conjoint']) : [];
        $lieu_naissance_conjoint = isset($_POST['lieu_naissance_conjoint']) ? array_map('trim', (array)$_POST['lieu_naissance_conjoint']) : [];
        $nationalite_conjoint = isset($_POST['nationalite_conjoint']) ? array_map('trim', (array)$_POST['nationalite_conjoint']) : [];
        $numero_document_conjoint = isset($_POST['numero_document_conjoint']) ? array_map('trim', (array)$_POST['numero_document_conjoint']) : [];
        $date_document_conjoint = isset($_POST['date_document_conjoint']) ? array_map('trim', (array)$_POST['date_document_conjoint']) : [];
        $lieu_document_conjoint = isset($_POST['lieu_document_conjoint']) ? array_map('trim', (array)$_POST['lieu_document_conjoint']) : [];
        $vendeur_acheteur = isset($_POST['vendeur_acheteur']) ? array_map('trim', (array)$_POST['vendeur_acheteur']) : [];
        $nom_complet_personne = isset($_POST['nom_complet_personne']) ? array_map('trim', (array)$_POST['nom_complet_personne']) : [];
        $statut_contractant = isset($_POST['statut_contractant']) ? array_map('trim', (array)$_POST['statut_contractant']) : [];
        $notes = isset($_POST['notes']) ? array_map('trim', (array)$_POST['notes']) : [];
        $id_demande_array = isset($_POST['id_demande']) ? (array)$_POST['id_demande'] : [$id_demande];

        // Call personneContratc function
        $personne = $db->personneContratc(
          $pdo,
          $prenom, // [trimmed values]
          $numero_document_identite, // [trimmed values]
          $nom, // [trimmed values]
          $prenom_pere, // [trimmed values]
          $date_emission_document, // [trimmed values]
          $sexe, // [trimmed values]
          $nationalite, // [trimmed values]
          $adresse, // [trimmed values]
          $profession, // [trimmed values]
          $etat_civil, // [trimmed values]
          $prenom_conjoint, // [trimmed values]
          $nom_conjoint, // [trimmed values]
          $prenom_pere_conjoint, // [trimmed values]
          $prenom_grand_pere_conjoint, // [trimmed values]
          $surnom_conjoint, // [trimmed values]
          $date_naissance_conjoint, // [trimmed values]
          $lieu_naissance_conjoint, // [trimmed values]
          $nationalite_conjoint, // [trimmed values]
          $numero_document_conjoint, // [trimmed values]
          $date_document_conjoint, // [trimmed values]
          $lieu_document_conjoint, // [trimmed values]
          $vendeur_acheteur, // [trimmed values]
          $id_demande_array, // [trimmed values]
          $nom_complet_personne, // [trimmed values]
          $statut_contractant, // [trimmed values]
          $notes // Ajoutez $notes si requis
      );
        
        if ($personne === false) {
            echo "❌ Erreur lors de la sauvegarde des données";
        } else {
            echo $personne;
        }
    } catch (Exception $e) {
        echo "❌ Une erreur est survenue : " . $e->getMessage();
    }
}*/


$contratManagement = new contratManager($pdo);


$id_demande = $_GET['id_demande'] ?? '';
$annee_demande = $_POST['annee_demande'] ?? '';
$date_demande = date('Y-m-d');


//$success = $contratManagement->enregistrerContrat($id_demande, $annee_demande, $date_demande, $id_contrat);


$id_demande = isset($_GET['id_demande']) ? intval($_GET['id_demande']) : 0;
$sujetducontrat = $db->getSujetContrat($id_demande);
$anneecontrat = $db->getAnneeContrat($id_demande);
$numcontrat = $db->getNumContrat($id_demande);
$pieces_jointes = $db->getPiecesJointesByDemande($id_demande);
$agent = $db->getAgent($id_demande);
$deposant = $db->getDeposant($id_demande);
$sujetContrat = $db->getSubject($id_demande);



if (isset($_GET['id_demande']) && isset($_GET['num_recu'])) {
    $id_demande = $_GET['id_demande'];
    $num_recu = $_GET['num_recu'];
    $demande = $db->getDemandeById($id_demande);
} else {
    echo "Paramètres manquants dans l'URL.";
    exit;
}

// Initialisation des variables globales (facultatif, peut être mis à jour dans le bloc POST)
$id_demande = isset($_GET['id_demande']) ? intval($_GET['id_demande']) : 0;
$sujetducontrat = $db->getSujetContrat($id_demande);
$anneecontrat = $db->getAnneeContrat($id_demande);
$numcontrat = $db->getNumContrat($id_demande);
$pieces_jointes = $db->getPiecesJointesByDemande($id_demande);
$agent = $db->getAgent($id_demande);
$deposant = $db->getDeposant($id_demande);
$sujetContrat = $db->getSubject($id_demande);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_all_data'])) {
    try {
        $pdo->beginTransaction();

        // Récupérer et valider les données de base
        $id_demande = isset($_POST['id_demande']) ? intval($_POST['id_demande']) : 0;
        $num_contrat = isset($_POST['num_contrat']) ? htmlspecialchars($_POST['num_contrat']) : ($numcontrat['num_contrat'] ?? '');
        $date_contrat = date('Y-m-d');

        // 1. General Data
        $annee_contrat = isset($_POST['annee_contrat']) ? trim($_POST['annee_contrat']) : '';
        $date_demande = isset($_POST['date_demande']) ? trim($_POST['date_demande']) : '';
        $sujet_contrat = isset($_POST['sujet_contrat']) ? trim($_POST['sujet_contrat']) : '';
        $prenom_deposant = isset($_POST['prenom_deposant']) ? trim($_POST['prenom_deposant']) : '';
        $nom_deposant = isset($_POST['nom_deposant']) ? trim($_POST['nom_deposant']) : '';

        // 2. Documents (pièces jointes)
        $libile_pieces = isset($_POST['libile_pieces']) ? array_map('trim', (array)$_POST['libile_pieces']) : [];
        $date_document = isset($_POST['date_document']) ? array_map('trim', (array)$_POST['date_document']) : [];
        $ref_document = isset($_POST['ref_document']) ? array_map('trim', (array)$_POST['ref_document']) : [];
        $date_ref = isset($_POST['date_ref']) ? array_map('trim', (array)$_POST['date_ref']) : [];
        $code_pieces = isset($_POST['code_pieces']) ? array_map('trim', (array)$_POST['code_pieces']) : [];

        // 3. Contract Parties
        $nom_complet_personne = isset($_POST['nom_complet_personne']) ? array_map('trim', (array)$_POST['nom_complet_personne']) : [];
        $numero_document_identite = isset($_POST['numero_document_identite']) ? array_map('trim', (array)$_POST['numero_document_identite']) : [];
        $role = isset($_POST['role']) ? array_map('trim', (array)$_POST['role']) : [];

        /*
        $prenom = isset($_POST['prenom']) ? array_map('trim', (array)$_POST['prenom']) : [];
        $numero_document_identite = isset($_POST['numero_document_identite']) ? array_map('trim', (array)$_POST['numero_document_identite']) : [];
        $nom = isset($_POST['nom']) ? array_map('trim', (array)$_POST['nom']) : [];
        $prenom_pere = isset($_POST['prenom_pere']) ? array_map('trim', (array)$_POST['prenom_pere']) : [];
        $date_emission_document = isset($_POST['date_emission_document']) ? array_map('trim', (array)$_POST['date_emission_document']) : [];
        $sexe = isset($_POST['sexe']) ? array_map('trim', (array)$_POST['sexe']) : [];
        $nationalite = isset($_POST['nationalite']) ? array_map('trim', (array)$_POST['nationalite']) : [];
        $adresse = isset($_POST['adresse']) ? array_map('trim', (array)$_POST['adresse']) : [];
        $profession = isset($_POST['profession']) ? array_map('trim', (array)$_POST['profession']) : [];
        $etat_civil = isset($_POST['etat_civil']) ? array_map('trim', (array)$_POST['etat_civil']) : [];
        $prenom_conjoint = isset($_POST['prenom_conjoint']) ? array_map('trim', (array)$_POST['prenom_conjoint']) : [];
        $nom_conjoint = isset($_POST['nom_conjoint']) ? array_map('trim', (array)$_POST['nom_conjoint']) : [];
        $prenom_pere_conjoint = isset($_POST['prenom_pere_conjoint']) ? array_map('trim', (array)$_POST['prenom_pere_conjoint']) : [];
        $prenom_grand_pere_conjoint = isset($_POST['prenom_grand_pere_conjoint']) ? array_map('trim', (array)$_POST['prenom_grand_pere_conjoint']) : [];
        $surnom_conjoint = isset($_POST['surnom_conjoint']) ? array_map('trim', (array)$_POST['surnom_conjoint']) : [];
        $date_naissance_conjoint = isset($_POST['date_naissance_conjoint']) ? array_map('trim', (array)$_POST['date_naissance_conjoint']) : [];
        $lieu_naissance_conjoint = isset($_POST['lieu_naissance_conjoint']) ? array_map('trim', (array)$_POST['lieu_naissance_conjoint']) : [];
        $nationalite_conjoint = isset($_POST['nationalite_conjoint']) ? array_map('trim', (array)$_POST['nationalite_conjoint']) : [];
        $numero_document_conjoint = isset($_POST['numero_document_conjoint']) ? array_map('trim', (array)$_POST['numero_document_conjoint']) : [];
        $date_document_conjoint = isset($_POST['date_document_conjoint']) ? array_map('trim', (array)$_POST['date_document_conjoint']) : [];
        $lieu_document_conjoint = isset($_POST['lieu_document_conjoint']) ? array_map('trim', (array)$_POST['lieu_document_conjoint']) : [];
        $vendeur_acheteur = isset($_POST['vendeur_acheteur']) ? array_map('trim', (array)$_POST['vendeur_acheteur']) : [];
        $nom_complet_personne = isset($_POST['nom_complet_personne']) ? array_map('trim', (array)$_POST['nom_complet_personne']) : [];
        $statut_contractant = isset($_POST['statut_contractant']) ? array_map('trim', (array)$_POST['statut_contractant']) : [];
        $notes = isset($_POST['notes']) ? array_map('trim', (array)$_POST['notes']) : [];
        $id_demande_array = isset($_POST['id_demande']) ? (array)$_POST['id_demande'] : [$id_demande];
*/
        // 4. Property Burdens (dessin_immobilier1)
        $nom_droit1 = isset($_POST['nom_droit1']) ? array_map('trim', (array)$_POST['nom_droit1']) : [];
        $sujet_contrat1 = isset($_POST['sujet_contrat1']) ? array_map('trim', (array)$_POST['sujet_contrat1']) : [];
        $unite1 = isset($_POST['unite1']) ? array_map('trim', (array)$_POST['unite1']) : [];
        $detail_general = isset($_POST['detail_general']) ? array_map('trim', (array)$_POST['detail_general']) : [];
        $contenu1 = isset($_POST['contenu1']) ? array_map('trim', (array)$_POST['contenu1']) : [];
        $valeur_prix1 = isset($_POST['valeur_prix1']) ? array_map('trim', (array)$_POST['valeur_prix1']) : [];
        $dure1 = isset($_POST['dure1']) ? array_map('trim', (array)$_POST['dure1']) : [];
        $surplus1 = isset($_POST['surplus1']) ? array_map('trim', (array)$_POST['surplus1']) : [];

        // 5. Autres sections (dessin_immobilier2, dessin_immobilier3, etc.)
        /*$date_inscri2 = isset($_POST['date_inscri2']) ? (is_array($_POST['date_inscri2']) ? $_POST['date_inscri2'][0] : $_POST['date_inscri2']) : '';
        $lieu_inscri2 = isset($_POST['lieu_inscri2']) ? (is_array($_POST['lieu_inscri2']) ? $_POST['lieu_inscri2'][0] : $_POST['lieu_inscri2']) : '';
        $doc2 = isset($_POST['doc2']) ? (is_array($_POST['doc2']) ? $_POST['doc2'][0] : $_POST['doc2']) : '';
        $num_inscri2 = isset($_POST['num_inscri2']) ? (is_array($_POST['num_inscri2']) ? $_POST['num_inscri2'][0] : $_POST['num_inscri2']) : '';
        $num_succursale2 = isset($_POST['num_succursale2']) ? (is_array($_POST['num_succursale2']) ? $_POST['num_succursale2'][0] : $_POST['num_succursale2']) : '';*/
        $regime_finance_couple3 = isset($_POST['regime_finance_couple3']) ? (is_array($_POST['regime_finance_couple3']) ? $_POST['regime_finance_couple3'][0] : $_POST['regime_finance_couple3']) : '';
        $remarques3 = isset($_POST['remarques3']) ? (is_array($_POST['remarques3']) ? $_POST['remarques3'][0] : $_POST['remarques3']) : '';

        /*$valeur_contrat_dinar = isset($_POST['valeur_contrat_dinar']) ? (is_array($_POST['valeur_contrat_dinar']) ? $_POST['valeur_contrat_dinar'][0] : $_POST['valeur_contrat_dinar']) : '';
        $prix_ecriture = isset($_POST['prix_ecriture']) ? (is_array($_POST['prix_ecriture']) ? $_POST['prix_ecriture'][0] : $_POST['prix_ecriture']) : '';*/

        $statut2 = isset($_POST['statut2']) ? (is_array($_POST['statut2']) ? $_POST['statut2'][0] : $_POST['statut2']) : '';
        $redacteur2 = isset($_POST['redacteur2']) ? (is_array($_POST['redacteur2']) ? $_POST['redacteur2'][0] : $_POST['redacteur2']) : '';
        $redaction2 = isset($_POST['redaction2']) ? (is_array($_POST['redaction2']) ? $_POST['redaction2'][0] : $_POST['redaction2']) : '';
        $revision2 = isset($_POST['revision2']) ? (is_array($_POST['revision2']) ? $_POST['revision2'][0] : $_POST['revision2']) : '';
        $validationFinal2 = isset($_POST['validationFinal2']) ? (is_array($_POST['validationFinal2']) ? $_POST['validationFinal2'][0] : $_POST['validationFinal2']) : '';

        $valeur_dinar3 = isset($_POST['valeur_dinar3']) ? (is_array($_POST['valeur_dinar3']) ? $_POST['valeur_dinar3'][0] : $_POST['valeur_dinar3']) : '';
        $pourcent3 = isset($_POST['pourcent3']) ? (is_array($_POST['pourcent3']) ? $_POST['pourcent3'][0] : $_POST['pourcent3']) : '';
        $montant_dinar3 = isset($_POST['montant_dinar3']) ? (is_array($_POST['montant_dinar3']) ? $_POST['montant_dinar3'][0] : $_POST['montant_dinar3']) : '';

        $prenom_personne = isset($_POST['prenom_personne']) ? (is_array($_POST['prenom_personne']) ? $_POST['prenom_personne'][0] : $_POST['prenom_personne']) : '';
        $prenom_pere = isset($_POST['prenom_pere']) ? (is_array($_POST['prenom_pere']) ? $_POST['prenom_pere'][0] : $_POST['prenom_pere']) : '';
        $prenom_grandpere = isset($_POST['prenom_grandpere']) ? (is_array($_POST['prenom_grandpere']) ? $_POST['prenom_grandpere'][0] : $_POST['prenom_grandpere']) : '';
        $nom_personne = isset($_POST['nom_personne']) ? (is_array($_POST['nom_personne']) ? $_POST['nom_personne'][0] : $_POST['nom_personne']) : '';
        $statut = isset($_POST['statut']) ? (is_array($_POST['statut']) ? $_POST['statut'][0] : $_POST['statut']) : '';
        $signature = isset($_POST['signature']) ? (is_array($_POST['signature']) ? $_POST['signature'][0] : $_POST['signature']) : '';

        $id_montant1 = isset($_POST['id_montant1']) ? (is_array($_POST['id_montant1']) ? $_POST['id_montant1'][0] : $_POST['id_montant1']) : '';
        $partieabstrait1 = isset($_POST['partieabstrait1']) ? (is_array($_POST['partieabstrait1']) ? $_POST['partieabstrait1'][0] : $_POST['partieabstrait1']) : '';
        $montant_obligatoire1 = isset($_POST['montant_obligatoire1']) ? (is_array($_POST['montant_obligatoire1']) ? $_POST['montant_obligatoire1'][0] : $_POST['montant_obligatoire1']) : '';
        $montant_paye1 = isset($_POST['montant_paye1']) ? (is_array($_POST['montant_paye1']) ? $_POST['montant_paye1'][0] : $_POST['montant_paye1']) : '';
        $num_recu1 = isset($_POST['num_recu1']) ? (is_array($_POST['num_recu1']) ? $_POST['num_recu1'][0] : $_POST['num_recu1']) : '';
        $date_payement1 = isset($_POST['date_payement1']) ? (is_array($_POST['date_payement1']) ? $_POST['date_payement1'][0] : $_POST['date_payement1']) : '';

        // 6. Contract Terms
        $contenue_chapitre = isset($_POST['contenue_chapitre']) ? trim($_POST['contenue_chapitre']) : '';

        // Appels aux fonctions de sauvegarde
        /*$personne = $db->personneContratc($pdo, $prenom, $numero_document_identite, $nom, $prenom_pere, $date_emission_document, $sexe, $nationalite, $adresse, $profession, $etat_civil, $prenom_conjoint, $nom_conjoint, $prenom_pere_conjoint, $prenom_grand_pere_conjoint, $surnom_conjoint, $date_naissance_conjoint, $lieu_naissance_conjoint, $nationalite_conjoint, $numero_document_conjoint, $date_document_conjoint, $lieu_document_conjoint, $vendeur_acheteur, $id_demande_array, $nom_complet_personne, $statut_contractant, $notes);
        if ($personne === false) throw new Exception("Erreur personneContratc");*/

        $dessin1 = $db->dessin_immobilier1($pdo, $nom_droit1[0] ?? '', $sujet_contrat1[0] ?? '', $unite1[0] ?? '', $detail_general[0] ?? '', $contenu1[0] ?? '', $valeur_prix1[0] ?? '', $dure1[0] ?? '', $surplus1[0] ?? '');
        if ($dessin1 === false) throw new Exception("Erreur dessin_immobilier1");

        $dessin2 = $db->dessin_immobilier2($pdo, $date_inscri2, $lieu_inscri2, $doc2, $num_inscri2, $num_succursale2);
        if ($dessin2 === false) throw new Exception("Erreur dessin_immobilier2");

        $dessin3 = $db->dessin_immobilier3($pdo, $regime_finance_couple3, $remarques3);
        if ($dessin3 === false) throw new Exception("Erreur dessin_immobilier3");

        $dessin4 = $db->dessin_immobilier4($pdo, $valeur_contrat_dinar, $prix_ecriture, $id_demande, $statut_contractant);
        if ($dessin4 === false) throw new Exception("Erreur dessin_immobilier4");

        $p1 = $db->perception1($pdo, $id_montant1, $partieabstrait1, $montant_obligatoire1, $montant_paye1, $num_recu1, $date_payement1);
        if ($p1 === false) throw new Exception("Erreur perception1");

        $p2 = $db->perception2($pdo, $statut2, $redacteur2, $redaction2, $revision2, $validationFinal2);
        if ($p2 === false) throw new Exception("Erreur perception2");

        $p3 = $db->perception3($pdo, $valeur_dinar3, $pourcent3, $montant_dinar3);
        if ($p3 === false) throw new Exception("Erreur perception3");

        /*$personne1 = $db->idPersonnes($pdo, $prenom_personne, $prenom_pere, $prenom_grandpere, $nom_personne, $statut, $signature);
        if ($personne1 === false) throw new Exception("Erreur idPersonnes");*/

        $chapitre = $db->insertChapitres($pdo, $contenue_chapitre);
        if ($chapitre === false) throw new Exception("Erreur insertChapitres");

        // Mettre à jour l'état de la demande (une seule fois)
        $updateStmt = $pdo->prepare("UPDATE T_demande SET etat_demande = 1 WHERE id_demande = :id_demande");
        error_log("Executing query: " . $updateStmt->queryString); 
        $updateStmt->execute(['id_demande' => $id_demande]);
        if ($updateStmt->rowCount() === 0) {
            throw new Exception("Aucune ligne mise à jour pour id_demande = $id_demande. Vérifiez l'existence de la table t_demande.");
        }

        // Sauvegarde des pièces jointes
        foreach ($libile_pieces as $index => $libile) {
            $stmt = $pdo->prepare("INSERT INTO pieces_jointes (id_demande, libile_pieces, date_document, ref_document, date_ref, code_pieces) VALUES (:id_demande, :libile_pieces, :date_document, :ref_document, :date_ref, :code_pieces)");
            $stmt->execute([$id_demande, $libile, $date_document[$index] ?? '', $ref_document[$index] ?? '', $date_ref[$index] ?? '', $code_pieces[$index] ?? '']);
        }
        

        $pdo->commit();
        header("Location: listeContrat.php?id_demande=" . urlencode($id_demande) . "&num_contrat=" . urlencode($num_contrat) . "&date_contrat=" . urlencode($date_contrat));
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        echo "❌ Erreur lors de la sauvegarde des données : " . htmlspecialchars($e->getMessage());
        if ($e instanceof PDOException) {
            error_log("SQL Error: " . print_r($e->errorInfo, true));
        }
    }
}


?> 




<!DOCTYPE html>
<html dir="rtl" lang="ar">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>نظام معالجة العقود</title>
    <link rel="stylesheet" href="css/Traitement.css" />
    <style>
        .save-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 30px;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            color: white;
            background: linear-gradient(135deg, #4DD0E1 0%, #00BCD4 100%);
            border: none;
            border-radius: 25px;
            box-shadow: 0 2px 8px rgba(0, 188, 212, 0.3);
            cursor: pointer;
            transition: all 0.3s ease;
            min-width: 150px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .save-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .save-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 188, 212, 0.4);
            background: linear-gradient(135deg, #5DD5E5 0%, #00C8D8 100%);
        }

        .save-button:hover::before {
            left: 100%;
        }

        .save-button:active {
            transform: translateY(0);
            box-shadow: 0 2px 6px rgba(0, 188, 212, 0.3);
        }

        .save-button .icon {
            margin-right: 8px;
            margin-left: 0;
            font-size: 16px;
            transition: transform 0.3s ease;
        }

        .save-button:hover .icon {
            transform: scale(1.1);
        }
    </style>
  </head>

  <body>
    <div class="container22">
      <!-- Sidebar Navigation -->
      <div class="sidebar">
        <div id="general-data" class="menu-item active"> 📊 معطيات عامة</div>
        <div id="documents" class="menu-item"> 📄 المؤيدات</div>
        <div id="contract-parties" class="menu-item"> 🤝 أطراف التعاقد</div>
        <div id="property-burdens" class="menu-item"> 🏗️ التحملات على العقار</div>
        <div id="contract-terms" class="menu-item"> 📑 الأحكام التعاقدية</div>
        <div id="extraction" class="menu-item"> 💰 الاستخلاص</div>
        <button onclick="window.location.href='logout.php'" data-section="logout" class="menu-item" style="color: red; background: none; border: none; cursor: pointer;">
          ❌ تسجيل الخروج
        </button>
      </div>

       <!-- General Data Section -->
      <div id="general-data-content" class="main-content active">
        <form action="save_contract.php" method="POST">
                <div class="top-bar">
                  <div class="search-form">
                    <span>عدد مطلب التحرير</span>
                    <input type="text" class="search-input" name="id_demande" 
                    value="<?php echo isset($demande['id_demande']) ? htmlspecialchars($demande['id_demande']) : ''; ?>" />
                    <span>/</span>
                    <input type="number" name="annee_contrat" value="<?php echo isset($anneecontrat['annee_contrat']) ? htmlspecialchars($anneecontrat['annee_contrat']) : ''; ?>" />           
                    <span>تاريخه</span>
                    <input type="text" class="search-input" name="date_demande" 
                    value="<?php echo isset($demande['date_demande']) ? htmlspecialchars($demande['date_demande']) : ''; ?>" />
                    <span>عدد العقد</span>
                    <input type="number" name="num_contrat" value="<?= $numcontrat ? htmlspecialchars($numcontrat['num_contrat']) : '' ?>" />                    
                  </div>
                  <img src="media/logo.png" alt="ONPFF" class="logo" />
                </div>
                <div id="form-container">
                  <div class="subject-field">
                    <span>موضوع العقد</span>
                    <input type="text" name="sujet_contrat" value="<?= $sujetducontrat ? htmlspecialchars($sujetducontrat['sujet_contrat']) : '' ?>" />                    </div>
                  <div class="section-title">القسم الأول : البيانات المتعلقة بطالب الخدمة</div>
                  <div class="person-info">
                    <div class="person-title">طالب الخدمة</div>
                      <div class="person-field">
                        <span style="margin: 3px">الاسم</span>
                        <input type="text" name="prenom_deposant" value="<?= $deposant ? htmlspecialchars($deposant['prenom_deposant']) : '' ?>" />
                      </div>
                    <div class="person-field">
                      <span style="margin: 3px">اللقب</span>
                      <input type="text" name="nom_deposant" value="<?= $deposant ? htmlspecialchars($deposant['nom_deposant']) : '' ?>" />
                    </div>
                  </div>
                  <div class="section-title"> القسم الثاني : البيانات المتعلقة بهوية و التزامات المحرر</div>
                  <div class="identity-section">
                  <div class="identity-title">هوية و التزامات المحرر</div>
                  <div class="identity-text">
                    عملا بأحكام الفصل 377 ثالثا من مجلة الحقوق العينية أشهد أنا محرر العقد :
                  </div>
                  <!-- Conteneur global centré -->
                  <div style="display: flex; justify-content: center; margin-top: 30px; direction: rtl;">

                    <!-- Conteneur interne en ligne -->
                    <div style="display: flex; gap: 20px; align-items: center;">

                      <!-- Champ prénom -->
                      <div>
                        <label for="prenom_admin">اسم المحرر</label><br>
                        <input type="text" id="prenom" name="prenom"
                          value="<?php echo isset($_SESSION['userAuth']['prenom_admin']) ? htmlspecialchars($_SESSION['userAuth']['prenom_admin']) : ''; ?>"
                          readonly />
                      </div>

                      <!-- Champ nom -->
                      <div>
                        <label for="nom_admin">لقب المحرر</label><br>
                        <input type="text" id="nom" name="nom"
                          value="<?php echo isset($_SESSION['userAuth']['nom_admin']) ? htmlspecialchars($_SESSION['userAuth']['nom_admin']) : ''; ?>"
                          readonly />
                      </div>

                      <!-- Champ CIN -->
                      <div>
                        <label for="cin_admin">رقم التعريف</label><br>
                        <input type="text" id="cin" name="cin"
                          value="<?php echo isset($_SESSION['userAuth']['cin_admin']) ? htmlspecialchars($_SESSION['userAuth']['cin_admin']) : ''; ?>"
                          readonly />
                      </div>
                    </div>
                  </div>
                  <!-- Section suivante -->
                  <div class="identity-section">
                    <div class="identity-title">
                      إني إطلعت على الرسم (الرسوم) العقاري(ة)
                    </div>
                  </div>
                </div>
                  <div class="final-text"> موضوع هذا الصك و  أشعرت الأطراف بالحالة القانونية الواردة به (بها) و المضمنة صلب هذا العقد و بعدم وجود مانع التحرير<br/></div>
                  </div>
        </form>
      </div>
            <!-- Documents Section -->
      <div id="documents-content" class="main-content">
            <div class="top-bar">
                  <div class="search-form">
                    <span>عدد مطلب التحرير</span>
                    <input type="text" class="search-input" name="id_demande" 
                    value="<?php echo isset($demande['id_demande']) ? htmlspecialchars($demande['id_demande']) : ''; ?>" />
                    <span>/</span>
                    <input type="number" name="annee_contrat" value="<?php echo isset($anneecontrat['annee_contrat']) ? htmlspecialchars($anneecontrat['annee_contrat']) : ''; ?>" />                    
                    <span>تاريخه</span>
                    <input type="text" class="search-input" name="date_demande" 
                    value="<?php echo isset($demande['date_demande']) ? htmlspecialchars($demande['date_demande']) : ''; ?>" />
                    <span>عدد العقد</span>
                    <input type="number" name="num_contrat" value="<?= $numcontrat ? htmlspecialchars($numcontrat['num_contrat']) : '' ?>" />                    
                  </div>
                  <img src="media/logo.png" alt="ONPFF" class="logo" />
                </div>

              <div class="section-title">القسم الثالث : البيانات المتعلقة بالمؤيدات</div>
              <form method="POST" action="">
              <table id="documents-table">
                  <thead>
                      <tr>
                          <th>ع ر</th>
                          <th>الوثيقة</th>
                          <th>تاريخها</th>
                          <th>مراجع التسجيل</th>
                          <th>تاريخها</th>
                          <th>نوع الوثيقة</th>
                      </tr>
                  </thead>
                  <tbody>
                      <?php if (!empty($pieces_jointes)): ?>
                          <?php $compteur = 1; ?>
                          <?php foreach ($pieces_jointes as $piece): ?>
                              <tr>
                                <td><?php echo $compteur++; ?></td>
                                <td><input type="text" name="libile_pieces[]" value="<?php echo htmlspecialchars($piece['libile_pieces']); ?>" /></td>
                                <td><input type="text" name="date_document[]" value="<?php echo htmlspecialchars($piece['date_document']); ?>" /></td>
                                <td><input type="text" name="ref_document[]" value="<?php echo htmlspecialchars($piece['ref_document']); ?>" /></td>
                                <td><input type="text" name="date_ref[]" value="<?php echo htmlspecialchars($piece['date_ref']); ?>" /></td>
                                <td><input type="text" name="code_pieces[]" value="<?php echo htmlspecialchars($piece['code_pieces']); ?>" /></td>
                                <input type="hidden" name="id_demande[]" value="<?php echo $id_demande; ?>" />
                              </tr>
                          <?php endforeach; ?>
                      <?php else: ?>
                          <tr>
                              <td>1</td>
                              <td><input type="text" name="libile_pieces[]" /></td>
                              <td><input type="text" name="date_document[]" /></td>
                              <td><input type="text" name="ref_inscription[]" /></td>
                              <td><input type="text" name="date_ref[]" /></td>
                              <td><input type="text" name="code_pieces[]" /></td>
                              <input type="hidden" name="id_demande[]" value="<?php echo $id_demande; ?>" />
                          </tr>
                      <?php endif; ?>
                  </tbody>
              </table>
              </form>
      </div>
      <!-- Contract Parties Section -->
      <div id="contract-parties-content" class="main-content">
        <div class="top-bar">
                  <div class="search-form">
                    <span>عدد مطلب التحرير</span>
                    <input type="text" class="search-input" name="id_demande" 
                    value="<?php echo isset($demande['id_demande']) ? htmlspecialchars($demande['id_demande']) : ''; ?>" />
                    <span>/</span>
                    <input type="number" name="annee_contrat" value="<?php echo isset($anneecontrat['annee_contrat']) ? htmlspecialchars($anneecontrat['annee_contrat']) : ''; ?>" />                      
                    <span>تاريخه</span>
                    <input type="text" class="search-input" name="date_demande" 
                    value="<?php echo isset($demande['date_demande']) ? htmlspecialchars($demande['date_demande']) : ''; ?>" />
                    <span>عدد العقد</span>
                    <input type="number" name="num_contrat" value="<?= $numcontrat ? htmlspecialchars($numcontrat['num_contrat']) : '' ?>" />                    
                  </div>
                  <img src="media/logo.png" alt="ONPFF" class="logo" />
                </div>

        <div class="table-container">
          <div class="section-title">القسم الرابع : البيانات المتعلقة بأطراف التعاقد</div>
          <form method="POST" action="">
          <table id="parties-table">
            <thead>
              <tr>
                <th>الاسم الثلاثي للمتعاقد</th>
                <th>رقم التعريف</th>
                <th>الصفة</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><input type="text"  id="nom_complet_personne[]" required /></td>
                <td><input type="text" name="numero_document_identite[]" required /></td>
                <td>
                  <select id="role" name="role[]" required>
                    <option value="">صفة المتعاقد ..</div></option>
                    <option value="vendeur">البائع</option>
                    <option value="acheteur">المشتري</option>
                  </select>               
                </td>
              </tr>
            </tbody>
          </table>
          </form>
          <div class="form-actions">
            <button type="button" class="btn-delete">حذف</button>
            <button type="button" class="btn-add">إضافة سطر</button>
          </div>
          </div>
        </div>
      </div>
    
    
      <!-- Property Burdens Section -->
      <div id="property-burdens-content" class="main-content">
            <div class="top-bar">
              <div class="search-form">
                <span>عدد مطلب التحرير</span>
                <input type="text" class="search-input" name="id_demande" value="<?php echo isset($demande['id_demande']) ? htmlspecialchars($demande['id_demande']) : ''; ?>" />
                <span>/</span>
                <input type="number" name="annee_contrat" value="<?php echo isset($anneecontrat['annee_contrat']) ? htmlspecialchars($anneecontrat['annee_contrat']) : ''; ?>" />           
                <input type="text" class="search-input" name="date_demande" value="<?php echo isset($demande['date_demande']) ? htmlspecialchars($demande['date_demande']) : ''; ?>" />
                <span>عدد العقد</span>
                <input type="number" name="num_contrat" value="<?= $numcontrat ? htmlspecialchars($numcontrat['num_contrat']) : '' ?>" />                    
              </div>
              <img src="media/logo.png" alt="ONPFF" class="logo" />
            </div>
            <div class="table-container">
              <form method="POST" action="">
                <div class="section-title"> القسم الخامس : البيانات المتعلقة بموضوع التعاقد و مراجع انجراره بالرسم العقاري </div>
                <table>
                    <thead>
                        <tr>
                            <th>عدد الحق</th>
                            <th>موضوع التعاقد</th>
                            <th>الوحدة</th>
                            <th>التجزئة العامة</th>
                            <th>المحتوى</th>
                            <th>الثمن</th>
                            <th>المدة</th>
                            <th>الفائض</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="text" name="nom_droit1[]" required /></td>
                            <td><input type="text" name="sujet_contrat1[]" required /></td>
                            <td><input type="text" name="unite1[]" required /></td>
                            <td><input type="text" name="detail_general[]" required /></td>
                            <td><input type="text" name="contenu1[]" required /></td>
                            <td><input type="text" name="valeur_prix1[]" required /></td>
                            <td><input type="text" name="dure1[]" required /></td>
                            <td><input type="text" name="surplus1[]" required /></td>
                        </tr>
                    </tbody>
                </table>
                <div class="form-actions">
                  <button type="button" class="btn-delete">حذف</button>
                  <button type="button" class="btn-add">إضافة سطر</button>
                </div>
              </form>
              

                <h3>بيانات تتعلق بمراجع انجرار الترسيم</h3>
                <table>
                  <thead>
                    <tr>
                      <th> التاريخ</th>
                      <th> الايداع</th>
                      <th>المجلد</th>
                      <th>العدد </th>
                      <th>ع.الفرعي</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td><input type="date"></td>
                      <td><input type="text"></td>
                      <td><input type="text"></td>
                      <td><input type="text"></td>
                      <td><input type="text"></td>
                    </tr>
                  </tbody>
                </table>
                <div class="form-actions">
                  <button type="button" class="btn-delete">حذف</button>
                  <button type="button" class="btn-add">إضافة سطر</button>
                </div>
                <form method="POST" action="">
                  <h3>البيانات الأخرى المتعلقة بالحق</h3>
                  <table>
                    <thead>
                      <tr>
                        <th> النظام المالي للزواج</th>
                        <th> ملاحظات أخرى</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                      <td><input type="text" name="regime_finance_couple3[]" required /></td>
                      <td><input type="text" name="remarques3[]" required /></td>
                      
                      </tr>
                    </tbody>
                  </table>
                  
                  </form>
                  <div cass="form-actions">
                    <button type="button" class="btn-delete">حذف</button>
                    <button type="button" class="btn-add">إضافة سطر</button>
                  </div>          
                </form> 

                

                  <h3>المبلغ الجملي لموضوع التعاقد</h3>
                  <table>
                      <thead>
                          <tr>
                              <th>قيمة موضوع التعاقد بالدينار</th>
                              <th>المبلغ بلسان القلم</th>
                          </tr>
                      </thead>
                      <tbody>
                          <tr>
                              <td><input type="text"></td>
                              <td><input type="text"></td>
                          </tr>
                      </tbody>
                  </table>
                  <div class="form-actions">
                      <button type="button" class="btn-delete">حذف</button>
                      <button type="button" class="btn-add">إضافة سطر</button>
                  </div>
                
              </form>
            </div>          
        </div>
      </div>

      <!-- Contract Terms Section -->
      <div id="contract-terms-content" class="main-content">
        <div class="top-bar">
          <div class="search-form">
            <span>عدد مطلب التحرير</span>
            <input type="text" class="search-input" name="id_demande" value="<?php echo isset($demande['id_demande']) ? htmlspecialchars($demande['id_demande']) : ''; ?>" />
            <span>/</span>
            <input type="number" name="annee_contrat" value="<?php echo isset($anneecontrat['annee_contrat']) ? htmlspecialchars($anneecontrat['annee_contrat']) : ''; ?>" />                       
            <span>تاريخه</span>
            <input type="text" class="search-input" name="date_demande" value="<?php echo isset($demande['date_demande']) ? htmlspecialchars($demande['date_demande']) : ''; ?>" />
            <span>عدد العقد</span>
            <input type="number" name="num_contrat" value="<?= $numcontrat ? htmlspecialchars($numcontrat['num_contrat']) : '' ?>" />                    
          </div>
          <img src="media/logo.png" alt="ONPFF" class="logo" />
        </div>

        <h2 class="section-title"> القسم السادس : البيانات المتعلقة بالأحكام التعاقدية</h2>
        <form method="POST" action="">
          <div>المحتوى</div>
          <textarea
            name="contenue_chapitre"
            style="width: 80%; height: 50%; border-radius: 10px"
            required
          ></textarea>
        </form>
      </div>

      <!-- Extraction Section -->
      <div id="extraction-content" class="main-content">
        <div class="top-bar">
          <div class="search-form">
            <span>عدد مطلب التحرير</span>
            <input type="text" class="search-input" name="id_demande" value="<?php echo isset($demande['id_demande']) ? htmlspecialchars($demande['id_demande']) : ''; ?>" />
            <span>/</span>
            <input type="number" name="annee_contrat" value="<?php echo isset($anneecontrat['annee_contrat']) ? htmlspecialchars($anneecontrat['annee_contrat']) : ''; ?>" />                       
            <span>تاريخه</span>
            <input type="text" class="search-input" name="date_demande" value="<?php echo isset($demande['date_demande']) ? htmlspecialchars($demande['date_demande']) : ''; ?>" />
            <span>عدد العقد</span>
            <input type="number" name="num_contrat" value="<?= $numcontrat ? htmlspecialchars($numcontrat['num_contrat']) : '' ?>" />                    
          </div>
          <img src="media/logo.png" alt="ONPFF" class="logo" />
        </div>

        <div class="section-title">القسم السابع : امضاءات الأطراف و التعريف بها</div>
        
        <!-- Signatures Form -->
        <form method="POST" action="" class="extraction-form">
          <input type="hidden" name="id_demande" value="<?php echo isset($demande['id_demande']) ? htmlspecialchars($demande['id_demande']) : ''; ?>" />
          <input type="hidden" name="num_contrat" value="<?= $numcontrat ? htmlspecialchars($numcontrat['num_contrat']) : '' ?>" />
          
          <table class="documents-table">
            <thead>
              <tr>
                <th>الاسم</th>
                <th>اسم الأب</th>
                <th>اسم الجد</th>
                <th>اللقب</th>
                <th>الصفة</th>
                <th>الامضاءات</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><input type="text"></td>
                <td><input type="text"></td>
                <td><input type="text"></td>
                <td><input type="text" ></td>
                <td><input type="text"></td>
                <td><input type="text"></td>
              </tr>
            </tbody>
          </table>
          <div class="form-actions">
            <button type="button" class="btn-delete">حذف</button>
            <button type="button" class="btn-add">إضافة سطر</button>
          </div>
        </form>

        <!-- Fees Form -->
        <form method="POST" action="" class="extraction-form">
          <input type="hidden" name="id_demande" value="<?php echo isset($demande['id_demande']) ? htmlspecialchars($demande['id_demande']) : ''; ?>" />
          <input type="hidden" name="num_contrat" value="<?= $numcontrat ? htmlspecialchars($numcontrat['num_contrat']) : '' ?>" />
          
          <h3>معاليم التحرير و مراجع الاستخلاص</h3>
          <table class="documents-table">
            <thead>
              <tr>
                <th>معرف المعلوم</th>
                <th> الجهة المستخلصة</th>
                <th> المبلغ المستوجب</th>
                <th>المبلغ المستخلص</th>
                <th>عدد الوصل</th>
                <th>التاريخ</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><input type="text" name="id_montant1[]" required/></td>
                <td><input type="text" name="partieabstrait1[]" required/></td>
                <td><input type="text" name="montant_obligatoire1[]" required/></td>
                <td><input type="text" name="montant_paye1[]" required/></td>
                <td><input type="text" name="num_recu1[]" required/></td>
                <td><input type="date" name="date_payement1[]" required/></td>
              </tr>
            </tbody>
          </table>
          <div class="form-actions">
            <button type="button" class="btn-delete">حذف</button>
            <button type="button" class="btn-add">إضافة سطر</button>
          </div>
        </form>

        <!-- Total Form -->
        <form method="POST" action="" class="extraction-form">
          <input type="hidden" name="id_demande" value="<?php echo isset($demande['id_demande']) ? htmlspecialchars($demande['id_demande']) : ''; ?>" />
          <input type="hidden" name="num_contrat" value="<?= $numcontrat ? htmlspecialchars($numcontrat['num_contrat']) : '' ?>" />
          
          <h3>المجموع</h3>
          <table class="documents-table">
            <thead>
              <tr>
                <th> مجموع المبلغ المستوجب</th>
                <th> مجموع المبلغ المستخلص </th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><input type="text"></td>
                <td><input type="text"></td>
              </tr>
            </tbody>
          </table>
          <div class="form-actions">
            <button type="button" class="btn-delete">حذف</button>
            <button type="button" class="btn-add">إضافة سطر</button>
          </div>
        </form>

        <!-- Contract Confirmation Form -->
        <form method="POST" action="" class="extraction-form">
          <input type="hidden" name="id_demande" value="<?php echo isset($demande['id_demande']) ? htmlspecialchars($demande['id_demande']) : ''; ?>" />
          <input type="hidden" name="num_contrat" value="<?= $numcontrat ? htmlspecialchars($numcontrat['num_contrat']) : '' ?>" />
          
          <h3>البيانات المتعلقة بتأكيد العقد</h3>
          <table class="documents-table">
            <thead>
              <tr>
                <th>الصفة</th>
                <th> التلقي</th>
                <th>التحرير</th>
                <th> المراجعة</th>
                <th>المصادقة النهائية</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><input type="text" name="statut2[]" required/></td>
                <td><input type="text" name="redacteur2[]" required/></td>
                <td><input type="text" name="redaction2[]" required/></td>
                <td><input type="text" name="revision2[]" required/></td>
                <td><input type="text" name="validationFinal2[]" required/></td>
              </tr>
            </tbody>
          </table>
          <div class="form-actions">
            <button type="button" class="btn-delete">حذف</button>
            <button type="button" class="btn-add">إضافة سطر</button>
          </div>
        </form>

        <!-- Registration Form -->
        <form method="POST" action="" class="extraction-form">
          <input type="hidden" name="id_demande" value="<?php echo isset($demande['id_demande']) ? htmlspecialchars($demande['id_demande']) : ''; ?>" />
          <input type="hidden" name="num_contrat" value="<?= $numcontrat ? htmlspecialchars($numcontrat['num_contrat']) : '' ?>" />
          
          <h3>البيانات المتعلقة بتسجيل العقد لدى القباضة المالية و استخلاص معلوم ادارة الملكية العقارية</h3>
          <table class="documents-table">
            <thead>
              <tr>
                <th> القيمة بالدينار</th>
                <th>  النسبة</th>
                <th>  المبلغ بالدينار</th>
                <th>ختم قابض التسجيل و امضاؤه</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><input type="text" name="valeur_dinar3[]" required/></td>
                <td><input type="text" name="pourcent3[]" required/></td>
                <td><input type="text" name="montant_dinar3[]" required/></td>
                <td><input type="text" name="signature3[]" required/></td>
              </tr>
            </tbody>
          </table>
          <div class="form-actions">
            <button type="button" class="btn-delete">حذف</button>
            <button type="button" class="btn-add">إضافة سطر</button>
          </div>
        </form>

        <!-- Property Services Form -->
        <form method="POST" action="" class="extraction-form">
          <input type="hidden" name="id_demande" value="<?php echo isset($demande['id_demande']) ? htmlspecialchars($demande['id_demande']) : ''; ?>" />
          <input type="hidden" name="num_contrat" value="<?= $numcontrat ? htmlspecialchars($numcontrat['num_contrat']) : '' ?>" />
          
          <h3>البيانات المتعلقة بتصفية معاليم الخدمات الراجعة لادارة الملكية العقارية</h3>
          <table class="documents-table">
            <thead>
              <tr>
                <th>  التسمية</th>
                <th>  القيمة بالدينار</th>
                <th> النسبة</th>
                <th> المبلغ بالدينار</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><input type="text" name="nom4[]" required/></td>
                <td><input type="text" name="valeur_dinar4[]" required/></td>
                <td><input type="text" name="pourcent4[]" required/></td>
                <td><input type="text" name="montant_dinar4[]" required/></td>
              </tr>
            </tbody>
            </table>
            <a href="verifierContrat.php" class="save-button">
                <span class="icon">💾</span>
                حفظ البيانات
            </a>
            </div>
          </div>
          
        </div>
      </div>
</div>
<script src="script/script.js"></script>
</body>
</html>