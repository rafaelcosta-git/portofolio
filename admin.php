<?php
include 'db.php';

$projects = [];

// ===============================
// BUSCA DE PROJETOS (DO PDF)
// ===============================
if (isset($_GET['search'])) {
    $search = htmlspecialchars($_GET['search']);
    $sql = "SELECT * FROM projects WHERE name LIKE ? OR description LIKE ?";
    $stmt = $conn->prepare($sql);
    $likeSearch = "%$search%";
    $stmt->bind_param("ss", $likeSearch, $likeSearch);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $projects[] = $row;
    }

    $stmt->close();
} else {
    $sql = "SELECT * FROM projects";
    $result = $conn->query($sql);

   $result = $conn->query($sql);

if (!$result) {
    die("Erro SQL: " . $conn->error);
}

while ($row = $result->fetch_assoc()) {
    $projects[] = $row;
}


}

// ===============================
// ADICIONAR PROJETO (DO PDF)
// ===============================
if (
    $_SERVER['REQUEST_METHOD'] == 'POST' &&
    isset($_POST['project_name']) &&
    isset($_POST['project_description'])
) {

    // Upload da imagem
    $image = '';
    if (isset($_FILES['project_image']) &&
        $_FILES['project_image']['error'] == UPLOAD_ERR_OK) {

        $image = 'uploads/' . basename($_FILES['project_image']['name']);
        move_uploaded_file($_FILES['project_image']['tmp_name'], $image);
    }

    $name = htmlspecialchars($_POST['project_name']);
    $description = htmlspecialchars($_POST['project_description']);

    $sql = "INSERT INTO projects (name, description, image) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $name, $description, $image);
    $stmt->execute();
    $stmt->close();

    header("Location: admin.php");
    exit();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Meu Portfólio</title>

    
    <style>

        @import url('https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&family=Poppins:wght@300;500;700&display=swap');
        *{margin:0;padding:0;box-sizing:border-box;}

        body {
            background:#212226;
            color:#fff;
            font-family:"Lato",sans-serif;
        }

        .container {
            width:90%;
            max-width:1100px;
            margin:auto;
        }

        header {
            background:#212226;
            padding:1rem 0;
            border-bottom:1px solid #333;
        }

        header .container {
            display:flex;
            justify-content:space-between;
            align-items:center;
        }

        #branding h1 {
            color:#cff250;
            font-family:"Poppins",sans-serif;
        }

        nav ul {
            display:flex;
            gap:1rem;
            list-style:none;
        }

        nav a {
            color:#cff250;
            text-decoration:none;
        }

        #showcase { padding:2rem 0 1rem; }

        h2 {
            margin-top:2rem;
            color:#cff250;
            border-bottom:2px solid #cff250;
            padding-bottom:.4rem;
        }

        form {
            margin:1rem 0 2rem;
            padding:1rem;
            background:#26272b;
            border:1px solid #cff250;
            border-radius:.5rem;
        }

        label { display:block; margin-top:.8rem; }
        input[type="text"], textarea {
            width:100%;
            padding:.7rem;
            background:#333;
            border:1px solid #555;
            border-radius:.3rem;
            color:#fff;
        }

        input[type="submit"] {
            margin-top:1rem;
            padding:.7rem 1.5rem;
            background:#cff250;
            border:none;
            border-radius:.3rem;
            cursor:pointer;
            font-weight:bold;
            color:#000;
        }

        table {
            width:100%;
            border-collapse:collapse;
            margin-top:1rem;
        }

        th, td {
            border:1px solid #444;
            padding:.6rem;
        }

        th {
            background:#333;
            color:#cff250;
        }

        td { background:#2c2d32; }

        img { max-width:150px; }

    </style>    
</head>
<body>

<header>
    <div class="container">
        <div id="branding">
            <h1>Olá Admin!</h1>
        </div>
        <nav>
            <ul>
                <li><a href="index.html">Início</a></li>
                <li><a href="admin.php">Administração</a></li>
            </ul>
        </nav>
    </div>
</header>

<section id="showcase">
    <div class="container">
        <h1>Bem-vindo à Página de Administração</h1>
        <p>Faça a gestão dos seus projetos abaixo:</p>
    </div>
</section>

<!-- FORMULÁRIO DE BUSCA (DO PDF) -->
<form action="admin.php" method="get">
    <label for="search">Buscar Projetos:</label>
    <input type="text" id="search" name="search" placeholder="Nome ou Descrição do Projeto">
    <input type="submit" value="Buscar">
</form>

<h2 id="projects">Meus Projetos</h2>

<?php if ($projects): ?>
<table>
    <thead>
        <tr>
            <th>Nome do Projeto</th>
            <th>Descrição</th>
            <th>Imagem</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($projects as $project): ?>
        <tr>
            <td><?= $project['name']; ?></td>
            <td><?= $project['description']; ?></td>
            <td>
                <img src="<?= $project['image']; ?>" width="100" alt="<?= $project['name']; ?>">
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php else: ?>
<p>Nenhum projeto encontrado.</p>
<?php endif; ?>

<h2>Adicionar Novo Projeto</h2>

<!-- FORMULÁRIO DE ADIÇÃO (DO PDF) -->
<form action="admin.php" method="post" enctype="multipart/form-data">
    <label for="project_name">Nome do Projeto:</label><br>
    <input type="text" id="project_name" name="project_name" required><br><br>

    <label for="project_description">Descrição do Projeto:</label><br>
    <textarea id="project_description" name="project_description" required></textarea><br><br>

    <label for="project_image">Imagem do Projeto:</label><br>
    <input type="file" id="project_image" name="project_image"><br><br>

    <input type="submit" value="Adicionar Projeto">
</form>

</body>
</html>
