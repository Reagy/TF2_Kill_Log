SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

DROP TABLE IF EXISTS `items_method`;
CREATE TABLE IF NOT EXISTS `items_method` (
  `method_type` int(2) NOT NULL,
  `method_text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `items_method` (`method_type`, `method_text`) VALUES
(0, 'Found'),
(1, 'Crafted'),
(2, 'Traded'),
(3, 'Bought'),
(4, 'Unboxed'),
(5, 'Gifted'),
(8, 'Earned'),
(9, 'Refunded'),
(10, 'Wrapped'),
(15, 'Earned'),
(16, 'MvM'),
(18, 'Holiday Gift'),
(20, 'MvM'),
(21, 'MvM');

DROP TABLE IF EXISTS `items_quality`;
CREATE TABLE IF NOT EXISTS `items_quality` (
  `quality_type` int(2) NOT NULL,
  `quality_text` varchar(24) NOT NULL,
  `quality_color` varchar(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `items_quality` (`quality_type`, `quality_text`, `quality_color`) VALUES
(1, 'Genuine', '#4D7455'),
(3, 'Vintage', '#476291'),
(5, 'Unusual', '#8650AC'),
(6, 'Unique', '#FFD700'),
(7, 'Community', '#70B04A'),
(9, 'Self-Made', '#70B04A'),
(11, 'Strange', '#CF6A32'),
(13, 'Haunted', '#38F3AB'),
(14, 'Collectors', '#AA0000');
