
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- professional_categories
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `professional_categories`;

CREATE TABLE `professional_categories`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `professional_category` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
