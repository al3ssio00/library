<?php
include "connessione.php";

session_start();

// Verifica se l'utente è loggato
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Recupera il ruolo dell'utente
$userRole = isset($_SESSION['role']) ? $_SESSION['role'] : 'guest'; // guest per utenti non autenticati

// Impostazione dei parametri per la paginazione
$records_per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Determina la pagina corrente
$start_from = ($page - 1) * $records_per_page;

// Gestione della ricerca
$search_term = isset($_GET['search']) ? $_GET['search'] : '';

// Impostazione della query per recuperare i libri con la disponibilità
$query = "SELECT l.id, l.titolo, l.autore, l.anno, SUM(d.count) AS disponibilita, 
            CASE 
              WHEN SUM(d.count) = 0 THEN NULL 
              ELSE GROUP_CONCAT(DISTINCT 
                CASE WHEN d.count > 0 THEN b.filiale END SEPARATOR ', ') 
            END AS filiale,
            CASE 
              WHEN SUM(d.count) = 0 THEN NULL 
              ELSE GROUP_CONCAT(DISTINCT 
                  CASE WHEN d.count > 0 THEN b.id END SEPARATOR ', ') 
            END AS id_filiali
          FROM libri l
            LEFT JOIN disponibilita d ON l.id = d.ref_libro
            LEFT OUTER JOIN bibliotecaL b on d.ref_biblioteca = b.id
          WHERE l.titolo LIKE '%$search_term%' 
            OR l.autore LIKE '%$search_term%' 
            OR l.anno LIKE '%$search_term%' 
          GROUP BY l.id
          LIMIT $start_from, $records_per_page
";

$result = $conn->query($query);

// Calcola il numero totale di libri per la paginazione
$total_query = "
    SELECT COUNT(*) 
    FROM libri l
    LEFT JOIN disponibilita d ON l.id = d.ref_libro
    WHERE l.titolo LIKE '%$search_term%' 
       OR l.autore LIKE '%$search_term%' 
       OR l.anno LIKE '%$search_term%';
";
$total_result = $conn->query($total_query);
$total_books = $total_result->fetch_row()[0];
$total_pages = ceil($total_books / $records_per_page);

// init message
$message = '';

if (isset($_GET['success'])) {
    if ($_GET['success'] == 'true') {
        $message = '✅ Libro prenotato con successo!';
    } elseif ($_GET['success'] == 'false') {
        $message = '⚠️ Questo libro non è disponibile!';
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <title>Biblioteca</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container">
    <a class="navbar-brand" href="index.php">Home</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav">
        
        <?php if ($_SESSION['role'] == 'admin'): ?>
          <!-- Link sempre visibile per gli admin -->
          <li class="nav-item">
            <a class="nav-link" href="annullo.php">Prenotazioni</a>
          </li>
        <?php endif; ?>

        <!-- Link visibile a tutti gli utenti loggati -->
        <li class="nav-item">
          <a class="nav-link" href="logout.php">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

  <div class="container table-container">
    <h1 class="mb-4 text-center">Libri della Biblioteca</h1>

    <!-- Messaggio di conferma o errore -->
    <?php if ($message): ?>
      <div id= "alert" class="alert alert-success text-center">
        <?= $message ?>
      </div>
    <?php endif; ?>

    <form class="mb-3" method="GET">
      <!-- Campo di ricerca (form) -->
      <input type="text" class="form-control" name="search" placeholder="Cerca libri..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" />
    </form>

    <!-- Tabella dei libri -->
    <table class="table table-striped table-bordered">
      <thead class="table-dark">
        <tr>
          <th scope="col" class="sortable" data-sort="titolo">Titolo</th>
          <th scope="col" class="sortable" data-sort="autore">Autore</th>
          <th scope="col" class="sortable" data-sort="anno">Anno</th>
          <th scope="col">Disponibilità</th>
          <th scope="col">Filiale</th>
          <th scope="col">Prenotazione</th>
        </tr>
      </thead>
      <tbody>
        <?php
        // Visualizza i libri con la disponibilità calcolata
        while ($row = $result->fetch_assoc()): 
          // Se la somma delle copie è NULL o 0, consideriamo il libro come non disponibile
          $disponibilita_value = ($row['disponibilita'] > 0) ? $row['disponibilita'] : 0;
          $array_filiali = explode(", ", $row['id_filiali']);
          $filiale = $array_filiali[array_rand($array_filiali)];
        ?>
          <tr>
            <td><?= htmlspecialchars($row['titolo']) ?></td>
            <td><?= htmlspecialchars($row['autore']) ?></td>
            <td><?= $row['anno'] ?></td>
            <td>
              <?= $disponibilita_value > 0 ? "<span class='badge bg-success'>$disponibilita_value disponibili</span>" : '<span class="badge bg-danger">Non disponibile</span>' ?>
            </td>
            <td><?= htmlspecialchars($row['filiale']) ?></td>
            <td>
              <?php if ($disponibilita_value > 0): ?>
                <a href="prenota.php?id=<?= $row['id'] ?>&filiale=<?= $filiale ?>" class="btn btn-sm btn-primary">Prenota</a>
              <?php else: ?>
                <span class="text-muted">Nessuna azione</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>

    <!-- Paginazione -->
    <div class="pagination-container">
      <ul class="pagination justify-content-center">
        <?php
        // Ciclo per generare i link per la paginazione
        for ($i = 1; $i <= $total_pages; $i++): 
        ?>
          <li class="page-item <?= $i == $page ? 'active' : '' ?>">
            <a class="page-link" href="?page=<?= $i ?>&search=<?= htmlspecialchars($search_term) ?>"><?=$i ?></a>
          </li>
        <?php endfor; ?>
      </ul>
    </div>
  </div>
  <script>
  setTimeout(() => {
    const alertBox = document.getElementById("alert");
    if (alertBox) alertBox.style.display = "none";
  }, 2500);
</script>
</body>
</html>
