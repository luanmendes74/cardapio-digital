-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 06/09/2025 às 06:31
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
-- Banco de dados: `cardapio_saas_db`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `estabelecimento_id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `ordem` int(11) DEFAULT 0,
  `ativo` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `categorias`
--

INSERT INTO `categorias` (`id`, `estabelecimento_id`, `nome`, `descricao`, `ordem`, `ativo`, `created_at`, `updated_at`) VALUES
(1, 1, 'Pizzas Salgadas 2', 'As melhores pizzas da região!', 1, 1, '2025-09-03 06:14:14', '2025-09-03 12:54:18'),
(2, 1, 'Bebidas', 'Refrigerantes, sucos e águas.', 2, 1, '2025-09-03 06:14:14', '2025-09-03 06:14:14'),
(3, 2, 'Hambúrgueres', 'Nossos burgers artesanais.', 1, 1, '2025-09-03 06:14:14', '2025-09-03 06:14:14'),
(4, 2, 'Porções', 'Para compartilhar com a galera.', 2, 1, '2025-09-03 06:14:14', '2025-09-03 06:14:14'),
(5, 1, 'Platos', '', 0, 1, '2025-09-03 12:54:45', '2025-09-03 12:54:45'),
(6, 1, 'Platos', '', 0, 1, '2025-09-03 12:54:45', '2025-09-03 12:54:45'),
(7, 1, 'teste', '', 0, 1, '2025-09-03 12:55:06', '2025-09-03 12:55:06'),
(8, 3, 'r', 's', 0, 1, '2025-09-04 18:09:02', '2025-09-04 18:09:02'),
(9, 8, 'Pizzaria Tope', 'ds', 0, 1, '2025-09-04 19:54:57', '2025-09-04 19:54:57'),
(10, 8, 'teste', 'ht', 1, 1, '2025-09-05 16:09:42', '2025-09-05 16:09:42'),
(11, 8, 'Pizzaria Topedww', '', 0, 1, '2025-09-06 00:59:10', '2025-09-06 00:59:10');

-- --------------------------------------------------------

--
-- Estrutura para tabela `configuracao_delivery`
--

CREATE TABLE `configuracao_delivery` (
  `id` int(11) NOT NULL,
  `estabelecimento_id` int(11) NOT NULL,
  `ativo` tinyint(1) DEFAULT 1,
  `taxa_entrega` decimal(10,2) DEFAULT 0.00,
  `valor_minimo_pedido` decimal(10,2) DEFAULT 0.00,
  `tempo_estimado_min` int(11) DEFAULT 30,
  `tempo_estimado_max` int(11) DEFAULT 60,
  `bairros_atendidos` text DEFAULT NULL,
  `horario_funcionamento` text DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `configuracao_delivery`
--

INSERT INTO `configuracao_delivery` (`id`, `estabelecimento_id`, `ativo`, `taxa_entrega`, `valor_minimo_pedido`, `tempo_estimado_min`, `tempo_estimado_max`, `bairros_atendidos`, `horario_funcionamento`, `observacoes`, `created_at`, `updated_at`) VALUES
(1, 8, 1, 5.00, 20.00, 30, 60, 'Centro, Zona Sul, Zona Norte', 'Segunda a Sexta: 11h às 22h, Sábado e Domingo: 11h às 23h', '', '2025-09-05 05:43:08', '2025-09-05 05:43:54'),
(2, 11, 1, 5.00, 20.00, 30, 60, 'Centro, Zona Sul, Zona Norte', 'Segunda a Sexta: 11h às 22h, Sábado e Domingo: 11h às 23h', NULL, '2025-09-05 05:43:08', '2025-09-05 05:43:08'),
(3, 2, 1, 5.00, 20.00, 30, 60, 'Centro, Zona Sul, Zona Norte', 'Segunda a Sexta: 11h às 22h, Sábado e Domingo: 11h às 23h', NULL, '2025-09-05 05:43:08', '2025-09-05 05:43:08'),
(4, 1, 1, 5.00, 20.00, 30, 60, 'Centro, Zona Sul, Zona Norte', 'Segunda a Sexta: 11h às 22h, Sábado e Domingo: 11h às 23h', NULL, '2025-09-05 05:43:08', '2025-09-05 05:43:08');

-- --------------------------------------------------------

--
-- Estrutura para tabela `configuracoes_layout`
--

CREATE TABLE `configuracoes_layout` (
  `id` int(11) NOT NULL,
  `estabelecimento_id` int(11) NOT NULL,
  `cor_primaria` varchar(7) DEFAULT '#3B82F6',
  `cor_secundaria` varchar(7) DEFAULT '#1E40AF',
  `cor_sucesso` varchar(7) DEFAULT '#10B981',
  `cor_aviso` varchar(7) DEFAULT '#F59E0B',
  `cor_erro` varchar(7) DEFAULT '#EF4444',
  `logo_url` varchar(255) DEFAULT NULL,
  `nome_estabelecimento` varchar(255) NOT NULL,
  `tema` varchar(20) DEFAULT 'claro',
  `fonte` varchar(50) DEFAULT 'Inter',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `configuracoes_layout`
--

INSERT INTO `configuracoes_layout` (`id`, `estabelecimento_id`, `cor_primaria`, `cor_secundaria`, `cor_sucesso`, `cor_aviso`, `cor_erro`, `logo_url`, `nome_estabelecimento`, `tema`, `fonte`, `created_at`, `updated_at`) VALUES
(1, 1, '#3B82F6', '#1E40AF', '#10B981', '#F59E0B', '#EF4444', NULL, 'Pizzaria Tope', 'claro', 'Inter', '2025-09-05 15:14:52', '2025-09-05 15:14:52'),
(2, 2, '#3B82F6', '#1E40AF', '#10B981', '#F59E0B', '#EF4444', NULL, 'Lanchonete', 'claro', 'Inter', '2025-09-05 15:14:52', '2025-09-05 15:14:52'),
(3, 8, '#3B82F6', '#1E40AF', '#10B981', '#F59E0B', '#EF4444', NULL, 'Coca-Cola Lata', 'claro', 'Inter', '2025-09-05 15:14:52', '2025-09-05 15:14:52'),
(4, 11, '#3B82F6', '#1E40AF', '#10B981', '#F59E0B', '#EF4444', NULL, 'teste 2', 'claro', 'Inter', '2025-09-05 15:14:52', '2025-09-05 15:14:52');

-- --------------------------------------------------------

--
-- Estrutura para tabela `estabelecimentos`
--

CREATE TABLE `estabelecimentos` (
  `id` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `subdominio` varchar(50) NOT NULL,
  `email` varchar(150) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `endereco` text DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `descricao_curta` varchar(255) DEFAULT NULL,
  `whatsapp` varchar(20) DEFAULT NULL,
  `instagram` varchar(255) DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `cor_primaria` varchar(7) DEFAULT '#dc2626',
  `cor_secundaria` varchar(7) DEFAULT '#f9fafb',
  `cor_texto_header` varchar(7) NOT NULL DEFAULT '#FFFFFF',
  `cor_botao_pedido` varchar(7) NOT NULL DEFAULT '#FFA500',
  `plano_id` int(11) DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `estabelecimentos`
--

INSERT INTO `estabelecimentos` (`id`, `nome`, `subdominio`, `email`, `telefone`, `endereco`, `logo`, `descricao_curta`, `whatsapp`, `instagram`, `facebook`, `cor_primaria`, `cor_secundaria`, `cor_texto_header`, `cor_botao_pedido`, `plano_id`, `ativo`, `created_at`, `updated_at`) VALUES
(1, 'Pizzaria Tope', 'pizzaria-top', 'contato@pizzariatop.com', '11988887777', 'Rua da Pizza, 123', 'logo_1.jpeg', 'Culinária italiana autêntica', '5511930958964', '', '', '#ac3416', '#be2409', '#cdbcbc', '#ffa500', 2, 1, '2025-09-03 06:14:14', '2025-09-04 17:48:59'),
(2, 'Lanchonete', 'lanchonete-lega', 'contato@lanchonetelegal.com', '11955554444', 'Av. do Burger, 456', NULL, NULL, NULL, NULL, NULL, '#2563eb', '#f9fafb', '#FFFFFF', '#FFA500', 1, 1, '2025-09-03 06:14:14', '2025-09-04 21:16:28'),
(8, 'Coca-Cola Lata 4', 'coca', 'admin@coca.com', 'fs', 'fsfs', NULL, 'fas', 'sfaf', 'sfs', 'sfa', '#0b379d', '#081f36', '#ffffff', '#ffa500', NULL, 1, '2025-09-04 19:51:31', '2025-09-06 01:55:03'),
(11, 'teste 2', 'ed', 'admin@ariatoppizz.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '#dc2626', '#f9fafb', '#FFFFFF', '#FFA500', NULL, 1, '2025-09-05 00:46:50', '2025-09-05 00:46:50');

-- --------------------------------------------------------

--
-- Estrutura para tabela `mesas`
--

CREATE TABLE `mesas` (
  `id` int(11) NOT NULL,
  `estabelecimento_id` int(11) NOT NULL,
  `numero` varchar(50) NOT NULL,
  `qr_code` text DEFAULT NULL,
  `status` enum('livre','ocupada') DEFAULT 'livre',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `mesas`
--

INSERT INTO `mesas` (`id`, `estabelecimento_id`, `numero`, `qr_code`, `status`, `created_at`, `updated_at`) VALUES
(5, 1, '7', 'mesa_5_68b981521aca8.png', 'livre', '2025-09-04 12:08:50', '2025-09-04 12:08:50'),
(6, 1, '55', 'mesa_6_68b9815d7b157.png', 'livre', '2025-09-04 12:09:01', '2025-09-04 12:09:01'),
(7, 1, '21', 'mesa_7_68b981653c8c2.png', 'livre', '2025-09-04 12:09:09', '2025-09-04 12:09:09'),
(8, 3, '1', NULL, 'livre', '2025-09-04 18:09:41', '2025-09-04 18:09:41'),
(9, 3, '1', NULL, 'livre', '2025-09-04 18:09:48', '2025-09-04 18:09:48'),
(10, 3, '1', NULL, 'livre', '2025-09-04 18:11:13', '2025-09-04 18:11:13'),
(14, 8, '2', 'mesa_14_68b9f02c3a397.png', 'livre', '2025-09-04 20:01:48', '2025-09-04 20:01:48'),
(15, 8, '45', 'mesa_15_68ba578db8536.png', 'livre', '2025-09-05 03:22:53', '2025-09-05 03:22:53'),
(16, 8, '77', 'mesa_16_68ba76ffca02a.png', 'livre', '2025-09-05 05:37:03', '2025-09-05 05:37:03'),
(17, 8, '67', 'mesa_17_68bb092cc4efa.png', 'livre', '2025-09-05 16:00:44', '2025-09-05 16:00:44'),
(18, 8, '644', 'mesa_18_68bb0b70e8fa1.png', 'livre', '2025-09-05 16:10:24', '2025-09-05 16:10:24'),
(21, 8, '78', 'mesa_21_78.png', 'livre', '2025-09-06 01:10:41', '2025-09-06 01:10:42');

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `estabelecimento_id` int(11) NOT NULL,
  `mesa_id` int(11) DEFAULT NULL,
  `cliente_nome` varchar(150) DEFAULT NULL,
  `cliente_telefone` varchar(20) DEFAULT NULL,
  `tipo` enum('mesa','delivery') NOT NULL DEFAULT 'mesa',
  `status` enum('recebido','preparando','pronto','entregue','cancelado') NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `observacoes` text DEFAULT NULL,
  `endereco_entrega` text DEFAULT NULL,
  `bairro` varchar(100) DEFAULT NULL,
  `cidade` varchar(100) DEFAULT NULL,
  `cep` varchar(10) DEFAULT NULL,
  `taxa_entrega` decimal(10,2) DEFAULT 0.00,
  `tempo_estimado` int(11) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `tempo_preparo_estimado` int(11) DEFAULT 30 COMMENT 'Tempo estimado em minutos'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pedidos`
--

INSERT INTO `pedidos` (`id`, `estabelecimento_id`, `mesa_id`, `cliente_nome`, `cliente_telefone`, `tipo`, `status`, `total`, `observacoes`, `endereco_entrega`, `bairro`, `cidade`, `cep`, `taxa_entrega`, `tempo_estimado`, `telefone`, `created_at`, `updated_at`, `tempo_preparo_estimado`) VALUES
(1, 8, 14, NULL, NULL, 'mesa', '', 26.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, NULL, '2025-09-05 03:10:05', '2025-09-05 05:10:32', 30),
(2, 8, 15, NULL, NULL, 'mesa', '', 6.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, NULL, '2025-09-05 03:23:07', '2025-09-05 05:10:31', 30),
(3, 8, 14, NULL, NULL, 'mesa', '', 24.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, NULL, '2025-09-05 03:26:12', '2025-09-05 05:10:26', 30),
(4, 8, 14, NULL, NULL, 'mesa', '', 6.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, NULL, '2025-09-05 04:42:30', '2025-09-05 05:10:25', 30),
(5, 1, 1, NULL, NULL, 'mesa', 'recebido', 51.50, '', NULL, NULL, NULL, NULL, 0.00, NULL, NULL, '2025-09-05 04:49:02', '2025-09-05 04:49:02', 30),
(6, 1, 1, NULL, NULL, 'mesa', 'recebido', 45.50, '', NULL, NULL, NULL, NULL, 0.00, NULL, NULL, '2025-09-05 04:50:04', '2025-09-05 04:50:04', 30),
(7, 1, 1, NULL, NULL, 'mesa', 'recebido', 45.50, '', NULL, NULL, NULL, NULL, 0.00, NULL, NULL, '2025-09-05 04:50:25', '2025-09-05 04:50:25', 30),
(8, 8, 14, NULL, NULL, 'mesa', '', 6.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, NULL, '2025-09-05 04:55:25', '2025-09-05 05:10:21', 30),
(9, 8, 14, NULL, NULL, 'mesa', '', 58.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, NULL, '2025-09-05 05:13:47', '2025-09-05 05:14:09', 30),
(10, 8, 14, NULL, NULL, 'mesa', '', 6.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, NULL, '2025-09-05 05:15:38', '2025-09-05 05:15:49', 30),
(11, 8, 14, NULL, NULL, 'mesa', '', 12.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, NULL, '2025-09-05 05:27:32', '2025-09-05 05:27:48', 30),
(12, 8, 14, NULL, NULL, 'mesa', '', 426.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, NULL, '2025-09-05 05:28:49', '2025-09-05 05:29:09', 30),
(13, 8, 14, NULL, NULL, 'mesa', '', 6.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, NULL, '2025-09-05 05:33:59', '2025-09-05 05:34:25', 30),
(14, 8, 14, NULL, NULL, 'mesa', '', 20.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, NULL, '2025-09-05 05:34:48', '2025-09-05 05:35:29', 30),
(15, 8, NULL, 'luan', '11930958', 'delivery', '', 29.00, '', 'rua tal', 'm', 'piracaia', '12970-000', 5.00, 45, NULL, '2025-09-05 12:23:06', '2025-09-05 14:35:43', 30),
(16, 8, NULL, 'luan', '11930958', 'delivery', '', 29.00, '', 'rua tal', 'm', 'piracaia', '12970-000', 5.00, 43, NULL, '2025-09-05 12:24:02', '2025-09-05 14:38:59', 30),
(17, 8, NULL, 'luan', '11930958', 'delivery', '', 29.00, '', 'rua tal', 'm', 'piracaia', '12970-000', 5.00, 59, NULL, '2025-09-05 12:24:08', '2025-09-05 14:39:14', 30),
(18, 8, NULL, 'luan', '11930958', 'delivery', '', 29.00, '', 'rua tal', 'm', 'piracaia', '12970-000', 5.00, 59, NULL, '2025-09-05 12:26:35', '2025-09-05 14:39:16', 30),
(19, 8, NULL, 'luan', '11930958', 'delivery', '', 29.00, '', 'rua tal', 'm', 'piracaia', '12970-000', 5.00, 39, NULL, '2025-09-05 12:28:21', '2025-09-05 14:29:34', 30),
(20, 8, NULL, 'luan', '11930958', 'delivery', '', 29.00, '', 'vdsedvgegv', 'm', 'piracaia', '12970-000', 5.00, 45, NULL, '2025-09-05 12:28:51', '2025-09-05 12:40:34', 30),
(21, 8, NULL, 'luan', '11930958', 'delivery', '', 29.00, '', 'vdsedvgegv', 'm', 'piracaia', '12970-000', 5.00, 48, NULL, '2025-09-05 12:31:19', '2025-09-05 12:39:23', 30),
(22, 8, NULL, 'luan', '11930958', 'delivery', '', 35.00, 'vsdfewgsdgsgewgwegd', 'dvs', 'm', 'piracaia', '12970-000', 5.00, 49, NULL, '2025-09-05 12:35:40', '2025-09-05 12:37:35', 30),
(23, 8, 15, NULL, NULL, 'mesa', '', 194.00, 'sdv\\bb\\brb', NULL, NULL, NULL, NULL, 0.00, NULL, NULL, '2025-09-05 12:43:52', '2025-09-05 12:44:28', 30),
(24, 8, 18, NULL, NULL, 'mesa', '', 40.00, 'ioh', NULL, NULL, NULL, NULL, 0.00, NULL, NULL, '2025-09-05 16:10:46', '2025-09-05 16:15:14', 30),
(25, 8, 18, NULL, NULL, 'mesa', 'recebido', 68.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, NULL, '2025-09-05 21:32:03', '2025-09-05 21:32:03', 30);

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedido_itens`
--

CREATE TABLE `pedido_itens` (
  `id` int(11) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  `produto_id` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `preco_unitario` decimal(10,2) NOT NULL,
  `preco_total` decimal(10,2) DEFAULT 0.00,
  `observacoes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pedido_itens`
--

INSERT INTO `pedido_itens` (`id`, `pedido_id`, `produto_id`, `quantidade`, `preco_unitario`, `preco_total`, `observacoes`, `created_at`) VALUES
(1, 1, 10, 1, 6.00, 0.00, '', '2025-09-05 03:10:05'),
(2, 1, 11, 1, 20.00, 0.00, '', '2025-09-05 03:10:05'),
(3, 2, 10, 1, 6.00, 0.00, '', '2025-09-05 03:23:07'),
(4, 3, 10, 4, 6.00, 0.00, '', '2025-09-05 03:26:12'),
(5, 4, 12, 1, 6.00, 0.00, '', '2025-09-05 04:42:30'),
(6, 5, 1, 1, 45.50, 0.00, '', '2025-09-05 04:49:02'),
(7, 5, 7, 1, 6.00, 0.00, '', '2025-09-05 04:49:02'),
(8, 6, 1, 1, 45.50, 0.00, '', '2025-09-05 04:50:04'),
(9, 7, 1, 1, 45.50, 0.00, '', '2025-09-05 04:50:25'),
(10, 8, 12, 1, 6.00, 0.00, '', '2025-09-05 04:55:26'),
(11, 9, 11, 2, 20.00, 0.00, '', '2025-09-05 05:13:47'),
(12, 9, 10, 2, 6.00, 0.00, '', '2025-09-05 05:13:47'),
(13, 9, 12, 1, 6.00, 0.00, '', '2025-09-05 05:13:47'),
(14, 10, 10, 1, 6.00, 0.00, '', '2025-09-05 05:15:38'),
(15, 11, 10, 2, 6.00, 0.00, '', '2025-09-05 05:27:32'),
(16, 12, 10, 1, 6.00, 0.00, '', '2025-09-05 05:28:49'),
(17, 12, 11, 21, 20.00, 0.00, '', '2025-09-05 05:28:49'),
(18, 13, 10, 1, 6.00, 0.00, '', '2025-09-05 05:33:59'),
(19, 14, 11, 1, 20.00, 0.00, '', '2025-09-05 05:34:48'),
(20, 21, 10, 1, 6.00, 6.00, NULL, '2025-09-05 12:31:19'),
(21, 21, 12, 3, 6.00, 18.00, NULL, '2025-09-05 12:31:19'),
(22, 22, 10, 2, 6.00, 12.00, NULL, '2025-09-05 12:35:40'),
(23, 22, 12, 3, 6.00, 18.00, NULL, '2025-09-05 12:35:40'),
(24, 23, 12, 4, 6.00, 0.00, '', '2025-09-05 12:43:52'),
(25, 23, 10, 5, 6.00, 0.00, '', '2025-09-05 12:43:53'),
(26, 23, 11, 7, 20.00, 0.00, '', '2025-09-05 12:43:53'),
(27, 24, 13, 1, 34.00, 0.00, '', '2025-09-05 16:10:46'),
(28, 24, 12, 1, 6.00, 0.00, '', '2025-09-05 16:10:46'),
(29, 25, 13, 2, 34.00, 0.00, '', '2025-09-05 21:32:03');

-- --------------------------------------------------------

--
-- Estrutura para tabela `planos`
--

CREATE TABLE `planos` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `preco_mensal` decimal(10,2) NOT NULL,
  `limite_produtos` int(11) DEFAULT NULL,
  `limite_mesas` int(11) DEFAULT NULL,
  `recursos` text DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `planos`
--

INSERT INTO `planos` (`id`, `nome`, `preco_mensal`, `limite_produtos`, `limite_mesas`, `recursos`, `ativo`, `created_at`) VALUES
(1, 'teste', 20.00, 10, 10, 'teste; tem; 7', 1, '2025-09-05 00:44:26');

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `id` int(11) NOT NULL,
  `estabelecimento_id` int(11) NOT NULL,
  `categoria_id` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `descricao` text DEFAULT NULL,
  `preco` decimal(10,2) NOT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `disponivel` tinyint(1) DEFAULT 1,
  `disponivel_delivery` tinyint(1) DEFAULT 1,
  `destaque` tinyint(1) DEFAULT 0,
  `ordem` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`id`, `estabelecimento_id`, `categoria_id`, `nome`, `descricao`, `preco`, `imagem`, `disponivel`, `disponivel_delivery`, `destaque`, `ordem`, `created_at`, `updated_at`) VALUES
(1, 1, 6, 'Pizza de Calabresa', 'Molho de tomate, queijo mussarela e calabresa fatiada.', 45.50, NULL, 1, 1, 1, 1, '2025-09-03 06:14:14', '2025-09-03 13:11:46'),
(4, 2, 3, 'X-Burger Clássico', 'Pão, burger, queijo e salada.', 25.00, NULL, 1, 1, 1, 1, '2025-09-03 06:14:14', '2025-09-03 06:14:14'),
(5, 2, 3, 'X-Bacon Especial', 'Pão, burger, queijo, bacon crocante e molho especial.', 32.50, NULL, 1, 1, 0, 2, '2025-09-03 06:14:14', '2025-09-03 06:14:14'),
(6, 2, 4, 'Batata Frita com Cheddar e Bacon', 'Porção de 400g.', 28.00, NULL, 1, 1, 0, 1, '2025-09-03 06:14:14', '2025-09-03 06:14:14'),
(7, 1, 2, 'Coca-Cola Lata 2', 'teste', 6.00, '68b835b027c08.jpeg', 1, 1, 1, 0, '2025-09-03 12:33:52', '2025-09-03 12:41:11'),
(8, 1, 2, 'teste', '', 6.00, '', 1, 1, 0, 0, '2025-09-03 13:12:33', '2025-09-03 13:12:33'),
(9, 3, 8, 'ihj', 'o', 6.00, '', 1, 1, 0, 0, '2025-09-04 18:08:33', '2025-09-04 18:09:11'),
(10, 8, 9, 'Pizzaria Tope', 'd', 6.00, '', 1, 1, 0, 0, '2025-09-04 19:55:11', '2025-09-04 19:55:11'),
(11, 8, 9, 'Pizzaria Tope', '', 20.00, '', 1, 1, 0, 0, '2025-09-05 02:57:21', '2025-09-05 02:57:21'),
(12, 8, 9, 'Pizzaria Top', '', 6.00, 'produto_68ba69ebc71a8.jpeg', 1, 1, 1, 0, '2025-09-05 04:41:15', '2025-09-05 04:42:07'),
(13, 8, 9, 'Luan Sadrak de Oliveira mendes', 'z', 34.00, '', 1, 1, 1, 0, '2025-09-05 16:09:14', '2025-09-05 16:09:14'),
(14, 8, 9, 'Pizzaria Topedsawfff', '', 45.50, NULL, 1, 1, 0, 0, '2025-09-06 00:58:20', '2025-09-06 00:58:20');

-- --------------------------------------------------------

--
-- Estrutura para tabela `relatorios_diarios`
--

CREATE TABLE `relatorios_diarios` (
  `id` int(11) NOT NULL,
  `estabelecimento_id` int(11) NOT NULL,
  `data_relatorio` date NOT NULL,
  `total_pedidos` int(11) DEFAULT 0,
  `total_mesa` int(11) DEFAULT 0,
  `total_delivery` int(11) DEFAULT 0,
  `faturamento_total` decimal(10,2) DEFAULT 0.00,
  `faturamento_mesa` decimal(10,2) DEFAULT 0.00,
  `faturamento_delivery` decimal(10,2) DEFAULT 0.00,
  `ticket_medio` decimal(10,2) DEFAULT 0.00,
  `taxa_entrega_total` decimal(10,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `relatorios_mensais`
--

CREATE TABLE `relatorios_mensais` (
  `id` int(11) NOT NULL,
  `estabelecimento_id` int(11) NOT NULL,
  `ano` int(11) NOT NULL,
  `mes` int(11) NOT NULL,
  `total_pedidos` int(11) DEFAULT 0,
  `total_mesa` int(11) DEFAULT 0,
  `total_delivery` int(11) DEFAULT 0,
  `faturamento_total` decimal(10,2) DEFAULT 0.00,
  `faturamento_mesa` decimal(10,2) DEFAULT 0.00,
  `faturamento_delivery` decimal(10,2) DEFAULT 0.00,
  `ticket_medio` decimal(10,2) DEFAULT 0.00,
  `dias_funcionamento` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `relatorios_produtos_vendidos`
--

CREATE TABLE `relatorios_produtos_vendidos` (
  `id` int(11) NOT NULL,
  `estabelecimento_id` int(11) NOT NULL,
  `produto_id` int(11) NOT NULL,
  `data_relatorio` date NOT NULL,
  `quantidade_vendida` int(11) DEFAULT 0,
  `valor_total` decimal(10,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `super_admins`
--

CREATE TABLE `super_admins` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `super_admins`
--

INSERT INTO `super_admins` (`id`, `nome`, `email`, `senha`, `created_at`) VALUES
(1, 'Super Admin Principal', 'superadmin@cardapio.com', '$2y$10$QwEg7.TqTtwJpBvu08b5we7LnNrYaJ6aMGK8PYKgu0OBoVK8KRWQW', '2025-09-04 17:20:04');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios_estabelecimento`
--

CREATE TABLE `usuarios_estabelecimento` (
  `id` int(11) NOT NULL,
  `estabelecimento_id` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `tipo` enum('admin','funcionario') NOT NULL,
  `ativo` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `nivel` enum('admin','cozinha') NOT NULL DEFAULT 'cozinha'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios_estabelecimento`
--

INSERT INTO `usuarios_estabelecimento` (`id`, `estabelecimento_id`, `nome`, `email`, `senha`, `tipo`, `ativo`, `created_at`, `updated_at`, `nivel`) VALUES
(1, 1, 'Admin Pizzaria', 'admin@pizzariatop.com', '123456', 'admin', 1, '2025-09-03 10:57:12', '2025-09-05 15:14:51', 'admin'),
(3, 8, 'lua', 'admin@coca.com', '$2y$10$sPg94VEjt6OvTqxCdX6RjOSOON13uu5H8DenOWk8fLLutca7Gy8aG', 'admin', 1, '2025-09-04 19:51:32', '2025-09-05 15:14:51', 'admin'),
(6, 11, 'dA', 'admin@ariatoppizz.com', '$2y$10$9Jthey48tjyn4/epa.0UEO/sboIojgth28bI08vykW07uxNo9D0Kq', 'admin', 1, '2025-09-05 00:46:50', '2025-09-05 15:14:51', 'admin');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `estabelecimento_id` (`estabelecimento_id`);

--
-- Índices de tabela `configuracao_delivery`
--
ALTER TABLE `configuracao_delivery`
  ADD PRIMARY KEY (`id`),
  ADD KEY `estabelecimento_id` (`estabelecimento_id`);

--
-- Índices de tabela `configuracoes_layout`
--
ALTER TABLE `configuracoes_layout`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_estabelecimento_layout` (`estabelecimento_id`);

--
-- Índices de tabela `estabelecimentos`
--
ALTER TABLE `estabelecimentos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subdominio` (`subdominio`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices de tabela `mesas`
--
ALTER TABLE `mesas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `estabelecimento_id` (`estabelecimento_id`);

--
-- Índices de tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `estabelecimento_id` (`estabelecimento_id`),
  ADD KEY `mesa_id` (`mesa_id`),
  ADD KEY `idx_pedidos_estabelecimento_data` (`estabelecimento_id`,`created_at`),
  ADD KEY `idx_pedidos_status` (`status`),
  ADD KEY `idx_pedidos_tipo` (`tipo`);

--
-- Índices de tabela `pedido_itens`
--
ALTER TABLE `pedido_itens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedido_id` (`pedido_id`),
  ADD KEY `produto_id` (`produto_id`),
  ADD KEY `idx_pedido_itens_produto` (`produto_id`);

--
-- Índices de tabela `planos`
--
ALTER TABLE `planos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `estabelecimento_id` (`estabelecimento_id`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- Índices de tabela `relatorios_diarios`
--
ALTER TABLE `relatorios_diarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_estabelecimento_data` (`estabelecimento_id`,`data_relatorio`);

--
-- Índices de tabela `relatorios_mensais`
--
ALTER TABLE `relatorios_mensais`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_estabelecimento_ano_mes` (`estabelecimento_id`,`ano`,`mes`);

--
-- Índices de tabela `relatorios_produtos_vendidos`
--
ALTER TABLE `relatorios_produtos_vendidos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_produto_data` (`produto_id`,`data_relatorio`),
  ADD KEY `estabelecimento_id` (`estabelecimento_id`);

--
-- Índices de tabela `super_admins`
--
ALTER TABLE `super_admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices de tabela `usuarios_estabelecimento`
--
ALTER TABLE `usuarios_estabelecimento`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `estabelecimento_id` (`estabelecimento_id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `configuracao_delivery`
--
ALTER TABLE `configuracao_delivery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `configuracoes_layout`
--
ALTER TABLE `configuracoes_layout`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `estabelecimentos`
--
ALTER TABLE `estabelecimentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `mesas`
--
ALTER TABLE `mesas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de tabela `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de tabela `pedido_itens`
--
ALTER TABLE `pedido_itens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de tabela `planos`
--
ALTER TABLE `planos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de tabela `relatorios_diarios`
--
ALTER TABLE `relatorios_diarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `relatorios_mensais`
--
ALTER TABLE `relatorios_mensais`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `relatorios_produtos_vendidos`
--
ALTER TABLE `relatorios_produtos_vendidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `super_admins`
--
ALTER TABLE `super_admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `usuarios_estabelecimento`
--
ALTER TABLE `usuarios_estabelecimento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `configuracoes_layout`
--
ALTER TABLE `configuracoes_layout`
  ADD CONSTRAINT `configuracoes_layout_ibfk_1` FOREIGN KEY (`estabelecimento_id`) REFERENCES `estabelecimentos` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `relatorios_diarios`
--
ALTER TABLE `relatorios_diarios`
  ADD CONSTRAINT `relatorios_diarios_ibfk_1` FOREIGN KEY (`estabelecimento_id`) REFERENCES `estabelecimentos` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `relatorios_mensais`
--
ALTER TABLE `relatorios_mensais`
  ADD CONSTRAINT `relatorios_mensais_ibfk_1` FOREIGN KEY (`estabelecimento_id`) REFERENCES `estabelecimentos` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `relatorios_produtos_vendidos`
--
ALTER TABLE `relatorios_produtos_vendidos`
  ADD CONSTRAINT `relatorios_produtos_vendidos_ibfk_1` FOREIGN KEY (`estabelecimento_id`) REFERENCES `estabelecimentos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `relatorios_produtos_vendidos_ibfk_2` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
