/*
MySQL Data Transfer
Source Host: 192.168.1.1
Source Database: bam
Target Host: 192.168.1.1
Target Database: bam
Date: 11.01.2012 16:40:45
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for ausers
-- ----------------------------
CREATE TABLE `ausers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(255) NOT NULL DEFAULT '',
  `pass` char(255) NOT NULL DEFAULT '',
  `access` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

-- ----------------------------
-- Table structure for basemenu
-- ----------------------------
CREATE TABLE `basemenu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menuname` char(255) NOT NULL DEFAULT '',
  `rus` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `menuname` (`menuname`,`rus`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for basetable
-- ----------------------------
CREATE TABLE `basetable` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tablename` char(55) NOT NULL DEFAULT '',
  `proptable` char(55) NOT NULL DEFAULT '',
  `rus` char(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `tablename` (`tablename`,`proptable`,`rus`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for config
-- ----------------------------
CREATE TABLE `config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `key` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for defmenu
-- ----------------------------
CREATE TABLE `defmenu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `rus` varchar(255) NOT NULL DEFAULT '',
  `sub` int(11) NOT NULL DEFAULT '0',
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  `link` varchar(255) DEFAULT NULL,
  `lview` varchar(255) DEFAULT NULL,
  `lmain` varchar(255) DEFAULT NULL,
  `ltable` varchar(255) DEFAULT NULL,
  `lrecord` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `keywords` text,
  `description` text,
  `order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for fieldtypes
-- ----------------------------
CREATE TABLE `fieldtypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `typename` char(255) NOT NULL DEFAULT '',
  `realtype` char(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for pages
-- ----------------------------
CREATE TABLE `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order` int(11) NOT NULL DEFAULT '0',
  `linkid` int(11) NOT NULL DEFAULT '0',
  `nameid` varchar(100) DEFAULT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for pages_defsec
-- ----------------------------
CREATE TABLE `pages_defsec` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `num` text NOT NULL,
  `query` varchar(200) NOT NULL DEFAULT '',
  `vname` varchar(30) NOT NULL DEFAULT '',
  `vucms` varchar(200) NOT NULL DEFAULT '',
  `vsite` varchar(200) NOT NULL DEFAULT '',
  `vtype` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for pages_prop
-- ----------------------------
CREATE TABLE `pages_prop` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fname` char(32) NOT NULL DEFAULT '',
  `ftype` int(11) NOT NULL DEFAULT '0',
  `rus` char(32) NOT NULL DEFAULT '',
  `properties` char(200) DEFAULT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `fname` (`fname`),
  KEY `ftype` (`ftype`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

-- ----------------------------
-- Records 
-- ----------------------------
INSERT INTO `ausers` VALUES ('1', 'admin', '36f17c3939ac3e7b2fc9396fa8e953ea', '1');
INSERT INTO `ausers` VALUES ('2', 'herurg', '455e5915e098cea14dea6295366899a9', '1');
INSERT INTO `basemenu` VALUES ('1', 'defmenu', 'Меню');
INSERT INTO `basetable` VALUES ('1', 'pages', 'pages_prop', 'pages');
INSERT INTO `config` VALUES ('1', 'E-mail для обратной связи', 'email_admin', 'rabotayes@mail.ru');
INSERT INTO `config` VALUES ('2', 'Постраничный переход для админки', 'admin_pager', '20');
INSERT INTO `fieldtypes` VALUES ('1', 'Системная строка', 'INT(11)');
INSERT INTO `fieldtypes` VALUES ('2', 'Строка', 'VARCHAR(255)');
INSERT INTO `fieldtypes` VALUES ('3', 'Почта', 'VARCHAR(255)');
INSERT INTO `fieldtypes` VALUES ('4', 'Целое число', 'INT(11)');
INSERT INTO `fieldtypes` VALUES ('5', 'Дата', 'DATE');
INSERT INTO `fieldtypes` VALUES ('6', 'Время', 'TIME');
INSERT INTO `fieldtypes` VALUES ('7', 'Текст', 'TEXT');
INSERT INTO `fieldtypes` VALUES ('8', 'Файл', 'VARCHAR(255)');
INSERT INTO `fieldtypes` VALUES ('9', 'Дата-время', 'DATETIME');
INSERT INTO `fieldtypes` VALUES ('10', 'Дробное число', 'DOUBLE');
INSERT INTO `fieldtypes` VALUES ('11', 'Список', 'SET');
INSERT INTO `fieldtypes` VALUES ('12', 'Логический', 'TINYINT(1)');
INSERT INTO `fieldtypes` VALUES ('13', 'Столбец', 'INT(11)');
INSERT INTO `fieldtypes` VALUES ('14', 'Столбец - множественный', 'VARCHAR(255)');
INSERT INTO `pages_defsec` VALUES ('1', 'nothing', 'SELECT * FROM `pages` ORDER BY `order`', '_short', 'id,visible,nameid,linkid', 'nameid', '1');
INSERT INTO `pages_prop` VALUES ('1', 'id', '1', 'Системное поле', '', '1');
INSERT INTO `pages_prop` VALUES ('2', 'order', '4', 'Порядок', '', '1');
INSERT INTO `pages_prop` VALUES ('3', 'linkid', '4', 'Ссылка', '', '0');
INSERT INTO `pages_prop` VALUES ('4', 'nameid', '2', 'Название', '', '1');
INSERT INTO `pages_prop` VALUES ('5', 'visible', '12', 'Отображать', '', '0');
