<?php
/**
 * TG2D MAROC — CONFIGURATION SYSTÈME & BASE DE DONNÉES
 * Configuration de l'environnement, de la base de données et du service de notifications email.
 */

// Fuseau horaire du Maroc
date_default_timezone_set('Africa/Casablanca');

return [
    // -------------------------------------------------------------
    // 1. CONFIGURATION DE LA BASE DE DONNÉES
    // -------------------------------------------------------------
    // Types supportés : 'sqlite' (zéro configuration, recommandé pour cPanel/local) ou 'mysql'
    'db' => [
        'driver' => 'sqlite', // 'sqlite' ou 'mysql'
        
        // Configuration SQLite
        'sqlite_path' => __DIR__ . '/../data/inquiries.sqlite',
        
        // Configuration MySQL (optionnel, pour basculer sur une base MySQL cPanel)
        'mysql' => [
            'host'     => '127.0.0.1',
            'port'     => 3306,
            'database' => 'tg2d_inquiries',
            'username' => 'root',
            'password' => '',
            'charset'  => 'utf8mb4'
        ]
    ],

    // -------------------------------------------------------------
    // 2. NOTIFICATIONS EMAIL AUTOMATIQUES
    // -------------------------------------------------------------
    'mail' => [
        // Destinataire principal recevant chaque notification de projet
        'notify_emails' => [
            'tg2d.maroc@gmail.com'
        ],
        
        // Nom de l'expéditeur et adresse d'envoi
        'from_name'  => 'TG2D Maroc — Chantiers & Études',
        'from_email' => 'noreply@tg2d.ma', // ou tg2d.maroc@gmail.com sur serveurs restreints

        // Confirmation automatique envoyée au client qui a fait la demande
        'send_client_confirmation' => true,

        // Mode d'envoi : 'mail' (fonction native PHP mail(), standard cPanel) ou 'smtp'
        'transport' => 'mail',

        // Configuration SMTP optionnelle (si transport = 'smtp')
        'smtp' => [
            'host'       => 'smtp.gmail.com', // ou mail.tg2d.ma
            'port'       => 587,
            'encryption' => 'tls', // 'tls' ou 'ssl'
            'username'   => 'tg2d.maroc@gmail.com',
            'password'   => '' // Mot de passe d'application si SMTP Gmail
        ]
    ],

    // -------------------------------------------------------------
    // 3. SÉCURITÉ & TABLEAU DE BORD ADMIN
    // -------------------------------------------------------------
    'admin' => [
        'username' => 'admin',
        // Mot de passe initial (peut être modifié ici à tout moment)
        'password' => 'tg2d2026',
        
        // Clé secrète pour signature des sessions
        'session_secret' => 'tg2d_maroc_secret_key_luxury_btp_2026',
        'session_lifetime' => 86400 * 7 // 7 jours
    ],

    // -------------------------------------------------------------
    // 4. MÉTADONNÉES DE L'ENTREPRISE
    // -------------------------------------------------------------
    'company' => [
        'name'    => 'TG2D MAROC',
        'tagline' => 'Entreprise Générale de Bâtiment, Génie Civil & Travaux Publics',
        'phone'   => '+212 6 18 39 23 28',
        'whatsapp'=> '212618392328',
        'email'   => 'tg2d.maroc@gmail.com',
        'website' => 'https://tg2d.ma'
    ]
];
