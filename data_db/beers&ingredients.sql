-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Hôte : maria_db:3306
-- Généré le : mer. 03 mai 2023 à 08:03
-- Version du serveur :  10.11.2-MariaDB-1:10.11.2+maria~ubu2204
-- Version de PHP : 7.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Base de données : `apiphp_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `beers`
--

CREATE TABLE `beers` (
  `id` int(255) NOT NULL,
  `name` varchar(25) DEFAULT NULL,
  `tagline` varchar(50) NOT NULL,
  `first_brewed` date NOT NULL,
  `description` varchar(250) NOT NULL,
  `image_url` varchar(500) NOT NULL,
  `brewers_tips` varchar(500) NOT NULL,
  `contribued_by` varchar(50) NOT NULL,
  `food_pairing1` varchar(50) DEFAULT NULL,
  `food_pairing2` varchar(50) DEFAULT NULL,
  `food_pairing3` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `beer_ingredient`
--

CREATE TABLE `beer_ingredient` (
  `beer_id` int(255) NOT NULL,
  `ingredient_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ingredients`
--

CREATE TABLE `ingredients` (
  `id` int(255) NOT NULL,
  `type` enum('malt','hops') NOT NULL,
  `name` varchar(50) NOT NULL,
  `amount_value` float(255,1) NOT NULL,
  `amount_unit` varchar(15) NOT NULL,
  `amount_add` varchar(15) DEFAULT NULL,
  `amount_attribute` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `beers`
--
ALTER TABLE `beers`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `beer_ingredient`
--
ALTER TABLE `beer_ingredient`
  ADD KEY `ingredient_id` (`ingredient_id`),
  ADD KEY `beer_id` (`beer_id`);

--
-- Index pour la table `ingredients`
--
ALTER TABLE `ingredients`
  ADD PRIMARY KEY (`id`);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `beer_ingredient`
--
ALTER TABLE `beer_ingredient`
  ADD CONSTRAINT `beer_ingredient_ibfk_1` FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients` (`id`),
  ADD CONSTRAINT `beer_ingredient_ibfk_2` FOREIGN KEY (`beer_id`) REFERENCES `beers` (`id`);
COMMIT;
