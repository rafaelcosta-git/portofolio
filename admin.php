<?php
session_start();
include 'db.php';

// VERIFICAÇÃO DE SEGURANÇA (O teu extra excelente)
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$summary_projects = [];
$filtered_projects = [];

// ===============================
// 1. CONSULTA À VIEW DE RESUMO (Conforme PDF) [cite: 490-491]
// ===============================
$summary_query = "SELECT * FROM view_projects_summary";
$summary_result = $conn->query($summary_query);
if ($summary_result) {
    while ($row = $summary_result->fetch_assoc()) {
        $summary_projects[] = $row;
    }
}

// ===============================
// 2. CONSULTA À VIEW FILTRADA (Conforme PDF) [cite: 492-499]
// ===============================
// Podes alterar o 'Web Development' para outra categoria se desejares [cite: 494]
$category_filter = 'Web Development'; 
$filtered_query = $conn->prepare("SELECT * FROM view_projects_by_category WHERE category_name = ?");
$filtered_query->bind_param('s', $category_filter);
$filtered_query->execute();
$filtered_result = $filtered_query->get_result();

while ($row = $filtered_result->fetch_assoc()) {
    $filtered_projects[] = $row;
}
$filtered_query->close();

// ===============================
// BUSCA DE CATEGORIAS (Para o formulário de adicionar projeto)
// ===============================
$categories = [];
$cat_sql = "SELECT * FROM categories";
$cat_result = $conn->query($cat_sql);
if ($cat_result) {
    while ($row = $cat_result->fetch_assoc()) {
        $categories[] = $row;
    }
}

// ===============================
// ADICIONAR PROJETO
// ===============================
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['project_title']) && isset($_POST['project_description'])) {
    
    $image = '';
    if (isset($_FILES['project_image']) && $_FILES['project_image']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
        $image = $uploadDir . basename($_FILES['project_image']['name']);
        move_uploaded_file($_FILES['project_image']['tmp_name'], $image);
    }

    $title = htmlspecialchars($_POST['project_title']);
    $description = htmlspecialchars($_POST['project_description']);
    $category_id = intval($_POST['category_id']);
    $creation_date = date('Y-m-d');

    $sql = "INSERT INTO projects (title, description, image, category_id, creation_date) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssis", $title, $description, $image, $category_id, $creation_date);
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
    <title>Relatórios do Portfólio</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&family=Poppins:wght@300;500;700&display=swap');
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #212226; color: #fff; font-family: "Lato", sans-serif; }
        .container { width: 90%; max-width: 1100px; margin: auto; padding-bottom: 2rem; }
        header { background: #212226; padding: 1rem 0; border-bottom: 1px solid #333; }
        header .container { display: flex; justify-content: space-between; align-items: center; padding-bottom: 0; }
        #branding h1 { color: #cff250; font-family: "Poppins", sans-serif; }
        nav ul { display: flex; gap: 1.5rem; list-style: none; align-items: center; }
        nav a { color: #cff250; text-decoration: none; font-weight: bold; }
        nav a:hover { color: #fff; }
        .btn-logout { color: #ff4d4d !important; }
        #showcase { padding: 2rem 0 1rem; }
        h2 { margin-top: 2rem; color: #cff250; border-bottom: 2px solid #cff250; padding-bottom: .4rem; }
        form { margin: 1rem 0 2rem; padding: 1rem; background: #26272b; border: 1px solid #cff250; border-radius: .5rem; }
        label { display: block; margin-top: .8rem; margin-bottom: .3rem;}
        input[type="text"], select, textarea { width: 100%; padding: .7rem; background: #333; border: 1px solid #555; border-radius: .3rem; color: #fff; }
        input[type="submit"] { margin-top: 1rem; padding: .7rem 1.5rem; background: #cff250; border: none; border-radius: .3rem; cursor: pointer; font-weight: bold; color: #000; }
        input[type="submit"]:hover { background: #fff; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { border: 1px solid #444; padding: .6px 12px; text-align: left; height: 40px; }
        th { background: #333; color: #cff250; }
        td { background: #2c2d32; }
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
                <li><a href="profile.php">Meu Perfil</a></li>
                <li><a href="admin.php">Administração</a></li>
                <li><a href="logout.php" class="btn-logout">Sair</a></li>
            </ul>
        </nav>
    </div>
</header>

<div class="container">
    <section id="showcase">
        <h1>Relatórios do Portfólio</h1>
    </section>

    <h2>Resumo dos Projetos</h2>
    <?php if ($summary_projects): ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Descrição</th>
                <th>Categoria</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($summary_projects as $project): ?>
            <tr>
                <td><?= htmlspecialchars($project['project_id']); ?></td>
                <td><?= htmlspecialchars($project['project_title']); ?></td>
                <td><?= htmlspecialchars($project['project_description']); ?></td>
                <td><strong style="color: #cff250;"><?= htmlspecialchars($project['category_name']); ?></strong></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
        <p style="margin-top: 1rem;">Nenhum projeto encontrado no resumo.</p>
    <?php endif; ?>

    <h2>Projetos por Categoria: <span style="color: #fff;"><?= htmlspecialchars($category_filter); ?></span></h2>
    <?php if ($filtered_projects): ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Descrição</th>
                <th>Categoria</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($filtered_projects as $project): ?>
            <tr>
                <td><?= htmlspecialchars($project['project_id']); ?></td>
                <td><?= htmlspecialchars($project['project_title']); ?></td>
                <td><?= htmlspecialchars($project['project_description']); ?></td>
                <td><strong style="color: #cff250;"><?= htmlspecialchars($project['category_name']); ?></strong></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
    <p style="margin-top: 1rem;">Nenhum projeto encontrado nesta categoria.</p>
    <?php endif; ?>

    <h2>Adicionar Novo Projeto</h2>
    <form action="admin.php" method="post" enctype="multipart/form-data">
        <label for="project_title">Título do Projeto:</label>
        <input type="text" id="project_title" name="project_title" required>

        <label for="category_id">Categoria:</label>
        <select id="category_id" name="category_id" required>
            <option value="">-- Selecione uma Categoria --</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id']; ?>"><?= htmlspecialchars($cat['name']); ?></option>
            <?php endforeach; ?>
        </select>

        <label for="project_description">Descrição do Projeto:</label>
        <textarea id="project_description" name="project_description" rows="4" required></textarea>

        <label for="project_image">Imagem do Projeto (Opcional):</label>
        <input type="file" id="project_image" name="project_image">

        <input type="submit" value="Adicionar Projeto">
    </form>
</div>

</body>
</html>