CREATE TABLE `official`.`access_tokens` (
	`token_id` INT(11)   NOT NULL,
	`user_id`  INT(11)   NOT NULL,
	`token`    TEXT      NOT NULL,
	`created`  DATETIME  NULL DEFAULT NULL,
	`expired`  TIMESTAMP NULL DEFAULT NULL,

	PRIMARY KEY (`token_id`),
	UNIQUE INDEX `token_id_UNIQUE` (`token_id` ASC)
)
	DEFAULT CHARACTER SET = utf8;
