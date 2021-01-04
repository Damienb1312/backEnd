-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 04 jan. 2021 à 19:16
-- Version du serveur :  10.4.11-MariaDB
-- Version de PHP : 7.4.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `terroir`
--

-- --------------------------------------------------------

--
-- Structure de la table `espace_membres_terroir`
--

CREATE TABLE `espace_membres_terroir` (
  `id` int(11) NOT NULL,
  `pseudo` varchar(250) NOT NULL,
  `pass` varchar(250) NOT NULL,
  `confirmation_mdp` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `creation_date` datetime NOT NULL DEFAULT current_timestamp(),
  `secret` text NOT NULL,
  `token` varchar(20) NOT NULL,
  `validation` int(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `notation_ville`
--

CREATE TABLE `notation_ville` (
  `id_notation` int(11) NOT NULL,
  `ville` varchar(250) NOT NULL,
  `dechets` int(1) NOT NULL,
  `pesticides` int(1) NOT NULL,
  `fleurs` int(1) NOT NULL,
  `code_postal` int(5) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `recup_password`
--

CREATE TABLE `recup_password` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `table_membres`
--

CREATE TABLE `table_membres` (
  `id` int(11) NOT NULL,
  `identifiant` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `token` varchar(20) NOT NULL,
  `validation` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `table_membres`
--

INSERT INTO `table_membres` (`id`, `identifiant`, `email`, `password`, `token`, `validation`) VALUES
(53, 'damien', 'damienbroggini@hotmail.fr', '$2y$10$63Dpe63K6BIbUzfSZuPgoel3c1v7FyR8vtFkf1cvP9jEvYOGhWNyC', 'valide', 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `espace_membres_terroir`
--
ALTER TABLE `espace_membres_terroir`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `notation_ville`
--
ALTER TABLE `notation_ville`
  ADD PRIMARY KEY (`id_notation`);

--
-- Index pour la table `recup_password`
--
ALTER TABLE `recup_password`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `table_membres`
--
ALTER TABLE `table_membres`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `espace_membres_terroir`
--
ALTER TABLE `espace_membres_terroir`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT pour la table `notation_ville`
--
ALTER TABLE `notation_ville`
  MODIFY `id_notation` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `recup_password`
--
ALTER TABLE `recup_password`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `table_membres`
--
ALTER TABLE `table_membres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
