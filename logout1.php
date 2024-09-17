<?php
session_start();
session_unset();
session_destroy();
header("Location: about.php?logout=success"); // Redireciona para a página inicial com parâmetro de logout
exit;
?>