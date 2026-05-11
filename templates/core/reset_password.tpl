<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Cite CRM - Reset Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    >
    <link href="css/default.css" rel="stylesheet" type="text/css">
  </head>
  <body class="bg-light d-flex align-items-center min-vh-100">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-7 col-lg-5">
          <div class="text-center mb-4">
            <img src="images/cite_crm.jpg" alt="Cite CRM" class="img-fluid mb-2" style="max-height: 80px;">
          </div>

          <div class="card shadow-sm">
            <div class="card-header bg-primary text-white text-center">
              <h1 class="h5 mb-0">Reset Password</h1>
            </div>
            <div class="card-body">
              {if $msg != ""}
                <div class="alert alert-success py-2 small" role="alert">
                  {$msg} <a class="alert-link" href="login.php">Go to login</a>
                </div>
              {/if}
              {if $error_msg != ""}
                <div class="alert alert-danger py-2 small" role="alert">{$error_msg}</div>
              {/if}

              {if $valid_link == 1 && $msg == ""}
                <form action="reset_password.php" method="POST" novalidate>
                  <input type="hidden" name="token" value="{$token|escape}">
                  <div class="mb-3">
                    <label for="new_password" class="form-label">New Password</label>
                    <input type="password" id="new_password" name="new_password" class="form-control" autocomplete="new-password" required>
                  </div>
                  <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" autocomplete="new-password" required>
                  </div>
                  <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Update password</button>
                  </div>
                </form>
              {elseif $msg == ""}
                <p class="small text-muted mb-0">This link is not valid. Please request a new reset link.</p>
              {/if}
            </div>
            <div class="card-footer text-center">
              <a class="small text-decoration-none" href="forgot_password.php">Request a new reset link</a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>

