CREATE TABLE `official`.`category_seo_link` (
	`id`          INT(11)      NOT NULL AUTO_INCREMENT,
	`category_id` INT(11)      NOT NULL,
	`lang_id`     VARCHAR(3)   NOT NULL DEFAULT 'en',
	`abbr_cd`     VARCHAR(100) NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `name_idx` (`abbr_cd`)
)
	ENGINE = InnoDB
	DEFAULT CHARACTER SET = utf8;
