-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 07, 2017 at 06:54 PM
-- Server version: 10.1.21-MariaDB
-- PHP Version: 7.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `scoring`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `add_participant` (IN `in_team_id` INT(11), IN `in_participant_firstName` VARCHAR(45), IN `in_participant_lastName` VARCHAR(45), IN `in_participant_email` VARCHAR(45), IN `in_participant_contactNo` VARCHAR(45))  BEGIN
	INSERT INTO participants(team_id, participant_firstName, participant_lastName, participant_email, participant_contactNo)
    VALUES(in_team_id, in_participant_firstName, in_participant_lastname, in_participant_email, in_participant_contactNo);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `add_project` (IN `in_team_id` INT, IN `in_event_id` INT, IN `in_project_name` VARCHAR(45), IN `in_project_type` VARCHAR(45), IN `in_short_desc` VARCHAR(160), IN `in_long_desc` VARCHAR(800))  BEGIN
	INSERT INTO project(
        team_id,
        event_id,
        project_name,
        project_type,
        short_desc,
        long_desc
    )
    VALUES(
        in_team_id,
        in_event_id,
        in_project_name,
        in_project_type,
        in_short_desc,
        in_long_desc
    );
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `app_scoresheet` (IN `in_project_id` INT)  BEGIN
	SET @sql = NULL;

	SELECT
		GROUP_CONCAT(DISTINCT
			CONCAT(
				'MAX(IF(criteria_desc = ''', criteria_desc,''', score, NULL)) AS ''', criteria_desc ,"'"
			)
		) INTO @sql
	FROM (
		(SELECT c.criteria_desc
		FROM
			criteria c,
			scores s
		WHERE s.project_id = in_project_id
		AND c.criteria_id = s.criteria_id)
		AS event_criteria
	);
    
	SET @sql = (
		CONCAT('
			SELECT judge_id, judge_name,', @sql ,'
			FROM
				(
					SELECT j.judge_id, j.judge_name, c.criteria_desc, s.score
					FROM
						judge j,
						criteria c,
						scores s
					WHERE
						s.project_id = ', in_project_id ,'
					AND c.criteria_id = s.criteria_id
					AND j.judge_id = s.judge_id
					ORDER BY judge_name, criteria_desc

				) AS raw_scores
			GROUP BY
				judge_id
		')
	);

	PREPARE stmt FROM @sql;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `app_total_scoresheet` (IN `in_event_id` INT)  BEGIN
	SET @sql = NULL;

	SELECT
		GROUP_CONCAT(DISTINCT
			CONCAT(
				'MAX(IF(team_name = ''', team_name,''', score, NULL)) AS ''', team_name ,"'"
			)
		) INTO @sql
	FROM (
		(
        SELECT
			t.team_name
        FROM
			project p,
            team t
		WHERE
			p.event_id = in_event_id
            AND t.team_id = p.team_id
		) AS event_teams
	);
    
	SET @sql = (
		CONCAT('
			SELECT judge_name AS Judge,', @sql ,'
			FROM
				(
					SELECT 
						j.judge_id, j.judge_name, t.team_name, (SELECT SUM(score) FROM scores WHERE project_id = p.project_id AND judge_id = j.judge_id) AS Score
					FROM
						judge j,
						team t,
						scores s,
						event e,
						project p
					WHERE
						e.event_id = ',in_event_id,'
							AND p.event_id = e.event_id
							AND j.event_id = e.event_id
							AND s.judge_id = j.judge_id
							AND s.project_id = p.project_id
							AND t.team_id = p.team_id
						GROUP BY team_name, judge_name
						ORDER BY j.judge_id, t.team_name
				) AS total_scores
			GROUP BY
				total_scores.judge_id
		')
	);

	PREPARE stmt FROM @sql;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `create_criteria` (IN `in_event_id` INT(11), IN `in_criteria_desc` VARCHAR(45), IN `in_criteria_weight` INT(11))  BEGIN
	INSERT INTO criteria(event_id, criteria_desc, criteria_weight)
		VALUES(in_event_id, in_criteria_desc, in_criteria_weight);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `create_event` (IN `in_event_name` VARCHAR(45), IN `in_event_host` VARCHAR(45), IN `in_event_desc` VARCHAR(160))  BEGIN
	INSERT INTO event(event_name, event_host, event_desc, event_date)
		VALUES(in_event_name, in_event_host, in_event_desc, NOW());
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `create_judge` (IN `in_event_id` INT(11), IN `in_judge_name` VARCHAR(45))  BEGIN
	INSERT INTO judge(event_id, judge_name)
		VALUES(in_event_id, in_judge_name);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_average_score` (IN `in_team_id` INT)  BEGIN
	SELECT SUM(score)
    FROM scores
    WHERE team_id = in_team_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `give_remarks` (IN `in_judge_id` INT, IN `in_project_id` INT, IN `in_remarks` VARCHAR(800))  BEGIN
	INSERT INTO remarks(judge_id,project_id,remarks) VALUES(in_judge_id, in_project_id, in_remarks);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `give_score` (IN `in_judge_id` INT(11), IN `in_criteria_id` INT(11), IN `in_project_id` INT(11), IN `in_score` INT(11))  BEGIN
	INSERT INTO scores(judge_id, criteria_id, project_id, score)
		VALUES(in_judge_id, in_criteria_id, in_project_id, in_score);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `judge_scoresheet` (IN `in_judge_id` INT)  BEGIN
	SET @sql = NULL;

	SELECT 
    GROUP_CONCAT(DISTINCT CONCAT('MAX(IF(criteria_desc = \'',
                criteria_desc,
                '\', score, NULL)) AS \'',
                criteria_desc,
                '\''))
INTO @sql FROM
    ((SELECT 
        c.criteria_desc
    FROM
        criteria c, judge j, event e
    WHERE
        j.judge_id = in_judge_id
            AND e.event_id = j.event_id
            AND c.event_id = j.event_id) AS event_criteria);
    
	SET @sql = (
		CONCAT('
			SELECT project_id, project_name, team_id, team_name,', @sql ,', (SELECT SUM(score) FROM scores WHERE judge_id = ',in_judge_id,' AND project_id = raw_scores.project_id) AS Total
			FROM
				(
					SELECT DISTINCT
						p.project_id,
						p.project_name,
						t.team_id,
						t.team_name,
						c.criteria_desc,
						s.score
					FROM
						scores s,
						criteria c,
						judge j,
						project p,
						team t
					WHERE
						s.judge_id = ', in_judge_id ,'
							AND p.project_id = s.project_id
							AND t.team_id = p.team_id
							AND c.event_id = p.event_id
							AND s.criteria_id = c.criteria_id
					ORDER BY t.team_name
				) AS raw_scores
			GROUP BY
				project_id
			ORDER BY
				team_name
		')
	);

	PREPARE stmt FROM @sql;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `register_team` (IN `in_team_name` VARCHAR(45))  BEGIN
    INSERT INTO team(
        team_name
    )
    VALUES(
        in_team_name
    );
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_remarks` (IN `in_judge_id` INT, IN `in_project_id` INT, IN `in_remarks` VARCHAR(800))  BEGIN
	UPDATE remarks SET remark = in_remarks WHERE judge_id = in_judge_id AND project_id = in_project_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_score` (IN `in_score_id` INT, IN `in_score` INT)  BEGIN
	UPDATE scores SET score = in_score WHERE score_id = in_score_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `view_criteria` (IN `in_event_id` INT(11))  BEGIN
	SELECT criteria_id, criteria_desc, criteria_longdesc, criteria_weight
    FROM criteria
    WHERE event_id = in_event_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `view_judges` (IN `in_event_id` INT(11))  BEGIN
	SELECT judge_id, judge_name
    FROM judge
    WHERE event_id = in_event_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `view_members` (IN `in_team_id` INT(11))  BEGIN
	SELECT participant_id, participant_firstName, participant_lastName, participant_email, participant_contactNo FROM participants
	WHERE team_id = in_team_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `view_score` (IN `in_project_id` INT, IN `in_judge_id` INT, IN `in_criteria_id` INT)  BEGIN
	SELECT
		score_id,
		score
	FROM
		scores
        
	WHERE project_id = in_project_id
			AND judge_id = in_judge_id
            AND criteria_id = in_criteria_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `view_teams` (IN `in_event_id` INT(11))  BEGIN
	SELECT t.team_id, t.team_name, p.project_id, p.project_name, p.project_type, p.short_desc, p.long_desc
    FROM team t,
		project p
    WHERE 
		p.event_id = in_event_id
    AND t.team_id = p.team_id
    ORDER BY t.team_name;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `criteria`
--

CREATE TABLE `criteria` (
  `criteria_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `criteria_desc` varchar(160) DEFAULT NULL,
  `criteria_weight` int(11) DEFAULT NULL,
  `criteria_longdesc` varchar(800) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `criteria`
--

INSERT INTO `criteria` (`criteria_id`, `event_id`, `criteria_desc`, `criteria_weight`, `criteria_longdesc`) VALUES
(1, 1, 'Scalability and Impact', 25, 'To what extent can the project be replicated or adapted by the bank and  different sectors Can this app be used by its target audience?'),
(2, 1, 'Execution and Design', 25, 'Do they have a prototype? How functional is the technical demo? Design matters! Does the  project easy to use?'),
(3, 1, 'Business Model', 25, 'How can they make it a successful  business? What customer segments they have defined and who are the early adopters? What are the (potential) revenue / cost models?'),
(4, 1, 'Project Validation', 25, 'Did they test the market Are they solving real problems? What is the value preposition?');

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE `event` (
  `event_id` int(11) NOT NULL,
  `event_name` varchar(45) DEFAULT NULL,
  `event_host` varchar(45) DEFAULT NULL,
  `event_desc` varchar(800) DEFAULT NULL,
  `event_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `event`
--

INSERT INTO `event` (`event_id`, `event_name`, `event_host`, `event_desc`, `event_date`) VALUES
(1, 'U:HAC Ultimate Pitching ', 'Unionbank', NULL, '2017-04-28 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `judge`
--

CREATE TABLE `judge` (
  `judge_id` int(11) NOT NULL,
  `event_id` int(11) DEFAULT NULL,
  `judge_name` varchar(45) DEFAULT 'Anonymous'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `judge`
--

INSERT INTO `judge` (`judge_id`, `event_id`, `judge_name`) VALUES
(1, 1, 'Judge 1'),
(2, 1, 'Judge 2'),
(3, 1, 'Judge 3'),
(4, 1, 'Judge 4'),
(5, 1, 'Judge 5'),
(6, 1, 'Judge 6'),
(7, 1, 'Judge 7'),
(8, 1, 'Judge 8'),
(9, 1, 'Judge 9'),
(10, 1, 'Judge 10'),
(11, 1, 'Judge 11'),
(12, 1, 'Judge 12'),
(13, 1, 'Judge 13'),
(14, 1, 'Judge 14'),
(15, 1, 'Judge 15');

-- --------------------------------------------------------

--
-- Table structure for table `participants`
--

CREATE TABLE `participants` (
  `participant_id` int(10) UNSIGNED NOT NULL,
  `team_id` int(11) DEFAULT NULL,
  `participant_firstName` varchar(45) DEFAULT NULL,
  `participant_lastName` varchar(45) DEFAULT NULL,
  `participant_email` varchar(45) DEFAULT NULL,
  `participant_contactNo` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `participants`
--

INSERT INTO `participants` (`participant_id`, `team_id`, `participant_firstName`, `participant_lastName`, `participant_email`, `participant_contactNo`) VALUES
(1, 1, 'Chris', 'Militante', NULL, NULL),
(2, 1, 'Patrick', 'Woogue', NULL, NULL),
(3, 1, 'Gabriel Andrew', 'Pineda', NULL, NULL),
(4, 3, 'Jelo Nicole', 'Javier', NULL, NULL),
(5, 3, 'Jayson', 'Abilar', NULL, NULL),
(6, 3, 'Carlo', 'Jumagdao', NULL, NULL),
(7, 3, 'John Paul', 'Escala', NULL, NULL),
(8, 3, 'Rafael', 'Desuyo', NULL, NULL),
(9, 2, 'Delfin Joseph', 'Baylon', NULL, NULL),
(10, 2, 'Jay', 'Jaboneta', NULL, NULL),
(11, 2, 'Daniel', 'Bueno', NULL, NULL),
(12, 2, 'John Lyndon', 'Claro', NULL, NULL),
(13, 2, 'Ian Dave', 'Invierno', NULL, NULL),
(14, 2, 'Enrico Benedict', 'Quinones', '', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `project`
--

CREATE TABLE `project` (
  `project_id` int(11) NOT NULL,
  `event_id` int(11) DEFAULT NULL,
  `team_id` int(11) DEFAULT NULL,
  `project_name` varchar(45) DEFAULT NULL,
  `project_type` varchar(45) DEFAULT NULL,
  `short_desc` varchar(160) DEFAULT NULL,
  `long_desc` varchar(800) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `project`
--

INSERT INTO `project` (`project_id`, `event_id`, `team_id`, `project_name`, `project_type`, `short_desc`, `long_desc`) VALUES
(1, 1, 1, 'LaurelEye', 'Web Application', 'An Uber of Tutorials', 'LaurelEye is an web-based platform where people can find other people willing to educate them in a vast array  of topics from academic subjects to arts and crafts.'),
(2, 1, 2, 'uBid', 'Mobile Application', 'Virtual Reality Listing app for Foreclosed Properties', 'The app focuses on Real Estate and Cars. The app can be deployed via major App stores (Apple App Store, Google Playstore). App also includes a VR walk-through of a Virtual Bank to familiarize customers of the different things you can do in a branch and also to promote the bank\'s ongoing promos or marketing campaigns. The property viewer will contain a \"dibs/I\'m Interested\" button that submits the user\'s contact info, making it easier for the bank to contact new leads. This can help the bank increase its market reach faster. As for the users, this decreases guess work and logistics by cutting the need to go to the actual site just by experiencing the interactive photo-spheres and real-time 3D spaces that eventually speed up buyers\'  decision to buy or not.'),
(3, 1, 3, 'Hooleh', 'Web and Mobile Application', 'Amobile recording of traffic violations with banking payment. ', 'This app maintains the history of traffic violation records of a certain driver that has a account on the app while also recording the traffic enforcer info who arrested him/her. This app also provides digital banking for the driver that has been arrested. He/she can pay to Unionbank for his violation/s.');

-- --------------------------------------------------------

--
-- Table structure for table `remarks`
--

CREATE TABLE `remarks` (
  `remarks_id` int(11) NOT NULL,
  `judge_id` int(11) DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL,
  `remark` varchar(800) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `scores`
--

CREATE TABLE `scores` (
  `score_id` int(11) NOT NULL,
  `judge_id` int(11) DEFAULT NULL,
  `criteria_id` int(11) DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL,
  `score` double DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `scores`
--

INSERT INTO `scores` (`score_id`, `judge_id`, `criteria_id`, `project_id`, `score`) VALUES
(1, 1, 1, 1, 0),
(2, 1, 2, 1, 0),
(3, 1, 3, 1, 0),
(4, 1, 4, 1, 0),
(5, 2, 1, 1, 25),
(6, 2, 2, 1, 23),
(7, 2, 3, 1, 20),
(8, 2, 4, 1, 23),
(9, 3, 1, 1, 25),
(10, 3, 2, 1, 23),
(11, 3, 3, 1, 24),
(12, 3, 4, 1, 21),
(13, 4, 1, 1, 23),
(14, 4, 2, 1, 23),
(15, 4, 3, 1, 23),
(16, 4, 4, 1, 25),
(17, 5, 1, 1, 22),
(18, 5, 2, 1, 21),
(19, 5, 3, 1, 22),
(20, 5, 4, 1, 22),
(21, 6, 1, 1, 20),
(22, 6, 2, 1, 20),
(23, 6, 3, 1, 20),
(24, 6, 4, 1, 15),
(25, 7, 1, 1, 0),
(26, 7, 2, 1, 0),
(27, 7, 3, 1, 0),
(28, 7, 4, 1, 0),
(29, 8, 1, 1, 18),
(30, 8, 2, 1, 21),
(31, 8, 3, 1, 19),
(32, 8, 4, 1, 18),
(33, 9, 1, 1, 0),
(34, 9, 2, 1, 0),
(35, 9, 3, 1, 0),
(36, 9, 4, 1, 0),
(37, 10, 1, 1, 18),
(38, 10, 2, 1, 25),
(39, 10, 3, 1, 15),
(40, 10, 4, 1, 25),
(41, 11, 1, 1, 15),
(42, 11, 2, 1, 10),
(43, 11, 3, 1, 12),
(44, 11, 4, 1, 14),
(45, 12, 1, 1, 0),
(46, 12, 2, 1, 0),
(47, 12, 3, 1, 0),
(48, 12, 4, 1, 0),
(49, 13, 1, 1, 15),
(50, 13, 2, 1, 20),
(51, 13, 3, 1, 10),
(52, 13, 4, 1, 10),
(53, 14, 1, 1, 23),
(54, 14, 2, 1, 24),
(55, 14, 3, 1, 22),
(56, 14, 4, 1, 23),
(57, 15, 1, 1, 20),
(58, 15, 2, 1, 25),
(59, 15, 3, 1, 15),
(60, 15, 4, 1, 15),
(61, 1, 1, 2, 0),
(62, 1, 2, 2, 0),
(63, 1, 3, 2, 0),
(64, 1, 4, 2, 0),
(65, 2, 1, 2, 25),
(66, 2, 2, 2, 20),
(67, 2, 3, 2, 25),
(68, 2, 4, 2, 20),
(69, 3, 1, 2, 25),
(70, 3, 2, 2, 20),
(71, 3, 3, 2, 22),
(72, 3, 4, 2, 22),
(73, 4, 1, 2, 20),
(74, 4, 2, 2, 23),
(75, 4, 3, 2, 23),
(76, 4, 4, 2, 23),
(77, 5, 1, 2, 20),
(78, 5, 2, 2, 22),
(79, 5, 3, 2, 25),
(80, 5, 4, 2, 20),
(81, 6, 1, 2, 15),
(82, 6, 2, 2, 15),
(83, 6, 3, 2, 10),
(84, 6, 4, 2, 10),
(85, 7, 1, 2, 20),
(86, 7, 2, 2, 20),
(87, 7, 3, 2, 15),
(88, 7, 4, 2, 25),
(89, 8, 1, 2, 18),
(90, 8, 2, 2, 23),
(91, 8, 3, 2, 21),
(92, 8, 4, 2, 18),
(93, 9, 1, 2, 0),
(94, 9, 2, 2, 0),
(95, 9, 3, 2, 0),
(96, 9, 4, 2, 0),
(97, 10, 1, 2, 20),
(98, 10, 2, 2, 20),
(99, 10, 3, 2, 18),
(100, 10, 4, 2, 20),
(101, 11, 1, 2, 0),
(102, 11, 2, 2, 18),
(103, 11, 3, 2, 19),
(104, 11, 4, 2, 17),
(105, 12, 1, 2, 0),
(106, 12, 2, 2, 0),
(107, 12, 3, 2, 0),
(108, 12, 4, 2, 0),
(109, 13, 1, 2, 20),
(110, 13, 2, 2, 20),
(111, 13, 3, 2, 10),
(112, 13, 4, 2, 15),
(113, 14, 1, 2, 23),
(114, 14, 2, 2, 21),
(115, 14, 3, 2, 20),
(116, 14, 4, 2, 21),
(117, 15, 1, 2, 25),
(118, 15, 2, 2, 15),
(119, 15, 3, 2, 20),
(120, 15, 4, 2, 15),
(121, 1, 1, 3, 0),
(122, 1, 2, 3, 0),
(123, 1, 3, 3, 0),
(124, 1, 4, 3, 0),
(125, 2, 1, 3, 19),
(126, 2, 2, 3, 20),
(127, 2, 3, 3, 18),
(128, 2, 4, 3, 15),
(129, 3, 1, 3, 22),
(130, 3, 2, 3, 20),
(131, 3, 3, 3, 22),
(132, 3, 4, 3, 22),
(133, 4, 1, 3, 25),
(134, 4, 2, 3, 25),
(135, 4, 3, 3, 25),
(136, 4, 4, 3, 25),
(137, 5, 1, 3, 18),
(138, 5, 2, 3, 20),
(139, 5, 3, 3, 15),
(140, 5, 4, 3, 20),
(141, 6, 1, 3, 20),
(142, 6, 2, 3, 15),
(143, 6, 3, 3, 15),
(144, 6, 4, 3, 15),
(145, 7, 1, 3, 20),
(146, 7, 2, 3, 25),
(147, 7, 3, 3, 15),
(148, 7, 4, 3, 22),
(149, 8, 1, 3, 18),
(150, 8, 2, 3, 22),
(151, 8, 3, 3, 20),
(152, 8, 4, 3, 22),
(153, 9, 1, 3, 0),
(154, 9, 2, 3, 0),
(155, 9, 3, 3, 0),
(156, 9, 4, 3, 0),
(157, 10, 1, 3, 20),
(158, 10, 2, 3, 20),
(159, 10, 3, 3, 18),
(160, 10, 4, 3, 20),
(161, 11, 1, 3, 20),
(162, 11, 2, 3, 18),
(163, 11, 3, 3, 21),
(164, 11, 4, 3, 20),
(165, 12, 1, 3, 0),
(166, 12, 2, 3, 0),
(167, 12, 3, 3, 0),
(168, 12, 4, 3, 0),
(169, 13, 1, 3, 20),
(170, 13, 2, 3, 25),
(171, 13, 3, 3, 20),
(172, 13, 4, 3, 20),
(173, 14, 1, 3, 23),
(174, 14, 2, 3, 24),
(175, 14, 3, 3, 23),
(176, 14, 4, 3, 23),
(177, 15, 1, 3, 20),
(178, 15, 2, 3, 25),
(179, 15, 3, 3, 25),
(180, 15, 4, 3, 20);

-- --------------------------------------------------------

--
-- Table structure for table `team`
--

CREATE TABLE `team` (
  `team_id` int(11) NOT NULL,
  `team_name` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `team`
--

INSERT INTO `team` (`team_id`, `team_name`) VALUES
(1, 'LaurelEye'),
(2, 'Chibot'),
(3, 'Intern');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `criteria`
--
ALTER TABLE `criteria`
  ADD PRIMARY KEY (`criteria_id`),
  ADD KEY `event_idx` (`event_id`);

--
-- Indexes for table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `judge`
--
ALTER TABLE `judge`
  ADD PRIMARY KEY (`judge_id`),
  ADD KEY `event_idx` (`event_id`);

--
-- Indexes for table `participants`
--
ALTER TABLE `participants`
  ADD PRIMARY KEY (`participant_id`),
  ADD KEY `participant_team_idx` (`team_id`);

--
-- Indexes for table `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`project_id`),
  ADD KEY `project_team_idx` (`team_id`),
  ADD KEY `project_event_idx` (`event_id`);

--
-- Indexes for table `remarks`
--
ALTER TABLE `remarks`
  ADD PRIMARY KEY (`remarks_id`),
  ADD KEY `remarks_judge_idx` (`judge_id`),
  ADD KEY `remarks_project_idx` (`project_id`);

--
-- Indexes for table `scores`
--
ALTER TABLE `scores`
  ADD PRIMARY KEY (`score_id`),
  ADD KEY `scores_judge_idx` (`judge_id`),
  ADD KEY `scores_criteria_idx` (`criteria_id`),
  ADD KEY `scores_project_idx` (`project_id`);

--
-- Indexes for table `team`
--
ALTER TABLE `team`
  ADD PRIMARY KEY (`team_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `criteria`
--
ALTER TABLE `criteria`
  MODIFY `criteria_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `event`
--
ALTER TABLE `event`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `judge`
--
ALTER TABLE `judge`
  MODIFY `judge_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `participants`
--
ALTER TABLE `participants`
  MODIFY `participant_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `project`
--
ALTER TABLE `project`
  MODIFY `project_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `remarks`
--
ALTER TABLE `remarks`
  MODIFY `remarks_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `scores`
--
ALTER TABLE `scores`
  MODIFY `score_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=181;
--
-- AUTO_INCREMENT for table `team`
--
ALTER TABLE `team`
  MODIFY `team_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `criteria`
--
ALTER TABLE `criteria`
  ADD CONSTRAINT `criteria_event` FOREIGN KEY (`event_id`) REFERENCES `event` (`event_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `judge`
--
ALTER TABLE `judge`
  ADD CONSTRAINT `judge_event` FOREIGN KEY (`event_id`) REFERENCES `event` (`event_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `participants`
--
ALTER TABLE `participants`
  ADD CONSTRAINT `participant_team` FOREIGN KEY (`team_id`) REFERENCES `team` (`team_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `project`
--
ALTER TABLE `project`
  ADD CONSTRAINT `project_event` FOREIGN KEY (`event_id`) REFERENCES `event` (`event_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `project_team` FOREIGN KEY (`team_id`) REFERENCES `team` (`team_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `remarks`
--
ALTER TABLE `remarks`
  ADD CONSTRAINT `remarks_judge` FOREIGN KEY (`judge_id`) REFERENCES `judge` (`judge_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `remarks_project` FOREIGN KEY (`project_id`) REFERENCES `project` (`project_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `scores`
--
ALTER TABLE `scores`
  ADD CONSTRAINT `scores_criteria` FOREIGN KEY (`criteria_id`) REFERENCES `criteria` (`criteria_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `scores_judge` FOREIGN KEY (`judge_id`) REFERENCES `judge` (`judge_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `scores_project` FOREIGN KEY (`project_id`) REFERENCES `project` (`project_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
