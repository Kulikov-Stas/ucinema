-- phpMyAdmin SQL Dump
-- version 3.1.1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Дек 19 2010 г., 20:52
-- Версия сервера: 5.0.67
-- Версия PHP: 5.2.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- База данных: `clean`
--

-- --------------------------------------------------------

--
-- Структура таблицы `ausers`
--

CREATE TABLE IF NOT EXISTS `ausers` (
  `id` int(11) NOT NULL auto_increment,
  `name` char(255) NOT NULL default '',
  `pass` char(255) NOT NULL default '',
  `access` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `ausers`
--

INSERT INTO `ausers` (`id`, `name`, `pass`, `access`) VALUES
(1, 'admin', '36f17c3939ac3e7b2fc9396fa8e953ea', 1),
(2, 'herurg', '455e5915e098cea14dea6295366899a9', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `basemenu`
--

CREATE TABLE IF NOT EXISTS `basemenu` (
  `id` int(11) NOT NULL auto_increment,
  `menuname` char(255) NOT NULL default '',
  `rus` char(32) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `menuname` (`menuname`,`rus`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `basemenu`
--

INSERT INTO `basemenu` (`id`, `menuname`, `rus`) VALUES
(1, 'defmenu', 'Меню');


-- --------------------------------------------------------

--
-- Структура таблицы `basetable`
--

CREATE TABLE IF NOT EXISTS `basetable` (
  `id` int(11) NOT NULL auto_increment,
  `tablename` char(55) NOT NULL default '',
  `proptable` char(55) NOT NULL default '',
  `rus` char(200) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `tablename` (`tablename`,`proptable`,`rus`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `basetable`
--

INSERT INTO `basetable` (`id`, `tablename`, `proptable`, `rus`) VALUES
(1, 'pages', 'pages_prop', 'pages');

-- --------------------------------------------------------

--
-- Структура таблицы `config`
--

CREATE TABLE IF NOT EXISTS `config` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  `key` varchar(100) NOT NULL default '',
  `value` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `config`
--


-- --------------------------------------------------------

--
-- Структура таблицы `defmenu`
--

CREATE TABLE IF NOT EXISTS `defmenu` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `rus` varchar(255) NOT NULL default '',
  `sub` int(11) NOT NULL default '0',
  `visible` tinyint(1) NOT NULL default '1',
  `link` varchar(255) default NULL,
  `lview` varchar(255) default NULL,
  `lmain` varchar(255) default NULL,
  `ltable` varchar(255) default NULL,
  `lrecord` varchar(255) default NULL,
  `title` varchar(255) default NULL,
  `keywords` text,
  `description` text,
  `order` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Структура таблицы `fieldtypes`
--

CREATE TABLE IF NOT EXISTS `fieldtypes` (
  `id` int(11) NOT NULL auto_increment,
  `typename` char(255) NOT NULL default '',
  `realtype` char(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Дамп данных таблицы `fieldtypes`
--

INSERT INTO `fieldtypes` (`id`, `typename`, `realtype`) VALUES
(1, 'Системная строка', 'INT(11)'),
(2, 'Строка', 'VARCHAR(255)'),
(3, 'Почта', 'VARCHAR(255)'),
(4, 'Целое число', 'INT(11)'),
(5, 'Дата', 'DATE'),
(6, 'Время', 'TIME'),
(7, 'Текст', 'TEXT'),
(8, 'Файл', 'VARCHAR(255)'),
(9, 'Дата-время', 'DATETIME'),
(10, 'Дробное число', 'DOUBLE'),
(11, 'Список', 'SET'),
(12, 'Логический', 'TINYINT(1)'),
(13, 'Столбец', 'INT(11)'),
(14, 'Столбец - множественный', 'VARCHAR(255)');

-- --------------------------------------------------------

--
-- Структура таблицы `pages`
--

CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(11) NOT NULL auto_increment,
  `order` int(11) NOT NULL default '0',
  `linkid` int(11) NOT NULL default '0',
  `nameid` varchar(100) default NULL,
  `visible` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `pages_defsec`
--

CREATE TABLE IF NOT EXISTS `pages_defsec` (
  `id` int(11) NOT NULL auto_increment,
  `num` text NOT NULL,
  `query` varchar(200) NOT NULL default '',
  `vname` varchar(30) NOT NULL default '', 
  `vucms` varchar(200) NOT NULL default '',
  `vsite` varchar(200) NOT NULL default '',
  `vtype` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `pages_defsec`
--

INSERT INTO `pages_defsec` (`id`, `num`, `query`, `vname`, `vucms`, `vsite`, `vtype`) VALUES
(1, 'nothing', 'SELECT * FROM `pages` ORDER BY `order`', '_short', 'id,visible,nameid,linkid', 'nameid', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `pages_prop`
--

CREATE TABLE IF NOT EXISTS `pages_prop` (
  `id` int(11) NOT NULL auto_increment,
  `fname` char(32) NOT NULL default '',
  `ftype` int(11) NOT NULL default '0',
  `rus` char(32) NOT NULL default '',
  `properties` char(200) default NULL,
  `visible` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `fname` (`fname`),
  KEY `ftype` (`ftype`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED AUTO_INCREMENT=6 ;

--
-- Дамп данных таблицы `pages_prop`
--

INSERT INTO `pages_prop` (`id`, `fname`, `ftype`, `rus`, `properties`, `visible`) VALUES
(1, 'id', 1, 'Системное поле', '', 1),
(2, 'order', 4, 'Порядок', '', 1),
(3, 'linkid', 4, 'Ссылка', '', 0),
(4, 'nameid', 2, 'Название', '', 1),
(5, 'visible', 12, 'Отображать', '', 0);
