# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.7.24)
# Database: anofie-db
# Generation Time: 2019-09-28 09:07:15 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table captcha
# ------------------------------------------------------------

DROP TABLE IF EXISTS `captcha`;

CREATE TABLE `captcha` (
  `captcha_id` bigint(13) unsigned NOT NULL AUTO_INCREMENT,
  `captcha_time` int(10) unsigned DEFAULT NULL,
  `ip_address` varchar(16) DEFAULT '0',
  `word` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`captcha_id`),
  KEY `word` (`word`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table ci_sessn
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ci_sessn`;

CREATE TABLE `ci_sessn` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  PRIMARY KEY (`id`),
  KEY `anonym_sessn_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


# Dump of table contacts
# ------------------------------------------------------------

DROP TABLE IF EXISTS `contacts`;

CREATE TABLE `contacts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) DEFAULT NULL,
  `email` varchar(256) DEFAULT NULL,
  `title` varchar(128) DEFAULT NULL,
  `message` text,
  `created` datetime DEFAULT NULL,
  `read` datetime DEFAULT NULL,
  `read_by` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `title` (`title`),
  KEY `created` (`created`),
  KEY `read` (`read`),
  KEY `read_by` (`read_by`),
  KEY `email` (`email`(78))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table controllers
# ------------------------------------------------------------

DROP TABLE IF EXISTS `controllers`;

CREATE TABLE `controllers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

LOCK TABLES `controllers` WRITE;
/*!40000 ALTER TABLE `controllers` DISABLE KEYS */;

INSERT INTO `controllers` (`id`, `name`)
VALUES
	(1,'reports'),
	(2,'messages'),
	(3,'users'),
	(4,'settings'),
	(5,'contacts'),
	(6,'pages');

/*!40000 ALTER TABLE `controllers` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table groups
# ------------------------------------------------------------

DROP TABLE IF EXISTS `groups`;

CREATE TABLE `groups` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `date_added` timestamp NULL DEFAULT NULL,
  `date_updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

LOCK TABLES `groups` WRITE;
/*!40000 ALTER TABLE `groups` DISABLE KEYS */;

INSERT INTO `groups` (`id`, `name`, `description`, `date_added`, `date_updated`)
VALUES
	(1,'admin','Administrator',NULL,NULL),
	(2,'editors','Semi-admin',NULL,NULL),
	(3,'members','Public Users (non-admin)',NULL,NULL);

/*!40000 ALTER TABLE `groups` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table login_attempts
# ------------------------------------------------------------

DROP TABLE IF EXISTS `login_attempts`;

CREATE TABLE `login_attempts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) DEFAULT '',
  `login` varchar(100) DEFAULT '',
  `time` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;



# Dump of table messages
# ------------------------------------------------------------

DROP TABLE IF EXISTS `messages`;

CREATE TABLE `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `m_from` int(11) NOT NULL DEFAULT '0',
  `m_to` int(11) NOT NULL DEFAULT '0',
  `ip_address` varchar(256) CHARACTER SET utf8 DEFAULT '',
  `message` varchar(512) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `m_flag` tinyint(1) NOT NULL DEFAULT '0',
  `m_favorite` tinyint(1) NOT NULL DEFAULT '0',
  `m_to_delete` tinyint(1) NOT NULL DEFAULT '0',
  `m_from_delete` tinyint(1) NOT NULL DEFAULT '0',
  `added` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;

INSERT INTO `messages` (`id`, `m_from`, `m_to`, `ip_address`, `message`, `m_flag`, `m_favorite`, `m_to_delete`, `m_from_delete`, `added`)
VALUES
	(1,4,10,'127.0.0.1','If you love someone put their name in a circle not a heart because hearts can break but circles go on forever.',0,1,0,0,'2019-07-04 15:22:33'),
	(4,4,10,'127.0.0.1','You are the cutest and most adorable girl I know.',0,0,0,0,'2019-07-04 16:05:21'),
	(7,4,10,'127.0.0.1','Don\'t lose the one you love for the one you like because the one you like might lose you for the one they love.',0,0,0,0,'2019-07-04 16:07:11'),
	(18,10,4,'127.0.0.1','I gave you 12 roses 11 fake 1 real and said that I will love you until the last one dies.',0,1,0,0,'2019-07-05 09:36:22'),
	(20,9,4,'127.0.0.1','Yesterday I sent an angel to go and look after you but she came back and said an angel cannot look after an angel.',0,0,0,0,'2019-07-05 09:39:17'),
	(27,3,9,'127.0.0.1','Ты самая милая и самая очаровательная девушка, которую я знаю.',0,1,0,0,'2019-07-05 10:04:33'),
	(29,9,3,'127.0.0.1','Я дал тебе 12 роз 11 фальшивых 1 реал и сказал, что буду любить тебя, пока не умрет последняя.',0,1,0,0,'2019-07-05 10:07:38'),
	(33,2,8,'127.0.0.1','أنت الفتاة اللطيفة والرائعة التي أعرفها.',0,1,0,0,'2019-07-05 10:59:21'),
	(36,8,2,'127.0.0.1','أعطيتك 12 وردة 11 وهمية 1 حقيقية وقلت أنني سوف أحبك حتى آخر واحد يموت.',0,1,0,0,'2019-07-05 11:01:33'),
	(41,5,11,'127.0.0.1','你是我认识的最可爱，最可爱的女孩。',0,1,0,0,'2019-07-05 11:25:04'),
	(44,11,5,'127.0.0.1','我给了你12朵玫瑰11假1真实，并说我会爱你直到最后一个死去。',0,1,0,0,'2019-07-05 11:28:04'),
	(51,6,7,'127.0.0.1','あなたは私が知っている最もかわいいと最も愛らしい女の子です。',0,1,0,0,'2019-07-05 11:41:32'),
	(54,7,6,'127.0.0.1','私はあなたに12本のバラ11本物の偽物1を与え、最後のものが死ぬまで私はあなたを愛していると言った。',0,1,0,0,'2019-07-05 11:44:42'),
	(61,7,6,'127.0.0.1','あなたが誰かを愛するならば、心が壊れることができるので円が永遠に続くので、心ではなく円に彼らの名前を入れる。',0,0,0,0,'2019-07-05 12:15:04');

/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table notifications
# ------------------------------------------------------------

DROP TABLE IF EXISTS `notifications`;

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `n_type` varchar(128) CHARACTER SET utf8 COLLATE utf8_estonian_ci DEFAULT NULL,
  `n_content` varchar(128) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `users_id` int(11) DEFAULT NULL,
  `date_added` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=104 DEFAULT CHARSET=utf8;

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;

INSERT INTO `notifications` (`id`, `n_type`, `n_content`, `is_read`, `users_id`, `date_added`)
VALUES
	(1,'users','noti_new_users',0,2,'2019-07-04 12:59:06'),
	(2,'users','noti_new_users',0,3,'2019-07-04 12:59:48'),
	(3,'users','noti_new_users',0,4,'2019-07-04 13:01:12'),
	(4,'users','noti_new_users',0,5,'2019-07-04 13:03:25'),
	(5,'users','noti_new_users',0,6,'2019-07-04 13:04:03');

/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table pages
# ------------------------------------------------------------

DROP TABLE IF EXISTS `pages`;

CREATE TABLE `pages` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `page` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `date_added` timestamp NULL DEFAULT NULL,
  `date_updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;

INSERT INTO `pages` (`id`, `page`, `title`, `content`, `date_added`, `date_updated`)
VALUES
	(1,'about','About Us','<p>Demo about us.....</p>','2019-06-25 11:47:45','2019-09-28 10:04:27'),
	(2,'privacy','Privacy Policy','<p>Demo privacy policy.....</p>','2019-06-25 11:47:45','2019-09-28 10:04:17'),
	(3,'terms','Terms and conditions','<p>Demo terms and conditions.....</p>','2019-06-25 11:47:45','2019-09-28 10:04:09');

/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table permissions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `permissions`;

CREATE TABLE `permissions` (
  `controllers_id` int(11) NOT NULL,
  `groups_id` int(11) NOT NULL,
  `p_add` int(11) NOT NULL DEFAULT '0',
  `p_edit` int(11) NOT NULL DEFAULT '0',
  `p_delete` int(11) NOT NULL DEFAULT '0',
  `p_view` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `controllers_id` (`controllers_id`,`groups_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;

INSERT INTO `permissions` (`controllers_id`, `groups_id`, `p_add`, `p_edit`, `p_delete`, `p_view`)
VALUES
	(1,2,0,0,0,1),
	(2,2,0,0,0,1),
	(3,2,1,0,0,1),
	(4,2,0,0,0,0),
	(5,2,0,0,0,0),
	(6,2,0,0,0,1);

/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table settings
# ------------------------------------------------------------

DROP TABLE IF EXISTS `settings`;

CREATE TABLE `settings` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `setting_type` varchar(512) NOT NULL DEFAULT '',
  `name` varchar(256) DEFAULT NULL,
  `input_type` enum('input','textarea','radio','dropdown','timezones','file','languages','currencies','email','email_templates','taxes','files') NOT NULL,
  `options` text COMMENT 'Use for radio and dropdown: key|value on each line',
  `is_numeric` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'forces numeric keypad on mobile devices',
  `show_editor` enum('0','1') NOT NULL DEFAULT '0',
  `input_size` enum('large','medium','small') DEFAULT NULL,
  `translate` enum('0','1') NOT NULL DEFAULT '0',
  `help_text` varchar(256) DEFAULT NULL,
  `validation` varchar(256) DEFAULT '',
  `sort_order` smallint(5) unsigned DEFAULT NULL,
  `label` varchar(256) DEFAULT NULL,
  `value` text COMMENT 'If translate is 1, just start with your default language',
  `last_update` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;

INSERT INTO `settings` (`id`, `setting_type`, `name`, `input_type`, `options`, `is_numeric`, `show_editor`, `input_size`, `translate`, `help_text`, `validation`, `sort_order`, `label`, `value`, `last_update`)
VALUES
	(1,'GENERAL','site_logo','file',NULL,'0','0','large','0','Upload atleast 300x200 size logo, or square dimension logo e.g 512x512 for better visibility','trim',10,'Site Logo','logo.png',NULL),
	(2,'GENERAL','site_name','input',NULL,'0','0','medium','0','Enter site name','required|trim|min_length[2]|max_length[64]',20,'Site Name','Anofie','2019-09-28 10:01:55'),
	(3,'GENERAL','site_title','input',NULL,'0','0','large','0','Enter title for welcome page','trim|required|max_length[256]',30,'Site Title','Anofie - anonymous feedback system Remastered','2019-09-28 10:01:55'),
	(4,'GENERAL','site_welcome','textarea',NULL,'0','0','large','0','Enter sub-title for welcome page','trim|required',40,'Site Sub-title','Create a platform for Anonymous Feedback. Empower your community to share the truth for once.','2019-09-28 10:01:55'),
	(5,'SEO','meta_title','input',NULL,'0','0','large','0','Enter meta title','trim|min_length[3]|max_length[128]|required',10,'Meta Title','Welcome to Anofie','2019-09-28 10:02:47'),
	(6,'SEO','meta_tags','input',NULL,'0','0','large','0','Comma-seperated list of meta tags','trim|min_length[2]|max_length[256]',20,'Meta Tags','anofie, anonymous, feedback, messaging, social, network, personal, questions, answers','2019-09-28 10:02:47'),
	(7,'SEO','meta_description','textarea',NULL,'0','0','large','0','Short description about your site.','trim',30,'Meta Description','Receive honest feedbacks and specific question\'s answers from your co-workers, friends and people around you.','2019-09-28 10:02:47'),
	(8,'SOCIAL','social_facebook','input',NULL,'0','0','large','0','Facebook page username','trim',10,'Facebook URL','classiebitsoftwares','2019-09-28 09:11:18'),
	(9,'SOCIAL','social_instagram','input',NULL,'0','0','large','0','Instagram account URL','trim',20,'Instagram URL','https://www.instagram.com/classiebitofficial/','2019-09-28 09:11:18'),
	(10,'SOCIAL','social_twitter','input',NULL,'0','0','large','0','Twitter account username','trim',30,'Twitter URL','classiebit','2019-09-28 09:11:18'),
	(11,'SOCIAL','social_linkedin','input',NULL,'0','0','large','0','Linkedin page URL','trim',40,'Linkedin URL','https://www.linkedin.com/company/classiebit/','2019-09-28 09:11:18'),
	(14,'MAIL','email_lib','dropdown','0|Codeigniter Email\n1|PHPMailer Email','0','0','medium','0',NULL,'trim',10,'Email Library','1','2019-09-28 09:59:15'),
	(15,'MAIL','sender_name','input',NULL,'0','0','medium','0',NULL,'trim',20,'Sender Name','Anofie','2019-09-28 09:59:15'),
	(16,'MAIL','sender_email','email',NULL,'0','0','medium','0',NULL,'trim|valid_email',30,'Sender Email','admin@noreply.anofie.com','2019-09-28 09:59:15'),
	(17,'MAIL','reply_to','dropdown','0|DISABLE\n1|ENABLE','0','0','medium','0',NULL,'trim',40,'Reply To (also receive all email as BCC)','1','2019-09-28 09:59:15'),
	(18,'MAIL','smtp_server','input',NULL,'0','0','medium','0',NULL,'trim',50,'SMTP Server','','2019-09-28 09:59:15'),
	(19,'MAIL','smtp_username','input',NULL,'0','0','medium','0',NULL,'trim',60,'SMTP Username','','2019-09-28 09:59:15'),
	(20,'MAIL','smtp_password','input',NULL,'0','0','medium','0',NULL,'trim',70,'SMTP Password','','2019-09-28 09:59:15'),
	(21,'MAIL','smtp_port','input',NULL,'0','0','medium','0',NULL,'trim',80,'SMTP Port','','2019-09-28 09:59:15'),
	(22,'MAIL','encryption','dropdown','0|DISABLE\nssl|SSL\ntls|TLS','0','0','medium','0',NULL,'trim',90,'Email Encryption','0','2019-09-28 09:59:15'),
	(23,'APPS','g_analytic','input',NULL,'0','0','large','0','Can be obtained from <a href=\"https://analytics.google.com/analytics/web/\" target=\"_blank\">https://analytics.google.com/analytics/web/</a>','trim',10,'Google Analytic Tracking ID','UA-148180654-1','2019-09-28 09:30:36'),
	(32,'REGIONAL','default_language','languages',NULL,'0','0','large','0',NULL,'required|trim',10,'Default Language','english',NULL),
	(33,'REGIONAL','timezones','timezones',NULL,'0','0','large','0',NULL,'required|trim',20,'Default Timezone','UTC',NULL);

/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) DEFAULT '',
  `fullname` varchar(256) DEFAULT NULL,
  `username` varchar(254) DEFAULT NULL,
  `password` varchar(255) DEFAULT '',
  `email` varchar(254) NOT NULL,
  `activation_selector` varchar(255) DEFAULT NULL,
  `activation_code` varchar(255) DEFAULT NULL,
  `forgotten_password_selector` varchar(255) DEFAULT NULL,
  `forgotten_password_code` varchar(255) DEFAULT NULL,
  `forgotten_password_time` int(11) unsigned DEFAULT NULL,
  `remember_selector` varchar(255) DEFAULT NULL,
  `remember_code` varchar(255) DEFAULT NULL,
  `created_on` int(11) unsigned DEFAULT NULL,
  `last_login` int(11) unsigned DEFAULT NULL,
  `active` tinyint(1) unsigned DEFAULT '0',
  `is_deleted` tinyint(1) DEFAULT '0',
  `image` varchar(256) DEFAULT NULL,
  `notifications` tinyint(4) DEFAULT '1',
  `date_added` timestamp NULL DEFAULT NULL,
  `date_updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uc_email` (`email`),
  UNIQUE KEY `uc_activation_selector` (`activation_selector`),
  UNIQUE KEY `uc_forgotten_password_selector` (`forgotten_password_selector`),
  UNIQUE KEY `uc_remember_selector` (`remember_selector`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;

INSERT INTO `users` (`id`, `ip_address`, `fullname`, `username`, `password`, `email`, `activation_selector`, `activation_code`, `forgotten_password_selector`, `forgotten_password_code`, `forgotten_password_time`, `remember_selector`, `remember_code`, `created_on`, `last_login`, `active`, `is_deleted`, `image`, `notifications`, `date_added`, `date_updated`)
VALUES
	(1,'127.0.0.1','Admin','administrator','$2y$12$n7qUZ6mNXR7u1F0fefL5Ue3EdmX/VG98oSebmQulxcUMnHBSSiJPG','admin@admin.com',NULL,'',NULL,NULL,NULL,NULL,NULL,1268889823,1569661611,1,0,NULL,1,'2019-07-03 18:44:52','2019-07-03 18:44:52'),
	(2,'127.0.0.1','جوش رمادي','joshgrey','$2y$10$efyVFq.FzoRxENaqs7rZLOqkpBEkCAu08TrD5lhhtR/euhVnDup6.','joshgrey@mail.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,1562241546,1562318046,1,0,'1562242791242.jpg',1,NULL,'2019-07-04 13:19:51'),
	(3,'127.0.0.1','Rōmən pān','romanpane','$2y$10$QX/HXEp5thyCxl5cZcHiTOU1G6eXcSwKTfQRFim8mmVgmxDorqsYO','romanpane@mail.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,1562241588,1562316379,1,0,'1562242814680.jpg',1,NULL,'2019-07-04 13:20:14'),
	(4,'127.0.0.1','David Lane','davidlane','$2y$10$9WxhG28ExKN.5VMi.uuQMeVt/OrjTC4eX6Nfw21VuoXdrgE3oGfPi','davidlane@mail.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,1562241672,1562316995,1,0,'1562242829312.jpg',1,NULL,'2019-07-04 13:20:29'),
	(5,'127.0.0.1','托尼 鳐','tonyray','$2y$10$TCoQ18NAnxSF4X2ezDY1nefMeXkSe4pZqZ1XOmkeWnlSw/32ZcV0C','tonyray@mail.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,1562241805,1562321597,1,0,'1562242846794.jpg',1,NULL,'2019-07-04 13:20:46'),
	(6,'127.0.0.1','アレックス ハート','alexhart','$2y$10$IUHNDcO48Yw.nAqWs.59LeHSl.DvD8NZGJ3ehGSWwNwBxodQZasPq','alexhart@mail.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,1562241843,1562323042,1,0,'1562242856834.jpg',1,NULL,'2019-07-04 13:20:56'),
	(7,'127.0.0.1','ソニア マクスウェル','soniamaxwell','$2y$10$W6vObFMgXn8y4Qjw25HxfeOgUutIB3vOkf6wx52EeuUk4d.Jmkw1.','soniamaxwell@mail.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,1562243017,1562323027,1,0,'1562243901935.jpg',1,'2019-07-04 13:23:37','2019-07-04 13:38:21'),
	(8,'127.0.0.1','جيناأبيض','ginawhite','$2y$10$XQWI./yLEysMNY3OoELSaOk2e7b3KyCW4FKcDX20WkCqPqxWNRYRi','ginawhite@mail.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,1562243062,1562318069,1,0,'1562243911882.jpg',1,'2019-07-04 13:24:22','2019-07-04 13:38:31'),
	(9,'127.0.0.1','Tara yəNG','tarayoung','$2y$10$FdqZCwGi.4lDPSgod/f1PetqrTRGkiiq45pa6yldvb/TMkmE1oyla','tarayoung@mail.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,1562243105,1562315868,1,0,'1562243923841.jpg',1,'2019-07-04 13:25:05','2019-07-04 13:38:43'),
	(10,'127.0.0.1','Cora Woods','corawoods','$2y$10$F8eGnc96DD/D.sIMiTcEY.cszhnSclgcMrSGsZffAR6syHEYrM5KO','corawoods@mail.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,1562243172,1562317010,1,0,'1562243933940.jpg',1,'2019-07-04 13:26:12','2019-07-04 13:38:54'),
	(11,'127.0.0.1','凯蒂 石头','katiestone','$2y$10$lRpYFgqOOocG/QO4u/AOO.4U6ztBf5Gynrectg6ygp3OcoGaxwbri','katiestone@mail.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,1562243223,1562321584,1,0,'1562243940464.jpg',1,'2019-07-04 13:27:03','2019-07-04 13:39:00'),
	(12,'127.0.0.1','Semi Admin','semiadmin','$2y$10$efyVFq.FzoRxENaqs7rZLOqkpBEkCAu08TrD5lhhtR/euhVnDup6.','semiadmin@mail.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,1562248189,NULL,1,0,'156224818991.png',1,'2019-07-04 14:49:49','2019-07-04 15:18:46');

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table users_groups
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users_groups`;

CREATE TABLE `users_groups` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uc_users_groups` (`user_id`,`group_id`),
  KEY `fk_users_groups_users1_idx` (`user_id`),
  KEY `fk_users_groups_groups1_idx` (`group_id`),
  CONSTRAINT `fk_users_groups_groups1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_users_groups_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;

LOCK TABLES `users_groups` WRITE;
/*!40000 ALTER TABLE `users_groups` DISABLE KEYS */;

INSERT INTO `users_groups` (`id`, `user_id`, `group_id`)
VALUES
	(1,1,1),
	(12,2,3),
	(13,3,3),
	(14,4,3),
	(15,5,3),
	(16,6,3),
	(23,7,3),
	(24,8,3),
	(25,9,3),
	(26,10,3),
	(27,11,3),
	(31,12,2);

/*!40000 ALTER TABLE `users_groups` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
