<?php
include "connessione.php";
session_start();

if (!isset($_SESSION['permessi']) || $_SESSION['permessi'] == 1) {
    header('Location: index.php');
    exit;
}

$azioneEseguita = false;

// Gestione POST per annullare la prenotazione
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["ordine_id"])) {
    $ordine_id = (int) $_POST["ordine_id"];
    $libro_id = (int) $_POST["id_libro"];
    $filiale_id = (int) $_POST["id_filiale"];

    // Query per ottenere i dettagli dell'ordine
    $query_dettagli = "
        SELECT id_libro, id_filiale, restituito 
        FROM storico_prenotazioni 
        WHERE id = $ordine_id AND restituito = 0
    ";
    $res_dettagli = $conn->query($query_dettagli);

    if ($res_dettagli->num_rows === 1) {
        $row = $res_dettagli->fetch_assoc();
        $libro_id = $row['id_libro'];
        $filiale_id = $row['id_filiale'];

        // Aggiorna restituito = 1
        $update_storico = "
            UPDATE storico_prenotazioni 
            SET restituito = 1 
            WHERE id = $ordine_id
        ";
        $conn->query($update_storico);

        // Incrementa disponibilità
        $update_disp = "
            UPDATE disponibilita 
            SET count = count + 1 
            WHERE ref_libro = $libro_id AND ref_biblioteca = $filiale_id
        ";
        $conn->query($update_disp);

        $azioneEseguita = true;
    }
}

// Paginazione
$records_per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start_from = ($page - 1) * $records_per_page;

// Conteggio totale prenotazioni di oggi
$total_query = "SELECT COUNT(*) FROM storico_prenotazioni WHERE DATE(data_ora) = CURDATE()";
$total_result = $conn->query($total_query);
$total_books = $total_result->fetch_row()[0];
$total_pages = ceil($total_books / $records_per_page);

// Query per ottenere i dati con info sulla filiale e utente
$query = "
    SELECT 
        s.id AS ordine_id, 
        s.id_libro, 
        s.id_filiale, 
        l.titolo, 
        l.autore, 
        b.filiale, 
        u.username AS utente, 
        s.data_ora, 
        s.restituito
    FROM storico_prenotazioni s
    JOIN libri l ON s.id_libro = l.id
    JOIN bibliotecaL b ON s.id_filiale = b.id
    JOIN utenti u ON s.id_utente = u.id
    WHERE DATE(s.data_ora) = CURDATE()
    ORDER BY s.data_ora DESC
    LIMIT $start_from, $records_per_page
";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <title>📚 Prenotazioni</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container">
    <a class="navbar-brand" href="index.php">Home</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav">
        <?php if ($_SESSION['role'] == 'admin'): ?>
          <li class="nav-item">
            <a class="nav-link" href="annullo.php">Prenotazioni</a>
          </li>
        <?php endif; ?>
        <li class="nav-item">
          <a class="nav-link" href="logout.php">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-4">
  <h1 class="text-center mb-3">📖 Prenotazioni</h1>

  <?php if ($azioneEseguita): ?>
    <div id="alert" class="alert alert-success" role="alert">
      ✅ Prenotazione annullata correttamente.
    </div>
  <?php endif; ?>

  <table class="table table-bordered table-striped">
    <thead class="table-dark">
      <tr>
        <th>ID Ordine</th>
        <th>Titolo</th>
        <th>Autore</th>
        <th>Filiale</th>
        <th>Utente</th>
        <th>Data e Ora</th>
        <th>Azioni</th>
      </tr>
    </thead>
    <tbody>
    <?php if ($result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $row['ordine_id'] ?></td>
          <td><?= htmlspecialchars($row['titolo']) ?></td>
          <td><?= htmlspecialchars($row['autore']) ?></td>
          <td><?= htmlspecialchars($row['filiale']) ?></td>
          <td><?= htmlspecialchars($row['utente']) ?></td>
          <td><?= $row['data_ora'] ?></td>
          <td>
            <?php if ($row['restituito'] == 0): ?>
            <form method="POST" action="annullo.php" class="d-inline">
              <input type="hidden" name="ordine_id" value="<?= $row['ordine_id'] ?>">
              <input type="hidden" name="id_libro" value="<?= $row['id_libro'] ?>">
              <input type="hidden" name="id_filiale" value="<?= $row['id_filiale'] ?>">
              <button type="submit" class="btn btn-sm btn-danger">Annulla Prenotazione</button>
            </form>
            <?php else: ?>
              <span class="text-muted">Prenotazione annullata</span>
            <?php endif; ?>
          </td>
        </tr>
      <?php endwhile; ?>
    <?php else: ?>
      <tr><td colspan="7" class="text-center">Nessuna prenotazione trovata per oggi.</td></tr>
    <?php endif; ?>
    </tbody>
</table>

  <!-- Paginazione -->
  <nav>
    <ul class="pagination justify-content-center">
      <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <li class="page-item <?= $i === $page ? 'active' : '' ?>">
          <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
        </li>
      <?php endfor; ?>
    </ul>
  </nav>
</div>

<script>
  setTimeout(() => {
    const alertBox = document.getElementById("alert");
    if (alertBox) alertBox.style.display = "none";
  }, 2500);
</script>

</body>
</html>
