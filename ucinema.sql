/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50140
Source Host           : 192.168.1.1:3306
Source Database       : ucinema

Target Server Type    : MYSQL
Target Server Version : 50140
File Encoding         : 65001

Date: 2013-10-18 17:41:25
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `ausers`
-- ----------------------------
DROP TABLE IF EXISTS `ausers`;
CREATE TABLE `ausers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(255) NOT NULL DEFAULT '',
  `pass` char(255) NOT NULL DEFAULT '',
  `access` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

-- ----------------------------
-- Records of ausers
-- ----------------------------
INSERT INTO `ausers` VALUES ('1', 'admin', '36f17c3939ac3e7b2fc9396fa8e953ea', '1');
INSERT INTO `ausers` VALUES ('2', 'herurg', '455e5915e098cea14dea6295366899a9', '1');

-- ----------------------------
-- Table structure for `basemenu`
-- ----------------------------
DROP TABLE IF EXISTS `basemenu`;
CREATE TABLE `basemenu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menuname` char(255) NOT NULL DEFAULT '',
  `rus` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `menuname` (`menuname`,`rus`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of basemenu
-- ----------------------------
INSERT INTO `basemenu` VALUES ('1', 'defmenu', 'Меню');

-- ----------------------------
-- Table structure for `basetable`
-- ----------------------------
DROP TABLE IF EXISTS `basetable`;
CREATE TABLE `basetable` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tablename` char(55) NOT NULL DEFAULT '',
  `proptable` char(55) NOT NULL DEFAULT '',
  `rus` char(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `tablename` (`tablename`,`proptable`,`rus`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of basetable
-- ----------------------------
INSERT INTO `basetable` VALUES ('1', 'pages', 'pages_prop', 'pages');
INSERT INTO `basetable` VALUES ('10', 'film', 'film_prop', 'Фильмы');
INSERT INTO `basetable` VALUES ('7', 'shedule', 'shedule_prop', 'Расписание');

-- ----------------------------
-- Table structure for `config`
-- ----------------------------
DROP TABLE IF EXISTS `config`;
CREATE TABLE `config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `key` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of config
-- ----------------------------
INSERT INTO `config` VALUES ('1', 'Постраничный переход ', 'admin_pager', '1000');
INSERT INTO `config` VALUES ('2', 'Email', 'email_admin', 'stasus2002@ukr.net');
INSERT INTO `config` VALUES ('3', 'Тип текстового редактора', 'wyzywig', '1');

-- ----------------------------
-- Table structure for `defmenu`
-- ----------------------------
DROP TABLE IF EXISTS `defmenu`;
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
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of defmenu
-- ----------------------------
INSERT INTO `defmenu` VALUES ('1', 'main', 'Афиша', '0', '1', '/', 'short.html', 'main.html', 'film', '', '', '', '', '1');
INSERT INTO `defmenu` VALUES ('5', 'contacts', 'Связь', '0', '1', '/contacts/', 'contacts.html', 'main.html', 'pages', '2', '', '', '', '6');
INSERT INTO `defmenu` VALUES ('19', 'shedule', 'Расписание', '0', '1', '/shedule/', 'short.html', 'main.html', 'shedule', '', '', '', '', '4');
INSERT INTO `defmenu` VALUES ('20', 'about', 'Кинотеатр', '0', '1', '/about/', 'long.html', 'main.html', 'pages', '3', '', '', '', '5');
INSERT INTO `defmenu` VALUES ('21', 'affiche', 'Афиша', '0', '1', '/affiche/', 'short.html', 'films.html', 'film', '', '', '', '', '7');

-- ----------------------------
-- Table structure for `fieldtypes`
-- ----------------------------
DROP TABLE IF EXISTS `fieldtypes`;
CREATE TABLE `fieldtypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `typename` char(255) NOT NULL DEFAULT '',
  `realtype` char(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of fieldtypes
-- ----------------------------
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

-- ----------------------------
-- Table structure for `film`
-- ----------------------------
DROP TABLE IF EXISTS `film`;
CREATE TABLE `film` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order` int(11) NOT NULL,
  `linkid` int(11) NOT NULL,
  `nameid` varchar(100) DEFAULT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  `country` varchar(255) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `duration` time DEFAULT NULL,
  `director` varchar(255) DEFAULT NULL,
  `cast` text,
  `language` varchar(255) DEFAULT NULL,
  `genre` varchar(255) DEFAULT NULL,
  `description` text,
  `image1` varchar(255) DEFAULT NULL,
  `image2` varchar(255) DEFAULT NULL,
  `image3` varchar(255) DEFAULT NULL,
  `image4` varchar(255) DEFAULT NULL,
  `image5` varchar(255) DEFAULT NULL,
  `image6` varchar(255) DEFAULT NULL,
  `image7` varchar(255) DEFAULT NULL,
  `image8` varchar(255) DEFAULT NULL,
  `image9` varchar(255) DEFAULT NULL,
  `image10` varchar(255) DEFAULT NULL,
  `trailer` text,
  `subtitles` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `isfilm` tinyint(1) DEFAULT NULL,
  `slider` varchar(255) DEFAULT NULL,
  `original` varchar(255) DEFAULT NULL,
  `slide_text` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `linkid` (`linkid`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of film
-- ----------------------------
INSERT INTO `film` VALUES ('1', '1', '1', 'Твоя рука в моей руке', '1', 'Франция', '2012', '01:21:00', 'Валери Донзелли ', 'Валери Лемерсье (\'Любовь живет три года\', \'Сабрина\'), Жереми Элькайм, Беатрис Де Стаэль, Валери Донзелли', 'французский', ' Мелодрама. Комедия. ', 'Иоким, катается на скейтборде по улицам Парижа и живет в доме замужней сестры, подрабатывает в мастерской.  Выполняя поручение в Опера Гарнье, Иоким ни с того ни с сего целует в  темноте женщину &mdash; Элен Маршаль, надменная и строгая, преподает в  балетных классах. Поцелуй оказался не простым, а &laquo;волшебным&raquo;: отныне эти двое притянуты будто бы магнитом, не могут надолго отдаляться и вынужденно имитируют движения друг друга.', '/siteimg/film/0dfb202fc18add85846e817abb7715a4.jpg', '/siteimg/film/f5cf778cdebdcfc156f7f674a7dc2c26.jpg', '/siteimg/film/5b3148be0666ec34d0f6dc9947dfea4f.jpg', '/siteimg/film/c822986251c2ba6afaebccbe86064bd6.jpg', '/siteimg/film/3909ef92f525493d9582d4c249759e70.jpg', '/siteimg/film/69fc43b4dbeb512ffee658beeec9244c.jpg', '/siteimg/film/cca4a4f2eaa4bbb958633db22e88d4a4.jpg', '/siteimg/film/609614b0237f900c85397545cf54b547.jpg', '/siteimg/film/edbae4fbf9ae5081b95f489f217de724.jpg', null, '<iframe src=\"http://www.youtube.com/embed/1tsmb449WyE?feature=player_detailpage\" frameborder=\"0\" height=\"360\" width=\"640\"></iframe>', '', 'http://tickets.od.ua/order/index', '1', '/siteimg/film/ad05c0da9ef52a99927ad172514f6d83.jpg', 'Main dans la main', 'Киносреда в Англетере: предпремьерный показ фильма &laquo;<span>Твоя рука в моей руке</span>&raquo;');
INSERT INTO `film` VALUES ('2', '2', '2', 'Альфа дог', '1', 'США', '2006', '01:52:00', 'Ник Кассаветис', 'Эмиль Хирш, Антон Ельчин, Джастин Тимберлэйк, Шон Хэтаси, Брюс Уиллис, Бен Фостер, Шэрон Стоун, Хезер Уолкист, Доминик Суэйн, Оливия Уайлд', 'Русский, Украинский, Английский, Литовский', 'Криминал, Драмы, Биография, Зарубежные', 'Когда жизнь человека течет по руслу, проложенному среди наркотиков и  сопутствующих им обстоятельств, ее продолжительность от рождения до  смерти резко сокращается. Если бы Джонни Трулав отнесся к этому факту  чуть более серьезно, то не оказался бы первым номером в списке  разыскиваемых преступников ФБР. Ведь поначалу все было &ldquo;тип-топ&rdquo;!', '/siteimg/film/alpha-dog.jpg', '/siteimg/film/7888abd6791f64d227f0c4e1e0675325.jpg', '/siteimg/film/87ae7266b72a7f3a6303d9b06f176e4d.jpg', '/siteimg/film/5b400f30584ed63915dc46436065390d.jpg', '/siteimg/film/14d0031ef215f92412f689ce491ce308.jpg', '/siteimg/film/474b258a772cfcc2503f382cb6461322.jpg', '/siteimg/film/c314f579732d238b9ae8b781e5a6fb74.jpg', '/siteimg/film/845e269bf7695647c7a77a3339269a83.jpg', null, null, '<iframe src=\"http://www.youtube.com/embed/0xTwMgnYadY?feature=player_detailpage\" frameborder=\"0\" height=\"360\" width=\"640\"></iframe>', '', 'http://tickets.od.ua/order/index', '1', '/siteimg/film/940a8b42aa747809ec20e56b19e59f6c.jpg', 'Alpha Dog', 'Киносреда в Англетере: предпремьерный показ фильма &laquo;<span>Альфа дог</span>&raquo;');
INSERT INTO `film` VALUES ('3', '3', '3', 'Список контактов', '1', 'США', '2008', '01:47:00', 'Марсель Лангенеггер', '<span class=\"players-item\">Юэн МакГрегор,</span> <span class=\"players-item\">Хью Джекман,</span> <span class=\"players-item\">Michelle Williams,</span> <span class=\"players-item\">Брюс Олтмен</span>, <span class=\"players-item\">Эндрю Гинсбург,</span> <span class=\"players-item\">Стефани Рот</span>, <span class=\"players-item\">Кристин Кан,</span> <span class=\"players-item\">Данте Спинотти,</span> <span class=\"players-item\">Каролина Мюллер</span>, <span class=\"players-item\">Агнет Орнсхолт</span>', 'Русский, Украинский, Английский, Литовский', 'Детективы, Триллеры, Криминал, Драмы, Зарубежные   ', 'Джонатан работает в крупнейшей аудиторской фирме в мире. Работая с  бумагами, он погряз в рутине и ничего, кроме своего стола, не видит. Он  смотрит на людей за окном и остро чувствует, что жизнь проходит мимо.  Случайно познакомившись с Уайетом Боссом, назвавшимся юристом, и по  ошибке обменявшись с ним мобилками, он вынужден отвечать на звонки его  контактов, причем, надо сказать, очень соблазнительных контактов.  Потихоньку Джонатан примеряет на себя образ жизни Уайета и ему это  нравится. Шаг за шагом он уходит от твердой опоры, которой для него был  собственный образ жизни. А в таком случае &ndash; не далеко до беды.', '/siteimg/film/spisok.jpg', '/siteimg/film/db898a47941634dc686239c34912154f.jpg', '/siteimg/film/78adddea0f3a6a5d271fea835361ceba.jpg', '/siteimg/film/9a373958918ef6055a3c1f665ce4cec3.jpg', '/siteimg/film/fc50dc5863b6da6b24621dfa8251217d.jpg', '/siteimg/film/d126a9383d80af515e5f2eceb37257c8.jpg', '/siteimg/film/987a0e7dd50a366f14287009413428f2.jpg', '', null, null, '<iframe src=\"http://www.youtube.com/embed/k6AY94RVi-s?feature=player_detailpage\" frameborder=\"0\" height=\"360\" width=\"640\"></iframe>', '', '', '1', '/siteimg/film/dfe67b18eecedf4e512658282ad9ee0a.jpg', 'Deception', 'Киносреда в Англетере: предпремьерный показ фильма &laquo;<span>Список контактов</span>&raquo;');
INSERT INTO `film` VALUES ('6', '4', '4', 'Тест_редактирования', '0', '', '0', '00:00:00', '', '', '', '', '', '/siteimg/film/df051e9de6150293bb137e21d8080cc9.jpg', '/siteimg/film/64a91501b5a3ec89c28eb8c8bc5fb022.jpg', '/siteimg/film/a95464b2611b2a154a93c3726b8eea02.jpg', null, null, null, null, null, null, null, '', '', '', '0', '/siteimg/film/bed74cb67a593cf55de07e2b3f874986.jpg', '', null);

-- ----------------------------
-- Table structure for `film_defsec`
-- ----------------------------
DROP TABLE IF EXISTS `film_defsec`;
CREATE TABLE `film_defsec` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `num` text,
  `query` varchar(255) NOT NULL,
  `vname` varchar(100) NOT NULL,
  `vucms` varchar(100) NOT NULL,
  `vsite` varchar(200) NOT NULL,
  `vtype` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of film_defsec
-- ----------------------------
INSERT INTO `film_defsec` VALUES ('1', 'nothing', 'SELECT * FROM `film` ORDER BY `order`', '_short', 'id,visible,nameid,linkid', 'nameid', '1');

-- ----------------------------
-- Table structure for `film_prop`
-- ----------------------------
DROP TABLE IF EXISTS `film_prop`;
CREATE TABLE `film_prop` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fname` varchar(100) NOT NULL,
  `ftype` int(11) NOT NULL,
  `rus` varchar(100) NOT NULL,
  `properties` varchar(100) DEFAULT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `fname` (`fname`)
) ENGINE=MyISAM AUTO_INCREMENT=146 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of film_prop
-- ----------------------------
INSERT INTO `film_prop` VALUES ('1', 'id', '1', 'Системное поле', '', '1');
INSERT INTO `film_prop` VALUES ('2', 'order', '4', 'Порядок', '', '1');
INSERT INTO `film_prop` VALUES ('3', 'linkid', '4', 'Ссылка', '', '0');
INSERT INTO `film_prop` VALUES ('4', 'nameid', '2', 'Название', '', '1');
INSERT INTO `film_prop` VALUES ('5', 'visible', '12', 'Отображать', '1', '0');
INSERT INTO `film_prop` VALUES ('27', 'trailer', '7', 'Трейлер', '', '1');
INSERT INTO `film_prop` VALUES ('7', 'country', '2', 'Страна', '', '1');
INSERT INTO `film_prop` VALUES ('8', 'year', '4', 'Год выпуска', '', '1');
INSERT INTO `film_prop` VALUES ('9', 'duration', '6', 'Продолжительность', '', '1');
INSERT INTO `film_prop` VALUES ('10', 'director', '2', 'Режиссер', '', '1');
INSERT INTO `film_prop` VALUES ('14', 'cast', '7', 'В ролях', '', '1');
INSERT INTO `film_prop` VALUES ('12', 'language', '2', 'Язык', '', '1');
INSERT INTO `film_prop` VALUES ('13', 'genre', '2', 'Жанр', '', '1');
INSERT INTO `film_prop` VALUES ('15', 'description', '7', 'Описание', '', '1');
INSERT INTO `film_prop` VALUES ('16', 'image1', '8', 'Картинка', 'jpg&jpeg&gif&png', '1');
INSERT INTO `film_prop` VALUES ('17', 'image2', '8', 'Картинка 2', 'jpg&jpeg&gif&png', '1');
INSERT INTO `film_prop` VALUES ('18', 'image3', '8', 'Картинка 3', 'jpg&jpeg&gif&png', '1');
INSERT INTO `film_prop` VALUES ('19', 'image4', '8', 'Картинка 4', 'jpg&jpeg&gif&png', '1');
INSERT INTO `film_prop` VALUES ('20', 'image5', '8', 'Картинка 5', 'jpg&jpeg&gif&png', '1');
INSERT INTO `film_prop` VALUES ('21', 'image6', '8', 'Картинка 6', 'jpg&jpeg&gif&png', '1');
INSERT INTO `film_prop` VALUES ('22', 'image7', '8', 'Картинка 7', 'jpg&jpeg&gif&png', '1');
INSERT INTO `film_prop` VALUES ('23', 'image8', '8', 'Картинка 8', 'jpg&jpeg&gif&png', '1');
INSERT INTO `film_prop` VALUES ('24', 'image9', '8', 'Картинка 9', 'jpg&jpeg&gif&png', '1');
INSERT INTO `film_prop` VALUES ('25', 'image10', '8', 'Картинка 10', 'jpg&jpeg&gif&png', '1');
INSERT INTO `film_prop` VALUES ('28', 'subtitles', '2', 'Субтитры', '', '1');
INSERT INTO `film_prop` VALUES ('29', 'link', '2', 'Бронировать билеты', '', '1');
INSERT INTO `film_prop` VALUES ('30', 'isfilm', '12', 'Это фильм?', '', '1');
INSERT INTO `film_prop` VALUES ('31', 'slider', '8', 'Слайдер', 'jpg&jpeg&gif&png', '1');
INSERT INTO `film_prop` VALUES ('32', 'original', '2', 'Название Original', '', '1');
INSERT INTO `film_prop` VALUES ('33', 'slide_text', '7', 'Текст на слайде', '', '1');

-- ----------------------------
-- Table structure for `pages`
-- ----------------------------
DROP TABLE IF EXISTS `pages`;
CREATE TABLE `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order` int(11) NOT NULL DEFAULT '0',
  `linkid` int(11) NOT NULL DEFAULT '0',
  `nameid` varchar(100) DEFAULT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  `content` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pages
-- ----------------------------
INSERT INTO `pages` VALUES ('7', '1', '1', 'Главная', '0', '<p>ООО \"КИЙ АВИА КАРГО\" оказывает профессиональные услуги по международная авиадоставка грузов. Несмотря на растущую конкуренцию на рынке международных грузовых авиаперевозок, ООО \"КИЙ АВИА КАРГО\" занимает лидирующие позиции по авиаперевозкам в Украине.</p>\r\n<p>Это еще раз доказывает высокий профессионализм компании, способность расти и развиваться, внедрять новые технологии и разработки в систему международной авиадоставки грузов. Мы доставляем экспортные, импортные, транзитные грузы регулярными и чартерными авиарейсами из крупнейших аэропортов Украины: Борисполь, Днепропетровск, Одесса, Симферополь, Львов.</p>\r\n<p>Мы способны учесть практически все факторы, от которых зависят грузовые авиаперевозки: от погодных условий до форс-мажорных ситуаций и свести к минимуму их влияние на скорость и качество международных авиаперевозок грузов. Имея немалый опыт работы в сфере авиадоставки грузов, наша компания гарантирует Вам скрупулезный контроль и качественную организацию всех этапов авиаперевозки.</p>\r\n<p>Благодаря этому международные авиаперевозки грузов проходят идеально и точно в срок, вне зависимости от внешних факторов, способных негативно отразиться на качестве или скорости авиаперевозки грузов.</p>');
INSERT INTO `pages` VALUES ('8', '2', '2', 'Связь', '1', '<p><span style=\"font-family: \'trebuchet ms\', geneva; font-size: 14px;\"><strong>Автоответчик кинотеатра:</strong></span> <br class=\"none\" /> <span style=\"color: #ff6600;\"><strong><span style=\"font-family: \'trebuchet ms\', geneva; font-size: 14px;\"> <em>(0482) 33-95-10</em></span></strong></span> <br class=\"none\" /> <br class=\"none\" /> <span style=\"font-family: \'trebuchet ms\', geneva; font-size: 14px;\"><strong>Справки и предварительный заказ билетов</strong> (с 16:00 до 21:00):</span> <br class=\"none\" /> <span style=\"color: #ff6600;\"><strong><span style=\"font-family: \'trebuchet ms\', geneva; font-size: 14px;\"> <em>(0482) 33-95-55</em></span></strong></span> <br class=\"none\" /> <br class=\"none\" /> <span style=\"font-family: \'trebuchet ms\', geneva; font-size: 14px;\"><strong>Организация мероприятий, размещение рекламы, сотрудничество:</strong></span> <br class=\"none\" /> <span style=\"font-family: \'trebuchet ms\', geneva; font-size: 14px;\"> <em><span style=\"color: #ff6600;\"><strong>(093) 120 50 80</strong></span> <br class=\"none\" /> str.teensky@gmail.com</em></span> <br class=\"none\" /> <br class=\"none\" /> <br class=\"none\" /> <span style=\"font-family: \'trebuchet ms\', geneva; font-size: 14px;\">Кинотеатр &laquo;U-CINEMA&raquo; расположен в здании Одесской киностудии</span> <br class=\"none\" /> <span style=\"font-family: \'trebuchet ms\', geneva; font-size: 14px;\">по адресу: <strong>Французский бульвар, 33</strong></span> <span style=\"font-family: \'trebuchet ms\', geneva; font-size: 14px;\"><img style=\"margin-top: 8px; margin-bottom: 8px;\" src=\"/images/odessafilm.jpg\" alt=\"\" width=\"700\" /> </span></p>');
INSERT INTO `pages` VALUES ('10', '3', '3', 'Кинотеатр', '1', '<div class=\"heading-title\">\r\n<h2>Кинотеатр</h2>\r\n</div>\r\n<div class=\"post\"><strong><span style=\"font-family: \'trebuchet ms\', geneva; font-size: 14px;\">Артхаусный кинотеатр U-CINEMA расположен в здании Одесской киностудии.</span></strong> <br class=\"none\" /> <strong><span style=\"font-family: \'trebuchet ms\', geneva; font-size: 14px;\">С момента открытия в апреле 2010 года, U-CINEMA стал центром кинофестивальной жизни города и единственным местом показа альтернативного кино.</span></strong> <br class=\"none\" /> <br class=\"none\" /> <strong><span style=\"font-family: \'trebuchet ms\', geneva; font-size: 14px;\">На протяжении года здесь проводятся фестивали, представляющие фильмы различных стран мира:</span></strong> <br class=\"none\" /> \r\n<ul>\r\n<li><strong><span style=\"font-family: \'trebuchet ms\', geneva; font-size: 14px;\">Фестиваль Ирландского Кино (март)</span></strong></li>\r\n<li><strong><span style=\"font-family: \'trebuchet ms\', geneva; font-size: 14px;\">Фестиваль Итальянского Кино (март)</span></strong></li>\r\n<li><strong><span style=\"font-family: \'trebuchet ms\', geneva; font-size: 14px;\">Ночи Французского короткого метра (апрель)</span></strong></li>\r\n<li><strong><span style=\"font-family: \'trebuchet ms\', geneva; font-size: 14px;\">Фестиваль Голландского Кино (апрель)</span></strong></li>\r\n<li><strong><span style=\"font-family: \'trebuchet ms\', geneva; font-size: 14px;\">Фестиваль Грузинского Кино (май)</span></strong></li>\r\n<li><strong><span style=\"font-family: \'trebuchet ms\', geneva; font-size: 14px;\">Фестиваль Израильского Кино (июнь)</span></strong></li>\r\n<li><strong><span style=\"font-family: \'trebuchet ms\', geneva; font-size: 14px;\">(ОМКФ) Одесский Международный Кинофестиваль (июль)</span></strong></li>\r\n<li><strong><span style=\"font-family: \'trebuchet ms\', geneva; font-size: 14px;\">Фестиваль Польского Кино (сентябрь)</span></strong></li>\r\n</ul>\r\n<br class=\"none\" /> <strong><span style=\"font-family: \'trebuchet ms\', geneva; font-size: 14px;\">Зал на 300 мест оснащён акустической системой Dolby Surround, а также большой сценой и свето-звуковым оборудованием, позволяющими проводить здесь музыкальные концерты, театральные представления, творческие вечера и презентации.</span></strong>\r\n<div id=\"gallery\" class=\"clearfix\">\r\n<ul>\r\n<li><a class=\"gallery\" rel=\"gal\" href=\"/siteimg/zal_1.jpg\"><img src=\"/siteimg/zal_1.jpg\" alt=\"\" width=\"225\" height=\"155\" /></a></li>\r\n<li><a class=\"gallery\" rel=\"gal\" href=\"/siteimg/zal_2.jpg\"><img src=\"/siteimg/zal_2.jpg\" alt=\"\" width=\"225\" height=\"155\" /></a></li>\r\n<li><a class=\"gallery\" rel=\"gal\" href=\"/siteimg/zal_3.jpg\"><img src=\"/siteimg/zal_3.jpg\" alt=\"\" width=\"225\" height=\"155\" /></a></li>\r\n<li><a class=\"gallery\" rel=\"gal\" href=\"/siteimg/zal_4.jpg\"><img src=\"/siteimg/zal_4.jpg\" alt=\"\" width=\"225\" height=\"155\" /></a></li>\r\n<li><a class=\"gallery\" rel=\"gal\" href=\"/siteimg/zal_5.jpg\"><img src=\"/siteimg/zal_5.jpg\" alt=\"\" width=\"225\" height=\"155\" /></a></li>\r\n<li><a class=\"gallery\" rel=\"gal\" href=\"/siteimg/zal_6.jpg\"><img src=\"/siteimg/zal_6.jpg\" alt=\"\" width=\"225\" height=\"155\" /></a></li>\r\n<li><a class=\"gallery\" rel=\"gal\" href=\"/siteimg/zal_7.jpg\"><img src=\"/siteimg/zal_7.jpg\" alt=\"\" width=\"225\" height=\"155\" /></a></li>\r\n<li><a class=\"gallery\" rel=\"gal\" href=\"/siteimg/zal_8.jpg\"><img src=\"/siteimg/zal_8.jpg\" alt=\"\" width=\"225\" height=\"155\" /></a></li>\r\n</ul>\r\n</div>\r\n</div>');

-- ----------------------------
-- Table structure for `pages_defsec`
-- ----------------------------
DROP TABLE IF EXISTS `pages_defsec`;
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
-- Records of pages_defsec
-- ----------------------------
INSERT INTO `pages_defsec` VALUES ('1', 'nothing', 'SELECT * FROM `pages` ORDER BY `order`', '_short', 'id,visible,nameid,linkid', 'nameid', '1');

-- ----------------------------
-- Table structure for `pages_prop`
-- ----------------------------
DROP TABLE IF EXISTS `pages_prop`;
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
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

-- ----------------------------
-- Records of pages_prop
-- ----------------------------
INSERT INTO `pages_prop` VALUES ('1', 'id', '1', 'Системное поле', '', '1');
INSERT INTO `pages_prop` VALUES ('2', 'order', '4', 'Порядок', '', '1');
INSERT INTO `pages_prop` VALUES ('3', 'linkid', '4', 'Ссылка', '', '0');
INSERT INTO `pages_prop` VALUES ('4', 'nameid', '2', 'Название', '', '1');
INSERT INTO `pages_prop` VALUES ('5', 'visible', '12', 'Отображать', '', '0');
INSERT INTO `pages_prop` VALUES ('6', 'content', '7', 'Текст', '', '1');

-- ----------------------------
-- Table structure for `shedule`
-- ----------------------------
DROP TABLE IF EXISTS `shedule`;
CREATE TABLE `shedule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order` int(11) NOT NULL,
  `linkid` int(11) NOT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  `nameid` int(11) DEFAULT NULL,
  `cost` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `linkid` (`linkid`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shedule
-- ----------------------------
INSERT INTO `shedule` VALUES ('1', '4', '1', '1', '1', '20, 25', '2013-10-24', '17:30:00', 'http://tickets.od.ua/order/index');
INSERT INTO `shedule` VALUES ('2', '4', '2', '1', '1', '30', '2013-10-19', '23:50:00', 'http://tickets.od.ua/order/index');
INSERT INTO `shedule` VALUES ('3', '4', '3', '1', '1', '30', '2013-10-18', '10:15:00', 'http://tickets.od.ua/order/index');
INSERT INTO `shedule` VALUES ('4', '1', '4', '1', '1', '25', '2013-10-25', '22:30:00', 'http://tickets.od.ua/order/index');
INSERT INTO `shedule` VALUES ('5', '5', '5', '1', '1', '25', '2013-10-16', '15:30:00', 'http://tickets.od.ua/order/index');
INSERT INTO `shedule` VALUES ('6', '6', '6', '1', '1', '30', '2013-10-20', '12:00:00', 'http://tickets.od.ua/order/index');
INSERT INTO `shedule` VALUES ('7', '7', '7', '1', '1', '30', '2013-10-21', '11:45:00', 'http://tickets.od.ua/order/index');
INSERT INTO `shedule` VALUES ('8', '8', '8', '1', '1', '20, 25', '2013-10-18', '16:30:00', 'http://tickets.od.ua/order/index');
INSERT INTO `shedule` VALUES ('9', '9', '9', '1', '1', '25', '2013-11-14', '10:15:00', 'http://tickets.od.ua/order/index');
INSERT INTO `shedule` VALUES ('10', '10', '10', '1', '1', '20, 25', '2013-10-23', '12:10:00', 'http://tickets.od.ua/order/index');
INSERT INTO `shedule` VALUES ('11', '11', '11', '1', '1', '25', '2013-10-17', '16:45:00', 'http://tickets.od.ua/order/index');
INSERT INTO `shedule` VALUES ('12', '12', '12', '1', '1', '25', '2013-10-19', '12:20:00', 'http://tickets.od.ua/order/index');
INSERT INTO `shedule` VALUES ('13', '13', '13', '1', '1', '25', '2013-10-18', '23:00:00', 'http://tickets.od.ua/order/index');
INSERT INTO `shedule` VALUES ('14', '14', '14', '1', '1', '25', '2013-10-19', '20:00:00', 'http://tickets.od.ua/order/index');
INSERT INTO `shedule` VALUES ('15', '15', '15', '1', '1', '20, 25', '2013-10-17', '12:15:00', 'http://tickets.od.ua/order/index');
INSERT INTO `shedule` VALUES ('16', '16', '16', '1', '1', '50', '2013-10-17', '13:10:00', 'http://tickets.od.ua/order/index');
INSERT INTO `shedule` VALUES ('17', '17', '17', '1', '2', '30, 35', '2013-10-25', '16:45:00', 'http://tickets.od.ua/order/index');
INSERT INTO `shedule` VALUES ('18', '18', '18', '1', '2', '40', '2013-10-18', '23:10:00', 'http://tickets.od.ua/order/index');
INSERT INTO `shedule` VALUES ('19', '21', '21', '1', '3', '35, 40', '2013-10-19', '12:20:00', 'http://tickets.od.ua/order/index');
INSERT INTO `shedule` VALUES ('20', '20', '20', '1', '3', '50', '2013-10-24', '22:00:00', 'http://tickets.od.ua/order/index');
INSERT INTO `shedule` VALUES ('25', '22', '22', '1', '2', '35, 40', '2013-10-18', '12:30:00', 'http://tickets.od.ua/order/index');
INSERT INTO `shedule` VALUES ('26', '23', '23', '1', '3', '45', '2013-10-18', '16:45:00', 'http://tickets.od.ua/order/index');

-- ----------------------------
-- Table structure for `shedule_defsec`
-- ----------------------------
DROP TABLE IF EXISTS `shedule_defsec`;
CREATE TABLE `shedule_defsec` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `num` text,
  `query` varchar(255) NOT NULL,
  `vname` varchar(100) NOT NULL,
  `vucms` varchar(100) NOT NULL,
  `vsite` varchar(200) NOT NULL,
  `vtype` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shedule_defsec
-- ----------------------------
INSERT INTO `shedule_defsec` VALUES ('1', 'nothing', 'SELECT s.*,film.nameid AS filmname,film.duration,film.description,film.image1 FROM `shedule` s JOIN film ON film.id = s.nameid ORDER BY s.`order`', '_short', 'id,visible,nameid,linkid', 'nameid', '1');

-- ----------------------------
-- Table structure for `shedule_prop`
-- ----------------------------
DROP TABLE IF EXISTS `shedule_prop`;
CREATE TABLE `shedule_prop` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fname` varchar(100) NOT NULL,
  `ftype` int(11) NOT NULL,
  `rus` varchar(100) NOT NULL,
  `properties` varchar(100) DEFAULT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `fname` (`fname`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shedule_prop
-- ----------------------------
INSERT INTO `shedule_prop` VALUES ('1', 'id', '1', 'Системное поле', '', '1');
INSERT INTO `shedule_prop` VALUES ('2', 'order', '4', 'Порядок', '', '1');
INSERT INTO `shedule_prop` VALUES ('3', 'linkid', '4', 'Ссылка', '', '0');
INSERT INTO `shedule_prop` VALUES ('4', 'nameid', '13', 'Фильм', 'film&nameid', '1');
INSERT INTO `shedule_prop` VALUES ('5', 'visible', '12', 'Отображать', '1', '0');
INSERT INTO `shedule_prop` VALUES ('12', 'link', '2', 'Билеты', '', '1');
INSERT INTO `shedule_prop` VALUES ('8', 'cost', '2', 'Стоимость', '', '1');
INSERT INTO `shedule_prop` VALUES ('10', 'date', '5', 'Дата сеанса', '', '1');
INSERT INTO `shedule_prop` VALUES ('11', 'time', '6', 'Время сеанса', '', '1');
