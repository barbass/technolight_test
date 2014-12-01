-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Дек 01 2014 г., 15:09
-- Версия сервера: 5.6.11
-- Версия PHP: 5.5.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `techolight_translate`
--
CREATE DATABASE IF NOT EXISTS `techolight_translate` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `techolight_translate`;

-- --------------------------------------------------------

--
-- Структура таблицы `language`
--

CREATE TABLE IF NOT EXISTS `language` (
  `language_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `language` varchar(45) NOT NULL,
  PRIMARY KEY (`language_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Структура таблицы `migrations`
--

CREATE TABLE IF NOT EXISTS `migrations` (
  `version` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `word`
--

CREATE TABLE IF NOT EXISTS `word` (
  `word_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `word` varchar(150) NOT NULL,
  `language_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`word_id`),
  KEY `language_id` (`language_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1063 ;

-- --------------------------------------------------------

--
-- Структура таблицы `word_to_word`
--

CREATE TABLE IF NOT EXISTS `word_to_word` (
  `word_id_from` int(11) unsigned NOT NULL,
  `word_id_to` int(11) unsigned NOT NULL,
  KEY `word_id_from` (`word_id_from`),
  KEY `word_id_to` (`word_id_to`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `word`
--
ALTER TABLE `word`
  ADD CONSTRAINT `word_ibfk_1` FOREIGN KEY (`language_id`) REFERENCES `language` (`language_id`);

--
-- Ограничения внешнего ключа таблицы `word_to_word`
--
ALTER TABLE `word_to_word`
  ADD CONSTRAINT `word_to_word_ibfk_2` FOREIGN KEY (`word_id_to`) REFERENCES `word` (`word_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `word_to_word_ibfk_1` FOREIGN KEY (`word_id_from`) REFERENCES `word` (`word_id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
