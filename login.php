<?php
session_start();

// Verificar se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conectar ao banco de dados (substitua com suas credenciais)
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "tcc";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar conexão
    if ($conn->connect_error) {
        die("Erro na conexão com o banco de dados: " . $conn->connect_error);
    }

    $username = $_POST['username'];
    $password = $_POST['password'];

    // Consulta SQL para verificar se o usuário existe
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Login bem-sucedido, salvar dados do usuário na sessão
            $_SESSION['username'] = $username;
            header("Location: index.php"); // Redirecionar para página inicial após login
            exit();
        } else {
            $login_error = "Senha incorreta";
        }
    } else {
        $login_error = "Usuário não encontrado";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" href="assets/img/logo.png" type="image/x-icon">
    <link rel="shortcut icon" href="assets/img/logo.png" type="image/x-icon"><link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&family=Playwrite+NG+Modern:wght@100..400&display=swap" rel="stylesheet">
    <title>EcoAsh - Login</title>
</head>
<body>




    <header>
        <div class="container-fluid">
            <div class="row">
                <div class="col-6">
                    <div class="logo">
                        <a href="index.php"><img src="assets/img/logo.png" id="logo"></a>
                    </div>
                </div>
                <div class="col-6">
                    <div class="buttons">
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Menu
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="index.php">Home</a>
                            <a class="dropdown-item" href="faq.php">FAQ</a>
                            <a class="dropdown-item" href="loja.php">Loja</a>
                           
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <main id="mainlogin">
        <div class="container" id="loginform">
            <h2>Login</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-group">
                    <label for="username">Usuário:</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Senha:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary" id="loginbutton">Login</button>
            </form>
            <?php if (isset($login_error)): ?>
                <div class="alert alert-danger mt-3" role="alert">
                    <?php echo $login_error; ?>
                </div>
            <?php endif; ?>
            <div class="row" id="regprompt">
                <strong><p>Ainda não possui uma conta?</p><a href="register.php" id="entrar"><p>&nbsp;Criar</strong></p></a>
            </div> 
        </div>
    </main>
    <footer>
        <div class="container-fluid">
            <div class="row">
                <div class="col-6 col-lg-10">
                    <h6>Todos os direitos reservados | EcoAsh | 2024</h6>
                    <P>Suporte:</P>
                    <p><a href="https://wa.me/5537991233885?text=Slep%20no%20flep">+55 (37) 99123-3885</a> | <a href="mailto:business.morais.lucas@outlook.com">ecoash@suporte.com.br</a></p>
                </div>
                <div class="col-6 col-lg-2" id="logofooter">
                    <img src="assets/img/facebook.png" width="40px">
                    <img src="assets/img/instagram.png" width="40px">
                    <a href="https://www.youtube.com/watch?v=qlUJ4rMtRg8"><img src="assets/img/youtube.png" width="40px"> </a>
                </div>    
            </div>
        </div>
    </footer>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  </body>
</html>
