<?php
session_start();
$logged_in = isset($_SESSION['username']);
$username = $logged_in ? $_SESSION['username'] : '';

$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "tcc";

// Incluir arquivo de conexão com o banco de dados
include_once 'conexao.php';

// Definir $balance como null inicialmente
$balance = null;
$email = '';

if ($logged_in) {
    // Obter o nome de usuário da sessão
    $username = $_SESSION['username'];

    // Consulta para obter o saldo do usuário e o e-mail
    $query_user = "SELECT balance, email FROM users WHERE username = ?";
    $stmt_user = $conn->prepare($query_user);
    $stmt_user->bind_param("s", $username);
    $stmt_user->execute();
    $stmt_user->bind_result($balance, $email);
    $stmt_user->fetch();
    $stmt_user->close();

    // Se $balance for null, definir um valor padrão (opcional)
    if ($balance === null) {
        $balance = 0; // ou outro valor padrão desejado
    }

    // Verificar se o formulário foi submetido com um item para compra
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['item_id'])) {
        $item_id = $_POST['item_id'];

        // Consulta para obter o preço e o nome do item
        $query_item = "SELECT price, name FROM items WHERE id = ?";
        $stmt_item = $conn->prepare($query_item);
        $stmt_item->bind_param("i", $item_id);
        $stmt_item->execute();
        $stmt_item->bind_result($item_price, $item_name);
        $stmt_item->fetch();
        $stmt_item->close();

        if ($item_price) {
            // Verificar se o saldo é suficiente
            if ($balance >= $item_price) {
                // Atualizar o saldo do usuário
                $update_balance_query = "UPDATE users SET balance = balance - ? WHERE username = ?";
                $stmt_update_balance = $conn->prepare($update_balance_query);
                $stmt_update_balance->bind_param("ds", $item_price, $username);
                $stmt_update_balance->execute();
                $stmt_update_balance->close();

                // Registrar a compra na tabela purchases com o nome do item e o e-mail
                $insert_purchase_query = "INSERT INTO purchases (item_name, username, email) VALUES (?, ?, ?)";
                $stmt_insert_purchase = $conn->prepare($insert_purchase_query);
                $stmt_insert_purchase->bind_param("sss", $item_name, $username, $email);
                $stmt_insert_purchase->execute();
                $stmt_insert_purchase->close();

                // Redirecionar para loja.php após a compra
              // Se a compra foi bem-sucedida
              header("Location: index.php?message=" . urlencode("Compra feita com sucesso. Espere o email da EcoAsh ou ligue para o número de suporte +55 (37) 99123-3885."));
              
              
              exit();
              

            } else {
                // Saldo insuficiente
                header("Location: loja.php?error_message=" . urlencode("Saldo insuficiente para a compra."));
                exit();
            }
        } else {
            // Item não encontrado
            header("Location: loja.php?error_message=" . urlencode("Item não encontrado."));
            exit();
        }
    }
} else {
    $logged_in = false;
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
    <link rel="shortcut icon" href="assets/img/logo.png" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&family=Playwrite+NG+Modern:wght@100..400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" />
    <title>Loja - EcoAsh</title>
    <style>
        /* Ajustes para o container principal */
        .container, .container-fluid {
            padding-left: 15px;
            padding-right: 15px;
            box-sizing: border-box;
        }

        /* Corrigir o problema de barra verde */
        html, body {
            margin: 0;
            padding: 0;
            overflow-x: hidden; /* Impede a barra de rolagem horizontal */
        }

        main {
            position: relative;
            z-index: 1;
            overflow-x: hidden; /* Impede a barra de rolagem horizontal */
        }

        main::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-attachment: fixed;
            background-size: cover; /* Ajusta a imagem para cobrir o fundo */
            background-position: center;
            opacity: 15%;
            filter: blur(8px);
            z-index: -1;
        }

        /* Ajustes no boxcart */
        .boxcart {
            background-color: rgb(10, 44, 10); /* Verde escuro */
            width: 100%;
            border-radius: 10px;
            padding: 10px;
            color: rgb(164, 222, 2);
            box-sizing: border-box; /* Inclui padding na largura total */
        }

        /* Ajustes para colunas e cartões */
        .card {
            display: flex;
            flex-direction: column;
            height: 100%;
            width: 100%;
        }

        .card-body {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 15px;
        }

        .card-img-top {
            margin-top: 10px;
            object-fit: cover; /* Ajusta a imagem para preencher o espaço sem distorcer */
            height: 300px; /* Define uma altura fixa para as imagens */
            border-radius: 5px;
        }

        .btn-primary {
            margin-top: auto; /* Empurra o botão para a parte inferior do card */
        }
        
        .row {
            display: flex;
            flex-wrap: wrap;
        }
      
        /* Estilos adicionais... */
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }
    </style>
</head>
<body>
<script>
    // Função para obter parâmetros da URL
    function getQueryParameter(name) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(name);
    }

    // Obter a mensagem da URL
    const message = getQueryParameter('message');
    const errorMessage = getQueryParameter('error_message');

    // Exibir o alert se a mensagem estiver presente
    if (message) {
        alert(message);
    }
    if (errorMessage) {
        alert(errorMessage);
    }
</script>

<header>
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-6 text-center text-md-left">
                <div class="logo">
                    <a href="index.php"><img src="assets/img/logo.png" id="logo" alt="Logo"></a>
                </div>
            </div>
            <div class="col-6 text-center text-md-right">
                <div class="buttons">
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Menu
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="index.php">Home</a>
                            <a class="dropdown-item" href="register.php">Cadastro | Login</a>
                            <a class="dropdown-item" href="loja.php">Loja</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row text-center">
            <div class="col-12">
                <div class="user-info">
                    <?php if ($logged_in) : ?>
                        <span class="welcome-message" data-aos="fade-up">Bem-vindo, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</header>

<main>
    <h1>&nbsp;</h1>
    <h1>&nbsp;</h1>
    <h1>&nbsp;</h1>
    <div class="container" id="ticket-form">
        <div class="row">
            <!-- Coluna para exibir o saldo -->
            <?php if ($balance !== null) : ?>
                <div class="boxcart">
                    <p class="card-text3">Saldo: R$<?php echo number_format($balance, 2); ?></p>
                    <a href="loja.php" class="btn btn-sm btn-primary" id="pp2">Atualizar saldo</a>
            <?php else : ?>
                <div class="boxcart">
                    <p class="card-text4">Saldo indisponível. Verifique seu login.</p>
            <?php endif; ?>
                </div>
        </div>     
        </div>
    </div>

    <h1>&nbsp;</h1>

    <h1 class="text-center" style="color: rgb(100, 150, 0);" data-aos="fade-down">LOJA</h1>
    <div class="container">
        <div class="row">
            <!-- Item 1 -->
            <div class="col-md-4 mb-4 d-flex align-items-stretch">
                <div class="card" data-aos="fade-left"  data-aos-duration="2000">
                    <img src="assets/img/item1.png" class="card-img-top" alt="Item 1">
                    <div class="card-body">
                        <h5 class="card-title">Consulta com o Psicólogo</h5>
                        <p class="card-text">Marque uma consulta com um profissional da área para apoio emocional.</p>
                        <p class="card-text"><strong>R$ 150,00</strong></p>
                        <form method="POST" action="loja.php" class="mt-auto">
                            <input type="hidden" name="item_id" value="1">
                            <button type="submit" class="btn btn-primary">Comprar</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Item 2 -->
            <div class="col-md-4 mb-4 d-flex align-items-stretch">
                <div class="card"  data-aos="fade-left"  data-aos-duration="2000">
                    <img src="assets/img/item2.png" class="card-img-top" alt="Item 2">
                    <div class="card-body">
                        <h5 class="card-title">Livro - Livre-se do cigarro</h5>
                        <p class="card-text">Livro de auto-ajuda sobre o vício escrito por um autor renomado.</p>
                        <p class="card-text"><strong>R$ 32,00</strong></p>
                        <form method="POST" action="loja.php" class="mt-auto">
                            <input type="hidden" name="item_id" value="2">
                            <button type="submit" class="btn btn-primary">Comprar</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Item 3 -->
            <div class="col-md-4 mb-4 d-flex align-items-stretch">
                <div class="card"  data-aos="fade-left" data-aos-duration="2000">
                    <img src="assets/img/item3.png" class="card-img-top" alt="Item 3">
                    <div class="card-body">
                        <h5 class="card-title">Champyx Gotas 30ml</h5>
                        <p class="card-text">Champyx Gotas 30ml: Suplemento Alimentar em Gotas para Combate ao Vício do Cigarro.</p>
                        <p class="card-text"><strong>R$ 30,00</strong></p>
                        <form method="POST" action="loja.php" class="mt-auto">
                            <input type="hidden" name="item_id" value="3">
                            <button type="submit" class="btn btn-primary">Comprar</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Item 4 -->
            <div class="col-md-4 mb-4 d-flex align-items-stretch">
                <div class="card" data-aos="fade-right" data-aos-duration="2000">
                    <img src="assets/img/item4.png" class="card-img-top" alt="Item 4">
                    <div class="card-body">
                        <h5 class="card-title">Nicorette 4mg Sabor Icemint 30 Gomas Mastigáveis.</h5>
                        <p class="card-text">Além disso, ajuda a branquear os dentes, amenizando o amarelado causado pelo tabaco. A embalagem contém 30 unidades com 4 mg de nicotina cada.</p>
                        <p class="card-text"><strong>R$ 40,00</strong></p>
                        <form method="POST" action="loja.php" class="mt-auto">
                            <input type="hidden" name="item_id" value="4">
                            <button type="submit" class="btn btn-primary">Comprar</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Item 5 -->
            <div class="col-md-4 mb-4 d-flex align-items-stretch">
                <div class="card" data-aos="fade-right" data-aos-duration="2000">
                    <img src="assets/img/item5.png" class="card-img-top" alt="Item 5">
                    <div class="card-body">
                        <h5 class="card-title">Adesivo Para Parar De Fumar Com 60 Extratos.</h5>
                        <p class="card-text">Colado na pele, o adesivo libera nicotina no organismo, durante as 24 horas depois de aplicado assim ajudando na reabilitação.</p>
                        <p class="card-text"><strong>R$ 150,00</strong></p>
                        <form method="POST" action="loja.php" class="mt-auto">
                            <input type="hidden" name="item_id" value="5">
                            <button type="submit" class="btn btn-primary">Comprar</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Item 6 -->
            <div class="col-md-4 mb-4 d-flex align-items-stretch">
                <div class="card" data-aos="fade-right" data-aos-duration="2000">
                    <img src="assets/img/item6.png" class="card-img-top" alt="Item 6">
                    <div class="card-body">
                        <h5 class="card-title">Check-Up de Pulmão e raio-x completo do tórax</h5>
                        <p class="card-text">Exames para o pulmão, sangue, urina e fezes para ver sua situação atual.Acompanhamento com um profissional com atendimento adaptativo.</p>
                        <p class="card-text"><strong>R$ 300,00</strong></p>
                        <form method="POST" action="loja.php" class="mt-auto">
                            <input type="hidden" name="item_id" value="6">
                            <button type="submit" class="btn btn-primary">Comprar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<footer>
    <div class="container-fluid">
        <div class="row">
            <div class="col-6 col-lg-10">
                <h6>Todos os direitos reservados | EcoAsh | 2024</h6>
                <p>Suporte:</p>
                <p><a href="https://wa.me/5537991233885?text=Slep%20no%20flep">+55 (37) 99123-3885</a> | <a href="mailto:business.morais.lucas@outlook.com">ecoash@suporte.com.br</a></p>
            </div>
            <div class="col-6 col-lg-2" id="logofooter">
                <?php if ($logged_in) : ?>
                    <a href="logout4.php">Logout</a>
                <?php endif; ?>
                <img src="assets/img/facebook.png" width="40px">
                <img src="assets/img/instagram.png" width="40px">
                <a href="https://www.youtube.com/watch?v=qlUJ4rMtRg8"><img src="assets/img/youtube.png" width="40px"> </a>
            </div>    
        </div>
    </div>
</footer>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script src="assets/js/javascript.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<script>
    AOS.init();
</script>
</body>
</html>
