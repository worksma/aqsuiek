CREATE TABLE `apps` (
  `id` int NOT NULL,
  `name` varchar(128) NOT NULL,
  `description` text NOT NULL,
  `patch` varchar(32) NOT NULL,
  `author` varchar(128) DEFAULT NULL,
  `website` varchar(256) DEFAULT NULL,
  `version` varchar(32) NOT NULL DEFAULT '1.0',
  `executor` varchar(512) DEFAULT NULL,
  `scripts` text NOT NULL,
  `styles` text NOT NULL,
  `date` varchar(64) NOT NULL DEFAULT '0000-00-00 00:00:00',
  `enable` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Структура таблицы `beta`
--

CREATE TABLE `beta` (
  `id` int NOT NULL,
  `userid` int NOT NULL,
  `date` varchar(64) NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Структура таблицы `beta__keys`
--

CREATE TABLE `beta__keys` (
  `id` int NOT NULL,
  `code` varchar(64) NOT NULL
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Структура таблицы `blog`
--

CREATE TABLE `blog` (
  `id` int NOT NULL,
  `userid` int NOT NULL,
  `themeid` int NOT NULL,
  `name` varchar(128) NOT NULL,
  `description` varchar(512) NOT NULL,
  `image` varchar(64) NOT NULL DEFAULT 'no_image.jpg',
  `cover` varchar(64) NOT NULL DEFAULT 'no_image.jpg',
  `data` text NOT NULL,
  `token` varchar(64) DEFAULT NULL,
  `date` varchar(64) NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Структура таблицы `blog__post`
--

CREATE TABLE `blog__post` (
  `id` int NOT NULL,
  `blogid` int NOT NULL,
  `userid` int DEFAULT NULL,
  `image` varchar(64) NOT NULL DEFAULT 'no_image.jpg',
  `title` varchar(128) NOT NULL,
  `content` text NOT NULL,
  `data` text NOT NULL,
  `date` varchar(64) NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Структура таблицы `blog__theme`
--

CREATE TABLE `blog__theme` (
  `id` int NOT NULL,
  `name` varchar(64) NOT NULL,
  `date` varchar(64) NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `blog__theme`
--

INSERT INTO `blog__theme` (`id`, `name`, `date`) VALUES
(1, 'Бизнес', '2023-08-09 02:14:00'),
(2, 'Новости', '2023-08-09 02:14:00'),
(3, 'Развлечения', '2023-08-09 02:14:00');

-- --------------------------------------------------------

--
-- Структура таблицы `chat__messages`
--

CREATE TABLE `chat__messages` (
  `id` int NOT NULL,
  `userid` int NOT NULL,
  `roomid` int NOT NULL,
  `message` varchar(4096) NOT NULL,
  `ready` text NOT NULL,
  `date` varchar(64) NOT NULL DEFAULT '0'
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Структура таблицы `chat__rooms`
--

CREATE TABLE `chat__rooms` (
  `id` int NOT NULL,
  `createid` int NOT NULL,
  `userid` int DEFAULT NULL,
  `name` varchar(128) NOT NULL,
  `description` varchar(512) NOT NULL,
  `participants` text NOT NULL,
  `date` int NOT NULL DEFAULT '0',
  `last_activity` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Структура таблицы `config`
--

CREATE TABLE `config` (
  `id` int NOT NULL,
  `site_name` varchar(128) NOT NULL DEFAULT 'SocialNetwork',
  `description` varchar(256) DEFAULT NULL,
  `appearance` varchar(64) NOT NULL DEFAULT 'standart',
  `keywords` varchar(256) DEFAULT NULL,
  `beta` int NOT NULL DEFAULT '0',
  `cache` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB;

--
-- Дамп данных таблицы `config`
--

INSERT INTO `config` (`id`, `site_name`, `description`, `appearance`, `keywords`, `beta`, `cache`) VALUES
(1, 'AQSUIEK', 'Создай свой собственный блог, развивай своё комьюнити и стремись завоевать высоты AWS Code Communities', 'standart', 'социальная сеть, socialnetwork, сеть для разработчиков, социальная сеть для разработчиков, php разработчики, вакансии программиста, торговая площадка, рынок, магазин, онлайн магазин, awscode, aws code, code, aws', 0, 2147483647);

-- --------------------------------------------------------

--
-- Структура таблицы `config__email`
--

CREATE TABLE `config__email` (
  `hostname` varchar(35) NOT NULL,
  `username` varchar(128) NOT NULL,
  `password` varchar(256) NOT NULL,
  `port` int NOT NULL,
  `charset` varchar(32) NOT NULL DEFAULT 'UTF-8'
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `config__email`
--

INSERT INTO `config__email` (`hostname`, `username`, `password`, `port`, `charset`) VALUES
('s25.hostia.name', 'no-reply@awscode.ru', 'aan39Who', 465, 'UTF-8');

-- --------------------------------------------------------

--
-- Структура таблицы `geo__city`
--

CREATE TABLE `geo__city` (
  `id` int NOT NULL,
  `name` varchar(128) NOT NULL,
  `countryid` int NOT NULL,
  `date` varchar(64) NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_update` varchar(64) NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB;

--
-- Дамп данных таблицы `geo__city`
--

INSERT INTO `geo__city` (`id`, `name`, `countryid`, `date`, `last_update`) VALUES
(1, 'Абай', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(2, 'Акколь', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(3, 'Аксай', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(4, 'Аксу', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(5, 'Актау', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(6, 'Актобе', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(7, 'Алга', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(8, 'Алматы', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(9, 'Алтай', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(10, 'Аральск', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(11, 'Аркалык', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(12, 'Арыс', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(13, 'Астана', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(14, 'Атбасар', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(15, 'Атырау', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(16, 'Аягоз', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(17, 'Байконур', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(18, 'Балхаш', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(19, 'Булаево', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(20, 'Державинск', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(21, 'Ерейментау', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(22, 'Есик', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(23, 'Есиль', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(24, 'Жанаозен', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(25, 'Жанатас', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(26, 'Жаркент', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(27, 'Жезказган', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(28, 'Жем', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(29, 'Жетысай', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(30, 'Житикара', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(31, 'Зайсан', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(32, 'Казалинск', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(33, 'Кандыагаш', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(34, 'Караганда', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(35, 'Каражал', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(36, 'Каратау', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(37, 'Каркаралинск', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(38, 'Каскелен', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(39, 'Кентау', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(40, 'Кокшетау', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(41, 'Конаев', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(42, 'Костанай', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(43, 'Косшы', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(44, 'Кульсары', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(45, 'Курчатов', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(46, 'Кызылорда', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(47, 'Ленгер', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(48, 'Лисаковск', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(49, 'Макинск', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(50, 'Мамлютка', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(51, 'Павлодар', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(52, 'Петропавловск', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(53, 'Приозёрск', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(54, 'Риддер', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(55, 'Рудный', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(56, 'Сарань', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(57, 'Сарканд', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(58, 'Сарыагаш', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(59, 'Сатпаев', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(60, 'Семей', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(61, 'Сергеевка', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(62, 'Серебрянск', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(63, 'Степногорск', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(64, 'Степняк', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(65, 'Тайынша', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(66, 'Талгар', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(67, 'Талдыкорган', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(68, 'Тараз', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(69, 'Текели', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(70, 'Темир', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(71, 'Темиртау', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(72, 'Тобыл', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(73, 'Туркестан', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(74, 'Уральск', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(75, 'Усть-Каменогорск', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(76, 'Ушарал', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(77, 'Уштобе', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(78, 'Форт-Шевченко', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(79, 'Хромтау', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(80, 'Шалкар', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(81, 'Шар', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(82, 'Шардара', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(83, 'Шахтинск', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(84, 'Шемонаиха', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(85, 'Шу', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(86, 'Шымкент', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(87, 'Щучинск', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(88, 'Экибастуз', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00'),
(89, 'Эмба', 1, '2023-07-17 14:59:00', '2023-07-17 14:59:00');

-- --------------------------------------------------------

--
-- Структура таблицы `geo__country`
--

CREATE TABLE `geo__country` (
  `id` int NOT NULL,
  `name` varchar(128) NOT NULL,
  `date` varchar(64) NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_update` varchar(64) NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB;

--
-- Дамп данных таблицы `geo__country`
--

INSERT INTO `geo__country` (`id`, `name`, `date`, `last_update`) VALUES
(1, 'Казахстан', '2023-07-17 14:38:00', '2023-07-17 14:38:00');

-- --------------------------------------------------------

--
-- Структура таблицы `help`
--

CREATE TABLE `help` (
  `id` int NOT NULL,
  `title` varchar(64) NOT NULL,
  `userid` int NOT NULL,
  `status` int NOT NULL DEFAULT '2',
  `date` varchar(64) NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Структура таблицы `help__chat`
--

CREATE TABLE `help__chat` (
  `id` int NOT NULL,
  `ticketid` int NOT NULL,
  `userid` int NOT NULL,
  `message` varchar(1024) NOT NULL,
  `date` varchar(64) NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Структура таблицы `music__albums`
--

CREATE TABLE `music__albums` (
  `id` int NOT NULL,
  `name` varchar(128) NOT NULL,
  `artistids` text NOT NULL,
  `image` varchar(64) NOT NULL,
  `date_create` int NOT NULL
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Структура таблицы `music__artist`
--

CREATE TABLE `music__artist` (
  `id` int NOT NULL,
  `name` varchar(128) NOT NULL,
  `nickname` varchar(32) NOT NULL,
  `date_create` int NOT NULL
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Структура таблицы `music__tracks`
--

CREATE TABLE `music__tracks` (
  `id` int NOT NULL,
  `name` varchar(128) NOT NULL,
  `albumid` int NOT NULL,
  `artistids` text NOT NULL,
  `image` varchar(64) NOT NULL,
  `file` varchar(256) NOT NULL,
  `streamings` int NOT NULL DEFAULT '0',
  `date_create` int NOT NULL
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Структура таблицы `noty__events`
--

CREATE TABLE `noty__events` (
  `id` int NOT NULL,
  `userid` int NOT NULL,
  `message` varchar(256) NOT NULL,
  `uri` varchar(256) NOT NULL,
  `date` varchar(64) NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Структура таблицы `noty__types`
--

CREATE TABLE `noty__types` (
  `id` int NOT NULL,
  `name` varchar(64) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `noty__types`
--

INSERT INTO `noty__types` (`id`, `name`) VALUES
(1, 'Безопасность профиля'),
(2, 'Поддержка сайта');

-- --------------------------------------------------------

--
-- Структура таблицы `noty__users`
--

CREATE TABLE `noty__users` (
  `id` int NOT NULL,
  `userid` int NOT NULL,
  `image` varchar(64) NOT NULL DEFAULT 'no_image.svg',
  `content` text NOT NULL,
  `link` varchar(256) DEFAULT NULL,
  `type` int DEFAULT NULL,
  `ready` int NOT NULL DEFAULT '2',
  `attached` varchar(512) DEFAULT NULL,
  `date` varchar(64) NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `noty__users`
--

INSERT INTO `noty__users` (`id`, `userid`, `image`, `content`, `link`, `type`, `ready`, `attached`, `date`) VALUES
(1, 1, 'cyber.svg', 'Выполнен вход в профиль', '/account/settings?act=security', 1, 1, NULL, '2024-03-15 16:32:15'),
(2, 1, 'support.svg', 'Доступен новый ответ', '/help/request/id1', 2, 1, NULL, '2024-03-15 17:26:19'),
(3, 1, 'cyber.svg', 'Выполнен вход в профиль', '/account/settings?act=security', 1, 1, NULL, '2024-03-15 19:26:42'),
(4, 1, 'cyber.svg', 'Выполнен вход в профиль', '/account/settings?act=security', 1, 1, NULL, '2024-03-16 08:45:40'),
(5, 1, 'cyber.svg', 'Выполнен вход в профиль', '/account/settings?act=security', 1, 1, NULL, '2024-03-17 23:10:56'),
(6, 1, 'cyber.svg', 'Выполнен вход в профиль', '/account/settings?act=security', 1, 1, NULL, '2024-03-18 18:05:12');

-- --------------------------------------------------------

--
-- Структура таблицы `pages`
--

CREATE TABLE `pages` (
  `id` int NOT NULL,
  `name` varchar(64) NOT NULL,
  `description` text NOT NULL,
  `keywords` text NOT NULL,
  `uri` varchar(128) NOT NULL,
  `module` varchar(256) NOT NULL,
  `image` varchar(64) NOT NULL DEFAULT '/public/images/other/meta.jpg',
  `appid` int DEFAULT NULL,
  `enable` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `email` varchar(128) DEFAULT NULL,
  `nickname` varchar(15) NOT NULL,
  `password` varchar(256) DEFAULT NULL,
  `first_name` varchar(64) NOT NULL,
  `last_name` varchar(64) DEFAULT NULL,
  `sex` int NOT NULL DEFAULT '1',
  `birthday` varchar(64) NOT NULL DEFAULT '0000-00-00',
  `country` int DEFAULT NULL,
  `city` int DEFAULT NULL,
  `rights` int NOT NULL DEFAULT '2',
  `language` varchar(11) NOT NULL DEFAULT 'ru',
  `very` int NOT NULL DEFAULT '0',
  `image` varchar(64) NOT NULL DEFAULT 'no_image.jpg',
  `cover` varchar(64) NOT NULL DEFAULT 'no_image.jpg',
  `balance` int NOT NULL DEFAULT '0',
  `register` varchar(64) NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_online` varchar(64) NOT NULL DEFAULT '0000-00-00 00:00:00',
  `hash` varchar(256) DEFAULT NULL
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Структура таблицы `users__blacklist`
--

CREATE TABLE `users__blacklist` (
  `id` int NOT NULL,
  `userid` int NOT NULL,
  `resid` int NOT NULL,
  `date` varchar(64) NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Структура таблицы `users__group`
--

CREATE TABLE `users__group` (
  `id` int NOT NULL,
  `name` varchar(64) NOT NULL,
  `rgba` varchar(64) NOT NULL,
  `style` varchar(256) NOT NULL,
  `rights` int NOT NULL
) ENGINE=InnoDB;

--
-- Дамп данных таблицы `users__group`
--

INSERT INTO `users__group` (`id`, `name`, `rgba`, `style`, `rights`) VALUES
(1, 'Основатель', '239, 59, 59', '', 1),
(2, 'Пользователь', '0, 0, 0', '', 2),
(3, 'Ограниченный', '122, 122, 122', 'text-decoration: line-through;', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `users__music`
--

CREATE TABLE `users__music` (
  `id` int NOT NULL,
  `userid` int NOT NULL,
  `data` text NOT NULL
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Структура таблицы `users__recovery`
--

CREATE TABLE `users__recovery` (
  `id` int NOT NULL,
  `email` varchar(64) NOT NULL,
  `ip` varchar(35) NOT NULL,
  `hash` varchar(512) NOT NULL,
  `date` varchar(64) NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Структура таблицы `users__sub`
--

CREATE TABLE `users__sub` (
  `id` int NOT NULL,
  `recipientid` int NOT NULL,
  `senderid` int NOT NULL,
  `date` varchar(64) NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Структура таблицы `writing`
--

CREATE TABLE `writing` (
  `id` int NOT NULL,
  `author` int NOT NULL,
  `universe` int NOT NULL,
  `entity` varchar(64) DEFAULT NULL,
  `date` varchar(64) NOT NULL DEFAULT '0000-00-00 00:00:00',
  `content` text NOT NULL,
  `remove` int NOT NULL DEFAULT '0',
  `remove_reason` varchar(256) DEFAULT NULL,
  `remove_date` varchar(64) NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Структура таблицы `writing__attachments`
--

CREATE TABLE `writing__attachments` (
  `id` int NOT NULL,
  `userid` int NOT NULL,
  `writeid` int NOT NULL,
  `name` varchar(256) NOT NULL,
  `size` varchar(64) NOT NULL,
  `expansion` varchar(64) NOT NULL,
  `document` varchar(256) NOT NULL,
  `date` varchar(64) NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Структура таблицы `writing__comments`
--

CREATE TABLE `writing__comments` (
  `id` int NOT NULL,
  `userid` int NOT NULL,
  `writeid` int NOT NULL,
  `content` varchar(2000) NOT NULL,
  `date` varchar(64) NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Структура таблицы `writing__comments-attachments`
--

CREATE TABLE `writing__comments-attachments` (
  `id` int NOT NULL,
  `userid` int NOT NULL,
  `commentid` int NOT NULL,
  `name` varchar(256) NOT NULL,
  `size` varchar(64) NOT NULL,
  `expansion` varchar(64) NOT NULL,
  `document` varchar(256) NOT NULL,
  `date` varchar(64) NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Структура таблицы `writing__comments-likes`
--

CREATE TABLE `writing__comments-likes` (
  `id` int NOT NULL,
  `userid` int NOT NULL,
  `commentid` int NOT NULL,
  `authorid` int NOT NULL,
  `date` varchar(64) NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Структура таблицы `writing__likes`
--

CREATE TABLE `writing__likes` (
  `id` int NOT NULL,
  `userid` int NOT NULL,
  `writeid` int NOT NULL,
  `authorid` int NOT NULL,
  `date` varchar(64) NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Структура таблицы `writing__views`
--

CREATE TABLE `writing__views` (
  `id` int NOT NULL,
  `writeid` int NOT NULL,
  `ip` varchar(64) NOT NULL,
  `date` varchar(64) NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `apps`
--
ALTER TABLE `apps`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `beta`
--
ALTER TABLE `beta`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `beta__keys`
--
ALTER TABLE `beta__keys`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `blog`
--
ALTER TABLE `blog`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `blog__post`
--
ALTER TABLE `blog__post`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `blog__theme`
--
ALTER TABLE `blog__theme`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `chat__messages`
--
ALTER TABLE `chat__messages`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `chat__rooms`
--
ALTER TABLE `chat__rooms`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `geo__city`
--
ALTER TABLE `geo__city`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `geo__country`
--
ALTER TABLE `geo__country`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `help`
--
ALTER TABLE `help`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `help__chat`
--
ALTER TABLE `help__chat`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `music__albums`
--
ALTER TABLE `music__albums`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `music__artist`
--
ALTER TABLE `music__artist`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `music__tracks`
--
ALTER TABLE `music__tracks`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `noty__events`
--
ALTER TABLE `noty__events`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `noty__types`
--
ALTER TABLE `noty__types`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `noty__users`
--
ALTER TABLE `noty__users`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users__blacklist`
--
ALTER TABLE `users__blacklist`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users__group`
--
ALTER TABLE `users__group`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users__music`
--
ALTER TABLE `users__music`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users__recovery`
--
ALTER TABLE `users__recovery`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users__sub`
--
ALTER TABLE `users__sub`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `writing`
--
ALTER TABLE `writing`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `writing__attachments`
--
ALTER TABLE `writing__attachments`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `writing__comments`
--
ALTER TABLE `writing__comments`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `writing__comments-attachments`
--
ALTER TABLE `writing__comments-attachments`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `writing__comments-likes`
--
ALTER TABLE `writing__comments-likes`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `writing__likes`
--
ALTER TABLE `writing__likes`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `writing__views`
--
ALTER TABLE `writing__views`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `apps`
--
ALTER TABLE `apps`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `beta`
--
ALTER TABLE `beta`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `beta__keys`
--
ALTER TABLE `beta__keys`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `blog`
--
ALTER TABLE `blog`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `blog__post`
--
ALTER TABLE `blog__post`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `blog__theme`
--
ALTER TABLE `blog__theme`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `chat__messages`
--
ALTER TABLE `chat__messages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `chat__rooms`
--
ALTER TABLE `chat__rooms`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `config`
--
ALTER TABLE `config`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `geo__city`
--
ALTER TABLE `geo__city`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT для таблицы `geo__country`
--
ALTER TABLE `geo__country`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `help`
--
ALTER TABLE `help`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `help__chat`
--
ALTER TABLE `help__chat`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `music__albums`
--
ALTER TABLE `music__albums`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `music__artist`
--
ALTER TABLE `music__artist`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `music__tracks`
--
ALTER TABLE `music__tracks`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `noty__events`
--
ALTER TABLE `noty__events`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `noty__types`
--
ALTER TABLE `noty__types`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `noty__users`
--
ALTER TABLE `noty__users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `users__blacklist`
--
ALTER TABLE `users__blacklist`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `users__group`
--
ALTER TABLE `users__group`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `users__music`
--
ALTER TABLE `users__music`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `users__recovery`
--
ALTER TABLE `users__recovery`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `users__sub`
--
ALTER TABLE `users__sub`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `writing`
--
ALTER TABLE `writing`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `writing__attachments`
--
ALTER TABLE `writing__attachments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `writing__comments`
--
ALTER TABLE `writing__comments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `writing__comments-attachments`
--
ALTER TABLE `writing__comments-attachments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `writing__comments-likes`
--
ALTER TABLE `writing__comments-likes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `writing__likes`
--
ALTER TABLE `writing__likes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `writing__views`
--
ALTER TABLE `writing__views`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;