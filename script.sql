-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3308
-- Généré le : dim. 04 jan. 2026 à 22:31
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `ecommerce_mvc`
--

-- --------------------------------------------------------

--
-- Structure de la table `admins`
--

DROP TABLE IF EXISTS `admins`;
CREATE TABLE IF NOT EXISTS `admins` (
                                        `id_admin` bigint NOT NULL AUTO_INCREMENT,
                                        `username` varchar(40) NOT NULL,
    `email` varchar(50) NOT NULL,
    `mdp` varchar(256) NOT NULL,
    `role` enum('admin','super_admin') DEFAULT 'admin',
    PRIMARY KEY (`id_admin`),
    UNIQUE KEY `username` (`username`),
    UNIQUE KEY `email` (`email`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

DROP TABLE IF EXISTS `categorie`;
CREATE TABLE IF NOT EXISTS `categorie` (
                                           `id_categorie` bigint NOT NULL AUTO_INCREMENT,
                                           `nom_categorie` varchar(30) NOT NULL,
    `description_categorie` text NOT NULL,
    `image_categorie` varchar(200) DEFAULT NULL,
    PRIMARY KEY (`id_categorie`),
    UNIQUE KEY `nom_categorie` (`nom_categorie`)
    ) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `categorie`
--

INSERT INTO `categorie` (`id_categorie`, `nom_categorie`, `description_categorie`, `image_categorie`) VALUES
                                                                                                          (1, 'Equipement', 'Concerne la vente d\'équipements exclusivement', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

DROP TABLE IF EXISTS `client`;
CREATE TABLE IF NOT EXISTS `client` (
  `id_client` bigint NOT NULL AUTO_INCREMENT,
  `adresse` varchar(200) NOT NULL,
  `ville` varchar(100) NOT NULL,
  `code_postal` varchar(10) NOT NULL,
  `email_client` varchar(50) NOT NULL,
  `mdp_client` varchar(256) NOT NULL,
  PRIMARY KEY (`id_client`),
  UNIQUE KEY `email_client` (`email_client`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `client`
--

INSERT INTO `client` (`id_client`, `adresse`, `ville`, `code_postal`, `email_client`, `mdp_client`) VALUES
(1, '123 Avenue du Test', 'Ville du Test', '77777', 'test.test@test.com', '$2y$10$k0db8qmfaLMMqV4.glS5Lez2DPJw3LbrHDjVHG1CDv9IoChPf2XAO');

-- --------------------------------------------------------

--
-- Structure de la table `commandes`
--

DROP TABLE IF EXISTS `commandes`;
CREATE TABLE IF NOT EXISTS `commandes` (
  `id_commande` bigint NOT NULL AUTO_INCREMENT,
  `id_client` bigint NOT NULL,
  `statut` enum('en attente','payée','expediée','livrée','annulée') DEFAULT 'en attente',
  `montant_total` decimal(10,2) NOT NULL,
  `adresse_livraison` varchar(200) NOT NULL,
  `ville_livraison` varchar(100) NOT NULL,
  `code_postal_livraison` varchar(10) NOT NULL,
  PRIMARY KEY (`id_commande`),
  KEY `id_client` (`id_client`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ligne_commande`
--

DROP TABLE IF EXISTS `ligne_commande`;
CREATE TABLE IF NOT EXISTS `ligne_commande` (
  `id_commande` bigint NOT NULL,
  `id_produit` bigint NOT NULL,
  `quantite` int NOT NULL,
  `prix_unitaire` decimal(10,2) NOT NULL,
  `sous_total` decimal(10,2) NOT NULL,
  KEY `id_commande` (`id_commande`),
  KEY `id_produit` (`id_produit`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `panier`
--

DROP TABLE IF EXISTS `panier`;
CREATE TABLE IF NOT EXISTS `panier` (
  `id_panier` bigint NOT NULL AUTO_INCREMENT,
  `id_client` bigint DEFAULT NULL,
  `id_produit` bigint NOT NULL,
  `quantite` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_panier`),
  KEY `id_client` (`id_client`),
  KEY `id_produit` (`id_produit`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

DROP TABLE IF EXISTS `produit`;
CREATE TABLE IF NOT EXISTS `produit` (
  `id_produit` bigint NOT NULL AUTO_INCREMENT,
  `id_categorie` bigint NOT NULL,
  `nom_produit` varchar(30) NOT NULL,
  `description_produit` text NOT NULL,
  `prix` decimal(10,2) NOT NULL,
  `stock` int NOT NULL,
  `image_produit` varchar(250) DEFAULT NULL,
  `statut_produit` enum('actif','inactif') DEFAULT 'actif',
  PRIMARY KEY (`id_produit`),
  UNIQUE KEY `nom_produit` (`nom_produit`),
  KEY `id_categorie` (`id_categorie`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`id_produit`, `id_categorie`, `nom_produit`, `description_produit`, `prix`, `stock`, `image_produit`, `statut_produit`) VALUES
(8, 1, '[Relique d\'âme] Claymore requi', 'Une claymore qui a été offerte en cadeau aux maîtres de la Voie Des Maîtres. Elle n\'est pas de première jeunesse, mais reste un artéfact respecté et craint par ceux qui l\'ont vu en action...', 30000.00, 3, 'D:\\ecole\\phpstorm\\serveur_web_projet\\backend\\public\\images\\15-6_Raid_Weapon_-_Claymore.png', 'actif'),
                                                                                                          (7, 1, '[Flammes du Jugement] Claymore', 'Une claymore qui renferme les flammes du purgatoire néées du berceau des flammes, Le Caveau de La Flamme sanguinaire. Bien qu\'abîmé par le combat pour l\'acquérir, son pouvoir destructeur est toujours immense.', 17000.00, 5, 'D:\\ecole\\phpstorm\\serveur_web_projet\\backend\\public\\images\\12-7Claymore.png', 'actif'),
                                                                                                          (6, 1, 'Claymore Ethéreene', 'Une claymore que l\'on peut obtenir en battant Add dans son laboratoire, son pouvoir est faible, mais il se développe avec l\'utilisateur', 10500.00, 2, 'D:\\ecole\\phpstorm\\serveur_web_projet\\backend\\public\\images\\4-YClaymore.png', 'actif'),
                                                                                                          (9, 1, '[Submergence de l\'Abysse] Clay', 'Une claymore qui sort tout droit des profondeurs de l\'Abysse, il en existe 5 dans le monde et font de leur détenteur des Dieux de l\'Epée. Cette Claymore est très dangereuse et peut se réveler un artéfact qui peut apporter la ruine à une nation entière.', 200000.00, 2, 'D:\\ecole\\phpstorm\\serveur_web_projet\\backend\\public\\images\\19-4Claymore.png', 'actif'),
(10, 1, '[Testament du Nécrodragon] Cla', 'J\'ai moi même rapporté cette claymore de mon combat avec le Dévoreur de Dimensions. Il aurait fait un bon animal de compagnie... Cette arme peut apporter la ruine au monde des Dieux et mettre les galaxies à feu et à sang, donc je ne le vends pas.', 999999.00, 1, 'D:\\ecole\\phpstorm\\serveur_web_projet\\backend\\public\\images\\HQ_Shop_Elsword_Legend_Weapon07.png', 'inactif');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
