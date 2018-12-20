-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  jeu. 20 déc. 2018 à 19:32
-- Version du serveur :  5.7.19
-- Version de PHP :  7.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `cupoftea`
--

-- --------------------------------------------------------

--
-- Structure de la table `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `cat_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(100) DEFAULT NULL,
  `cat_description` text,
  `cat_picture` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`cat_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `category`
--

INSERT INTO `category` (`cat_id`, `cat_name`, `cat_description`, `cat_picture`) VALUES
(1, 'Thé noir', '<p>Le th&eacute; noir, que les chinois appellent th&eacute; rouge en r&eacute;f&eacute;rence &agrave; la couleur cuivr&eacute;e de son infusion, est un th&eacute; compl&egrave;tement oxyd&eacute;. La fabrication du th&eacute; noir se fait en cinq &eacute;tapes : le fl&eacute;trissage, le roulage, l\'oxydation, la torr&eacute;faction et le triage. Cette derni&egrave;re op&eacute;ration permet de diff&eacute;rencier les diff&eacute;rents grades.</p>', '1.jpg'),
(2, 'Thé vert', 'Réputé pour ses nombreuses vertus grâce à sa richesse en antioxydants, le thé vert désaltère, tonifie, apaise, fortifie, et procure une incontestable sensation de bien-être. Délicat et peu amer, il est apprécié à tout moment de la journée et propose une palette d\'arômes très variés : végétal, minéral, floral, fruité.', '2.jpg'),
(3, 'Oolong', 'Les Oolong, que les chinois appellent thés bleu-vert en référence à la couleur de leurs feuilles infusées, sont des thés semi-oxydés : leur oxydation n\'a pas été menée à son terme. Spécialités de Chine et de Taïwan, il en existe une grande variété, en fonction de la région de culture, de l\'espèce du théier ou encore du processus de fabrication.', '3.jpg'),
(4, 'Thé blanc', 'Le thé blanc est une spécialité de la province chinoise du Fujian. De toutes les familles de thé, c\'est celle dont la feuille est la moins transformée par rapport à son état naturel. Non oxydé, le thé blanc ne subit que deux opérations : un flétrissage et une dessiccation. Il existe deux grands types de thés blancs : les Aiguilles d\'Argent et les Bai Mu Dan.', '4.jpg'),
(5, 'Rooibos', 'Le Rooibos (appelé thé rouge bien qu\'il ne s\'agisse pas de thé) est une plante poussant uniquement en Afrique du Sud et qui ne contient pas du tout de théine. Son infusion donne une boisson très agréable, ronde et légèrement sucrée. Riche en antioxydants, faible en tanins et dénué de théine, le Rooibos peut être dégusté en journée comme en soirée.', '5.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `customer`
--

DROP TABLE IF EXISTS `customer`;
CREATE TABLE IF NOT EXISTS `customer` (
  `cust_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cust_firstname` varchar(100) DEFAULT NULL,
  `cust_lastname` varchar(100) DEFAULT NULL,
  `cust_email` varchar(255) DEFAULT NULL,
  `cust_password` varchar(255) DEFAULT NULL,
  `cust_address` varchar(255) DEFAULT NULL,
  `cust_cp` varchar(10) DEFAULT NULL,
  `cust_city` varchar(255) DEFAULT NULL,
  `cust_country` varchar(255) DEFAULT NULL,
  `cust_phone` varchar(45) DEFAULT NULL,
  `cust_createdDate` datetime DEFAULT NULL,
  `cust_birthday` date DEFAULT NULL,
  PRIMARY KEY (`cust_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `customer`
--

INSERT INTO `customer` (`cust_id`, `cust_firstname`, `cust_lastname`, `cust_email`, `cust_password`, `cust_address`, `cust_cp`, `cust_city`, `cust_country`, `cust_phone`, `cust_createdDate`, `cust_birthday`) VALUES
(1, 'Fabien', 'Sellès', 'fabien.selles@alti-com.fr', '$2y$10$WyOWMI./qj6AwuYXulVNa.1BtmFFqQA8bGfr6dgRBuSGNToqs78ri', '59 Rue Carnot', '05000', 'GAP', 'France', '0608371743', '2018-12-16 01:03:52', '1999-12-13');

-- --------------------------------------------------------

--
-- Structure de la table `order`
--

DROP TABLE IF EXISTS `order`;
CREATE TABLE IF NOT EXISTS `order` (
  `ord_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ord_date` datetime DEFAULT NULL,
  `ord_status` int(11) NOT NULL DEFAULT '0',
  `ord_dateShipped` date DEFAULT NULL,
  `ord_dateDelivery` date DEFAULT NULL,
  `ord_comment` text,
  `customer_cust_id` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`ord_id`),
  KEY `fk_order_customer_idx` (`customer_cust_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `order`
--

INSERT INTO `order` (`ord_id`, `ord_date`, `ord_status`, `ord_dateShipped`, `ord_dateDelivery`, `ord_comment`, `customer_cust_id`) VALUES
(13, '2018-12-20 06:39:47', 1, NULL, NULL, '', 1),
(14, '2018-12-20 07:01:52', 3, NULL, NULL, '', 1),
(15, '2018-12-20 07:02:24', 3, NULL, NULL, '', 1),
(16, '2018-12-20 07:12:07', 1, NULL, NULL, '', 1),
(17, '2018-12-20 07:12:51', 3, NULL, NULL, '', 1);

-- --------------------------------------------------------

--
-- Structure de la table `orderdetail`
--

DROP TABLE IF EXISTS `orderdetail`;
CREATE TABLE IF NOT EXISTS `orderdetail` (
  `ordd_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ordd_quantity` int(11) DEFAULT NULL,
  `ordd_price` double DEFAULT NULL,
  `order_ord_id` int(10) UNSIGNED NOT NULL,
  `product_prod_id` int(10) UNSIGNED NOT NULL,
  `productvariation_prodv_id` int(10) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`ordd_id`),
  KEY `fk_orderDetail_order1_idx` (`order_ord_id`),
  KEY `fk_orderDetail_product1_idx` (`product_prod_id`),
  KEY `fk_orderdetail_productvariation1_idx` (`productvariation_prodv_id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `orderdetail`
--

INSERT INTO `orderdetail` (`ordd_id`, `ordd_quantity`, `ordd_price`, `order_ord_id`, `product_prod_id`, `productvariation_prodv_id`) VALUES
(15, 10, 12, 13, 3, 5),
(16, 1, 18, 14, 1, 1),
(19, 6, 12, 15, 3, 5),
(20, 6, 12, 16, 3, 5),
(31, 9, 18, 17, 1, 1),
(32, 10, 360, 17, 5, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `product`
--

DROP TABLE IF EXISTS `product`;
CREATE TABLE IF NOT EXISTS `product` (
  `prod_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `prod_name` varchar(255) DEFAULT NULL,
  `prod_subtitle` varchar(255) DEFAULT NULL,
  `prod_description` text,
  `prod_createdDate` date DEFAULT NULL,
  `prod_price` double UNSIGNED DEFAULT NULL,
  `prod_tva` float UNSIGNED NOT NULL DEFAULT '20',
  `prod_picture` varchar(255) DEFAULT NULL,
  `category_cat_id` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`prod_id`),
  KEY `fk_product_category1_idx` (`category_cat_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `product`
--

INSERT INTO `product` (`prod_id`, `prod_name`, `prod_subtitle`, `prod_description`, `prod_createdDate`, `prod_price`, `prod_tva`, `prod_picture`, `category_cat_id`) VALUES
(1, 'Blue of London', 'Thé noir à la bergamote', '<section id=\"product-description\">\r\n<p>Blue of London est un Earl Grey d\'exception qui associe un des meilleurs th&eacute;s noirs au monde, le Yunnan, et une bergamote fra&icirc;che et d&eacute;licate. Un m&eacute;lange remarquable d\'&eacute;quilibre et de finesse.</p>\r\n<p>Le Earl Grey est un grand classique anglais, depuis que Charles Grey, comte (earl en anglais) de Falodon et Ministre des Affaires &eacute;trang&egrave;res du Royaume britannique au milieu du XIX &egrave;me si&egrave;cle, re&ccedil;ut d\'un mandarin chinois une vieille recette consistant &agrave; aromatiser son th&eacute; avec de la bergamote.</p>\r\n<p><strong>Profitez d\'une remise de 5% sur la pochette de 500g (prix d&eacute;j&agrave; remis&eacute;).</strong></p>\r\n<p><strong>Profitez d\'une remise de 10% sur le lot de 2 pochettes de 500g (prix d&eacute;j&agrave; remis&eacute;).</strong></p>\r\n</section>', '2018-12-16', 15, 20, 'product3_big.jpg', 1),
(3, 'Thé bleu', 'Super thé tout bleu', '<p>Youpi !</p>', '2018-12-16', 10, 20, 'product9_big.jpg', 3),
(4, 'Thé rouge du Pérou', 'Le thé qui est rouge', '<p>Le th&eacute; qui est rouge</p>\r\n<p>Le th&eacute; qui est rouge</p>\r\n<p>Le th&eacute; qui est rouge</p>\r\n<p>Le th&eacute; qui est rouge</p>\r\n<p>Le th&eacute; qui est rouge</p>', '2018-12-17', 150, 20, 'product1.jpg', 5),
(5, 'Thé rouge de Marseille', 'Mélangé à la main par des cagolles', '<p>Toutes les saveurs du SUD !!!</p>', '2018-12-17', 300, 20, 'product13_big.jpg', 5),
(6, 'Thé rouge de Gap', 'Préparer par des vrais paysans !', '<p>Pr&eacute;parer par des vrais paysans !</p>\r\n<p>Pr&eacute;parer par des vrais paysans !</p>\r\n<p>Pr&eacute;parer par des vrais paysans !</p>\r\n<p>&nbsp;</p>', '2018-12-17', 600, 20, NULL, 5);

-- --------------------------------------------------------

--
-- Structure de la table `productvariation`
--

DROP TABLE IF EXISTS `productvariation`;
CREATE TABLE IF NOT EXISTS `productvariation` (
  `prodv_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `prodv_name` varchar(255) DEFAULT NULL,
  `prodv_price` double DEFAULT NULL,
  `prodv_quantity` int(11) DEFAULT NULL,
  `product_prod_id` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`prodv_id`),
  KEY `fk_productVariation_product1_idx` (`product_prod_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `productvariation`
--

INSERT INTO `productvariation` (`prodv_id`, `prodv_name`, `prodv_price`, `prodv_quantity`, `product_prod_id`) VALUES
(1, 'Sachet de 100g', 0, 200, 1),
(2, 'Sachet de 200g', 15, 150, 1),
(4, 'Sachet de 300g', 20, 100, 1),
(5, 'Sachet de 100g', 0, 300, 3),
(6, 'Sachet de 50g', -5, 100, 1),
(7, 'Sachet de 100g', 0, 20, 4),
(8, 'Sachet de 200g', 100, 20, 4),
(9, 'Sachet de 50g', -50, 10, 4);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `fk_order_customer` FOREIGN KEY (`customer_cust_id`) REFERENCES `customer` (`cust_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `orderdetail`
--
ALTER TABLE `orderdetail`
  ADD CONSTRAINT `fk_orderDetail_order1` FOREIGN KEY (`order_ord_id`) REFERENCES `order` (`ord_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_orderDetail_product1` FOREIGN KEY (`product_prod_id`) REFERENCES `product` (`prod_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_orderdetail_productvariation1` FOREIGN KEY (`productvariation_prodv_id`) REFERENCES `productvariation` (`prodv_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `fk_product_category1` FOREIGN KEY (`category_cat_id`) REFERENCES `category` (`cat_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `productvariation`
--
ALTER TABLE `productvariation`
  ADD CONSTRAINT `fk_productVariation_product1` FOREIGN KEY (`product_prod_id`) REFERENCES `product` (`prod_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
