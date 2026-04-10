<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Portfólio</title>
    <style>
        /* ===== FONTES E BASE ===== */
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

        /* ===== CONTENTOR ===== */
        .container {
            width: 90%;
            max-width: 400px;
            background-color: #333;
            padding: 30px;
            border-radius: 8px;
            border: 1px solid #CFF250;
        }

        h1 {
            color: #CFF250;
            text-align: center;
            font-family: "Poppins", sans-serif;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-top: 15px;
            margin-bottom: 5px;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #CFF250;
            border-radius: 4px;
            background-color: #212226;
            color: #fff;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #CFF250;
            color: #212226;
            border: none;
            padding: 12px;
            width: 100%;
            border-radius: 4px;
            font-weight: bold;
            margin-top: 20px;
            cursor: pointer;
            transition: 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #212226;
            color: #CFF250;
            border: 1px solid #CFF250;
        }

        .register-link {
            text-align: center;
            margin-top: 20px;
        }

        .register-link a {
            color: #CFF250;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Login</h1>
        <form action="process_login.php" method="POST">
            <label for="username">Nome de utilizador:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Senha:</label>
            <input type="password" id="password" name="password" required>

            <input type="submit" value="Entrar">
        </form>
        <p class="register-link">Ainda não tem conta? <a href="register.php">Registe-se</a></p>
    </div>
</body>
</html>