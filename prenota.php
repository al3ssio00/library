<?php 
include "connessione.php";

session_start();

if (!isset($_SESSION['permessi'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET["id"];
$filiale = $_GET["filiale"];

// Recupera l'ID dell'utente loggato dalla sessione
$user_id = $_SESSION['user_id'];

// Query per ottenere il libro e la sua disponibilità
$query = "
SELECT l.id, l.titolo, l.autore, l.anno, 
       SUM(d.count) AS disponibilita
FROM disponibilita d
RIGHT OUTER JOIN libri l ON d.ref_libro = l.id
LEFT OUTER JOIN bibliotecaL b ON d.ref_biblioteca = b.id
WHERE l.id = $id
GROUP BY l.titolo, l.autore, l.anno;
";
$result = $conn->query($query);
$libro = $result->fetch_assoc();

// Verifica se il libro è disponibile
if ($libro["disponibilita"] <= 0) {
    // Se il libro non è disponibile, reindirizza con messaggio di errore
    header("Location: index.php?success=false");
    exit;
} else {
    // Aggiorna la disponibilità - 1
    $update = "UPDATE disponibilita SET count = count - 1 WHERE ref_libro = $id AND ref_biblioteca = $filiale AND count > 0";
    if ($conn->query($update)) {
        // Inserisci la prenotazione nello storico
        $storico = "INSERT INTO storico_prenotazioni (id_libro, id_filiale, id_utente) VALUES ($id, $filiale, $user_id)";
        if ($conn->query($storico)) {
            // Se la prenotazione ha avuto successo, reindirizza con il messaggio di successo
            header("Location: index.php?success=true");
            exit;
        } else {
            // Se c'è un errore durante l'inserimento nello storico
            echo "Errore durante la registrazione della prenotazione.";
        }
    } else {
        // Se c'è un errore durante l'aggiornamento della disponibilità
        echo "Errore durante l'aggiornamento della disponibilità del libro.";
    }
}
?>
