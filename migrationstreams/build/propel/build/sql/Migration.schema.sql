
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- migrations
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `city` VARCHAR(255),
    `country_id` INTEGER NOT NULL,
    `month` INTEGER NOT NULL,
    `year` INTEGER NOT NULL,
    `person_id` INTEGER NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `migrations_FI_1` (`country_id`),
    INDEX `migrations_FI_2` (`person_id`)
) ENGINE=MyISAM;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
