-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  mer. 18 avr. 2018 à 20:45
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
-- Base de données :  `sallea`
--

-- --------------------------------------------------------

--
-- Structure de la table `avis`
--

DROP TABLE IF EXISTS `avis`;
CREATE TABLE IF NOT EXISTS `avis` (
  `id_avis` int(3) NOT NULL AUTO_INCREMENT,
  `id_membre` int(3) DEFAULT NULL,
  `id_salle` int(3) DEFAULT NULL,
  `commentaire` text NOT NULL,
  `note` int(2) NOT NULL,
  `date_enregistrement` datetime NOT NULL,
  PRIMARY KEY (`id_avis`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `avis`
--

INSERT INTO `avis` (`id_avis`, `id_membre`, `id_salle`, `commentaire`, `note`, `date_enregistrement`) VALUES
(1, 1, 1, ' efqsfq', 1, '2018-04-04 12:15:20'),
(3, 1, 4, ' test3', 1, '2018-04-04 12:21:31'),
(4, 1, 6, ' test 5', 5, '2018-04-04 12:22:36'),
(9, 5, 1, ' ouvre toi !', 4, '2018-04-04 13:25:15'),
(10, 1, 3, ' test formulaire', 4, '2018-04-05 10:15:20'),
(11, 1, 1, ' salle de dingue', 5, '2018-04-05 15:49:20'),
(12, 1, 7, ' ', 4, '2018-04-18 20:49:38'),
(13, 1, 5, ' ', 3, '2018-04-18 20:51:00');

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

DROP TABLE IF EXISTS `commande`;
CREATE TABLE IF NOT EXISTS `commande` (
  `id_commande` int(3) NOT NULL AUTO_INCREMENT,
  `id_membre` int(3) DEFAULT NULL,
  `id_produit` int(3) DEFAULT NULL,
  `date_enregistrement` datetime NOT NULL,
  PRIMARY KEY (`id_commande`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`id_commande`, `id_membre`, `id_produit`, `date_enregistrement`) VALUES
(1, 1, 2, '2018-04-05 11:54:43'),
(4, 1, 1, '2018-04-05 11:57:42'),
(5, 1, 1, '2018-04-05 12:00:53'),
(6, 1, 1, '2018-04-05 12:05:02'),
(7, 1, 1, '2018-04-05 12:05:21'),
(8, 1, 1, '2018-04-05 12:07:54'),
(9, 1, 1, '2018-04-05 12:12:38'),
(10, 1, 1, '2018-04-05 12:36:35'),
(11, 1, 2, '2018-04-05 13:15:24'),
(12, 1, 11, '2018-04-18 22:02:36'),
(13, 5, 9, '2018-04-18 22:05:28'),
(14, 7, 12, '2018-04-18 22:24:25'),
(15, 7, 13, '2018-04-18 22:25:16');

-- --------------------------------------------------------

--
-- Structure de la table `membre`
--

DROP TABLE IF EXISTS `membre`;
CREATE TABLE IF NOT EXISTS `membre` (
  `id_membre` int(3) NOT NULL AUTO_INCREMENT,
  `pseudo` text NOT NULL,
  `mdp` varchar(60) NOT NULL,
  `nom` varchar(20) NOT NULL,
  `prenom` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `civilite` enum('m','f') NOT NULL,
  `statut` int(1) NOT NULL,
  `date_enregistrement` datetime NOT NULL,
  PRIMARY KEY (`id_membre`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `membre`
--

INSERT INTO `membre` (`id_membre`, `pseudo`, `mdp`, `nom`, `prenom`, `email`, `civilite`, `statut`, `date_enregistrement`) VALUES
(1, 'jeannot95', 'd73138bef5a01d820e3511ab83562863', 'audoin', 'jean', 'audoinjean95@hotmail.com', 'm', 1, '2018-04-02 21:53:20'),
(2, 'boby', 'a0c8b07722e57e3ebeb3bc9779040fc4', 'damien', 'favre', 'damien@hotmail.com', 'm', 0, '2018-03-29 17:42:54'),
(5, 'tintin', '1f3d34f31b40ae8495d1bc6da0488421', 'tintin', 'milou', 'tintin@hotmail.com', 'm', 0, '2018-04-02 21:07:35'),
(6, 'jacques', '367b8538d1020720065a0305c2491cff', 'jacques', 'favre', 'damien@hotmail.com', 'm', 0, '2018-04-02 21:52:07'),
(7, 'jambon', '2a7447762dd886b4fd61ae9ee6b9552a', 'jambon', 'jambon', 'jambon@hotmail.com', 'm', 0, '2018-04-18 22:23:53');

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

DROP TABLE IF EXISTS `produit`;
CREATE TABLE IF NOT EXISTS `produit` (
  `id_produit` int(3) NOT NULL AUTO_INCREMENT,
  `id_salle` int(3) DEFAULT NULL,
  `date_arrivee` datetime NOT NULL,
  `date_depart` datetime NOT NULL,
  `prix` int(3) NOT NULL,
  `etat` enum('libre','reservation') NOT NULL,
  PRIMARY KEY (`id_produit`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`id_produit`, `id_salle`, `date_arrivee`, `date_depart`, `prix`, `etat`) VALUES
(1, 1, '2018-04-03 00:00:00', '2018-04-04 00:00:00', 100, 'reservation'),
(2, 3, '2018-04-10 00:00:00', '2018-04-11 00:00:00', 600, 'reservation'),
(3, 1, '2018-04-05 00:00:00', '2018-04-06 00:00:00', 300, 'libre'),
(4, 4, '2018-04-06 00:00:00', '2018-04-08 00:00:00', 400, 'libre'),
(5, 3, '2018-04-12 00:00:00', '2018-04-15 00:00:00', 900, 'libre'),
(6, 4, '2018-04-11 00:00:00', '2018-04-12 00:00:00', 200, 'libre'),
(7, 5, '2018-04-11 00:00:00', '2018-04-13 00:00:00', 500, 'libre'),
(8, 6, '2018-04-18 00:00:00', '2018-04-22 00:00:00', 200, 'libre'),
(9, 7, '2018-04-27 00:00:00', '2018-04-30 00:00:00', 300, 'reservation'),
(10, 1, '2018-04-14 00:00:00', '2018-04-21 00:00:00', 500, 'libre'),
(11, 5, '2018-04-22 00:00:00', '2018-04-29 00:00:00', 1000, 'reservation'),
(12, 4, '2018-04-26 00:00:00', '2018-04-28 00:00:00', 300, 'reservation'),
(13, 7, '2018-05-01 00:00:00', '2018-04-07 00:00:00', 500, 'reservation'),
(14, 3, '2018-04-27 00:00:00', '2018-04-30 00:00:00', 500, 'libre');

-- --------------------------------------------------------

--
-- Structure de la table `salle`
--

DROP TABLE IF EXISTS `salle`;
CREATE TABLE IF NOT EXISTS `salle` (
  `id_salle` int(3) NOT NULL AUTO_INCREMENT,
  `titre` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `photo` varchar(200) NOT NULL,
  `pays` varchar(20) NOT NULL,
  `ville` enum('Paris','Marseille','Lyon') NOT NULL,
  `adresse` varchar(50) NOT NULL,
  `cp` int(5) NOT NULL,
  `capacite` int(3) NOT NULL,
  `categorie` enum('Réunion','Bureau','Formation') NOT NULL,
  PRIMARY KEY (`id_salle`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `salle`
--

INSERT INTO `salle` (`id_salle`, `titre`, `description`, `photo`, `pays`, `ville`, `adresse`, `cp`, `capacite`, `categorie`) VALUES
(1, 'salle césam', 'Une très belle salle', 'photo/salle césam_salle1.jpg', 'France', 'Paris', '30 rue du Martroy', 75000, 10, 'Réunion'),
(3, 'salle abracadabra', 'salle de ouf', 'photo/salle abracadabra_salle9.jpg', 'France', 'Marseille', '30 rue de pantin', 80000, 20, 'Bureau'),
(4, 'salle Star wars', 'une très belle salle', 'photo/salle Star wars_salle4.jpg', 'France', 'Lyon', '3 rue de poitiers', 13000, 50, 'Formation'),
(5, 'Egypte', 'Une salle détente pour faire ses réunion..', 'photo/Egypte_salle5.jpg', 'France', 'Paris', '12 place du maréchal', 75018, 80, 'Réunion'),
(6, 'Arabian Nights', 'Une salle  ou tout le monde est assis(au sol)', 'photo/Arabian Nights_salle8.jpg', 'France', 'Marseille', '20 rue du boutier', 80222, 60, 'Formation'),
(7, 'Médiévale', 'Une salle médiévale', 'photo/Médiévale_salle7.jpg', 'France', 'Paris', '45 place de la Bastille', 75010, 20, 'Réunion');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
