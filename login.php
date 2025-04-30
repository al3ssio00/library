<?php
include "connessione.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $check = "SELECT * FROM utenti WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($check);

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();            
        session_start();
        
        // Imposta le variabili di sessione
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['permessi'] = $row['permessi'];
        
        // variabile di sessione per il ruolo
        if ($row['permessi'] == 1) {
            $_SESSION['role'] = 'user';  // Utente normale
        } elseif ($row['permessi'] == 2) {
            $_SESSION['role'] = 'admin'; // Admin
        }
        
        // Reindirizza in base al permesso
        if ($row['permessi'] == 1) {
            header("Location: index.php"); // Utente normale
        } else {
            header("Location: annullo.php"); // Admin
        }
        exit; // Fermiamo l'esecuzione dopo il redirect
    } else {
        $Mlogin = FALSE;
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
              <h2 class="fw-bold mb-2 text-uppercase">Login</h2>
              <p class="text-white-50 mb-5">Please enter your username and password!</p>

              <form method="POST" action="">
                <div data-mdb-input-init class="form-outline form-white mb-4">
                  <input type="text" id="text" name="username" class="form-control form-control-lg" />
                  <label class="form-label" for="text">Username</label>
                </div>
                
                <div data-mdb-input-init class="form-outline form-white mb-5">
                  <input type="password" id="typePasswordX" name="password" class="form-control form-control-lg" />
                  <label class="form-label" for="typePasswordX">Password</label>
                </div>
                
                <button data-mdb-button-init data-mdb-ripple-init class="btn btn-outline-light btn-lg px-5" type="submit">Login</button>
                
                <?php if ($Mlogin === FALSE): ?>
                  <div id="alert" class="alert alert-danger" role="alert">
                    ❌ Errore durante il login. Riprova!
                    <script>
                      setTimeout(function() {
                        window.location.href = 'login.php';
                      }, 2000);
                    </script>
                  </div>
                <?php endif; ?>
              </form>

              <div>
                <p class="mb-0">Don't have an account? <a href="registrazione.php" class="text-white-50 fw-bold">Sign Up</a></p>
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
    }, 2000);
</script>

</body>
</html>
