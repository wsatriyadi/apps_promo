
-- CREATE DATABASE IF NOT EXISTS `db_apps_promo` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci	;
-- USE `db_apps_promo`;

-- Delete free table
-- DROP TABLE `free`;

-- Create free table
CREATE TABLE IF NOT EXISTS `free` (
	`id` int(11) NOT NULL AUTO_INCREMENT ,
	`title` varchar(360) NOT NULL ,
	`thumbnail` longtext NOT NULL ,
	`link` varchar(360) NOT NULL ,
	`text` text NOT NULL ,
	PRIMARY KEY (`id`)
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


