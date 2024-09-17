<?php
// Dados de conexão com o banco de dados
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "tcc";

// Criar conexão
$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

// Configurar o conjunto de caracteres para UTF-8
$conn->set_charset("utf8");

?>
