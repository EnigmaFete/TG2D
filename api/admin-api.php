<?php
if (!function_exists("tg2d_val")) {
    function tg2d_val(&$val, $default = "") {
        return isset($val) && $val !== null ? $val : $default;
    }
}
/**
 * TG2D MAROC — API DU TABLEAU DE BORD ADMINISTRATEUR
 * Point d'accès complet pour la gestion des demandes de projets en base de données,
 * statistiques, filtres, modifications de statut, notes et exports.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json; charset=UTF-8');

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/mailer.php';

// Récupération du JSON si applicable
$inputRaw = file_get_contents('php://input');
$jsonBody = json_decode($inputRaw, true);
if (is_array($jsonBody)) {
    $_REQ = array_merge($_REQUEST, $jsonBody);
} else {
    $_REQ = $_REQUEST;
}

$action = (isset($_GET['action']) ? $_GET['action'] : (isset($_POST['action']) ? $_POST['action'] : (isset($_REQ['action']) ? $_REQ['action'] : '')));

$config = tg2d_get_config();

// -------------------------------------------------------------
// 1. AUTHENTIFICATION
// -------------------------------------------------------------
if ($action === 'login') {
    $user = trim(tg2d_val($_REQ['username'], ''));
    $pass = trim(tg2d_val($_REQ['password'], ''));

    $adminUser = $config['admin']['username'];
    $adminPass = $config['admin']['password'];

    if ($user === $adminUser && ($pass === $adminPass || password_verify($pass, $adminPass))) {
        $_SESSION['tg2d_admin_auth'] = true;
        $_SESSION['tg2d_admin_user'] = $user;
        $_SESSION['tg2d_login_time'] = time();

        echo json_encode([
            'success' => true,
            'message' => 'Authentification réussie.',
            'user'    => $user
        ]);
        exit;
    } else {
        http_response_code(401);
        echo json_encode(['success' => false, 'error' => 'Identifiant ou mot de passe incorrect.']);
        exit;
    }
}

if ($action === 'logout') {
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
    echo json_encode(['success' => true, 'message' => 'Session terminée.']);
    exit;
}

if ($action === 'check_auth') {
    $isAuth = !empty($_SESSION['tg2d_admin_auth']) && $_SESSION['tg2d_admin_auth'] === true;
    echo json_encode([
        'authenticated' => $isAuth,
        'user'          => $isAuth ? ((isset($_SESSION['tg2d_admin_user']) ? $_SESSION['tg2d_admin_user'] : 'admin')) : null
    ]);
    exit;
}

// -------------------------------------------------------------
// VÉRIFICATION DE LA SESSION ADMIN POUR TOUTES LES AUTRES ACTIONS
// -------------------------------------------------------------
if (empty($_SESSION['tg2d_admin_auth']) || $_SESSION['tg2d_admin_auth'] !== true) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Session expirée ou accès non autorisé. Veuillez vous reconnecter.']);
    exit;
}

// -------------------------------------------------------------
// 2. ACTIONS MÉTIER
// -------------------------------------------------------------
try {
    $pdo = tg2d_get_db();

    // STATISTIQUES GLOBALES
    if ($action === 'get_stats') {
        // Total
        $totalStmt = $pdo->query("SELECT COUNT(*) AS total FROM inquiries");
        $total = (int)$totalStmt->fetch()['total'];

        // Statuts
        $statusStmt = $pdo->query("SELECT status, COUNT(*) as c FROM inquiries GROUP BY status");
        $statusCounts = [
            'Nouveau'         => 0,
            'Contacté'        => 0,
            'Étude en cours'  => 0,
            'Devis envoyé'    => 0,
            'Clôturé'         => 0,
            'Archivé'         => 0
        ];
        while ($row = $statusStmt->fetch()) {
            if (isset($statusCounts[$row['status']])) {
                $statusCounts[$row['status']] = (int)$row['c'];
            }
        }

        // Besoins
        $besoinStmt = $pdo->query("SELECT besoin, COUNT(*) as c FROM inquiries GROUP BY besoin ORDER BY c DESC");
        $besoinCounts = [];
        while ($row = $besoinStmt->fetch()) {
            $besoinCounts[$row['besoin']] = (int)$row['c'];
        }

        // Profils
        $profilStmt = $pdo->query("SELECT profil, COUNT(*) as c FROM inquiries GROUP BY profil ORDER BY c DESC");
        $profilCounts = [];
        while ($row = $profilStmt->fetch()) {
            $profilCounts[$row['profil']] = (int)$row['c'];
        }

        // Aujourd'hui et Cette semaine
        $todayStmt = $pdo->query("SELECT COUNT(*) as c FROM inquiries WHERE date(created_at) = date('now', 'localtime')");
        $today = (int)$todayStmt->fetch()['c'];

        // Taux email envoyé
        $emailStmt = $pdo->query("SELECT COUNT(*) as c FROM inquiries WHERE email_sent = 1");
        $emailSentCount = (int)$emailStmt->fetch()['c'];

        // Statistiques Devis
        $devisTotalStmt = $pdo->query("SELECT COUNT(*) as c, COALESCE(SUM(montant_ht), 0) as total_ht, COALESCE(SUM(montant_ttc), 0) as total_ttc FROM devis");
        $devisMetrics = $devisTotalStmt->fetch();
        $devisTotal = (int)$devisMetrics['c'];
        $devisTotalHt = (float)$devisMetrics['total_ht'];
        $devisTotalTtc = (float)$devisMetrics['total_ttc'];

        $devisValidesStmt = $pdo->query("SELECT COUNT(*) as c FROM devis WHERE status = 'Validé'");
        $devisValides = (int)$devisValidesStmt->fetch()['c'];

        echo json_encode([
            'success'          => true,
            'total'            => $total,
            'today'            => $today,
            'status'           => $statusCounts,
            'besoins'          => $besoinCounts,
            'profils'          => $profilCounts,
            'email_sent'       => $emailSentCount,
            'email_rate'       => $total > 0 ? round(($emailSentCount / $total) * 100, 1) : 100,
            'devis_total'      => $devisTotal,
            'devis_total_ht'   => $devisTotalHt,
            'devis_total_ttc'  => $devisTotalTtc,
            'devis_valides'    => $devisValides
        ]);
        exit;
    }

    // LISTE DES DEMANDES AVEC RECHERCHE ET FILTRES
    if ($action === 'get_inquiries') {
        $search = trim((isset($_REQ['search']) ? $_REQ['search'] : ''));
        $status = trim(tg2d_val($_REQ['status'], ''));
        $besoin = trim((isset($_REQ['besoin']) ? $_REQ['besoin'] : ''));
        $page   = max(1, (int)((isset($_REQ['page']) ? $_REQ['page'] : 1)));
        $limit  = min(100, max(10, (int)((isset($_REQ['limit']) ? $_REQ['limit'] : 50))));
        $offset = ($page - 1) * $limit;

        $where = [];
        $params = [];

        if (!empty($search)) {
            $where[] = "(nom LIKE :s1 OR telephone LIKE :s2 OR email LIKE :s3 OR message LIKE :s4 OR notes LIKE :s5)";
            $params[':s1'] = "%$search%";
            $params[':s2'] = "%$search%";
            $params[':s3'] = "%$search%";
            $params[':s4'] = "%$search%";
            $params[':s5'] = "%$search%";
        }

        if (!empty($status) && $status !== 'all') {
            $where[] = "status = :status";
            $params[':status'] = $status;
        }

        if (!empty($besoin) && $besoin !== 'all') {
            $where[] = "besoin = :besoin";
            $params[':besoin'] = $besoin;
        }

        $whereSql = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

        // Count total
        $countStmt = $pdo->prepare("SELECT COUNT(*) as c FROM inquiries $whereSql");
        $countStmt->execute($params);
        $totalItems = (int)$countStmt->fetch()['c'];

        // Query items
        $querySql = "SELECT id, created_at, nom, telephone, email, profil, besoin, message, status, notes, email_sent, email_error, ip_address 
                     FROM inquiries 
                     $whereSql 
                     ORDER BY created_at DESC 
                     LIMIT :limit OFFSET :offset";

        $stmt = $pdo->prepare($querySql);
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $rows = $stmt->fetchAll();

        echo json_encode([
            'success'     => true,
            'data'        => $rows,
            'total'       => $totalItems,
            'page'        => $page,
            'total_pages' => ceil($totalItems / $limit)
        ]);
        exit;
    }

    // MISE À JOUR DU STATUT D'UNE DEMANDE
    if ($action === 'update_status') {
        $id = (int)(tg2d_val($_REQ['id'], 0));
        $newStatus = trim(tg2d_val($_REQ['status'], ''));

        $allowedStatuses = ['Nouveau', 'Contacté', 'Étude en cours', 'Devis envoyé', 'Clôturé', 'Archivé'];
        if (!in_array($newStatus, $allowedStatuses)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Statut non valide.']);
            exit;
        }

        $stmt = $pdo->prepare("UPDATE inquiries SET status = :status WHERE id = :id");
        $stmt->execute([':status' => $newStatus, ':id' => $id]);

        echo json_encode(['success' => true, 'message' => "Statut mis à jour : {$newStatus}"]);
        exit;
    }

    // MISE À JOUR DES NOTES INTERNES
    if ($action === 'update_notes') {
        $id = (int)(tg2d_val($_REQ['id'], 0));
        $notes = trim(tg2d_val($_REQ['notes'], ''));

        $stmt = $pdo->prepare("UPDATE inquiries SET notes = :notes WHERE id = :id");
        $stmt->execute([':notes' => $notes, ':id' => $id]);

        echo json_encode(['success' => true, 'message' => 'Notes internes enregistrées.']);
        exit;
    }

    // SUPPRESSION D'UNE DEMANDE
    if ($action === 'delete_inquiry') {
        $id = (int)(tg2d_val($_REQ['id'], 0));
        $stmt = $pdo->prepare("DELETE FROM inquiries WHERE id = :id");
        $stmt->execute([':id' => $id]);

        echo json_encode(['success' => true, 'message' => "Dossier #{$id} supprimé de la base."]);
        exit;
    }

    // AJOUT MANUEL D'UN DOSSIER (ex: appel téléphonique ou rdv physique)
    if ($action === 'add_inquiry') {
        $nom       = trim((isset($_REQ['nom']) ? $_REQ['nom'] : ''));
        $telephone = trim((isset($_REQ['telephone']) ? $_REQ['telephone'] : ''));
        $email     = trim((isset($_REQ['email']) ? $_REQ['email'] : ''));
        $profil    = trim((isset($_REQ['profil']) ? $_REQ['profil'] : 'promoteur'));
        $besoin    = trim((isset($_REQ['besoin']) ? $_REQ['besoin'] : 'tce'));
        $message   = trim(isset($_REQ['message']) ? $_REQ['message'] : 'Saisie manuelle via le tableau de bord');
        $status    = trim((isset($_REQ['status']) ? $_REQ['status'] : 'Nouveau'));
        $notes     = trim(tg2d_val($_REQ['notes'], ''));
        $sendEmail = !empty($_REQ['send_email']);

        if (empty($nom) || empty($telephone)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Nom et téléphone obligatoires.']);
            exit;
        }

        $stmt = $pdo->prepare("INSERT INTO inquiries 
            (nom, telephone, email, profil, besoin, message, status, notes, email_sent, email_error, ip_address, user_agent) 
            VALUES (:nom, :telephone, :email, :profil, :besoin, :message, :status, :notes, 0, '', 'ADMIN', 'TG2D Dashboard')");

        $stmt->execute([
            ':nom'       => $nom,
            ':telephone' => $telephone,
            ':email'     => $email,
            ':profil'    => $profil,
            ':besoin'    => $besoin,
            ':message'   => $message,
            ':status'    => $status,
            ':notes'     => $notes
        ]);

        $newId = $pdo->lastInsertId();

        if ($sendEmail) {
            $inquiryRecord = [
                'id' => $newId, 'nom' => $nom, 'telephone' => $telephone,
                'email' => $email, 'profil' => $profil, 'besoin' => $besoin,
                'message' => $message, 'ip_address' => 'ADMIN'
            ];
            $mailRes = tg2d_send_inquiry_notification($inquiryRecord);
            $pdo->prepare("UPDATE inquiries SET email_sent = :s, email_error = :e WHERE id = :id")
                ->execute([':s' => $mailRes['success'] ? 1 : 0, ':e' => (isset($mailRes['error']) ? $mailRes['error'] : ''), ':id' => $newId]);
        }

        echo json_encode(['success' => true, 'id' => $newId, 'message' => 'Dossier créé avec succès.']);
        exit;
    }

    // TEST D'EXPÉDITION D'EMAIL
    if ($action === 'test_email') {
        $targetEmail = trim(!empty($_REQ['email']) ? $_REQ['email'] : (!empty($config['mail']['notify_emails'][0]) ? $config['mail']['notify_emails'][0] : 'tg2d.maroc@gmail.com'));
        $res = tg2d_send_test_email($targetEmail);

        if ($res['success']) {
            echo json_encode([
                'success' => true,
                'message' => "Email de test envoyé avec succès à {$targetEmail}. Vérifiez votre boîte de réception (et vos spams)."
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'error'   => "Échec de l'envoi : " . (!empty($res['error']) ? $res['error'] : 'Vérifiez la configuration du serveur mail.')
            ]);
        }
        exit;
    }

    // EXPORT CSV POUR EXCEL
    if ($action === 'export_csv') {
        $stmt = $pdo->query("SELECT id, created_at, nom, telephone, email, profil, besoin, message, status, notes, email_sent FROM inquiries ORDER BY created_at DESC");
        $rows = $stmt->fetchAll();

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="demandes_tg2d_' . date('Y-m-d_His') . '.csv"');

        $output = fopen('php://output', 'w');
        // UTF-8 BOM pour bon affichage des accents dans Excel Windows/Mac
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        fputcsv($output, ['ID', 'Date & Heure', 'Nom & Organisme', 'Téléphone', 'Email', 'Profil Client', 'Nature du Projet', 'Détails / Message', 'Statut', 'Notes Internes', 'Notification Email Envoyée'], ';', '"', "\\");

        foreach ($rows as $row) {
            fputcsv($output, [
                $row['id'],
                $row['created_at'],
                $row['nom'],
                $row['telephone'],
                $row['email'],
                tg2d_format_label($row['profil'], 'profil'),
                tg2d_format_label($row['besoin'], 'besoin'),
                $row['message'],
                $row['status'],
                $row['notes'],
                $row['email_sent'] ? 'Oui' : 'Non'
            ], ';', '"', "\\");
        }

        fclose($output);
        exit;
    }

    // INFORMATIONS SUR LE SYSTÈME ET LA BASE DE DONNÉES
    if ($action === 'get_system_info') {
        $driver = $config['db']['driver'];
        $dbSize = 'N/A';
        if ($driver === 'sqlite') {
            $sqliteFile = $config['db']['sqlite_path'];
            if (file_exists($sqliteFile)) {
                $bytes = filesize($sqliteFile);
                $dbSize = round($bytes / 1024, 2) . ' KB';
            }
        }

        echo json_encode([
            'success'       => true,
            'driver'        => strtoupper($driver),
            'db_size'       => $dbSize,
            'php_version'   => phpversion(),
            'server_time'   => date('Y-m-d H:i:s'),
            'notify_emails' => $config['mail']['notify_emails'],
            'transport'     => $config['mail']['transport']
        ]);
        exit;
    }

    // GESTION DES DEVIS
    if ($action === 'get_devis') {
        $search = trim((isset($_GET['search']) ? $_GET['search'] : (isset($_REQ['search']) ? $_REQ['search'] : '')));
        $status = trim((isset($_GET['status']) ? $_GET['status'] : (isset($_REQ['status']) ? $_REQ['status'] : '')));
        $limit  = (int)((isset($_GET['limit']) ? $_GET['limit'] : (isset($_REQ['limit']) ? $_REQ['limit'] : 50)));
        $offset = (int)((isset($_GET['offset']) ? $_GET['offset'] : (isset($_REQ['offset']) ? $_REQ['offset'] : 0)));

        $params = [];
        $where = [];

        if ($search !== '') {
            $where[] = "(reference LIKE ? OR client_nom LIKE ? OR client_societe LIKE ? OR projet_titre LIKE ? OR type_travaux LIKE ?)";
            $term = "%{$search}%";
            $params = array_merge($params, [$term, $term, $term, $term, $term]);
        }

        if ($status !== '' && $status !== 'Tous') {
            $where[] = "status = ?";
            $params[] = $status;
        }

        $whereClause = count($where) > 0 ? "WHERE " . implode(' AND ', $where) : "";

        // Total count
        $countSql = "SELECT COUNT(*) AS total FROM devis {$whereClause}";
        $countStmt = $pdo->prepare($countSql);
        $countStmt->execute($params);
        $total = (int)$countStmt->fetch()['total'];

        // Rows
        $sql = "SELECT * FROM devis {$whereClause} ORDER BY created_at DESC LIMIT {$limit} OFFSET {$offset}";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll();

        echo json_encode([
            'success' => true,
            'total'   => $total,
            'devis'   => $rows
        ]);
        exit;
    }

    if ($action === 'create_devis') {
        $clientNom     = trim(tg2d_val($_REQ['client_nom'], ''));
        $clientSociete = trim(tg2d_val($_REQ['client_societe'], ''));
        $clientEmail   = trim(tg2d_val($_REQ['client_email'], ''));
        $clientTel     = trim(tg2d_val($_REQ['client_tel'], ''));
        $projetTitre   = trim(tg2d_val($_REQ['projet_titre'], ''));
        $typeTravaux   = trim(tg2d_val($_REQ['type_travaux'], ''));
        $localisation  = trim(tg2d_val($_REQ['localisation'], ''));
        $montantHt     = (float)(tg2d_val($_REQ['montant_ht'], 0));
        $tvaPct        = (float)(tg2d_val($_REQ['tva_pct'], 20));
        $montantTtc    = $montantHt * (1 + ($tvaPct / 100));
        $status        = trim(tg2d_val($_REQ['status'], 'Brouillon'));
        $validiteDate  = trim((isset($_REQ['validite_date']) ? $_REQ['validite_date'] : date('Y-m-d', strtotime('+30 days'))));
        $notes         = trim(tg2d_val($_REQ['notes'], ''));

        if ($clientNom === '') {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Le nom du client est obligatoire.']);
            exit;
        }

        // Generate unique reference DEV-YYYY-XXXX
        $year = date('Y');
        $countStmt = $pdo->query("SELECT COUNT(*) FROM devis");
        $nextNum = str_pad((int)$countStmt->fetchColumn() + 1, 3, '0', STR_PAD_LEFT);
        $reference = "DEV-{$year}-{$nextNum}";

        $sql = "INSERT INTO devis (reference, client_nom, client_email, client_tel, client_societe, projet_titre, type_travaux, localisation, montant_ht, tva_pct, montant_ttc, status, validite_date, notes)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $reference, $clientNom, $clientEmail, $clientTel, $clientSociete,
            $projetTitre, $typeTravaux, $localisation, $montantHt, $tvaPct, $montantTtc,
            $status, $validiteDate, $notes
        ]);

        $newId = (int)$pdo->lastInsertId();

        echo json_encode([
            'success'   => true,
            'message'   => "Devis {$reference} créé avec succès.",
            'devis_id'  => $newId,
            'reference' => $reference
        ]);
        exit;
    }

    if ($action === 'delete_devis') {
        $id = (int)(tg2d_val($_REQ['id'], 0));
        if ($id <= 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Identifiant invalide.']);
            exit;
        }

        // Get devis details before deletion to clean linked inquiries
        $refStmt = $pdo->prepare("SELECT reference, client_email, client_tel FROM devis WHERE id = ?");
        $refStmt->execute([$id]);
        $row = $refStmt->fetch();

        $stmt = $pdo->prepare("DELETE FROM devis WHERE id = ?");
        $stmt->execute([$id]);

        // Clean any inquiry matching the reference number (e.g. DEV-2026-001 -> inquiry id 1)
        if ($row && !empty($row['reference']) && preg_match('/DEV-\d{4}-(\d+)/', $row['reference'], $matches)) {
            $inqId = (int)$matches[1];
            if ($inqId > 0) {
                $delInq = $pdo->prepare("DELETE FROM inquiries WHERE id = ?");
                $delInq->execute([$inqId]);
            }
        }

        echo json_encode(['success' => true, 'message' => "Devis supprimé définitivement."]);
        exit;
    }

    if ($action === 'update_devis_status') {
        $id = (int)(tg2d_val($_REQ['id'], 0));
        $status = trim(tg2d_val($_REQ['status'], ''));
        if ($id <= 0 || $status === '') {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Paramètres invalides.']);
            exit;
        }
        $stmt = $pdo->prepare("UPDATE devis SET status = ? WHERE id = ?");
        $stmt->execute([$status, $id]);
        echo json_encode(['success' => true, 'message' => "Statut mis à jour."]);
        exit;
    }

    if ($action === 'update_devis') {
        $id            = (int)(tg2d_val($_REQ['id'], 0));
        $clientNom     = trim(tg2d_val($_REQ['client_nom'], ''));
        $clientSociete = trim(tg2d_val($_REQ['client_societe'], ''));
        $clientEmail   = trim(tg2d_val($_REQ['client_email'], ''));
        $clientTel     = trim(tg2d_val($_REQ['client_tel'], ''));
        $projetTitre   = trim(tg2d_val($_REQ['projet_titre'], ''));
        $typeTravaux   = trim(tg2d_val($_REQ['type_travaux'], ''));
        $localisation  = trim(tg2d_val($_REQ['localisation'], ''));
        $montantHt     = (float)(tg2d_val($_REQ['montant_ht'], 0));
        $tvaPct        = (float)(tg2d_val($_REQ['tva_pct'], 20));
        $montantTtc    = $montantHt * (1 + ($tvaPct / 100));
        $status        = trim(tg2d_val($_REQ['status'], 'Brouillon'));
        $validiteDate  = trim((isset($_REQ['validite_date']) ? $_REQ['validite_date'] : date('Y-m-d', strtotime('+30 days'))));
        $notes         = trim(tg2d_val($_REQ['notes'], ''));

        if ($id <= 0 || $clientNom === '') {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Identifiant et nom client obligatoires.']);
            exit;
        }

        $sql = "UPDATE devis SET 
                client_nom = ?, client_email = ?, client_tel = ?, client_societe = ?,
                projet_titre = ?, type_travaux = ?, localisation = ?, 
                montant_ht = ?, tva_pct = ?, montant_ttc = ?, status = ?, 
                validite_date = ?, notes = ?
                WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $clientNom, $clientEmail, $clientTel, $clientSociete,
            $projetTitre, $typeTravaux, $localisation,
            $montantHt, $tvaPct, $montantTtc, $status,
            $validiteDate, $notes, $id
        ]);

        echo json_encode(['success' => true, 'message' => "Devis mis à jour avec succès."]);
        exit;
    }

    if ($action === 'export_devis_csv') {
        $stmt = $pdo->query("SELECT id, reference, created_at, client_nom, client_societe, client_tel, client_email, projet_titre, type_travaux, localisation, montant_ht, tva_pct, montant_ttc, status, validite_date, notes FROM devis ORDER BY created_at DESC");
        $rows = $stmt->fetchAll();

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="devis_tg2d_' . date('Y-m-d_His') . '.csv"');

        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        fputcsv($output, ['ID', 'Référence', 'Date Création', 'Client', 'Société', 'Téléphone', 'Email', 'Projet', 'Corps d\'état', 'Localisation', 'Montant HT (MAD)', 'TVA (%)', 'Montant TTC (MAD)', 'Statut', 'Date Validité', 'Notes & Détails'], ';', '"', "\\");

        foreach ($rows as $r) {
            fputcsv($output, [
                $r['id'],
                $r['reference'],
                $r['created_at'],
                $r['client_nom'],
                $r['client_societe'],
                $r['client_tel'],
                $r['client_email'],
                $r['projet_titre'],
                $r['type_travaux'],
                $r['localisation'],
                $r['montant_ht'],
                $r['tva_pct'],
                $r['montant_ttc'],
                $r['status'],
                $r['validite_date'],
                $r['notes']
            ], ';', '"', "\\");
        }

        fclose($output);
        exit;
    }

    // PARAMÈTRES ET LOGICIELS
    if ($action === 'get_settings') {
        $stmt = $pdo->query("SELECT key_name, val_content FROM app_settings");
        $settings = [];
        while ($row = $stmt->fetch()) {
            $settings[$row['key_name']] = $row['val_content'];
        }
        echo json_encode([
            'success'  => true,
            'settings' => $settings
        ]);
        exit;
    }

    if ($action === 'save_settings') {
        $links = [
            'link_paperless' => trim(tg2d_val($_REQ['link_paperless'], 'http://localhost:8001')),
            'link_gce'       => trim(tg2d_val($_REQ['link_gce'], 'https://tg2d.ma/gce')),
            'link_easydoc'   => trim(tg2d_val($_REQ['link_easydoc'], 'https://tg2d.ma/easydoc/login.php'))
        ];
        foreach ($links as $k => $v) {
            $check = $pdo->prepare("SELECT COUNT(*) FROM app_settings WHERE key_name = ?");
            $check->execute([$k]);
            if ((int)$check->fetchColumn() > 0) {
                $stmt = $pdo->prepare("UPDATE app_settings SET val_content = ? WHERE key_name = ?");
                $stmt->execute([$v, $k]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO app_settings (key_name, val_content) VALUES (?, ?)");
                $stmt->execute([$k, $v]);
            }
        }
        echo json_encode(['success' => true, 'message' => 'Paramètres logiciels mis à jour.']);
        exit;
    }

    // ACTION INCONNUE
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => "Action '{$action}' inconnue."]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
