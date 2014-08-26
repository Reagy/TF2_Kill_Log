SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `profiles` (
  `communityid` varchar(18) NOT NULL,
  `avatar` varchar(128) NOT NULL,
  `time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `profiles`
 ADD UNIQUE KEY `communityid` (`communityid`);
