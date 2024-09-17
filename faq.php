<?php
session_start();

// Verificar se a sessão de usuário está definida
if (isset($_SESSION['username'])) {
    $logged_in = true;
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
    <link rel="shortcut icon" href="assets/img/logo.png" type="image/x-icon"><link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&family=Playwrite+NG+Modern:wght@100..400&display=swap" rel="stylesheet">
    <title>EcoAsh - FAQ</title>
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
                            <a class="dropdown-item" href="register.php">Cadastro | Login</a>
                            <a class="dropdown-item" href="loja.php">Loja</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <main>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-md-5">
                    <div class="hidden" id="animateMe">
                        <h1 id="title"><u>Qual a nossa demanda?</u></h1>
                        <br>
                        <h3 id="text">A demanda selecionada por nossa equipe envolve o desenvolvimento e implementação de um sistema de logística reversa destinado ao descarte adequado e à coleta de bitucas de cigarro, com o objetivo de encaminhá-las para reciclagem e garantir a limpeza eficiente de áreas públicas.</h3>
                    </div>
                </div>
                <div class="col-12 col-md-7">
                    <div class="hidden" id="animateMe">
                        <h1 id="title"><u>Como irá funcionar?</u></h1>
                        <br>
                        <h3 id="text">Nosso projeto será dividido essencialmente em duas partes: o totem de coleta e o website. O totem será construído utilizando materiais sustentáveis, como latão, e operará de forma simples, sem a necessidade de energia elétrica. Será estrategicamente posicionado em praças e locais públicos de fácil acesso e visibilidade para os transeuntes.</h3>
                        <br>
                        <h3 id="text">O website funcionará em conjunto com o totem, oferecendo um espaço onde serão disponibilizados serviços de tratamento e outros benefícios em troca do descarte correto das bitucas. Este sistema funcionará através de tickets emitidos pelo totem quando um número específico de bitucas for depositado dentro de um prazo determinado (por exemplo, um mês).</h3>
                        <br>
                        <h3 id="text">Um profissional designado será responsável por visitar o local do totem, realizar a coleta das bitucas e reiniciar o contador para um novo ciclo. Além disso, ele será encarregado de trocar a quantidade necessária de cigarros para a liberação dos tickets.</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-7">
                    <div class="hidden" id="animateMe">
                        <h1 id="title"><u>Qual a proposta do grupo?</u></h1>
                        <br>
                        <h3 id="text">A proposta da equipe consiste na implementação de um sistema que visa incentivar os fumantes a realizar o descarte adequado das bitucas, além de oferecer suporte para o tratamento da dependência tabágica. Isso será realizado por meio de parcerias estratégicas com clínicas especializadas em tratamento de tabagismo e órgãos públicos, como a prefeitura da cidade onde o projeto será desenvolvido</h3>
                    </div>
                </div>
                <div class="col-12 col-md-5">
                    <div class="hidden" id="animateMe">
                        <h1 id="title"><u>Quem são os parceiros?</u></h1>
                        <br>
                        <h3 id="text">Nosso projeto visa estabelecer parcerias com clínicas de tratamento e órgãos públicos para garantir a sustentabilidade do projeto. Com isso, planejamos promover uma relação mutuamente benéfica para todos os envolvidos, incluindo a dinamização da economia local por meio da criação de novos empregos, além do apoio ao tratamento de fumantes nas clínicas parceiras.</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-5">
                    <div class="hidden" id="animateMe">
                        <h1 id="title"><u>Por que escolhemos a demanda?</u></h1>
                        <br>
                        <h3 id="text">A demanda selecionada apresentava um potencial substancial para elaboração e crescimento, além de abrir perspectivas para abordagens que integram tecnologia na concepção da solução. Devido à novidade da informática no âmbito do Serviço Nacional de Aprendizagem Industrial (SENAI), poucas demandas despertaram possibilidade para o desenvolvimento de soluções.</h3>
                    </div>
                </div>
                <div class="col-12 col-md-7">
                    <div class="hidden" id="animateMe">
                        <h1 id="title"><u>Quais os objetivos?</u></h1>
                        <br>
                        <h3 id="text">Nosso objetivo principal é facilitar o descarte adequado e a reciclagem de bitucas de cigarro, além de impulsionar a economia local através da criação de novos empregos e da prestação de serviços que promovam o bem-estar público.</h3>
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
                    <P>Suporte:</P>
                    <p><a href="https://wa.me/5537991233885?text=Slep%20no%20flep">+55 (37) 99123-3885</a> | <a href="mailto:business.morais.lucas@outlook.com">ecoash@suporte.com.br</a></p>
                </div>
                <div class="col-6 col-lg-2" id="logofooter">
                <?php if (isset($_SESSION['username'])) : ?>
                    <a href="logout2.php">Logout</a>
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
        }
    </script>  
</body>
</html>










