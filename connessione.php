<?php
$conn = new mysqli("127.0.0.1", "root", "Password@", "biblioteca");
if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}
?>
