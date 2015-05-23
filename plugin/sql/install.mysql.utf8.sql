CREATE TABLE IF NOT EXISTS `#__jpopular_content` (
`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`ordering` INT(11) NOT NULL,
`state` TINYINT(1) NOT NULL DEFAULT '1',
`hits` INT(11) NOT NULL DEFAULT '0',
`action_by` VARCHAR(255) NOT NULL DEFAULT 'Guest',
`article_id` INT(11) NOT NULL DEFAULT '0',
`article_title` VARCHAR(255) NOT NULL,
`day_logged` VARCHAR(10) NOT NULL,
`ip_address` VARCHAR(15) NOT NULL DEFAULT 'Not available',
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;
