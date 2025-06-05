<?php
require_once("connect.php");

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class TraitementManager {
    private $db;
    private $pdo;

    public function __construct() {
        $this->db = new ClsConnect();
        $this->pdo = $this->db->getConnection();
    }

    // Sauvegarder les données générales
    public function saveGeneralData($data) {
        try {
            $sql = "INSERT INTO donnees_generales (id_demande, num_contrat, sujet_contrat, nom_deposant, prenom_deposant) 
                    VALUES (:id_demande, :num_contrat, :sujet_contrat, :nom_deposant, :prenom_deposant)";
            
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($data);
        } catch (PDOException $e) {
            return false;
        }
    }

    // Sauvegarder les documents
    public function saveDocuments($documents) {
        try {
            $sql = "INSERT INTO documents (id_demande, libile_pieces, date_document, ref_document, date_ref, code_pieces) 
                    VALUES (:id_demande, :libile_pieces, :date_document, :ref_document, :date_ref, :code_pieces)";
            
            $stmt = $this->pdo->prepare($sql);
            foreach ($documents as $doc) {
                $stmt->execute($doc);
            }
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    // Sauvegarder les parties contractantes
    public function saveContractParties($parties) {
        try {
            $sql = "INSERT INTO parties_contrat (id_demande, nom, prenom, cin, role) 
                    VALUES (:id_demande, :nom, :prenom, :cin, :role)";
            
            $stmt = $this->pdo->prepare($sql);
            foreach ($parties as $party) {
                $stmt->execute($party);
            }
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    // Sauvegarder les charges sur la propriété
    public function savePropertyBurdens($burdens) {
        try {
            $sql = "INSERT INTO charges_propriete (id_demande, nom_droit, sujet_contrat, unite, detail_general, 
                    contenu, valeur_prix, dure, surplus) 
                    VALUES (:id_demande, :nom_droit, :sujet_contrat, :unite, :detail_general, 
                    :contenu, :valeur_prix, :dure, :surplus)";
            
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($burdens);
        } catch (PDOException $e) {
            return false;
        }
    }

    // Sauvegarder les termes du contrat
    public function saveContractTerms($terms) {
        try {
            $sql = "INSERT INTO termes_contrat (id_demande, contenue_chapitre) 
                    VALUES (:id_demande, :contenue_chapitre)";
            
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($terms);
        } catch (PDOException $e) {
            return false;
        }
    }

    // Sauvegarder les signatures et authentifications
    public function saveSignatures($signatures) {
        try {
            $sql = "INSERT INTO signatures (id_demande, prenom_personne, prenom_pere, prenom_grandpere, 
                    nom_personne, statut, signature) 
                    VALUES (:id_demande, :prenom_personne, :prenom_pere, :prenom_grandpere, 
                    :nom_personne, :statut, :signature)";
            
            $stmt = $this->pdo->prepare($sql);
            foreach ($signatures as $sig) {
                $stmt->execute($sig);
            }
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    // Sauvegarder les frais d'écriture
    public function saveWritingFees($fees) {
        try {
            $sql = "INSERT INTO frais_ecriture (id_demande, id_montant, partie_abstrait, montant_obligatoire, 
                    montant_paye, num_recu, date_payement) 
                    VALUES (:id_demande, :id_montant, :partie_abstrait, :montant_obligatoire, 
                    :montant_paye, :num_recu, :date_payement)";
            
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($fees);
        } catch (PDOException $e) {
            return false;
        }
    }
}

// Traitement de la sauvegarde
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $manager = new TraitementManager();
    $response = array("success" => true, "messages" => array());

    // Sauvegarder les données générales
    if (isset($_POST['general_data'])) {
        if (!$manager->saveGeneralData($_POST['general_data'])) {
            $response["success"] = false;
            $response["messages"][] = "Erreur lors de la sauvegarde des données générales";
        }
    }

    // Sauvegarder les documents
    if (isset($_POST['documents'])) {
        if (!$manager->saveDocuments($_POST['documents'])) {
            $response["success"] = false;
            $response["messages"][] = "Erreur lors de la sauvegarde des documents";
        }
    }

    // Sauvegarder les parties contractantes
    if (isset($_POST['contract_parties'])) {
        if (!$manager->saveContractParties($_POST['contract_parties'])) {
            $response["success"] = false;
            $response["messages"][] = "Erreur lors de la sauvegarde des parties contractantes";
        }
    }

    // Sauvegarder les charges sur la propriété
    if (isset($_POST['property_burdens'])) {
        if (!$manager->savePropertyBurdens($_POST['property_burdens'])) {
            $response["success"] = false;
            $response["messages"][] = "Erreur lors de la sauvegarde des charges sur la propriété";
        }
    }

    // Sauvegarder les termes du contrat
    if (isset($_POST['contract_terms'])) {
        if (!$manager->saveContractTerms($_POST['contract_terms'])) {
            $response["success"] = false;
            $response["messages"][] = "Erreur lors de la sauvegarde des termes du contrat";
        }
    }

    // Sauvegarder les signatures
    if (isset($_POST['signatures'])) {
        if (!$manager->saveSignatures($_POST['signatures'])) {
            $response["success"] = false;
            $response["messages"][] = "Erreur lors de la sauvegarde des signatures";
        }
    }

    // Sauvegarder les frais d'écriture
    if (isset($_POST['writing_fees'])) {
        if (!$manager->saveWritingFees($_POST['writing_fees'])) {
            $response["success"] = false;
            $response["messages"][] = "Erreur lors de la sauvegarde des frais d'écriture";
        }
    }

    // Renvoyer la réponse en JSON
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?> 