--
-- Table structure for table `#__avisamusicdb_musics`
--

CREATE TABLE IF NOT EXISTS `#__avisamusicdb_musics` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `featured` tinyint(2) DEFAULT '0',
  `music_story` text,
  `release_date` date NULL DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `directors` text,
  `actors` text,
  `albums` text,
  `genres` text,
  `duration` varchar(50) DEFAULT NULL,
  `country` varchar(10) DEFAULT NULL,
  `m_language` varchar(255) DEFAULT '',
  `dvdlink` text,
  `show_time` text,
  `website` varchar(255) DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `twitter` varchar(255) DEFAULT NULL,
  `vimeo` varchar(255) DEFAULT NULL,
  `gplus` varchar(255) DEFAULT NULL,
  `youtube` varchar(255) DEFAULT NULL,
  `trailer_one_title` varchar(255) DEFAULT NULL,
  `trailer_one` text,
  `t_thumb_one` text,
  `trailer_two_title` varchar(255) DEFAULT NULL,
  `trailer_two` text,
  `t_thumb_two` text,
  `trailer_three_title` varchar(255) DEFAULT NULL,
  `trailer_three` text,
  `t_thumb_three` text,
  `trailer_four_title` varchar(255) DEFAULT NULL,
  `trailer_four` text,
  `t_thumb_four` text,
  `trailer_five_title` varchar(255) DEFAULT NULL,
  `trailer_five` text,
  `t_thumb_five` text,
  `trailer_six_title` varchar(255) DEFAULT NULL,
  `trailer_six` text,
  `t_thumb_six` text,
  `trailer_seven_title` varchar(255) DEFAULT NULL,
  `trailer_seven` text,
  `t_thumb_seven` text,
  `trailer_eight_title` varchar(255) DEFAULT NULL,
  `trailer_eight` text,
  `t_thumb_eight` text,
  `trailer_nine_title` varchar(255) DEFAULT NULL,
  `trailer_nine` text,
  `t_thumb_nine` text,
  `trailer_ten_title` varchar(255) DEFAULT '',
  `trailer_ten` text,
  `t_thumb_ten` text,
  `access` int(10) NOT NULL DEFAULT '0',
  `hits` bigint(20) DEFAULT '0',
  `language` char(7) NOT NULL DEFAULT '*',
  `ordering` int(10) NOT NULL DEFAULT '0',
  `published` tinyint(1) DEFAULT '0',
  `publish_up` datetime NULL DEFAULT NULL,
  `publish_down` datetime NULL DEFAULT NULL,
  `created` datetime NULL DEFAULT NULL,
  `created_by` bigint(20) NOT NULL DEFAULT '0',
  `modified` datetime NULL DEFAULT NULL,
  `modified_by` bigint(20) NOT NULL DEFAULT '0',
  `checked_out` bigint(20) NOT NULL DEFAULT '0',
  `checked_out_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `#__avisamusicdb_celebrities`
--

CREATE TABLE IF NOT EXISTS `#__avisamusicdb_celebrities` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `hits` bigint(20) DEFAULT '0',
  `celebrity_type` varchar(10) DEFAULT NULL,
  `gender` varchar(10) DEFAULT 'male',
  `featured` tinyint(1) DEFAULT '0',
  `biography` text,
  `designation` varchar(255) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `birth_name` varchar(255) DEFAULT '',
  `dob` date NULL DEFAULT NULL,
  `residence` varchar(255) DEFAULT '',
  `height` varchar(50) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `twitter` varchar(255) DEFAULT NULL,
  `linkedin` varchar(255) DEFAULT NULL,
  `gplus` varchar(255) DEFAULT NULL,
  `youtube` varchar(255) DEFAULT NULL,
  `vimeo` varchar(255) DEFAULT NULL,
  `language` char(7) NOT NULL DEFAULT '*',
  `access` int(10) NOT NULL DEFAULT '0',
  `ordering` int(10) NOT NULL DEFAULT '0',
  `published` tinyint(1) DEFAULT '0',
  `publish_up` datetime NULL DEFAULT NULL,
  `publish_down` datetime NULL DEFAULT NULL,
  `created` datetime NULL DEFAULT NULL,
  `created_by` bigint(20) NOT NULL DEFAULT '0',
  `modified` datetime NULL DEFAULT NULL,
  `modified_by` bigint(20) NOT NULL DEFAULT '0',
  `checked_out` bigint(20) NOT NULL DEFAULT '0',
  `checked_out_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `#__avisamusicdb_genres`
--

CREATE TABLE IF NOT EXISTS `#__avisamusicdb_genres` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `language` char(7) NOT NULL DEFAULT '*',
  `access` int(10) DEFAULT '0',
  `ordering` int(10) NOT NULL DEFAULT '0',
  `published` tinyint(1) DEFAULT '0',
  `publish_up` datetime NULL DEFAULT NULL,
  `publish_down` datetime NULL DEFAULT NULL,
  `created` datetime NULL DEFAULT NULL,
  `created_by` bigint(20) NOT NULL DEFAULT '0',
  `modified` datetime NULL DEFAULT NULL,
  `modified_by` bigint(20) NOT NULL DEFAULT '0',
  `checked_out` bigint(20) NOT NULL DEFAULT '0',
  `checked_out_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `#__avisamusicdb_reviews`
--

CREATE TABLE IF NOT EXISTS `#__avisamusicdb_reviews` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `musicid` bigint(20) DEFAULT NULL,
  `rating` tinyint(2) DEFAULT '0',
  `review` text,
  `ordering` int(10) NOT NULL DEFAULT '0',
  `published` tinyint(1) DEFAULT '0',
  `publish_up` datetime NULL DEFAULT NULL,
  `publish_down` datetime NULL DEFAULT NULL,
  `created` datetime NULL DEFAULT NULL,
  `created_by` bigint(20) NOT NULL DEFAULT '0',
  `modified` datetime NULL DEFAULT NULL,
  `modified_by` bigint(20) NOT NULL DEFAULT '0',
  `checked_out` bigint(20) NOT NULL DEFAULT '0',
  `checked_out_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `#__avisamedia`
--

CREATE TABLE IF NOT EXISTS `#__avisamedia` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `alt` varchar(255) NOT NULL,
  `caption` varchar(2048) NOT NULL,
  `description` mediumtext NOT NULL,
  `type` varchar(100) NOT NULL DEFAULT 'image',
  `extension` varchar(100) NOT NULL,
  `created` datetime NULL DEFAULT NULL,
  `created_by` bigint(20) NOT NULL DEFAULT '0',
  `modified` datetime NULL DEFAULT NULL,
  `modified_by` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `#__avisamusicdb_albums`
--

CREATE TABLE IF NOT EXISTS `#__avisamusicdb_albums` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `actor` varchar(255) DEFAULT NULL,
  `hits` bigint(20) DEFAULT '0',
  `featured` tinyint(1) DEFAULT '0',
  `albumbio` text,
  `profile_image` varchar(255) DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `album_name` varchar(255) DEFAULT '',
  `dob` date NULL DEFAULT NULL,
  `residence` varchar(255) DEFAULT '',
  `height` varchar(50) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `twitter` varchar(255) DEFAULT NULL,
  `linkedin` varchar(255) DEFAULT NULL,
  `gplus` varchar(255) DEFAULT NULL,
  `youtube` varchar(255) DEFAULT NULL,
  `vimeo` varchar(255) DEFAULT NULL,
  `language` char(7) NOT NULL DEFAULT '*',
  `access` int(10) NOT NULL DEFAULT '0',
  `ordering` int(10) NOT NULL DEFAULT '0',
  `published` tinyint(1) DEFAULT '0',
  `publish_up` datetime NULL DEFAULT NULL,
  `publish_down` datetime NULL DEFAULT NULL,
  `created` datetime NULL DEFAULT NULL,
  `created_by` bigint(20) NOT NULL DEFAULT '0',
  `modified` datetime NULL DEFAULT NULL,
  `modified_by` bigint(20) NOT NULL DEFAULT '0',
  `checked_out` bigint(20) NOT NULL DEFAULT '0',
  `checked_out_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;