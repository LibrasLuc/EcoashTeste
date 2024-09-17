
<?php
session_start();

// Verificar se a sessão de usuário está definida
$logged_in = isset($_SESSION['username']);
$username = $logged_in ? $_SESSION['username'] : '';
?>


<?php
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "tcc";

// Incluir arquivo de conexão com o banco de dados
include_once 'conexao.php';

// Definir $balance como null inicialmente
$balance = null;

// Verificar se a sessão de usuário está definida
if ($logged_in) {
    // Obter o nome de usuário da sessão
    $username = $_SESSION['username'];

    // Consulta para obter o saldo do usuário
    $query = "SELECT balance FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($balance);
    $stmt->fetch();
    $stmt->close();

    // Se $balance for null, definir um valor padrão (opcional)
    if ($balance === null) {
        $balance = 0; // ou outro valor padrão desejado
    }

    // Verificar se o formulário foi submetido com uma senha física
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ticket'])) {
        $ticket_code = $_POST['ticket'];

        // Consulta para verificar se a senha inserida coincide com o ticket válido no banco de dados
        $query_ticket = "SELECT id, valid FROM ticket WHERE code = ? AND valid = 1";
        $stmt_ticket = $conn->prepare($query_ticket);
        $stmt_ticket->bind_param("s", $ticket_code);
        $stmt_ticket->execute();
        $stmt_ticket->store_result();
        $stmt_ticket->bind_result($ticket_id, $ticket_valid);
        $stmt_ticket->fetch();

        if ($stmt_ticket->num_rows > 0 && $ticket_valid == 1) {
            // Ticket válido encontrado, então atualizar o saldo do usuário
            $update_query = "UPDATE users SET balance = balance + ? WHERE username = ?";

            // Verificar os últimos dois caracteres do ticket_code
            $last_two_chars = substr($ticket_code, -2);

            // Definir pontos padrão a serem adicionados
            $points = 50; // pontos padrão

            // Verificar se os últimos dois caracteres são específicos para ganhar mais pontos
            if ($last_two_chars == 'XP' || $last_two_chars == 'PT') {
                $points = 100; // ajustar pontos para tickets especiais
            }

            
            $stmt_update = $conn->prepare($update_query);
            $stmt_update->bind_param("is", $points, $username);
            $stmt_update->execute();
            $stmt_update->close();

            // Marcar o ticket como utilizado (valid = 0) e deletar o código
            $update_ticket_query = "UPDATE ticket SET valid = 0 WHERE id = ?";
            $stmt_update_ticket = $conn->prepare($update_ticket_query);
            $stmt_update_ticket->bind_param("i", $ticket_id);
            $stmt_update_ticket->execute();
            $stmt_update_ticket->close();

            // Redirecionar para index.php após o sucesso
            header("Location: index.php?ticket_success=true");
            exit();
        } else {
            // Se a senha inserida não coincidir ou o ticket já foi usado, redirecionar com uma mensagem de erro
            $error_message = "O ticket inserido não é válido ou já foi utilizado.";
            header("Location: index.php?error_message=" . urlencode($error_message));
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
    <title>EcoAsh</title>
</head>
<body>
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
                            <a class="dropdown-item" href="register.php">Cadastro | Login</a>
                            <a class="dropdown-item" href="faq.php">FAQ</a>
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
                        <span class="welcome-message">Bem-vindo, <?php echo htmlspecialchars($username); ?>!</span>
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
            <!-- Coluna para o formulário de inserção de ticket -->
            <div class="col-md-6" id="espaco">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="ticket-form">
                    <div class="form-group">
                        <h2 id="raiva">Insira o Ticket</h2>
                        <input type="text" class="form-control" id="ticket" name="ticket" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Verificar ticket</button>
                </form>
            </div>
            
            <!-- Coluna para exibir o saldo -->
            <div class="col-md-6">
                <?php if ($balance !== null) : ?>
                    <div class="boxcart">
                        <p class="card-text2">Saldo: R$<?php echo number_format($balance, 2); ?></p>
                        <a href="index.php" class="btn btn-sm btn-primary" id="pp">Atualizar saldo</a>
                <?php else : ?>
                    <div class="boxcart">
                        <p class="card-text1">Saldo indisponível. Verifique seu login.</p>
                <?php endif; ?>
                    </div>
            </div>
            </div>
        </div>
    </div>
    <h1>&nbsp;</h1>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4" id="espaco">
                <div class="card h-100">
                    <img src="assets/img/not1.png" class="card-img-top" alt="Imagem da Notícia" id="notimg">
                    <div class="card-body">
                        <h5 class="card-title">OMS lança diretriz para tratar dependência de cigarro</h5>
                        <p class="card-text">Pela primeira vez em mais de sete décadas, a agência das Nações Unidas divulga uma série de normas para tratar a dependência, combinando terapia, medicamentos e ajuda digital, como o uso de internet com mensagens de incentivo.</p>
                    </div>
                    <div class="card-footer">
                        <a href="https://www.correiobraziliense.com.br/ciencia-e-saude/2024/07/6890136-oms-lanca-diretriz-para-tratar-dependencia-de-cigarro.html" class="btn btn-primary">Ver mais</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4" id="espaco">
                <div class="card h-100">
                    <img src="assets/img/not2.png" class="card-img-top" alt="Imagem da Notícia" id="notimg">
                    <div class="card-body">
                        <h5 class="card-title">Comissão adia votação do PL que regulamenta cigarro eletrônico</h5>
                        <p class="card-text">A votação do Projeto de Lei (PL) 5.008/2023, que regulamenta a produção, a comercialização, a fiscalização e a propaganda de cigarros eletrônicos no Brasil foi adiada pela (CAE) do Senado, nesta terça-feira (11).</p>
                    </div>
                    <div class="card-footer">
                        <a href="https://agenciabrasil.ebc.com.br/saude/noticia/2024-06/comissao-adia-votacao-do-pl-que-regulamenta-cigarro-eletr%C3%B4nico" class="btn btn-primary">Ver mais</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4" id="espaco">
                <div class="card h-100">
                    <img src="assets/img/not3.png" class="card-img-top" alt="Imagem da Notícia" id="notimg">
                    <div class="card-body">
                        <h5 class="card-title">Tadeu Marroco, presidente mundial da BAT: "O cigarro eletrônico é a solução"</h5>
                        <p class="card-text">Engenheiro de formação, Tadeu Marroco, de 58 anos, trabalha há 30 anos no mundo dos cigarros. O executivo, que passou por diversas posições de liderança da companhia na América Latina.</p>
                    </div>
                    <div class="card-footer">
                        <a href="https://veja.abril.com.br/economia/tadeu-marroco-presidente-mundial-da-bat-o-cigarro-eletronico-e-a-solucao" class="btn btn-primary">Ver mais</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<h1>&nbsp;</h1>
<h1>&nbsp;</h1>
<footer>
    <div class="container-fluid">
        <div class="row">
            <div class="col-6 col-lg-10">
                <h6>Todos os direitos reservados | EcoAsh | 2024</h6>
                <P>Suporte:</P>
                <p><a href="https://wa.me/5537991233885?text=Slep%20no%20flep">+55 (37) 99123-3885</a> | <a href="mailto:business.morais.lucas@outlook.com">ecoash@suporte.com.br</a></p>
            </div>
            <div class="col-6 col-lg-2" id="logofooter">
                <?php if ($logged_in) : ?>
                    <a href="logout.php">Logout</a>
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
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<script src="assets/js/javascript.js"></script>
<script>
    // Verifica se há um parâmetro de logout na URL e exibe uma mensagem
    const urlParams = new URLSearchParams(window.location.search);

    if (urlParams.has('logout') && urlParams.get('logout') === 'success') {
        alert('Logout efetuado com sucesso.');
        window.location.href = 'index.php';
    }

    // Verifica se há um parâmetro de ticket_success na URL e exibe uma mensagem
    if (urlParams.has('ticket_success') && urlParams.get('ticket_success') === 'true') {
        alert('Ticket adicionado à sua conta com sucesso.');
    }

    // Verifica se há um parâmetro de erro na URL e exibe uma mensagem
    if (urlParams.has('error_message')) {
        alert(urlParams.get('error_message'));
        setTimeout(() => {
            window.location.href = 'index.php';
        }, 2000); // Atraso de 2 segundos
    }
</script>  
<script>
    // Função para obter parâmetros da URL
    function getQueryParameter(name) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(name);
    }

    // Obter a mensagem da URL
    const message = getQueryParameter('message');

    // Exibir o alert se a mensagem estiver presente
    if (message) {
        alert(decodeURIComponent(message));
    }
</script>

</body>
</html>
