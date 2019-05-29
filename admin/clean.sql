-- phpMyAdmin SQL Dump
-- version 3.1.1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Дек 01 2010 г., 21:14
-- Версия сервера: 5.0.67
-- Версия PHP: 5.2.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

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
-- Структура таблицы `banner`
--

CREATE TABLE IF NOT EXISTS `banner` (
  `id` int(11) NOT NULL auto_increment,
  `order` int(11) NOT NULL default '0',
  `linkid` int(11) NOT NULL default '0',
  `nameid` char(100) default NULL,
  `visible` tinyint(1) NOT NULL default '1',
  `path` varchar(255) default NULL,
  `date` date default NULL,
  `radio` tinyint(1) default NULL,
  `st1` int(11) default NULL,
  `st2` varchar(255) default NULL,
  `set` set('red','yellow','blue') default NULL,
  `text` text,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `linkid` (`linkid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED AUTO_INCREMENT=7 ;

--
-- Дамп данных таблицы `banner`
--

INSERT INTO `banner` (`id`, `order`, `linkid`, `nameid`, `visible`, `path`, `date`, `radio`, `st1`, `st2`, `set`, `text`) VALUES
(1, 1, 1, 'баннер1', 1, '', '0000-00-00', 0, 1, '', '', ''),
(2, 2, 2, 'баннер2', 1, NULL, '1970-01-01', 1, 2, '3*4*6', 'red', '\r\n\r\nasas\r\n'),
(3, 3, 3, 'баннер3', 1, NULL, '2010-10-15', 1, 7, '3*4*6', 'blue', '<p>xdfgsdfg ds</p>'),
(4, 4, 4, 'баннер4', 1, NULL, '2010-10-14', 1, 2, '3', 'red', '<p>as ssa</p>\r\n<p>as</p>\r\n<p>as</p>'),
(5, 5, 5, 'баннер52', 1, '/siteimg/banner/favicon.jpg', '2010-10-13', 0, 2, '2', 'red', '<p>sdfg as</p>'),
(6, 6, 6, 'баннер6', 1, '&lt;img border=''0'' alt='''' src=''/siteimg/foto_Impressum.jpg''&gt;', '1970-01-01', 1, 2, NULL, 'red', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `banner_defsec`
--

CREATE TABLE IF NOT EXISTS `banner_defsec` (
  `id` int(11) NOT NULL auto_increment,
  `num` text character set cp1251 NOT NULL,
  `query` varchar(200) character set cp1251 NOT NULL default '',
  `vname` varchar(30) character set cp1251 NOT NULL default '',
  `vucms` varchar(200) character set cp1251 NOT NULL default '',
  `vsite` varchar(200) character set cp1251 NOT NULL default '',
  `vtype` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `banner_defsec`
--

INSERT INTO `banner_defsec` (`id`, `num`, `query`, `vname`, `vucms`, `vsite`, `vtype`) VALUES
(1, 'a:1:{i:0;i:0;}', 'select * from `banner`', '_short', 'id,visible,nameid,linkid', 'nameid', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `banner_prop`
--

CREATE TABLE IF NOT EXISTS `banner_prop` (
  `id` int(11) NOT NULL auto_increment,
  `fname` char(32) NOT NULL default '',
  `ftype` int(11) NOT NULL default '0',
  `rus` char(32) NOT NULL default '',
  `properties` char(200) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `fname` (`fname`),
  KEY `ftype` (`ftype`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED AUTO_INCREMENT=13 ;

--
-- Дамп данных таблицы `banner_prop`
--

INSERT INTO `banner_prop` (`id`, `fname`, `ftype`, `rus`, `properties`) VALUES
(1, 'id', 1, 'Системное поле', NULL),
(2, 'order', 4, 'Порядок', NULL),
(3, 'linkid', 4, 'Ссылка', NULL),
(4, 'nameid', 2, 'Название', NULL),
(5, 'visible', 12, 'Отображать', NULL),
(6, 'path', 8, 'Картинка', ''),
(7, 'date', 5, 'date', ''),
(8, 'radio', 12, 'radio', ''),
(9, 'st1', 13, 'st1', 'pages&nameid'),
(10, 'st2', 14, 'st2', 'pages&nameid'),
(11, 'set', 11, 'set', 'red&yellow&blue'),
(12, 'text', 7, 'text', '');

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `basemenu`
--

INSERT INTO `basemenu` (`id`, `menuname`, `rus`) VALUES
(1, 'defmenu', 'Меню'),
(2, 'left_menu', 'Левое меню');

-- --------------------------------------------------------

--
-- Структура таблицы `baseproperties`
--

CREATE TABLE IF NOT EXISTS `baseproperties` (
  `id` int(11) NOT NULL auto_increment,
  `name` char(255) NOT NULL default '',
  `value` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `baseproperties`
--

INSERT INTO `baseproperties` (`id`, `name`, `value`) VALUES
(1, 'confdelfield', 0);

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `basetable`
--

INSERT INTO `basetable` (`id`, `tablename`, `proptable`, `rus`) VALUES
(1, 'banner', 'banner_prop', 'Баннер'),
(2, 'pages', 'pages_prop', 'pages'),
(3, 'gallery', 'gallery_prop', 'Галерея');

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Дамп данных таблицы `defmenu`
--

INSERT INTO `defmenu` (`id`, `name`, `rus`, `sub`, `visible`, `link`, `lview`, `lmain`, `ltable`, `lrecord`, `title`, `keywords`, `description`, `order`) VALUES
(1, 'qq', 'qq', 0, 1, 'qq/', 'first.html', 'main.html', 'pages', 'sadf', 'qq', 'sadf', 'sadf', 1),
(2, 'wewe', 'wewe', 0, 0, 'wewe/', 'short.html', 'main.html', 'banner', '', 'wewe', '', '', 2),
(3, 'fghfgh', 'fghfgh', 0, 0, 'fghfgh/', 'short.html', 'main.html', 'banner', '', 'fghfgh', '', '', 3),
(4, 'fghfgh', 'fghfgh', 0, 0, 'fghfgh/', 'short.html', 'main.html', 'banner', '', 'fghfgh', '', '', 4),
(5, 'cxn', 'nvbvvvvvvvvvvv', 0, 1, '/cxn/', 'short.html', 'main.html', 'banner', '', 'vm', '', '', 5),
(6, 'dsfgdsfg', 'dfsgds', 0, 1, '/dsfgdsfg/', 'short.html', 'main.html', 'banner', '', 'dsfgdsg', '', '', 6);

-- --------------------------------------------------------

--
-- Структура таблицы `fieldtypes`
--

CREATE TABLE IF NOT EXISTS `fieldtypes` (
  `id` int(11) NOT NULL auto_increment,
  `typename` char(255) NOT NULL default '',
  `realtype` char(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `typename` (`typename`)
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
(8, 'Картинка', 'VARCHAR(255)'),
(9, 'Дата-время', 'DATETIME'),
(10, 'Дробное число', 'DOUBLE'),
(11, 'Список', 'SET'),
(12, 'Логический', 'TINYINT(1)'),
(13, 'Столбец', 'INT(11)'),
(14, 'Столбец - множественный', 'VARCHAR(255)');

-- --------------------------------------------------------

--
-- Структура таблицы `gallery`
--

CREATE TABLE IF NOT EXISTS `gallery` (
  `id` int(11) NOT NULL auto_increment,
  `order` int(11) NOT NULL,
  `linkid` int(11) NOT NULL,
  `nameid` varchar(100) default NULL,
  `visible` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `linkid` (`linkid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `gallery`
--


-- --------------------------------------------------------

--
-- Структура таблицы `gallery_defsec`
--

CREATE TABLE IF NOT EXISTS `gallery_defsec` (
  `id` int(11) NOT NULL auto_increment,
  `num` text,
  `query` varchar(255) NOT NULL,
  `vname` varchar(100) NOT NULL,
  `vucms` varchar(100) NOT NULL,
  `vsite` varchar(200) NOT NULL,
  `vtype` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `gallery_defsec`
--

INSERT INTO `gallery_defsec` (`id`, `num`, `query`, `vname`, `vucms`, `vsite`, `vtype`) VALUES
(1, 'nothing', 'select * from `gallery`', '_short', 'id,visible,nameid,linkid', 'nameid', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `gallery_prop`
--

CREATE TABLE IF NOT EXISTS `gallery_prop` (
  `id` int(11) NOT NULL auto_increment,
  `fname` varchar(100) NOT NULL,
  `ftype` int(11) NOT NULL,
  `rus` varchar(100) NOT NULL,
  `properties` varchar(100) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Дамп данных таблицы `gallery_prop`
--

INSERT INTO `gallery_prop` (`id`, `fname`, `ftype`, `rus`, `properties`) VALUES
(1, 'id', 1, 'Системное поле', ''),
(2, 'order', 4, 'Порядок', ''),
(3, 'linkid', 4, 'Ссылка', ''),
(4, 'nameid', 2, 'Название', ''),
(5, 'visible', 12, 'Отображать', '');

-- --------------------------------------------------------

--
-- Структура таблицы `left_menu`
--

CREATE TABLE IF NOT EXISTS `left_menu` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `left_menu`
--


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
  `content` text,
  `author` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- Дамп данных таблицы `pages`
--

INSERT INTO `pages` (`id`, `order`, `linkid`, `nameid`, `visible`, `content`, `author`) VALUES
(2, 1, 1, 'О компании', 1, '', ''),
(3, 2, 2, 'История', 1, '&lt;p style=&quot;text-align: justify;&quot;&gt;Консалтинговая компания &amp;laquo;ЕвроМенеджмент&amp;raquo; работает на рынке бизнес-образования Украины с февраля 2000г, проводит открытые и корпоративные бизнес-тренинги.&lt;/p&gt;\r\n&lt;p style=&quot;text-align: justify;&quot;&gt;Офис расположен в городе Одесса.&lt;/p&gt;\r\n&lt;p style=&quot;text-align: justify;&quot;&gt;Все специалисты компании имеют собственный реальный опыт работы на предприятиях&amp;nbsp; торговой, производственной и финансовой отраслях. Практический опыт постановки управленческого учета и бюджетирования, достижения стратегических целей и&amp;nbsp; управления проектами, разработка и внедрение оргструктуры, оптимизации бизнес-процессов, определение миссии и видения компаний.&lt;/p&gt;', ''),
(4, 3, 3, 'Клиенты и партнеры', 1, '', ''),
(5, 5, 4, 'Услуги', 1, '', ''),
(6, 4, 5, 'Консалтинг', 1, '&lt;p style=&quot;text-align: justify;&quot;&gt;&lt;strong&gt;Направление деятельности:&lt;/strong&gt;&lt;br /&gt;- Организационное и индивидуальное консультирование&lt;br /&gt;- Финансово-экономические практикумы&lt;br /&gt;- Построение сбыта товаров и услуг&lt;br /&gt;- Управленческий консалтинг&lt;/p&gt;\r\n&lt;p style=&quot;text-align: justify;&quot;&gt;Наша деятельность в выбранных Вами направлениях сотрудничества позволит Вашей организации:&lt;br /&gt;&amp;bull;&amp;nbsp;определить эффективную стратегию взаимодействия с&amp;nbsp; клиентами;&lt;br /&gt;&amp;bull;&amp;nbsp;организовывать исключительный сервис и высокое качество; &lt;br /&gt;&amp;bull;&amp;nbsp;улучшить межличностные и деловые отношения;&lt;br /&gt;&amp;bull;&amp;nbsp;найти новые интересные решения поставленных задач;&lt;br /&gt;&amp;bull;&amp;nbsp;увеличить долю рынка и прибыль компании!&lt;/p&gt;', ''),
(7, 6, 6, 'Тренинги', 1, '&lt;p style=&quot;text-align: justify;&quot;&gt;Компания &quot;ЕвроМенеджмент&quot; специализируется на обучении линейки ТОП-менеджмента, руководителей подразделений и специалистов компаний в формате открытых и корпоративных тренингов.&lt;/p&gt;\r\n&lt;p style=&quot;text-align: justify;&quot;&gt;&lt;strong&gt;Открытые бизнес-тренинги&lt;/strong&gt; собирают представителей разных компаний, которые в ходе обучения также имеют возможность ознакомиться и даже позаимствовать опыт других компаний.&lt;/p&gt;\r\n&lt;p style=&quot;text-align: justify;&quot;&gt;&lt;strong&gt;Корпоративные бизнес-тренинги&lt;/strong&gt;&lt;br /&gt;Структурная схема нашей работы при организации корпоративного бизнес-тренинга состоит из трех этапов:&lt;/p&gt;\r\n&lt;p style=&quot;text-align: justify;&quot;&gt;1.&amp;nbsp;Дотренинговая подготовка и диагностика&lt;br /&gt;2.&amp;nbsp;Проведение тренинга&lt;br /&gt;3.&amp;nbsp;Посттренинговое обслуживание&lt;/p&gt;\r\n&lt;p style=&quot;text-align: justify;&quot;&gt;&lt;span style=&quot;text-decoration: underline;&quot;&gt;На этапе дотренинговой подготовки&lt;/span&gt; Вы выбираете область исследования, в рамках деятельности Вашей компании, которое будет исследоваться нашим специалистом. Будут проведены необходимые &lt;em&gt;мероприятия по диагностике&lt;/em&gt; и составлен отчёт о положении дел данного направления. На основании проведённого анализа мы предлагаем программу сотрудничества и определяем тему тренинга.&lt;br /&gt;На втором этапе &lt;span style=&quot;text-decoration: underline;&quot;&gt;составляется программа тренинга&lt;/span&gt; так, что бы учесть все выявленные потребности, а также включаются поставленные Вами цели и задачи. Утверждается программа тренинга, количество участников, время и место проведения.&lt;br /&gt;После проведения тренинга мы &lt;span style=&quot;text-decoration: underline;&quot;&gt;предлагаем программу посттренингового обслуживания&lt;/span&gt;, которая сможет включать контрольную диагностику, консультационное обслуживание и различные мероприятия последующего сопровождения.&lt;br /&gt;Объем каждого этапа работы определяется при составлении индивидуальной программы. Стоимость зависит от количества участников, времени проведения, программы бизнес-тренинга и оговаривается на взаимовыгодных условиях.&lt;/p&gt;', ''),
(8, 7, 7, 'Проекты', 1, '', ''),
(9, 8, 8, 'Сервисы-Тренинги', 1, '', ''),
(10, 9, 9, 'Клубный проект Boss club', 1, '&lt;p&gt;&lt;span style=&quot;font-size: small;&quot;&gt;&lt;em&gt;&lt;strong&gt;Миссия:&lt;/strong&gt;&lt;/em&gt; повышение уровня ведения бизнеса в Украине посредством развития личного потенциала каждого из членов клуба.&lt;/span&gt;&lt;br /&gt;&lt;span style=&quot;font-size: small;&quot;&gt;&lt;em&gt;&lt;strong&gt;&lt;/strong&gt;&lt;/em&gt;&lt;/span&gt;&lt;/p&gt;\r\n&lt;p&gt;&lt;span style=&quot;font-size: small;&quot;&gt;&lt;em&gt;&lt;strong&gt;Цель:&lt;/strong&gt;&lt;/em&gt;&amp;nbsp; предоставление всех возможностей и сопровождающих условий для активного обучения членов клуба и обмена опытом с коллегами и экспертами.&lt;/span&gt;&lt;/p&gt;\r\n&lt;p&gt;&lt;span style=&quot;font-size: small;&quot;&gt;&lt;em&gt;&lt;strong&gt;Описание клуба&lt;/strong&gt;&lt;/em&gt;: клуб директоров создан, как площадка для обучения у ведущих тренеров и экспертов Украины и России, получения уникальных знаний и обмена опытом с коллегами.&lt;/span&gt;&lt;/p&gt;\r\n&lt;p&gt;&lt;span style=&quot;font-size: small;&quot;&gt;&lt;em&gt;&lt;strong&gt;Члены клуба:&lt;/strong&gt;&lt;/em&gt; представители ТОП-менеджмента (руководители, директора и первые лица компаний).&lt;/span&gt;&lt;/p&gt;\r\n&lt;p&gt;&lt;span style=&quot;font-size: small;&quot;&gt;&lt;em&gt;&lt;strong&gt;Условия посещения встреч клуба:&lt;/strong&gt;&lt;/em&gt;&amp;nbsp; для посещения встреч клуба Вам необходимо подтвердить статус члена клуба и оплатить разовый годовой членский взнос. &lt;/span&gt;&lt;/p&gt;\r\n&lt;p&gt;&lt;span style=&quot;text-decoration: underline;&quot;&gt;&lt;span style=&quot;font-size: small;&quot;&gt;Членство и посещение встреч клуба будет интересно Вам, если:&lt;/span&gt;&lt;/span&gt;&lt;br /&gt;&lt;span style=&quot;font-size: small;&quot;&gt;*&amp;nbsp;&amp;nbsp;&amp;nbsp; Вы не останавливаетесь на достигнутом и осознаете потребность в непрерывном обучении и познании новых бизнес &amp;ndash; инструментов;&lt;/span&gt;&lt;br /&gt;&lt;span style=&quot;font-size: small;&quot;&gt;*&amp;nbsp;&amp;nbsp;&amp;nbsp; Вы занятой человек, день которого расписан по минутам, при этом Вы готовы выделить время для получения конкретной целевой информации, подготовленной специально под Ваши потребности;&lt;/span&gt;&lt;br /&gt;&lt;span style=&quot;font-size: small;&quot;&gt;*&amp;nbsp;&amp;nbsp;&amp;nbsp; Вам интересно получение альтернативной информации, которая выходит за рамки Вашей текущей деятельности, при этом сможет помочь Вам в решении не только повседневных, а и ключевых бизнес-вопросов;&lt;/span&gt;&lt;br /&gt;&lt;span style=&quot;font-size: small;&quot;&gt;*&amp;nbsp;&amp;nbsp;&amp;nbsp; Вы знаете цену интеллектуального общения в кругу достойных собеседников.&lt;/span&gt;&lt;/p&gt;\r\n&lt;p&gt;&lt;strong&gt;&lt;span style=&quot;font-size: small;&quot;&gt;Преимущества статуса члена клуба:&lt;/span&gt;&lt;/strong&gt;&lt;br /&gt;&lt;span style=&quot;font-size: small;&quot;&gt;- посещение всех встреч, которые проходят в рамках клуба, получение всех раздаточных материалов (в т.ч. и видеоматериалов со встреч, если таковые будут);&lt;/span&gt;&lt;br /&gt;&lt;span style=&quot;font-size: small;&quot;&gt;- свободный доступ к корпоративной библиотеке, которая содержит различные обучающие материалы (книги, журналы, видеофрагменты тренингов и т.д.);&lt;/span&gt;&lt;br /&gt;&lt;span style=&quot;font-size: small;&quot;&gt;- гарантированное место на каждой встрече клуба;&lt;/span&gt;&lt;br /&gt;&lt;span style=&quot;font-size: small;&quot;&gt;- 15% скидка на тренинги, которые проводятся компанией Евроменеджмент&lt;/span&gt;.&lt;br /&gt;&lt;br /&gt;&lt;strong&gt;&lt;span style=&quot;font-size: small;&quot;&gt;Если член клуба пропускает встречу:&lt;/span&gt;&lt;/strong&gt;&lt;br /&gt;&lt;span style=&quot;font-size: small;&quot;&gt;Если член клуба по уважительным причинам или рабочим обстоятельствам пропустил встречу клуба, он имеет право получить видеозапись или видеофрагменты встречи, если таковые будут иметься после встречи. &lt;/span&gt;&lt;br /&gt;&lt;span style=&quot;font-size: small;&quot;&gt;Также, при согласовании с координатором клуба и по необходимости член клуба может вместо себя зарегистрировать на встречу кого-либо из своих заместителей или представителей своей компании.&lt;/span&gt;&lt;br /&gt;&lt;br /&gt;&lt;strong&gt;&lt;span style=&quot;font-size: small;&quot;&gt;О программе встреч:&lt;/span&gt;&lt;/strong&gt;&lt;br /&gt;&lt;span style=&quot;font-size: small;&quot;&gt;Тематика встреч определяется исходя из запросов членов клуба. Каждая встреча направлена на то, чтобы собрать аудиторию, для которой в данный момент остро стоят одни и те же вопросы. В течение 3 часов встречи происходит поиск ответов на вопросы, и определяются пути решения.&lt;/span&gt;&lt;br /&gt;&lt;span style=&quot;font-size: small;&quot;&gt;Каждый член клуба может и должен влиять на выбор тематики встреч, чтобы извлекать максимальную пользу от членства в клубе. &lt;/span&gt;&lt;br /&gt;&lt;span style=&quot;font-size: small;&quot;&gt;Мы приглашаем ведущих специалистов из Украины, России и других стран СНГ для того, чтобы дать возможность членам клуба в максимально короткие сроки решить наиболее важные бизнес-вопросы/задачи и повысить эффективность своего бизнеса.&lt;/span&gt;&lt;/p&gt;\r\n&lt;p&gt;&lt;strong&gt;&lt;span style=&quot;font-size: small;&quot;&gt;Тематический план встреч на 2010 г.: &lt;/span&gt;&lt;/strong&gt;&lt;br /&gt;&lt;span style=&quot;font-size: small;&quot;&gt;Лидерство &lt;/span&gt;&lt;br /&gt;&lt;span style=&quot;font-size: small;&quot;&gt;Личная эффективность&lt;/span&gt;&lt;br /&gt;&lt;span style=&quot;font-size: small;&quot;&gt;Управление временем&lt;/span&gt;&lt;br /&gt;&lt;span style=&quot;font-size: small;&quot;&gt;Управление работоспособностью&lt;/span&gt;&lt;br /&gt;&lt;span style=&quot;font-size: small;&quot;&gt;Эффективное планирование&lt;/span&gt;&lt;br /&gt;&lt;span style=&quot;font-size: small;&quot;&gt;Управление эмоциональным состоянием&lt;/span&gt;&lt;br /&gt;&lt;span style=&quot;font-size: small;&quot;&gt;Эмоциональный интеллект&lt;/span&gt;&lt;br /&gt;&lt;span style=&quot;font-size: small;&quot;&gt;Эффективное мышление&lt;/span&gt;&lt;br /&gt;&lt;span style=&quot;font-size: small;&quot;&gt;&amp;nbsp;Развитие креативного мышления&amp;hellip;&lt;/span&gt;&lt;br /&gt;&lt;br /&gt;&lt;em&gt;&lt;span style=&quot;font-size: small;&quot;&gt;Встречи клуба проходят 2 раза в месяц. &lt;/span&gt;&lt;/em&gt;&lt;br /&gt;&lt;em&gt;&lt;span style=&quot;font-size: small;&quot;&gt;Продолжительность встречи: от 2,5-3 часа.&lt;/span&gt;&lt;/em&gt;&lt;/p&gt;\r\n&lt;p&gt;&lt;span style=&quot;font-size: small;&quot;&gt;&lt;strong&gt;Форматы встреч:&lt;/strong&gt; вечерние встречи и бизнес - завтраки&lt;/span&gt;&lt;/p&gt;\r\n&lt;p style=&quot;text-align: center;&quot;&gt;&lt;span style=&quot;font-size: small;&quot;&gt;* Встречи проходят в современных конференц-залах, а также в оригинальных нестандартных местах.&lt;/span&gt;&lt;br /&gt;&lt;br /&gt;&lt;br /&gt;&lt;em&gt;&lt;strong&gt;&lt;span style=&quot;font-size: small;&quot;&gt;В нашем клубе Вас ждут встречи с ведущими экспертами, интеллектуальное общение с коллегами, ценный опыт, приятная атмосфера и яркие эмоции!!!&lt;/span&gt;&lt;/strong&gt;&lt;/em&gt;&lt;/p&gt;', '');

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
(1, 'a:17:{i:0;i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;i:4;i:5;i:5;i:6;i:6;i:7;i:7;i:8;i:8;i:9;i:9;i:10;i:10;i:11;i:11;i:12;i:12;i:13;i:13;i:14;i:14;i:15;i:15;i:16;i:16;}', 'select * from `pages`', '_short', 'id,visible,nameid,linkid', 'nameid', 1);

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
  PRIMARY KEY  (`id`),
  UNIQUE KEY `fname` (`fname`),
  KEY `ftype` (`ftype`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED AUTO_INCREMENT=9 ;

--
-- Дамп данных таблицы `pages_prop`
--

INSERT INTO `pages_prop` (`id`, `fname`, `ftype`, `rus`, `properties`) VALUES
(1, 'id', 1, 'Системное поле', ''),
(2, 'order', 4, 'Порядок', ''),
(3, 'linkid', 4, 'Ссылка', ''),
(4, 'nameid', 2, 'Название', ''),
(5, 'visible', 12, 'Отображать', ''),
(6, 'content', 7, 'Текст', ''),
(7, 'author', 2, 'Автор', ''),
(8, 'www', 1, 'www', '');
