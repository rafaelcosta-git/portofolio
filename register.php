<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registo - Portfólio</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* ===== FONTES [cite: 280, 281] ===== */
        @import url('https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&family=Poppins:wght@400;600;700&display=swap');

        /* ===== BASE E CORES DO PDF [cite: 289, 290, 292] ===== */
        body {
            background-color: #212226; /* Cinza muito escuro / Preto */
            color: #ffffff;
            font-family: "Lato", sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        /* ===== CONTENTOR DO FORMULÁRIO [cite: 299, 302, 305] ===== */
        .container {
            width: 90%;
            max-width: 450px;
            background-color: #333; /* Fundo do formulário [cite: 333] */
            padding: 30px;
            border-radius: 8px;
            border: 1px solid #CFF250; /* Borda Verde Neon [cite: 305] */
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        }

        h1 {
            font-family: "Poppins", sans-serif;
            color: #CFF250; /* Título Verde Neon [cite: 308] */
            text-align: center;
            margin-bottom: 20px;
            font-size: 2rem;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #ffffff;
        }

        /* ===== INPUTS ESTILIZADOS [cite: 323, 331, 333] ===== */
        input[type="text"], 
        input[type="email"], 
        input[type="password"], 
        select, 
        input[type="file"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 18px;
            border: 1px solid #CFF250; /* Borda Verde [cite: 331] */
            border-radius: 4px;
            background-color: #212226;
            color: #fff;
            box-sizing: border-box;
        }

        /* ===== BOTÃO REGISTAR (ESTILO btn-success) [cite: 336, 337, 339] ===== */
        input[type="submit"] {
            background-color: #CFF250; /* Verde Neon [cite: 337] */
            color: #212226; /* Texto Escuro [cite: 339] */
            border: 1px solid #CFF250;
            padding: 12px;
            width: 100%;
            border-radius: 4px;
            font-weight: bold;
            font-size: 1rem;
            cursor: pointer;
            transition: 0.3s;
        }

        input[type="submit"]:hover {
            background-color: transparent;
            color: #CFF250;
        }

        #error-message {
            color: #ff4d4d;
            text-align: center;
            margin-top: 15px;
            font-size: 0.9rem;
            font-weight: bold;
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
        }

        .login-link a {
            color: #CFF250;
            text-decoration: none;
            font-weight: 600;
        }

        .login-link a:hover {
            color: #ffffff;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Registo</h1>
    
    <form id="register-form" enctype="multipart/form-data">
        <label for="username">Nome de utilizador</label>
        <input type="text" id="username" name="username" required minlength="3">

        <label for="email">E-mail</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Senha (mín. 6 caracteres)</label>
        <input type="password" id="password" name="password" required minlength="6">

        <label for="confirm-password">Confirmar Senha</label>
        <input type="password" id="confirm-password" name="confirm-password" required>

        <label for="user_type">Tipo de Utilizador</label>
        <select id="user_type" name="user_type">
            <option value="user">Utilizador</option>
            <option value="admin">Administrador</option>
        </select>

        <label for="profile_pic">Foto de Perfil</label>
        <input type="file" id="profile_pic" name="profile_pic" accept="image/*" required>

        <input type="submit" value="Registar">
        
        <p id="error-message"></p>
    </form>

    <div class="login-link">
        <p>Já tem conta? <a href="login.php">Faça Login</a></p>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#register-form').submit(function (event) {
            event.preventDefault();
            
            let password = $('#password').val();
            let confirmPassword = $('#confirm-password').val();

            // Validação de senhas iguais no cliente [cite: 240, 242]
            if (password !== confirmPassword) {
                $('#error-message').text('As senhas não correspondem.');
                return;
            }

            var formData = new FormData(this);

            $.ajax({
                url: 'process_register.php', // Arquivo de backend [cite: 254, 455]
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
               success: function (response) {
    // Se o servidor retornar uma string, convertemos. Se já for objeto, usamos direto.
    let res = (typeof response === 'string') ? JSON.parse(response) : response;
    
    if (res.success) {
        alert('Registo concluído com sucesso!');
        window.location.href = 'login.php';
    } else {
        // Exibe a mensagem de erro vinda do PHP [cite: 79]
        $('#error-message').text(res.message);
    }
},
            });
        });
    });
</script>

</body>
</html>