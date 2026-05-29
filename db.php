<?php
$host = 'localhost';
$user = 'root'; // Utilizador padrão do XAMPP
$pass = ''; // Senha padrão do XAMPP (vazia)
$dbname = 'portfolio_db'; // O nome correto da nova base de dados

// Ligação com a variável correta $dbname
$conn = new mysqli($host, $user, $pass, $dbname);

// Verificação de erro
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>