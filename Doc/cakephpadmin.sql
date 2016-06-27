-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 27, 2016 at 11:11 AM
-- Server version: 5.5.47-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `cakephpadmin`
--

-- --------------------------------------------------------

--
-- Table structure for table `city_master`
--

CREATE TABLE IF NOT EXISTS `city_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_id` int(11) NOT NULL COMMENT 'country id',
  `state_id` int(11) NOT NULL COMMENT 'state id',
  `city_name` varchar(255) NOT NULL COMMENT 'city name',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Active = 1, Inactive = 0',
  `date_created` datetime NOT NULL COMMENT 'created date',
  `date_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'modified date',
  PRIMARY KEY (`id`),
  KEY `country_id` (`country_id`),
  KEY `state_id` (`state_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='city master table' AUTO_INCREMENT=2 ;

--
-- Dumping data for table `city_master`
--

INSERT INTO `city_master` (`id`, `country_id`, `state_id`, `city_name`, `status`, `date_created`, `date_modified`) VALUES
(1, 2, 1, 'Ahmedabad', 0, '2016-03-31 10:23:19', '2016-03-31 04:53:19');

-- --------------------------------------------------------

--
-- Table structure for table `country_master`
--

CREATE TABLE IF NOT EXISTS `country_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_name` varchar(255) NOT NULL COMMENT 'country name',
  `status` tinyint(1) NOT NULL COMMENT 'Active = 1, Inactive = 0',
  `date_created` datetime NOT NULL COMMENT 'created date',
  `date_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'modified date',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='country master table' AUTO_INCREMENT=4 ;

--
-- Dumping data for table `country_master`
--

INSERT INTO `country_master` (`id`, `country_name`, `status`, `date_created`, `date_modified`) VALUES
(2, 'india', 0, '2016-03-07 10:43:54', '2016-03-07 05:13:54'),
(3, 'U.S', 0, '2016-06-01 12:36:43', '2016-06-01 07:06:43');

-- --------------------------------------------------------

--
-- Table structure for table `faq`
--

CREATE TABLE IF NOT EXISTS `faq` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` varchar(100) NOT NULL,
  `answer` text NOT NULL,
  `status` tinyint(1) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `faq`
--

INSERT INTO `faq` (`id`, `question`, `answer`, `status`, `date_created`, `date_modified`) VALUES
(1, 'Question12', '<p>Answer</p>', 0, '2015-12-03 13:36:25', '2016-04-13 05:43:52'),
(3, 'Dummy question12', '<p>Please follow below steps:&nbsp;</p>\r\n\r\n<ol>\r\n	<li>Dummy step1</li>\r\n	<li>Dummy step2</li>\r\n	<li>Dummy step3</li>\r\n</ol>\r\n', 1, '2015-12-07 14:00:44', '2016-06-17 12:50:42');

-- --------------------------------------------------------

--
-- Table structure for table `module`
--

CREATE TABLE IF NOT EXISTS `module` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(3) NOT NULL COMMENT 'Primary key of module',
  `link_order` int(3) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `display_submodules` tinyint(1) NOT NULL DEFAULT '1',
  `icon` varchar(100) DEFAULT NULL,
  `href` varchar(255) DEFAULT NULL,
  `target` varchar(50) DEFAULT NULL,
  `controller` varchar(100) DEFAULT NULL,
  `action` varchar(50) DEFAULT NULL,
  `actions` text NOT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Module Deleted = 1, Module Not Deleted = 0',
  `created` datetime NOT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='machine''s model table' AUTO_INCREMENT=17 ;

--
-- Dumping data for table `module`
--

INSERT INTO `module` (`id`, `parent_id`, `link_order`, `name`, `alias`, `display_submodules`, `icon`, `href`, `target`, `controller`, `action`, `actions`, `is_deleted`, `created`, `modified`) VALUES
(1, 0, 1, 'Dashboard', 'dashboard', 1, 'fa fa-home', 'dashboard', NULL, 'dashboard', 'index', '{"ALL":"All Rights"}', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 0, 2, 'User & Roles', 'user_and_roles', 1, 'fa fa-user', '#', NULL, NULL, NULL, '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 2, 1, 'Users', 'user_and_roles_users', 1, 'fa fa-group', 'users', NULL, 'users', 'index', '{\n"ADD":"Add User",\n"EDIT":"Edit User",\n"VIEW":"View User",\n"DELETE":"Delete User",\n"EDIT_PERMISSION":"Edit User Permissions","EXPORT":"Export Users"\n} ', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 2, 2, 'Roles', 'user_and_roles_roles', 1, 'fa-user-plus', 'rolemasters', NULL, 'roleMasters', 'index', '{"ADD":"Add Role","EDIT":"Edit Role","VIEW":"View Role","DELETE":"Delete",\n"EDIT_PERMISSION":"Edit Role Permissions","EXPORT":"Export Roles" }', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 0, 3, 'Masters', 'masters', 1, 'fa-book', '#', NULL, NULL, NULL, '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, 5, 1, 'Country Master', 'masters_country', 1, 'fa-globe', 'country_masters', NULL, 'CountryMasters', 'index', '{"ADD":"Add Country","EDIT":"Edit Country","VIEW":"View Country","DELETE":"Delete Country", "EXPORT":"Export Country"}', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 5, 2, 'State Master', 'masters_state', 1, 'fa-fort-awesome', 'state_masters', NULL, 'StateMasters', 'index', '{"ADD":"Add State","EDIT":"Edit State","VIEW":"View State","DELETE":"Delete State", "EXPORT":"Export State"}', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(9, 5, 4, 'Tag Master', 'masters_tag', 1, 'fa-tags', 'tag_masters', NULL, 'TagMasters', 'index', '{"ADD":"Add Tag","EDIT":"Edit Tag","VIEW":"View Tag","DELETE":"Delete Tag", "EXPORT":"Export Tag"}', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10, 0, 3, 'CMS', 'cms', 1, 'fa-sitemap', '#', NULL, NULL, NULL, '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(11, 10, 1, 'Template', 'cms_template', 1, 'fa-code', 'template', NULL, 'Template', 'index', '{"ADD":"Add Template","EDIT":"Edit Template","VIEW":"View Template","DELETE":"Delete Template"}', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(12, 10, 2, 'Page', 'cms_page', 1, 'fa-newspaper-o', 'pages', NULL, 'Pages', 'index', '{"ADD":"Add Page","EDIT":"Edit Page","VIEW":"View Page","DELETE":"Delete Page"}', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(13, 10, 3, 'Faq', 'cms_faq', 1, 'fa-tasks', 'faq', NULL, 'Faq', 'index', '{"ADD":"Add Faq","EDIT":"Edit Faq","VIEW":"View Faq","DELETE":"Delete Faq"}', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(14, 10, 4, 'Posts', 'cms_posts', 1, 'fa-comments', 'posts', NULL, 'Posts', 'index', '{"ADD":"Add Posts","EDIT":"Edit Posts","VIEW":"View Posts","DELETE":"Delete Posts"}', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(16, 5, 2, 'City Master', 'masters_city', 1, 'fa-fort-awesome', 'city_masters', NULL, 'CityMasters', 'index', '{"ADD":"Add City","EDIT":"Edit City","VIEW":"View City","DELETE":"Delete City", "EXPORT":"Export City"}', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `module_permission`
--

CREATE TABLE IF NOT EXISTS `module_permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` tinyint(4) DEFAULT NULL COMMENT 'Primary key of role_master',
  `user_id` int(11) DEFAULT NULL COMMENT 'Primary key of user_master',
  `module_id` int(3) NOT NULL COMMENT 'Primary key of module',
  `permissions` text NOT NULL COMMENT 'permission actions',
  `date_created` datetime NOT NULL COMMENT 'created date',
  `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'modified date',
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`),
  KEY `user_id` (`user_id`),
  KEY `module_id` (`module_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='module permission for admin panel' AUTO_INCREMENT=336 ;

--
-- Dumping data for table `module_permission`
--

INSERT INTO `module_permission` (`id`, `role_id`, `user_id`, `module_id`, `permissions`, `date_created`, `date_modified`) VALUES
(5, NULL, 1, 3, '["ALL","ADD","EDIT","VIEW","DELETE","EDIT_PERMISSION","EXPORT"]', '0000-00-00 00:00:00', NULL),
(6, NULL, 1, 4, '["ALL","ADD","EDIT","VIEW","DELETE","EDIT_PERMISSION","EXPORT"]', '0000-00-00 00:00:00', NULL),
(51, 1, NULL, 1, '["ALL"]', '2016-03-18 13:37:27', '2016-03-18 08:07:27'),
(52, 1, NULL, 2, '["ALL"]', '2016-03-18 13:37:27', '2016-03-18 08:07:27'),
(53, 1, NULL, 3, '["ALL","ADD","EDIT","VIEW","DELETE","EDIT_PERMISSION","EXPORT"]', '2016-03-18 13:37:27', '2016-03-18 08:07:27'),
(54, 1, NULL, 4, '["ALL","ADD","EDIT","VIEW","DELETE","EDIT_PERMISSION","EXPORT"]', '2016-03-18 13:37:27', '2016-03-18 08:07:27'),
(55, 1, NULL, 5, '["ALL"]', '2016-03-18 13:37:27', '2016-03-18 08:07:27'),
(56, 1, NULL, 6, '["ALL","ADD","EDIT","VIEW","DELETE","EXPORT"]', '2016-03-18 13:37:27', '2016-03-18 08:07:27'),
(57, 1, NULL, 7, '["ALL","ADD","EDIT","VIEW","DELETE","EXPORT"]', '2016-03-18 13:37:27', '2016-03-18 08:07:27'),
(59, 1, NULL, 9, '["ALL","ADD","EDIT","VIEW","DELETE","EXPORT"]', '2016-03-18 13:37:27', '2016-03-18 08:07:27'),
(60, 1, NULL, 10, '["ALL"]', '2016-03-18 13:37:27', '2016-03-18 08:07:27'),
(61, 1, NULL, 11, '["ALL","ADD","EDIT","VIEW","DELETE"]', '2016-03-18 13:37:27', '2016-03-18 08:07:27'),
(62, 1, NULL, 12, '["ALL","ADD","EDIT","VIEW","DELETE"]', '2016-03-18 13:37:27', '2016-03-18 08:07:27'),
(63, 1, NULL, 13, '["ALL","ADD","EDIT","VIEW","DELETE"]', '2016-03-18 13:37:27', '2016-03-18 08:07:27'),
(64, 1, NULL, 14, '["ALL","ADD","EDIT","VIEW","DELETE"]', '2016-03-18 13:37:27', '2016-03-18 08:07:27'),
(68, 2, NULL, 1, '["ALL"]', '2016-03-28 13:58:29', '2016-03-28 08:28:29'),
(69, 2, NULL, 2, '["ALL"]', '2016-03-28 13:58:29', '2016-03-28 08:28:29'),
(70, 2, NULL, 3, '["ALL","ADD","EDIT","VIEW","DELETE","EDIT_PERMISSION","EXPORT"]', '2016-03-28 13:58:29', '2016-03-28 08:28:29'),
(71, 2, NULL, 4, '["ALL","ADD","EDIT","VIEW","DELETE","EDIT_PERMISSION","EXPORT"]', '2016-03-28 13:58:29', '2016-03-28 08:28:29'),
(72, 2, NULL, 5, '["ALL"]', '2016-03-28 13:58:29', '2016-03-28 08:28:29'),
(73, 2, NULL, 6, '["ALL","ADD","EDIT","VIEW","DELETE","EXPORT"]', '2016-03-28 13:58:29', '2016-03-28 08:28:29'),
(74, 2, NULL, 7, '["ALL","ADD","EDIT","VIEW","DELETE","EXPORT"]', '2016-03-28 13:58:29', '2016-03-28 08:28:29'),
(76, 2, NULL, 9, '["ALL","ADD","EDIT","VIEW","DELETE","EXPORT"]', '2016-03-28 13:58:29', '2016-03-28 08:28:29'),
(77, 2, NULL, 10, '["ALL"]', '2016-03-28 13:58:29', '2016-03-28 08:28:29'),
(78, 2, NULL, 11, '["ALL","ADD","EDIT","VIEW","DELETE"]', '2016-03-28 13:58:29', '2016-03-28 08:28:29'),
(79, 2, NULL, 12, '["ALL","ADD","EDIT","VIEW","DELETE"]', '2016-03-28 13:58:29', '2016-03-28 08:28:29'),
(80, 2, NULL, 13, '["ALL","ADD","EDIT","VIEW","DELETE"]', '2016-03-28 13:58:29', '2016-03-28 08:28:29'),
(81, 2, NULL, 14, '["ALL","ADD","EDIT","VIEW","DELETE"]', '2016-03-28 13:58:29', '2016-03-28 08:28:29'),
(321, NULL, 7, 1, '["ALL"]', '2016-03-31 10:22:59', '2016-03-31 04:52:59'),
(322, NULL, 7, 2, '["ALL"]', '2016-03-31 10:22:59', '2016-03-31 04:52:59'),
(323, NULL, 7, 3, '["ALL","ADD","EDIT","VIEW","DELETE","EDIT_PERMISSION","EXPORT"]', '2016-03-31 10:22:59', '2016-03-31 04:52:59'),
(324, NULL, 7, 4, '["ALL","ADD","EDIT","VIEW","DELETE","EDIT_PERMISSION","EXPORT"]', '2016-03-31 10:22:59', '2016-03-31 04:52:59'),
(325, NULL, 7, 5, '["ALL"]', '2016-03-31 10:22:59', '2016-03-31 04:52:59'),
(326, NULL, 7, 6, '["ALL","ADD","EDIT","VIEW","DELETE","EXPORT"]', '2016-03-31 10:22:59', '2016-03-31 04:52:59'),
(327, NULL, 7, 7, '["ALL","ADD","EDIT","VIEW","DELETE","EXPORT"]', '2016-03-31 10:22:59', '2016-03-31 04:52:59'),
(329, NULL, 7, 9, '["ALL","ADD","EDIT","VIEW","DELETE","EXPORT"]', '2016-03-31 10:22:59', '2016-03-31 04:52:59'),
(330, NULL, 7, 16, '["ALL","ADD","EDIT","VIEW","DELETE","EXPORT"]', '2016-03-31 10:22:59', '2016-03-31 04:52:59'),
(331, NULL, 7, 10, '["ALL"]', '2016-03-31 10:22:59', '2016-03-31 04:52:59'),
(332, NULL, 7, 11, '["ALL","ADD","EDIT","VIEW","DELETE"]', '2016-03-31 10:22:59', '2016-03-31 04:52:59'),
(333, NULL, 7, 12, '["ALL","ADD","EDIT","VIEW","DELETE"]', '2016-03-31 10:22:59', '2016-03-31 04:52:59'),
(334, NULL, 7, 13, '["ALL","ADD","EDIT","VIEW","DELETE"]', '2016-03-31 10:22:59', '2016-03-31 04:52:59'),
(335, NULL, 7, 14, '["ALL","ADD","EDIT","VIEW","DELETE"]', '2016-03-31 10:22:59', '2016-03-31 04:52:59');

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `status` tinyint(1) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `title`, `content`, `status`, `date_created`, `date_modified`) VALUES
(8, 'Dummy title', '<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n\r\n<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n\r\n<p>&nbsp;</p>\r\n', 0, '2015-12-03 09:49:19', '2016-06-17 13:40:45'),
(11, 'About Us', '<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n\r\n<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n', 1, '2015-12-07 08:35:42', '2016-06-17 13:40:35');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(100) NOT NULL,
  `content` text,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Active =1, Inactive = 0',
  `created_by` int(11) NOT NULL COMMENT 'Primary key of user_master',
  `modified_by` int(11) DEFAULT NULL COMMENT 'Primary key of user_master',
  `date_created` datetime NOT NULL,
  `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`created_by`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `subject`, `content`, `status`, `created_by`, `modified_by`, `date_created`, `date_modified`) VALUES
(8, 'Demo Post 2', 'Demo Post 2 Content', 1, 7, NULL, '2016-03-22 10:20:48', '2016-03-22 04:50:48'),
(9, 'Demo Post 3', 'Demo Post 3 Content', 1, 7, NULL, '2016-03-22 10:20:48', '2016-03-22 04:50:48'),
(10, 'Demo Post 4', 'Demo Post 4 Content', 1, 7, NULL, '2016-03-22 10:20:48', '2016-03-22 04:50:48'),
(11, 'Demo Post 5', 'Demo Post 5 Content', 1, 7, NULL, '2016-03-22 10:20:48', '2016-03-22 04:50:48'),
(12, 'Demo Post 6', 'Demo Post 6 Content', 1, 7, NULL, '2016-03-22 10:20:48', '2016-03-22 04:50:48'),
(13, 'Demo Post 7', '<p>Demo Post 7 Content</p>', 1, 7, 7, '2016-03-22 10:20:48', '2016-04-13 01:45:04'),
(14, 'Demo Post 8', '<p>Demo Post 8 Content</p>\r\n', 1, 7, 7, '2016-03-22 10:20:48', '2016-06-17 12:53:47');

-- --------------------------------------------------------

--
-- Table structure for table `post_tag`
--

CREATE TABLE IF NOT EXISTS `post_tag` (
  `post_id` int(11) NOT NULL COMMENT 'Primary key of posts',
  `tag_id` int(11) NOT NULL COMMENT 'Primary key of tags',
  PRIMARY KEY (`post_id`,`tag_id`),
  KEY `post_id` (`post_id`,`tag_id`),
  KEY `tag_id` (`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `role_master`
--

CREATE TABLE IF NOT EXISTS `role_master` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(255) NOT NULL COMMENT 'role name',
  `description` varchar(2000) DEFAULT NULL COMMENT 'description',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Active =1, Inactive = 0',
  `date_created` datetime NOT NULL COMMENT 'created date',
  `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'modified date',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='user role master table' AUTO_INCREMENT=6 ;

--
-- Dumping data for table `role_master`
--

INSERT INTO `role_master` (`id`, `role_name`, `description`, `status`, `date_created`, `date_modified`) VALUES
(1, 'Super Admin', 'superadmin does have all permission.', 1, '2015-10-06 18:42:04', '2016-05-17 03:52:39'),
(2, 'Admin', 'admin user', 1, '2015-10-07 12:56:57', '2016-03-29 05:23:22'),
(3, 'Manager', 'manager user', 1, '2015-10-07 12:55:29', '2015-10-07 07:25:52'),
(4, 'Operator', 'operator user', 1, '2015-10-07 12:57:59', '2015-10-07 07:28:00'),
(5, 'Auditor', 'Auditor', 1, '2015-10-07 12:58:53', '2016-06-01 06:53:55');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `site_title` varchar(100) NOT NULL,
  `logo_img` varchar(255) NOT NULL,
  `logo_link` varchar(255) NOT NULL,
  `admin_email` varchar(255) NOT NULL,
  `email_setting` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1 = PHP Mail, 2 = SMTP',
  `smtp_host` varchar(255) DEFAULT NULL,
  `smtp_port` int(11) DEFAULT NULL,
  `smtp_username` varchar(255) DEFAULT NULL,
  `smtp_password` varchar(255) DEFAULT NULL,
  `currency_symbol` varchar(20) NOT NULL,
  `currency_code` varchar(5) NOT NULL,
  `discount_percentage` double NOT NULL COMMENT 'discount in percentage',
  `fixed_discount` double NOT NULL COMMENT 'fixed discount',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='website default setting' AUTO_INCREMENT=2 ;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `site_title`, `logo_img`, `logo_link`, `admin_email`, `email_setting`, `smtp_host`, `smtp_port`, `smtp_username`, `smtp_password`, `currency_symbol`, `currency_code`, `discount_percentage`, `fixed_discount`) VALUES
(1, 'CakeProject', 'sample-logo@2x.png', 'https://encrypted-tbn3.gstatic.com/images?q=tbn:ANd9GcT6wIJPlmA6ZFHVrHgLSLneKFf-TcgUgtQ0DYOMm9WGv3jKn4DcLAQztJ4', 'test@gmail.com', 2, 'smtp.cygnet-india.com', 587, 'cygnetmail', 'India!338$', '£', 'GBP', 10, 100);

-- --------------------------------------------------------

--
-- Table structure for table `state_master`
--

CREATE TABLE IF NOT EXISTS `state_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_id` int(11) NOT NULL COMMENT 'Primary key of country_master',
  `state_name` varchar(255) NOT NULL COMMENT 'state name',
  `date_created` datetime NOT NULL COMMENT 'created date',
  `date_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'modified date',
  PRIMARY KEY (`id`),
  KEY `FK_state_master_country_master` (`country_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='state master table' AUTO_INCREMENT=5 ;

--
-- Dumping data for table `state_master`
--

INSERT INTO `state_master` (`id`, `country_id`, `state_name`, `date_created`, `date_modified`) VALUES
(1, 2, 'Gujarat', '2016-03-16 11:58:41', '2016-03-16 06:28:53'),
(4, 2, 'punjab', '2016-03-18 05:45:40', '2016-03-18 00:15:40');

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE IF NOT EXISTS `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag_name` varchar(100) NOT NULL,
  `status` tinyint(1) NOT NULL COMMENT 'Active =1, Inactive = 0',
  `date_created` datetime NOT NULL,
  `date_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `tag_name`, `status`, `date_created`, `date_modified`) VALUES
(1, 'Internete', 1, '2016-03-29 00:00:00', '2016-06-17 13:16:41'),
(2, 'Technician', 0, '2016-06-17 15:30:38', '2016-06-17 13:16:10'),
(3, 'Computer', 1, '2016-06-17 17:22:53', '2016-06-17 13:13:06'),
(4, 'Technical', 1, '2016-06-17 17:26:59', '2016-06-17 13:12:56'),
(5, 'Keyword', 0, '2016-06-17 17:26:59', '2016-06-17 13:19:20'),
(6, 'PHP', 1, '2016-06-17 18:49:06', '2016-06-17 13:19:06');

-- --------------------------------------------------------

--
-- Table structure for table `template`
--

CREATE TABLE IF NOT EXISTS `template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL COMMENT 'title',
  `subject` varchar(255) DEFAULT NULL COMMENT 'subject',
  `type` varchar(255) DEFAULT NULL COMMENT 'type',
  `header` text COMMENT 'header',
  `content` text NOT NULL COMMENT 'content',
  `footer` text COMMENT 'footer',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Default =1, Not Default = 0',
  `date_created` datetime NOT NULL COMMENT 'created date',
  `date_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'modified date',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `template`
--

INSERT INTO `template` (`id`, `title`, `subject`, `type`, `header`, `content`, `footer`, `status`, `date_created`, `date_modified`) VALUES
(1, 'User registration', 'User registration', 'user_registration', '<p>Header</p>', '<p>[first_name]</p>\r\n<p>[last_name]</p>\r\n<p>[user_name]</p>\r\n<p>[email]</p>\r\n<p>[registration_confirmation_link]</p>\r\n<p>[site_url]</p>\r\n<p>[site_title]</p>\r\n<p>[admin_email]</p>\r\n<p>&nbsp;</p>', '<p>Footer</p>', 1, '2016-04-04 06:26:41', '2016-04-04 00:56:41'),
(2, 'Forgot password', 'Forgot password', 'forgot_password', '<p>Header</p>', '<p>[first_name]</p>\r\n<p>[last_name]</p>\r\n<p>[user_name]</p>\r\n<p>[email]</p>\r\n<p>[forgot_password_link]</p>\r\n<p>[site_url]</p>\r\n<p>[site_title]</p>\r\n<p>[admin_email]</p>', '<p>Footer</p>', 1, '2016-04-04 06:27:30', '2016-06-15 09:10:24'),
(3, 'Reset password confirmation', 'Reset password confirmation', 'reset_password_confirmation', '<p>Header</p>', '<p>[first_name]</p>\r\n<p>[last_name]</p>\r\n<p>[user_name]</p>\r\n<p>[email]</p>\r\n<p>[site_url]</p>\r\n<p>[site_title]</p>\r\n<p>[admin_email]</p>\r\n<p>&nbsp;</p>', '<p>Footer</p>', 1, '2016-04-04 06:28:07', '2016-04-04 00:58:07'),
(8, 'Coupon Code', 'Coupon Code', 'discount_code', '<p>Dear [seller],</p>\r\n', '<p>You have got a coupon code for your next sale , use below code to avail <strong>[discount]</strong> on your next sale.&nbsp;</p>\r\n\r\n<p>Coupon Code :&nbsp;<strong>[discount_code]</strong></p>\r\n', '<p>Team&nbsp;[site_title]</p>\r\n', 1, '2016-04-05 11:23:57', '2016-06-16 11:09:10');

-- --------------------------------------------------------

--
-- Table structure for table `user_master`
--

CREATE TABLE IF NOT EXISTS `user_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) NOT NULL COMMENT 'user name',
  `first_name` varchar(255) NOT NULL COMMENT 'user  first name',
  `middle_name` varchar(255) NOT NULL COMMENT 'user  middle name',
  `last_name` varchar(255) NOT NULL COMMENT 'user  last name',
  `password` varchar(255) DEFAULT NULL COMMENT 'password',
  `password_token` varchar(255) DEFAULT NULL COMMENT 'password token ',
  `email` varchar(255) NOT NULL COMMENT 'user email',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Active =1, Inactive = 0',
  `role_id` tinyint(4) NOT NULL COMMENT 'Primary key of role_master',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Deleted = 1, User Not Deleted = 0',
  `date_created` datetime NOT NULL COMMENT 'created date',
  `date_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'modified date',
  PRIMARY KEY (`id`),
  KEY `FK_user_master_role_id` (`role_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='status ->  0 : Inactive , 1 : Active , 2 : Delete' AUTO_INCREMENT=25 ;

--
-- Dumping data for table `user_master`
--

INSERT INTO `user_master` (`id`, `user_name`, `first_name`, `middle_name`, `last_name`, `password`, `password_token`, `email`, `status`, `role_id`, `is_deleted`, `date_created`, `date_modified`) VALUES
(1, 'superuser', 'Sunil', 'u', 'Kumar', '$2a$10$61I9919YB9zch9rp2aDy2eEF5.1n2wPOWZT1OUg4GXpnvrRpdi3dy', NULL, 'skumar@cygnet-infotech.com', 1, 1, 0, '2015-10-06 18:54:03', '2016-05-17 03:49:17'),
(2, 'suboda', 'sachin', 'u', 'boda', 'suboda', NULL, 'mjp@cygnet-infotech.com', 0, 2, 1, '2015-10-07 13:01:12', '2016-05-17 01:23:00'),
(3, 'skumar', 'sandip', 'u', 'joshi', '$2a$10$61I9919YB9zch9rp2aDy2eEF5.1n2wPOWZT1OUg4GXpnvrRpdi3dy', NULL, 'rdmehta@cygnet-infotech.com', 1, 3, 0, '2015-10-07 13:02:57', '2015-11-18 05:39:56'),
(4, 'rsshah', 'ravi', 's', 'shah', 'rsshah', NULL, 'pdshah@cygnet-infotech.com', 1, 3, 0, '2015-10-07 13:03:36', '2016-03-17 23:57:23'),
(5, 'njmakwana', 'nirav', 'j', 'makwana', '$2a$10$61I9919YB9zch9rp2aDy2eEF5.1n2wPOWZT1OUg4GXpnvrRpdi3dy', NULL, 'mkjangir@cygnet-infotech.com', 1, 4, 0, '2015-10-07 13:04:15', '2016-04-19 04:08:55'),
(6, 'kajoshi', 'karan', 'a', 'joshi', '$2a$10$61I9919YB9zch9rp2aDy2eEF5.1n2wPOWZT1OUg4GXpnvrRpdi3dy', NULL, 'ngjani@cygnet-infotech.com', 1, 4, 1, '2015-10-07 13:09:59', '2015-11-16 07:46:03'),
(7, 'admin', 'John', 'm', 'Appleseed', '$2a$10$61I9919YB9zch9rp2aDy2eEF5.1n2wPOWZT1OUg4GXpnvrRpdi3dy', NULL, 'auharshe@cygnet-infotech.com', 1, 1, 0, '2015-10-15 09:33:44', '2016-06-01 13:04:37'),
(15, 'jbhoraniya755', 'jaymit', 'H', 'Bhoraniya', '$2a$10$saaKy.MsKa6cwBQbG/8wVua29BdQfSEwEITof4VfdB.5pIWVtVwIe', NULL, 'jhbhoraniya@cygnet-infotech.com', 1, 5, 0, '2016-04-05 10:59:18', '2016-04-19 07:42:11'),
(19, 'aharshe189', 'aniket', '', 'harshe', NULL, NULL, 'cygnet.auharshe@gmail.com', 1, 1, 0, '2016-04-05 12:54:04', '2016-04-05 07:24:04'),
(20, 'skumar737', 'Sunil', 'Kumar', 'Kumar', NULL, NULL, 'sunil@mailinator.com', 1, 1, 0, '2016-05-17 07:27:36', '2016-05-17 01:57:36'),
(21, 'skumar737', 'Sunil', 'Kumar', 'Kumar', NULL, NULL, 'sunil1@mailinator.com', 1, 1, 0, '2016-05-17 07:30:08', '2016-05-17 02:00:08'),
(22, 'ttest785', 'test', 'test', 'test', NULL, NULL, 'sunil12@mailinator.com', 1, 1, 0, '2016-05-17 08:17:01', '2016-05-17 02:47:01'),
(23, 'ttest785', 'test', 'test', 'test', NULL, NULL, 'sunil123@mailinator.com', 0, 1, 0, '2016-05-17 08:18:50', '2016-05-23 09:18:25'),
(24, 'stest47', 'Sunil', 'TEst', 'TESt', NULL, NULL, 'test@tset.com', 1, 3, 0, '2016-05-17 09:13:39', '2016-06-01 06:40:17');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `city_master`
--
ALTER TABLE `city_master`
  ADD CONSTRAINT `city_master_ibfk_1` FOREIGN KEY (`state_id`) REFERENCES `state_master` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `city_master_ibfk_2` FOREIGN KEY (`country_id`) REFERENCES `country_master` (`id`);

--
-- Constraints for table `module_permission`
--
ALTER TABLE `module_permission`
  ADD CONSTRAINT `module_permission_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `role_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `module_permission_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `module_permission_ibfk_3` FOREIGN KEY (`module_id`) REFERENCES `module` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `post_tag`
--
ALTER TABLE `post_tag`
  ADD CONSTRAINT `post_tag_ibfk_1` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `post_tag_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `state_master`
--
ALTER TABLE `state_master`
  ADD CONSTRAINT `state_master_ibfk_1` FOREIGN KEY (`country_id`) REFERENCES `city_master` (`country_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
