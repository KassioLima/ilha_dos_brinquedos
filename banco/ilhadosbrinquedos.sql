-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 15-Dez-2018 às 20:27
-- Versão do servidor: 10.1.28-MariaDB
-- PHP Version: 7.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ilhadosbrinquedos`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `brinquedo`
--

CREATE TABLE `brinquedo` (
  `id` int(11) NOT NULL,
  `nome` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `preco` int(11) NOT NULL,
  `reservado_data` longtext NOT NULL,
  `confirmado_data` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `brinquedo`
--

INSERT INTO `brinquedo` (`id`, `nome`, `preco`, `reservado_data`, `confirmado_data`) VALUES
(1, 'Pula-pula mini (1,50m de diÃ¢metro)', 80, '|2018-12-30|2018-12-29', ''),
(2, 'Pula-pula pequeno (2,44m de diÃ¢metro)', 90, '', ''),
(3, 'Pula-pula mÃ©dio (3,10m de diÃ¢metro)', 110, '', ''),
(4, 'Pula-pula grande (3,70m de diÃ¢metro)', 130, '', ''),
(5, 'Piscina de bolinha (1,10m x 1,10m)', 80, '', ''),
(6, 'Piscina de bolinha (1,50m x 1,50m)', 90, '', ''),
(7, 'TotÃ³ infantil', 70, '', ''),
(8, 'TotÃ³ grande', 100, '', ''),
(9, 'Mini Ã¡rea baby', 250, '', ''),
(10, 'Ãrea baby completa', 350, '', ''),
(11, 'Escorrega', 50, '', '');

-- --------------------------------------------------------

--
-- Estrutura da tabela `cliente`
--

CREATE TABLE `cliente` (
  `cpf` varchar(14) NOT NULL,
  `nome` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `codigo` varchar(8) NOT NULL,
  `email` tinytext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `cliente`
--

INSERT INTO `cliente` (`cpf`, `nome`, `codigo`, `email`) VALUES
('17774116702', 'Kassio', '00000', 'kassio.lima.p@gmail.com'),
('17774116703', 'Marcos GonÃ§alves', '00000', 'kassio.lima.pereira@gmail.com'),
('17774116704', 'Jeferson', '65794', 'appjef36@gmail.com');

-- --------------------------------------------------------

--
-- Estrutura da tabela `evento`
--

CREATE TABLE `evento` (
  `id` int(11) NOT NULL,
  `nome` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `cpf` varchar(20) NOT NULL,
  `cell` varchar(30) NOT NULL,
  `endereco` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `cep` varchar(20) NOT NULL,
  `ref` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `data` date NOT NULL,
  `h_comeco` varchar(10) NOT NULL,
  `h_fim` varchar(10) NOT NULL,
  `brinquedos` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `termos_aceitos` tinyint(1) NOT NULL,
  `abertura` varchar(25) NOT NULL,
  `valor` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `passado` tinyint(1) NOT NULL,
  `sinal_pago` tinyint(1) NOT NULL,
  `tudo_pago` tinyint(1) NOT NULL,
  `entregue` tinyint(1) NOT NULL,
  `devolvido` tinyint(1) NOT NULL,
  `dano` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `dano_pago` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `evento`
--

INSERT INTO `evento` (`id`, `nome`, `cpf`, `cell`, `endereco`, `cep`, `ref`, `data`, `h_comeco`, `h_fim`, `brinquedos`, `termos_aceitos`, `abertura`, `valor`, `passado`, `sinal_pago`, `tudo_pago`, `entregue`, `devolvido`, `dano`, `dano_pago`) VALUES
(147, 'Evento sem nome', '17774116702', '27820544', 'Vila Leopoldina, Rua TeresÃ³polis, 142, casa 3', '25030070', 'PrÃ³ximo Ã  Drogaria Flor do Corte 8', '2018-12-29', '00:00', '05:00', '|1#80#campo1', 1, '09/12/2018_21:22:57', '80', 0, 0, 0, 0, 0, '', 0),
(148, '', '17774116702', '27820544', 'Vila Leopoldina, Rua TeresÃ³polis, 142, casa 3', '25030070', 'PrÃ³ximo Ã  Drogaria Flor do Corte 8', '2018-12-30', '00:00', '05:00', '|1#80#campo1', 1, '10/12/2018_03:05:36', '80', 0, 0, 0, 0, 0, '', 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `redefinicao_de_senha`
--

CREATE TABLE `redefinicao_de_senha` (
  `cod` varchar(115) NOT NULL,
  `cpf` varchar(11) NOT NULL,
  `data_hora` varchar(20) NOT NULL,
  `resolvido_hora` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `redefinicao_de_senha`
--

INSERT INTO `redefinicao_de_senha` (`cod`, `cpf`, `data_hora`, `resolvido_hora`) VALUES
('RESOLVIDO', '17774116702', '04/11/2018_12:58:04', '04/11/2018_12:58:25'),
('RESOLVIDO', '17774116702', '11/11/2018_00:46:14', '11/11/2018_00:48:41'),
('93254941263739657823', '17774116704', '15/11/2018_20:29:00', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brinquedo`
--
ALTER TABLE `brinquedo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`cpf`);

--
-- Indexes for table `evento`
--
ALTER TABLE `evento`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `brinquedo`
--
ALTER TABLE `brinquedo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `evento`
--
ALTER TABLE `evento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=149;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
