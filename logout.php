
<?php
// Inicia a sessão para poder manipulá-la [cite: 878]
session_start();

// Limpa todas as variáveis da sessão (ID, Username, Role) [cite: 879]
session_unset();

// Destrói a sessão completamente no servidor [cite: 880]
session_destroy();

// Redireciona o utilizador para a página inicial [cite: 881]
header("Location: index.html");
exit;
?>

