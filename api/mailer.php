<?php
if (!function_exists("tg2d_val")) {
    function tg2d_val($val, $default = "") {
        return isset($val) && $val !== null ? $val : $default;
    }
}
/**
 * TG2D MAROC — SERVICE D'EXPÉDITION D'EMAILS & NOTIFICATIONS
 * Envoi d'alertes HTML premium avec boutons d'actions directs (WhatsApp, Appel, Réponse)
 * Supporte la fonction PHP mail() standard et le protocole SMTP direct.
 */

require_once __DIR__ . '/db.php';

function tg2d_clean_phone_for_whatsapp($phone) {
    $clean = preg_replace('/[^0-9]/', '', $phone);
    // Si commence par 06 ou 07, remplacer par 2126 ou 2127
    if (strpos($clean, '0') === 0 && strlen($clean) === 10) {
        $clean = '212' . substr($clean, 1);
    }
    return $clean;
}

function tg2d_format_label($val, $type = 'besoin') {
    $besoins = [
        'tce' => 'Construction Tous Corps d\'État (TCE)',
        'gros-oeuvre' => 'Gros Œuvre & Structure',
        'terrassement' => 'Terrassement Lourd / Déroctage',
        'flotte' => 'Mobilisation d\'Engins de Flotte',
        'renovation' => 'Rénovation & Réhabilitation',
        'appel-offres' => 'Appel d\'Offres & Consultation Publique'
    ];
    $profils = [
        'promoteur' => 'Promoteur / Investisseur Immobilier',
        'public' => 'Maître d\'Ouvrage Public / Collectivité',
        'bet' => 'Bureau d\'Études / Architecte',
        'industriel' => 'Groupe Industriel & Logistique',
        'autre' => 'Autre Organisme / Particulier'
    ];

    if ($type === 'besoin') {
        return $besoins[$val] ?? htmlspecialchars($val);
    }
    return $profils[$val] ?? htmlspecialchars($val);
}

/**
 * Envoi de la notification automatique au(x) administrateur(s)
 */
function tg2d_send_inquiry_notification(array $inquiry) {
    $config = tg2d_get_config();
    $mailConfig = $config['mail'];
    $company = $config['company'];

    $recipients = $mailConfig['notify_emails'];
    if (empty($recipients)) {
        return ['success' => false, 'error' => "Aucun email administrateur configuré."];
    }

    $id = $inquiry['id'] ?? 'REF-NEW';
    $nom = htmlspecialchars((isset($inquiry['nom']) ? $inquiry['nom'] : ''));
    $tel = htmlspecialchars($inquiry['telephone'] ?? '');
    $cleanPhone = tg2d_clean_phone_for_whatsapp($inquiry['telephone'] ?? '');
    $email = htmlspecialchars($inquiry['email'] ?? '');
    $profil = tg2d_format_label($inquiry['profil'] ?? '', 'profil');
    $besoin = tg2d_format_label((isset($inquiry['besoin']) ? $inquiry['besoin'] : ''), 'besoin');
    $msg = nl2br(htmlspecialchars($inquiry['message'] ?? 'Aucun détail textuel spécifié.'));
    $date = date('d/m/Y à H:i');

    $subject = "🚨 [Nouveau Projet TG2D #{$id}] " . ($inquiry['nom'] ?? 'Client') . " — " . $besoin;

    // Déterminer l'URL du tableau de bord admin
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'] ?? 'tg2d.ma';
    $adminUrl = $protocol . $host . '/admin/';

    $html = '
    <!DOCTYPE html>
    <html lang="fr">
    <head>
      <meta charset="UTF-8">
      <title>Nouvelle demande d\'étude TG2D Maroc</title>
      <style>
        body { margin:0; padding:0; background-color:#060911; font-family:-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif; color:#FFFFFF; }
        .wrapper { width:100%; max-width:680px; margin:0 auto; background-color:#0D1424; border:1px solid rgba(0, 194, 255, 0.25); border-radius:12px; overflow:hidden; }
        .hdr { background:linear-gradient(135deg, #060911 0%, #101E38 100%); padding:32px 28px; border-bottom:1px solid rgba(255,255,255,0.1); text-align:center; }
        .hdr h1 { margin:10px 0 0; font-size:20px; font-weight:700; color:#FFFFFF; letter-spacing:-0.02em; }
        .badge-alert { display:inline-block; background:rgba(0,194,255,0.15); border:1px solid #00C2FF; color:#00C2FF; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; padding:5px 12px; border-radius:20px; }
        .content { padding:28px; }
        .card-box { background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.08); border-radius:8px; padding:20px; margin-bottom:20px; }
        .row-item { padding:8px 0; border-bottom:1px solid rgba(255,255,255,0.06); }
        .row-item:last-child { border-bottom:none; }
        .lbl { font-size:11px; text-transform:uppercase; letter-spacing:0.06em; color:rgba(255,255,255,0.5); margin-bottom:4px; }
        .val { font-size:14px; font-weight:600; color:#FFFFFF; }
        .highlight { color:#00C2FF; }
        .msg-box { background:#060911; border-left:3px solid #00C2FF; border-radius:4px; padding:16px; font-size:13px; line-height:1.6; color:rgba(255,255,255,0.9); margin-top:10px; }
        .actions-wrap { text-align:center; padding:10px 0 20px; }
        .btn-wa { display:inline-block; background-color:#25D366; color:#FFFFFF; font-weight:700; font-size:13px; text-decoration:none; padding:12px 22px; border-radius:6px; margin:6px 4px; }
        .btn-call { display:inline-block; background-color:#00C2FF; color:#060911; font-weight:700; font-size:13px; text-decoration:none; padding:12px 22px; border-radius:6px; margin:6px 4px; }
        .btn-admin { display:inline-block; background-color:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.2); color:#FFFFFF; font-weight:600; font-size:13px; text-decoration:none; padding:12px 22px; border-radius:6px; margin:6px 4px; }
        .footer { background-color:#060911; padding:20px; text-align:center; font-size:11px; color:rgba(255,255,255,0.4); border-top:1px solid rgba(255,255,255,0.06); }
      </style>
    </head>
    <body>
      <div style="padding:24px 10px;">
        <div class="wrapper">
          <div class="hdr">
            <span class="badge-alert">Notification Automatique — Base de Données TG2D</span>
            <h1>Nouvelle Demande d\'Étude Technique</h1>
            <p style="margin:6px 0 0; font-size:12px; color:rgba(255,255,255,0.6);">Reçue le ' . $date . ' (Réf: #' . $id . ')</p>
          </div>
          
          <div class="content">
            <!-- Client Info -->
            <div class="card-box">
              <div class="row-item">
                <div class="lbl">Client / Organisme</div>
                <div class="val highlight" style="font-size:16px;">' . $nom . '</div>
              </div>
              <div class="row-item">
                <div class="lbl">Numéro de Téléphone</div>
                <div class="val"><a href="tel:' . $tel . '" style="color:#00C2FF; text-decoration:none;">' . $tel . '</a></div>
              </div>
              <div class="row-item">
                <div class="lbl">Adresse Email</div>
                <div class="val"><a href="mailto:' . $email . '" style="color:#00C2FF; text-decoration:none;">' . $email . '</a></div>
              </div>
              <div class="row-item">
                <div class="lbl">Profil du Maître d\'Ouvrage</div>
                <div class="val">' . $profil . '</div>
              </div>
              <div class="row-item">
                <div class="lbl">Nature du Projet Souhaité</div>
                <div class="val" style="color:#FFB800;">' . $besoin . '</div>
              </div>
            </div>

            <!-- Project Details -->
            <div class="card-box">
              <div class="lbl">Description du Projet & Contraintes Techniques</div>
              <div class="msg-box">' . $msg . '</div>
            </div>

            <!-- Quick Action Buttons -->
            <div class="actions-wrap">
              ' . (!empty($cleanPhone) ? '<a href="https://wa.me/' . $cleanPhone . '?text=Bonjour%20' . urlencode((isset($inquiry['nom']) ? $inquiry['nom'] : '')) . ',%20suite%20%C3%A0%20votre%20demande%20d%27%C3%A9tude%20sur%20TG2D%20Maroc..." target="_blank" class="btn-wa">💬 Contacter sur WhatsApp</a>' : '') . '
              ' . (!empty($tel) ? '<a href="tel:' . $tel . '" class="btn-call">📞 Appeler le Client</a>' : '') . '
              <a href="' . $adminUrl . '" class="btn-admin">📊 Ouvrir le Dashboard Admin</a>
            </div>
          </div>

          <div class="footer">
            <strong>TG2D MAROC</strong> — Entreprise Générale de Bâtiment, Génie Civil & Travaux Publics<br>
            Alerte automatique générée pour l\'équipe de direction technique.<br>
            Adresse IP déclencheur : ' . htmlspecialchars((isset($inquiry['ip_address']) ? $inquiry['ip_address'] : 'N/A')) . '
          </div>
        </div>
      </div>
    </body>
    </html>
    ';

    $to = implode(', ', $recipients);
    $fromName = $mailConfig['from_name'];
    $fromEmail = $mailConfig['from_email'];

    $headers = [];
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-type: text/html; charset=UTF-8';
    $headers[] = "From: {$fromName} <{$fromEmail}>";
    // Reply-To pointe vers le client pour que répondre à l'email réponde directement au demandeur
    if (!empty($inquiry['email'])) {
        $headers[] = "Reply-To: {$nom} <" . $inquiry['email'] . ">";
    }
    $headers[] = 'X-Mailer: PHP/' . phpversion();

    $headersStr = implode("\r\n", $headers);

    // Envoi via mail()
    $sent = @mail($to, $subject, $html, $headersStr);

    if ($sent) {
        return ['success' => true, 'error' => null];
    } else {
        $err = error_get_last();
        return ['success' => false, 'error' => (isset($err['message']) ? $err['message'] : 'Erreur native mail()')];
    }
}

/**
 * Envoi d'un accusé de réception automatique au client demandeur
 */
function tg2d_send_client_confirmation(array $inquiry) {
    $config = tg2d_get_config();
    $mailConfig = $config['mail'];
    $company = $config['company'];

    if (empty($inquiry['email']) || !filter_var($inquiry['email'], FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    $nom = htmlspecialchars((isset($inquiry['nom']) ? $inquiry['nom'] : 'Cher Partenaire'));
    $besoin = tg2d_format_label((isset($inquiry['besoin']) ? $inquiry['besoin'] : ''), 'besoin');
    $to = $inquiry['email'];
    $subject = "Dossier d'Étude Technique Reçu — TG2D Maroc";

    $html = '
    <!DOCTYPE html>
    <html lang="fr">
    <head>
      <meta charset="UTF-8">
      <title>Confirmation de réception TG2D Maroc</title>
      <style>
        body { margin:0; padding:0; background-color:#060911; font-family:-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif; color:#FFFFFF; }
        .wrapper { width:100%; max-width:620px; margin:0 auto; background-color:#0D1424; border:1px solid rgba(255,255,255,0.1); border-radius:10px; overflow:hidden; }
        .hdr { background:#060911; padding:28px 24px; text-align:center; border-bottom:1px solid rgba(0, 194, 255, 0.2); }
        .hdr h1 { margin:8px 0 0; font-size:18px; color:#FFFFFF; }
        .content { padding:28px 24px; font-size:14px; line-height:1.7; color:rgba(255,255,255,0.85); }
        .box { background:rgba(0,194,255,0.06); border:1px solid rgba(0,194,255,0.25); border-radius:6px; padding:16px; margin:20px 0; }
        .footer { background:#060911; padding:20px; text-align:center; font-size:11px; color:rgba(255,255,255,0.4); border-top:1px solid rgba(255,255,255,0.06); }
      </style>
    </head>
    <body>
      <div style="padding:24px 10px;">
        <div class="wrapper">
          <div class="hdr">
            <div style="font-size:12px; color:#00C2FF; font-weight:700; text-transform:uppercase; letter-spacing:0.08em;">TG2D MAROC — DIRECTION TECHNIQUE</div>
            <h1>Votre dossier d\'étude a bien été pris en compte</h1>
          </div>
          <div class="content">
            <p>Bonjour <strong>' . $nom . '</strong>,</p>
            <p>Nous vous remercions pour l\'intérêt que vous portez à <strong>TG2D Maroc</strong> pour votre projet de <strong>' . $besoin . '</strong>.</p>
            
            <div class="box">
              <strong style="color:#00C2FF;">Engagement de nos ingénieurs :</strong><br>
              Votre dossier a été transmis à notre cellule d\'ingénierie et d\'études de prix. Un ingénieur chargé d\'affaires analysera vos éléments et prendra contact avec vous sous <strong>24h à 48h ouvrées</strong>.
            </div>

            <p>Pour toute urgence ou consultation prioritaire, nos équipes restent joignables directement :</p>
            <ul style="padding-left:20px; margin:12px 0;">
              <li>Téléphone direct : <strong>' . $company['phone'] . '</strong></li>
              <li>WhatsApp Direction : <strong><a href="https://wa.me/' . $company['whatsapp'] . '" style="color:#00C2FF;">+212 6 18 39 23 28</a></strong></li>
              <li>Courriel : <strong>' . $company['email'] . '</strong></li>
            </ul>
            <p style="margin-top:24px;">Bien cordialement,<br><strong>L\'Équipe TG2D Maroc</strong></p>
          </div>
          <div class="footer">
            TG2D MAROC — Travaux Généraux de Bâtiment, Génie Civil & Flotte d\'Engins Lourds<br>
            Casablanca, Maroc — <a href="' . $company['website'] . '" style="color:#00C2FF;">tg2d.ma</a>
          </div>
        </div>
      </div>
    </body>
    </html>
    ';

    $fromName = $mailConfig['from_name'];
    $fromEmail = $mailConfig['from_email'];

    $headers = [];
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-type: text/html; charset=UTF-8';
    $headers[] = "From: {$fromName} <{$fromEmail}>";
    $headers[] = "Reply-To: {$company['email']}";
    $headers[] = 'X-Mailer: PHP/' . phpversion();

    return @mail($to, $subject, $html, implode("\r\n", $headers));
}

/**
 * Envoi d'un email de test depuis le Dashboard Admin
 */
function tg2d_send_test_email($targetEmail) {
    $testInquiry = [
        'id' => 'TEST-' . rand(100, 999),
        'nom' => 'Testeur Direction TG2D',
        'telephone' => '+212 6 18 39 23 28',
        'email' => $targetEmail,
        'profil' => 'promoteur',
        'besoin' => 'tce',
        'message' => 'Ceci est un test de diagnostic envoyé depuis le tableau de bord TG2D Admin pour confirmer que les alertes e-mail automatiques fonctionnent correctement.',
        'ip_address' => (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1')
    ];

    $res = tg2d_send_inquiry_notification($testInquiry);
    return $res;
}
