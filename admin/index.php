<?php
/**
 * TG2D MAROC — TABLEAU DE BORD ADMINISTRATEUR
 * Direction Générale, Ingénierie & Gestion Commerciale
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tableau de Bord — TG2D Maroc</title>
  <meta name="robots" content="noindex, nofollow" />

  <!-- Favicon -->
  <link rel="shortcut icon" href="../favicon.ico?v=2" />
  <link rel="icon" type="image/png" sizes="32x32" href="../favicon-32x32.png?v=2" />

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Cinzel:wght@600;700&display=swap" rel="stylesheet" />

  <style>
    :root {
      --sidebar-bg: #09101d;
      --sidebar-border: rgba(255, 255, 255, 0.08);
      --sidebar-item-active: #152238;
      --sidebar-text: #94A3B8;
      --sidebar-text-active: #FFFFFF;
      --content-bg: #F8FAFC;
      --text-dark: #0F172A;
      --text-muted: #64748B;
      --border-light: #E2E8F0;
      --c-blue: #0084FF;
      --c-blue-hover: #0070E0;
      --c-cyan: #00C2FF;
      --font-main: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      background-color: var(--content-bg);
      color: var(--text-dark);
      font-family: var(--font-main);
      min-height: 100vh;
      -webkit-font-smoothing: antialiased;
      overflow-x: hidden;
    }

    /* Auth Screen Modal / Overlay */
    #authOverlay {
      position: fixed;
      inset: 0;
      background: #060A14;
      z-index: 9999;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 24px;
    }
    .auth-box {
      width: 100%;
      max-width: 420px;
      background: #0E1626;
      border: 1px solid rgba(0, 194, 255, 0.25);
      border-radius: 16px;
      padding: 40px;
      box-shadow: 0 25px 60px rgba(0,0,0,0.6);
      text-align: center;
    }
    .auth-logo {
      height: 48px;
      margin-bottom: 24px;
    }
    .auth-title {
      font-size: 1.4rem;
      font-weight: 700;
      color: #FFFFFF;
      margin-bottom: 8px;
    }
    .auth-subtitle {
      font-size: 0.88rem;
      color: #94A3B8;
      margin-bottom: 28px;
    }
    .auth-field {
      text-align: left;
      margin-bottom: 18px;
    }
    .auth-field label {
      display: block;
      font-size: 0.78rem;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      color: #94A3B8;
      margin-bottom: 8px;
      font-weight: 600;
    }
    .auth-field input {
      width: 100%;
      background: #080D18;
      border: 1px solid rgba(255, 255, 255, 0.12);
      border-radius: 8px;
      padding: 12px 16px;
      color: #FFFFFF;
      font-size: 0.95rem;
      outline: none;
      transition: all 0.2s;
    }
    .auth-field input:focus {
      border-color: var(--c-cyan);
      box-shadow: 0 0 0 3px rgba(0, 194, 255, 0.15);
    }
    .auth-btn {
      width: 100%;
      background: linear-gradient(135deg, #0084FF 0%, #0060DF 100%);
      color: #FFFFFF;
      border: none;
      border-radius: 8px;
      padding: 13px;
      font-size: 0.95rem;
      font-weight: 600;
      cursor: pointer;
      margin-top: 10px;
      transition: all 0.2s;
    }
    .auth-btn:hover {
      background: linear-gradient(135deg, #1A92FF 0%, #0070F3 100%);
      transform: translateY(-1px);
    }
    .auth-error {
      background: rgba(239, 68, 68, 0.15);
      border: 1px solid rgba(239, 68, 68, 0.35);
      color: #FCA5A5;
      padding: 10px;
      border-radius: 8px;
      font-size: 0.85rem;
      margin-bottom: 16px;
      display: none;
    }

    /* Main Dashboard Layout */
    .dashboard-layout {
      display: flex;
      min-height: 100vh;
    }

    /* Left Sidebar */
    .sidebar {
      width: 240px;
      background: var(--sidebar-bg);
      border-right: 1px solid var(--sidebar-border);
      position: fixed;
      top: 0;
      bottom: 0;
      left: 0;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      padding: 24px 16px 20px;
      z-index: 100;
    }
    .sidebar-brand {
      padding: 0 8px 30px;
      display: flex;
      align-items: center;
      gap: 12px;
      text-decoration: none;
    }
    .sidebar-brand img {
      height: 40px;
      width: auto;
      object-fit: contain;
    }
    .sidebar-nav {
      list-style: none;
      display: flex;
      flex-direction: column;
      gap: 6px;
      flex: 1;
    }
    .sidebar-nav-item {
      display: flex;
      align-items: center;
      gap: 14px;
      padding: 12px 14px;
      border-radius: 8px;
      color: var(--sidebar-text);
      font-size: 0.92rem;
      font-weight: 500;
      text-decoration: none;
      cursor: pointer;
      transition: all 0.18s ease;
    }
    .sidebar-nav-item:hover {
      color: #FFFFFF;
      background: rgba(255, 255, 255, 0.04);
    }
    .sidebar-nav-item.active {
      background: var(--sidebar-item-active);
      color: var(--sidebar-text-active);
      font-weight: 600;
    }
    .sidebar-nav-item svg {
      width: 19px;
      height: 19px;
      stroke-width: 2;
      flex-shrink: 0;
    }

    .sidebar-bottom {
      border-top: 1px solid rgba(255, 255, 255, 0.08);
      padding-top: 16px;
    }
    .sidebar-site-link {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 10px 12px;
      border-radius: 8px;
      background: rgba(255, 255, 255, 0.03);
      border: 1px solid rgba(255, 255, 255, 0.06);
      color: #FFFFFF;
      text-decoration: none;
      font-size: 0.85rem;
      transition: all 0.2s;
    }
    .sidebar-site-link:hover {
      background: rgba(255, 255, 255, 0.07);
      border-color: rgba(255, 255, 255, 0.12);
    }
    .sidebar-site-link-left {
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .sidebar-site-link-left .label {
      font-size: 0.82rem;
      font-weight: 600;
      line-height: 1.2;
    }
    .sidebar-site-link-left .domain {
      font-size: 0.74rem;
      color: var(--sidebar-text);
    }

    /* Main Content */
    .main-wrap {
      margin-left: 240px;
      flex: 1;
      padding: 24px 44px 60px;
      max-width: 1440px;
    }

    /* Top Search Bar */
    .top-search-row {
      margin-bottom: 28px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 20px;
    }
    .search-input-wrap {
      position: relative;
      width: 100%;
      max-width: 440px;
    }
    .search-input-wrap svg {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      width: 17px;
      height: 17px;
      color: #94A3B8;
      pointer-events: none;
    }
    .search-input {
      width: 100%;
      background: #FFFFFF;
      border: 1px solid var(--border-light);
      border-radius: 8px;
      padding: 10px 16px 10px 42px;
      font-size: 0.88rem;
      color: var(--text-dark);
      outline: none;
      transition: all 0.2s;
      box-shadow: 0 1px 2px rgba(0,0,0,0.03);
    }
    .search-input:focus {
      border-color: var(--c-blue);
      box-shadow: 0 0 0 3px rgba(0, 132, 255, 0.12);
    }
    .user-pill {
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .logout-btn {
      background: transparent;
      border: 1px solid var(--border-light);
      border-radius: 6px;
      padding: 6px 12px;
      font-size: 0.8rem;
      color: var(--text-muted);
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      transition: all 0.18s;
    }
    .logout-btn:hover {
      color: #EF4444;
      border-color: rgba(239, 68, 68, 0.3);
      background: #FEF2F2;
    }

    /* Hero Banner Area */
    .hero-banner-card {
      background: #FFFFFF;
      border: 1px solid var(--border-light);
      border-radius: 16px;
      position: relative;
      overflow: hidden;
      display: flex;
      align-items: center;
      justify-content: space-between;
      box-shadow: 0 1px 3px rgba(0,0,0,0.02);
      min-height: 200px;
    }
    .hero-banner-content {
      padding: 36px 40px;
      position: relative;
      z-index: 2;
      max-width: 580px;
    }
    .hero-eyebrow {
      font-size: 0.78rem;
      font-weight: 700;
      color: var(--text-muted);
      letter-spacing: 0.08em;
      text-transform: uppercase;
      margin-bottom: 8px;
    }
    .hero-title {
      font-size: clamp(2rem, 3.2vw, 2.7rem);
      font-weight: 800;
      color: var(--text-dark);
      letter-spacing: -0.03em;
      line-height: 1.1;
      margin-bottom: 12px;
    }
    .hero-subtitle {
      font-size: 0.95rem;
      color: var(--text-muted);
      line-height: 1.5;
    }

    /* Hero Right Architectural Visual */
    .hero-banner-visual {
      position: absolute;
      right: 0;
      top: 0;
      bottom: 0;
      width: 52%;
      background: url('../assets/images/admin-hero-architecture.jpg') center right / cover no-repeat;
      display: flex;
      align-items: center;
      justify-content: flex-start;
      padding-left: 80px;
    }
    .hero-banner-visual::before {
      content: "";
      position: absolute;
      inset: 0;
      background: linear-gradient(90deg, #FFFFFF 0%, rgba(255, 255, 255, 0.94) 18%, rgba(255, 255, 255, 0.35) 60%, transparent 100%);
      pointer-events: none;
    }
    .hero-quote-block {
      position: relative;
      z-index: 3;
      max-width: 280px;
    }
    .hero-quote-text {
      font-size: 0.88rem;
      font-weight: 700;
      letter-spacing: 0.12em;
      color: #1E293B;
      line-height: 1.6;
      text-transform: uppercase;
    }
    .hero-quote-bar {
      width: 44px;
      height: 3px;
      background: var(--c-blue);
      margin-top: 10px;
      border-radius: 2px;
    }

    /* 4 Quick Access Cards */
    .quick-cards-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 18px;
      margin: 28px 0;
    }
    .quick-card {
      padding: 18px 20px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      gap: 16px;
      text-decoration: none;
      transition: all 0.2s ease;
      cursor: pointer;
      position: relative;
    }
    .quick-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(0,0,0,0.06);
    }

    /* Card Themes */
    .card-devis {
      background: #EFF6FF;
      border: 1px solid #DBEAFE;
    }
    .card-paperless {
      background: #F0FDF4;
      border: 1px solid #DCFCE7;
    }
    .card-gce {
      background: #FEF2F2;
      border: 1px solid #FEE2E2;
    }
    .card-easydoc {
      background: #EFF6FF;
      border: 1px solid #DBEAFE;
    }

    .quick-icon-wrap {
      width: 44px;
      height: 44px;
      border-radius: 10px;
      background: #FFFFFF;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .quick-card-body {
      flex: 1;
      min-width: 0;
    }
    .quick-card-title {
      font-size: 1.05rem;
      font-weight: 700;
      color: var(--text-dark);
      margin-bottom: 2px;
    }
    .quick-card-desc {
      font-size: 0.82rem;
      color: var(--text-muted);
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .quick-card-arrow {
      color: var(--text-dark);
      display: flex;
      align-items: center;
      flex-shrink: 0;
      margin-left: 6px;
    }
    .quick-card-arrow svg {
      width: 17px;
      height: 17px;
    }

    /* Main Section: Derniers Devis */
    .section-header-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 18px;
    }
    .section-title {
      font-size: 1.25rem;
      font-weight: 700;
      color: var(--text-dark);
      letter-spacing: -0.01em;
    }
    .section-link {
      font-size: 0.88rem;
      font-weight: 600;
      color: var(--c-blue);
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      transition: gap 0.2s;
    }
    .section-link:hover {
      gap: 10px;
      text-decoration: underline;
    }

    /* Content Box Container */
    .content-box {
      background: #FFFFFF;
      border: 1px solid var(--border-light);
      border-radius: 16px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.03);
      overflow: hidden;
    }

    /* Empty State View */
    .empty-state-view {
      padding: 64px 24px;
      text-align: center;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }
    .empty-icon-circle {
      width: 72px;
      height: 72px;
      border-radius: 50%;
      background: #F1F5F9;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #64748B;
      margin-bottom: 20px;
    }
    .empty-icon-circle svg {
      width: 32px;
      height: 32px;
    }
    .empty-title {
      font-size: 1.3rem;
      font-weight: 700;
      color: var(--text-dark);
      margin-bottom: 8px;
    }
    .empty-desc {
      font-size: 0.92rem;
      color: var(--text-muted);
      margin-bottom: 24px;
      max-width: 400px;
    }
    .btn-create-devis {
      background: var(--c-blue);
      color: #FFFFFF;
      border: none;
      border-radius: 8px;
      padding: 12px 24px;
      font-size: 0.92rem;
      font-weight: 600;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      transition: all 0.2s;
      box-shadow: 0 2px 6px rgba(0, 132, 255, 0.25);
    }
    .btn-create-devis:hover {
      background: var(--c-blue-hover);
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(0, 132, 255, 0.35);
    }

    /* Table View when devis exist */
    .devis-table-wrap {
      width: 100%;
      overflow-x: auto;
    }
    .devis-table {
      width: 100%;
      border-collapse: collapse;
      text-align: left;
      font-size: 0.9rem;
    }
    .devis-table th {
      background: #F8FAFC;
      padding: 14px 20px;
      font-size: 0.78rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      color: #64748B;
      border-bottom: 1px solid var(--border-light);
    }
    .devis-table td {
      padding: 16px 20px;
      border-bottom: 1px solid var(--border-light);
      color: var(--text-dark);
      vertical-align: middle;
    }
    .devis-table tr:hover td {
      background: #F8FAFC;
    }
    .status-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 4px 10px;
      border-radius: 6px;
      font-size: 0.76rem;
      font-weight: 700;
    }
    .badge-brouillon { background: #F1F5F9; color: #475569; }
    .badge-transmis { background: #EFF6FF; color: #1D4ED8; }
    .badge-valide { background: #ECFDF5; color: #047857; }
    .badge-refuse { background: #FEF2F2; color: #B91C1C; }
    .badge-attente { background: #FEF3C7; color: #B45309; }

    /* Modal Styling */
    .modal-overlay {
      position: fixed;
      inset: 0;
      background: rgba(15, 23, 42, 0.6);
      backdrop-filter: blur(4px);
      z-index: 2000;
      display: none;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }
    .modal-card {
      background: #FFFFFF;
      border-radius: 16px;
      width: 100%;
      max-width: 640px;
      max-height: 90vh;
      overflow-y: auto;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
      border: 1px solid var(--border-light);
      padding: 32px;
      position: relative;
    }
    .modal-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 24px;
      border-bottom: 1px solid var(--border-light);
      padding-bottom: 16px;
    }
    .modal-title {
      font-size: 1.3rem;
      font-weight: 700;
      color: var(--text-dark);
    }
    .modal-close {
      background: transparent;
      border: none;
      font-size: 1.5rem;
      color: #94A3B8;
      cursor: pointer;
    }
    .modal-close:hover { color: var(--text-dark); }
    .form-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 16px;
      margin-bottom: 20px;
    }
    .form-col-full { grid-column: span 2; }
    .form-group label {
      display: block;
      font-size: 0.8rem;
      font-weight: 600;
      color: #475569;
      margin-bottom: 6px;
    }
    .form-group input, .form-group select, .form-group textarea {
      width: 100%;
      border: 1px solid var(--border-light);
      border-radius: 8px;
      padding: 10px 14px;
      font-size: 0.9rem;
      color: var(--text-dark);
      outline: none;
      transition: border 0.2s;
    }
    .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
      border-color: var(--c-blue);
      box-shadow: 0 0 0 3px rgba(0, 132, 255, 0.12);
    }
    .modal-actions {
      display: flex;
      justify-content: flex-end;
      gap: 12px;
      margin-top: 24px;
      padding-top: 16px;
      border-top: 1px solid var(--border-light);
    }

    /* Views Switcher */
    .tab-view { display: none; }
    .tab-view.active-tab { display: block; }

    /* Responsive adjustments */
    @media (max-width: 1100px) {
      .quick-cards-grid { grid-template-columns: repeat(2, 1fr); }
      .hero-banner-visual { width: 44%; }
    }
    @media (max-width: 820px) {
      .sidebar { width: 72px; padding: 20px 8px; }
      .sidebar-brand span, .sidebar-nav-item span, .sidebar-site-link-left .label, .sidebar-site-link-left .domain { display: none; }
      .sidebar-brand { justify-content: center; padding: 0 0 20px; }
      .sidebar-nav-item { justify-content: center; padding: 12px; }
      .sidebar-site-link { justify-content: center; padding: 12px; }
      .sidebar-site-link svg:last-child { display: none; }
      .main-wrap { margin-left: 72px; padding: 20px; }
      .hero-banner-visual { display: none; }
      .hero-banner-content { max-width: 100%; }
      .quick-cards-grid { grid-template-columns: 1fr; }
    }
  </style>
</head>
<body>

  <!-- ================= AUTH OVERLAY ================= -->
  <div id="authOverlay">
    <div class="auth-box">
      <img src="../assets/images/tg2d-logo-cyan.png" alt="TG2D Maroc" class="auth-logo" />
      <h2 class="auth-title">Accès Tableau de Bord</h2>
      <p class="auth-subtitle">Espace Privé — Direction & Ingénierie TG2D</p>
      
      <div id="authAlert" class="auth-error"></div>

      <form id="authForm" onsubmit="handleLogin(event)">
        <div class="auth-field">
          <label for="authUsername">Identifiant</label>
          <input type="text" id="authUsername" value="admin" required />
        </div>
        <div class="auth-field">
          <label for="authPassword">Mot de passe</label>
          <input type="password" id="authPassword" placeholder="••••••••" required autofocus />
        </div>
        <button type="submit" class="auth-btn" id="authBtn">Se connecter au tableau de bord</button>
      </form>
    </div>
  </div>

  <!-- ================= DASHBOARD LAYOUT ================= -->
  <div class="dashboard-layout" id="mainDashboard" style="display: none;">

    <!-- Left Sidebar -->
    <aside class="sidebar">
      <div>
        <a href="index.php" class="sidebar-brand">
          <img src="../assets/images/tg2d-logo-cyan.png" alt="TG2D Logo" />
        </a>

        <ul class="sidebar-nav">
          <li>
            <a class="sidebar-nav-item active" data-tab="tab-accueil" onclick="switchTab('tab-accueil', this)">
              <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
              <span>Accueil</span>
            </a>
          </li>
          <li>
            <a class="sidebar-nav-item" data-tab="tab-devis" onclick="switchTab('tab-devis', this)">
              <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
              <span>Devis</span>
            </a>
          </li>
          <li>
            <a class="sidebar-nav-item" data-tab="tab-logiciels" onclick="switchTab('tab-logiciels', this)">
              <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
              <span>Logiciels</span>
            </a>
          </li>
          <li>
            <a class="sidebar-nav-item" data-tab="tab-parametres" onclick="switchTab('tab-parametres', this)">
              <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
              <span>Paramètres</span>
            </a>
          </li>
        </ul>
      </div>

      <!-- Bottom Site Link -->
      <div class="sidebar-bottom">
        <a href="https://tg2d.ma" target="_blank" class="sidebar-site-link" title="Ouvrir le site public">
          <div class="sidebar-site-link-left">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
            <div>
              <div class="label">Voir le site</div>
              <div class="domain">tg2d.ma</div>
            </div>
          </div>
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
        </a>
      </div>
    </aside>

    <!-- Main Content Body -->
    <main class="main-wrap">

      <!-- Top Search & Profile Row -->
      <header class="top-search-row">
        <div class="search-input-wrap">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          <input type="text" id="globalSearchInput" class="search-input" placeholder="Rechercher un devis, un client, un document..." oninput="handleSearch(this.value)" />
        </div>

        <div class="user-pill">
          <button class="logout-btn" onclick="handleLogout()" title="Fermer la session">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            <span>Déconnexion</span>
          </button>
        </div>
      </header>

      <!-- ================= TAB: ACCUEIL ================= -->
      <section id="tab-accueil" class="tab-view active-tab">

        <!-- Hero Banner with Architecture Photo & Tagline -->
        <div class="hero-banner-card">
          <div class="hero-banner-content">
            <div class="hero-eyebrow">TABLEAU DE BORD</div>
            <h1 class="hero-title">Bienvenue</h1>
            <p class="hero-subtitle">Accédez rapidement à vos outils et gérez vos devis depuis un seul espace.</p>
          </div>

          <div class="hero-banner-visual">
            <div class="hero-quote-block">
              <div class="hero-quote-text">
                DES FONDATIONS<br />
                SOLIDES POUR<br />
                UN AVENIR MEILLEUR.
              </div>
              <div class="hero-quote-bar"></div>
            </div>
          </div>
        </div>

        <!-- 4 Quick Access Cards -->
        <div class="quick-cards-grid">
          
          <!-- Card 1: Devis -->
          <a class="quick-card card-devis" onclick="switchTab('tab-devis', document.querySelector('[data-tab=\'tab-devis\']'))">
            <div class="quick-icon-wrap" style="color: #0084FF;">
              <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
            </div>
            <div class="quick-card-body">
              <div class="quick-card-title">Devis</div>
              <div class="quick-card-desc">Gérer tous les devis</div>
            </div>
            <div class="quick-card-arrow">
              <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </div>
          </a>

          <!-- Card 2: Paperless (GED) -->
          <a class="quick-card card-paperless" id="linkPaperlessCard" href="http://localhost:8001" target="_blank" rel="noopener">
            <div class="quick-icon-wrap">
              <!-- Official Paperless Polygon Green Logo -->
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path d="M12 2L3 8V16L12 22L21 16V8L12 2Z" fill="#10B981" opacity="0.2"/>
                <path d="M12 2L4 9L12 14L20 9L12 2Z" fill="#059669"/>
                <path d="M4 9V15L12 21V14L4 9Z" fill="#10B981"/>
              </svg>
            </div>
            <div class="quick-card-body">
              <div class="quick-card-title">Paperless</div>
              <div class="quick-card-desc">Accéder à la GED</div>
            </div>
            <div class="quick-card-arrow">
              <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
            </div>
          </a>

          <!-- Card 3: GCE -->
          <a class="quick-card card-gce" id="linkGceCard" href="https://tg2d.ma/gce" target="_blank" rel="noopener">
            <div class="quick-icon-wrap">
              <!-- GCE Red Styled Badge -->
              <div style="width: 24px; height: 24px; border-radius: 5px; background: linear-gradient(135deg, #B91C1C 0%, #7F1D1D 100%); color: #FFFFFF; font-weight: 800; font-size: 11px; display: flex; align-items: center; justify-content: center; font-family: monospace;">
                G
              </div>
            </div>
            <div class="quick-card-body">
              <div class="quick-card-title">GCE</div>
              <div class="quick-card-desc">Ouvrir GCE</div>
            </div>
            <div class="quick-card-arrow">
              <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
            </div>
          </a>

          <!-- Card 4: Easydoc -->
          <a class="quick-card card-easydoc" id="linkEasydocCard" href="https://tg2d.ma/easydoc/login.php" target="_blank" rel="noopener">
            <div class="quick-icon-wrap" style="color: #0084FF;">
              <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><circle cx="10" cy="14" r="2"/><path d="M12 14h3"/></svg>
            </div>
            <div class="quick-card-body">
              <div class="quick-card-title">Easydoc</div>
              <div class="quick-card-desc">Accéder à Easydoc</div>
            </div>
            <div class="quick-card-arrow">
              <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
            </div>
          </a>

        </div>

        <!-- Section: Derniers devis -->
        <div>
          <div class="section-header-row">
            <h2 class="section-title">Derniers devis</h2>
            <a class="section-link" onclick="switchTab('tab-devis', document.querySelector('[data-tab=\'tab-devis\']'))">
              <span>Voir tous les devis</span>
              <span>&rarr;</span>
            </a>
          </div>

          <!-- Main White Container -->
          <div class="content-box" id="latestDevisContainer">
            <!-- Will render Empty State or Table dynamically -->
            <div class="empty-state-view">
              <div class="empty-icon-circle">
                <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
              </div>
              <h3 class="empty-title">Aucun devis pour le moment</h3>
              <p class="empty-desc">Vos devis apparaîtront ici une fois créés.</p>
              <button class="btn-create-devis" onclick="openDevisModal()">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                <span>Créer un nouveau devis</span>
              </button>
            </div>
          </div>
        </div>

      </section>

      <!-- ================= TAB: DEVIS & DEMANDES ================= -->
      <section id="tab-devis" class="tab-view">
        <div class="section-header-row" style="margin-bottom: 24px;">
          <div>
            <h1 style="font-size: 1.8rem; font-weight: 800; color: var(--text-dark); margin-bottom: 4px;">Gestion des Devis</h1>
            <p style="color: var(--text-muted); font-size: 0.9rem;">Consultez, créez et suivez vos devis et chiffrages de projets BTP.</p>
          </div>
          <button class="btn-create-devis" onclick="openDevisModal()">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            <span>Nouveau devis</span>
          </button>
        </div>

        <!-- Filters Strip -->
        <div style="display: flex; gap: 12px; margin-bottom: 20px; flex-wrap: wrap; align-items: center; justify-content: space-between;">
          <div style="display: flex; gap: 8px; flex-wrap: wrap;">
            <button class="status-btn active" onclick="filterDevisStatus('Tous', this)">Tous</button>
            <button class="status-btn" onclick="filterDevisStatus('En attente', this)">En attente</button>
            <button class="status-btn" onclick="filterDevisStatus('Transmis', this)">Transmis</button>
            <button class="status-btn" onclick="filterDevisStatus('Validé', this)">Validés</button>
            <button class="status-btn" onclick="filterDevisStatus('Refusé', this)">Refusés</button>
            <button class="status-btn" onclick="filterDevisStatus('Brouillon', this)">Brouillons</button>
          </div>
          <div style="color: var(--text-muted); font-size: 0.85rem;" id="devisCounterText">0 devis enregistrés</div>
        </div>

        <div class="content-box" id="devisFullContainer">
          <div class="empty-state-view" style="padding: 60px;">
            <div class="empty-icon-circle">
              <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/></svg>
            </div>
            <h3 class="empty-title">Aucun devis trouvé</h3>
            <p class="empty-desc">Commencez par éditer un premier devis chiffré pour vos clients.</p>
            <button class="btn-create-devis" onclick="openDevisModal()">+ Créer un nouveau devis</button>
          </div>
        </div>
      </section>

      <!-- ================= TAB: LOGICIELS ================= -->
      <section id="tab-logiciels" class="tab-view">
        <div style="margin-bottom: 28px;">
          <h1 style="font-size: 1.8rem; font-weight: 800; color: var(--text-dark); margin-bottom: 4px;">Applications & Logiciels Métier</h1>
          <p style="color: var(--text-muted); font-size: 0.9rem;">Accès direct aux logiciels internes de gestion, GED et facturation chantiers.</p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 24px;">
          
          <!-- Paperless Box -->
          <div class="content-box" style="padding: 28px;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
              <div class="quick-icon-wrap" style="width: 52px; height: 52px; background: #F0FDF4; border: 1px solid #DCFCE7;">
                <svg width="30" height="30" viewBox="0 0 24 24" fill="none">
                  <path d="M12 2L4 9L12 14L20 9L12 2Z" fill="#059669"/>
                  <path d="M4 9V15L12 21V14L4 9Z" fill="#10B981"/>
                </svg>
              </div>
              <span style="font-size: 0.76rem; background: #ECFDF5; color: #059669; padding: 4px 10px; border-radius: 20px; font-weight: 700;">SYSTÈME GED</span>
            </div>
            <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 6px;">Paperless-ngx</h3>
            <p style="font-size: 0.88rem; color: var(--text-muted); line-height: 1.5; margin-bottom: 20px;">
              Portail privé de Gestion Électronique des Documents avec indexation OCR intelligente, reconnaissance de texte sur plans, factures et bons de livraison.
            </p>
            <a id="linkPaperlessSuite" href="http://localhost:8001" target="_blank" class="btn-create-devis" style="width: 100%; justify-content: center; text-decoration: none;">
              <span>Ouvrir Paperless-ngx</span>
              <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
            </a>
          </div>

          <!-- GCE Box -->
          <div class="content-box" style="padding: 28px;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
              <div class="quick-icon-wrap" style="width: 52px; height: 52px; background: #FEF2F2; border: 1px solid #FEE2E2;">
                <div style="width: 32px; height: 32px; border-radius: 6px; background: #B91C1C; color: #FFFFFF; font-weight: 800; font-size: 14px; display: flex; align-items: center; justify-content: center;">G</div>
              </div>
              <span style="font-size: 0.76rem; background: #FEF2F2; color: #B91C1C; padding: 4px 10px; border-radius: 20px; font-weight: 700;">GESTION COMMERCIALE</span>
            </div>
            <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 6px;">GCE Entreprise</h3>
            <p style="font-size: 0.88rem; color: var(--text-muted); line-height: 1.5; margin-bottom: 20px;">
              Module de gestion commerciale chantiers, suivi des règlements fournisseurs BTP, facturation d'avancement et décomptes provisoires.
            </p>
            <a id="linkGceSuite" href="https://tg2d.ma/gce" target="_blank" class="btn-create-devis" style="width: 100%; justify-content: center; text-decoration: none; background: #B91C1C;">
              <span>Ouvrir GCE</span>
              <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
            </a>
          </div>

          <!-- Easydoc Box -->
          <div class="content-box" style="padding: 28px;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
              <div class="quick-icon-wrap" style="width: 52px; height: 52px; background: #EFF6FF; border: 1px solid #DBEAFE; color: #0084FF;">
                <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><circle cx="10" cy="14" r="2"/></svg>
              </div>
              <span style="font-size: 0.76rem; background: #EFF6FF; color: #0084FF; padding: 4px 10px; border-radius: 20px; font-weight: 700;">ARCHIVAGE TECHNIQUE</span>
            </div>
            <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 6px;">Easydoc</h3>
            <p style="font-size: 0.88rem; color: var(--text-muted); line-height: 1.5; margin-bottom: 20px;">
              Référentiel des documents techniques, fiches de sécurité chantiers, habilitations des équipes et attestations de conformité LPEE.
            </p>
            <a id="linkEasydocSuite" href="https://tg2d.ma/easydoc/login.php" target="_blank" class="btn-create-devis" style="width: 100%; justify-content: center; text-decoration: none;">
              <span>Accéder à Easydoc</span>
              <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
            </a>
          </div>

        </div>
      </section>

      <!-- ================= TAB: PARAMÈTRES ================= -->
      <section id="tab-parametres" class="tab-view">
        <div style="margin-bottom: 28px;">
          <h1 style="font-size: 1.8rem; font-weight: 800; color: var(--text-dark); margin-bottom: 4px;">Paramètres du Système</h1>
          <p style="color: var(--text-muted); font-size: 0.9rem;">Configurez les liens d'accès aux logiciels et les réglages de votre tableau de bord.</p>
        </div>

        <div style="max-width: 680px;" class="content-box">
          <form id="settingsForm" onsubmit="saveSoftwareSettings(event)" style="padding: 32px;">
            <div id="settingsAlert" style="display: none; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 0.88rem;"></div>

            <h3 style="font-size: 1.15rem; font-weight: 700; margin-bottom: 16px; border-bottom: 1px solid var(--border-light); padding-bottom: 8px;">
              Liens des Cartes Logiciels
            </h3>

            <div class="form-group" style="margin-bottom: 16px;">
              <label for="cfg_paperless">URL de Paperless (GED)</label>
              <input type="text" id="cfg_paperless" placeholder="http://localhost:8001 ou https://ged.tg2d.ma" required />
            </div>

            <div class="form-group" style="margin-bottom: 16px;">
              <label for="cfg_gce">URL de GCE</label>
              <input type="text" id="cfg_gce" placeholder="https://..." required />
            </div>

            <div class="form-group" style="margin-bottom: 24px;">
              <label for="cfg_easydoc">URL de Easydoc</label>
              <input type="text" id="cfg_easydoc" placeholder="https://..." required />
            </div>

            <button type="submit" class="btn-create-devis">
              <span>Enregistrer les liens</span>
            </button>
          </form>
        </div>
      </section>

    </main>
  </div>

  <!-- ================= MODAL: CRÉER UN NOUVEAU DEVIS ================= -->
  <div class="modal-overlay" id="devisModal">
    <div class="modal-card">
      <div class="modal-header">
        <h3 class="modal-title">Créer un nouveau devis</h3>
        <button class="modal-close" onclick="closeDevisModal()">&times;</button>
      </div>

      <div id="devisModalAlert" style="display: none; padding: 12px; border-radius: 8px; margin-bottom: 16px; font-size: 0.88rem;"></div>

      <form id="createDevisForm" onsubmit="handleCreateDevis(event)">
        <div class="form-grid">
          
          <div class="form-group form-col-full">
            <label for="dev_nom">Nom complet du client *</label>
            <input type="text" id="dev_nom" placeholder="Ex: M. Mohammed Alami" required />
          </div>

          <div class="form-group">
            <label for="dev_societe">Société / Entité</label>
            <input type="text" id="dev_societe" placeholder="Ex: Groupe Foncière Maroc" />
          </div>

          <div class="form-group">
            <label for="dev_tel">Téléphone</label>
            <input type="text" id="dev_tel" placeholder="Ex: +212 6..." />
          </div>

          <div class="form-group">
            <label for="dev_email">Email</label>
            <input type="email" id="dev_email" placeholder="client@domaine.ma" />
          </div>

          <div class="form-group">
            <label for="dev_titre">Intitulé du projet</label>
            <input type="text" id="dev_titre" placeholder="Ex: Construction Plateforme Logistique" />
          </div>

          <div class="form-group">
            <label for="dev_travaux">Corps d'état / Nature</label>
            <select id="dev_travaux">
              <option value="Gros Œuvre & Structure">Gros Œuvre & Structure</option>
              <option value="Bâtiment Industriel & Logistique">Bâtiment Industriel & Logistique</option>
              <option value="Tous Corps d'État (TCE)">Tous Corps d'État (TCE)</option>
              <option value="Second Œuvre & Finitions">Second Œuvre & Finitions</option>
              <option value="Rénovation & Réhabilitation">Rénovation & Réhabilitation</option>
            </select>
          </div>

          <div class="form-group">
            <label for="dev_loc">Localisation</label>
            <input type="text" id="dev_loc" placeholder="Ex: Bouskoura, Casablanca" />
          </div>

          <div class="form-group">
            <label for="dev_montant">Montant Estimé HT (MAD)</label>
            <input type="number" id="dev_montant" placeholder="0.00" step="100" />
          </div>

          <div class="form-group">
            <label for="dev_statut">Statut initial</label>
            <select id="dev_statut">
              <option value="Brouillon">Brouillon</option>
              <option value="Transmis">Transmis au client</option>
              <option value="En étude">En étude technique</option>
              <option value="Validé">Validé / Signé</option>
            </select>
          </div>

          <div class="form-group form-col-full">
            <label for="dev_notes">Notes techniques / Détails chiffrage</label>
            <textarea id="dev_notes" rows="3" placeholder="Hypothèses de terrassement, béton B25, cadences..."></textarea>
          </div>

        </div>

        <div class="modal-actions">
          <button type="button" class="logout-btn" onclick="closeDevisModal()" style="padding: 10px 18px;">Annuler</button>
          <button type="submit" class="btn-create-devis" id="saveDevisBtn">Enregistrer le devis</button>
        </div>
      </form>
    </div>
  </div>

  <style>
    .status-btn {
      background: #FFFFFF;
      border: 1px solid var(--border-light);
      border-radius: 6px;
      padding: 6px 14px;
      font-size: 0.82rem;
      font-weight: 600;
      color: var(--text-muted);
      cursor: pointer;
      transition: all 0.18s;
    }
    .status-btn:hover {
      background: #F8FAFC;
      color: var(--text-dark);
    }
    .status-btn.active {
      background: #0F172A;
      color: #FFFFFF;
      border-color: #0F172A;
    }
  </style>

  <!-- ================= CORE LOGIC JAVASCRIPT ================= -->
  <script>
    let devisList = [];
    let currentFilter = 'Tous';
    let searchQuery = '';

    // Check authentication on load
    document.addEventListener('DOMContentLoaded', async () => {
      try {
        const res = await fetch('../api/admin-api.php?action=check_auth');
        const data = await res.json();
        if (data.authenticated) {
          showDashboard();
        } else {
          showAuth();
        }
      } catch (e) {
        showAuth();
      }
    });

    function showAuth() {
      document.getElementById('authOverlay').style.display = 'flex';
      document.getElementById('mainDashboard').style.display = 'none';
    }

    function showDashboard() {
      document.getElementById('authOverlay').style.display = 'none';
      document.getElementById('mainDashboard').style.display = 'flex';
      loadSettings();
      loadDevis();
    }

    // Login Handler
    async function handleLogin(e) {
      e.preventDefault();
      const user = document.getElementById('authUsername').value.trim();
      const pass = document.getElementById('authPassword').value.trim();
      const alert = document.getElementById('authAlert');
      const btn = document.getElementById('authBtn');

      btn.disabled = true;
      btn.textContent = "Connexion en cours...";
      alert.style.display = 'none';

      try {
        const res = await fetch('../api/admin-api.php?action=login', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ username: user, password: pass })
        });
        const data = await res.json();
        if (data.success) {
          showDashboard();
        } else {
          alert.textContent = data.error || 'Identifiant ou mot de passe incorrect.';
          alert.style.display = 'block';
        }
      } catch (err) {
        alert.textContent = "Erreur de connexion au serveur.";
        alert.style.display = 'block';
      } finally {
        btn.disabled = false;
        btn.textContent = "Se connecter au tableau de bord";
      }
    }

    // Logout
    async function handleLogout() {
      if (!confirm('Voulez-vous fermer votre session administrateur ?')) return;
      try {
        await fetch('../api/admin-api.php?action=logout');
      } catch (e) {}
      window.location.reload();
    }

    // Tabs switcher
    function switchTab(tabId, element) {
      document.querySelectorAll('.tab-view').forEach(t => t.classList.remove('active-tab'));
      document.querySelectorAll('.sidebar-nav-item').forEach(i => i.classList.remove('active'));

      const target = document.getElementById(tabId);
      if (target) target.classList.add('active-tab');
      if (element) element.classList.add('active');

      if (tabId === 'tab-devis' || tabId === 'tab-accueil') {
        loadDevis();
      }
    }

    // Load software settings
    async function loadSettings() {
      try {
        const res = await fetch('../api/admin-api.php?action=get_settings');
        const data = await res.json();
        if (data.success && data.settings) {
          const p = data.settings.link_paperless || 'http://localhost:8001';
          const g = data.settings.link_gce || 'https://tg2d.ma/gce';
          const e = data.settings.link_easydoc || 'https://tg2d.ma/easydoc/login.php';

          // Set links on quick access cards
          const linkP = document.getElementById('linkPaperlessCard');
          const linkG = document.getElementById('linkGceCard');
          const linkE = document.getElementById('linkEasydocCard');
          if (linkP) linkP.href = p;
          if (linkG) linkG.href = g;
          if (linkE) linkE.href = e;

          // Set links on Logiciels tab
          const sP = document.getElementById('linkPaperlessSuite');
          const sG = document.getElementById('linkGceSuite');
          const sE = document.getElementById('linkEasydocSuite');
          if (sP) sP.href = p;
          if (sG) sG.href = g;
          if (sE) sE.href = e;

          // Set inputs in Paramètres tab
          const inP = document.getElementById('cfg_paperless');
          const inG = document.getElementById('cfg_gce');
          const inE = document.getElementById('cfg_easydoc');
          if (inP) inP.value = p;
          if (inG) inG.value = g;
          if (inE) inE.value = e;
        }
      } catch (err) {
        console.error('Erreur chargement paramètres:', err);
      }
    }

    async function saveSoftwareSettings(e) {
      e.preventDefault();
      const p = document.getElementById('cfg_paperless').value.trim();
      const g = document.getElementById('cfg_gce').value.trim();
      const ed = document.getElementById('cfg_easydoc').value.trim();
      const alert = document.getElementById('settingsAlert');

      try {
        const res = await fetch('../api/admin-api.php?action=save_settings', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ link_paperless: p, link_gce: g, link_easydoc: ed })
        });
        const data = await res.json();
        if (data.success) {
          alert.style.display = 'block';
          alert.style.background = '#ECFDF5';
          alert.style.color = '#047857';
          alert.style.border = '1px solid #A7F3D0';
          alert.textContent = '✅ Liens des logiciels enregistrés avec succès !';
          loadSettings();
        }
      } catch (err) {
        alert.style.display = 'block';
        alert.style.background = '#FEF2F2';
        alert.style.color = '#B91C1C';
        alert.textContent = 'Erreur lors de la sauvegarde.';
      }
    }

    // Load devis list
    async function loadDevis() {
      try {
        const res = await fetch('../api/admin-api.php?action=get_devis');
        const data = await res.json();
        if (data.success) {
          devisList = data.devis || [];
          renderAllDevis();
        }
      } catch (err) {
        console.error('Erreur chargement devis:', err);
      }
    }

    // Render Devis in both Accueil and Devis tabs
    function renderAllDevis() {
      renderAccueilDevis();
      renderDevisTab();
    }

    function renderAccueilDevis() {
      const container = document.getElementById('latestDevisContainer');
      if (!container) return;

      if (!devisList || devisList.length === 0) {
        container.innerHTML = `
          <div class="empty-state-view">
            <div class="empty-icon-circle">
              <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
            </div>
            <h3 class="empty-title">Aucun devis pour le moment</h3>
            <p class="empty-desc">Vos devis apparaîtront ici une fois créés.</p>
            <button class="btn-create-devis" onclick="openDevisModal()">
              <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
              <span>Créer un nouveau devis</span>
            </button>
          </div>
        `;
        return;
      }

      // Display table of latest 5 devis
      const latest = devisList.slice(0, 5);
      container.innerHTML = `
        <div class="devis-table-wrap">
          <table class="devis-table">
            <thead>
              <tr>
                <th>RÉFÉRENCE</th>
                <th>CLIENT / SOCIÉTÉ</th>
                <th>PROJET & TRAVAUX</th>
                <th>MONTANT HT</th>
                <th>STATUT</th>
                <th>DATE</th>
              </tr>
            </thead>
            <tbody>
              ${latest.map(d => `
                <tr>
                  <td><strong style="color: var(--c-blue);">${escapeHtml(d.reference || 'DEV-N/A')}</strong></td>
                  <td>
                    <div style="font-weight: 600;">${escapeHtml(d.client_nom)}</div>
                    ${d.client_societe ? `<div style="font-size: 0.78rem; color: #64748B;">${escapeHtml(d.client_societe)}</div>` : ''}
                  </td>
                  <td>
                    <div>${escapeHtml(d.projet_titre || 'Chantier BTP')}</div>
                    <div style="font-size: 0.78rem; color: #64748B;">${escapeHtml(d.type_travaux || 'TCE')}</div>
                  </td>
                  <td><strong>${Number(d.montant_ht || 0).toLocaleString('fr-FR')} MAD</strong></td>
                  <td>${getStatusBadge(d.status)}</td>
                  <td style="color: #64748B; font-size: 0.82rem;">${d.created_at ? d.created_at.substring(0, 10) : ''}</td>
                </tr>
              `).join('')}
            </tbody>
          </table>
        </div>
      `;
    }

    function renderDevisTab() {
      const container = document.getElementById('devisFullContainer');
      const counter = document.getElementById('devisCounterText');
      if (!container) return;

      let filtered = devisList.filter(d => {
        if (currentFilter !== 'Tous' && d.status !== currentFilter) return false;
        if (searchQuery !== '') {
          const q = searchQuery.toLowerCase();
          const match = (d.reference && d.reference.toLowerCase().includes(q)) ||
                        (d.client_nom && d.client_nom.toLowerCase().includes(q)) ||
                        (d.client_societe && d.client_societe.toLowerCase().includes(q)) ||
                        (d.projet_titre && d.projet_titre.toLowerCase().includes(q));
          if (!match) return false;
        }
        return true;
      });

      if (counter) counter.textContent = `${filtered.length} devis affiché(s)`;

      if (filtered.length === 0) {
        container.innerHTML = `
          <div class="empty-state-view" style="padding: 60px;">
            <div class="empty-icon-circle">
              <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/></svg>
            </div>
            <h3 class="empty-title">Aucun devis trouvé</h3>
            <p class="empty-desc">Aucun devis ne correspond aux critères de filtre sélectionnés.</p>
            <button class="btn-create-devis" onclick="openDevisModal()">+ Créer un nouveau devis</button>
          </div>
        `;
        return;
      }

      container.innerHTML = `
        <div class="devis-table-wrap">
          <table class="devis-table">
            <thead>
              <tr>
                <th>RÉFÉRENCE</th>
                <th>CLIENT & CONTACT</th>
                <th>PROJET</th>
                <th>MONTANT HT</th>
                <th>MONTANT TTC</th>
                <th>STATUT</th>
                <th>DATE</th>
                <th>ACTIONS</th>
              </tr>
            </thead>
            <tbody>
              ${filtered.map(d => {
                const cleanTel = (d.client_tel || '').replace(/[^0-9]/g, '');
                const waNumber = cleanTel.startsWith('0') ? '212' + cleanTel.substring(1) : cleanTel;
                const waLink = cleanTel ? `https://wa.me/${waNumber}?text=${encodeURIComponent('Bonjour ' + (d.client_nom || '') + ', concernant votre dossier d\'étude ' + (d.reference || '') + ' pour TG2D...')}` : '';
                return `
                <tr>
                  <td><strong style="color: var(--c-blue);">${escapeHtml(d.reference || 'DEV-N/A')}</strong></td>
                  <td>
                    <div style="font-weight: 600;">${escapeHtml(d.client_nom)}</div>
                    ${d.client_societe ? `<div style="font-size: 0.78rem; color: #475569;">🏢 ${escapeHtml(d.client_societe)}</div>` : ''}
                    ${d.client_tel ? `<div style="font-size: 0.78rem; color: #64748B;">📞 ${escapeHtml(d.client_tel)}</div>` : ''}
                    ${d.client_email ? `<div style="font-size: 0.78rem; color: #64748B;">✉️ ${escapeHtml(d.client_email)}</div>` : ''}
                  </td>
                  <td>
                    <div style="font-weight: 600;">${escapeHtml(d.projet_titre || 'Chantier')}</div>
                    <div style="font-size: 0.78rem; color: #64748B;">📍 ${escapeHtml(d.localisation || 'Maroc')}</div>
                    ${d.type_travaux ? `<div style="font-size: 0.75rem; color: #0284C7;">${escapeHtml(d.type_travaux)}</div>` : ''}
                  </td>
                  <td><strong>${Number(d.montant_ht || 0).toLocaleString('fr-FR')} MAD</strong></td>
                  <td><strong style="color: #047857;">${Number(d.montant_ttc || 0).toLocaleString('fr-FR')} MAD</strong></td>
                  <td>
                    <select onchange="updateDevisStatus(${d.id}, this.value)" style="border: 1px solid #CBD5E1; border-radius: 6px; padding: 4px 8px; font-size: 0.8rem; background: #FFFFFF; font-weight: 600;">
                      <option value="En attente" ${d.status === 'En attente' ? 'selected' : ''}>En attente</option>
                      <option value="En étude" ${d.status === 'En étude' ? 'selected' : ''}>En étude</option>
                      <option value="Transmis" ${d.status === 'Transmis' ? 'selected' : ''}>Transmis</option>
                      <option value="Validé" ${d.status === 'Validé' ? 'selected' : ''}>Validé</option>
                      <option value="Refusé" ${d.status === 'Refusé' ? 'selected' : ''}>Refusé</option>
                      <option value="Brouillon" ${d.status === 'Brouillon' ? 'selected' : ''}>Brouillon</option>
                    </select>
                  </td>
                  <td style="color: #64748B; font-size: 0.82rem;">${d.created_at ? d.created_at.substring(0, 10) : ''}</td>
                  <td>
                    <div style="display: inline-flex; align-items: center; gap: 6px;">
                      ${waLink ? `<a href="${waLink}" target="_blank" rel="noopener noreferrer" style="background: #25D366; color: #FFFFFF; text-decoration: none; border-radius: 6px; padding: 4px 8px; font-size: 0.78rem; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;" title="Contacter sur WhatsApp">💬 WhatsApp</a>` : ''}
                      <button onclick="deleteDevis(${d.id})" style="background: transparent; border: 1px solid #FECACA; border-radius: 6px; color: #EF4444; cursor: pointer; padding: 4px 8px; font-size: 0.82rem; font-weight: 600;" title="Supprimer ce devis">
                        ✕
                      </button>
                    </div>
                  </td>
                </tr>
              `;}).join('')}
            </tbody>
          </table>
        </div>
      `;
    }

    function getStatusBadge(status) {
      if (status === 'Validé') return `<span class="status-badge badge-valide">Validé</span>`;
      if (status === 'Transmis') return `<span class="status-badge badge-transmis">Transmis</span>`;
      if (status === 'Refusé') return `<span class="status-badge badge-refuse">Refusé</span>`;
      if (status === 'En attente' || status === 'Nouveau' || status === 'En étude') return `<span class="status-badge badge-attente">${status}</span>`;
      return `<span class="status-badge badge-brouillon">${status || 'Brouillon'}</span>`;
    }

    function filterDevisStatus(status, btn) {
      currentFilter = status;
      document.querySelectorAll('.status-btn').forEach(b => b.classList.remove('active'));
      if (btn) btn.classList.add('active');
      renderDevisTab();
    }

    function handleSearch(val) {
      searchQuery = val.trim();
      renderDevisTab();
    }

    // Modal Create Devis
    function openDevisModal() {
      document.getElementById('devisModal').style.display = 'flex';
    }
    function closeDevisModal() {
      document.getElementById('devisModal').style.display = 'none';
      document.getElementById('createDevisForm').reset();
    }

    async function handleCreateDevis(e) {
      e.preventDefault();
      const payload = {
        client_nom: document.getElementById('dev_nom').value.trim(),
        client_societe: document.getElementById('dev_societe').value.trim(),
        client_tel: document.getElementById('dev_tel').value.trim(),
        client_email: document.getElementById('dev_email').value.trim(),
        projet_titre: document.getElementById('dev_titre').value.trim(),
        type_travaux: document.getElementById('dev_travaux').value,
        localisation: document.getElementById('dev_loc').value.trim(),
        montant_ht: parseFloat(document.getElementById('dev_montant').value) || 0,
        status: document.getElementById('dev_statut').value,
        notes: document.getElementById('dev_notes').value.trim()
      };

      const btn = document.getElementById('saveDevisBtn');
      btn.disabled = true;
      btn.textContent = "Création en cours...";

      try {
        const res = await fetch('../api/admin-api.php?action=create_devis', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload)
        });
        const data = await res.json();
        if (data.success) {
          closeDevisModal();
          await loadDevis();
        } else {
          alert(data.error || 'Erreur lors de la création du devis.');
        }
      } catch (err) {
        alert("Erreur serveur lors de l'enregistrement du devis.");
      } finally {
        btn.disabled = false;
        btn.textContent = "Enregistrer le devis";
      }
    }

    async function updateDevisStatus(id, newStatus) {
      try {
        await fetch('../api/admin-api.php?action=update_devis_status', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ id: id, status: newStatus })
        });
        await loadDevis();
      } catch (err) {
        console.error(err);
      }
    }

    async function deleteDevis(id) {
      if (!confirm('Êtes-vous sûr de vouloir supprimer définitivement ce devis ?')) return;
      try {
        await fetch('../api/admin-api.php?action=delete_devis', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ id: id })
        });
        await loadDevis();
      } catch (err) {
        console.error(err);
      }
    }

    function escapeHtml(text) {
      if (!text) return '';
      return String(text).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }
  </script>

</body>
</html>
