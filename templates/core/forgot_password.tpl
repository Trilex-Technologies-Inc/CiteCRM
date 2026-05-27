<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Cite CRM - Forgot Password</title>
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
         
          <div class="card shadow-sm">
            <div class="card-header bg-primary text-white text-center">
              <h1 class="h5 mb-0">Forgot Password</h1>
            </div>
            <div class="card-body">
              <p class="small text-muted mb-3">Enter your login or email address and we will send a reset link.</p>

              {if $msg != ""}
                <div class="alert alert-success py-2 small" role="alert">{$msg}</div>
              {/if}
              {if $error_msg != ""}
                <div class="alert alert-danger py-2 small" role="alert">{$error_msg}</div>
              {/if}

              <form action="forgot_password.php" method="POST" novalidate>
                <div class="mb-3">
                  <label for="login_or_email" class="form-label">Login or Email</label>
                  <input type="text" id="login_or_email" name="login_or_email" class="form-control" autocomplete="username" required>
                </div>

                <div class="d-grid">
                  <button type="submit" class="btn btn-primary">Send reset link</button>
                </div>
              </form>
            </div>
            <div class="card-footer text-center">
              <a class="small text-decoration-none" href="login.php">Back to login</a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>

