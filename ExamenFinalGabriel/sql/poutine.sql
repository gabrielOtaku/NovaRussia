-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 09 mai 2022 à 15:11
-- Version du serveur : 8.0.27
-- Version de PHP : 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `poutine`
--

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

DROP TABLE IF EXISTS `client`;
CREATE TABLE IF NOT EXISTS `client` (
  `idClient` smallint NOT NULL AUTO_INCREMENT,
  `prenom` varchar(25) DEFAULT NULL,
  `nom` varchar(25) DEFAULT NULL,
  `courriel` varchar(50) NOT NULL,
  `telephone` varchar(10) DEFAULT NULL,
  `motPasse` varchar(25) NOT NULL,
  PRIMARY KEY (`idClient`),
  UNIQUE KEY `courriel` (`courriel`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `forfait`
--

DROP TABLE IF EXISTS `forfait`;
CREATE TABLE IF NOT EXISTS `forfait` (
  `idForfait` smallint NOT NULL AUTO_INCREMENT,
  `nom` varchar(30) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `prix` float DEFAULT NULL,
  PRIMARY KEY (`idForfait`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `forfait`
--

INSERT INTO `forfait` (`idForfait`, `nom`, `description`, `prix`) VALUES
(1, 'Accompagnement', 'Vous voulez envahir un pays ? Faites affaire avec nous.', 5.6),
(2, 'Visite de courtoisie', 'Pour briser l\'isolement, quelqu\'un vous rend visite au moment de votre choix.', 8),
(3, 'Livraison', 'Que ce soit pour aller chercher une livraison à l\'Otan\', chez l\'armurier ou ailleurs, nos guerriers sont là pour vous aider.', 10),
(4, 'Appel téléphonique', 'Le président vous appelle à la fréquence qui vous convient, pour parler de tous et de rien.', 16),
(5, 'Forfait missile', 'Pour le diner ou le souper, vous avez accès à un armement complet et équilibré et ce, livré à votre porte.', 35.75);

-- --------------------------------------------------------

--
-- Structure de la table `rendezvous`
--

DROP TABLE IF EXISTS `rendezvous`;
CREATE TABLE IF NOT EXISTS `rendezvous` (
  `idRV` smallint NOT NULL AUTO_INCREMENT,
  `noClient` smallint NOT NULL,
  `noForfait` smallint NOT NULL,
  `dateRV` date NOT NULL,
  `endroit` varchar(50) DEFAULT NULL,
  `remarque` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`idRV`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
