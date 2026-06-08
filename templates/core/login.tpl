<!-- Begin Login.tpl -->
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Cite CRM Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
  >

  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
  >

  <!-- App theme overrides -->
  <link href="css/default.css" rel="stylesheet" type="text/css">

  <style>
    {literal}

    :root {
      --login-primary: #0f5f9f;
      --login-primary-dark: #123a5f;
      --login-accent: #16a085;
      --login-ink: #172033;
      --login-muted: #687385;
      --login-line: #dbe3ed;
      --login-bg: #eef3f7;
    }

    body {
      min-height: 100vh;
      background:
        linear-gradient(
          135deg,
          rgba(15, 95, 159, 0.12),
          rgba(22, 160, 133, 0.10)
        ),
        var(--login-bg);

      color: var(--login-ink);
      font-family: Arial, Helvetica, sans-serif;
    }

    .login-page {
      min-height: 100vh;
      padding: 2rem 1rem;
    }

    .login-shell {
      width: 100%;
      max-width: 860px;
    }

    .login-card {
      display: grid;
      border: 0;
      border-radius: 0.75rem;
      background: #ffffff;
      box-shadow: 0 1.5rem 3.5rem rgba(23, 32, 51, 0.18);
      overflow: hidden;
    }

    .login-brand-panel {
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      gap: 2rem;
      min-height: 100%;
      padding: 2rem;

      background:
        linear-gradient(
          155deg,
          rgba(18, 58, 95, 0.98),
          rgba(15, 95, 159, 0.92)
        ),
        var(--login-primary-dark);

      color: #ffffff;
    }

    .login-brand {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }

    .login-logo-wrap {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 11.5rem;
      min-height: 5.5rem;
      padding: 0.75rem;
      border-radius: 0.5rem;
      background: rgba(255,255,255,0.94);
    }

    .login-logo {
      display: block;
      max-width: 100%;
      max-height: 4rem;
      width: auto;
      height: auto;
      object-fit: contain;
    }

    .login-company-name {
      margin: 0;
      max-width: 16rem;
      color: #ffffff;
      font-size: 1.45rem;
      font-weight: 700;
      line-height: 1.2;
    }

    .login-brand-note {
      margin: 0;
      color: rgba(255,255,255,0.76);
      font-size: 0.9rem;
      line-height: 1.5;
    }

    .login-form-panel {
      padding: 2rem;
    }

    .login-title {
      margin-bottom: 1.5rem;
    }

    .login-title h1 {
      margin: 0;
      color: var(--login-ink);
      font-size: 1.55rem;
      font-weight: 700;
      line-height: 1.2;
    }

    .login-title p {
      margin: 0.35rem 0 0;
      color: var(--login-muted);
      font-size: 0.95rem;
    }

    .form-label {
      color: #2e3948;
      font-size: 0.88rem;
      font-weight: 700;
    }

    /* FORM CONTROLS */
    .form-control {
      height: 48px;
      border-radius: 0.5rem;
      border-color: var(--login-line);
      color: var(--login-ink);
      font-size: 0.98rem;
      box-shadow: none;
    }

    .form-control:focus {
      border-color: var(--login-primary);
      box-shadow: 0 0 0 0.22rem rgba(15,95,159,0.14);
    }

    /* CAPTCHA SAME SIZE AS INPUT */
    .captcha-styled {
      width: 100%;
      height: 48px;
      border: 1px solid var(--login-line);
      border-radius: 0.5rem;
      background-color: #fff;
      overflow: hidden;
      position: relative;

      display: flex;
      align-items: center;

      transition:
        border-color 0.15s ease-in-out,
        box-shadow 0.15s ease-in-out;
    }

    .captcha-styled:focus-within {
      border-color: var(--login-primary);
      box-shadow: 0 0 0 0.22rem rgba(15,95,159,0.14);
    }

    /* CENTER CAPTCHA */
    .g-recaptcha,
    .cf-turnstile {
      width: 100%;
      min-width: 100%;
      height: 100%;

      display: flex;
      align-items: center;
    }

    /* SCALE GOOGLE RECAPTCHA */
    .g-recaptcha {
      transform: scale(0.77);
      transform-origin: center center;
    }

    .cf-turnstile iframe,
    .g-recaptcha iframe {
      display: block;
      width: 100% !important;
      max-width: none;
    }

    .cf-turnstile > div {
      width: 100% !important;
    }

    .forgot-link {
      color: var(--login-primary);
      font-weight: 700;
    }

    .forgot-link:hover,
    .forgot-link:focus {
      color: var(--login-primary-dark);
    }

    .btn-primary {
      height: 48px;
      border-radius: 0.5rem;
      border: 0;
      background: var(--login-primary);
      font-weight: 600;
      box-shadow: 0 0.75rem 1.5rem rgba(15,95,159,0.24);
    }

    .btn-primary:hover,
    .btn-primary:focus {
      background: var(--login-primary-dark);
      box-shadow: 0 0.85rem 1.65rem rgba(18,58,95,0.28);
    }

    .alert-danger {
      border: 0;
      border-left: 4px solid #c73b3b;
      border-radius: 0.5rem;
      background: #fff1f1;
      color: #7a1f1f;
    }

    .footer-text {
      text-align: center;
      margin-top: 1.25rem;
      font-size: 0.86rem;
      color: var(--login-muted);
    }

    .footer-text a {
      color: var(--login-primary);
      font-weight: 700;
      text-decoration: none;
    }

    @media (min-width: 768px) {
      .login-card {
        grid-template-columns:
          minmax(260px, 0.95fr)
          minmax(360px, 1.25fr);
      }
    }

    @media (max-width: 767.98px) {

      .login-page {
        padding: 1rem;
      }

      .login-brand-panel {
        padding: 1.5rem;
      }

      .login-logo-wrap {
        width: 100%;
        max-width: 13rem;
      }

      .login-form-panel {
        padding: 1.5rem;
      }

      /* Mobile recaptcha scale */
      .g-recaptcha {
        transform: scale(0.72);
      }
    }

    {/literal}
  </style>

  {if $captcha_enabled == 1 && $captcha_provider == 'turnstile' && $captcha_site_key != ""}
    <script
      src="https://challenges.cloudflare.com/turnstile/v0/api.js"
      async
      defer>
    </script>

  {elseif $captcha_enabled == 1 && $captcha_provider == 'recaptcha' && $captcha_site_key != ""}

    <script
      src="https://www.google.com/recaptcha/api.js"
      async
      defer>
    </script>

  {/if}

</head>

<body>

<main class="login-page d-flex align-items-center justify-content-center">

  <div class="login-shell">

    <div class="card login-card">

      <!-- LEFT PANEL -->
      <div class="login-brand-panel">

        <div class="login-brand">

          {if $company_logo_url|default:'' != ''}

            <div class="login-logo-wrap">

              <img
                class="login-logo"
                src="{$company_logo_url}"
                alt="{$company_name|default:'Cite CRM'|escape}"
              >

            </div>

          {/if}

          <p class="login-company-name">
            {$company_name|default:'Cite CRM'|escape}
          </p>

        </div>

        <p class="login-brand-note">
          Customer relationship management
        </p>

      </div>

      <!-- RIGHT PANEL -->
      <div class="login-form-panel">

        <div class="login-title">
          <h1>Login</h1>
          <p>Welcome back.</p>
        </div>

        <form action="index.php" method="POST" novalidate>

          <!-- USERNAME -->
          <div class="mb-3">

            <label for="login" class="form-label">
              Username
            </label>

            <input
              type="text"
              id="login"
              name="login"
              class="form-control"
              autocomplete="username"
              required
            >

          </div>

          <!-- PASSWORD -->
          <div class="mb-3">

            <label for="password" class="form-label">
              Password
            </label>

            <input
              type="password"
              id="password"
              name="password"
              class="form-control"
              autocomplete="current-password"
              required
            >

          </div>

          <!-- TURNSTILE -->
          {if $captcha_enabled == 1
            && $captcha_provider == 'turnstile'
            && $captcha_site_key != ""}

            <div class="mb-3">

              <label class="form-label">
                Verification
              </label>

              <div class="captcha-styled">

                <div
                  class="cf-turnstile"
                  data-sitekey="{$captcha_site_key|escape}"
                  data-size="flexible">
                </div>

              </div>

            </div>

          <!-- RECAPTCHA -->
          {elseif $captcha_enabled == 1
            && $captcha_provider == 'recaptcha'
            && $captcha_site_key != ""}

            <div class="mb-3">

              <label class="form-label">
                Verification
              </label>

              <div class="captcha-styled">

                <div
                  class="g-recaptcha"
                  data-sitekey="{$captcha_site_key|escape}">
                </div>

              </div>

            </div>

          {/if}

          <!-- FORGOT -->
          <div class="d-flex justify-content-end mb-3">

            <a
              class="forgot-link btn btn-link btn-sm text-decoration-none p-0"
              href="forgot_password.php"
            >
              <i class="bi bi-key"></i>
              Forgot password?
            </a>

          </div>

          <!-- ERROR -->
          {if $error_msg != ""}

            <div class="alert alert-danger py-2 small" role="alert">
              {$error_msg}
            </div>

          {/if}

          <!-- SUBMIT -->
          <div class="d-grid">

            <button
              type="submit"
              name="submit"
              class="btn btn-primary"
            >
              <i
                class="bi bi-box-arrow-in-right me-1"
                aria-hidden="true">
              </i>

              Login
            </button>

          </div>

        </form>

      </div>

    </div>

    <!-- FOOTER -->
    <div class="footer-text">

      © 2005 - {$smarty.now|date_format:"%Y"} Cite CRM

      <a
        href="http://www.incitecrm.com"
        target="_blank"
      >
        www.incitecrm.com
      </a>

      — All rights reserved.

    </div>

  </div>

</main>

<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
