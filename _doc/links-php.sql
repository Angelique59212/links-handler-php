-- phpMyAdmin SQL Dump
-- version 5.1.4deb1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : dim. 18 déc. 2022 à 00:18
-- Version du serveur : 8.0.31-0ubuntu2
-- Version de PHP : 8.1.7-1ubuntu3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `links-php`
--

-- --------------------------------------------------------

--
-- Structure de la table `mdf58_links`
--

CREATE TABLE `mdf58_links` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `image` text,
  `user_fk` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `mdf58_links`
--

INSERT INTO `mdf58_links` (`id`, `name`, `link`, `image`, `user_fk`) VALUES
(13, 'Google', 'https://www.google.com/', '221217090417-639e20c1933c7.png', 21),
(14, 'facebook', 'https://www.facebook.com/', '221217090425-639e20c9dde20.jpg', 21);

-- --------------------------------------------------------

--
-- Structure de la table `mdf58_user`
--

CREATE TABLE `mdf58_user` (
  `id` int UNSIGNED NOT NULL,
  `pseudo` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `mdf58_user`
--

INSERT INTO `mdf58_user` (`id`, `pseudo`, `email`, `password`) VALUES
(19, 'titi', 'titi@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$MU5kVkhOUDN0N0lKUmEyQw$PrIZ5BFgUB4GBRA2UaagQRtf+OLoGDlskfrhunk6BhY'),
(21, 'angelika', 'dehainaut.angelique@orange.fr', '$argon2i$v=19$m=65536,t=4,p=1$Sy95Szc5S21keUMuWGcvZg$S23CfNKoOMptpq/zSU7IdXyt9AzDywWbZnE+TyFX9yE');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `mdf58_links`
--
ALTER TABLE `mdf58_links`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_fk_idx` (`user_fk`);

--
-- Index pour la table `mdf58_user`
--
ALTER TABLE `mdf58_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `mdf58_links`
--
ALTER TABLE `mdf58_links`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pour la table `mdf58_user`
--
ALTER TABLE `mdf58_user`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `mdf58_links`
--
ALTER TABLE `mdf58_links`
  ADD CONSTRAINT `fk_mdf58_links_mdf58_user` FOREIGN KEY (`user_fk`) REFERENCES `mdf58_user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
