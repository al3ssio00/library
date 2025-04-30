<?php

include "connessione.php";
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Check se l'utente è già esistente
    $check = "SELECT * FROM utenti WHERE username = '$username'";
    $result = $conn->query($check);

    if ($result->num_rows > 0) { // Controllo righe tabella username
        $Mregistrazione = FALSE;
    } else {
        // Query per inserire utente nel DB
        $sqlInsert = "INSERT INTO utenti (username, password, permessi) VALUES ('$username', '$password', '1')";

        if($conn->query($sqlInsert) === TRUE) {
            $Mregistrazione = TRUE;
        } else {
            $Mregistrazione = FALSE;
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign-Up</title>
  <link rel="stylesheet" href="stile.css">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

  <section class="vh-100 gradient-custom">
    <div class="container py-5 h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-12 col-md-8 col-lg-6 col-xl-5">
          <div class="card bg-dark text-white" style="border-radius: 1rem;">
            <div class="card-body p-5 text-center">

              <div class="mb-md-5 mt-md-4 pb-5">

                <h2 class="fw-bold mb-2 text-uppercase">Sign-Up</h2>
                <p class="text-white-50 mb-5">Please enter your username and password!</p>

                <form method="POST" action="">
                  <div data-mdb-input-init class="form-outline form-white mb-4">
                    <input type="text" id="typeEmailX" name="username" class="form-control form-control-lg" />
                    <label class="form-label" for="typeEmailX">Username</label>
                  </div>

                  <div data-mdb-input-init class="form-outline form-white mb-5">
                    <input type="password" id="typePasswordX" name="password" class="form-control form-control-lg" />
                    <label class="form-label" for="typePasswordX">Password</label>
                  </div>

                  <button data-mdb-button-init data-mdb-ripple-init class="btn btn-outline-light btn-lg px-5" type="submit">
                    Registrati
                  </button>

                  <?php if (isset($Mregistrazione) && $Mregistrazione): ?>
                    <div id="alert" class="alert alert-success" role="alert">
                      ✅ Registrazione effettuata con successo stai per essere indirizzato 
                      verso il login form!
                      <script>
                        setTimeout(function() {
                        window.location.href = 'login.php';
                        }, 4000);
                        </script>
                    </div>
                  <?php elseif (isset($Mregistrazione)): ?>
                    <div id="alert" class="alert alert-danger" role="alert">
                      ❌ Errore durante la registrazione o Username probabilmente esistente!
                    </div>
                  <?php endif; ?>
                </form>
                <div>
                    <p class="mb-0">Already have an account? <a href="login.php" class="text-white-50 fw-bold">Login</a>
                    </p>
                </div>

              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <script>
    setTimeout(function () {
      let alertBox = document.getElementById("alert");
      if (alertBox) alertBox.style.display = "none";
    }, 4000);
  </script>

</body>
</html>
