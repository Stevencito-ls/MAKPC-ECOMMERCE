<?php
/**
 * @var string|null $error
 * @var string $usuario_previo
 * @var string $title
 * @var bool $bloqueado
 */
$flash = getFlash();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($title ?? 'Acceso Seguro al Sistema | MAKPC Enterprises') ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= asset('css/app.css') ?>?v=<?= file_exists(__DIR__ . '/../../assets/css/app.css') ? filemtime(__DIR__ . '/../../assets/css/app.css') : '2.0' ?>">
  <link rel="icon" type="image/png" href="<?= asset('img/logo.png') ?>">
  <style>
    body.auth-page {
      min-height: 100vh;
      background: radial-gradient(circle at 15% 25%, #1c265a 0%, #161D45 50%, #0a0e23 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1.5rem;
      margin: 0;
      color: #FAFAFA;
      font-family: 'Inter', system-ui, -apple-system, sans-serif;
      position: relative;
      overflow-x: hidden;
    }

    body.auth-page::before {
      content: "";
      position: absolute;
      top: -10%;
      right: -10%;
      width: 500px;
      height: 500px;
      background: radial-gradient(circle, rgba(5, 169, 233, 0.12) 0%, transparent 70%);
      border-radius: 50%;
      pointer-events: none;
    }

    body.auth-page::after {
      content: "";
      position: absolute;
      bottom: -15%;
      left: -10%;
      width: 550px;
      height: 550px;
      background: radial-gradient(circle, rgba(252, 200, 39, 0.08) 0%, transparent 70%);
      border-radius: 50%;
      pointer-events: none;
    }

    .auth-card {
      width: 100%;
      max-width: 450px;
      background: rgba(22, 29, 69, 0.75);
      border: 1px solid rgba(255, 255, 255, 0.14);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border-radius: 20px;
      padding: 2.6rem 2.2rem;
      box-shadow: 0 25px 60px rgba(0, 0, 0, 0.55), 0 0 0 1px rgba(255, 255, 255, 0.05);
      position: relative;
      z-index: 10;
      animation: authCardIn 0.35s cubic-bezier(0.16, 1, 0.3, 1);
    }

    @keyframes authCardIn {
      from { opacity: 0; transform: translateY(12px) scale(0.97); }
      to { opacity: 1; transform: translateY(0) scale(1); }
    }

    .auth-brand {
      text-align: center;
      margin-bottom: 2rem;
    }

    .auth-brand img {
      height: 58px;
      width: auto;
      margin: 0 auto 0.85rem auto;
      filter: drop-shadow(0 4px 12px rgba(0,0,0,0.4));
    }

    .auth-brand h1 {
      font-size: 1.45rem;
      font-weight: 800;
      letter-spacing: -0.5px;
      margin: 0;
      color: #ffffff;
    }

    .auth-brand h1 span {
      color: var(--color-yellow);
    }

    .auth-brand p {
      font-size: 0.82rem;
      color: rgba(255, 255, 255, 0.65);
      margin-top: 0.35rem;
    }

    .auth-security-badge {
      display: inline-flex;
      align-items: center;
      gap: 0.4rem;
      background: rgba(5, 169, 233, 0.12);
      border: 1px solid rgba(5, 169, 233, 0.3);
      border-radius: 20px;
      padding: 0.25rem 0.75rem;
      font-size: 0.68rem;
      font-weight: 700;
      color: var(--color-celeste);
      margin-top: 0.75rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .auth-security-badge svg {
      width: 12px;
      height: 12px;
      fill: currentColor;
    }

    .auth-form-group {
      margin-bottom: 1.25rem;
    }

    .auth-form-group label {
      display: block;
      font-size: 0.82rem;
      font-weight: 600;
      margin-bottom: 0.4rem;
      color: rgba(255, 255, 255, 0.9);
      letter-spacing: 0.2px;
    }

    .auth-input-wrapper {
      position: relative;
      display: flex;
      align-items: center;
    }

    .auth-input-icon {
      position: absolute;
      left: 1rem;
      display: flex;
      align-items: center;
      justify-content: center;
      color: rgba(255, 255, 255, 0.45);
      pointer-events: none;
    }

    .auth-input-icon svg {
      width: 18px;
      height: 18px;
      fill: currentColor;
    }

    .auth-input {
      width: 100%;
      background: rgba(255, 255, 255, 0.07);
      border: 1px solid rgba(255, 255, 255, 0.16);
      border-radius: 10px;
      padding: 0.75rem 2.8rem 0.75rem 2.8rem;
      color: #ffffff;
      font-size: 0.95rem;
      outline: none;
      transition: var(--transition);
      box-sizing: border-box;
    }

    .auth-input:focus {
      background: rgba(255, 255, 255, 0.12);
      border-color: var(--color-celeste);
      box-shadow: 0 0 0 3px rgba(5, 169, 233, 0.25);
    }

    .auth-input::placeholder {
      color: rgba(255, 255, 255, 0.35);
    }

    .auth-input:disabled {
      opacity: 0.5;
      cursor: not-allowed;
    }

    .auth-toggle-pwd {
      position: absolute;
      right: 0.9rem;
      background: transparent;
      border: none;
      color: rgba(255, 255, 255, 0.5);
      cursor: pointer;
      padding: 0.25rem;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: color 0.2s ease;
    }

    .auth-toggle-pwd:hover {
      color: #ffffff;
    }

    .auth-toggle-pwd svg {
      width: 18px;
      height: 18px;
      fill: currentColor;
    }

    .btn-auth-submit {
      width: 100%;
      background: var(--color-yellow);
      color: var(--color-blue);
      border: none;
      border-radius: 10px;
      padding: 0.85rem;
      font-size: 0.95rem;
      font-weight: 800;
      cursor: pointer;
      box-shadow: 0 4px 15px rgba(252, 200, 39, 0.35);
      transition: var(--transition);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      margin-top: 1.5rem;
    }

    .btn-auth-submit:hover:not(:disabled) {
      background: var(--color-yellow-hover);
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(252, 200, 39, 0.45);
    }

    .btn-auth-submit:disabled {
      opacity: 0.6;
      cursor: not-allowed;
      transform: none;
      box-shadow: none;
    }

    .btn-auth-submit svg {
      width: 16px;
      height: 16px;
      fill: currentColor;
    }

    .auth-alert {
      display: flex;
      align-items: flex-start;
      gap: 0.65rem;
      padding: 0.85rem 1rem;
      border-radius: 10px;
      font-size: 0.84rem;
      margin-bottom: 1.25rem;
      line-height: 1.4;
    }

    .auth-alert-danger {
      background: rgba(239, 68, 68, 0.15);
      border: 1px solid rgba(239, 68, 68, 0.35);
      color: #fca5a5;
    }

    .auth-alert-success {
      background: rgba(16, 185, 129, 0.15);
      border: 1px solid rgba(16, 185, 129, 0.35);
      color: #6ee7b7;
    }

    .auth-alert-info {
      background: rgba(5, 169, 233, 0.15);
      border: 1px solid rgba(5, 169, 233, 0.35);
      color: #7dd3fc;
    }

    .auth-alert svg {
      width: 18px;
      height: 18px;
      fill: currentColor;
      flex-shrink: 0;
      margin-top: 2px;
    }

    .auth-footer-links {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-top: 1.75rem;
      padding-top: 1.25rem;
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      font-size: 0.82rem;
    }

    .auth-back-link {
      color: rgba(255, 255, 255, 0.7);
      text-decoration: none;
      transition: var(--transition);
      display: inline-flex;
      align-items: center;
      gap: 0.4rem;
    }

    .auth-back-link:hover {
      color: var(--color-celeste);
    }

    .auth-back-link svg {
      width: 14px;
      height: 14px;
      fill: currentColor;
    }

    .auth-policy-tag {
      font-size: 0.72rem;
      color: rgba(255, 255, 255, 0.45);
    }
  </style>
</head>
<body class="auth-page">

  <div class="auth-card">
    <div class="auth-brand">
      <a href="<?= url() ?>">
        <img src="<?= asset('img/logo.png') ?>" alt="Logo MAKPC">
      </a>
      <h1>MAK<span>PC</span> ACCESS</h1>
      <p>Gestión Comercial, Taller & Administración</p>
      <div class="auth-security-badge">
        <svg viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/></svg>
        <span>Autenticación Segura SSL / CSRF</span>
      </div>
    </div>

    <?php if ($flash): ?>
      <div class="auth-alert auth-alert-<?= e($flash['type'] === 'danger' ? 'danger' : ($flash['type'] === 'success' ? 'success' : 'info')) ?>">
        <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
        <span><?= e($flash['message']) ?></span>
      </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
      <div class="auth-alert auth-alert-danger">
        <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
        <span><?= e($error) ?></span>
      </div>
    <?php endif; ?>

    <form action="<?= url('login') ?>" method="POST" id="loginForm">
      <?= csrf_field() ?>

      <div class="auth-form-group">
        <label for="usuario">Usuario del Sistema</label>
        <div class="auth-input-wrapper">
          <span class="auth-input-icon">
            <svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
          </span>
          <input 
            type="text" 
            name="usuario" 
            id="usuario" 
            class="auth-input" 
            placeholder="Ingrese su nombre de usuario" 
            required 
            autofocus 
            value="<?= e($usuario_previo ?? '') ?>"
            <?= !empty($bloqueado) ? 'disabled' : '' ?>
            autocomplete="username"
          >
        </div>
      </div>

      <div class="auth-form-group">
        <label for="password">Contraseña de Seguridad</label>
        <div class="auth-input-wrapper">
          <span class="auth-input-icon">
            <svg viewBox="0 0 24 24"><path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/></svg>
          </span>
          <input 
            type="password" 
            name="password" 
            id="password" 
            class="auth-input" 
            placeholder="••••••••••••" 
            required
            <?= !empty($bloqueado) ? 'disabled' : '' ?>
            autocomplete="current-password"
          >
          <button type="button" class="auth-toggle-pwd" id="togglePassword" aria-label="Mostrar u ocultar contraseña">
            <svg id="eyeIcon" viewBox="0 0 24 24"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
          </button>
        </div>
      </div>

      <button type="submit" class="btn-auth-submit" <?= !empty($bloqueado) ? 'disabled' : '' ?>>
        <span><?= !empty($bloqueado) ? 'Acceso Bloqueado Temporalmente' : 'Ingresar al Sistema' ?></span>
        <svg viewBox="0 0 24 24"><path d="M5 13h11.86l-5.43 5.43 1.42 1.42L21.14 12l-8.29-8.29-1.42 1.42 5.43 5.43H5v2z"/></svg>
      </button>
    </form>

    <div class="auth-footer-links">
      <a href="<?= url() ?>" class="auth-back-link">
        <svg viewBox="0 0 24 24"><path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/></svg>
        <span>Volver a la Tienda</span>
      </a>
      <span class="auth-policy-tag">MAKPC Security v<?= APP_VERSION ?></span>
    </div>
  </div>

  <script>
    // Mostrar / Ocultar Contraseña de forma interactiva
    const toggleBtn = document.getElementById('togglePassword');
    const pwdInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');
    
    if (toggleBtn && pwdInput) {
      toggleBtn.addEventListener('click', function() {
        if (pwdInput.type === 'password') {
          pwdInput.type = 'text';
          eyeIcon.innerHTML = '<path d="M12 7c2.76 0 5 2.24 5 5 0 .65-.13 1.26-.36 1.83l2.92 2.92c1.51-1.26 2.7-2.89 3.44-4.75-1.73-4.39-6-7.5-11-7.5-1.4 0-2.74.25-3.98.7l2.16 2.16C10.74 7.13 11.35 7 12 7zM2 4.27l2.28 2.28.46.46C3.08 8.3 1.78 10.02 1 12c1.73 4.39 6 7.5 11 7.5 1.55 0 3.03-.3 4.38-.84l.42.42L19.73 22 21 20.73 3.27 3 2 4.27zM7.53 9.8l1.55 1.55c-.05.21-.08.43-.08.65 0 1.66 1.34 3 3 3 .22 0 .44-.03.65-.08l1.55 1.55c-.67.33-1.41.53-2.2.53-2.76 0-5-2.24-5-5 0-.79.2-1.53.53-2.2zm4.31-.78l3.15 3.15.02-.16c0-1.66-1.34-3-3-3l-.17.01z"/>';
        } else {
          pwdInput.type = 'password';
          eyeIcon.innerHTML = '<path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>';
        }
      });
    }
  </script>
</body>
</html>
