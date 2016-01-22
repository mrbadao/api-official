CREATE TABLE `official`.`language` (
	`id`       VARCHAR(3)   NOT NULL,
	`name`     VARCHAR(100) NOT NULL,
	`active`   TINYINT(1)   NOT NULL DEFAULT 1,
	`created`  DATETIME              DEFAULT NULL,
	`modified` DATETIME              DEFAULT NULL,
	PRIMARY KEY (`id`),
	INDEX `name_idx` (`name`)
)
	ENGINE = InnoDB
	DEFAULT CHARACTER SET = utf8;
