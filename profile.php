<?php
include 'db.php';
session_start();

// Verifica se o utilizador está logado 
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit;
}

// Procura os dados do utilizador logado para exibir no perfil 
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT username, email, user_type, profile_pic FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil - Portfólio</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Poppins:wght@600&display=swap');

        body {
            background-color: #212226;
            color: #ffffff;
            font-family: "Lato", sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            width: 90%;
            max-width: 450px;
            background-color: #333;
            padding: 30px;
            border-radius: 12px;
            border: 1px solid #CFF250;
            text-align: center;
        }

        h1 {
            color: #CFF250;
            font-family: "Poppins", sans-serif;
            margin-bottom: 25px;
        }

        /* Foto de Perfil Redonda */
        .profile-img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 3px solid #CFF250;
            object-fit: cover;
            margin-bottom: 20px;
        }

        .info-box {
            text-align: left;
            background-color: #212226;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .info-box p {
            margin: 10px 0;
            font-size: 1.1rem;
        }

        .info-box strong {
            color: #CFF250;
        }

        /* Botões de Ação */
        .btn {
            display: block;
            width: 100%;
            padding: 12px;
            margin-top: 10px;
            border-radius: 4px;
            font-weight: bold;
            text-decoration: none;
            text-align: center;
            transition: 0.3s;
            box-sizing: border-box;
        }

        .btn-home {
            background-color: #CFF250;
            color: #212226;
        }

        .btn-home:hover {
            background-color: transparent;
            color: #CFF250;
            border: 1px solid #CFF250;
        }

        .btn-admin {
            background-color: transparent;
            color: #CFF250;
            border: 1px solid #CFF250;
        }

        .btn-admin:hover {
            background-color: #CFF250;
            color: #212226;
        }

        .btn-logout {
            color: #ff4d4d;
            font-size: 0.9rem;
            margin-top: 15px;
        }

        .btn-logout:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Perfil</h1>

        <img src="<?php echo $user['profile_pic'] ? $user['profile_pic'] : 'uploads/default-profile-pic.jpg'; ?>" alt="Foto de Perfil" class="profile-img">

        <div class="info-box">
            <p><strong>Nome:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
            <p><strong>E-mail:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Tipo:</strong> <?php echo htmlspecialchars($user['user_type']); ?></p>
        </div>

        <a href="index.html" class="btn btn-home">Ir para o Website</a>
        
        <?php if ($user['user_type'] === 'admin'): ?>
            <a href="admin.php" class="btn btn-admin">Ir para Administração</a>
        <?php endif; ?>
        
        <a href="logout.php" class="btn btn-logout">Sair da Sessão</a>
    </div>
</body>
</html>