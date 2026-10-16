<?php
if (!function_exists("tg2d_val")) {
    function tg2d_val($val, $default = "") {
        return isset($val) && $val !== null ? $val : $default;
    }
}
/**
 * TG2D MAROC — POINT D'ENTRÉE SOUMISSION DEMANDE DE PROJET
 * Reçoit les demandes d'études, les insère en base de données,
 * et déclenche automatiquement l'envoi d'e-mail d'alerte.
 */

header('Content-Type: application/json; charset=UTF-8');

// Permettre les requêtes CORS raisonnables si besoin
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Méthode non autorisée. Utilisez POST.']);
    exit;
}

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/mailer.php';

// Récupérer le contenu POST (JSON ou form-data classique)
$rawInput = file_get_contents('php://input');
$data = json_decode($rawInput, true);

if (!is_array($data) || empty($data)) {
    $data = $_POST;
}

// Extraction et nettoyage des champs
$nom       = trim((isset($data['nom']) ? $data['nom'] : ''));
$telephone = trim((isset($data['telephone']) ? $data['telephone'] : ''));
$email     = trim((isset($data['email']) ? $data['email'] : ''));
$profil    = trim((isset($data['profil']) ? $data['profil'] : 'promoteur'));
$besoin    = trim((isset($data['besoin']) ? $data['besoin'] : 'tce'));
$message   = trim((isset($data['message']) ? $data['message'] : ''));

// Validation
if (empty($nom)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Le champ Nom & Organisme est obligatoire.']);
    exit;
}

if (empty($telephone)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Le numéro de téléphone est obligatoire.']);
    exit;
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Une adresse e-mail valide est requise.']);
    exit;
}

// Métadonnées techniques
$ip = (isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1')));
$userAgent = substr((isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'Unknown'), 0, 450);

try {
    $pdo = tg2d_get_db();

    // Insertion en base de données
    $stmt = $pdo->prepare("INSERT INTO inquiries 
        (nom, telephone, email, profil, besoin, message, status, notes, email_sent, email_error, ip_address, user_agent) 
        VALUES (:nom, :telephone, :email, :profil, :besoin, :message, 'Nouveau', '', 0, '', :ip, :ua)");

    $stmt->execute([
        ':nom'       => $nom,
        ':telephone' => $telephone,
        ':email'     => $email,
        ':profil'    => $profil,
        ':besoin'    => $besoin,
        ':message'   => $message,
        ':ip'        => $ip,
        ':ua'        => $userAgent
    ]);

    $inquiryId = $pdo->lastInsertId();

    $inquiryRecord = [
        'id'         => $inquiryId,
        'nom'        => $nom,
        'telephone'  => $telephone,
        'email'      => $email,
        'profil'     => $profil,
        'besoin'     => $besoin,
        'message'    => $message,
        'ip_address' => $ip
    ];

    // Déclencher immédiatement l'envoi de l'e-mail automatique à l'administrateur
    $mailResult = tg2d_send_inquiry_notification($inquiryRecord);
    $emailSent = $mailResult['success'] ? 1 : 0;
    $emailError = (isset($mailResult['error']) ? $mailResult['error'] : '');

    // Mettre à jour le statut d'envoi dans la base
    $updateStmt = $pdo->prepare("UPDATE inquiries SET email_sent = :sent, email_error = :err WHERE id = :id");
    $updateStmt->execute([
        ':sent' => $emailSent,
        ':err'  => $emailError,
        ':id'   => $inquiryId
    ]);

    // Synchronisation automatique dans la table des devis
    $year = date('Y');
    $reference = "DEV-{$year}-" . str_pad($inquiryId, 3, '0', STR_PAD_LEFT);
    $typeLabel = tg2d_format_label($besoin, 'besoin');
    $profilLabel = tg2d_format_label($profil, 'profil');
    $validiteDate = date('Y-m-d', strtotime('+30 days'));

    try {
        $devisStmt = $pdo->prepare("INSERT INTO devis 
            (reference, client_nom, client_email, client_tel, client_societe, projet_titre, type_travaux, localisation, montant_ht, tva_pct, montant_ttc, status, validite_date, notes)
            VALUES (?, ?, ?, ?, ?, ?, ?, 'Maroc', 0, 20, 0, 'En attente', ?, ?)");
        $devisStmt->execute([
            $reference,
            $nom,
            $email,
            $telephone,
            $profilLabel,
            "Demande en ligne : " . $typeLabel,
            $typeLabel,
            $validiteDate,
            $message
        ]);
    } catch (Exception $eDevis) {
        // En cas d'erreur secondaire sur devis, on logue sans bloquer la demande principale
        error_log("Erreur sync devis: " . $eDevis->getMessage());
    }

    // Envoi de l'accusé de réception au client si activé dans config
    $config = tg2d_get_config();
    if (!empty($config['mail']['send_client_confirmation'])) {
        @tg2d_send_client_confirmation($inquiryRecord);
    }

    echo json_encode([
        'success'    => true,
        'inquiry_id' => $inquiryId,
        'reference'  => $reference,
        'email_sent' => (bool)$emailSent,
        'message'    => 'Votre demande a été enregistrée avec succès. Notre équipe d\'ingénieurs vous contactera sous 24h ouvrées.'
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error'   => 'Une erreur est survenue lors de l\'enregistrement : ' . $e->getMessage()
    ]);
}
