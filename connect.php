<?php

session_start();


class ClsConnect {
    private $host = "localhost";
    private $port = "5432";
    private $dbname = "pfe_bdd"; // Changez ceci selon votre nom de base de données
    private $user = "postgres";    // Changez ceci selon votre utilisateur
    private $pass = "pfe";           // Mettez votre mot de passe ici
    private $pdo;

    public function __construct() {
        try {
            $dsn = "pgsql:host=$this->host;port=$this->port;dbname=$this->dbname;";
            $this->pdo = new PDO($dsn, $this->user, $this->pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            
            // Test de la connexion
            $this->pdo->query('SELECT 1');
            error_log("Connexion à la base de données réussie");
        } catch(PDOException $e) {
            error_log("Erreur de connexion à la base de données : " . $e->getMessage());
            throw new Exception("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }

    public function getConnection() {
        if (!$this->pdo) {
            throw new Exception("Pas de connexion à la base de données");
        }
        return $this->pdo;
    }

    // Méthode pour exécuter une requête avec gestion des erreurs
    public function executeQuery($sql, $params = []) {
        try {
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute($params);
            if (!$result) {
                error_log("Erreur d'exécution de la requête : " . implode(", ", $stmt->errorInfo()));
                return false;
            }
            return $stmt;
        } catch (PDOException $e) {
            error_log("Erreur SQL : " . $e->getMessage());
            throw $e;
        }
    }

    // Méthode pour démarrer une transaction
    public function beginTransaction() {
        try {
            return $this->pdo->beginTransaction();
        } catch (PDOException $e) {
            error_log("Erreur lors du démarrage de la transaction : " . $e->getMessage());
            throw $e;
        }
    }

    // Méthode pour valider une transaction
    public function commit() {
        try {
            return $this->pdo->commit();
        } catch (PDOException $e) {
            error_log("Erreur lors de la validation de la transaction : " . $e->getMessage());
            throw $e;
        }
    }

    // Méthode pour annuler une transaction
    public function rollBack() {
        try {
            return $this->pdo->rollBack();
        } catch (PDOException $e) {
            error_log("Erreur lors de l'annulation de la transaction : " . $e->getMessage());
            throw $e;
        }
    }

    public function verifierUtilisateur($cin, $password){
        if (empty($cin) || empty($password)) {
            echo "Veuillez remplir tous les champs obligatoires.";
            return false;
        }

        $stmt = $this->pdo->prepare("SELECT * FROM admin WHERE cin = :cin_admin AND password = :password");
        $stmt->bindParam(':cin_admin', $cin);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }

    

    public function envoyerCodeVerification($user) {
        $code_verification = mt_rand(100000, 999999);

        $_SESSION['code_verification'] = $code_verification;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'erijedridi1@gmail.com';
            //$mail->Password   = 'ibswasejbfbdbmyv';
            $mail->Password = 'gaom vvaf fpqa tpeh';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('erijedridi1@gmail.com', 'Rédacteur');
            $mail->addAddress($user['email']);

            $mail->isHTML(true);
            $mail->Subject = 'Code de vérification';
            $mail->Body    = 'Votre code de vérification est : <b>' . $code_verification . '</b>';
            $mail->AltBody = 'Votre code de vérification est : ' . $code_verification;

            $mail->send();

            header("Location: verifier_code.php");
            exit();
        } catch (Exception $e) {
            echo "Erreur d'envoi d'email : {$mail->ErrorInfo}";
        }
    }

    public function traitResult($type_demande) {
        $sql = "SELECT * FROM public.\"T_demande\" WHERE type_demande = :type_demande";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':type_demande', $type_demande, PDO::PARAM_INT);
        $stmt->execute();
        
    if ($stmt->rowCount() > 0) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        return false;
    }
    }



    public function traitContrat($etat_demande, $etat_contrat) {
        try {
            $sql = "SELECT c.id_demande, c.etat_contrat, d.etat_demande 
                    FROM public.\"contrat\" c
                    INNER JOIN public.\"T_demande\" d ON c.id_demande = d.id_demande
                    WHERE d.etat_demande = :etat_demande AND c.etat_contrat = :etat_contrat";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':etat_demande', $etat_demande, PDO::PARAM_INT);
            $stmt->bindParam(':etat_contrat', $etat_contrat, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur dans traitContrat : " . $e->getMessage());
            return [];
        }
    }




    public function getDemandeById($id_demande) {
        $sql = "SELECT * FROM public.\"T_demande\" WHERE id_demande = :id_demande";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id_demande', $id_demande, PDO::PARAM_INT);
        $stmt->execute();

    if ($stmt->rowCount() > 0) {
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        return false;
    }
    }


    public function getidcontract() {
        $sql = "SELECT nextval('public.next-id-contract')";
        //return $sql;
        $stmt = $this->pdo->prepare($sql);
        //$stmt->execute();
        if ($stmt->rowCount() > 0) {    
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }


   
    
    function getPiecesJointesByDemande($id_demande) {
        global $pdo;
        
        $id_demande = $id_demande;
        
        $sql = "SELECT * FROM pieces_jointes WHERE id_demande =".$id_demande;
        $result =$pdo->query($sql);
        //echo $result;
        $result->execute();
        return $result;
        exit;

        if ($result->rowCount() > 0) {
            return $result->fetch(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }


    //annee contrat
    public function getAnneeContrat($id_demande) {
        $sql = "SELECT * FROM contrats WHERE id_demande = :id";
        $stmt = $this->pdo->prepare($sql); 
        $stmt->bindParam(':id', $id_demande, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }

    //numero contrat
    public function getNumContrat($id_demande) {
        $sql = "SELECT * FROM contrats WHERE id_demande = :id";
        $stmt = $this->pdo->prepare($sql); 
        $stmt->bindParam(':id', $id_demande, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }
    //SUJET DU CONTRAT
    public function getSujetContrat($id_demande) {
        $sql = "SELECT * FROM contrats WHERE id_demande = :id";
        $stmt = $this->pdo->prepare($sql); 
        $stmt->bindParam(':id', $id_demande, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }



    //AGENT
    public function getAgent($id_demande) {
        $sql = "SELECT * FROM agent WHERE id_demande = :id";
        $stmt = $this->pdo->prepare($sql); 
        $stmt->bindParam(':id', $id_demande, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }
  
    

    //DEPOSANT
    public function getDeposant($id_demande) {
        $sql = "SELECT * FROM deposant WHERE id_demande = :id";
        $stmt = $this->pdo->prepare($sql); 
        $stmt->bindParam(':id', $id_demande, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }


    public function getSubject($id_demande) {
        $sql = "SELECT * FROM contrat WHERE id_demande = :id";
        $stmt = $this->pdo->prepare($sql); 
        $stmt->bindParam(':id', $id_demande, PDO::PARAM_INT);
        //$stmt->execute();

        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }



    function getAllDemandes() {
        try {
            // Requête simple pour récupérer toutes les données
            $sql = 'SELECT * FROM "T_demande"';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Debug
            error_log("Nombre de résultats trouvés : " . count($result));
            
            return $result;
        } catch(PDOException $e) {
            error_log("Erreur dans getAllDemandes : " . $e->getMessage());
            throw $e; // Remonter l'erreur pour la voir dans les logs
        }
    }
    




    public function getAgents() {
        $agents = [];
        try {
            $stmtRedacteur = $this->pdo->query("SELECT name, cin, email, password FROM redacteur");
            $redacteurs = $stmtRedacteur->fetchAll();
            foreach ($redacteurs as $agent) {
                $agent['role'] = 'redacteur';
                $agents[] = $agent;
            }

            $stmtValideur = $this->pdo->query("SELECT name, cin, email, password FROM valideur");
            $valideurs = $stmtValideur->fetchAll();
            foreach ($valideurs as $agent) {
                $agent['role'] = 'valideur';
                $agents[] = $agent;
            }

            return json_encode($agents);
        } catch (PDOException $e) {
            return json_encode(['success' => false, 'message' => 'Erreur lors de la récupération : ' . $e->getMessage()]);
        }
    }

    public function deleteAgent($role, $email) {
        if (!in_array($role, ['redacteur', 'valideur'])) {
            return json_encode(['success' => false, 'message' => 'Rôle invalide.']);
        }

        $table = $role === 'redacteur' ? 'redacteur' : 'valideur';
        $sql = "DELETE FROM $table WHERE email = :email";

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            return json_encode(['success' => true]);
        } catch (PDOException $e) {
            return json_encode(['success' => false, 'message' => 'Erreur lors de la suppression : ' . $e->getMessage()]);
        }
    }
    public function updateAgent($role, $oldEmail, $name, $cin, $email, $password) {
        if (!in_array($role, ['redacteur', 'valideur'])) {
            return json_encode(['success' => false, 'message' => 'Rôle invalide.']);
        }
    
        $table = $role === 'redacteur' ? 'redacteur' : 'valideur';
        $sql = "UPDATE $table SET name = :name, cin = :cin, email = :email, password = :password WHERE email = :oldEmail";
    
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':cin', $cin);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':oldEmail', $oldEmail);
            $stmt->execute();
            return json_encode(['success' => true]);
        } catch (PDOException $e) {
            return json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour : ' . $e->getMessage()]);
        }
        if ($action === 'update_agent') {
            $role = isset($_POST['role']) ? $_POST['role'] : '';
            $oldEmail = isset($_POST['oldEmail']) ? $_POST['oldEmail'] : '';
            $name = isset($_POST['name']) ? $_POST['name'] : '';
            $cin = isset($_POST['cin']) ? $_POST['cin'] : '';
            $email = isset($_POST['email']) ? $_POST['email'] : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';
            echo $connect->updateAgent($role, $oldEmail, $name, $cin, $email, $password);
        }
    }


    function getTousLesUtilisateurs($conn) {
        $sql = "
            SELECT 
                id_redacteur AS id,
                nom_redacteur AS nom,
                prenom_redacteur AS prenom,
                cin_redacteur AS identification_number,
                password,
                post,
                email,
                adresse,
                telephone,
                'redacteur' AS role 
            FROM redacteur
            UNION
            SELECT 
                id_valideur AS id,
                nom_valideur AS nom,
                prenom_valideur AS prenom,
                cin_valideur AS identification_number,
                password,
                post,
                email,
                adresse,
                telephone,
                'valideur' AS role 
            FROM valideur
        ";
    
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Vérifie si les champs nécessaires sont présents
            if (isset($_POST['post'], $_POST['agentName'], $_POST['cin'], $_POST['agentEmail'])) {
        
                $post = $_POST['post']; // 1 ou 2
                $nomPrenom = trim($_POST['agentName']);
                $cin = trim($_POST['cin']);
                $email = trim($_POST['agentEmail']);
                $adresse = trim($_POST['agentAdresse']);
                $telephone = trim($_POST['agentTele']);
                $date_naissance = $_POST['agentNaissance'];
                $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
        
                // Séparer le nom et prénom
                $parts = explode(' ', $nomPrenom, 2);
                $nom = $parts[0] ?? '';
                $prenom = $parts[1] ?? '';
        
                // Déterminer la table selon le post
                if ($post == 1) {
                    $table = 'redacteur';
                } elseif ($post == 2) {
                    $table = 'valideur';
                } else {
                    die("Valeur de 'post' invalide.");
                }
        
                // Requête d'insertion
                $sql = "INSERT INTO $table (nom, prenom, identification_number, email, adresse, telephone, date_naissance, password)
                        VALUES (:nom, :prenom, :cin, :email, :adresse, :telephone, :date_naissance, :password)";
                $stmt = $pdo->prepare($sql);
        
                $stmt->bindParam(':nom', $nom);
                $stmt->bindParam(':prenom', $prenom);
                $stmt->bindParam(':cin', $cin);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':adresse', $adresse);
                $stmt->bindParam(':telephone', $telephone);
                $stmt->bindParam(':date_naissance', $date_naissance);
                $stmt->bindParam(':password', $password);
        
                if ($stmt->execute()) {
                    echo "Utilisateur ajouté avec succès.";
                } else {
                    echo "Erreur lors de l'ajout.";
                }
            } else {
                echo "Champs requis manquants.";
            }
        }
    }
    


    public function getContratsForValideur() {
        try {
            $sql = "SELECT c.id_contrat, c.date_contrat, c.id_demande, 
                           d.num_recu, d.date_demande
                    FROM public.contrat c
                    INNER JOIN public.\"T_demande\" d ON c.id_demande = d.id_demande
                    WHERE d.etat_demande = 1 AND c.etat_contrat = 0
                    ORDER BY d.date_demande DESC";
                    
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur dans getContratsForValideur : " . $e->getMessage());
            return [];
        }
    }

    // Méthodes pour les demandes
    public function getDemandesByType($type, $id_redacteur = null) {
        $sql = "SELECT * FROM demandes WHERE type_demande = :type";
        if ($id_redacteur) {
            $sql .= " AND id_redacteur = :id_redacteur";
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':type', $type);
        if ($id_redacteur) {
            $stmt->bindParam(':id_redacteur', $id_redacteur);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateDemandeStatus($id, $status) {
        $stmt = $this->pdo->prepare("UPDATE demandes SET etat_demande = :status WHERE id_demande = :id");
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Méthodes pour les contrats
    public function createContrat($data) {
        $stmt = $this->pdo->prepare("INSERT INTO contrats (id_demande, date_creation, id_redacteur) VALUES (:id_demande, :date_creation, :id_redacteur)");
        return $stmt->execute($data);
    }

    public function getContratsByStatus($status, $id_redacteur = null) {
        $sql = "SELECT c.*, d.num_recu FROM contrats c 
                JOIN demandes d ON c.id_demande = d.id_demande 
                WHERE c.etat_contrat = :status";
        if ($id_redacteur) {
            $sql .= " AND c.id_redacteur = :id_redacteur";
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':status', $status);
        if ($id_redacteur) {
            $stmt->bindParam(':id_redacteur', $id_redacteur);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateContratStatus($id, $status, $motif = null) {
        $sql = "UPDATE contrats SET etat_contrat = :status";
        if ($motif) {
            $sql .= ", motif_rejet = :motif";
        }
        $sql .= " WHERE id_contrat = :id";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        if ($motif) {
            $stmt->bindParam(':motif', $motif);
        }
        return $stmt->execute();
    }

    // Méthodes pour les parties du contrat
    public function savePartieContrat($data) {
        $stmt = $this->pdo->prepare("INSERT INTO parties_contrat 
            (id_contrat, nom, prenom, cin, date_naissance, lieu_naissance, adresse, type_partie) 
            VALUES (:id_contrat, :nom, :prenom, :cin, :date_naissance, :lieu_naissance, :adresse, :type_partie)");
        return $stmt->execute($data);
    }

    // Méthodes pour les détails du contrat
    public function saveDetailsContrat($data) {
        $stmt = $this->pdo->prepare("INSERT INTO details_contrat 
            (id_contrat, montant, surface, adresse_bien, description_bien, num_titre_foncier) 
            VALUES (:id_contrat, :montant, :surface, :adresse_bien, :description_bien, :num_titre_foncier)");
        return $stmt->execute($data);
    }

    // Méthodes pour les documents
    public function saveDocument($data) {
        $stmt = $this->pdo->prepare("INSERT INTO documents 
            (id_contrat, type_document, reference, date_document) 
            VALUES (:id_contrat, :type_document, :reference, :date_document)");
        return $stmt->execute($data);
    }

    public function getContratComplet($id_contrat) {
        $contrat = [];
        
        // Récupérer les informations de base du contrat
        $stmt = $this->pdo->prepare("SELECT c.*, d.num_recu, d.date_demande 
            FROM contrats c 
            JOIN demandes d ON c.id_demande = d.id_demande 
            WHERE c.id_contrat = :id");
        $stmt->bindParam(':id', $id_contrat);
        $stmt->execute();
        $contrat['base'] = $stmt->fetch(PDO::FETCH_ASSOC);

        // Récupérer les parties du contrat
        $stmt = $this->pdo->prepare("SELECT * FROM parties_contrat WHERE id_contrat = :id");
        $stmt->bindParam(':id', $id_contrat);
        $stmt->execute();
        $contrat['parties'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Récupérer les détails du contrat
        $stmt = $this->pdo->prepare("SELECT * FROM details_contrat WHERE id_contrat = :id");
        $stmt->bindParam(':id', $id_contrat);
        $stmt->execute();
        $contrat['details'] = $stmt->fetch(PDO::FETCH_ASSOC);

        // Récupérer les documents
        $stmt = $this->pdo->prepare("SELECT * FROM documents WHERE id_contrat = :id");
        $stmt->bindParam(':id', $id_contrat);
        $stmt->execute();
        $contrat['documents'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $contrat;
    }

    // Méthodes pour les contrats dans PostgreSQL
    public function saveContratPG($id_demande) {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO contrat (id_demande, date_creation, etat_contrat) 
                                        VALUES (:id_demande, CURRENT_DATE, 0) 
                                        RETURNING id_contrat");
            $stmt->execute([':id_demande' => $id_demande]);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Erreur saveContrat: " . $e->getMessage());
            throw $e;
        }
    }

    public function saveDocumentPG($id_contrat, $type, $reference, $date) {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO documents (id_contrat, type_document, reference, date_document) 
                                        VALUES (:id_contrat, :type, :reference, :date)");
            return $stmt->execute([
                ':id_contrat' => $id_contrat,
                ':type' => $type,
                ':reference' => $reference,
                ':date' => $date
            ]);
        } catch (PDOException $e) {
            error_log("Erreur saveDocument: " . $e->getMessage());
            throw $e;
        }
    }

    public function savePartieContratPG($id_contrat, $data) {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO parties_contrat 
                (id_contrat, nom, prenom, cin, date_naissance, lieu_naissance, adresse, type_partie) 
                VALUES (:id_contrat, :nom, :prenom, :cin, :date_naissance, :lieu_naissance, :adresse, :type_partie)");
            
            return $stmt->execute([
                ':id_contrat' => $id_contrat,
                ':nom' => $data['nom'],
                ':prenom' => $data['prenom'],
                ':cin' => $data['cin'],
                ':date_naissance' => $data['date_naissance'],
                ':lieu_naissance' => $data['lieu_naissance'],
                ':adresse' => $data['adresse'],
                ':type_partie' => $data['type']
            ]);
        } catch (PDOException $e) {
            error_log("Erreur savePartieContrat: " . $e->getMessage());
            throw $e;
        }
    }

    public function updateDemandeStatusPG($id_demande, $status) {
        try {
            $stmt = $this->pdo->prepare("UPDATE demandes SET etat_demande = :status WHERE id_demande = :id");
            return $stmt->execute([
                ':status' => $status,
                ':id' => $id_demande
            ]);
        } catch (PDOException $e) {
            error_log("Erreur updateDemandeStatus: " . $e->getMessage());
            throw $e;
        }
    }

    //القسم الرابع
    //وثيقة الهوية
    public function personneContratc($pdo) {
        if (isset($_POST['submit'])) {
            try {
                $prenom = $_POST['prenom'] ?? null;
                $numero_document_identite = $_POST['numero_document_identite'] ?? null;
                $nom = $_POST['nom'] ?? null;
                $prenom_pere = $_POST['prenom_pere'] ?? null;
                $date_emission_document = $_POST['date_emission_document'] ?? null;
                $sexe = $_POST['sexe'] ?? null;
                $nationalite = $_POST['nationalite'] ?? null;
                $adresse = $_POST['adresse'] ?? null;
                $profession = $_POST['profession'] ?? null;
                $etat_civil = $_POST['etat_civil'] ?? null;
                $prenom_conjoint = $_POST['prenom_conjoint'] ?? null;
                $nom_conjoint = $_POST['nom_conjoint'] ?? null;
                $prenom_pere_conjoint = $_POST['prenom_pere_conjoint'] ?? null;
                $prenom_grand_pere_conjoint = $_POST['prenom_grand_pere_conjoint'] ?? null;
                $surnom_conjoint = $_POST['surnom_conjoint'] ?? null;
                $date_naissance_conjoint = $_POST['date_naissance_conjoint'] ?? null;
                $lieu_naissance_conjoint = $_POST['lieu_naissance_conjoint'] ?? null;
                $nationalite_conjoint = $_POST['nationalite_conjoint'] ?? null;
                $numero_document_conjoint = $_POST['numero_document_conjoint'] ?? null;
                $date_document_conjoint = $_POST['date_document_conjoint'] ?? null;
                $lieu_document_conjoint = $_POST['lieu_document_conjoint'] ?? null;
                $vendeur_acheteur =$_POST['vendeur_acheteur'] ?? null ;
    
                // Vérification qu'au moins le numéro de document est rempli
                if (!$numero_document_identite) {
                    return "❌ ";
                }
    
                $sql = "INSERT INTO personnes_contracteurs (
                    prenom, numero_document_identite, nom, prenom_pere, date_emission_document, sexe,
                    nationalite, adresse, profession, etat_civil,prenom_conjoint, nom_conjoint, prenom_pere_conjoint,
                    prenom_grand_pere_conjoint, surnom_conjoint, date_naissance_conjoint, lieu_naissance_conjoint,
                    nationalite_conjoint, numero_document_conjoint, date_document_conjoint, lieu_document_conjoint,vendeur_acheteur
                ) VALUES (
                    :prenom, :numero_document_identite, :nom, :prenom_pere, :date_emission_document, :sexe,
                    :nationalite, :adresse, :profession, :etat_civil,:prenom_conjoint, :nom_conjoint, :prenom_pere_conjoint,
                    :prenom_grand_pere_conjoint, :surnom_conjoint, :date_naissance_conjoint, :lieu_naissance_conjoint,
                    :nationalite_conjoint, :numero_document_conjoint, :date_document_conjoint, :lieu_document_conjoint,:vendeur_acheteur
                )";
    
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':prenom' => $prenom,
                    ':numero_document_identite' => $numero_document_identite,
                    ':nom' => $nom,
                    ':prenom_pere' => $prenom_pere,
                    ':date_emission_document' => $date_emission_document,
                    ':sexe' => $sexe,
                    ':nationalite' => $nationalite,
                    ':adresse' => $adresse,
                    ':profession' => $profession,
                    ':etat_civil' => $etat_civil,
                    ':prenom_conjoint' => $prenom_conjoint,
                    ':nom_conjoint' => $nom_conjoint,
                    ':prenom_pere_conjoint' => $prenom_pere_conjoint,
                    ':prenom_grand_pere_conjoint' => $prenom_grand_pere_conjoint,
                    ':surnom_conjoint' => $surnom_conjoint,
                    ':date_naissance_conjoint' => $date_naissance_conjoint,
                    ':lieu_naissance_conjoint' => $lieu_naissance_conjoint,
                    ':nationalite_conjoint' => $nationalite_conjoint,
                    ':numero_document_conjoint' => $numero_document_conjoint,
                    ':date_document_conjoint' => $date_document_conjoint,
                    ':lieu_document_conjoint' => $lieu_document_conjoint,
                    ':vendeur_acheteur' => $vendeur_acheteur
                ]);
    
                return "✅ ";
            } catch (PDOException $e) {
                return "❌";
            }
        }
        return null;
    }
    





    //القسم الخامس
    //dessin_immobilier1
    public function insertContractData($pdo) {
        if (isset($_POST['submit'])) {
            try {
                // Vérification que les données existent
                $nom_droit1 = $_POST['nom_droit1'] ?? null;
                $sujet_contrat1 = $_POST['sujet_contrat1'] ?? null;
                $unite1 = $_POST['unite1'] ?? null;
                $detail_general = $_POST['detail_general'] ?? null;
                $contenu1 = $_POST['contenu1'] ?? null;
                $valeur_prix1 = $_POST['valeur_prix1'] ?? null;
                $dure1 = $_POST['dure1'] ?? null;
                $surplus1 = $_POST['surplus1'] ?? null;

                // Vérification qu'au moins un champ est rempli
                if (!$nom_droit1 && !$sujet_contrat1 && !$unite1 && !$detail_general && 
                    !$contenu1 && !$valeur_prix1 && !$dure1 && !$surplus1) {
                    return "❌";
                }

                $sql = "INSERT INTO dessin_immobiler1 
                        (nom_droit1, sujet_contrat1, unite1, detail_general, contenu1, valeur_prix1, dure1, surplus1)
                        VALUES 
                        (:nom_droit1, :sujet_contrat1, :unite1, :detail_general, :contenu1, :valeur_prix1, :dure1, :surplus1)";
                
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':nom_droit1' => $nom_droit1,
                    ':sujet_contrat1' => $sujet_contrat1,
                    ':unite1' => $unite1,
                    ':detail_general' => $detail_general,
                    ':contenu1' => $contenu1,
                    ':valeur_prix1' => $valeur_prix1,
                    ':dure1' => $dure1,
                    ':surplus1' => $surplus1
                ]);

                return "✅ ";
            } catch (PDOException $e) {
                return "❌";
            }
        }
        return null;
    }




    //perception2
    public function insertContractData2($pdo) {
        if (isset($_POST['submit']) && isset($_POST['date_inscri2'])) {
            try {
                $sql = "INSERT INTO dessin_immobilers2 
                        (date_inscri2, lieu_inscri2, doc2, num_inscri2, num_succursale2) 
                        VALUES 
                        (:date_inscri2, :lieu_inscri2, :doc2, :num_inscri2, :num_succursale2)";
                $stmt = $pdo->prepare($sql);
    
                // Gérer les tableaux imbriqués
                $date_inscri2_array = is_array($_POST['date_inscri2']) ? $_POST['date_inscri2'] : [$_POST['date_inscri2']];
                foreach ($date_inscri2_array as $index => $date_inscri2) {
                    // Extraire la valeur scalaire
                    $date_inscri2_value = is_array($date_inscri2) ? ($date_inscri2[0] ?? null) : $date_inscri2;
                    if (empty($date_inscri2_value)) {
                        error_log("Erreur : date_inscri2 requis pour ligne " . ($index + 1));
                        continue;
                    }
    
                    // Valider le format de la date (YYYY-MM-DD)
                    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date_inscri2_value)) {
                        error_log("Erreur : format de date invalide pour ligne " . ($index + 1) . ": " . $date_inscri2_value);
                        continue;
                    }
    
                    $stmt->execute([
                        ':date_inscri2' => $date_inscri2_value,
                        ':lieu_inscri2' => is_array($_POST['lieu_inscri2'][$index]) ? ($_POST['lieu_inscri2'][$index][0] ?? null) : ($_POST['lieu_inscri2'][$index] ?? null),
                        ':doc2' => is_array($_POST['doc2'][$index]) ? ($_POST['doc2'][$index][0] ?? null) : ($_POST['doc2'][$index] ?? null),
                        ':num_inscri2' => is_array($_POST['num_inscri2'][$index]) ? ($_POST['num_inscri2'][$index][0] ?? null) : ($_POST['num_inscri2'][$index] ?? null),
                        ':num_succursale2' => is_array($_POST['num_succursale2'][$index]) ? ($_POST['num_succursale2'][$index][0] ?? null) : ($_POST['num_succursale2'][$index] ?? null)
                    ]);
                }
    
                return "✅";
            } catch (PDOException $e) {
                error_log("Erreur dans insertContractData2 : " . $e->getMessage());
                return "❌ Erreur SQL : " . $e->getMessage();
            }
        }
        error_log("Aucune donnée valide : " . print_r($_POST, true));
        return null;
    }


    

    //dessin_immobilier3
    public function insertContractData3($pdo) {
        if (isset($_POST['submit'])) {
            try {
                $regime_finance_couple3 = $_POST['regime_finance_couple3'] ?? null;
                $remarques3 = $_POST['remarques3'] ?? null;

                // Vérification qu'au moins un champ est rempli
                if (!$regime_finance_couple3 && !$remarques3) {
                    return "❌";
                }

                $sql = "INSERT INTO dessin_immobiler3
                        (regime_finance_couple3, remarques3)
                        VALUES 
                        (:regime_finance_couple3, :remarques3)";

                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':regime_finance_couple3' => $regime_finance_couple3,
                    ':remarques3' => $remarques3
                ]);

                return "✅ ";
            } catch (PDOException $e) {
                return "❌ " ;
            }
        }
        return null;
    }


    public function insertContractData4($pdo) {
        if (isset($_POST['submit'])) {
            try {
                $valeur_contrat_dinar = $_POST['valeur_contrat_dinar'] ?? null;
                $prix_ecriture = $_POST['prix_ecriture'] ?? null;

                // Vérification qu'au moins un champ est rempli
                if (!$valeur_contrat_dinar && !$prix_ecriture) {
                    return "❌ ";
                }

                $sql = "INSERT INTO dessin_immobilier4
                        (valeur_contrat_dinar, prix_ecriture)
                        VALUES 
                        (:valeur_contrat_dinar, :prix_ecriture)";

                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':valeur_contrat_dinar' => $valeur_contrat_dinar,
                    ':prix_ecriture' => $prix_ecriture
                ]);

                return "✅";
            } catch (PDOException $e) {
                return "❌";
            }
        }
        return null;
    }

    public function nomGOUV($pdo) {
        if (isset($_POST['submit'])) {
            try {
                $code_gouv = $_POST['code_gouv'] ?? null;
                $libile_gouv = $_POST['libile_gouv'] ?? null;
                $ip_gouv = $_POST['ip_gouv'] ?? null;

                // Vérification qu'au moins un champ est rempli
                if (!$libile_gouv) {
                    return "❌ ";
                }

                $sql = "INSERT INTO T_gouv(
                    code_gouv, libile_gouv, ip_gouv)
                    VALUES (:code_gouv, :libile_gouv, :ip_gouv)";

                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':code_gouv' => $code_gouv,
                    ':libile_gouv' => $libile_gouv,
                    ':ip_gouv' => $ip_gouv
                ]);

                return "✅ ";
            } catch (PDOException $e) {
                return "❌";
            }
        }
        return null;
    }














    public function nomTITRE($pdo) {
    if (isset($_POST['submit'])) {
        try {
            $num_titre = $_POST['num_titre'] ?? null;
            $gouv_titre = $_POST['gouv_titre'] ?? null;
            $doub_titre = $_POST['doub_titre'] ?? null;
            $etat_titre = $_POST['etat_titre'] ?? null;

            // Déclaration des variables nécessaires
            $valeur_contrat_dinar = $_POST['valeur_contrat_dinar'] ?? null;
            $prix_ecriture = $_POST['prix_ecriture'] ?? null;

            // Vérification qu'au moins un champ est rempli
            if (!$valeur_contrat_dinar && !$prix_ecriture) {
                return "❌ ";
            }

            $sql = "INSERT INTO titres (
                num_titre, gouv_titre, doub_titre, etat_titre
            ) VALUES (
                :num_titre, :gouv_titre, :doub_titre, :etat_titre
            )";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':num_titre' => $num_titre,
                ':gouv_titre' => $gouv_titre,
                ':doub_titre' => $doub_titre,
                ':etat_titre' => $etat_titre
            ]);

            return "✅ ";
        } catch (PDOException $e) {
            return "❌";
        }
    }
    return null;
    }



























    //القسم السادس
    public function insertChapitres($pdo) { 
        if (isset($_POST['submit'])) {
            try {
                $contenue_chapitre = $_POST['contenue_chapitre'] ?? null;
    
                if (!$contenue_chapitre) {
                    return "❌";
                }
    
                $sql = "INSERT INTO chapitres_juridiques (contenue_chapitre)
                        VALUES (:contenue_chapitre)";
    
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':contenue_chapitre' => $contenue_chapitre
                ]);
    
                return "✅";
            } catch (PDOException $e) {
                return "❌ " ;
            }
        }
        return null;
    }
    
    


    //القسم السابع
    //IDpersonnes
    public function idPersonnes($pdo) {
        if (isset($_POST['submit'])) {
            try {
                // Vérifier l'existence des clés et convertir les tableaux en valeurs simples
                $prenom_personne = isset($_POST['prenom_personne']) ? (is_array($_POST['prenom_personne']) ? $_POST['prenom_personne'][0] : $_POST['prenom_personne']) : null;
                $prenom_pere = isset($_POST['prenom_pere']) ? (is_array($_POST['prenom_pere']) ? $_POST['prenom_pere'][0] : $_POST['prenom_pere']) : null;
                $prenom_grandpere = isset($_POST['prenom_grandpere']) ? (is_array($_POST['prenom_grandpere']) ? $_POST['prenom_grandpere'][0] : $_POST['prenom_grandpere']) : null;
                $nom_personne = isset($_POST['nom_personne']) ? (is_array($_POST['nom_personne']) ? $_POST['nom_personne'][0] : $_POST['nom_personne']) : null;
                $statut = isset($_POST['statut']) ? (is_array($_POST['statut']) ? $_POST['statut'][0] : $_POST['statut']) : null;
                $signature = isset($_POST['signature']) ? (is_array($_POST['signature']) ? $_POST['signature'][0] : $_POST['signature']) : null;
    
                // Vérification que le champ n'est pas vide
                if (!$statut) {
                    return "❌ ";
                }
    
                $sql = "INSERT INTO IDpersonnes (
                    prenom_personne, prenom_pere, prenom_grandpere, nom_personne, statut, signature
                ) VALUES (
                    :prenom_personne, :prenom_pere, :prenom_grandpere, :nom_personne, :statut, :signature
                )";
    
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':prenom_personne' => $prenom_personne,
                    ':prenom_pere' => $prenom_pere,
                    ':prenom_grandpere' => $prenom_grandpere,
                    ':nom_personne' => $nom_personne,
                    ':statut' => $statut,
                    ':signature' => $signature
                ]);
    
                return "✅";
            } catch (PDOException $e) {
                return "❌";
            }
        }
        return null;
    }


    //perception1
    public function perception1($pdo) {
        if (isset($_POST['submit'])) {
            try {
                $id_montant1 = $_POST['id_montant1'] ?? null;
                $partieabstrait1 = $_POST['partieabstrait1'] ?? null;
                $montant_obligatoire1 = $_POST['montant_obligatoire1'] ?? null;
                $montant_paye1 = $_POST['montant_paye1'] ?? null;
                $num_recu1 = $_POST['num_recu1'] ?? null;
                $date_payement1 = $_POST['date_payement1'] ?? null;

                // Vérification que le champ n'est pas vide
                if (!$id_montant1) {
                    return "❌ ";
                }

                $sql = "INSERT INTO perception1(
                    id_montant1, partieabstrait1, montant_obligatoire1, montant_paye1, num_recu1, date_payement1)
                    VALUES (:id_montant1, :partieabstrait1, :montant_obligatoire1, :montant_paye1, :num_recu1, :date_payement1)";

                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':id_montant1' => $id_montant1,
                    ':partieabstrait1' => $partieabstrait1,
                    ':montant_obligatoire1' => $montant_obligatoire1,
                    ':montant_paye1' => $montant_paye1,
                    ':num_recu1' => $num_recu1,
                    ':date_payement1' => $date_payement1

                ]);

                return "✅ ";
            } catch (PDOException $e) {
                return "❌" ;
            }
        }
        return null;
    }



    //perception4
    public function perception4($pdo) {
        if (isset($_POST['submit'])) {
            try {
                // Récupération des valeurs et conversion en scalaires
                $nom4 = isset($_POST['nom4']) ? (is_array($_POST['nom4']) ? $_POST['nom4'][0] : $_POST['nom4']) : null;
                $valeur_dinar4 = isset($_POST['valeur_dinar4']) ? (is_array($_POST['valeur_dinar4']) ? $_POST['valeur_dinar4'][0] : $_POST['valeur_dinar4']) : null;
                $pourcent4 = isset($_POST['pourcent4']) ? (is_array($_POST['pourcent4']) ? $_POST['pourcent4'][0] : $_POST['pourcent4']) : null;
                $montant_dinar4 = isset($_POST['montant_dinar4']) ? (is_array($_POST['montant_dinar4']) ? $_POST['montant_dinar4'][0] : $_POST['montant_dinar4']) : null;

                // Vérification que le champ n'est pas vide
                if (!$nom4) {
                    return "❌ ";
                }

                // Log des valeurs pour le débogage
                error_log("Valeurs reçues : " . print_r([
                    'nom4' => $nom4,
                    'valeur_dinar4' => $valeur_dinar4,
                    'pourcent4' => $pourcent4,
                    'montant_dinar4' => $montant_dinar4
                ], true));

                $sql = "INSERT INTO perception4(
                    nom4, valeur_dinar4, pourcent4, montant_dinar4)
                    VALUES (:nom4, :valeur_dinar4, :pourcent4, :montant_dinar4)";

                $stmt = $pdo->prepare($sql);
                $result = $stmt->execute([
                    ':nom4' => $nom4,
                    ':valeur_dinar4' => $valeur_dinar4,
                    ':pourcent4' => $pourcent4,
                    ':montant_dinar4' => $montant_dinar4
                ]);

                if (!$result) {
                    throw new Exception("Erreur lors de l'insertion des données");
                }

                return "✅";
            } catch (PDOException $e) {
                error_log("Erreur dans perception4 : " . $e->getMessage());
                return "❌";
            }
        }
        return null;
    }



    





    //perception3
    public function perception3($pdo) {
        if (isset($_POST['submit'])) {
            try {
                // Check if inputs are arrays and convert single values to arrays if necessary
                $valeur_dinar3_array = isset($_POST['valeur_dinar3']) ? (is_array($_POST['valeur_dinar3']) ? $_POST['valeur_dinar3'] : [$_POST['valeur_dinar3']]) : [];
                $pourcent3_array = isset($_POST['pourcent3']) ? (is_array($_POST['pourcent3']) ? $_POST['pourcent3'] : [$_POST['pourcent3']]) : [];
                $montant_dinar3_array = isset($_POST['montant_dinar3']) ? (is_array($_POST['montant_dinar3']) ? $_POST['montant_dinar3'] : [$_POST['montant_dinar3']]) : [];
                $signature3_array = isset($_POST['signature3']) ? (is_array($_POST['signature3']) ? $_POST['signature3'] : [$_POST['signature3']]) : [];
    
                // Ensure at least one row has valid data
                if (empty($montant_dinar3_array) || !isset($montant_dinar3_array[0]) || empty($montant_dinar3_array[0])) {
                    return "❌ No valid amount provided";
                }
    
                $sql = "INSERT INTO perecption3 (valeur_dinar3, pourcent3, montant_dinar3, signature3)
                        VALUES (:valeur_dinar3, :pourcent3, :montant_dinar3, :signature3)";
                $stmt = $pdo->prepare($sql);
    
                // Process each row
                $success = true;
                foreach ($montant_dinar3_array as $index => $montant_dinar3) {
                    // Ensure scalar values
                    $valeur_dinar3 = isset($valeur_dinar3_array[$index]) && is_scalar($valeur_dinar3_array[$index]) ? $valeur_dinar3_array[$index] : null;
                    $pourcent3 = isset($pourcent3_array[$index]) && is_scalar($pourcent3_array[$index]) ? $pourcent3_array[$index] : null;
                    $montant_dinar3 = is_scalar($montant_dinar3) ? $montant_dinar3 : null;
                    $signature3 = isset($signature3_array[$index]) && is_scalar($signature3_array[$index]) ? $signature3_array[$index] : null;
    
                    // Skip if no valid montant_dinar3 for this row
                    if (empty($montant_dinar3)) {
                        error_log("Skipping row $index: montant_dinar3 is empty");
                        continue;
                    }
    
                    // Execute the query for this row
                    $result = $stmt->execute([
                        ':valeur_dinar3' => $valeur_dinar3,
                        ':pourcent3' => $pourcent3,
                        ':montant_dinar3' => $montant_dinar3,
                        ':signature3' => $signature3
                    ]);
    
                    if (!$result) {
                        $success = false;
                        error_log("Failed to insert row $index: " . print_r($stmt->errorInfo(), true));
                    }
                }
    
                return $success ? "✅ Successfully inserted rows" : "❌ Failed to insert some rows";
            } catch (PDOException $e) {
                error_log("Erreur dans validerPrix : " . $e->getMessage());
                return "❌ SQL Error: " . $e->getMessage();
            }
        }
        return null;
    }

    //perception2
    public function perception2($pdo) {
        if (isset($_POST['submit'])) {
            try {
                // Check if inputs are arrays and iterate over them
                $statut2_array = isset($_POST['statut2']) ? (is_array($_POST['statut2']) ? $_POST['statut2'] : [$_POST['statut2']]) : [];
                $redacteur2_array = isset($_POST['redacteur2']) ? (is_array($_POST['redacteur2']) ? $_POST['redacteur2'] : [$_POST['redacteur2']]) : [];
                $redaction2_array = isset($_POST['redaction2']) ? (is_array($_POST['redaction2']) ? $_POST['redaction2'] : [$_POST['redaction2']]) : [];
                $revision2_array = isset($_POST['revision2']) ? (is_array($_POST['revision2']) ? $_POST['revision2'] : [$_POST['revision2']]) : [];
                $validationFinal2_array = isset($_POST['validationFinal2']) ? (is_array($_POST['validationFinal2']) ? $_POST['validationFinal2'] : [$_POST['validationFinal2']]) : [];

    
                // Ensure at least one row has valid data
                if (empty($statut2_array) || !isset($statut2_array[0]) || empty(trim($statut2_array[0]))) {
                    return "❌ No valid status provided";
                }
    
                $sql = 'INSERT INTO perception2 (statut2, redacteur2, redaction2, revision2, "validationFinal2")
                        VALUES (:statut2, :redacteur2, :redaction2, :revision2, :validationFinal2)';
        
                $stmt = $pdo->prepare($sql);
    
                // Process each row
                $success = true;
                foreach ($statut2_array as $index => $statut2) {
                    // Ensure scalar values
                    $statut2 = isset($statut2_array[$index]) && is_scalar($statut2_array[$index]) ? $statut2_array[$index] : null;
                    $redacteur2 = isset($redacteur2_array[$index]) && is_scalar($redacteur2_array[$index]) ? $redacteur2_array[$index] : null;
                    $redaction2 = isset($redaction2_array[$index]) && is_scalar($redaction2_array[$index]) ? $redaction2_array[$index] : null;
                    $revision2 = isset($revision2_array[$index]) && is_scalar($revision2_array[$index]) ? $revision2_array[$index] : null;
                    $validationFinal2 = isset($validationFinal2_array[$index]) && is_scalar($validationFinal2_array[$index]) ? $validationFinal2_array[$index] : null;
    
                    // Skip if no valid data for this row
                    if (empty($statut2)) {
                        error_log("Skipping row $index: statut2 is empty");
                        continue;
                    }
    
                    // Execute the query for this row
                    $result = $stmt->execute([
                        ':statut2' => $statut2,
                        ':redacteur2' => $redacteur2,
                        ':redaction2' => $redaction2,
                        ':revision2' => $revision2,
                        ':validationFinal2' => $validationFinal2
                    ]);
    
                    if (!$result) {
                        $success = false;
                        error_log("Failed to insert row $index: " . print_r($stmt->errorInfo(), true));
                    }
                }
    
                return $success ? "✅ Successfully inserted rows" : "❌ Failed to insert some rows";
            } catch (PDOException $e) {
                error_log("Erreur dans validationRevision: " . $e->getMessage());
                return "❌ SQL Error: " . $e->getMessage();
            }
        }
        return null;
    }








    

    /**
     * Démarre la session et stocke les infos de l'utilisateur.
     */
    /*public function demarrerSessionUtilisateur(array $utilisateur): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (! isset($_SESSION['userAuth'])) {
            $_SESSION['userAuth'] = [
                'prenom_admin' => $utilisateur['prenom_admin'] ?? '',
                'nom_admin'    => $utilisateur['nom_admin']    ?? '',
                'cin_admin'    => $utilisateur['cin_admin']    ?? '',
                'role'         => (int)($utilisateur['role']  ?? 1),
            ];
        }
    }

    /**
     * Redirige l'utilisateur selon son rôle.
     * 0 = admin, 1 = rédacteur, 2 = validateur
     */
    /*public function redirigerParRole(int $role): void {
        switch ($role) {
            case 0:
                header('Location: admin_dashboard.php');
                break;
            case 1:
                header('Location: verifier_email.php');
                break;
            case 2:
                header('Location: verifier_contrat.php');
                break;
            default:
                header('Location: login.php?error=role_invalide');
        }
        exit;
    }*/

    public function generatePDF($id_demande, $num_recu) {
        try {
            // Chemin vers l'interpréteur Python et le script
            $python = "python";  // ou "python3" selon votre système
            $script = _DIR_ . "/PDF.py";
            
            // Échapper les arguments pour la sécurité
            $id_demande = escapeshellarg($id_demande);
            $num_recu = escapeshellarg($num_recu);
            
            // Construire et exécuter la commande
            $command = "$python $script $id_demande $num_recu";
            $output = [];
            $returnVar = 0;
            
            exec($command, $output, $returnVar);
            
            // Vérifier si la commande s'est bien exécutée
            if ($returnVar === 0) {
                // Le dernier élément de $output devrait être le nom du fichier PDF généré
                $pdfFile = end($output);
                if (file_exists($pdfFile)) {
                    return $pdfFile;
                }
            }
            
            throw new Exception("Erreur lors de la génération du PDF");
        } catch (Exception $e) {
            error_log("Erreur de génération PDF: " . $e->getMessage());
            return false;
        }
    }

    // Exemple d'utilisation dans une autre partie du code :
    public function handlePDFGeneration($id_demande, $num_recu) {
        $pdfFile = $this->generatePDF($id_demande, $num_recu);
        
        if ($pdfFile) {
            // Si le PDF a été généré avec succès
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . basename($pdfFile) . '"');
            header('Content-Length: ' . filesize($pdfFile));
            readfile($pdfFile);
            
            // Optionnel : Supprimer le fichier après l'envoi
            unlink($pdfFile);
            exit;
        } else {
            // Gérer l'erreur
            echo "Erreur lors de la génération du PDF";
        }
    }

}


        




class contratManager {
    private $pdo;
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    public function enregistrerContrat($id_demande, $annee_demande, $date_demande, $id_contrat) {
        try {
            $query = "INSERT INTO contrat (id_demande, annee_demande, date_demande) 
                    VALUES (:id_demande, :annee_demande, :date_demande)";
            $annee = date('Y', strtotime($date_demande));
            $annee_demande = $annee;
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id_contrat', $id_contrat);
            $stmt->bindParam(':id_demande', $id_demande);
            $stmt->bindParam(':annee_demande', $annee_demande);
            $stmt->bindParam(':date_demande', $date_demande);
            $stmt->execute();
            return true; // succès
        } catch (PDOException $e) {
            error_log("Erreur enregistrement contrat : " . $e->getMessage());
            return false; // échec
        }
    }
    function demarrerSessionUtilisateur($utilisateur) {
        session_start();
    
        if (!isset($_SESSION['userAuth'])) {
            // $prenom = $utilisateur['prenom_admin'];
            // $nom = $utilisateur['nom_admin'];
            $_SESSION['userAuth'] = [
                'prenom_admin' => isset($utilisateur['prenom_admin']) ? $utilisateur['prenom_admin'] : '',
                'nom_admin' => isset($utilisateur['nom_admin']) ? $utilisateur['nom_admin'] : '',
                'cin_admin' => isset($utilisateur['cin_admin']) ? $utilisateur['cin_admin'] : '',
            ];
        } 
    } 
}