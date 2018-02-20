SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE TABLE `heroes` (
  `id` int(11) NOT NULL,
  `angel_name` varchar(250) COLLATE utf8_bin NOT NULL,
  `name` varchar(250) COLLATE utf8_bin NOT NULL,
  `might` int(11) DEFAULT NULL,
  `can_affect_game` tinyint(1) DEFAULT NULL,
  `last_visit` datetime DEFAULT NULL,
  `alive` tinyint(1) DEFAULT NULL,
  `clan_name` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `clan_id` int(11) DEFAULT NULL,
  `gender` int(11) DEFAULT NULL,
  `race` int(11) DEFAULT NULL,
  `level` int(11) DEFAULT NULL,
  `exp` int(11) DEFAULT NULL,
  `power` int(11) DEFAULT NULL,
  `companion` varchar(40) COLLATE utf8_bin DEFAULT NULL,
  `peacefulness` int(11) DEFAULT NULL,
  `peacefulness_verbose` varchar(40) COLLATE utf8_bin DEFAULT NULL,
  `honor` int(11) DEFAULT NULL,
  `honor_verbose` varchar(40) COLLATE utf8_bin DEFAULT NULL,
  `money` int(11) DEFAULT NULL,
  `strength` int(11) DEFAULT NULL,
  `physic` int(11) DEFAULT NULL,
  `magic` int(11) DEFAULT NULL,
  `lvl_equip` int(11) DEFAULT NULL,
  `lvl_equip_title` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `avg_equip` int(11) DEFAULT NULL,
  `speed` float DEFAULT NULL,
  `initiative` float DEFAULT NULL,
  `position` varchar(10) COLLATE utf8_bin DEFAULT NULL,
  `quest` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `quest_list` varchar(500) COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `masters` (
  `id` int(11) NOT NULL,
  `name` varchar(40) COLLATE utf8_bin DEFAULT NULL,
  `profession` int(11) DEFAULT NULL,
  `gender` int(11) DEFAULT NULL,
  `race` int(11) DEFAULT NULL,
  `practic` varchar(40) COLLATE utf8_bin DEFAULT NULL,
  `cosmetic` varchar(40) COLLATE utf8_bin DEFAULT NULL,
  `city_id` int(11) NOT NULL,
  `building` int(11) DEFAULT NULL,
  `integrity` float DEFAULT NULL,
  `politic_power` int(11) DEFAULT NULL,
  `power_outer` int(11) DEFAULT NULL,
  `power_outer_fraction` int(11) DEFAULT NULL,
  `power_inner` int(11) DEFAULT NULL,
  `power_inner_fraction` int(11) DEFAULT NULL,
  `job_effect` int(11) DEFAULT NULL,
  `job_name` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `positive_job` int(11) DEFAULT NULL,
  `negative_job` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `places` (
  `id` int(11) NOT NULL,
  `name` varchar(40) COLLATE utf8_bin NOT NULL,
  `frontier` tinyint(1) DEFAULT NULL,
  `specialization` int(11) DEFAULT NULL,
  `demographics` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `demographics_title` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `persons_count` int(11) DEFAULT NULL,
  `persons` varchar(500) COLLATE utf8_bin DEFAULT NULL,
  `stability` float DEFAULT NULL,
  `stability_title` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `freedom` float DEFAULT NULL,
  `freedom_title` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `production` int(11) DEFAULT NULL,
  `production_title` varchar(250) COLLATE utf8_bin NOT NULL,
  `transport` float DEFAULT NULL,
  `transport_title` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `safety` float DEFAULT NULL,
  `safety_title` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `time` int(11) DEFAULT NULL,
  `culture` float DEFAULT NULL,
  `culture_title` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `area` int(11) DEFAULT NULL,
  `size` int(11) DEFAULT NULL,
  `economy` int(11) DEFAULT NULL,
  `politic_power` int(11) DEFAULT NULL,
  `power_outer` int(11) DEFAULT NULL,
  `power_outer_fraction` int(11) DEFAULT NULL,
  `power_inner` int(11) DEFAULT NULL,
  `power_inner_fraction` int(11) DEFAULT NULL,
  `bills_count` int(11) DEFAULT NULL,
  `bills` varchar(500) COLLATE utf8_bin DEFAULT NULL,
  `job_effect` int(11) DEFAULT NULL,
  `job_name` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `positive_job` int(11) DEFAULT NULL,
  `negative_job` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `table_updates` (
  `table_name` varchar(40) COLLATE utf8_bin NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


ALTER TABLE `heroes`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `masters`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `places`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `table_updates`
  ADD PRIMARY KEY (`table_name`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
