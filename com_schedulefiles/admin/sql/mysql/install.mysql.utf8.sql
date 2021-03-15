/*
* CREATE main table
*/
CREATE TABLE IF NOT EXISTS `#__scheduled_tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `asset_id` INT(10) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `frequency` enum('Weekly', 'Daily', 'Hourly') NOT NULL DEFAULT 'Hourly',
  `day` enum('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday') DEFAULT NULL,
  `hour` int(2) NOT NULL DEFAULT 0,
  `active` int(1) NOT NULL DEFAULT 0,
  `date_created` datetime DEFAULT CURRENT_TIMESTAMP,
  `date_modified` datetime DEFAULT NULL,
  `date_last_run` datetime DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `modified_by` int(11) NOT NULL,
  `executed_by` varchar(255) NOT NULL,
  `delete` tinyint(1) NOT NULL DEFAULT 0,
  `output` varchar(15000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
/*
* CREATE copy of main table, adding an extra column
*/
CREATE TABLE IF NOT EXISTS `backup_scheduledtasks` LIKE `#__scheduled_tasks`;
/*
* Populate main table with contents of backup table
*/
INSERT INTO `#__scheduled_tasks` (SELECT * FROM `backup_scheduledtasks` WHERE `delete` = 0);
/*
* CREATE status table
*/
CREATE TABLE IF NOT EXISTS `#__scheduled_tasks_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `task_id` int(11) NOT NULL DEFAULT '0',
  `http_code` int(3) NOT NULL,
  `http_code_text` varchar(255) NOT NULL, 
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
/*
* CREATE copy of status table
*/
CREATE TABLE IF NOT EXISTS `backup_scheduledtasksstatus` LIKE `#__scheduled_tasks_status`;
/*
* Populate status table with contents from backup table
*/
INSERT INTO `#__scheduled_tasks_status` (SELECT `backup_scheduledtasksstatus`.* FROM `backup_scheduledtasksstatus` RIGHT JOIN `backup_scheduledtasks` ON `backup_scheduledtasks`.`id` = `backup_scheduledtasksstatus`.`task_id` WHERE `backup_scheduledtasks`.`delete` = 0);

CREATE TABLE IF NOT EXISTS `#__scheduled_tasks_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `date_created` datetime DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
CREATE TABLE IF NOT EXISTS `backup_scheduledtasksfiles` LIKE `#__scheduled_tasks_files`;
INSERT INTO `backup_scheduledtasksfiles` (SELECT * FROM `#__scheduled_tasks_files`);