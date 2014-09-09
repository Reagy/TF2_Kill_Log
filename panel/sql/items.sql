SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


CREATE TABLE IF NOT EXISTS `items` (
  `index` int(6) NOT NULL DEFAULT '0',
  `name` varchar(64) DEFAULT NULL,
  `image` varchar(72) DEFAULT NULL,
  `class` varchar(72) DEFAULT NULL,
  `slot` varchar(9) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


ALTER TABLE `items`
 ADD PRIMARY KEY (`index`), ADD KEY `class` (`class`), ADD KEY `slot` (`slot`);
 