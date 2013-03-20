DROP TABLE IF EXISTS `#__pbevents_events`;
DROP TABLE IF EXISTS `#__pbevents_events_dates`;
DROP TABLE IF EXISTS `#__pbevents_rsvps`;
DROP TABLE IF EXISTS `#__pbevents_config`;

CREATE TABLE `#__pbevents_events` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(256),
  `description` text,
  `price` FLOAT(11,2) DEFAULT 0.00,
  `panelist` varchar(256) DEFAULT NULL,
  `hstart` TIME DEFAULT NULL,
  `hend` TIME DEFAULT NULL,
  `publish` tinyint(1) DEFAULT 1,
  `max_people` int(128) DEFAULT 0,
  `show_counter` tinyint(1) DEFAULT 0,
  `email_admin_success` tinyint(1) DEFAULT 0,
  `email_admin_failure` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;

CREATE TABLE `#__pbevents_rsvps` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `event_id` int(11) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_pbevents_rsvps_events`
  FOREIGN KEY (`event_id`) REFERENCES `#__pbevents_events` (`id`)
  ON DELETE CASCADE 
  ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE TABLE `#__pbevents_events_dates` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `date` DATE DEFAULT NULL,
  `hstart` TIME DEFAULT NULL,
  `hend` TIME DEFAULT NULL,
  `henable` tinyint(1) DEFAULT 0,
  `description` text DEFAULT NULL,
  `event_id` int(11) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_pbevents_dates_events`
  FOREIGN KEY (`event_id`) REFERENCES `#__pbevents_events` (`id`)
  ON DELETE CASCADE 
  ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE TABLE `#__pbevents_config` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `send_notifications_to` varchar(256),
  `email_success_body` text,
  `email_success_subject` text,
  `email_failed_body` text,
  `email_failed_subject` text,
  `date_picker_locale` varchar(10),
  PRIMARY KEY (`id`))
 ENGINE = InnoDB;
 