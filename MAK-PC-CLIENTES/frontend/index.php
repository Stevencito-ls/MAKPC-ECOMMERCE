<?php
if (session_status() === PHP_SESSION_NONE && php_sapi_name() !== 'cli') {
    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');

if (session_status() === PHP_SESSION_NONE) {
    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');
    session_set_cookie_params([
        'lifetime' => 7200,
        'path'     => '/',
        'domain'   => '',
        'secure'   => $isHttps,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    @session_start();
    session_start();
}
require_once __DIR__ . '/../../ecommerce/config/app.php';

// Verificar sesión compartida
if (!isLoggedIn()) {
    $_SESSION['intended_url'] = ROOT_URL . 'taller';
    header("Location: " . ROOT_URL . "login?redirect=" . urlencode(ROOT_URL . 'taller'));
    $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
    $ecommercePath = preg_replace('#/MAK-PC-CLIENTES/frontend(/.*)?$#i', '', $scriptName) . '/ecommerce/login';
    header("Location: " . $ecommercePath);
    exit;
}

$userId = auth('id');
$userRole = strtolower(trim((string)auth('rol')));
$userRoleDisplay = strtoupper(trim((string)auth('rol')));
$userName = auth('nombre_completo');

// Extraer iniciales (ej. "Juan Pérez" -> "JP")
$words = explode(" ", $userName);
$initials = strtoupper(substr($words[0], 0, 1));
if (isset($words[1])) {
    $initials .= strtoupper(substr($words[1], 0, 1));
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MAK-PC Enterprises S.A.C. | Sistema de Taller y Mostrador</title>
  <base href="<?= ROOT_URL . 'MAK-PC-CLIENTES/frontend/' ?>">
  <link rel="icon" type="image/png" href="img/logo.png">
  
  <!-- Iconos Phosphor Icons -->
  <script src="https://unpkg.com/@phosphor-icons/web"></script>
  
  <!-- Estilos Corporativos MAK-PC -->
  <link rel="stylesheet" href="css/styles.css?v=<?= file_exists(__DIR__ . '/css/styles.css') ? filemtime(__DIR__ . '/css/styles.css') : '2.8' ?>">

  <!-- ESTILOS INMEDIATOS A PRUEBA DE CACHÉ PARA MODAL EXECUTIVE -->
  <style id="executive-taller-styles">
    :root {
      --m-navy-950: #0a0f24;
      --m-navy-900: #101738;
      --m-navy-800: #161D45;
      --m-cyan-500: #05A9E9;
      --m-cyan-600: #0284c7;
      --m-cyan-50: #f0f9ff;
      --m-emerald-500: #10b981;
      --m-slate-50: #f8fafc;
      --m-slate-100: #f1f5f9;
      --m-slate-200: #e2e8f0;
      --m-slate-300: #cbd5e1;
      --m-slate-400: #94a3b8;
      --m-slate-500: #64748b;
      --m-slate-600: #475569;
      --m-slate-700: #334155;
      --m-slate-800: #1e293b;
    }

    #modal-nuevo-ingreso .modal-container {
      max-width: 980px !important;
      width: 95% !important;
      border-radius: 16px !important;
      overflow: hidden !important;
      box-shadow: 0 25px 60px -15px rgba(10, 15, 36, 0.4), 0 0 0 1px rgba(255, 255, 255, 0.1) !important;
      background: #ffffff !important;
    }

    #modal-nuevo-ingreso .modal-header {
      background: linear-gradient(135deg, #101738 0%, #161D45 55%, #0d2252 100%) !important;
      color: #ffffff !important;
      padding: 1.15rem 1.6rem !important;
      border-bottom: 1px solid rgba(255, 255, 255, 0.12) !important;
      display: flex !important;
      justify-content: space-between !important;
      align-items: center !important;
    }

    #modal-nuevo-ingreso .modal-header h3 {
      font-size: 1.15rem !important;
      font-weight: 700 !important;
      margin: 0 !important;
      display: flex !important;
      align-items: center !important;
      gap: 0.55rem !important;
      color: #ffffff !important;
    }

    #modal-nuevo-ingreso .modal-body {
      padding: 1.25rem 1.6rem !important;
      max-height: calc(85vh - 130px) !important;
      overflow-y: auto !important;
      background: #f8fafc !important;
    }

    /* Stepper Navigation */
    .modal-steps-nav {
      display: flex !important;
      align-items: center !important;
      background: #ffffff !important;
      border: 1.5px solid var(--m-slate-200) !important;
      border-radius: 12px !important;
      padding: 0.4rem 0.6rem !important;
      margin-bottom: 1.25rem !important;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04) !important;
      gap: 0.5rem !important;
    }

    .step-nav-btn {
      display: flex !important;
      align-items: center !important;
      gap: 0.6rem !important;
      background: transparent !important;
      border: 1.5px solid transparent !important;
      padding: 0.55rem 0.9rem !important;
      border-radius: 9px !important;
      cursor: pointer !important;
      font-family: inherit !important;
      font-size: 0.86rem !important;
      font-weight: 600 !important;
      color: var(--m-slate-600) !important;
      transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
      flex: 1 !important;
      justify-content: center !important;
      outline: none !important;
    }

    .step-nav-btn:hover {
      background: var(--m-slate-50) !important;
      color: var(--m-navy-900) !important;
    }

    .step-nav-btn.active {
      background: #ffffff !important;
      border-color: var(--m-cyan-500) !important;
      color: var(--m-navy-900) !important;
      box-shadow: 0 2px 8px rgba(5, 169, 233, 0.18) !important;
      font-weight: 700 !important;
    }

    .step-nav-btn .step-num {
      width: 24px !important;
      height: 24px !important;
      border-radius: 50% !important;
      background: var(--m-slate-100) !important;
      color: var(--m-slate-600) !important;
      display: inline-flex !important;
      align-items: center !important;
      justify-content: center !important;
      font-size: 0.76rem !important;
      font-weight: 800 !important;
      transition: all 0.2s !important;
    }

    .step-nav-btn.active .step-num {
      background: var(--m-cyan-500) !important;
      color: #ffffff !important;
      box-shadow: 0 2px 6px rgba(5, 169, 233, 0.35) !important;
    }

    .step-nav-btn.completed .step-num {
      background: var(--m-emerald-500) !important;
      color: #ffffff !important;
    }

    .step-nav-divider {
      width: 1px !important;
      height: 22px !important;
      background: var(--m-slate-200) !important;
    }

    .step-pill {
      display: inline-flex !important;
      align-items: center !important;
      gap: 0.35rem !important;
      padding: 0.2rem 0.6rem !important;
      border-radius: 9999px !important;
      font-size: 0.72rem !important;
      font-weight: 600 !important;
      background: var(--m-slate-100) !important;
      color: var(--m-slate-500) !important;
      transition: all 0.2s !important;
      border: 1px solid transparent !important;
    }

    .step-pill.completed {
      background: #ecfdf5 !important;
      color: #059669 !important;
      border-color: #a7f3d0 !important;
    }

    .btn-toggle-all {
      background: #ffffff !important;
      border: 1px solid var(--m-slate-200) !important;
      color: var(--m-slate-700) !important;
      padding: 0.5rem 0.85rem !important;
      border-radius: 8px !important;
      font-size: 0.78rem !important;
      font-weight: 600 !important;
      cursor: pointer !important;
      display: inline-flex !important;
      align-items: center !important;
      gap: 0.4rem !important;
      transition: all 0.2s !important;
      white-space: nowrap !important;
    }

    .btn-toggle-all:hover {
      background: var(--m-slate-50) !important;
      border-color: var(--m-slate-300) !important;
      color: var(--m-navy-900) !important;
    }

    /* Accordion Step Cards */
    .accordion-step-card {
      border: 1.5px solid var(--m-slate-200) !important;
      border-radius: 14px !important;
      background: #ffffff !important;
      margin-bottom: 1rem !important;
      transition: border-color 0.25s, box-shadow 0.25s !important;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03) !important;
      overflow: hidden !important;
    }

    .accordion-step-card.open {
      border-color: #93c5fd !important;
      box-shadow: 0 4px 14px rgba(5, 169, 233, 0.08) !important;
    }

    .accordion-step-header {
      display: flex !important;
      align-items: center !important;
      justify-content: space-between !important;
      padding: 0.95rem 1.35rem !important;
      background: #ffffff !important;
      cursor: pointer !important;
      user-select: none !important;
      transition: background 0.2s !important;
    }

    .accordion-step-header:hover {
      background: #fafbfc !important;
    }

    .accordion-header-left {
      display: flex !important;
      align-items: center !important;
      gap: 0.85rem !important;
    }

    .accordion-icon-box {
      width: 42px !important;
      height: 42px !important;
      border-radius: 11px !important;
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
      font-size: 1.25rem !important;
      flex-shrink: 0 !important;
    }

    .accordion-icon-box.client {
      background: #e0f2fe !important;
      color: #0284c7 !important;
    }

    .accordion-icon-box.device {
      background: #ede9fe !important;
      color: #7c3aed !important;
    }

    .accordion-icon-box.diagnostic {
      background: #fef3c7 !important;
      color: #d97706 !important;
    }

    .accordion-title {
      font-size: 0.98rem !important;
      font-weight: 700 !important;
      color: var(--m-slate-800) !important;
    }

    .accordion-subtitle {
      font-size: 0.77rem !important;
      color: var(--m-slate-500) !important;
      margin-top: 2px !important;
    }

    .accordion-header-right {
      display: flex !important;
      align-items: center !important;
      gap: 0.75rem !important;
    }

    .accordion-arrow {
      font-size: 1rem !important;
      color: var(--m-slate-400) !important;
      transition: transform 0.25s ease !important;
    }

    .accordion-step-card.open .accordion-arrow {
      transform: rotate(180deg) !important;
      color: var(--m-cyan-500) !important;
    }

    .accordion-step-content {
      display: none !important;
      border-top: 1px solid var(--m-slate-100) !important;
      background: #ffffff !important;
    }

    .accordion-step-card.open .accordion-step-content {
      display: block !important;
      animation: fadeInStep 0.25s ease !important;
    }

    @keyframes fadeInStep {
      from { opacity: 0; transform: translateY(-4px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .accordion-step-inner {
      padding: 1.35rem 1.45rem !important;
    }

    /* Form Grid */
    .form-grid {
      display: grid !important;
      grid-template-columns: repeat(12, 1fr) !important;
      gap: 1.05rem !important;
      align-items: start !important;
    }

    .form-group {
      display: flex !important;
      flex-direction: column !important;
      gap: 0.35rem !important;
    }

    .form-group label {
      font-size: 0.81rem !important;
      font-weight: 600 !important;
      color: var(--m-slate-700) !important;
      display: flex !important;
      align-items: center !important;
      gap: 0.35rem !important;
    }

    .form-group label .required {
      color: #ef4444 !important;
      font-weight: 700 !important;
    }

    .form-control {
      width: 100% !important;
      border: 1.5px solid var(--m-slate-200) !important;
      border-radius: 9px !important;
      padding: 0.6rem 0.85rem !important;
      font-size: 0.88rem !important;
      color: var(--m-slate-800) !important;
      background: #ffffff !important;
      transition: border-color 0.2s, box-shadow 0.2s !important;
      outline: none !important;
      font-family: inherit !important;
      box-sizing: border-box !important;
    }

    .form-control:focus {
      border-color: var(--m-cyan-500) !important;
      box-shadow: 0 0 0 3px rgba(5, 169, 233, 0.15) !important;
    }

    .font-mono {
      font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace !important;
    }

    /* Input with action */
    .input-with-action {
      display: flex !important;
      align-items: stretch !important;
      position: relative !important;
      width: 100% !important;
    }

    .input-with-action .form-control {
      border-top-right-radius: 0 !important;
      border-bottom-right-radius: 0 !important;
      border-right: none !important;
    }

    .btn-search-doc {
      border-top-left-radius: 0 !important;
      border-bottom-left-radius: 0 !important;
      padding: 0.55rem 1rem !important;
      background: linear-gradient(135deg, #05A9E9 0%, #0284c7 100%) !important;
      color: #ffffff !important;
      border: none !important;
      font-weight: 600 !important;
      font-size: 0.82rem !important;
      cursor: pointer !important;
      display: inline-flex !important;
      align-items: center !important;
      gap: 0.4rem !important;
      transition: all 0.2s !important;
      white-space: nowrap !important;
    }

    .btn-search-doc:hover {
      background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%) !important;
    }

    /* Metadata rows & character counters */
    .field-meta-row {
      display: flex !important;
      align-items: center !important;
      justify-content: space-between !important;
      margin-top: 0.28rem !important;
      gap: 0.5rem !important;
    }

    .field-meta-row.justify-end {
      justify-content: flex-end !important;
    }

    .char-counter {
      font-size: 0.72rem !important;
      color: var(--m-slate-400) !important;
      font-family: monospace !important;
      font-weight: 600 !important;
      white-space: nowrap !important;
    }

    .char-counter.valid {
      color: #10b981 !important;
    }

    .char-counter.limit {
      color: #ef4444 !important;
      font-weight: 700 !important;
    }

    .field-hint {
      font-size: 0.72rem !important;
      color: var(--m-slate-500) !important;
    }

    .field-hint.valid {
      color: #059669 !important;
      font-weight: 600 !important;
    }

    /* Doc Lookup Status Badge */
    .doc-feedback-badge {
      display: inline-flex !important;
      align-items: center !important;
      gap: 0.4rem !important;
      font-size: 0.74rem !important;
      padding: 0.28rem 0.65rem !important;
      border-radius: 6px !important;
      background: var(--m-slate-100) !important;
      color: var(--m-slate-600) !important;
      border: 1px solid var(--m-slate-200) !important;
      transition: all 0.2s !important;
      width: 100% !important;
      box-sizing: border-box !important;
    }

    .doc-feedback-badge.loading {
      background: #eff6ff !important;
      color: #2563eb !important;
      border-color: #bfdbfe !important;
    }

    .doc-feedback-badge.success {
      background: #ecfdf5 !important;
      color: #047857 !important;
      border-color: #a7f3d0 !important;
      font-weight: 600 !important;
    }

    .doc-feedback-badge.not-found {
      background: #fffbeb !important;
      color: #b45309 !important;
      border-color: #fde68a !important;
    }

    /* Quick Chips */
    .quick-chips-wrapper {
      display: flex !important;
      flex-wrap: wrap !important;
      align-items: center !important;
      gap: 0.35rem !important;
      margin-top: 0.45rem !important;
    }

    .quick-chips-label {
      font-size: 0.72rem !important;
      font-weight: 600 !important;
      color: var(--m-slate-500) !important;
      display: inline-flex !important;
      align-items: center !important;
      gap: 0.25rem !important;
      margin-right: 0.2rem !important;
    }

    .quick-chip {
      background: #f1f5f9 !important;
      border: 1px solid #e2e8f0 !important;
      color: #334155 !important;
      padding: 0.22rem 0.6rem !important;
      border-radius: 6px !important;
      font-size: 0.74rem !important;
      font-weight: 500 !important;
      cursor: pointer !important;
      transition: all 0.15s ease !important;
      user-select: none !important;
    }

    .quick-chip:hover {
      background: #05A9E9 !important;
      color: #ffffff !important;
      border-color: #05A9E9 !important;
      transform: translateY(-1px) !important;
    }

    /* Step Navigation Footer */
    .step-nav-footer {
      display: flex !important;
      justify-content: flex-end !important;
      margin-top: 1.25rem !important;
      padding-top: 0.85rem !important;
      border-top: 1px dashed var(--m-slate-200) !important;
    }

    .btn-step-next {
      background: linear-gradient(135deg, #161D45 0%, #101738 100%) !important;
      color: #ffffff !important;
      border: none !important;
      padding: 0.65rem 1.35rem !important;
      border-radius: 9px !important;
      font-size: 0.85rem !important;
      font-weight: 600 !important;
      cursor: pointer !important;
      display: inline-flex !important;
      align-items: center !important;
      gap: 0.5rem !important;
      transition: all 0.2s !important;
      box-shadow: 0 2px 6px rgba(16, 23, 56, 0.2) !important;
    }

    .btn-step-next:hover {
      background: linear-gradient(135deg, #05A9E9 0%, #0284c7 100%) !important;
      box-shadow: 0 4px 12px rgba(5, 169, 233, 0.3) !important;
      transform: translateX(2px) !important;
    }

    /* Finance Panel */
    .finance-live-panel {
      display: grid !important;
      grid-template-columns: repeat(3, 1fr) !important;
      gap: 1rem !important;
      margin-top: 1.25rem !important;
      padding: 1.15rem !important;
      background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%) !important;
      border: 1.5px solid var(--m-slate-200) !important;
      border-radius: 12px !important;
    }

    .finance-calc-card {
      background: #ffffff !important;
      border: 1px solid var(--m-slate-200) !important;
      border-radius: 10px !important;
      padding: 0.85rem 1rem !important;
      display: flex !important;
      flex-direction: column !important;
      gap: 0.4rem !important;
      transition: all 0.2s !important;
    }

    .finance-calc-card:focus-within {
      border-color: var(--m-cyan-500) !important;
      box-shadow: 0 2px 8px rgba(5, 169, 233, 0.12) !important;
    }

    .finance-calc-card label {
      display: flex !important;
      justify-content: space-between !important;
      align-items: center !important;
      font-size: 0.78rem !important;
      font-weight: 700 !important;
      color: var(--m-slate-700) !important;
    }

    .finance-calc-card.saldo-card {
      background: linear-gradient(135deg, #0a1128 0%, #161D45 100%) !important;
      color: #ffffff !important;
      border-color: #1e293b !important;
      justify-content: center !important;
    }

    .finance-calc-card.saldo-card .saldo-title {
      font-size: 0.78rem !important;
      font-weight: 600 !important;
      color: rgba(255, 255, 255, 0.75) !important;
    }

    .finance-calc-card.saldo-card .saldo-usd {
      font-size: 0.82rem !important;
      font-weight: 700 !important;
      color: #34d399 !important;
    }

    .finance-calc-card.saldo-card .saldo-amount {
      font-size: 1.45rem !important;
      font-weight: 900 !important;
      color: #38bdf8 !important;
      font-family: ui-monospace, monospace !important;
      letter-spacing: -0.02em !important;
    }

    /* Modal Footer Floating Bar */
    #modal-nuevo-ingreso .modal-footer {
      display: flex !important;
      align-items: center !important;
      justify-content: space-between !important;
      padding: 0.95rem 1.6rem !important;
      background: #ffffff !important;
      border-top: 1px solid var(--m-slate-200) !important;
      box-shadow: 0 -4px 16px rgba(0, 0, 0, 0.04) !important;
    }

    .modal-footer-summary {
      display: flex !important;
      align-items: center !important;
      gap: 0.6rem !important;
      font-size: 0.82rem !important;
      color: var(--m-slate-600) !important;
      background: var(--m-slate-50) !important;
      padding: 0.4rem 0.85rem !important;
      border-radius: 8px !important;
      border: 1px solid var(--m-slate-200) !important;
    }

    /* Responsive Grid */
    .col-3 { grid-column: span 3 !important; }
    .col-4 { grid-column: span 4 !important; }
    .col-5 { grid-column: span 5 !important; }
    .col-6 { grid-column: span 6 !important; }
    .col-8 { grid-column: span 8 !important; }
    .col-12 { grid-column: span 12 !important; }

    @media (max-width: 768px) {
      .col-3, .col-4, .col-5, .col-6, .col-8 {
        grid-column: span 12 !important;
      }
      .finance-live-panel {
        grid-template-columns: 1fr !important;
      }
      .modal-steps-nav {
        flex-direction: column !important;
        align-items: stretch !important;
      }
      .step-nav-divider {
        display: none !important;
      }
    }
  </style>
</head>
<body>

  <!-- =========================================================================
       1. NAVBAR SUPERIOR INSTITUCIONAL (PREMIUM & ADAPTATIVO)
       ========================================================================= -->
  <header class="top-navbar">
    <div class="top-navbar-inner">
      <div class="brand-wrapper">
        <div class="brand-logo-badge">
          <img src="img/logo.png" alt="MAK-PC Enterprises S.A.C." class="brand-logo-img">
        </div>
        <div class="brand-info">
          <div class="brand-title">
            <span class="brand-name-main">MAK-PC</span>
            <span class="brand-name-sub">ENTERPRISES</span>
            <span class="brand-name-type">S.A.C.</span>
          </div>
          <div class="brand-meta-list">
            <span class="meta-item"><i class="ph-bold ph-identification-card"></i> RUC: 20409456520</span>
            <span class="meta-sep">&bull;</span>
            <span class="meta-item"><i class="ph-bold ph-phone"></i> 975 513 327</span>
            <span class="meta-sep meta-sep-location">&bull;</span>
            <span class="meta-item meta-item-location"><i class="ph-bold ph-map-pin"></i> Tumbes, Perú</span>
          </div>
        </div>
      </div>

      <div class="nav-controls">
        <a href="<?= ROOT_URL ?>panel" class="btn btn-sm btn-cyan" style="text-decoration:none;display:inline-flex;align-items:center;gap:6px;font-weight:700;font-size:0.85rem;" title="Ir al Panel Administrativo">
          <i class="ph-bold ph-layout"></i>
          <span>Panel ERP</span>
        </a>
        <a href="<?= ROOT_URL ?>tienda" class="btn btn-sm" style="text-decoration:none;display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,0.12);color:#fff;font-weight:600;font-size:0.85rem;" title="Ver Tienda Online">
          <i class="ph-bold ph-storefront"></i>
          <span>Tienda</span>
        </a>

        <div class="api-status-pill" id="api-status-container" title="Estado de la conexión al Backend REST">
          <span class="status-dot" id="api-status-dot"></span>
          <span id="api-status-text">Conectado</span>
        </div>

        <div class="user-badge" title="Operador en mostrador">
          <div class="user-avatar"><?= htmlspecialchars($initials) ?></div>
          <div class="user-meta">
            <div class="user-name"><?= htmlspecialchars($userName) ?></div>
            <div class="user-role"><?= htmlspecialchars($userRoleDisplay) ?></div>
          </div>
        </div>
      </div>

    </div>
  </header>

  <!-- =========================================================================
       2. WORKSPACE OPERATIVO UNIFICADO (PANTALLA ÚNICA)
       ========================================================================= -->
  <main class="main-container">

    <!-- A. RESUMEN COMPACTO DE KPIS -->
    <div class="kpi-grid">
      <div class="kpi-card">
        <div class="kpi-content">
          <div class="kpi-label">Equipos en Taller</div>
          <div class="kpi-value" id="kpi-en-taller">0</div>
          <div class="kpi-subtext">En diagnóstico o reparación</div>
        </div>
        <div class="kpi-icon-box blue">
          <i class="ph-bold ph-laptop"></i>
        </div>
      </div>

      <div class="kpi-card">
        <div class="kpi-content">
          <div class="kpi-label">Listos para Entrega</div>
          <div class="kpi-value" id="kpi-listos" style="color: var(--success);">0</div>
          <div class="kpi-subtext">Reparados esperando cliente</div>
        </div>
        <div class="kpi-icon-box green">
          <i class="ph-bold ph-check-circle"></i>
        </div>
      </div>

      <div class="kpi-card">
        <div class="kpi-content">
          <div class="kpi-label">Entregados (Histórico)</div>
          <div class="kpi-value" id="kpi-entregados">0</div>
          <div class="kpi-subtext">Garantía activa de 90 días</div>
        </div>
        <div class="kpi-icon-box purple">
          <i class="ph-bold ph-shield-check"></i>
        </div>
      </div>

      <div class="kpi-card">
        <div class="kpi-content">
          <div class="kpi-label">Saldos por Cobrar</div>
          <div class="kpi-value" id="kpi-por-cobrar" style="color: var(--gold-600);">S/. 0.00</div>
          <div class="kpi-subtext">Pendiente al momento de entrega</div>
        </div>
        <div class="kpi-icon-box amber">
          <i class="ph-bold ph-hand-coins"></i>
        </div>
      </div>
    </div>

    <!-- B. BARRA DE ACCIÓN Y BÚSQUEDA UNIVERSAL RÁPIDA -->
    <div class="search-banner">
      <div class="search-input-wrapper">
        <i class="ph-bold ph-magnifying-glass"></i>
        <input type="text" id="input-search-taller" class="search-input" placeholder="Buscar por DNI, cliente, código (ORD-2026-0001), marca o modelo..." autofocus>
      </div>

      <div class="filter-group">
        <select id="filter-estado-taller" class="filter-select">
          <option value="">-- Todos los Estados --</option>
          <option value="RECEPCIONADO">Recepcionado</option>
          <option value="EN_DIAGNOSTICO">En Diagnóstico</option>
          <option value="ESPERA_REPUESTOS">Espera de Repuestos</option>
          <option value="ESPERA_APROBACION">Espera de Aprobación</option>
          <option value="EN_REPARACION">En Reparación</option>
          <option value="REPARADO">Reparado (Listo p/ Entrega)</option>
          <option value="ENTREGADO">Entregado</option>
        </select>

        <button type="button" class="btn btn-gold" id="btn-nuevo-ingreso" onclick="App.abrirNuevoIngreso()">
          <i class="ph-bold ph-plus-circle" style="font-size: 1.2rem;"></i>
          <span>+ Ingreso de Equipo</span>
        </button>
      </div>
    </div>

    <!-- C. TABLA OPERATIVA PRINCIPAL DE CONTROL DE TALLER -->
    <div class="form-card">
      <div class="form-header">
        <h2>
          <i class="ph-bold ph-desktop-tower"></i> 
          <span>Tablero de Atención y Control Técnico</span>
        </h2>
        <span id="contador-ordenes-total" style="font-size: 0.85rem; color: var(--cyan-400); font-weight: 700;">
          Cargando registros...
        </span>
      </div>

      <div class="table-container">
        <table class="custom-table">
          <thead>
            <tr>
              <th>Código</th>
              <th>Cliente / Teléfono</th>
              <th>Equipo / Modelo</th>
              <th>Falla / Motivo</th>
              <th>Estado Técnico</th>
              <th>Costos y Saldos</th>
              <th>Fecha Ingreso</th>
              <th style="text-align: center; min-width: 170px;">Acción Contextual</th>
            </tr>
          </thead>
          <tbody id="taller-orders-tbody">
            <!-- Renderizado dinámico vía app.js -->
          </tbody>
        </table>
      </div>
    </div>

  </main>


  <!-- =========================================================================
       3. MODAL 1: RECEPCIÓN EXPRESS DE EQUIPO (+ INGRESO)
       ========================================================================= -->
  <!-- =========================================================================
       3. MODAL 1: RECEPCIÓN EXPRESS DE EQUIPO (+ INGRESO CON ASISTENTE POR PASOS)
       ========================================================================= -->
  <div class="modal-backdrop" id="modal-nuevo-ingreso">
    <div class="modal-container modal-lg">
      <div class="modal-header">
        <div>
          <h3>
            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
            <span>Nueva Recepción de Equipo en Mostrador</span>
          </h3>
          <p style="font-size: 0.78rem; color: rgba(255,255,255,0.78); margin: 3px 0 0 0;">
            Asistente ágil en 3 pasos con validación estricta y autocompletado en tiempo real con <strong>apisperu.com</strong>
          </p>
        </div>
        <button type="button" class="btn-close-modal" id="btn-cerrar-modal-ingreso" onclick="Utils.closeModal('modal-nuevo-ingreso')" title="Cerrar ventana">&times;</button>
      </div>

      <form id="form-recepcion-equipo" novalidate>
        <div class="modal-body">
          
          <!-- BARRA DE NAVEGACIÓN Y PROGRESO ENTRE PASOS -->
          <div class="modal-steps-nav">
            <button type="button" class="step-nav-btn active" data-step="1" id="btn-step-tab-1">
              <span class="step-num">1</span>
              <span class="step-title">Cliente</span>
              <span class="step-pill" id="badge-nav-1"><span class="step-pill-dot"></span> Pendiente</span>
            </button>
            <div class="step-nav-divider"></div>
            <button type="button" class="step-nav-btn" data-step="2" id="btn-step-tab-2">
              <span class="step-num">2</span>
              <span class="step-title">Equipo</span>
              <span class="step-pill" id="badge-nav-2"><span class="step-pill-dot"></span> Pendiente</span>
            </button>
            <div class="step-nav-divider"></div>
            <button type="button" class="step-nav-btn" data-step="3" id="btn-step-tab-3">
              <span class="step-num">3</span>
              <span class="step-title">Diagnóstico & Cotización</span>
              <span class="step-pill" id="badge-nav-3"><span class="step-pill-dot"></span> Pendiente</span>
            </button>
            <div class="step-nav-actions" style="margin-left:auto;">
              <button type="button" class="btn-toggle-all" id="btn-toggle-all-accordions" title="Expandir o contraer todas las secciones">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M7 15l5 5 5-5M7 9l5-5 5 5"/></svg>
                <span>Expandir Todo</span>
              </button>
            </div>
          </div>

          <!-- =================================================================
               PASO 1: ACORDEÓN IDENTIFICACIÓN Y CONTACTO DEL CLIENTE
               ================================================================= -->
          <div class="accordion-step-card open" id="acc-card-1">
            <div class="accordion-step-header" data-toggle="acc-card-1">
              <div class="accordion-header-left">
                <div class="accordion-icon-box client">
                  <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                </div>
                <div class="accordion-header-titles">
                  <div class="accordion-title">
                    <span>1. Identificación y Contacto del Cliente</span>
                  </div>
                  <div class="accordion-subtitle">
                    Autocompletado con <strong>apisperu.com</strong> (RENIEC / SUNAT oficial)
                  </div>
                </div>
              </div>
              <div class="accordion-header-right">
                <span class="step-pill" id="step-badge-cliente"><span class="step-pill-dot"></span> Incompleto</span>
                <svg class="accordion-arrow" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"></polyline></svg>
              </div>
            </div>

            <div class="accordion-step-content">
              <div class="accordion-step-inner">
                <div class="form-grid">
                  <!-- Fila 1: Documento y Nombres -->
                  <div class="col-3 form-group">
                    <label for="cliente-tipo-doc">Tipo de Documento</label>
                    <select id="cliente-tipo-doc" class="form-control" style="font-weight: 600;">
                      <option value="DNI" selected>DNI (8 dígitos)</option>
                      <option value="RUC">RUC (11 dígitos)</option>
                      <option value="CE">C.E. (Extranjería)</option>
                      <option value="PASAPORTE">Pasaporte</option>
                    </select>
                  </div>

                  <div class="col-4 form-group">
                    <label for="cliente-doc">Número de Documento <span class="required">*</span></label>
                    <div class="input-with-action">
                      <input type="text" id="cliente-doc" class="form-control font-mono" placeholder="8 dígitos numéricos" required maxlength="8" inputmode="numeric" autocomplete="off" style="font-weight: 700; letter-spacing: 0.05em;">
                      <button type="button" id="btn-buscar-dni" class="btn-search-doc" title="Consultar en apisperu.com / RENIEC / SUNAT">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        <span>Buscar</span>
                      </button>
                    </div>
                    <div class="field-meta-row">
                      <div class="doc-feedback-badge" id="doc-lookup-status">
                        <span>⚡ Escriba 8 dígitos de DNI u 11 de RUC</span>
                      </div>
                      <span class="char-counter" id="cliente-doc-counter">0 / 8</span>
                    </div>
                  </div>

                  <div class="col-5 form-group">
                    <label for="cliente-nombre">Nombres Completos / Razón Social <span class="required">*</span></label>
                    <input type="text" id="cliente-nombre" class="form-control" placeholder="Ej: JUAN PÉREZ MORALES o EMPRESA S.A.C." required maxlength="180" autocomplete="off">
                    <div class="field-meta-row justify-end">
                      <span class="char-counter" id="cliente-nombre-counter">0 / 180</span>
                    </div>
                  </div>

                  <!-- Fila 2: Contacto -->
                  <div class="col-4 form-group">
                    <label for="cliente-telefono">
                      <svg viewBox="0 0 24 24" width="16" height="16" fill="#25D366"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0 0 12.04 2zm0 18.15c-1.48 0-2.93-.4-4.2-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.13 8.13 0 0 1-1.25-4.38c0-4.5 3.66-8.15 8.16-8.15 2.18 0 4.23.85 5.77 2.39a8.1 8.1 0 0 1 2.39 5.76c0 4.5-3.66 8.15-8.16 8.15z"/></svg>
                      Teléfono / WhatsApp <span class="required">*</span>
                    </label>
                    <input type="tel" id="cliente-telefono" class="form-control font-mono" placeholder="Ej: 975513327 (solo dígitos)" required maxlength="9" inputmode="numeric" autocomplete="off">
                    <div class="field-meta-row">
                      <span class="field-hint" id="cliente-telefono-hint">9 dígitos (móvil de Perú)</span>
                      <span class="char-counter" id="cliente-telefono-counter">0 / 9</span>
                    </div>
                  </div>

                  <div class="col-5 form-group">
                    <label for="cliente-direccion">Dirección Fiscal / Domicilio <span style="font-weight:400;color:var(--m-slate-400);">(Opcional)</span></label>
                    <input type="text" id="cliente-direccion" class="form-control" placeholder="Av. Principal 123, Distrito" maxlength="255">
                    <div class="field-meta-row justify-end">
                      <span class="char-counter" id="cliente-direccion-counter">0 / 255</span>
                    </div>
                  </div>

                  <div class="col-3 form-group">
                    <label for="cliente-email">Email <span style="font-weight:400;color:var(--m-slate-400);">(Opcional)</span></label>
                    <input type="email" id="cliente-email" class="form-control" placeholder="cliente@correo.com" maxlength="100">
                    <div class="field-meta-row justify-end">
                      <span class="char-counter" id="cliente-email-counter">0 / 100</span>
                    </div>
                  </div>
                </div>

                <div class="step-nav-footer">
                  <button type="button" class="btn-step-next" data-goto="2">
                    <span>Continuar a Especificaciones del Equipo</span>
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- =================================================================
               PASO 2: ACORDEÓN DATOS DEL EQUIPO E INGRESO
               ================================================================= -->
          <div class="accordion-step-card open" id="acc-card-2">
            <div class="accordion-step-header" data-toggle="acc-card-2">
              <div class="accordion-header-left">
                <div class="accordion-icon-box device">
                  <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.2"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                </div>
                <div class="accordion-header-titles">
                  <div class="accordion-title">
                    <span>2. Especificaciones del Equipo y Accesorios</span>
                  </div>
                  <div class="accordion-subtitle">
                    Dispositivo, marca, modelo, serie y elementos dejados en custodia
                  </div>
                </div>
              </div>
              <div class="accordion-header-right">
                <span class="step-pill" id="step-badge-equipo"><span class="step-pill-dot"></span> Incompleto</span>
                <svg class="accordion-arrow" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"></polyline></svg>
              </div>
            </div>

            <div class="accordion-step-content">
              <div class="accordion-step-inner">
                <div class="form-grid">
                  <!-- Fila 1: Ficha Técnica -->
                  <div class="col-3 form-group">
                    <label for="equipo-tipo">Tipo de Dispositivo <span class="required">*</span></label>
                    <select id="equipo-tipo" class="form-control" required style="font-weight:600;">
                      <option value="LAPTOP" selected>Laptop / Portátil</option>
                      <option value="PC_ESCRITORIO">PC de Escritorio / Torre</option>
                      <option value="ALL_IN_ONE">All in One</option>
                      <option value="IMPRESORA">Impresora</option>
                      <option value="MONITOR">Monitor</option>
                      <option value="SERVIDOR">Servidor</option>
                      <option value="OTRO">Otro Dispositivo</option>
                    </select>
                    <input type="text" id="equipo-tipo-otro" class="form-control" placeholder="Especifique el dispositivo" maxlength="60" style="display: none; margin-top: 0.5rem;">
                  </div>

                  <div class="col-3 form-group">
                    <label for="equipo-marca">Marca <span class="required">*</span></label>
                    <input type="text" id="equipo-marca" class="form-control" placeholder="Ej: Lenovo, HP, Asus, Dell" required maxlength="60">
                    <div class="field-meta-row justify-end">
                      <span class="char-counter" id="equipo-marca-counter">0 / 60</span>
                    </div>
                  </div>

                  <div class="col-3 form-group">
                    <label for="equipo-modelo">Modelo <span class="required">*</span></label>
                    <input type="text" id="equipo-modelo" class="form-control" placeholder="Ej: IdeaPad 3, Pavilion 15" required maxlength="80">
                    <div class="field-meta-row justify-end">
                      <span class="char-counter" id="equipo-modelo-counter">0 / 80</span>
                    </div>
                  </div>

                  <div class="col-3 form-group">
                    <label for="equipo-serie">N° de Serie (S/N) <span style="font-weight:400;color:var(--m-slate-400);">(Opcional)</span></label>
                    <input type="text" id="equipo-serie" class="form-control font-mono" placeholder="Ej: PF2XYZ89" maxlength="80" style="text-transform: uppercase;">
                    <div class="field-meta-row justify-end">
                      <span class="char-counter" id="equipo-serie-counter">0 / 80</span>
                    </div>
                  </div>

                  <!-- Fila 2: Seguridad y Custodia -->
                  <div class="col-4 form-group">
                    <label for="equipo-password">
                      <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                      Clave de Usuario / Windows / BIOS
                    </label>
                    <input type="text" id="equipo-password" class="form-control" placeholder="1234 / Sin contraseña" maxlength="100">
                    <span class="field-hint">Indicar si no tiene o dejar en blanco</span>
                  </div>

                  <div class="col-8 form-group">
                    <label for="equipo-accesorios">Accesorios en Custodia Recibidos</label>
                    <input type="text" id="equipo-accesorios" class="form-control" placeholder="Cargador original, cable poder, mouse, funda" maxlength="250">
                    <!-- Chips de selección rápida -->
                    <div class="quick-chips-wrapper">
                      <span class="quick-chips-label">+ Añadir rápido:</span>
                      <button type="button" class="quick-chip" data-append-target="equipo-accesorios" data-text="Cargador original">Cargador original</button>
                      <button type="button" class="quick-chip" data-append-target="equipo-accesorios" data-text="Cable de poder">Cable de poder</button>
                      <button type="button" class="quick-chip" data-append-target="equipo-accesorios" data-text="Mouse">Mouse</button>
                      <button type="button" class="quick-chip" data-append-target="equipo-accesorios" data-text="Funda / Mochila">Funda / Mochila</button>
                      <button type="button" class="quick-chip" data-append-target="equipo-accesorios" data-text="Solo equipo">Solo equipo</button>
                    </div>
                  </div>
                </div>

                <div class="step-nav-footer">
                  <button type="button" class="btn-step-next" data-goto="3">
                    <span>Continuar a Diagnóstico y Cotización</span>
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- =================================================================
               PASO 3: ACORDEÓN DIAGNÓSTICO, MOTIVO Y CONDICIONES ECONÓMICAS
               ================================================================= -->
          <div class="accordion-step-card open" id="acc-card-3">
            <div class="accordion-step-header" data-toggle="acc-card-3">
              <div class="accordion-header-left">
                <div class="accordion-icon-box diagnostic">
                  <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path></svg>
                </div>
                <div class="accordion-header-titles">
                  <div class="accordion-title">
                    <span>3. Diagnóstico Preliminar y Cotización Comercial</span>
                  </div>
                  <div class="accordion-subtitle">
                    Falla reportada, estado físico, nivel de prioridad, presupuesto y adelanto inicial
                  </div>
                </div>
              </div>
              <div class="accordion-header-right">
                <span class="step-pill" id="step-badge-diagnostico"><span class="step-pill-dot"></span> Incompleto</span>
                <svg class="accordion-arrow" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"></polyline></svg>
              </div>
            </div>

            <div class="accordion-step-content">
              <div class="accordion-step-inner">
                <div class="form-grid">
                  <!-- Fila 1: Prioridad -->
                  <div class="col-4 form-group">
                    <label for="orden-prioridad">Nivel de Prioridad de Atención</label>
                    <select id="orden-prioridad" class="form-control" style="font-weight:600;">
                      <option value="BAJA">🟢 Baja (Sin apuro)</option>
                      <option value="MEDIA" selected>🟡 Normal (Atención estándar)</option>
                      <option value="ALTA">🟠 Alta (Preferencial)</option>
                      <option value="URGENTE">🔴 Urgente (Prioridad máxima mostrador)</option>
                    </select>
                  </div>

                  <div class="col-8 form-group" style="display: flex; align-items: center; padding-top: 1.25rem;">
                    <div style="font-size: 0.8rem; color: var(--m-slate-600); background: var(--m-slate-100); padding: 0.55rem 0.95rem; border-radius: 8px; width: 100%; border: 1px solid var(--m-slate-200);">
                      ℹ️ El técnico asignado recibirá la orden con la prioridad seleccionada y se notificará en pantalla.
                    </div>
                  </div>

                  <!-- Fila 2: Falla Reportada -->
                  <div class="col-6 form-group">
                    <label for="orden-motivo">Falla Reportada por el Cliente <span class="required">*</span></label>
                    <textarea id="orden-motivo" class="form-control" rows="2" placeholder="Equipo no enciende, lentitud extrema, cambio de pantalla, mantenimiento preventivo..." required maxlength="500" style="min-height: 75px;"></textarea>
                    <div class="field-meta-row justify-end">
                      <span class="char-counter" id="motivo-counter">0 / 500</span>
                    </div>
                    <!-- Chips de fallas frecuentes -->
                    <div class="quick-chips-wrapper">
                      <span class="quick-chips-label">⚡ Frecuentes:</span>
                      <button type="button" class="quick-chip" data-append-target="orden-motivo" data-text="No enciende">No enciende</button>
                      <button type="button" class="quick-chip" data-append-target="orden-motivo" data-text="Pantalla rota o sin video">Pantalla / Video</button>
                      <button type="button" class="quick-chip" data-append-target="orden-motivo" data-text="Mantenimiento térmico y limpieza">Mantenimiento</button>
                      <button type="button" class="quick-chip" data-append-target="orden-motivo" data-text="Lentitud / Virus / Formateo">Lentitud / SO</button>
                      <button type="button" class="quick-chip" data-append-target="orden-motivo" data-text="Problema de teclado / batería">Teclado / Batería</button>
                    </div>
                  </div>

                  <!-- Fila 3: Estado Físico -->
                  <div class="col-6 form-group">
                    <label for="equipo-observaciones">Estado Físico / Detalles Estéticos <span style="font-weight:400;color:var(--m-slate-400);">(Opcional)</span></label>
                    <textarea id="equipo-observaciones" class="form-control" rows="2" placeholder="Rayones en tapa, bisagra floja, golpes preexistentes, tornillos faltantes..." maxlength="500" style="min-height: 75px;"></textarea>
                    <div class="field-meta-row justify-end">
                      <span class="char-counter" id="observaciones-counter">0 / 500</span>
                    </div>
                    <!-- Chips de estado estético -->
                    <div class="quick-chips-wrapper">
                      <span class="quick-chips-label">🏷️ Detalles:</span>
                      <button type="button" class="quick-chip" data-append-target="equipo-observaciones" data-text="Buen estado estético">Buen estado</button>
                      <button type="button" class="quick-chip" data-append-target="equipo-observaciones" data-text="Rayones leves de uso">Rayones leves</button>
                      <button type="button" class="quick-chip" data-append-target="equipo-observaciones" data-text="Bisagra floja o quebrada">Bisagra floja</button>
                      <button type="button" class="quick-chip" data-append-target="equipo-observaciones" data-text="Golpes en esquinas">Golpes en bordes</button>
                    </div>
                  </div>
                </div>

                <!-- Panel Financiero Integrado (Costo Estimado, Adelanto y Saldo Calculado) -->
                <div class="finance-live-panel">
                  <div class="finance-calc-card">
                    <label for="orden-costo-estimado">
                      <span>Costo Estimado (S/.)</span>
                      <span id="orden-costo-usd" style="color:var(--m-emerald-500); font-weight:700;">$ 0.00</span>
                    </label>
                    <input type="number" step="0.50" min="0" max="99999.99" id="orden-costo-estimado" class="form-control font-mono" placeholder="S/. 0.00" style="font-weight: 700; font-size: 1.05rem;">
                  </div>
            <div class="col-6 form-group">
              <label for="equipo-observaciones" style="display:flex; justify-content:space-between; align-items:center;">
                <span>Estado Físico / Detalles Estéticos</span>
                <label style="font-size: 0.8rem; font-weight: normal; margin:0; cursor:pointer; color:var(--primary); display:flex; align-items:center; gap: 4px;">
                  <input type="checkbox" id="check-sin-novedad" onchange="if(this.checked){ document.getElementById('equipo-observaciones').value = 'Ingresa sin novedades físicas o estéticas aparentes.'; } else { document.getElementById('equipo-observaciones').value = ''; }"> Sin novedad
                </label>
              </label>
              <textarea id="equipo-observaciones" class="form-control" rows="2" placeholder="Rayones en tapa, bisagra floja, golpes preexistentes..." style="min-height: 70px;"></textarea>
            </div>

                  <div class="finance-calc-card">
                    <label for="orden-monto-adelanto">
                      <span>Adelanto Inicial (A Cuenta S/.)</span>
                      <span id="orden-adelanto-usd" style="color:var(--m-emerald-500); font-weight:700;">$ 0.00</span>
                    </label>
                    <input type="number" step="0.50" min="0" max="99999.99" id="orden-monto-adelanto" class="form-control font-mono" placeholder="S/. 0.00" style="font-weight: 700; font-size: 1.05rem; color: var(--m-navy-900);">
                  </div>

                  <div class="finance-calc-card saldo-card">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                      <span class="saldo-title">Saldo por Cobrar</span>
                      <span class="saldo-usd" id="orden-saldo-usd">$ 0.00</span>
                    </div>
                    <div class="saldo-amount" id="orden-saldo-calc">S/. 0.00</div>
                  </div>
                </div>

              </div>
            </div>
          </div>
          
          <input type="hidden" id="orden-tecnico-asignado" value="<?= htmlspecialchars($userId) ?>">
        </div>

        <div class="modal-footer">
          <div class="modal-footer-summary" id="footer-client-preview">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            <span>Cliente: <strong id="summary-preview-name" style="color:var(--m-navy-900);">Sin asignar</strong></span>
            <span style="color: var(--m-slate-300);">|</span>
            <span>Equipo: <strong id="summary-preview-device" style="color:var(--m-navy-900);">Laptop</strong></span>
          </div>

          <div style="display: flex; align-items: center; gap: 0.65rem;">
            <button type="button" class="btn btn-close-modal" id="btn-cancelar-modal-ingreso" onclick="Utils.closeModal('modal-nuevo-ingreso')" style="background: var(--m-slate-200); color: var(--m-slate-700); font-weight: 600; border: none; padding: 0.7rem 1.3rem; border-radius: 9px; cursor: pointer;">Cancelar</button>
            <button type="submit" class="btn btn-primary" style="padding: 0.72rem 1.75rem; font-weight: 700; border-radius: 9px; display: inline-flex; align-items: center; gap: 0.5rem; cursor: pointer;">
              <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
              <span>Registrar y Generar Orden</span>
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- =========================================================================
       4. MODAL 2: ATENCIÓN TÉCNICA Y DIAGNÓSTICO DE LABORATORIO
       ========================================================================= -->
  <div class="modal-backdrop" id="modal-actualizar-tecnico">
    <div class="modal-container modal-md">
      <div class="modal-header">
        <h3><i class="ph-bold ph-wrench"></i> Atención Técnica: <span id="modal-tec-codigo" style="color: var(--gold-500); font-family: var(--font-mono);">ORD-2026-XXXX</span></h3>
        <button type="button" class="btn-close-modal">&times;</button>
      </div>

      <form id="form-actualizar-tecnico">
        <div class="modal-body">
          <input type="hidden" id="modal-tec-orden-id">

          <div style="background: var(--slate-100); padding: 0.85rem 1rem; border-radius: var(--radius-md); margin-bottom: 1rem; font-size: 0.88rem;">
            <div><strong>Cliente:</strong> <span id="modal-tec-cliente">-</span></div>
            <div><strong>Equipo:</strong> <span id="modal-tec-equipo">-</span></div>
            <div><strong>Falla Reportada:</strong> <span id="modal-tec-motivo" style="color: var(--danger);">-</span></div>
          </div>

          <!-- Botón de Acción Rápida: Marcar como REPARADO -->
          <div style="background: var(--success-bg); border: 1.5px dashed var(--success); padding: 0.75rem 1rem; border-radius: var(--radius-md); margin-bottom: 1.25rem; display: flex; align-items: center; justify-content: space-between;">
            <div>
              <strong style="color: #065f46; font-size: 0.9rem;">¿Reparación completada?</strong>
              <div style="font-size: 0.78rem; color: #047857;">Cambia el estado a REPARADO para habilitar el cobro en mostrador.</div>
            </div>
            <button type="button" id="btn-quick-marcar-reparado" class="btn btn-success btn-sm">
              <i class="ph-bold ph-check-circle"></i> Marcar REPARADO
            </button>
          </div>

          <div class="form-group" style="margin-bottom: 1rem;">
            <label for="modal-tec-estado">Estado Actual del Equipo <span class="required">*</span></label>
            <select id="modal-tec-estado" class="form-control" required>
              <option value="RECEPCIONADO">RECEPCIONADO (Ingreso reciente)</option>
              <option value="EN_DIAGNOSTICO">EN DIAGNÓSTICO (En banco de pruebas)</option>
              <option value="ESPERA_APROBACION">ESPERA DE APROBACIÓN DE PRESUPUESTO</option>
              <option value="ESPERA_REPUESTOS">ESPERA DE REPUESTOS / COMPONENTES</option>
              <option value="EN_REPARACION">EN REPARACIÓN (Proceso activo)</option>
              <option value="REPARADO">REPARADO (Listo para entrega al cliente)</option>
              <option value="NO_REPARABLE">NO REPARABLE / DEVUELTO</option>
              <option value="ENTREGADO">ENTREGADO AL CLIENTE</option>
            </select>
          </div>

          <div class="form-group" style="margin-bottom: 1rem;">
            <label for="modal-tec-diagnostico">Informe de Diagnóstico Técnico</label>
            <textarea id="modal-tec-diagnostico" class="form-control" placeholder="Fallas encontradas: corto en línea de carga, pasta térmica cristalizada, sectores dañados en SSD..."></textarea>
          </div>

          <div class="form-group">
            <label for="modal-tec-solucion">Solución Técnica Aplicada / Trabajo Realizado</label>
            <textarea id="modal-tec-solucion" class="form-control" placeholder="Trabajo realizado: reparación de mainboard componente, cambio de pasta térmica Artic MX-4, mantenimiento integral..."></textarea>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-close-modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">
            <i class="ph-bold ph-floppy-disk"></i> Guardar Diagnóstico
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- =========================================================================
       5. MODAL 3: COBRO Y EMISIÓN DE RECIBO A5 (PASO ÚNICO DE ENTREGA)
       ========================================================================= -->
  <div class="modal-backdrop" id="modal-cobro-recibo">
    <div class="modal-container modal-receipt">
      <div class="modal-header" style="background: linear-gradient(135deg, #065f46, var(--navy-800));">
        <h3><i class="ph-bold ph-receipt"></i> Cobro y Emisión de Recibo A5 <span id="cobro-correlativo-preview" style="color: var(--gold-500); font-family: var(--font-mono);">Nº 000402</span></h3>
        <button type="button" class="btn-close-modal">&times;</button>
      </div>

      <form id="form-cobro-recibo">
        <div class="modal-body">
          <input type="hidden" id="cobro-orden-id">
          <input type="hidden" id="cobro-recibo-id">

          <div style="background: var(--slate-100); padding: 0.85rem 1rem; border-radius: var(--radius-md); margin-bottom: 1.25rem; font-size: 0.88rem;">
            <div><strong>Cliente:</strong> <span id="cobro-cliente-info">-</span></div>
            <div><strong>Equipo:</strong> <span id="cobro-equipo-info">-</span></div>
          </div>

          <div class="form-group" style="margin-bottom: 1rem;">
            <label for="cobro-concepto">POR CONCEPTO DE: <span class="required">*</span></label>
            <textarea id="cobro-concepto" class="form-control" required style="min-height: 60px;" placeholder="Descripción del servicio para el comprobante físico A5"></textarea>
          </div>

          <div class="form-grid">
            <div class="col-4 form-group">
              <label for="cobro-monto-total" style="display:flex; justify-content:space-between;">
                  <span>Monto Total (S/.) <span class="required">*</span></span>
                  <span id="cobro-total-usd" style="color:var(--success); font-weight:600; font-size:0.9rem;">$ 0.00</span>
              </label>
              <input type="number" step="0.50" min="1" id="cobro-monto-total" class="form-control" required style="font-weight: 700; font-size: 1.15rem; color: var(--navy-900);">
            </div>

            <div class="col-4 form-group">
              <label for="cobro-monto-a-cuenta" style="display:flex; justify-content:space-between;">
                  <span>A Cuenta Previo (S/.)</span>
                  <span id="cobro-acuenta-usd" style="color:var(--success); font-weight:600; font-size:0.9rem;">$ 0.00</span>
              </label>
              <input type="number" step="0.50" min="0" id="cobro-monto-a-cuenta" class="form-control" value="0.00">
            </div>

            <div class="col-4 form-group">
              <label for="cobro-monto-saldo" style="display:flex; justify-content:space-between;">
                  <span>Saldo a Pagar (S/.)</span>
                  <span id="cobro-saldo-usd" style="color:var(--danger); font-weight:600; font-size:0.9rem;">$ 0.00</span>
              </label>
              <input type="text" id="cobro-monto-saldo" class="form-control" readonly style="background: #fee2e2; font-weight: 800; font-size: 1.15rem; color: var(--danger);">
            </div>

            <div class="col-12 form-group">
              <label for="cobro-monto-letras">Total en Letras (Autogenerado FASE 3)</label>
              <input type="text" id="cobro-monto-letras" class="form-control" readonly style="background: var(--slate-100); font-size: 0.85rem; font-weight: 700; color: var(--navy-800);">
            </div>

            <div class="col-6 form-group">
              <label for="cobro-metodo-pago">Método de Cancelación</label>
              <select id="cobro-metodo-pago" class="form-control">
                <option value="EFECTIVO" selected>Efectivo</option>
                <option value="YAPE">Yape</option>
                <option value="PLIN">Plin</option>
                <option value="TRANSFERENCIA">Transferencia Bancaria</option>
                <option value="TARJETA">Tarjeta Débito/Crédito</option>
              </select>
            </div>

            <div class="col-6 form-group">
              <label for="cobro-dias-garantia">Garantía Legal</label>
              <input type="text" id="cobro-dias-garantia" class="form-control" value="90 Días Calendario (Ley Nº 29571)" readonly style="background: var(--slate-100);">
            </div>

            <div class="col-12" style="margin-top: 0.5rem;">
              <label style="display: flex; align-items: center; gap: 0.5rem; font-weight: 600; cursor: pointer;">
                <input type="checkbox" id="cobro-marcar-entregado" checked style="width: 18px; height: 18px;">
                <span>Marcar orden automáticamente como <strong>ENTREGADO</strong> y activar los 90 días de garantía</span>
              </label>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-close-modal">Cancelar</button>
          <button type="submit" class="btn btn-success" style="padding: 0.75rem 1.75rem;">
            <i class="ph-bold ph-printer"></i> Confirmar Cobro e Imprimir Recibo A5
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Contenedor para Notificaciones Toast -->
  <div id="toast-container"></div>

  <script>
    // Inyectar datos de sesión globalmente para la API de JS
    window.currentUser = {
      id: <?= json_encode($userId) ?>,
      nombre: <?= json_encode($userName) ?>,
      rol: <?= json_encode($userRole) ?>
    };
    window.MAKPC_API_BASE = <?= json_encode(ROOT_URL . 'api') ?>;
    window.MAKPC_ROOT_URL = <?= json_encode(ROOT_URL) ?>;

  </script>
  <!-- Scripts -->
  <script src="js/api.js"></script>
  <script src="js/utils.js"></script>
  <script src="js/currency.js?v=1.1"></script>
  <script src="js/app.js?v=<?= file_exists(__DIR__ . '/js/app.js') ? filemtime(__DIR__ . '/js/app.js') : '2.7' ?>"></script>
  <script src="js/app.js?v=2.9"></script>
</body>
</html>
