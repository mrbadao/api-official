CREATE TABLE `official`.`media` (
	`media_id`          INT(11)      NOT NULL AUTO_INCREMENT,
	`media_name`        VARCHAR(128) NOT NULL,
	`media_mime_type`   VARCHAR(60)  NOT NULL,
	`media_link`        VARCHAR(200) NOT NULL DEFAULT 'http://localhost/',
	`media_thumbnail`   VARCHAR(200) NOT NULL DEFAULT 'http://localhost/',
	`media_size`        VARCHAR(11)  NOT NULL,
	`media_dimension`   VARCHAR(45)  NULL,
	`media_origin_name` VARCHAR(128) NULL,
	`created`           TIMESTAMP    NULL,
	`modified`          TIMESTAMP    NULL,
	PRIMARY KEY (`media_id`),
	UNIQUE INDEX `media_name_UNIQUE` (`media_name` ASC),
	UNIQUE INDEX `media_id_UNIQUE` (`media_id` ASC),
	INDEX `media_link_idx` USING BTREE (`media_link` ASC),
	INDEX `media_thumbnail_idx` (`media_thumbnail` ASC),
	INDEX `created_idx` (`created` ASC)
)
	ENGINE = InnoDB
	DEFAULT CHARACTER SET = utf8;
