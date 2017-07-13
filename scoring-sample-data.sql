-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 13, 2017 at 04:34 PM
-- Server version: 5.6.26-log
-- PHP Version: 7.0.4

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
	SELECT LAST_INSERT_ID() AS id;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `add_project` (IN `in_team_id` INT, IN `in_event_id` INT, IN `in_project_name` VARCHAR(45), IN `in_project_type` VARCHAR(45), IN `in_short_desc` VARCHAR(160), IN `in_long_desc` VARCHAR(800), IN `in_pitch_order` INT(10))  BEGIN
	INSERT INTO project(
        team_id,
        event_id,
        project_name,
        project_type,
        short_desc,
        long_desc,
        pitch_order
    )
    VALUES(
        in_team_id,
        in_event_id,
        in_project_name,
        in_project_type,
        in_short_desc,
        in_long_desc,
        in_pitch_order
    );
	SELECT LAST_INSERT_ID() as id;
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `create_criteria` (IN `in_event_id` INT(11), IN `in_criteria_desc` VARCHAR(45), IN `in_criteria_weight` INT(11), IN `in_criteria_longdesc` VARCHAR(800))  BEGIN
	INSERT INTO criteria(event_id, criteria_desc, criteria_weight, criteria_longdesc)
		VALUES(in_event_id, in_criteria_desc, in_criteria_weight, in_criteria_longdesc);
	SELECT LAST_INSERT_ID() as id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `create_event` (IN `in_event_name` VARCHAR(45), IN `in_event_host` VARCHAR(45), IN `in_event_desc` VARCHAR(160))  BEGIN
	INSERT INTO event(event_name, event_host, event_desc, event_date)
		VALUES(in_event_name, in_event_host, in_event_desc, NOW());
	SELECT LAST_INSERT_ID() AS id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `create_judge` (IN `in_event_id` INT(11), IN `in_judge_name` VARCHAR(45))  BEGIN
	INSERT INTO judge(event_id, judge_name)
		VALUES(in_event_id, in_judge_name);
    SELECT LAST_INSERT_ID() as id;
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
	SELECT LAST_INSERT_ID() as id;
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
	SELECT LAST_INSERT_ID() AS id;
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
	SELECT t.team_id, t.team_name, p.project_id, p.project_name, p.project_type, p.short_desc, p.long_desc, p.pitch_order
    FROM team t,
		project p
    WHERE 
		p.event_id = in_event_id
    AND t.team_id = p.team_id
    ORDER BY p.pitch_order;
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
(1, 1, 'Scalability and Impact', 25, 'To what extent can the project be replicated or adapted by the bank and  different sectors Can this app be used by its target audience? '),
(2, 1, 'Execution and Design', 25, 'Do they have a prototype? How functional is the technical demo? Design matters! Does the  project easy to use?'),
(3, 1, 'Business Model', 25, 'How can they make it a successful  business? What customer segments they have defined and who are the early adopters? What are the (potential) revenue / cost models?'),
(4, 1, 'Project Validation', 25, 'Did they test the market Are they solving real problems? What is the value preposition');

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
(1, 'U:HAC Cebu', 'Unionbank', 'U:Hac is another project of the Bank to find the most creative and innovative solutions in the digital world.', '2017-07-15 00:00:00');

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
(1, 1, 'Red Periabras'),
(2, 1, 'Rihanna'),
(3, 1, 'Rivermaya'),
(4, 1, 'A rocket To The moon'),
(5, 1, 'One Direction'),
(6, 1, 'Rachel Platten'),
(7, 1, 'Silent Sanctuary'),
(8, 1, 'Charlie puth'),
(9, 1, 'LayLow'),
(10, 1, 'Justin Timberlake'),
(11, 1, 'Paramore'),
(12, 1, 'Bruno mars');

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
  `long_desc` varchar(800) DEFAULT NULL,
  `pitch_order` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `project`
--

INSERT INTO `project` (`project_id`, `event_id`, `team_id`, `project_name`, `project_type`, `short_desc`, `long_desc`, `pitch_order`) VALUES
(1, 1, 1, 'PLDT Bot', 'Messenger Bot', 'Messenger based Robot', 'Messenger bot to serve as gateway of PLDT receive concerns from people that has messenger.', 48),
(2, 1, 2, 'Scoring System', 'Web App', 'Scoring System for Hackathons.', 'Judges may really find a hard time to judge thru papers and pencils. Why not make it with their phone? easy right?? haha', 140),
(3, 1, 3, 'Red Wizards Platform', 'Web App', 'Events and everything you needed is here.', 'How about a system to record events that you go into to create a profile hehe', 37),
(4, 1, 4, 'Pub', 'Web App', 'Unionbank social events platform', 'Let your co-employees where you will be today, tomorrow, and the next day. Invite everyone and enjoy every moment with them.', 7);

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
(1, 1, 3, 4, 25),
(2, 1, 4, 4, 0),
(3, 1, 1, 4, 25),
(4, 1, 2, 4, 25),
(5, 1, 1, 4, 25),
(6, 1, 2, 4, 25),
(7, 1, 4, 4, 25),
(8, 1, 3, 4, 25),
(9, 1, 1, 3, 25),
(10, 1, 3, 3, 25),
(11, 1, 2, 3, 25),
(12, 1, 4, 3, 25),
(13, 1, 2, 1, 25),
(14, 1, 4, 1, 0),
(15, 1, 1, 1, 25),
(16, 1, 3, 1, 25),
(17, 1, 1, 2, 25),
(18, 1, 3, 2, 0),
(19, 1, 2, 2, 0),
(20, 1, 4, 2, 25),
(21, 2, 1, 4, 20),
(22, 2, 2, 4, 10),
(23, 2, 3, 4, 25),
(24, 2, 1, 4, 5),
(25, 2, 2, 4, 10),
(26, 2, 4, 4, 25),
(27, 2, 3, 4, 25),
(28, 2, 1, 3, 20),
(29, 2, 2, 3, 25),
(30, 2, 1, 1, 2),
(31, 2, 2, 1, 20),
(32, 2, 3, 1, 20),
(33, 2, 4, 1, 20),
(34, 2, 1, 2, 20),
(35, 2, 3, 2, 12),
(36, 2, 2, 2, 2),
(37, 2, 4, 2, 22),
(38, 3, 1, 4, 20),
(39, 3, 2, 4, 20),
(40, 3, 3, 4, 20),
(41, 3, 4, 4, 20),
(42, 3, 1, 3, 20),
(43, 3, 2, 3, 20),
(44, 3, 3, 3, 20),
(45, 3, 4, 3, 20),
(46, 3, 3, 2, 25),
(47, 3, 2, 2, 2),
(48, 3, 1, 2, 25),
(49, 3, 4, 2, 25),
(50, 3, 1, 1, 16),
(51, 3, 2, 1, 25),
(52, 3, 3, 1, 20),
(53, 3, 4, 1, 20),
(54, 4, 1, 4, 25),
(55, 4, 3, 4, 25),
(56, 4, 2, 4, 25),
(57, 4, 4, 4, 25),
(58, 4, 2, 3, 25),
(59, 4, 1, 3, 25),
(60, 4, 4, 3, 25),
(61, 4, 3, 3, 25),
(62, 4, 1, 1, 25),
(63, 4, 2, 1, 25),
(64, 4, 3, 1, 25),
(65, 4, 4, 1, 25),
(66, 4, 1, 2, 5),
(67, 4, 2, 2, 25),
(68, 4, 3, 2, 25),
(69, 4, 4, 2, 25),
(70, 5, 1, 4, 20),
(71, 5, 2, 4, 20),
(72, 5, 3, 4, 20),
(73, 5, 4, 4, 20),
(74, 5, 3, 3, 25),
(75, 5, 4, 3, 25),
(76, 5, 1, 3, 25),
(77, 5, 2, 3, 25),
(78, 5, 2, 1, 25),
(79, 5, 1, 1, 25),
(80, 5, 3, 1, 25),
(81, 5, 4, 1, 25),
(82, 5, 1, 2, 25),
(83, 5, 3, 2, 25),
(84, 5, 2, 2, 25),
(85, 5, 4, 2, 25),
(86, 6, 1, 4, 25),
(87, 6, 2, 4, 25),
(88, 6, 3, 4, 25),
(89, 6, 4, 4, 25),
(90, 6, 1, 3, 25),
(91, 6, 2, 3, 25),
(92, 6, 3, 3, 25),
(93, 6, 4, 3, 5),
(94, 6, 1, 1, 25),
(95, 6, 3, 1, 25),
(96, 6, 2, 1, 25),
(97, 6, 4, 1, 25),
(98, 6, 1, 1, 15),
(99, 6, 2, 1, 25),
(100, 6, 3, 1, 25),
(101, 6, 4, 1, 25),
(102, 6, 1, 1, 15),
(103, 6, 2, 1, 25),
(104, 6, 3, 1, 25),
(105, 6, 4, 1, 25),
(106, 6, 1, 2, 20),
(107, 6, 2, 2, 20),
(108, 6, 4, 2, 20),
(109, 6, 3, 2, 20),
(110, 7, 1, 4, 20),
(111, 7, 3, 4, 20),
(112, 7, 2, 4, 20),
(113, 7, 4, 4, 20),
(114, 7, 1, 3, 25),
(115, 7, 4, 3, 20),
(116, 7, 3, 3, 20),
(117, 7, 2, 3, 20),
(118, 7, 1, 1, 25),
(119, 7, 2, 1, 25),
(120, 7, 3, 1, 25),
(121, 7, 4, 1, 25),
(122, 7, 1, 2, 10),
(123, 7, 2, 2, 10),
(124, 7, 3, 2, 10),
(125, 7, 4, 2, 10),
(126, 8, 1, 4, 25),
(127, 8, 2, 4, 25),
(128, 8, 3, 4, 25),
(129, 8, 4, 4, 25),
(130, 8, 1, 3, 25),
(131, 8, 2, 3, 25),
(132, 8, 3, 3, 25),
(133, 8, 4, 3, 25),
(134, 8, 1, 1, 2),
(135, 8, 2, 1, 2),
(136, 8, 3, 1, 2),
(137, 8, 4, 1, 2),
(138, 8, 1, 2, 20),
(139, 8, 2, 2, 10),
(140, 8, 3, 2, 10),
(141, 8, 4, 2, 10),
(142, 9, 1, 4, 20),
(143, 9, 2, 4, 20),
(144, 9, 3, 4, 20),
(145, 9, 4, 4, 20),
(146, 9, 1, 3, 10),
(147, 9, 2, 3, 10),
(148, 9, 3, 3, 10),
(149, 9, 4, 3, 10),
(150, 9, 1, 1, 1),
(151, 9, 2, 1, 10),
(152, 9, 3, 1, 10),
(153, 9, 4, 1, 10),
(154, 9, 1, 2, 2),
(155, 9, 2, 2, 2),
(156, 9, 3, 2, 20),
(157, 9, 4, 2, 20),
(158, 10, 1, 4, 20),
(159, 10, 2, 4, 20),
(160, 10, 3, 4, 20),
(161, 10, 4, 4, 20),
(162, 10, 1, 3, 20),
(163, 10, 2, 3, 20),
(164, 10, 3, 3, 20),
(165, 10, 4, 3, 20),
(166, 10, 1, 1, 20),
(167, 10, 4, 1, 20),
(168, 10, 2, 1, 20),
(169, 10, 3, 1, 20),
(170, 10, 1, 2, 5),
(171, 10, 2, 2, 15),
(172, 10, 3, 2, 15),
(173, 10, 4, 2, 15),
(174, 11, 1, 4, 10),
(175, 11, 2, 4, 10),
(176, 11, 3, 4, 10),
(177, 11, 4, 4, 10),
(178, 11, 1, 3, 10),
(179, 11, 2, 3, 10),
(180, 11, 3, 3, 20),
(181, 11, 4, 3, 20),
(182, 11, 1, 1, 2),
(183, 11, 2, 1, 20),
(184, 11, 3, 1, 20),
(185, 11, 4, 1, 20),
(186, 11, 1, 2, 1),
(187, 11, 2, 2, 5),
(188, 11, 3, 2, 5),
(189, 11, 4, 2, 5),
(190, 12, 1, 4, 20),
(191, 12, 2, 4, 15),
(192, 12, 3, 4, 15),
(193, 12, 4, 4, 15),
(194, 12, 1, 3, 20),
(195, 12, 2, 3, 20),
(196, 12, 3, 3, 20),
(197, 12, 4, 3, 20);

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
(1, 'MambaCodes'),
(2, 'Canley Codes'),
(3, 'Harambeats'),
(4, 'Team Part-Time');

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
  MODIFY `judge_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `participants`
--
ALTER TABLE `participants`
  MODIFY `participant_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `project`
--
ALTER TABLE `project`
  MODIFY `project_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `remarks`
--
ALTER TABLE `remarks`
  MODIFY `remarks_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `scores`
--
ALTER TABLE `scores`
  MODIFY `score_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=198;
--
-- AUTO_INCREMENT for table `team`
--
ALTER TABLE `team`
  MODIFY `team_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
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
