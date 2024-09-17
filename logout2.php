<?php
session_start();
session_unset();
session_destroy();
header("Location: faq.php?logout=success"); // Redireciona para a página inicial com parâmetro de logout
exit;
?>