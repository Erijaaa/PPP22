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


    public function traitContratsss() {
        $sql = "SELECT * FROM contrats";
        $stmt = $this->pdo->prepare($sql);
        //$stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
    if ($stmt->rowCount() > 0) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        return false;
    }
    }

    public function traitContrat($etat_demande = 1, $etat_contrat = 0) {
        try {
            $sql = "SELECT c.date_contrat, c.id_demande, c.num_contrat, c.etat_contrat, d.num_recu
                    FROM contrats c
                    JOIN public.\"T_demande\" d ON c.id_demande = d.id_demande
                    WHERE c.etat_demande = :etat_demande AND c.etat_contrat = :etat_contrat";
                    
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':etat_demande', $etat_demande, PDO::PARAM_INT);
            $stmt->bindParam(':etat_contrat', $etat_contrat, PDO::PARAM_INT);
            $stmt->execute();
    
            if ($stmt->rowCount() > 0) {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                return false;
            }
        } catch (PDOException $e) {
            error_log("Erreur dans traitContrat : " . $e->getMessage());
            throw $e;
        }
    }
    


    /*public function getContrat($id_contrat) {
        $sql = "SELECT * FROM contrat WHERE id_demande = :id_demande";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id_demande', $id_contrat, PDO::PARAM_INT);
        //$stmt->execute();
    
        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }*/
    


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
    
    public function getValidationById($id_demande) {
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


    public function getContrat($id_contrat) {
        $sql = "SELECT * FROM contrats WHERE id_contrat = :id_contrat"; 
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id_contrat', $id_contrat, PDO::PARAM_INT);
        $stmt->execute();
    
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


    //ajouter un nouveau user par l'admin
    public function ajouterUser($pdo) {
        try {
            // Retrieve and validate input data
            $nom_prenom_user = $_POST['nom_prenom_user'] ?? null;
            $cin_user = $_POST['cin_user'] ?? null;
            $email_user = $_POST['email_user'] ?? null;
            $adresse_user = $_POST['adresse_user'] ?? null;
            $telephone_user = $_POST['telephone_user'] ?? null;
            $date_naissance_user = $_POST['date_naissance_user'] ?? null;
            $password_user = $_POST['password_user'] ?? null;
            $post = $_POST['post'] ?? null;
    
            // Check for missing fields
            if (empty($nom_prenom_user) || empty($cin_user) || empty($email_user) || 
                empty($adresse_user) || empty($telephone_user) || empty($date_naissance_user) || 
                empty($password_user) || empty($post)) {
                error_log("Missing required fields in ajouterUser");
                return "❌ Champs manquants";
            }
    
            // Validate post value against allowed values
            $allowed_post_values = [1, 2]; // Matches the form and updated database constraint
            if (!in_array($post, $allowed_post_values)) {
                error_log("Invalid post value: $post. Allowed values: " . implode(', ', $allowed_post_values));
                return "❌ Erreur : Valeur invalide pour le champ 'post'. Valeurs autorisées : " . implode(', ', $allowed_post_values);
            }
    
            // Check if cin_user already exists
            $checkSql = "SELECT COUNT(*) FROM public.users WHERE cin_user = :cin_user";
            $checkStmt = $pdo->prepare($checkSql);
            $checkStmt->execute([':cin_user' => $cin_user]);
            $cinCount = $checkStmt->fetchColumn();
    
            if ($cinCount > 0) {
                error_log("Duplicate cin_user detected: " . $cin_user);
                return "❌ Erreur : Le CIN existe déjà dans la base de données";
            }
    
            // Prepare SQL query for insertion
            $sql = "INSERT INTO public.users(
                    nom_prenom_user, cin_user, email_user, adresse_user, telephone_user, 
                    date_naissance_user, password_user, post)
                    VALUES (:nom_prenom_user, :cin_user, :email_user, :adresse_user, 
                            :telephone_user, :date_naissance_user, :password_user, :post)";
    
            $stmt = $pdo->prepare($sql);
    
            // Execute with correct parameter binding
            $result = $stmt->execute([
                ':nom_prenom_user' => $nom_prenom_user,
                ':cin_user' => $cin_user,
                ':email_user' => $email_user,
                ':adresse_user' => $adresse_user,
                ':telephone_user' => $telephone_user,
                ':date_naissance_user' => $date_naissance_user,
                ':password_user' => $password_user,
                ':post' => $post
            ]);
    
            if (!$result) {
                error_log("Failed to insert user: " . print_r($stmt->errorInfo(), true));
                return "❌ Échec de l'insertion";
            }
    
            return "✅ Utilisateur inséré avec succès";
        } catch (PDOException $e) {
            error_log("Erreur dans ajouterUser: " . $e->getMessage());
            return "❌ Erreur SQL : " . $e->getMessage();
        }
    }


    //update user
    public function updateUser($pdo) {
        try {
            $original_cin_user = $_POST['original_cin_user'] ?? null;
            $nom_prenom_user = $_POST['nom_prenom_user'] ?? null;
            $cin_user = $_POST['cin_user'] ?? null;
            $email_user = $_POST['email_user'] ?? null;
            $adresse_user = $_POST['adresse_user'] ?? null;
            $telephone_user = $_POST['telephone_user'] ?? null;
            $date_naissance_user = $_POST['date_naissance_user'] ?? null;
            $password_user = $_POST['password_user'] ?? null;
            $post = $_POST['post'] ?? null;

            $missing_fields = [];
            if (empty($original_cin_user)) $missing_fields[] = 'original_cin_user';
            if (empty($nom_prenom_user)) $missing_fields[] = 'nom_prenom_user';
            if (empty($cin_user)) $missing_fields[] = 'cin_user';
            if (empty($email_user)) $missing_fields[] = 'email_user';
            if (empty($adresse_user)) $missing_fields[] = 'adresse_user';
            if (empty($telephone_user)) $missing_fields[] = 'telephone_user';
            if (empty($date_naissance_user)) $missing_fields[] = 'date_naissance_user';
            if (empty($post)) $missing_fields[] = 'post';

            if (!empty($missing_fields)) {
                error_log("Missing fields in updateUser: " . implode(', ', $missing_fields));
                return "❌ Champs manquants: " . implode(', ', $missing_fields);
            }

            $allowed_post_values = [1, 2];
            if (!in_array($post, $allowed_post_values)) {
                error_log("Invalid post value: $post. Allowed values: " . implode(', ', $allowed_post_values));
                return "❌ Valeur invalide pour le champ 'post'. Valeurs autorisées : " . implode(', ', $allowed_post_values);
            }

            // Check if cin_user exists in the database
            $checkSql = "SELECT COUNT(*) FROM public.users WHERE cin_user = :cin_user";
            $checkStmt = $pdo->prepare($checkSql);
            $checkStmt->execute([':cin_user' => $cin_user]);
            $cinCount = $checkStmt->fetchColumn();
            error_log("Checking cin_user: $cin_user, count: $cinCount, original_cin_user: $original_cin_user");

            if ($cinCount > 0 && $cin_user !== $original_cin_user) {
                error_log("cin_user already exists: $cin_user");
                return "❌ Le CIN existe déjà, aucune modification effectuée";
            }

            $sql = "UPDATE public.users SET
                    nom_prenom_user = :nom_prenom_user,
                    cin_user = :cin_user,
                    email_user = :email_user,
                    adresse_user = :adresse_user,
                    telephone_user = :telephone_user,
                    date_naissance_user = :date_naissance_user,
                    post = :post";
            $params = [
                ':nom_prenom_user' => $nom_prenom_user,
                ':cin_user' => $cin_user,
                ':email_user' => $email_user,
                ':adresse_user' => $adresse_user,
                ':telephone_user' => $telephone_user,
                ':date_naissance_user' => $date_naissance_user,
                ':post' => $post,
                ':original_cin_user' => $original_cin_user
            ];

            if (!empty($password_user)) {
                $sql .= ", password_user = :password_user";
                $params[':password_user'] = $password_user; // Consider hashing
            }

            $sql .= " WHERE cin_user = :original_cin_user";

            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute($params);

            if (!$result) {
                error_log("Failed to update user: " . print_r($stmt->errorInfo(), true));
                return "❌ Échec de la mise à jour";
            }

            return "✅ Utilisateur mis à jour avec succès";
        } catch (PDOException $e) {
            error_log("Erreur dans updateUser: " . $e->getMessage());
            return "❌ Erreur SQL : " . $e->getMessage();
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
    /*public function personneContratc(
        $pdo,
        $prenom,
        $numero_document_identite,
        $nom,
        $prenom_pere,
        $date_emission_document,
        $sexe,
        $nationalite,
        $adresse,
        $profession,
        $etat_civil,
        $prenom_conjoint,
        $nom_conjoint,
        $prenom_pere_conjoint,
        $prenom_grand_pere_conjoint,
        $surnom_conjoint,
        $date_naissance_conjoint,
        $lieu_naissance_conjoint,
        $nationalite_conjoint,
        $numero_document_conjoint,
        $date_document_conjoint,
        $lieu_document_conjoint,
        $vendeur_acheteur,
        $id_demande_array,
        $nom_complet_personne,
        $statut_contractant,
        $notes,
        $role
    ) {            
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
            try {
                // Check if required fields are provided
                if (empty($numero_document_identite) || empty($id_demande_array) || empty($nom_complet_personne) || empty($role)) {
                    error_log("Missing required fields: numero_document_identite, id_demande_array, nom_complet_personne, or role");
                    return "❌ Missing required data: " . (empty($numero_document_identite) ? "numero_document_identite, " : "") .
                           (empty($id_demande_array) ? "id_demande_array, " : "") .
                           (empty($nom_complet_personne) ? "nom_complet_personne, " : "") .
                           (empty($role) ? "role" : "");
                }
    
                // Handle array inputs for multiple rows
                $prenom = is_array($prenom) ? $prenom : [$prenom];
                $numero_document_identite = is_array($numero_document_identite) ? $numero_document_identite : [$numero_document_identite];
                $nom = is_array($nom) ? $nom : [$nom];
                $prenom_pere = is_array($prenom_pere) ? $prenom_pere : [$prenom_pere];
                $date_emission_document = is_array($date_emission_document) ? $date_emission_document : [$date_emission_document];
                $sexe = is_array($sexe) ? $sexe : [$sexe];
                $nationalite = is_array($nationalite) ? $nationalite : [$nationalite];
                $adresse = is_array($adresse) ? $adresse : [$adresse];
                $profession = is_array($profession) ? $profession : [$profession];
                $etat_civil = is_array($etat_civil) ? $etat_civil : [$etat_civil];
                $prenom_conjoint = is_array($prenom_conjoint) ? $prenom_conjoint : [$prenom_conjoint];
                $nom_conjoint = is_array($nom_conjoint) ? $nom_conjoint : [$nom_conjoint];
                $prenom_pere_conjoint = is_array($prenom_pere_conjoint) ? $prenom_pere_conjoint : [$prenom_pere_conjoint];
                $prenom_grand_pere_conjoint = is_array($prenom_grand_pere_conjoint) ? $prenom_grand_pere_conjoint : [$prenom_grand_pere_conjoint];
                $surnom_conjoint = is_array($surnom_conjoint) ? $surnom_conjoint : [$surnom_conjoint];
                $date_naissance_conjoint = is_array($date_naissance_conjoint) ? $date_naissance_conjoint : [$date_naissance_conjoint];
                $lieu_naissance_conjoint = is_array($lieu_naissance_conjoint) ? $lieu_naissance_conjoint : [$lieu_naissance_conjoint];
                $nationalite_conjoint = is_array($nationalite_conjoint) ? $nationalite_conjoint : [$nationalite_conjoint];
                $numero_document_conjoint = is_array($numero_document_conjoint) ? $numero_document_conjoint : [$numero_document_conjoint];
                $date_document_conjoint = is_array($date_document_conjoint) ? $date_document_conjoint : [$date_document_conjoint];
                $lieu_document_conjoint = is_array($lieu_document_conjoint) ? $lieu_document_conjoint : [$lieu_document_conjoint];
                $vendeur_acheteur = is_array($vendeur_acheteur) ? $vendeur_acheteur : [$vendeur_acheteur];
                $id_demande_array = is_array($id_demande_array) ? $id_demande_array : [$id_demande_array];
                $nom_complet_personne = is_array($nom_complet_personne) ? $nom_complet_personne : [$nom_complet_personne];
                $statut_contractant = is_array($statut_contractant) ? $statut_contractant : [$statut_contractant];
                $notes = is_array($notes) ? $notes : [$notes];
                $role = is_array($role) ? $role : [$role];
    
                $success = true;
                $errors = [];
    
                // Begin transaction for atomicity
                $pdo->beginTransaction();
    
                // Loop through each row of data
                for ($i = 0; $i < count($numero_document_identite); $i++) {
                    // Skip if no document number for this row
                    if (empty($numero_document_identite[$i])) {
                        $errors[] = "Missing numero_document_identite for row " . ($i + 1);
                        continue;
                    }
    
                    // Prepare SQL query
                    $sql = "INSERT INTO personnes_contracteurs (
                        prenom, numero_document_identite, nom, prenom_pere, date_emission_document, sexe, 
                        nationalite, adresse, profession, etat_civil, prenom_conjoint, nom_conjoint, 
                        prenom_pere_conjoint, prenom_grand_pere_conjoint, surnom_conjoint, 
                        date_naissance_conjoint, lieu_naissance_conjoint, nationalite_conjoint, 
                        numero_document_conjoint, date_document_conjoint, lieu_document_conjoint, 
                        vendeur_acheteur, id_demande, nom_complet_personne, statut_contractant, notes, role
                    ) VALUES (
                        :prenom, :numero_document_identite, :nom, :prenom_pere, :date_emission_document,
                        :sexe, :nationalite, :adresse, :profession, :etat_civil, :prenom_conjoint,
                        :nom_conjoint, :prenom_pere_conjoint, :prenom_grand_pere_conjoint,
                        :surnom_conjoint, :date_naissance_conjoint, :lieu_naissance_conjoint,
                        :nationalite_conjoint, :numero_document_conjoint, :date_document_conjoint,
                        :lieu_document_conjoint, :vendeur_acheteur, :id_demande, :nom_complet_personne,
                        :statut_contractant, :notes, :role
                    )";
    
                    $stmt = $pdo->prepare($sql);
                    $result = $stmt->execute([
                        ':prenom' => $prenom[$i] ?? null,
                        ':numero_document_identite' => $numero_document_identite[$i],
                        ':nom' => $nom[$i] ?? null,
                        ':prenom_pere' => $prenom_pere[$i] ?? null,
                        ':date_emission_document' => $date_emission_document[$i] ?? null,
                        ':sexe' => $sexe[$i] ?? null,
                        ':nationalite' => $nationalite[$i] ?? null,
                        ':adresse' => $adresse[$i] ?? null,
                        ':profession' => $profession[$i] ?? null,
                        ':etat_civil' => $etat_civil[$i] ?? null,
                        ':prenom_conjoint' => $prenom_conjoint[$i] ?? null,
                        ':nom_conjoint' => $nom_conjoint[$i] ?? null,
                        ':prenom_pere_conjoint' => $prenom_pere_conjoint[$i] ?? null,
                        ':prenom_grand_pere_conjoint' => $prenom_grand_pere_conjoint[$i] ?? null,
                        ':surnom_conjoint' => $surnom_conjoint[$i] ?? null,
                        ':date_naissance_conjoint' => $date_naissance_conjoint[$i] ?? null,
                        ':lieu_naissance_conjoint' => $lieu_naissance_conjoint[$i] ?? null,
                        ':nationalite_conjoint' => $nationalite_conjoint[$i] ?? null,
                        ':numero_document_conjoint' => $numero_document_conjoint[$i] ?? null,
                        ':date_document_conjoint' => $date_document_conjoint[$i] ?? null,
                        ':lieu_document_conjoint' => $lieu_document_conjoint[$i] ?? null,
                        ':vendeur_acheteur' => $vendeur_acheteur[$i] ?? null,
                        ':id_demande' => $id_demande_array[$i] ?? null,
                        ':nom_complet_personne' => $nom_complet_personne[$i],
                        ':statut_contractant' => $statut_contractant[$i] ?? null,
                        ':notes' => $notes[$i] ?? null,
                        ':role' => $role[$i] ?? null
                    ]);
    
                    if (!$result) {
                        $success = false;
                        $errors[] = "Failed to insert row " . ($i + 1) . ": " . implode(", ", $stmt->errorInfo());
                    }
                }
    
                // Commit or rollback based on success
                if ($success && empty($errors)) {
                    $pdo->commit();
                    return "✅ Successfully inserted all contract parties";
                } else {
                    $pdo->rollBack();
                    error_log("Errors in personneContratc: " . implode(", ", $errors));
                    return "❌ Partial or no data inserted: " . implode(", ", $errors);
                }
            } catch (PDOException $e) {
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                error_log("SQL Error in personneContratc: " . $e->getMessage());
                return "❌ SQL Error: " . $e->getMessage();
            }
        }
        return null;
    }*/
    
    
    





    //القسم الخامس
    //dessin_immobilier1
    public function dessin_immobilier1($pdo) {
        if (isset($_POST['submit'])) {
            try {
                // Check if inputs are arrays and iterate over them
                $nom_droit1_array = isset($_POST['nom_droit1']) ? (is_array($_POST['nom_droit1']) ? $_POST['nom_droit1'] : [$_POST['nom_droit1']]) : [];
                $sujet_contrat1_array = isset($_POST['sujet_contrat1']) ? (is_array($_POST['sujet_contrat1']) ? $_POST['sujet_contrat1'] : [$_POST['sujet_contrat1']]) : [];
                $unite1_array = isset($_POST['unite1']) ? (is_array($_POST['unite1']) ? $_POST['unite1'] : [$_POST['unite1']]) : [];
                $detail_general_array = isset($_POST['detail_general']) ? (is_array($_POST['detail_general']) ? $_POST['detail_general'] : [$_POST['detail_general']]) : [];
                $contenu1_array = isset($_POST['contenu1']) ? (is_array($_POST['contenu1']) ? $_POST['contenu1'] : [$_POST['contenu1']]) : [];
                $valeur_prix1_array = isset($_POST['valeur_prix1']) ? (is_array($_POST['valeur_prix1']) ? $_POST['valeur_prix1'] : [$_POST['valeur_prix1']]) : [];
                $dure1_array = isset($_POST['dure1']) ? (is_array($_POST['dure1']) ? $_POST['dure1'] : [$_POST['dure1']]) : [];
                $surplus1_array = isset($_POST['surplus1']) ? (is_array($_POST['surplus1']) ? $_POST['surplus1'] : [$_POST['surplus1']]) : [];
    
                // Ensure at least one row has valid data
                if (empty($nom_droit1_array) || !isset($nom_droit1_array[0]) || empty(trim($nom_droit1_array[0]))) {
                    return "❌ No valid data provided";
                }
    
                $sql = "INSERT INTO dessin_immobiler1
                        (nom_droit1, sujet_contrat1, unite1, detail_general, contenu1, valeur_prix1, dure1, surplus1)
                        VALUES
                        (:nom_droit1, :sujet_contrat1, :unite1, :detail_general, :contenu1, :valeur_prix1, :dure1, :surplus1)";
               
                $stmt = $pdo->prepare($sql);
    
                // Process each row
                $success = true;
                foreach ($nom_droit1_array as $index => $nom_droit1) {
                    // Ensure scalar values
                    $nom_droit1 = isset($nom_droit1_array[$index]) && is_scalar($nom_droit1_array[$index]) ? $nom_droit1_array[$index] : null;
                    $sujet_contrat1 = isset($sujet_contrat1_array[$index]) && is_scalar($sujet_contrat1_array[$index]) ? $sujet_contrat1_array[$index] : null;
                    $unite1 = isset($unite1_array[$index]) && is_scalar($unite1_array[$index]) ? $unite1_array[$index] : null;
                    $detail_general = isset($detail_general_array[$index]) && is_scalar($detail_general_array[$index]) ? $detail_general_array[$index] : null;
                    $contenu1 = isset($contenu1_array[$index]) && is_scalar($contenu1_array[$index]) ? $contenu1_array[$index] : null;
                    $valeur_prix1 = isset($valeur_prix1_array[$index]) && is_scalar($valeur_prix1_array[$index]) ? $valeur_prix1_array[$index] : null;
                    $dure1 = isset($dure1_array[$index]) && is_scalar($dure1_array[$index]) ? $dure1_array[$index] : null;
                    $surplus1 = isset($surplus1_array[$index]) && is_scalar($surplus1_array[$index]) ? $surplus1_array[$index] : null;
    
                    // Skip if no valid data for this row
                    if (empty($nom_droit1)) {
                        error_log("Skipping row $index: nom_droit1 is empty");
                        continue;
                    }
    
                    // Execute the query for this row
                    $result = $stmt->execute([
                        ':nom_droit1' => $nom_droit1,
                        ':sujet_contrat1' => $sujet_contrat1,
                        ':unite1' => $unite1,
                        ':detail_general' => $detail_general,
                        ':contenu1' => $contenu1,
                        ':valeur_prix1' => $valeur_prix1,
                        ':dure1' => $dure1,
                        ':surplus1' => $surplus1
                    ]);
    
                    if (!$result) {
                        $success = false;
                        error_log("Failed to insert row $index: " . print_r($stmt->errorInfo(), true));
                    }
                }
    
                return $success ? "✅ Successfully inserted rows" : "❌ Failed to insert some rows";
            } catch (PDOException $e) {
                error_log("Erreur dans dessin_immobilier1: " . $e->getMessage());
                return "❌ SQL Error: " . $e->getMessage();
            }
        }
        return null;
    }
    public function dessin_immobilier2($pdo) {
        if (isset($_POST['submit'])) {
            try {
                // Check if inputs are arrays and iterate over them
                $date_inscri2_array = isset($_POST['date_inscri2']) ? (is_array($_POST['date_inscri2']) ? $_POST['date_inscri2'] : [$_POST['date_inscri2']]) : [];
                $lieu_inscri2_array = isset($_POST['lieu_inscri2']) ? (is_array($_POST['lieu_inscri2']) ? $_POST['lieu_inscri2'] : [$_POST['lieu_inscri2']]) : [];
                $doc2_array = isset($_POST['doc2']) ? (is_array($_POST['doc2']) ? $_POST['doc2'] : [$_POST['doc2']]) : [];
                $num_inscri2_array = isset($_POST['num_inscri2']) ? (is_array($_POST['num_inscri2']) ? $_POST['num_inscri2'] : [$_POST['num_inscri2']]) : [];
                $num_succursale2_array = isset($_POST['num_succursale2']) ? (is_array($_POST['num_succursale2']) ? $_POST['num_succursale2'] : [$_POST['num_succursale2']]) : [];
    
                // Ensure at least one row has valid data
                if (empty($date_inscri2_array) || !isset($date_inscri2_array[0]) || empty(trim($date_inscri2_array[0]))) {
                    return "❌ No valid data provided";
                }
    
                $sql = "INSERT INTO dessin_immobiliers2
                        (date_inscri2, lieu_inscri2, doc2, num_inscri2, num_succursale2)
                        VALUES
                        (:date_inscri2, :lieu_inscri2, :doc2, :num_inscri2, :num_succursale2)";
               
                $stmt = $pdo->prepare($sql);
    
                // Process each row
                $success = true;
                foreach ($date_inscri2_array as $index => $date_inscri2) {
                    // Ensure scalar values
                    $date_inscri2 = isset($date_inscri2_array[$index]) && is_scalar($date_inscri2_array[$index]) ? $date_inscri2_array[$index] : null;
                    $lieu_inscri2 = isset($lieu_inscri2_array[$index]) && is_scalar($lieu_inscri2_array[$index]) ? $lieu_inscri2_array[$index] : null;
                    $doc2 = isset($doc2_array[$index]) && is_scalar($doc2_array[$index]) ? $doc2_array[$index] : null;
                    $num_inscri2 = isset($num_inscri2_array[$index]) && is_scalar($num_inscri2_array[$index]) ? $num_inscri2_array[$index] : null;
                    $num_succursale2 = isset($num_succursale2_array[$index]) && is_scalar($num_succursale2_array[$index]) ? $num_succursale2_array[$index] : null;
    
                    // Skip if no valid data for this row
                    if (empty($date_inscri2)) {
                        error_log("Skipping row $index: date_inscri2 is empty");
                        continue;
                    }
    
                    // Execute the query for this row
                    $result = $stmt->execute([
                        ':date_inscri2' => $date_inscri2,
                        ':lieu_inscri2' => $lieu_inscri2,
                        ':doc2' => $doc2,
                        ':num_inscri2' => $num_inscri2,
                        ':num_succursale2' => $num_succursale2
                    ]);
    
                    if (!$result) {
                        $success = false;
                        error_log("Failed to insert row $index: " . print_r($stmt->errorInfo(), true));
                    }
                }
    
                return $success ? "✅ Successfully inserted rows" : "❌ Failed to insert some rows";
            } catch (PDOException $e) {
                error_log("Erreur dans dessin_immobilier2: " . $e->getMessage());
                return "❌ SQL Error: " . $e->getMessage();
            }
        }
        return null;
    }
        
    
    public function dessin_immobilier3($pdo) {
        if (isset($_POST['submit'])) {
            try {
                // Check if inputs are arrays and iterate over them
                $regime_finance_couple3_array = isset($_POST['regime_finance_couple3']) ? (is_array($_POST['regime_finance_couple3']) ? $_POST['regime_finance_couple3'] : [$_POST['regime_finance_couple3']]) : [];
                $remarques3_array = isset($_POST['remarques3']) ? (is_array($_POST['remarques3']) ? $_POST['remarques3'] : [$_POST['remarques3']]) : [];
    
                // Ensure at least one row has valid data
                if (empty($regime_finance_couple3_array) || !isset($regime_finance_couple3_array[0]) || empty(trim($regime_finance_couple3_array[0]))) {
                    return "❌ No valid data provided";
                }
    
                $sql = "INSERT INTO dessin_immobiler3
                        (regime_finance_couple3, remarques3)
                        VALUES
                        (:regime_finance_couple3, :remarques3)";
               
                $stmt = $pdo->prepare($sql);
    
                // Process each row
                $success = true;
                foreach ($regime_finance_couple3_array as $index => $regime_finance_couple3) {
                    // Ensure scalar values
                    $regime_finance_couple3 = isset($regime_finance_couple3_array[$index]) && is_scalar($regime_finance_couple3_array[$index]) ? $regime_finance_couple3_array[$index] : null;
                    $remarques3 = isset($remarques3_array[$index]) && is_scalar($remarques3_array[$index]) ? $remarques3_array[$index] : null;
    
                    // Skip if no valid data for this row
                    if (empty($regime_finance_couple3)) {
                        error_log("Skipping row $index: regime_finance_couple3 is empty");
                        continue;
                    }
    
                    // Execute the query for this row
                    $result = $stmt->execute([
                        ':regime_finance_couple3' => $regime_finance_couple3,
                        ':remarques3' => $remarques3
                    ]);
    
                    if (!$result) {
                        $success = false;
                        error_log("Failed to insert row $index: " . print_r($stmt->errorInfo(), true));
                    }
                }
    
                return $success ? "✅ Successfully inserted rows" : "❌ Failed to insert some rows";
            } catch (PDOException $e) {
                error_log("Erreur dans dessin_immobilier3: " . $e->getMessage());
                return "❌ SQL Error: " . $e->getMessage();
            }
        }
        return null;
    }
    public function dessin_immobilier4($pdo, $valeur_contrat_dinar, $prix_ecriture, $id_demande, $statut_contractant) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
            try {
                // Validate input
                if (empty(trim($valeur_contrat_dinar))) {
                    error_log("No valid data provided: valeur_contrat_dinar is empty");
                    return "❌ No valid data provided";
                }
    
                if (empty(trim($statut_contractant))) {
                    error_log("Statut contractant is empty");
                    return "❌ Le statut du contractant est requis";
                }
    
                $sql = "INSERT INTO public.dessin_immobilier4 (valeur_contrat_dinar, prix_ecriture, id_demande, statut_contractant) 
                        VALUES (:valeur_contrat_dinar, :prix_ecriture, :id_demande, :statut_contractant)";
                $stmt = $pdo->prepare($sql);
    
                // Log parameters for debugging
                error_log("Inserting: valeur_contrat_dinar=$valeur_contrat_dinar, prix_ecriture=$prix_ecriture, id_demande=$id_demande, statut_contractant=$statut_contractant");
    
                // Execute the query
                $result = $stmt->execute([
                    ':valeur_contrat_dinar' => $valeur_contrat_dinar,
                    ':prix_ecriture' => $prix_ecriture,
                    ':id_demande' => $id_demande,
                    ':statut_contractant' => $statut_contractant
                ]);
    
                if (!$result) {
                    error_log("Failed to insert: " . print_r($stmt->errorInfo(), true));
                    return "❌ Failed to insert row";
                }
    
                return "✅ Successfully inserted row";
            } catch (PDOException $e) {
                error_log("Erreur dans dessin_immobilier4: " . $e->getMessage());
                return "❌ SQL Error: " . $e->getMessage();
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
    
    


    //القسم الرابع
    //IDpersonnes
    public function idPersonnes($pdo) {
        if (isset($_POST['submit'])) {
            try {
                // Check if inputs are arrays and convert scalars to arrays
                $prenom_personne_array = isset($_POST['prenom_personne']) ? (is_array($_POST['prenom_personne']) ? $_POST['prenom_personne'] : [$_POST['prenom_personne']]) : [];
                $prenom_pere_array = isset($_POST['prenom_pere']) ? (is_array($_POST['prenom_pere']) ? $_POST['prenom_pere'] : [$_POST['prenom_pere']]) : [];
                $prenom_grandpere_array = isset($_POST['prenom_grandpere']) ? (is_array($_POST['prenom_grandpere']) ? $_POST['prenom_grandpere'] : [$_POST['prenom_grandpere']]) : [];
                $nom_personne_array = isset($_POST['nom_personne']) ? (is_array($_POST['nom_personne']) ? $_POST['nom_personne'] : [$_POST['nom_personne']]) : [];
                $statut_array = isset($_POST['statut']) ? (is_array($_POST['statut']) ? $_POST['statut'] : [$_POST['statut']]) : [];
                $signature_array = isset($_POST['signature']) ? (is_array($_POST['signature']) ? $_POST['signature'] : [$_POST['signature']]) : [];
    
                // Validate that we have at least one row of data
                if (empty($statut_array)) {
                    return "❌ Aucune donnée n'a été fournie";
                }

                // Begin transaction
                $pdo->beginTransaction();

                $sql = "INSERT INTO IDpersonnes (
                    prenom_personne, prenom_pere, prenom_grandpere, nom_personne, statut, signature
                ) VALUES (
                    :prenom_personne, :prenom_pere, :prenom_grandpere, :nom_personne, :statut, :signature
                )";
    
                $stmt = $pdo->prepare($sql);
    
                // Process each row
                $insertedRows = 0;
                $errors = [];

                foreach ($statut_array as $index => $statut) {
                    // Validate required fields
                    if (empty(trim($statut))) {
                        $errors[] = "Ligne " . ($index + 1) . ": Le statut est requis";
                        continue;
                    }

                    try {
                        $result = $stmt->execute([
                            ':prenom_personne' => isset($prenom_personne_array[$index]) ? trim($prenom_personne_array[$index]) : null,
                            ':prenom_pere' => isset($prenom_pere_array[$index]) ? trim($prenom_pere_array[$index]) : null,
                            ':prenom_grandpere' => isset($prenom_grandpere_array[$index]) ? trim($prenom_grandpere_array[$index]) : null,
                            ':nom_personne' => isset($nom_personne_array[$index]) ? trim($nom_personne_array[$index]) : null,
                            ':statut' => trim($statut),
                            ':signature' => isset($signature_array[$index]) ? trim($signature_array[$index]) : null
                        ]);

                        if ($result) {
                            $insertedRows++;
                        } else {
                            $errors[] = "Ligne " . ($index + 1) . ": Échec de l'insertion";
                        }
                    } catch (PDOException $e) {
                        $errors[] = "Ligne " . ($index + 1) . ": " . $e->getMessage();
                    }
                }

                // Commit or rollback based on success
                if (empty($errors)) {
                    $pdo->commit();
                    return "✅ $insertedRows ligne(s) insérée(s) avec succès";
                } else {
                    $pdo->rollBack();
                    return "❌ Erreurs lors de la sauvegarde:<br>" . implode("<br>", $errors);
                }

            } catch (PDOException $e) {
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                error_log("Erreur dans idPersonnes: " . $e->getMessage());
                return "❌ Erreur SQL: " . $e->getMessage();
            }
        }
        return null;
    }


    //perception1
    public function perception1($pdo) {
        if (isset($_POST['submit'])) {
            try {
                // Check if inputs are arrays and convert scalars to arrays
                $id_montant1_array = isset($_POST['id_montant1']) ? (is_array($_POST['id_montant1']) ? $_POST['id_montant1'] : [$_POST['id_montant1']]) : [];
                $partieabstrait1_array = isset($_POST['partieabstrait1']) ? (is_array($_POST['partieabstrait1']) ? $_POST['partieabstrait1'] : [$_POST['partieabstrait1']]) : [];
                $montant_obligatoire1_array = isset($_POST['montant_obligatoire1']) ? (is_array($_POST['montant_obligatoire1']) ? $_POST['montant_obligatoire1'] : [$_POST['montant_obligatoire1']]) : [];
                $montant_paye1_array = isset($_POST['montant_paye1']) ? (is_array($_POST['montant_paye1']) ? $_POST['montant_paye1'] : [$_POST['montant_paye1']]) : [];
                $num_recu1_array = isset($_POST['num_recu1']) ? (is_array($_POST['num_recu1']) ? $_POST['num_recu1'] : [$_POST['num_recu1']]) : [];
                $date_payement1_array = isset($_POST['date_payement1']) ? (is_array($_POST['date_payement1']) ? $_POST['date_payement1'] : [$_POST['date_payement1']]) : [];
    
                // Ensure at least one row has valid data
                if (empty($id_montant1_array) || !isset($id_montant1_array[0]) || empty(trim($id_montant1_array[0]))) {
                    return "❌ No valid ID montant provided";
                }
    
                $sql = "INSERT INTO perception1(
                    id_montant1, partieabstrait1, montant_obligatoire1, montant_paye1, num_recu1, date_payement1)
                    VALUES (:id_montant1, :partieabstrait1, :montant_obligatoire1, :montant_paye1, :num_recu1, :date_payement1)";
    
                $stmt = $pdo->prepare($sql);
    
                // Process each row
                $success = true;
                foreach ($id_montant1_array as $index => $id_montant1) {
                    // Ensure scalar values
                    $id_montant1 = isset($id_montant1_array[$index]) && is_scalar($id_montant1_array[$index]) ? $id_montant1_array[$index] : null;
                    $partieabstrait1 = isset($partieabstrait1_array[$index]) && is_scalar($partieabstrait1_array[$index]) ? $partieabstrait1_array[$index] : null;
                    $montant_obligatoire1 = isset($montant_obligatoire1_array[$index]) && is_scalar($montant_obligatoire1_array[$index]) ? $montant_obligatoire1_array[$index] : null;
                    $montant_paye1 = isset($montant_paye1_array[$index]) && is_scalar($montant_paye1_array[$index]) ? $montant_paye1_array[$index] : null;
                    $num_recu1 = isset($num_recu1_array[$index]) && is_scalar($num_recu1_array[$index]) ? $num_recu1_array[$index] : null;
                    $date_payement1 = isset($date_payement1_array[$index]) && is_scalar($date_payement1_array[$index]) ? $date_payement1_array[$index] : null;
    
                    // Skip if no valid id_montant1 for this row
                    if (empty($id_montant1)) {
                        error_log("Skipping row $index: id_montant1 is empty");
                        continue;
                    }
    
                    // Execute the query for this row
                    $result = $stmt->execute([
                        ':id_montant1' => $id_montant1,
                        ':partieabstrait1' => $partieabstrait1,
                        ':montant_obligatoire1' => $montant_obligatoire1,
                        ':montant_paye1' => $montant_paye1,
                        ':num_recu1' => $num_recu1,
                        ':date_payement1' => $date_payement1
                    ]);
    
                    if (!$result) {
                        $success = false;
                        error_log("Failed to insert row $index: " . print_r($stmt->errorInfo(), true));
                    }
                }
    
                return $success ? "✅ Successfully inserted rows" : "❌ Failed to insert some rows";
            } catch (PDOException $e) {
                error_log("Erreur dans perception1: " . $e->getMessage());
                return "❌ SQL Error: " . $e->getMessage();
            }
        }
        return null;
    }

    //perception4
    public function perception4($pdo) {
        if (isset($_POST['submit'])) {
            try {
                // Récupération dces valeurs et conversion en scalaires
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








    //PARTIE VALIDATION DES DONNEES
    public function getContratDetailsById($id_demande) {
        $sql = "
            SELECT 
                c.num_contrat,
                c.sujet_contrat,
                dem.date_demande,
                di.valeur_prix1,
                di.contenu1,
                di.detail_general,
                tg.libile_gouv,
                r.nom_redacteur,
                r.prenom_redacteur
            FROM public.\"contrats\" c
            JOIN public.\"T_demande\" dem ON c.id_demande = dem.id_demande
            JOIN public.\"dessin_immobiler1\" di ON c.id_demande = di.id_demande
            JOIN public.\"T_gouv\" tg ON di.id_demande = tg.id_demande
            JOIN public.\"redacteur\" r ON c.id_redacteur = r.id_redacteur
            WHERE c.id_demande = :id_demande
        ";
    
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id_demande', $id_demande, PDO::PARAM_INT);
        $stmt->execute();
    
        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }
    
     // New function to generate PDF by calling app.py
     public function generateContractPDF($id_demande) {
        $logFile = 'C:/wamp64/www/PFE_erij/PFEEEEEEEEEEEEE/debug.log';
        try {
            $python_script = 'C:/wamp64/www/PFE_erij/PFEEEEEEEEEEEEE/app.py';
            $python_exec = 'python'; // Adjust path if needed, e.g., 'C:/Python39/python.exe'
            $id_demande_escaped = escapeshellarg($id_demande);
            $command = "$python_exec " . escapeshellarg($python_script) . " $id_demande_escaped 2>&1";
            file_put_contents($logFile, "Executing command: $command\n", FILE_APPEND);

            $output = [];
            $return_var = 0;
            exec($command, $output, $return_var);
            file_put_contents($logFile, "Python exec returned: code=$return_var, output=" . implode("\n", $output) . "\n", FILE_APPEND);

            if ($return_var !== 0) {
                throw new Exception("Python script failed: " . implode("\n", $output));
            }

            $pdf_path = "C:/wamp64/www/PFE_erij/PFEEEEEEEEEEEEE/contrat_{$id_demande}.pdf";
            if (!file_exists($pdf_path)) {
                throw new Exception("PDF file not generated at $pdf_path");
            }

            return $pdf_path;
        } catch (Exception $e) {
            file_put_contents($logFile, "PDF Generation Error: " . $e->getMessage() . "\n", FILE_APPEND);
            throw $e;
        }
    }
    //المؤيدات
    function getPiecesJointesByDemandeV($id_demande) {
        try {
            $sql = "SELECT * FROM pieces_jointes WHERE id_demande = :id_demande";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id_demande' => $id_demande]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error in getPiecesJointesByDemande: " . $e->getMessage());
            return [];
        }
    }



        

    //أطراف التعاقد
    public function getPerContracV() {
        $sql = "SELECT nom_complet_personne, role
                FROM personnes_contracteurs"; 

        $stmt = $this->pdo->prepare($sql); 
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //dessin_immobilier1
    public function getDI1($id_demande) {
        try {
            $sql = 'SELECT nom_droit1, sujet_contrat1, unite1, detail_general, contenu1, valeur_prix1, dure1, surplus1 
                    FROM public.dessin_immobiler1 WHERE id_demande = :id_demande';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id_demande' => $id_demande]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("Nombre de résultats dans getDI1 : " . count($result));
            return $result;
        } catch (PDOException $e) {
            error_log("Erreur dans getDI1 : " . $e->getMessage());
            return [];
        }
    }

    //dessin_immobiliers2
    public function getDI2() {
        try {
            $sql = 'SELECT date_inscri2, lieu_inscri2, doc2, num_inscri2, num_succursale2, id_demande FROM public.dessin_immobilers2';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("Nombre de résultats dans getDI2 : " . count($result));
            error_log("Données dans getDI2 : " . print_r($result, true));
            return $result;
        } catch (PDOException $e) {
            error_log("Erreur dans getDI2 : " . $e->getMessage());
            return [];
        }
    }
    //dessin_immobilier3
    public function getDI3() {
        try {
            $sql = 'SELECT regime_finance_couple3, remarques3, id_demande FROM public.dessin_immobiler3';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("Nombre de résultats dans getDI3 : " . count($result));
            error_log("Données dans getDI3 : " . print_r($result, true));
            return $result;
        } catch (PDOException $e) {
            error_log("Erreur dans getDI3 : " . $e->getMessage());
            return [];
        }
    }
    //dessin_immobilier4
    public function getDI4() {
        try {
            $sql = 'SELECT valeur_contrat_dinar, prix_ecriture, id_demande FROM public.dessin_immobilier4';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("Nombre de résultats dans getDI4 : " . count($result));
            error_log("Données dans getDI4 : " . print_r($result, true));
            return $result;
        } catch (PDOException $e) {
            error_log("Erreur dans getDI4 : " . $e->getMessage());
            return [];
        }
    }

    //chapitres juridiques
    public function getChJ() {
        try {
            $sql = 'SELECT contenue_chapitre FROM public.chapitres_juridiques';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("Nombre de résultats dans getChJ : " . count($result));
            error_log("Données dans getChJ : " . print_r($result, true));
            return $result;
        } catch (PDOException $e) {
            error_log("Erreur dans getChJ : " . $e->getMessage());
            return [];
        }
    }
    //perception1
    public function getPer1() {
        try {
            $sql = 'SELECT id_montant1, partieabstrait1, montant_obligatoire1, montant_paye1, num_recu1, date_payement1 FROM public.perception1';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("Nombre de résultats dans getPer1 : " . count($result));
            error_log("Données dans getPer1 : " . print_r($result, true));
            return $result;
        } catch (PDOException $e) {
            error_log("Erreur dans getPer1 : " . $e->getMessage());
            return [];
        }
    }

    public function getPerceptionById($id_demande) {
        $sql = "SELECT * FROM perceptions WHERE id_demande = :id_demande";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id_demande', $id_demande, PDO::PARAM_INT);
        $stmt->execute();
    
        if ($stmt->rowCount() > 0) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }
    
    //perception2
    public function getPer2() {
        try {
            $sql = "SELECT * FROM perception2";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("Nombre de résultats dans getPer2 : " . count($result)); // Log du nombre de lignes
            error_log("Données dans getPer2 : " . print_r($result, true)); // Log des données
            return $result;
        } catch (PDOException $e) {
            error_log("Erreur dans getPer2 : " . $e->getMessage());
            return [];
        }
    }
    //perception3
    public function getPer3() {
        try {
            $sql = 'SELECT valeur_dinar3, pourcent3, montant_dinar3, signature3 FROM public.perecption3';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("Nombre de résultats dans getPer3 : " . count($result));
            error_log("Données dans getPer3 : " . print_r($result, true));
            return $result;
        } catch (PDOException $e) {
            error_log("Erreur dans getPer3 : " . $e->getMessage());
            return [];
        }
    }
    //perception4
    public function getPer4() {
       try {
            $sql = "SELECT * FROM perception4";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("Nombre de résultats dans getPer4 : " . count($result));
            error_log("Données dans getPer4 : " . print_r($result, true));
            return $result;
        } catch (PDOException $e) {
            error_log("Erreur dans getPer4 : " . $e->getMessage());
            return [];
        }
    }
    //somme
    public function getSomme() {
        try {
            $sql = 'SELECT somme_prix_obligatoire, somme_prix_paye FROM public.somme';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("Nombre de résultats dans getSomme : " . count($result));
            error_log("Données dans getSomme : " . print_r($result, true));
            return $result;
        } catch (PDOException $e) {
            error_log("Erreur dans getSomme : " . $e->getMessage());
            return [];
        }
    }
    public function getIDpersonne() {
        try {
            $sql = 'SELECT prenom_personne, prenom_pere, prenom_grandpere, nom_personne, statut, signature FROM public."IDpersonnes"';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("Nombre de résultats dans getIDpersonne : " . count($result));
            error_log("Données dans getIDpersonne : " . print_r($result, true));
            return $result;
        } catch (PDOException $e) {
            error_log("Erreur dans getIDpersonne : " . $e->getMessage());
            return [];
        }
    }

    function getContratInfo($id_demande) {
        // Connexion à la BDD
        $conn = new PDO("pgsql:host=localhost;dbname=pfe_bdd", "postgres", "pfe");
    
        $stmt = $conn->prepare("
            SELECT num_contrat, date_demande 
            FROM contrats c 
            JOIN T_demande dem ON c.id_demande = dem.id_demande 
            WHERE c.id_demande = :id_demande
        ");
        $stmt->execute([':id_demande' => $id_demande]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        return $result ?: null;
    }

  
    public function insertTextRefus($pdo) {
        if (isset($_POST['submit'])) {
            try {
                $text_refus = $_POST['text_refus'] ?? null;
    
                if (!$text_refus || trim($text_refus) === '') {
                    return "❌ Texte vide";
                }
    
                $sql = "INSERT INTO public.text_refus(text_refus) VALUES (:text_refus)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':text_refus' => $text_refus
                ]);
    
                return "✅ Enregistrement réussi";
            } catch (PDOException $e) {
                return "❌ Erreur BDD : " . $e->getMessage();
            }
        }
        return null;
    }


    //inserer un text refus
    public function insertTextRefusR($pdo) {
        if (isset($_POST['submit'])) {
            try {
                $text_refus = $_POST['text_refus'] ?? null;
    
                if (!$text_refus || trim($text_refus) === '') {
                    return "❌ Texte vide";
                }
    
                $sql = "INSERT INTO public.text_refus(text_refus) VALUES (:text_refus)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':text_refus' => $text_refus
                ]);
    
                return "✅ Enregistrement réussi";
            } catch (PDOException $e) {
                return "❌ Erreur BDD : " . $e->getMessage();
            }
        }
        return null;
    }

    //insertion d'une nouvelle liggne 
    function insererNouvelleLigneEtChangerEtat($pdo, $id_demande) {
        try {
            $stmt = $pdo->prepare("INSERT INTO contrats (id_demande, date_redaction, nb_demande_redaction, nb_acte, etat)
                                   VALUES (:id_demande, CURRENT_DATE, 0, 0, 'في الانتظار')");
            $stmt->bindParam(':id_demande', $id_demande);
            $stmt->execute();
            $update = $pdo->prepare("UPDATE demandes SET etat_demande = 1 WHERE id_demande = :id_demande");
            $update->bindParam(':id_demande', $id_demande);
            $update->execute();
    
            return true;
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
            return false;
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