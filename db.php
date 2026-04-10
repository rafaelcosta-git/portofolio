<?php
$host = 'localhost';
$db = 'portfolio';
$user = 'root'; // Alterar conforme necessário
$pass = ''; // Alterar conforme necessário

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>
