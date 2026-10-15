<?php
if (!function_exists("tg2d_val")) {
    function tg2d_val($val, $default = "") {
        return isset($val) && $val !== null ? $val : $default;
    }
}
/**
 * TG2D MAROC — COUCHE D'ACCÈS BASE DE DONNÉES (PDO)
 * Gestion unifiée SQLite / MySQL avec création automatique des tables et index.
 */

function tg2d_get_config() {
    static $config = null;
    if ($config === null) {
        $configFile = __DIR__ . '/config.php';
        if (file_exists($configFile)) {
            $config = require $configFile;
        } else {
            throw new Exception("Le fichier de configuration api/config.php est introuvable.");
        }
    }
    return $config;
}

function tg2d_get_db() {
    static $pdo = null;
    if ($pdo !== null) {
        return $pdo;
    }

    $config = tg2d_get_config();
    $dbConfig = $config['db'];

    if ($dbConfig['driver'] === 'sqlite') {
        $sqlitePath = $dbConfig['sqlite_path'];
        $sqliteDir = dirname($sqlitePath);

        if (!is_dir($sqliteDir)) {
            @mkdir($sqliteDir, 0755, true);
        }

        $dsn = 'sqlite:' . $sqlitePath;
        try {
            $pdo = new PDO($dsn, null, null, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_TIMEOUT => 5
            ]);
            // Enable WAL mode for high concurrency
            $pdo->exec("PRAGMA journal_mode = WAL;");
            $pdo->exec("PRAGMA synchronous = NORMAL;");
        } catch (PDOException $e) {
            error_log("TG2D SQLite Error: " . $e->getMessage());
            throw new Exception("Impossible d'initialiser la base de données SQLite : " . $e->getMessage());
        }

        // Création automatique des tables inquiries, devis et app_settings si inexistantes
        $schema = "CREATE TABLE IF NOT EXISTS inquiries (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            nom TEXT NOT NULL,
            telephone TEXT NOT NULL,
            email TEXT NOT NULL,
            profil TEXT,
            besoin TEXT,
            message TEXT,
            status TEXT DEFAULT 'Nouveau',
            notes TEXT DEFAULT '',
            email_sent INTEGER DEFAULT 0,
            email_error TEXT DEFAULT '',
            ip_address TEXT,
            user_agent TEXT
        );
        CREATE INDEX IF NOT EXISTS idx_inquiries_created_at ON inquiries(created_at DESC);
        CREATE INDEX IF NOT EXISTS idx_inquiries_status ON inquiries(status);

        CREATE TABLE IF NOT EXISTS devis (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            reference TEXT UNIQUE,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            client_nom TEXT NOT NULL,
            client_email TEXT DEFAULT '',
            client_tel TEXT DEFAULT '',
            client_societe TEXT DEFAULT '',
            projet_titre TEXT DEFAULT '',
            type_travaux TEXT DEFAULT '',
            localisation TEXT DEFAULT '',
            montant_ht REAL DEFAULT 0,
            tva_pct REAL DEFAULT 20,
            montant_ttc REAL DEFAULT 0,
            status TEXT DEFAULT 'Brouillon',
            validite_date TEXT DEFAULT '',
            notes TEXT DEFAULT ''
        );
        CREATE INDEX IF NOT EXISTS idx_devis_created_at ON devis(created_at DESC);
        CREATE INDEX IF NOT EXISTS idx_devis_status ON devis(status);

        CREATE TABLE IF NOT EXISTS app_settings (
            key_name TEXT PRIMARY KEY,
            val_content TEXT
        );";

        $pdo->exec($schema);

        // Valeurs par défaut pour les logiciels si non configurés
        $initSettings = [
            'link_paperless' => 'http://localhost:8001',
            'link_gce'       => 'https://tg2d.ma',
            'link_easydoc'   => 'https://tg2d.ma'
        ];
        foreach ($initSettings as $k => $v) {
            $check = $pdo->prepare("SELECT COUNT(*) FROM app_settings WHERE key_name = ?");
            $check->execute([$k]);
            if ((int)$check->fetchColumn() === 0) {
                $ins = $pdo->prepare("INSERT INTO app_settings (key_name, val_content) VALUES (?, ?)");
                $ins->execute([$k, $v]);
            }
        }
    } else {
        // MySQL
        $mysql = $dbConfig['mysql'];
        $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=%s',
            $mysql['host'],
            $mysql['port'],
            $mysql['database'],
            $mysql['charset']
        );

        try {
            $pdo = new PDO($dsn, $mysql['username'], $mysql['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            error_log("TG2D MySQL Error: " . $e->getMessage());
            throw new Exception("Connexion à la base de données MySQL échouée : " . $e->getMessage());
        }

        // Création automatique de la table inquiries MySQL
        $schema = "CREATE TABLE IF NOT EXISTS inquiries (
            id INT AUTO_INCREMENT PRIMARY KEY,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            nom VARCHAR(255) NOT NULL,
            telephone VARCHAR(100) NOT NULL,
            email VARCHAR(255) NOT NULL,
            profil VARCHAR(100) NULL,
            besoin VARCHAR(100) NULL,
            message TEXT NULL,
            status VARCHAR(50) DEFAULT 'Nouveau',
            notes TEXT NULL,
            email_sent TINYINT(1) DEFAULT 0,
            email_error TEXT NULL,
            ip_address VARCHAR(50) NULL,
            user_agent VARCHAR(500) NULL,
            INDEX idx_created (created_at DESC),
            INDEX idx_status (status)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

        $pdo->exec($schema);
    }

    return $pdo;
}
