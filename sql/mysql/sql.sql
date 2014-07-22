DROP TABLE IF EXISTS `smilecollectionbinaryfile`;
CREATE TABLE IF NOT EXISTS `smilecollectionbinaryfile` (
  `informationcollection_id` int(11) NOT NULL DEFAULT '0',
  `contentobject_attribute_id` int(11) NOT NULL DEFAULT '0',
  `download_count` int(11) NOT NULL DEFAULT '0',
  `filename` varchar(255) NOT NULL DEFAULT '',
  `mime_type` varchar(255) NOT NULL DEFAULT '',
  `original_filename` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`informationcollection_id`,`contentobject_attribute_id`)
) 
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci
