
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- countries
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `countries`;

CREATE TABLE `countries`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `country` VARCHAR(255) NOT NULL,
    `code` VARCHAR(255) NOT NULL,
    `latitude` DOUBLE NOT NULL,
    `longitude` DOUBLE NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
