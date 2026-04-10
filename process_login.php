<?php
include 'db.php'; // Usa o teu ficheiro de conexão [cite: 805]
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        die(json_encode(['success' => false, 'message' => 'Campos obrigatórios.']));
    }

    // Usando Prepared Statements com MySQLi para segurança [cite: 816, 854]
    $stmt = $conn->prepare("SELECT id, password_hash, user_type FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Verifica a senha usando a hash guardada [cite: 820, 859]
    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = $user['user_type'];
        
        // Redireciona para o perfil [cite: 826, 863]
        header('Location: profile.php');
        exit;
    } else {
        echo json_encode(['success' => false, 'message' => 'Utilizador ou senha inválidos.']);
    }
}
?>