--
-- Alter Table structure for table `#__avisamusicdb_musics`
--

ALTER TABLE `#__avisamusicdb_musics`
CHANGE `avisamusicdb_music_id` `id` bigint unsigned NOT NULL auto_increment,
CHANGE `slug` `alias` varchar(255) NULL DEFAULT NULL,
CHANGE `enabled` `published` tinyint(1) NULL DEFAULT '0',
CHANGE `created_on` `created` datetime NULL DEFAULT NULL,
CHANGE `modified_on` `modified` datetime NULL DEFAULT NULL,
CHANGE `locked_on` `checked_out_time` datetime NULL DEFAULT NULL,
CHANGE `locked_by` `checked_out` bigint NOT NULL DEFAULT '0',
CHANGE `release_date` `release_date` date NULL DEFAULT NULL,
ADD COLUMN `publish_up` datetime NULL DEFAULT NULL,
ADD COLUMN `publish_down` datetime NULL DEFAULT NULL;

UPDATE `#__avisamusicdb_musics` SET `created` = '1980-01-01 00:00:00' WHERE `created` = '0000-00-00 00:00:00';
UPDATE `#__avisamusicdb_musics` SET `modified` = NULL WHERE `modified` = '0000-00-00 00:00:00';
UPDATE `#__avisamusicdb_musics` SET `checked_out_time` = NULL WHERE `checked_out_time` = '0000-00-00 00:00:00';
UPDATE `#__avisamusicdb_musics` SET `release_date` = NULL WHERE `release_date` = '0000-00-00';
--
-- Alter Table structure for table `#__avisamusicdb_celebrities`
--

ALTER TABLE `#__avisamusicdb_celebrities`
CHANGE `avisamusicdb_celebrity_id` `id` bigint unsigned NOT NULL auto_increment,
CHANGE `slug` `alias` varchar(255) NULL DEFAULT NULL,
CHANGE `dob` `dob` date NULL DEFAULT NULL,
CHANGE `enabled` `published` tinyint(1) NULL DEFAULT '0',
CHANGE `locked_by` `checked_out` bigint NOT NULL DEFAULT '0',
CHANGE `locked_on` `checked_out_time` datetime NULL DEFAULT NULL,
CHANGE `modified_on` `modified` datetime NULL DEFAULT NULL,
CHANGE `created_on` `created` datetime NULL DEFAULT NULL,
ADD COLUMN `publish_up` datetime NULL DEFAULT NULL,
ADD COLUMN `publish_down` datetime NULL DEFAULT NULL;

UPDATE `#__avisamusicdb_celebrities` SET `created` = '1980-01-01 00:00:00' WHERE `created` = '0000-00-00 00:00:00';
UPDATE `#__avisamusicdb_celebrities` SET `modified` = NULL WHERE `modified` = '0000-00-00 00:00:00';
UPDATE `#__avisamusicdb_celebrities` SET `checked_out_time` = NULL WHERE `checked_out_time` = '0000-00-00 00:00:00';
UPDATE `#__avisamusicdb_celebrities` SET `dob` = NULL WHERE `dob` = '0000-00-00';

--
-- Alter Table structure for table `#__avisamusicdb_genres`
--

ALTER TABLE `#__avisamusicdb_genres`
CHANGE `avisamusicdb_genre_id` `id` bigint unsigned NOT NULL auto_increment,
CHANGE `slug` `alias` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
CHANGE `enabled` `published` tinyint(1) NULL,
CHANGE `created_on` `created` datetime NULL DEFAULT NULL,
CHANGE `modified_on` `modified` datetime NULL DEFAULT NULL,
CHANGE `locked_by` `checked_out` bigint NOT NULL DEFAULT '0',
CHANGE `locked_on` `checked_out_time` datetime NULL DEFAULT NULL,
ADD COLUMN `publish_up` datetime NULL DEFAULT NULL,
ADD COLUMN `publish_down` datetime NULL DEFAULT NULL;

UPDATE `#__avisamusicdb_genres` SET `created` = '1980-01-01 00:00:00' WHERE `created` = '0000-00-00 00:00:00';
UPDATE `#__avisamusicdb_genres` SET `modified` = NULL WHERE `modified` = '0000-00-00 00:00:00';
UPDATE `#__avisamusicdb_genres` SET `checked_out_time` = NULL WHERE `checked_out_time` = '0000-00-00 00:00:00';
--
-- Alter Table structure for table `#__avisamusicdb_reviews`
--

UPDATE `#__avisamusicdb_reviews` SET `ordering`=0 WHERE ordering is NULL;

ALTER TABLE `#__avisamusicdb_reviews`
CHANGE `avisamusicdb_review_id` `id` bigint unsigned NOT NULL auto_increment,
CHANGE `enabled` `published` tinyint(1) NULL,
CHANGE `created_on` `created` datetime NULL DEFAULT NULL,
CHANGE `ordering` `ordering` int(10) NOT NULL DEFAULT '0',
ADD COLUMN `modified` datetime NULL DEFAULT NULL,
ADD COLUMN `modified_by` bigint(20) NOT NULL DEFAULT '0',
ADD COLUMN `checked_out` bigint NOT NULL DEFAULT '0',
ADD COLUMN `checked_out_time` datetime NULL DEFAULT NULL,
ADD COLUMN `publish_up` datetime NULL DEFAULT NULL,
ADD COLUMN `publish_down` datetime NULL DEFAULT NULL;

UPDATE `#__avisamusicdb_reviews` SET `created` = '1980-01-01 00:00:00' WHERE `created` = '0000-00-00 00:00:00';
UPDATE `#__avisamusicdb_reviews` SET `modified` = NULL WHERE `modified` = '0000-00-00 00:00:00';
UPDATE `#__avisamusicdb_reviews` SET `checked_out_time` = NULL WHERE `checked_out_time` = '0000-00-00 00:00:00';

--
-- Alter Table structure for table `#__avisamedia`
--

ALTER TABLE `#__avisamedia`
CHANGE `created_on` `created` datetime NULL DEFAULT NULL,
CHANGE `modified_on` `modified` datetime NULL DEFAULT NULL;

UPDATE `#__avisamedia` SET `created` = '1980-01-01 00:00:00' WHERE `created` = '0000-00-00 00:00:00';
UPDATE `#__avisamedia` SET `modified` = NULL WHERE `modified` = '0000-00-00 00:00:00';