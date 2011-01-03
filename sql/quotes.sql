-- phpMyAdmin SQL Dump
-- version 3.2.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 03, 2011 at 10:22 AM
-- Server version: 5.1.44
-- PHP Version: 5.3.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `shabda_model`
--

-- --------------------------------------------------------

--
-- Table structure for table `quotes`
--

CREATE TABLE `quotes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `quote` text NOT NULL,
  `source` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `views` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `quotes`
--

INSERT INTO `quotes` VALUES(1, 'I don''t know where my creativity comes from, and I don''t want to know.', 'Johnny Carson', '2007-11-14 14:24:55', '2007-11-14 14:24:55', 126);
INSERT INTO `quotes` VALUES(2, 'Learn to see... listen... and think for yourself.', 'Malcolm X', '2007-11-14 14:42:31', '2007-11-14 14:42:31', 119);
INSERT INTO `quotes` VALUES(3, 'You cannot separate peace from freedom, because no one can be at peace until he has freedom.', 'Malcolm X', '2007-11-14 14:43:16', '2007-11-14 14:43:16', 116);
INSERT INTO `quotes` VALUES(4, 'An idealist is one who, on noticing that a rose smells better than a cabbage, concludes that it will also make better soup.', 'H.L. Mencken', '2007-11-14 14:50:04', '2007-11-14 14:50:04', 140);
INSERT INTO `quotes` VALUES(5, 'The only way to keep your health is to eat what you don''t want, drink what you don''t like and do what you''d rather not.', 'Mark Twain', '2007-11-14 14:51:38', '2007-11-14 14:51:38', 128);
INSERT INTO `quotes` VALUES(6, 'Never underestimate a man who overestimates himself.', 'Franklin D. Roosevelt', '2007-11-14 14:52:57', '2007-11-14 14:52:57', 124);
INSERT INTO `quotes` VALUES(7, 'Anyone can make the simple complicated. Creativity is making the complicated simple.', 'Charles Mingus', '2007-11-14 17:58:16', '2007-11-14 17:58:16', 116);
INSERT INTO `quotes` VALUES(8, 'The fool doth think himself wise, but the wise man knows himself to be a fool.', 'William Shakespeare', '2007-11-19 10:21:47', '2007-11-19 10:21:47', 141);
INSERT INTO `quotes` VALUES(9, 'Men occasionally stumble over the truth, but most of them pick themselves up and hurry off as if nothing ever happened.', 'Winston Churchill', '2007-11-19 10:23:32', '2007-11-19 10:23:32', 121);
INSERT INTO `quotes` VALUES(10, 'To succeed, jump as quickly at opportunities as you do at conclusions.', 'Benjamin Franklin', '2007-11-25 02:12:38', '2007-11-25 02:12:38', 124);
INSERT INTO `quotes` VALUES(11, 'They who would give up an essential liberty for temporary security, deserve neither liberty or security.', 'Benjamin Franklin', '2007-11-25 02:11:29', '2007-11-25 02:11:29', 126);
INSERT INTO `quotes` VALUES(12, 'Where liberty is, there is my country.', 'Benjamin Franklin', '2007-11-25 02:13:08', '2007-11-25 02:13:08', 132);
INSERT INTO `quotes` VALUES(13, 'There are more love songs than anything else. If songs could make you do something we''d all love one another.', 'Frank Zappa', '2007-11-25 02:14:42', '2007-11-25 02:14:42', 110);
INSERT INTO `quotes` VALUES(14, 'Music, in performance, is a type of sculpture. The air in the performance is sculpted into something.', 'Frank Zappa', '2007-11-25 02:15:05', '2007-11-25 02:15:05', 119);
INSERT INTO `quotes` VALUES(15, 'Music was my refuge. I could crawl into the space between the notes and curl my back to loneliness.', 'Maya Angelou', '2007-11-25 02:15:41', '2007-11-25 02:15:41', 98);
INSERT INTO `quotes` VALUES(16, 'The memory of things gone is important to a jazz musician. Things like old folks singing in the moonlight in the back yard on a hot night or something said long ago.', 'Louis Armstrong', '2007-11-25 02:16:09', '2007-11-25 02:16:09', 126);
INSERT INTO `quotes` VALUES(17, 'Discontent is the first step in the progress of a man or a nation.', 'Oscar Wilde', '2007-11-25 14:59:24', '2007-11-25 14:59:24', 108);
INSERT INTO `quotes` VALUES(18, 'Experience is the name everyone gives to their mistakes.', 'Oscar Wilde', '2007-11-25 15:00:13', '2007-11-25 15:00:13', 127);
INSERT INTO `quotes` VALUES(19, 'The well-bred contradict other people.  The wise contradict themselves.', 'Oscar Wilde', '2007-11-25 15:01:05', '2007-11-25 15:01:05', 112);
INSERT INTO `quotes` VALUES(20, 'You can''t change the music of your soul.', 'Katherine Hepburn', '2007-11-25 15:01:40', '2007-11-25 15:01:40', 109);
INSERT INTO `quotes` VALUES(21, 'We should all be obliged to appear before a board every five years to justify our existence... on pain of liquidation.', 'George Bernard Shaw', '2007-11-25 15:03:59', '2007-11-25 15:03:59', 121);
INSERT INTO `quotes` VALUES(22, 'The power of accurate observation is commonly called cynicism by those who have not got it.', 'George Bernard Shaw', '2007-11-25 15:04:50', '2007-11-25 15:04:50', 118);
INSERT INTO `quotes` VALUES(23, 'A goal is a dream with a deadline.', 'igvita.com', '2008-02-28 03:39:18', '2008-02-28 03:39:18', 35);
INSERT INTO `quotes` VALUES(24, 'Everybody talks about the weather and nobody does anything about it.', 'Carl Sandburg', '2007-11-25 15:07:00', '2007-11-25 15:07:00', 118);
INSERT INTO `quotes` VALUES(25, 'May you live to eat the hen that scratches on your grave.', 'Carl Sandburg', '2007-11-25 15:07:31', '2007-11-25 15:07:31', 111);
INSERT INTO `quotes` VALUES(26, 'Why is the bribe-taker convicted so often and the bribe-giver so seldom?', 'Carl Sandburg', '2007-11-25 15:09:10', '2007-11-25 15:09:10', 116);
INSERT INTO `quotes` VALUES(27, 'Let us rise to a standard to which the wise and honest can repair?', 'George Washington', '2007-11-25 15:10:44', '2007-11-25 15:10:44', 119);
INSERT INTO `quotes` VALUES(28, 'In a free and republican government, you cannot restrain the voice of the multitude.', 'George Washington', '2007-11-25 15:11:48', '2007-11-25 15:11:48', 118);
INSERT INTO `quotes` VALUES(29, 'It is only after time has been given for cool and deliberate reflection that the real voice of the people can be known.', 'George Washington', '2007-11-25 15:12:31', '2007-11-28 16:27:18', 116);
INSERT INTO `quotes` VALUES(30, 'Talkers are not good doers.', 'Wiliam Shakespeare', '2007-11-25 15:13:58', '2007-11-25 15:13:58', 113);
INSERT INTO `quotes` VALUES(31, 'Circumstance has no value. It is how one relates to a situation that has value. All true meaning lies in the personal relationship to a phenomenon, what it means to you.', 'Chris McCandless', '2007-12-09 04:15:58', '2007-12-09 04:15:58', 119);
INSERT INTO `quotes` VALUES(32, 'I was surprised, as always, by how easy the act of leaving was, and how good it felt. The world was suddenly rich with possibility.', 'Jon Krakauer', '2007-12-09 04:16:21', '2007-12-09 04:16:21', 119);
INSERT INTO `quotes` VALUES(33, 'But for the sound of heaven have I seen all the colors of the Earth.', 'Jay Sanders', '2008-01-17 21:42:24', '2008-01-17 21:42:24', 103);
