CREATE TABLE `official`.`category_name` (
	`id`          INT(11)      NOT NULL AUTO_INCREMENT,
	`category_id` INT(11)      NOT NULL,
	`lang_id`     VARCHAR(3)   NOT NULL DEFAULT 'en',
	`name`        VARCHAR(100) NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `name_idx` (`name`)
)
	ENGINE = InnoDB
	DEFAULT CHARACTER SET = utf8;
