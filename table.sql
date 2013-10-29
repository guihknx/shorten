CREATE TABLE IF NOT EXISTS `shorten` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `hash` varchar(255) NOT NULL,
  `url` longtext NOT NULL,
  `data` datetime NOT NULL,
  `clicks` int(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;