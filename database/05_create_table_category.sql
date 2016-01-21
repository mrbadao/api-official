CREATE TABLE `official`.`category` (
  `id`       INT(11)    NOT NULL AUTO_INCREMENT,
  `del_flag` TINYINT(1) NOT NULL DEFAULT 0,
  `created`  DATETIME   NULL     DEFAULT NULL,
  `modified` DATETIME   NULL     DEFAULT NULL,
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARACTER SET = utf8;
