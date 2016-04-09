
CREATE TABLE IF NOT EXISTS `uri_data` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uri` varchar(100) DEFAULT NULL,
  `html_raw` text,
  `rdf_raw` text NOT NULL,
  `words` text NOT NULL,
  `log` text NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `stale` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uri` (`uri`),
  FULLTEXT (words)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0;