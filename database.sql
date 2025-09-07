-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           8.0.30 - MySQL Community Server - GPL
-- OS do Servidor:               Win64
-- HeidiSQL Versão:              12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Copiando estrutura do banco de dados para cardapio_saas_db
CREATE DATABASE IF NOT EXISTS `cardapio_saas_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `cardapio_saas_db`;

-- Copiando estrutura para tabela cardapio_saas_db.categorias
CREATE TABLE IF NOT EXISTS `categorias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `estabelecimento_id` int NOT NULL,
  `nome` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `descricao` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `ordem` int DEFAULT '0',
  `ativo` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `estabelecimento_id` (`estabelecimento_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela cardapio_saas_db.categorias: ~4 rows (aproximadamente)
INSERT INTO `categorias` (`id`, `estabelecimento_id`, `nome`, `descricao`, `ordem`, `ativo`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Pizzas Salgadas', 'As melhores pizzas da região!', 1, 1, '2025-09-03 06:14:14', '2025-09-03 06:14:14'),
	(2, 1, 'Bebidas', 'Refrigerantes, sucos e águas.', 2, 1, '2025-09-03 06:14:14', '2025-09-03 06:14:14'),
	(3, 2, 'Hambúrgueres', 'Nossos burgers artesanais.', 1, 1, '2025-09-03 06:14:14', '2025-09-03 06:14:14'),
	(4, 2, 'Porções', 'Para compartilhar com a galera.', 2, 1, '2025-09-03 06:14:14', '2025-09-03 06:14:14');

-- Copiando estrutura para tabela cardapio_saas_db.estabelecimentos
CREATE TABLE IF NOT EXISTS `estabelecimentos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `subdominio` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `telefone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `endereco` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cor_primaria` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '#dc2626',
  `cor_secundaria` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '#f9fafb',
  `plano_id` int DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `subdominio` (`subdominio`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela cardapio_saas_db.estabelecimentos: ~2 rows (aproximadamente)
INSERT INTO `estabelecimentos` (`id`, `nome`, `subdominio`, `email`, `telefone`, `endereco`, `logo`, `cor_primaria`, `cor_secundaria`, `plano_id`, `ativo`, `created_at`, `updated_at`) VALUES
	(1, 'Pizzaria Top', 'pizzaria-top', 'contato@pizzariatop.com', '11988887777', 'Rua da Pizza, 123', NULL, '#dc2626', '#f9fafb', 2, 1, '2025-09-03 06:14:14', '2025-09-03 06:14:14'),
	(2, 'Lanchonete Legal', 'lanchonete-legal', 'contato@lanchonetelegal.com', '11955554444', 'Av. do Burger, 456', NULL, '#2563eb', '#f9fafb', 1, 1, '2025-09-03 06:14:14', '2025-09-03 06:14:14');

-- Copiando estrutura para tabela cardapio_saas_db.mesas
CREATE TABLE IF NOT EXISTS `mesas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `estabelecimento_id` int NOT NULL,
  `numero` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `qr_code` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `status` enum('livre','ocupada') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'livre',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `estabelecimento_id` (`estabelecimento_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela cardapio_saas_db.mesas: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela cardapio_saas_db.pedidos
CREATE TABLE IF NOT EXISTS `pedidos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `estabelecimento_id` int NOT NULL,
  `mesa_id` int DEFAULT NULL,
  `tipo` enum('mesa','delivery') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` enum('recebido','preparando','pronto','entregue','cancelado') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `observacoes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `endereco_entrega` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `telefone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `estabelecimento_id` (`estabelecimento_id`),
  KEY `mesa_id` (`mesa_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela cardapio_saas_db.pedidos: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela cardapio_saas_db.pedido_itens
CREATE TABLE IF NOT EXISTS `pedido_itens` (
  `id` int NOT NULL AUTO_INCREMENT,
  `pedido_id` int NOT NULL,
  `produto_id` int NOT NULL,
  `quantidade` int NOT NULL,
  `preco_unitario` decimal(10,2) NOT NULL,
  `observacoes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `pedido_id` (`pedido_id`),
  KEY `produto_id` (`produto_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela cardapio_saas_db.pedido_itens: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela cardapio_saas_db.planos
CREATE TABLE IF NOT EXISTS `planos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `preco_mensal` decimal(10,2) NOT NULL,
  `limite_produtos` int DEFAULT NULL,
  `limite_mesas` int DEFAULT NULL,
  `recursos` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `ativo` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela cardapio_saas_db.planos: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela cardapio_saas_db.produtos
CREATE TABLE IF NOT EXISTS `produtos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `estabelecimento_id` int NOT NULL,
  `categoria_id` int NOT NULL,
  `nome` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `descricao` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `preco` decimal(10,2) NOT NULL,
  `imagem` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `disponivel` tinyint(1) DEFAULT '1',
  `destaque` tinyint(1) DEFAULT '0',
  `ordem` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `estabelecimento_id` (`estabelecimento_id`),
  KEY `categoria_id` (`categoria_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela cardapio_saas_db.produtos: ~6 rows (aproximadamente)
INSERT INTO `produtos` (`id`, `estabelecimento_id`, `categoria_id`, `nome`, `descricao`, `preco`, `imagem`, `disponivel`, `destaque`, `ordem`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, 'Pizza de Calabresa', 'Molho de tomate, queijo mussarela e calabresa fatiada.', 45.50, NULL, 1, 1, 1, '2025-09-03 06:14:14', '2025-09-03 06:14:14'),
	(2, 1, 1, 'Pizza 4 Queijos', 'Mussarela, provolone, parmesão e gorgonzola.', 52.00, NULL, 1, 0, 2, '2025-09-03 06:14:14', '2025-09-03 06:14:14'),
	(3, 1, 2, 'Coca-Cola Lata', '350ml', 6.00, NULL, 1, 0, 1, '2025-09-03 06:14:14', '2025-09-03 06:14:14'),
	(4, 2, 3, 'X-Burger Clássico', 'Pão, burger, queijo e salada.', 25.00, NULL, 1, 1, 1, '2025-09-03 06:14:14', '2025-09-03 06:14:14'),
	(5, 2, 3, 'X-Bacon Especial', 'Pão, burger, queijo, bacon crocante e molho especial.', 32.50, NULL, 1, 0, 2, '2025-09-03 06:14:14', '2025-09-03 06:14:14'),
	(6, 2, 4, 'Batata Frita com Cheddar e Bacon', 'Porção de 400g.', 28.00, NULL, 1, 0, 1, '2025-09-03 06:14:14', '2025-09-03 06:14:14');

-- Copiando estrutura para tabela cardapio_saas_db.usuarios_estabelecimento
CREATE TABLE IF NOT EXISTS `usuarios_estabelecimento` (
  `id` int NOT NULL AUTO_INCREMENT,
  `estabelecimento_id` int NOT NULL,
  `nome` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `senha` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tipo` enum('admin','funcionario') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `ativo` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `estabelecimento_id` (`estabelecimento_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela cardapio_saas_db.usuarios_estabelecimento: ~0 rows (aproximadamente)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;