<?php
// Desativa a exibição de erros HTML para não corromper o JSON
error_reporting(0); 
include 'db.php'; 

header('Content-Type: application/json'); // Garante que o browser entende como JSON

$response = ['success' => false, 'message' => 'Erro desconhecido.'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $userType = $_POST['user_type'];

    // Validação mínima [cite: 501, 605]
    if (strlen($username) < 3) {
        $response['message'] = 'O nome de utilizador deve ter pelo menos 3 caracteres.';
        echo json_encode($response);
        exit;
    }

    $passwordHash = password_hash($password, PASSWORD_DEFAULT); // [cite: 69, 565]

    $profilePic = 'default-profile-pic.jpg';
    if (!empty($_FILES['profile_pic']['name'])) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true); // [cite: 555, 655]
        }
        $profilePic = $uploadDir . basename($_FILES['profile_pic']['name']);
        move_uploaded_file($_FILES['profile_pic']['tmp_name'], $profilePic); // [cite: 557, 660]
    }

    // Preparar a query (MySQLi conforme o teu db.php)
    $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash, user_type, profile_pic) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $username, $email, $passwordHash, $userType, $profilePic);

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Registo bem-sucedido!';
    } else {
        $response['message'] = 'Erro ao registar: ' . $conn->error;
    }
}

echo json_encode($response);