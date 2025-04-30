<?php
include "connessione.php";

session_start();
if ($_SESSION['permessi'] == 1) {
  header('Location: index.php');
}


// Impostazione dei parametri per la paginazione
$records_per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start_from = ($page - 1) * $records_per_page;

// Conteggio dei record solo per oggi
$total_query = "SELECT COUNT(*) FROM storico_prenotazioni WHERE DATE(data_ora) = CURDATE()";
$total_result = $conn->query($total_query);
$total_books = $total_result->fetch_row()[0];
$total_pages = ceil($total_books / $records_per_page);

// Query per ottenere i dati con paginazione
$query = "SELECT l.titolo, l.autore, s.data_ora,
          b.filiale
          FROM libri l
          JOIN storico_prenotazioni s ON l.id = s.id_libro
          LEFT JOIN disponibilita d ON l.id = d.ref_libro
          LEFT JOIN bibliotecaL b ON d.ref_biblioteca = b.id
          WHERE DATE(s.data_ora) = CURDATE()
          GROUP BY l.id, s.data_ora, b.filiale
          ORDER BY s.data_ora DESC
          LIMIT $start_from, $records_per_page";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <title>Biblioteca</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .table-container {
      padding: 20px;
    }
  </style>
</head>

<body class="bg-light">

  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
      <a class="navbar-brand" href="index.php">Home</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="annullo.php">Annullo</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="storico.php">Storico</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="logout.php">Esci</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>


  <div class="container table-container">
    <h1 class="mb-4 text-center">📖 Storico Prenotazioni</h1>
  </div>
</body>

  <div class="container table-container">

    <form class="mb-3" method="get">
      <input type="text" class="form-control" name="search" placeholder="Cerca libri..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" />
    </form>
    <table class="table table-striped table-bordered">
      <thead class="table-dark">
        <tr>
          <th>Titolo</th>
          <th>Autore</th>
          <th>filiale</th>
          <th>Data e Ora</th>
        </tr>
      </thead>
      <tbody>


          <?php 

          // Verifica ricerca nella tabella
          $search_term = isset($_GET["search"]) ? $_GET["search"] : '';  
          
          if($search_term != '') {
          $search_condition = " AND l.titolo LIKE '%$search_term%' OR l.autore LIKE '%$search_term%' OR l.anno LIKE '%$search_term%'";
          }
          
          $query = "SELECT l.titolo, l.autore, s.data_ora
          FROM libri l
          JOIN storico_prenotazioni s ON l.id = s.id_libro
          WHERE DATE(s.data_ora) = CURDATE()
          $search_condition
          ORDER BY s.data_ora DESC
          LIMIT $start_from, $records_per_page";
          $result = $conn->query($query); 
          ?>
        <?php if ($result && $result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['titolo']) ?></td>
              <td><?= htmlspecialchars($row['autore']) ?></td>
              <td><?= htmlspecialchars($row['filiale'] ?? '') ?></td>
              <td><?= $row["data_ora"] ?></td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="3" class="text-center">Nessun risultato trovato per oggi.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>

    <!-- Paginazione -->
    <div class="pagination-container">
      <ul class="pagination justify-content-center">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
          <li class="page-item <?= $i == $page ? 'active' : '' ?>">
            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>

          </li>
        <?php endfor; ?>
      </ul>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>