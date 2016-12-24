-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 30, 2016 at 11:35 AM
-- Server version: 5.5.52-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `empty_portal`
--

-- --------------------------------------------------------

--
-- Table structure for table `apps`
--

CREATE TABLE IF NOT EXISTS `apps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `classname` varchar(255) NOT NULL,
  `order` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `appfilepath` varchar(255) NOT NULL,
  `appiconfilepath` varchar(255) NOT NULL,
  `type` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;

--
-- Dumping data for table `apps`
--

INSERT INTO `apps` (`id`, `name`, `description`, `classname`, `order`, `is_active`, `appfilepath`, `appiconfilepath`, `type`) VALUES
(1, 'Home', 'Home screen', 'timeline_app', 1, 1, 'timeline_app_1411717661.php', 'HOME_1411717247.png', 0),
(2, 'File Upload', 'Upload an Item to the DB', 'fileupload_app', 1, 1, 'fileupload_app_1409037763.php', '', 1),
(3, 'Back to', 'Return to home screen', 'back_to', 2, 1, 'back_to_1408796116.php', 'back_1408797469.png', 0),
(5, 'Model Viewer', 'Model viewing application', 'modelviewer_app', 1, 1, 'modelviewer_app_1408716236.php', '', 2),
(7, 'Project History', 'An list of activities that have taken place on the project in Chronological order', 'ticket_app', 2, 1, 'ticket_app_1408783806.php', '', 0),
(8, 'File Request', 'The file request app ', 'request_file', 2, 1, 'request_file_1409042159.php', '', 1),
(9, 'Help', 'Wiki used to document Application details', 'help_app', 5, 1, 'help_app_1409554135.php', '', 0),
(10, 'Feedback', 'Provide feedback to Admin', 'feedback_app', 6, 1, 'feedback_app_1409568194.php', '', 0),
(11, 'Messaging', 'Real time chat application', 'messaging_app', 3, 1, 'messaging_app_1409579496.php', 'app_1409579503.png', 0),
(12, 'Site Images', 'Display project photographs', 'sitephotograph_app', 5, 1, 'sitephotograph_app_1409636305.php', 'app_1409636315.png', 2),
(13, 'Marketing Media', 'Content for Marketing team', 'render_app', 6, 1, 'render_app_1409657992.php', 'app_1409658003.png', 2),
(16, 'File Manager', 'Location of all files on a project', 'filemanager_app', 3, 1, 'filemanager_app_1410415875.php', '', 1),
(17, 'My Account', 'User account properties', 'account_app', 4, 1, 'account_app_1411560992.php', 'app_1411561003.png', 0),
(18, 'Issues', 'Discussion board for latest issues', 'issueviewer_app', 4, 1, 'issueviewer_app_1412192289.php', 'issueviewer_app_1412600545.png', 2),
(19, '3D Printing', 'Form to submit details of 3D print request to Admin', 'print3d_request_app', 1, 1, 'print3d_request_app_1412614639.php', 'print3diconw_1412871157.png', 3),
(20, 'Project Team', 'Collection of users assigned to project', 'project_team_app', 4, 1, 'project_team_app_1412334454.php', 'project_team_app_1412787060.png', 1),
(21, 'Quantity Takeoff', 'Form to submit request for Quantity Take Off details to admin team', 'qto_app', 3, 1, 'qto_app_1413119466.php', 'qto_app_1413119509.png', 2),
(22, 'Gantt Chart', 'Browse project timeline', 'gantt_chart_app', 2, 1, 'gantt_chart_app_1413197976.php', '', 2);

-- --------------------------------------------------------

--
-- Table structure for table `case`
--

CREATE TABLE IF NOT EXISTS `case` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT 'case name eg: project assign',
  `text` text NOT NULL COMMENT 'case text eg: %1s assgned to the project %2s',
  `description` text NOT NULL COMMENT 'descriptive example of the project',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `case`
--

INSERT INTO `case` (`id`, `name`, `text`, `description`) VALUES
(1, 'USER ASSIGNED TO PROJECT', '<li data-notif="[+notif_id+]">\r\n  <div class="image"><div><img src="%1$s" alt="" /></div></div>\r\n  <div class="left_con_del">\r\n    <h2>%2$s</h2>\r\n    <div class="clear"></div>\r\n    <p>Has been assigned to the project <strong>%3$s</strong></p>\r\n    <span class="date">[+datetime+]</span>\r\n  </div>\r\n</li>', '$1 = user profilepic\n\n$2 = user name\n\n$3 = ProjectName'),
(2, 'USER IS REVOKED FROM A PROJECT', '<li data-notif="[+notif_id+]">\r\n  <div class="image"><div><img src="%1$s" alt="" /></div></div>\r\n  <div class="left_con_del">\r\n    <h2>%2$s</h2>\r\n    <div class="clear"></div>\r\n    <p>Has been revoked from the project <strong>%3$s</strong></p>\r\n    <span class="date">[+datetime+]</span>\r\n  </div>\r\n</li>', '$1 = user profilepic\r\n\r\n$2 = user name\r\n\r\n$3 = projectname'),
(3, 'FILE IS UPLOADED', '<li data-notif="[+notif_id+]">\r\n\r\n  <div class="image"><div><img src="%1$s" alt="" /></div></div>                                     \r\n  <div class="left_con_del">\r\n    <h2>%2$s</h2>\r\n    <div class="clear"></div>\r\n    <p>Has uploaded a file named <strong>%3$s</strong> to the project <strong>%4$s</strong> of type <strong>%5$s</strong></p>\r\n    <span class="date">[+datetime+]</span>\r\n    <div class="actions">\r\n      <a id="pid-[+pid+]" href="[+filelink+]" class="for_admin_ajax set_project blue-button action">View File</a>\r\n      <a href="[+ticketlink+]" class="for_admin_ajax set_project blue-button action" id="pid-[+pid+]">View Ticket</a>\r\n    </div>\r\n  </div>\r\n                                        \r\n</li>', '$1 = user profilepic\n\n$2 = user name\n\n$3 = File name\n\n$4 = Project name\n\n$5 = Request doc type'),
(4, 'FILE IS REQUESTED', '<li data-notif="[+notif_id+]">\r\n  <div class="image"><div><img src="%1$s" alt="" /></div></div>\r\n  <div class="left_con_del">\r\n    <h2>%2$s</h2>\r\n    <div class="clear"></div>\r\n    <p>Has requested a file of the project <strong>%3$s</strong> of type <strong>%4$s</strong>, and the extension is <strong>%5$s</strong>.</p>\r\n    <span class="date">[+datetime+]</span>\r\n    <div class="actions">\r\n      <a href="[+ticketlink+]" class="for_admin_ajax set_project blue-button action" id="pid-[+pid+]">View Ticket</a>\r\n    </div>\r\n  </div>\r\n</li>', '$1 = user profilepic\n\n$2 = user name\n\n$3 = Project name\n\n$4 = Docuemnt type\n\n$5 = Extension'),
(5, 'Issue Created', '<li data-notif="[+notif_id+]">\r\n  <div class="image" style="background-image: url(%5$s);"></div>\r\n  <div class="left_con_del">\r\n    <h2>%2$s</h2>\r\n    <div class="clear"></div>\r\n    <p>An issue has been detected!<span></p>\r\n    <span class="date">[+datetime+]</span>\r\n    <div class="actions">\r\n      <a href="[+ticketlink+]" class="for_admin_ajax set_project blue-button action" id="pid-[+pid+]">View Ticket</a>\r\n    </div>\r\n  </div>\r\n</li>', '');

-- --------------------------------------------------------

--
-- Table structure for table `chat_history`
--

CREATE TABLE IF NOT EXISTS `chat_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `content` text NOT NULL,
  `status` int(11) NOT NULL COMMENT '1= default,2=recevier_notified,3=receiver_viewd',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `doctype`
--

CREATE TABLE IF NOT EXISTS `doctype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `order` int(11) NOT NULL,
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=29 ;

--
-- Dumping data for table `doctype`
--

INSERT INTO `doctype` (`id`, `name`, `description`, `is_active`, `order`, `parent_id`) VALUES
(1, 'Drawing', 'Drawing', 1, 1, 0),
(2, 'Documentation', 'Documentation', 1, 2, 0),
(3, 'Gantt Chart', 'Gantt Chart', 1, 3, 0),
(4, 'Quantity Takeoff', 'Quantity Takeoff', 1, 4, 0),
(5, 'Other', 'Other', 1, 5, 0),
(6, 'Elevation', 'Elevation', 1, 1, 1),
(7, 'Detail', 'Detail', 1, 2, 1),
(8, 'Section', 'Section', 1, 3, 1),
(9, 'Perspective', 'Perspective', 1, 4, 1),
(10, 'Concept', 'Concept', 1, 5, 1),
(11, 'General Civils', 'General Civils', 1, 1, 2),
(12, 'Mechanical', 'Mechanical', 1, 2, 2),
(13, 'Miscellaneous', 'Miscellaneous', 1, 3, 2),
(14, 'Permanent Way', 'Permanent Way', 1, 4, 2),
(15, 'Property', 'Property', 1, 5, 2),
(16, 'Roads. Highways. Car Park', 'Roads. Highways. Car Park', 1, 6, 2),
(17, 'Safety', 'Safety', 1, 7, 2),
(18, 'Signalling', 'Signalling', 1, 8, 2),
(19, 'Telecoms', 'Telecoms', 1, 9, 2),
(20, 'Track', 'Track', 1, 10, 2),
(21, 'Gantt Chart', 'Gantt Chart', 1, 1, 3),
(22, 'Quantity Takoff', 'Quantity Takoff', 1, 1, 4),
(23, 'Other', 'Other', 1, 1, 5),
(24, 'Plan', 'Plan', 1, 6, 1),
(25, 'Electrification & Plant', 'Electrification & Plant', 1, 11, 2),
(26, 'Digital Models', 'Digital Models', 1, 6, 0),
(27, '3d Model', '3D Model', 1, 1, 26),
(28, 'Environmental', 'Environmental', 1, 12, 2);

-- --------------------------------------------------------

--
-- Table structure for table `group`
--

CREATE TABLE IF NOT EXISTS `group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `creator_id` int(11) NOT NULL,
  `created_date` int(11) NOT NULL,
  `modified_date` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `group_members`
--

CREATE TABLE IF NOT EXISTS `group_members` (
  `group_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `joinin_date` int(11) NOT NULL,
  `leaving date` int(11) NOT NULL,
  `continue` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `group_members`
--

INSERT INTO `group_members` (`group_id`, `member_id`, `joinin_date`, `leaving date`, `continue`) VALUES
(1, 1, 1410342537, 0, 1),
(1, 2, 1410342537, 0, 1),
(2, 1, 1410342615, 0, 1),
(2, 2, 1410342616, 0, 1),
(3, 1, 1410346286, 0, 1),
(3, 5, 1410346286, 0, 1),
(3, 2, 1410346286, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `issues`
--

CREATE TABLE IF NOT EXISTS `issues` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `path` varchar(255) NOT NULL,
  `original_filename` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `project` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE IF NOT EXISTS `notes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `body` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `notes`
--

INSERT INTO `notes` (`id`, `user_id`, `body`) VALUES
(1, 9, 'This note pad can be used to store notes forever on your user account. The next time you log in, this will be here! The notes are saved across every project, so if you''ve written yourself an important reminder, it''ll be the first thing you see!');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `related_id` text NOT NULL,
  `case_id` int(11) NOT NULL,
  `date_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `project_id`, `related_id`, `case_id`, `date_time`) VALUES
(1, 4, '{"uid":"9","p_id":"4"}', 1, 1480504735);

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE IF NOT EXISTS `projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `embedcode` text NOT NULL,
  `active` tinyint(1) NOT NULL,
  `startdate` int(11) NOT NULL,
  `enddate` int(11) NOT NULL,
  `company` tinyint(1) NOT NULL,
  `bimsync_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `name`, `embedcode`, `active`, `startdate`, `enddate`, `company`, `bimsync_id`) VALUES
(4, 'Empty project', '', 1, 0, 0, 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `request_doc`
--

CREATE TABLE IF NOT EXISTS `request_doc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` text NOT NULL,
  `type` int(11) NOT NULL,
  `extension` varchar(15) NOT NULL,
  `time` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `site_photographs`
--

CREATE TABLE IF NOT EXISTS `site_photographs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `upload_date` int(11) NOT NULL,
  `path` varchar(255) NOT NULL,
  `original_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `site_renders`
--

CREATE TABLE IF NOT EXISTS `site_renders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `upload_date` int(11) NOT NULL,
  `path` varchar(255) NOT NULL,
  `original_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ticket`
--

CREATE TABLE IF NOT EXISTS `ticket` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `itemid` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `user_type` int(11) NOT NULL,
  `ticket_for` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `parent_ticket_id` int(11) NOT NULL COMMENT 'If this ticket was created in response ofanother ticket',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='The table create the tocket for any instance' AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_for`
--

CREATE TABLE IF NOT EXISTS `ticket_for` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket_for` text NOT NULL,
  `is_active` int(11) NOT NULL,
  `is_file` tinyint(1) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `ticket_for`
--

INSERT INTO `ticket_for` (`id`, `ticket_for`, `is_active`, `is_file`) VALUES
(1, 'File Upload', 1, 0),
(2, 'File request', 1, 0),
(3, 'Issue created', 1, 0),
(4, 'File download', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `ticket_log`
--

CREATE TABLE IF NOT EXISTS `ticket_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `modifier_id` int(11) NOT NULL,
  `ticket_status_id` int(11) NOT NULL,
  `modifier_role` int(11) NOT NULL,
  `modify_time` int(11) NOT NULL,
  `log_status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_status`
--

CREATE TABLE IF NOT EXISTS `ticket_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `desc` text NOT NULL,
  `visible` tinyint(1) unsigned DEFAULT '0',
  `only_user_role` int(10) unsigned DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `ticket_status`
--

INSERT INTO `ticket_status` (`id`, `name`, `desc`, `visible`, `only_user_role`) VALUES
(1, 'File is requested', 'File is requested, when ever a file is requested then the ticket status will be this', 0, 0),
(2, 'File is uploaded', 'When ever a file is just uploaded then it status will be that.It may be uploaded by any body', 0, 0),
(3, 'File verified by admin', 'File verified by admin', 1, 1),
(5, 'BIM model updated', 'BIM module updated', 1, 1),
(6, 'BIM model update accepted', 'Update accepted', 1, 1),
(7, 'BIM model update rejected', 'BIM model update rejected', 1, 1),
(8, 'BIM model update verified', 'BIM model update verified', 1, 1),
(9, 'File verified by user', 'File verified by admin', 1, 1),
(10, 'Issue resolved', 'Issue resolved', 1, 1),
(11, 'Issue identified', 'Issue Identified', 0, 0),
(12, 'File is downloaded', 'Created when a file is downloaded', 1, 1),
(13, 'Further comment', 'Further comment', 1, 0),
(14, 'Model Check in progress', 'Model Check in progress', 1, 1),
(15, 'Model Check complete', 'Model Check complete', 1, 1),
(16, 'FTAO Users/Disciplines', 'FTAO Users/Disciplines', 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `uploaddoc`
--

CREATE TABLE IF NOT EXISTS `uploaddoc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `path` text NOT NULL,
  `userid` int(11) NOT NULL,
  `projectid` int(11) NOT NULL,
  `doctypeid` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `uploadtime` int(11) NOT NULL,
  `requestid` int(11) NOT NULL,
  `details` text NOT NULL,
  `document_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `uname` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `discipline` varchar(100) NOT NULL,
  `company` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `role` tinyint(1) NOT NULL,
  `joiningdate` int(11) NOT NULL,
  `activationdate` int(11) NOT NULL,
  `profilepic` varchar(255) NOT NULL,
  `activity_status` int(11) NOT NULL COMMENT '0 = loggedout,1= logedin,2=inactive',
  `last_login_time` int(11) NOT NULL,
  `last_logout_time` int(11) NOT NULL,
  `password_reset` char(32) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `uname`, `password`, `email`, `discipline`, `company`, `phone`, `status`, `role`, `joiningdate`, `activationdate`, `profilepic`, `activity_status`, `last_login_time`, `last_logout_time`, `password_reset`) VALUES
(8, 'Admin Ted', '', '2e9ec317e197819358fbc43afca7d837', 'admin@yourcompany.com', 'Digital Platform Team', 'YourCompany', '07788822200', 3, 1, 1412605432, 1413550145, '8_1480503365~!~dash_profile_pic~!~_thumb.png', 0, 1480505479, 1480505516, ''),
(9, 'Joe Bloggs User', '', '2e9ec317e197819358fbc43afca7d837', 'user@yourcompany.com', 'Asset management team', 'MyCompany', '07788833300', 3, 2, 1480504678, 1480504735, '', 1, 1480505666, 1480505456, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users_feedback`
--

CREATE TABLE IF NOT EXISTS `users_feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `date` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_assigned_projects`
--

CREATE TABLE IF NOT EXISTS `user_assigned_projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `projectid` int(11) NOT NULL,
  `assigndate` int(11) NOT NULL,
  `continue` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22 ;

--
-- Dumping data for table `user_assigned_projects`
--

INSERT INTO `user_assigned_projects` (`id`, `userid`, `projectid`, `assigndate`, `continue`) VALUES
(20, 8, 4, 1413550139, 1),
(21, 9, 4, 1480504735, 1);

-- --------------------------------------------------------

--
-- Table structure for table `wiki_pages`
--

CREATE TABLE IF NOT EXISTS `wiki_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `is_active` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `wiki_pages`
--

INSERT INTO `wiki_pages` (`id`, `page_id`, `content`, `is_active`) VALUES
(1, 1, '<div width=800px>\n<p style="text-align:justify">&nbsp;</p>\n\n<p style="text-align:center"><strong>Welcome to your BIMportal. </strong></p>\n\n<p style="text-align:justify">This portal will act as your central project management system for all the projects you are involved with. It creates a comprehensive and adaptable space for all your project files, including various ways to interact with BIM data and your project partners. Before we go rushing into it, let&#39;s get started with how to use, and navigate through the portal.</p>\n\n<p style="text-align:justify"><em>During the development stages of the BIMportal, access to the portal is gained through the Key. The Key is a USB device which contains the BIMportal Browser. Please see the readme loaded onto the USB device for further information.</em></p>\n\n<p style="text-align:justify"><strong>Login</strong></p>\n\n<p style="text-align:justify">The first thing you will see when on the BIMportal is the login screen. You will be familiar with such screens from other account based websites.</p>\n\n<p style="text-align:justify"><strong>Registration</strong></p>\n\n<p style="text-align:justify">Before you can log in, you need to register yourself as a BIMportal user. Click on the &#39;register&#39; button to go to the registration form. Fill in your details and submit your registration. At this point, an email is sent directly to BIMscript notifying them of your wish to register.</p>\n\n<p style="text-align:justify">BIMscript will activate your account and assign you to the necessary projects. Once activated and assigned, you are free to log in and begin work.</p>\n\n<p style="text-align:justify">Please note that the details you enter on this screen will be shared with other colleagues who are working on the same projects as you are.</p>\n\n<p style="text-align:justify"><strong>Email notifications</strong></p>\n\n<p style="text-align:justify">BIMscript are guardians of the BIMportal. We are here to maintain and develop the federated BIM model, output the exports onto the portal and assist and work with the BIMportal users. We ensure our services remain of the highest level and so offer a personal approach with interaction with the portals users. Currently, whilst the BIMportal is in development stage, email is the most efficient way for notifications to happen.</p>\n\n<p style="text-align:justify"><strong>BIMportal Layout</strong></p>\n\n<p style="text-align:justify">After logging in, you arrive at the portal. The portal has a comprehensive dashboard styled layout which makes projects easy to navigate and project data easy to view. The app panel is on the left of the screen. The app viewer is the window to the right of the app panel. There is a header containing the company credentials and &#39;breadcrumbs&#39; which are a quick way to see where you are in the site map. On the right hand side of the header, the project name is displayed. In the footer, we can see a quick link to this help guide, should you be using an app which you are unused to.</p>\n\n<p style="text-align:justify"><strong>Project Selection</strong></p>\n\n<p style="text-align:justify">The first page you come to upon signing in, is the Project Selection screen. Here, you can select the project you wish to go in to from the selection on the app panel. In the app viewer, you will find a timeline for all the projects you are part of. This timeline is like a news feed containing updates that are happening across each project. This gives a quick overview of the latest update for every project before you choose one to go into.</p>\n\n<p style="text-align:justify">Once you have selected the project you would like to go in to, the next page you see will be the Project Dashboard.</p>\n\n<p style="text-align:justify"><strong>Project Dashboard</strong></p>\n\n<p style="text-align:justify">Initially the Project Dashboard looks very familiar to the Project Selection screen. The layout is the same, with the app panel on the left and app viewer to the right of that. The header and footer is retained. Project selection has been replaced by app selection, however. These apps are tools that you can use to view project particulars. Apps are grouped into types;</p>\n\n<ul>\n	<li>\n	<p style="text-align:justify"><strong>Core Apps</strong></p>\n	</li>\n	<li>\n	<p style="text-align:justify">These are the base apps built into the framework of the portal. These apps often focus on collaboration, resolution or account tools.</p>\n	</li>\n	<li>\n	<p style="text-align:justify"><strong>Project Data Apps</strong></p>\n	</li>\n	<li>\n	<p style="text-align:justify">These apps are file management and team data tools.</p>\n	</li>\n	<li>\n	<p style="text-align:justify"><strong>BIM Apps</strong></p>\n	</li>\n	<li>\n	<p style="text-align:justify">These apps are the Building Information Modelling apps. These are the most useful apps for exploring the federated model and the exported BIM outputs.</p>\n	</li>\n	<li>\n	<p style="text-align:justify"><strong>BIMscript Technologies Apps</strong></p>\n	</li>\n	<li>\n	<p style="text-align:justify">These apps are extra technologies that are offered by BIMscript. For this &#39;proof of concept&#39; development stage portal, they are simply request forms to open a discussion with BIMscript regarding what you would like to achieve.</p>\n	</li>\n</ul>\n\n<p style="text-align:justify">We recommend the first port of call once you&#39;re inside the Project Dashboard, is to review the information in the My Account app, and set your profile picture. This is important as this information will be portrayed to other users of the BIMportal.</p>\n\n<p style="text-align:justify"><strong>Below are these apps explained in greater detail. You will become more familiar with them as you use the site, however should any further help be required that isn&#39;t on this help documentation, feel free to open up a live chat with Webmaster, which is BIMscript, or submit some feedback.</strong></p>\n\n<p style="text-align:justify"><strong>Core Apps</strong></p>\n\n<p style="text-align:justify"><strong>Home (news feed/timeline) &ndash; </strong>The project homepage contains all the immediate information you may need to know about your project. As with the Project Selection screen, there is a news feed which publishes every update on the project in chronological order so you can easily see the most recent changes. To the right of the news feed, there is a weather widget, showing you the weather on site at the moment, a map of the site, and a notepad. The notepad is there for you to write any notes that you may need to remember for next time you log on to the BIMportal. These notes are saved across all projects and are not project specific.</p>\n\n<p style="text-align:justify"><strong>Support Tickets &ndash;</strong> The ticket system is the way for you and your colleagues to keep track of any specific developments. Tickets are created for events that may require a discussion, resolution or just that may need to be monitored. The following apps create tickets: Upload an Item, Request an item, Issue Viewer, <em>Site images and snagging</em>. Tickets are assigned a status, with different statuses available to assign by the Webmaster, users or automatically by the portal. You can add a comment to any ticket. This is how issues and developments get discussed and resolved. Each ticket is assigned a unique Ticket ID so that they can be referred to in any discussion.</p>\n\n<p style="text-align:justify"><strong>Message Centre / Live Chat &ndash; </strong>Another way to discuss any aspect of the project is through the messaging app. This consists of two modules; the live chat module and the Message Centre module. The live chat is just like any other instant messaging service. You can see who is available to chat to within your project, check their online status (red = offline, amber = inactive for more than 10 minutes, green = active) and send them instant messages. The Webmaster is always available for chat. All the messages you have sent to and received from other users are available to review in the Message Centre. This is a log of all your chats, with dates and times for each message attached. You can also see the date and time on messages in the live chat window by hovering over the message with your cursor.</p>\n\n<p style="text-align:justify"><strong>My Account &ndash; </strong>This app is where you can change your account settings and see the details your colleague can see. Here you can change your name, phone number and password. You can also set your profile picture here</p>\n\n<p style="text-align:justify"><strong>Help &ndash; </strong>This is where you are reading this guide!</p>\n\n<p style="text-align:justify"><strong>Feedback &ndash; </strong>Here is where you can submit feedback directly to BIMscript regarding your experience with the BIMportal. Whilst the BIMportal is in the development stages, we encourage the submission of all comments, suggestions, complaints or compliments by you through this app. This is a vital tool for BIMscript to continually improve and tune our services through the portal.</p>\n\n<p style="text-align:justify"><strong>Project Data Apps</strong></p>\n\n<p style="text-align:justify"><strong>Upload an Item &ndash;</strong> This is where you can put any documentation, drawings, models, or other files onto the BIMportal. Any item you decide to upload, or &#39;check in&#39;, here will be organised into a structured directory on our server, and the item will be listed in the File Manager app. Any item uploaded automatically generates a support ticket. This ticket means that items uploaded can be tracked to a user and specifically discussed.</p>\n\n<p style="text-align:justify"><strong>Request an Item &ndash;</strong> This app is where you can request an item from the federated BIM model. If you cannot find the item elsewhere on the site, or the item does not exist yet (for example, taking a drawing from the 3D model), use this form to directly request BIMscript to provide it. Submission of the request sends BIMscript a specific request notification. Once the request has been fulfilled, the requested item will be uploaded to the File Manager, and you will be notified.</p>\n\n<p style="text-align:justify"><strong>File Manager &ndash; </strong>Here you will find a list of items that each project is composed of. The list appears as a table which is easily sortable by clicking the column headings. The items can be searched for any keyword, date or detail attached to the item. Any item of the following file formats can be previewed online by clicking the preview button. This opens the item in fullscreen and allows you to view the item without downloading it. Clicking download will directly download the item to your local PC. Downloading items automatically generates a ticket so that all downloads can be tracked. Every item will have an &#39;upload item&#39; ticket for them, at the point they were checked in to the BIMportal, you can see this ticket by clicking &#39;view ticket&#39; on the far right of the table.</p>\n\n<p style="text-align:justify"><strong>Project Team &ndash;</strong> The Project Team app is where you can see the credentials of the other members involved in the project. Here you will find their details for reference.</p>\n\n<p style="text-align:justify"><strong>BIM Apps</strong></p>\n\n<p style="text-align:justify"><strong>Model Viewer &ndash; </strong>Here you can see the collection of 3D digital models that build up the complete model for the project.</p>\n\n<p style="text-align:justify">Selecting the model version.</p>\n\n<p style="text-align:justify">On the top left there are two dropdown menus. The first is the model type you are viewing. This can be an architectural, electrical, sanitation, structural or ventilation model. The drop down to the right is the revision. Each revision has the date and time of upload so you can be sure as to what the latest version is.</p>\n\n<p style="text-align:justify">Showing and hiding components.</p>\n\n<p style="text-align:justify">Below the drop down menus, you will see two &#39;eye&#39; icons, the first is &#39;show&#39; and the second is &#39;hide&#39;. What these do is show and hide selected components from the model. Sometimes it is very helpful to hide components to see others more clearly.</p>\n\n<p style="text-align:justify">The components list and component properties window.</p>\n\n<p style="text-align:justify">On the left hand side of the app viewer is the component list. This is a complete list of all the components within the selected model. Selecting a component from this list will highlight it on the model, and vice versa. When you select a component, its properties are displayed on the right hand side of the app viewer. You can select multiple objects by holding Ctrl and left clicking with the mouse.</p>\n\n<p style="text-align:justify">Camera Actions</p>\n\n<p style="text-align:justify">Clicking and holding the left mouse button anywhere in the app viewer allows for orbiting the model. This can rotate the camera around the model, at the location you have clicked. Clicking and holding the right mouse button allows for panning the camera. This can be used to slide the camera left to right or up to down. Finally, zooming in and out of the model is achieved by using the scroll wheel.</p>\n\n<p style="text-align:justify"><strong>Gantt Chart &ndash; </strong>Clicking on this app brings you to a sortable and searchable Gantt chart for the entire project. The chart is built and exported from the federated BIM model and uploaded by BIMscript.</p>\n\n<p style="text-align:justify">Revision Selection.</p>\n\n<p style="text-align:justify">Any updates to the project timeline will require a further Gantt chart to be uploaded. Each chart is given a version number, so you can see which is the latest and most accurate version. To select a revision, select the date from the drop down menu on the top left and click &#39;select revision&#39;.</p>\n\n<p style="text-align:justify">Searching.</p>\n\n<p style="text-align:justify">There are many ways to search through the timeline, including different zoom levels; days and months, different display options; full day, office hours (9am &ndash; 5pm) and full week. You can also enter specific dates to view between.</p>\n\n<p style="text-align:justify">Tasks.</p>\n\n<p style="text-align:justify">The tasks are displayed with the task list on the left and the timeline on the right. The tasks are expandable and collapsable based on the parent tasks, so you are able to hide or show groups of tasks. Clicking on a task brings that tasks bar chart into view on the right. This way you can easily navigate through tasks to see the start and end date.</p>\n\n<p style="text-align:justify">Planned and Actual dates</p>\n\n<p style="text-align:justify">There are two sets of dates taken from the BIM model, the planned dates and the actual dates. Planned dates are the designated start and end dates for any task. These are displayed for every task as light blue bars with a border. The actual dates are the dates which the task actually started and actually finished. This is displayed by the planned dates bar filling up with a darker blue. The traffic light system is there to quickly see what tasks are complete (green), what tasks are begun and ongoing (amber) and what tasks are yet to begin (red).</p>\n\n<p style="text-align:justify"><strong>Quantity Takeoff &ndash;</strong> This app displays a Quantity Takeoff exported directly from the BIM model.</p>\n\n<p style="text-align:justify"><strong>Issue Viewer &ndash; </strong>This app displays a table of issues found in any revision of the BIM model. Clash detection and model checking are undertaken by BIMscript and the results are published through this app. Each issue found automatically generates a ticket so that the issue can be discussed and resolved.</p>\n\n<p style="text-align:justify"><strong>Site Images &amp; Snagging &ndash; </strong>Any images that you have of the site which you may want to share with other users can be uploaded here. <em>These can be photographs of general updates to the project, or snags or other issues that may need to be shared and resolved. The app displays them as a chronological gallery, with attached comments.</em></p>\n\n<p style="text-align:justify"><strong>Renders &ndash; </strong>Here you will find all the marketing renders and animations from the project. These can be concept images through to production renders produced through our service and taken directly from the BIM model. Renders are displayed as a gallery and clicking an image opens it in a lightbox.</p>\n\n</div>\n', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
