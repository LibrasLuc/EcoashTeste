-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 05/08/2024 às 19:20
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `tcc`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `items`
--

INSERT INTO `items` (`id`, `name`, `price`, `description`, `image_url`) VALUES
(1, 'Consulta com o Psicólogo', 150.00, 'Marque uma consulta com um profissional da área para apoio emocional.', NULL),
(2, 'Livro - Livre-se do cigarro', 32.00, 'Livro de auto-ajuda sobre o vício escrito por um autor renomado.', NULL),
(3, 'Champyx Gotas 30ml', 30.00, 'Champyx Gotas 30ml: Suplemento Alimentar em Gotas para Combate ao Vício do Cigarro.', NULL),
(4, 'Nicorette 4mg Sabor Icemint 30 Gomas Mastigáveis.', 40.00, 'Ajuda a branquear os dentes, amenizando o amarelado causado pelo tabaco. A embalagem contém 30 unidades com 4 mg de nicotina cada.', NULL),
(5, 'Adesivo Para Parar De Fumar Com 60 Extratos.', 150.00, 'Colado na pele, o adesivo libera nicotina no organismo, durante as 24 horas depois de aplicado assim ajudando na reabilitação.', NULL),
(6, 'Check-Up de Pulmão e raio-x completo do tórax', 300.00, 'Exames para o pulmão, sangue, urina e fezes para ver sua situação atual.Acompanhamento com um profissional com atendimento adaptativo.', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `purchases`
--

CREATE TABLE `purchases` (
  `id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `purchase_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `purchases`
--

INSERT INTO `purchases` (`id`, `item_name`, `username`, `email`, `purchase_date`) VALUES
(1, 'Livro - Livre-se do cigarro', 'lucas', 'lucas@gmail.com', '2024-08-05 17:19:39');

-- --------------------------------------------------------

--
-- Estrutura para tabela `ticket`
--

CREATE TABLE `ticket` (
  `id` int(11) NOT NULL,
  `code` varchar(255) NOT NULL,
  `valid` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `ticket`
--

INSERT INTO `ticket` (`id`, `code`, `valid`, `created_at`) VALUES
(1, 'PDFXP', 1, '2024-08-05 17:17:44'),
(2, 'LOCAL', 1, '2024-08-05 17:18:27'),
(3, 'EXTRA', 0, '2024-08-05 17:18:36');

-- --------------------------------------------------------

--
-- Estrutura para tabela `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `balance` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `balance`) VALUES
(1, 'lucas', 'lucas@gmail.com', '$2y$10$vGIv4D1q4JrM0T0OqMnNSe2CPtsZJK3zLkHrhfi9hH8Kkq4uuoyWS', 18.00);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `ticket`
--
ALTER TABLE `ticket`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Índices de tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `ticket`
--
ALTER TABLE `ticket`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
