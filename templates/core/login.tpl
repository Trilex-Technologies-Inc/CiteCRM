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

    <!-- App theme overrides -->
    <link href="css/default.css" rel="stylesheet" type="text/css">
  </head>
  <body class="bg-light d-flex align-items-center min-vh-100">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
          <div class="text-center mb-4">
            <img src="images/cite_crm.jpg" alt="Cite CRM" class="img-fluid mb-2" style="max-height: 80px;">
          </div>

          <div class="card shadow-sm">
            <div class="card-header bg-primary text-white text-center">
              <h1 class="h5 mb-0">Login</h1>
            </div>
            <div class="card-body">
              <form action="index.php" method="POST" novalidate>
                <div class="mb-3">
                  <label for="login" class="form-label">Login</label>
                  <input
                    type="text"
                    id="login"
                    name="login"
                    class="form-control"
                    autocomplete="username"
                    required
                  >
                </div>

                <div class="mb-3">
                  <label for="password" class="form-label">Password</label>
                  <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-control"
                    autocomplete="current-password"
                    required
                  >
                </div>

                {if $error_msg != ""}
                  <div class="alert alert-danger py-2 small" role="alert">
                    {$error_msg}
                  </div>
                {/if}

                <div class="d-grid">
                  <button type="submit" name="submit" class="btn btn-primary">
                    Login
                  </button>
                </div>
              </form>
            </div>
          </div>

          <div class="text-center text-muted small mt-3">
            Copyright 2005 &copy; Cite CRM
            <a href="http://www.incitecrm.com" target="new">www.incitecrm.com</a>
            &mdash; All rights reserved.
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap Bundle JS (with Popper) -->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>
  </body>
</html>