-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 26-Mar-2020 às 14:04
-- Versão do servidor: 10.4.11-MariaDB
-- versão do PHP: 7.2.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `corona_virus`
--
CREATE DATABASE IF NOT EXISTS `corona_virus` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `corona_virus`;

-- --------------------------------------------------------

--
-- Estrutura da tabela `adm`
--

CREATE TABLE `adm` (
  `id` int(11) NOT NULL,
  `nome` varchar(70) NOT NULL,
  `login` varchar(70) NOT NULL,
  `senha` varchar(32) NOT NULL,
  `telefone` varchar(11) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELACIONAMENTOS PARA TABELAS `adm`:
--

--
-- Extraindo dados da tabela `adm`
--

INSERT INTO `adm` (`id`, `nome`, `login`, `senha`, `telefone`, `email`) VALUES
(1, 'Israel Silva', 'israelitalo', '8CDE6F42481714DA249DDAFBC419C88F', '81995309618', 'israelitalo@hotmail.com');

-- --------------------------------------------------------

--
-- Estrutura da tabela `diagnostico_virus`
--

CREATE TABLE `diagnostico_virus` (
  `id` int(11) NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELACIONAMENTOS PARA TABELAS `diagnostico_virus`:
--

--
-- Extraindo dados da tabela `diagnostico_virus`
--

INSERT INTO `diagnostico_virus` (`id`, `status`) VALUES
(1, 'positivo'),
(2, 'negativo'),
(3, 'suspeito');

-- --------------------------------------------------------

--
-- Estrutura da tabela `historico`
--

CREATE TABLE `historico` (
  `id` int(11) NOT NULL,
  `id_hospital` int(11) NOT NULL,
  `id_paciente` int(11) NOT NULL,
  `id_diagnostico` int(11) NOT NULL,
  `data_entrada` date NOT NULL DEFAULT current_timestamp(),
  `data_saida` date DEFAULT NULL,
  `motivoalta` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELACIONAMENTOS PARA TABELAS `historico`:
--   `id_diagnostico`
--       `diagnostico_virus` -> `id`
--   `id_hospital`
--       `hospital` -> `id`
--   `id_paciente`
--       `paciente` -> `id`
--

--
-- Extraindo dados da tabela `historico`
--

INSERT INTO `historico` (`id`, `id_hospital`, `id_paciente`, `id_diagnostico`, `data_entrada`, `data_saida`, `motivoalta`) VALUES
(6, 3, 8, 1, '2020-03-11', '2020-03-16', 1),
(12, 5, 2, 2, '2020-03-01', '2020-03-08', 1),
(13, 5, 6, 1, '2020-03-06', '2020-03-10', 2),
(14, 3, 7, 1, '2020-03-07', '2020-03-12', 2),
(15, 5, 2, 2, '2020-03-09', '2020-03-10', 1),
(16, 5, 2, 3, '2020-03-04', '2020-03-11', 1),
(17, 3, 8, 1, '2020-03-20', '2020-03-24', 1),
(18, 5, 2, 2, '2020-03-23', NULL, NULL),
(19, 3, 8, 1, '2020-03-12', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `hospital`
--

CREATE TABLE `hospital` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `cnpj` varchar(14) NOT NULL,
  `telefone` varchar(11) NOT NULL,
  `rua` varchar(70) NOT NULL,
  `numero` varchar(11) NOT NULL,
  `bairro` varchar(70) NOT NULL,
  `cidade` varchar(70) NOT NULL,
  `estado` varchar(2) NOT NULL,
  `cep` varchar(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELACIONAMENTOS PARA TABELAS `hospital`:
--

--
-- Extraindo dados da tabela `hospital`
--

INSERT INTO `hospital` (`id`, `nome`, `cnpj`, `telefone`, `rua`, `numero`, `bairro`, `cidade`, `estado`, `cep`) VALUES
(1, 'Unidade Hospitalar', '00000000000000', '8137283259', 'av.', 's/n', 'Centro', 'bezerros', 'PE', '55660000'),
(2, 'Mestre vitalino', '01234567888955', '81995309618', 'rua das flores', '29', 'Santo Amaro', 'Bezerros', 'PE', '55660000'),
(3, 'Hospital Real Portugues', '123456789', '81999999999', 'Av. Recife', '1000', 'Boa Viagem', 'Recife', 'PE', '45680465'),
(5, 'hospital jesus pequenino', '10072296000533', '8199998989', 'rua', 'numero', 'bairro', 'bezerros', 'pe', '55660000'),
(6, 'Unidade Mista São José', '321654987', '819566548', 'Rua Doutor José Mariano', '132', 'Centro', 'Caruaru', 'PE', '55660000'),
(7, 'hospital 1', '123456789', '81995309618', 'rua das flores, 29', '123', 'bairro', 'Bezerros', 'PE', '55660000'),
(8, 'hospital 2', '98756421', '81995309618', 'rua das flores, 29', '12', 'bairro', 'Bezerros', 'PE', '55660000'),
(9, 'hospital 3', '123798456', '81995309618', 'rua das flores, 29', '321', 'bairro', 'Bezerros', 'PE', '55660000'),
(10, 'hospital 4', '987357159', '81995309618', 'rua das flores, 29', '321', 'bairro', 'Bezerros', 'PE', '55660000'),
(11, 'hospital 5', '951842687', '81995309618', 'rua das flores, 29', '6549', 'bairro', 'Bezerros', 'PE', '55660000');

-- --------------------------------------------------------

--
-- Estrutura da tabela `paciente`
--

CREATE TABLE `paciente` (
  `id` int(11) NOT NULL,
  `id_hospital` int(11) NOT NULL,
  `nome` varchar(70) NOT NULL,
  `cpf` varchar(11) NOT NULL,
  `sexo` varchar(1) DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL,
  `rua` varchar(70) NOT NULL,
  `numero` varchar(11) NOT NULL,
  `bairro` varchar(70) NOT NULL,
  `cidade` varchar(100) NOT NULL,
  `estado` varchar(2) NOT NULL,
  `cep` varchar(8) NOT NULL,
  `telefone` varchar(11) NOT NULL,
  `vida` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELACIONAMENTOS PARA TABELAS `paciente`:
--   `id_hospital`
--       `hospital` -> `id`
--

--
-- Extraindo dados da tabela `paciente`
--

INSERT INTO `paciente` (`id`, `id_hospital`, `nome`, `cpf`, `sexo`, `data_nascimento`, `rua`, `numero`, `bairro`, `cidade`, `estado`, `cep`, `telefone`, `vida`) VALUES
(2, 5, 'Talico da Silva', '12345678910', 'm', '2007-09-17', 'Rua das flores', '29', 'Santo Amaro', 'Bezerros', 'PE', '55660000', '81995309618', 1),
(6, 5, 'Maria Do Socorro', '98745623135', 'f', '1970-07-12', 'Rua Doutor JosÃ© Mariano', '986', 'Nossa Senhora Das Dores', 'Caruaru', 'PE', '55002000', '8137281586', 2),
(7, 3, 'Teste', '123456789', 'm', '1970-08-07', 'Rua Doutor JosÃ© Mariano', '600', 'Nossa Senhora Das Dores', 'Caruaru', 'PE', '55002000', '12345', 2),
(8, 3, 'Israel Ítalo Bernardo Cabral Silva', '06900661475', 'm', '1991-10-05', 'Rua das flores', '29', 'Santo Amaro', 'Bezerros', 'PE', '55660000', '81995309618', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `ativo` int(1) NOT NULL,
  `nome` varchar(70) NOT NULL,
  `login` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(32) NOT NULL,
  `id_hospital` int(11) NOT NULL,
  `telefone` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELACIONAMENTOS PARA TABELAS `usuario`:
--   `id_hospital`
--       `hospital` -> `id`
--

--
-- Extraindo dados da tabela `usuario`
--

INSERT INTO `usuario` (`id`, `ativo`, `nome`, `login`, `email`, `senha`, `id_hospital`, `telefone`) VALUES
(11, 1, 'Israel ítalo Bernardo Cabral Silva', 'israelitalo', 'israelitalo2012@gmail.com', 'd01f994c50026690f784f625a0863f67', 3, '81995309618'),
(14, 1, 'Nataly Costa Mendes', 'nataly', 'nataly_mendes@outlook.com', 'e8d95a51f3af4a3b134bf6bb680a213a', 5, '81995309618');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `adm`
--
ALTER TABLE `adm`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `diagnostico_virus`
--
ALTER TABLE `diagnostico_virus`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `historico`
--
ALTER TABLE `historico`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_paciente_historico` (`id_paciente`),
  ADD KEY `id_hospital_historico` (`id_hospital`),
  ADD KEY `id_diagnostico_historico` (`id_diagnostico`);

--
-- Índices para tabela `hospital`
--
ALTER TABLE `hospital`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `paciente`
--
ALTER TABLE `paciente`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_hospital_paciente` (`id_hospital`);

--
-- Índices para tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_hospital_usuario` (`id_hospital`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `adm`
--
ALTER TABLE `adm`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `diagnostico_virus`
--
ALTER TABLE `diagnostico_virus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `historico`
--
ALTER TABLE `historico`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de tabela `hospital`
--
ALTER TABLE `hospital`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `paciente`
--
ALTER TABLE `paciente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `historico`
--
ALTER TABLE `historico`
  ADD CONSTRAINT `id_diagnostico_historico` FOREIGN KEY (`id_diagnostico`) REFERENCES `diagnostico_virus` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `id_hospital_historico` FOREIGN KEY (`id_hospital`) REFERENCES `hospital` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `id_paciente_historico` FOREIGN KEY (`id_paciente`) REFERENCES `paciente` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `paciente`
--
ALTER TABLE `paciente`
  ADD CONSTRAINT `id_hospital_paciente` FOREIGN KEY (`id_hospital`) REFERENCES `hospital` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `id_hospital_usuario` FOREIGN KEY (`id_hospital`) REFERENCES `hospital` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
