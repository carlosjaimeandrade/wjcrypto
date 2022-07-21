-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 21-Jul-2022 às 17:44
-- Versão do servidor: 10.4.24-MariaDB
-- versão do PHP: 7.4.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `wjcrypto`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `accounts`
--

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `account` varchar(350) NOT NULL,
  `value` varchar(350) NOT NULL,
  `users_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `accounts`
--

INSERT INTO `accounts` (`id`, `account`, `value`, `users_id`) VALUES
(50, 'MTA4MTk=', 'Mjc2NzUuMzM=', 108),
(51, 'MTA5OTE=', 'MTIxMDQ3NC43Mw==', 109);

-- --------------------------------------------------------

--
-- Estrutura da tabela `history`
--

CREATE TABLE `history` (
  `id` int(11) NOT NULL,
  `description` varchar(250) NOT NULL,
  `category` varchar(50) NOT NULL,
  `createdAt` date NOT NULL DEFAULT current_timestamp(),
  `users_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `history`
--

INSERT INTO `history` (`id`, `description`, `category`, `createdAt`, `users_id`) VALUES
(2, 'Deposito de 2.323,23', 'deposit', '2022-07-21', 109),
(3, 'Retira de 1.212,12', 'removal', '2022-07-21', 109),
(4, 'Retira de 22,22', 'removal', '2022-07-21', 109),
(5, 'Depósito de 10,00', 'deposit', '2022-07-21', 109),
(6, 'Transferencia de 10,00 para Otavio', 'transfer', '2022-07-21', 109),
(7, 'Transferência de 4.444,44 para Otavio', 'transfer', '2022-07-21', 109),
(8, 'Login realizado', 'login', '2022-07-21', 109),
(9, 'Login realizado', 'login', '2022-07-21', 108),
(10, 'Login realizado', 'login', '2022-07-21', 109),
(11, 'Retira de 2.342,34', 'removal', '2022-07-21', 109),
(12, 'Depósito de 2.342,34', 'deposit', '2022-07-21', 109),
(13, 'Depósito de 34.243,24', 'deposit', '2022-07-21', 109);

-- --------------------------------------------------------

--
-- Estrutura da tabela `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(150) NOT NULL,
  `type` varchar(4) NOT NULL,
  `cpf_cnpj` varchar(14) NOT NULL,
  `rg_ie` int(12) NOT NULL,
  `date_birth` date NOT NULL,
  `telephone` varchar(10) NOT NULL,
  `address` varchar(300) NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `type`, `cpf_cnpj`, `rg_ie`, `date_birth`, `telephone`, `address`, `createdAt`) VALUES
(108, 'Otavio', 'otavio_andrek@hotmail.com', '202cb962ac59075b964b07152d234b70', 'cpf', '54545842544565', 545484852, '1996-01-30', '3598605545', 'Rua são joão del rei', '2022-07-17 17:10:18'),
(109, 'Carlos Jaime', 'jaime_andrek@hotmail.com', '202cb962ac59075b964b07152d234b70', 'cpf', '54545842544565', 545484852, '1996-01-30', '3598605545', 'Rua são joão del rei', '2022-07-17 18:30:29');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_users_id` (`users_id`);

--
-- Índices para tabela `history`
--
ALTER TABLE `history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_history_users_id` (`users_id`);

--
-- Índices para tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT de tabela `history`
--
ALTER TABLE `history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `accounts`
--
ALTER TABLE `accounts`
  ADD CONSTRAINT `fk_users_id` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`);

--
-- Limitadores para a tabela `history`
--
ALTER TABLE `history`
  ADD CONSTRAINT `fk_history_users_id` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
