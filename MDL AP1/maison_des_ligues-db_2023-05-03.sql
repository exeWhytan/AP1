-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 03 mai 2023 à 05:28
-- Version du serveur : 8.0.31
-- Version de PHP : 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `maison_des_ligues`
--

-- --------------------------------------------------------

--
-- Structure de la table `demande`
--

CREATE TABLE `demande` (
  `Numdemande` int NOT NULL,
  `Objetdemande` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `Idetat` int NOT NULL,
  `Idpriorite` int NOT NULL,
  `assignee` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `demande`
--

INSERT INTO `demande` (`Numdemande`, `Objetdemande`, `Idetat`, `Idpriorite`, `assignee`) VALUES
(33, 'aller chez Pierre', 4, 1, 'ElonCartman'),
(34, 'aaahahahahah', 4, 1, 'ElonCartman');

-- --------------------------------------------------------

--
-- Structure de la table `etat`
--

CREATE TABLE `etat` (
  `Idetat` int NOT NULL,
  `Etatavancement` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `etat`
--

INSERT INTO `etat` (`Idetat`, `Etatavancement`) VALUES
(1, ' non assignée'),
(2, 'en cours de réalisation'),
(3, 'en attente'),
(4, 'terminée');

-- --------------------------------------------------------

--
-- Structure de la table `priorite`
--

CREATE TABLE `priorite` (
  `Idpriorite` int NOT NULL,
  `Niveaupriorite` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `priorite`
--

INSERT INTO `priorite` (`Idpriorite`, `Niveaupriorite`) VALUES
(1, 1),
(2, 2),
(3, 3);

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

CREATE TABLE `role` (
  `Idrole` int NOT NULL,
  `Fonction` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `role`
--

INSERT INTO `role` (`Idrole`, `Fonction`) VALUES
(1, 'utilisateur'),
(2, 'employe'),
(3, 'responsable');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `Mail` varchar(100) NOT NULL,
  `Nom` varchar(20) NOT NULL,
  `Adresse` text NOT NULL,
  `Prenom` varchar(20) NOT NULL,
  `Tel` int NOT NULL,
  `Idrole` int NOT NULL,
  `Identifiant` varchar(12) NOT NULL,
  `Password` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`Mail`, `Nom`, `Adresse`, `Prenom`, `Tel`, `Idrole`, `Identifiant`, `Password`) VALUES
('davidsql@gmail.com', 'Masclet', '21 rue du Linux', 'David', 646721496, 3, 'DavidSQL', 'DavidSQL'),
('Zizoulezidane@gmail.com', 'lezidane', '14 rue du ballon ', 'Zidane', 606060606, 2, 'Lezizou', 'Lezizou'),
('bobleponge@gmail.com', 'Leponge', '21 rue des Ananas', 'Bob', 321231818, 1, 'BobLeponge', 'BobLeponge'),
('eloncartman@gmail.com', 'Cartman', '21 rue du Chili', 'Elon', 0, 2, 'ElonCartman', 'ElonCartman');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `demande`
--
ALTER TABLE `demande`
  ADD PRIMARY KEY (`Numdemande`),
  ADD KEY `Idpriorite` (`Idpriorite`),
  ADD KEY `Idetat` (`Idetat`);

--
-- Index pour la table `etat`
--
ALTER TABLE `etat`
  ADD PRIMARY KEY (`Idetat`);

--
-- Index pour la table `priorite`
--
ALTER TABLE `priorite`
  ADD PRIMARY KEY (`Idpriorite`);

--
-- Index pour la table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`Idrole`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD KEY `Idrole` (`Idrole`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `demande`
--
ALTER TABLE `demande`
  MODIFY `Numdemande` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `demande`
--
ALTER TABLE `demande`
  ADD CONSTRAINT `demande_ibfk_1` FOREIGN KEY (`Idpriorite`) REFERENCES `priorite` (`Idpriorite`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `demande_ibfk_2` FOREIGN KEY (`Idetat`) REFERENCES `etat` (`Idetat`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`Idrole`) REFERENCES `role` (`Idrole`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
