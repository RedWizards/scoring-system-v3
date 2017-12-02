-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 02, 2017 at 11:07 AM
-- Server version: 10.1.25-MariaDB
-- PHP Version: 7.1.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: scoring-backup
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
-- Table structure for table criteria
--

CREATE TABLE criteria (
  criteria_id int(11) NOT NULL,
  event_id int(11) NOT NULL,
  criteria_desc varchar(160) DEFAULT NULL,
  criteria_weight int(11) DEFAULT NULL,
  criteria_longdesc varchar(800) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE `event` (
  event_id int(11) NOT NULL,
  event_name varchar(45) DEFAULT NULL,
  event_host varchar(45) DEFAULT NULL,
  event_desc varchar(800) DEFAULT NULL,
  event_date datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `event`
--

INSERT INTO `event` (event_id, event_name, event_host, event_desc, event_date) VALUES
(1, 'UHAC Cebu', 'Unionbank', 'Another project of the Bank to find the most creative and innovative solutions in the digital world.', '2017-07-16 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table judge
--

CREATE TABLE judge (
  judge_id int(11) NOT NULL,
  event_id int(11) DEFAULT NULL,
  judge_name varchar(45) DEFAULT 'Anonymous'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table participants
--

CREATE TABLE participants (
  participant_id int(10) UNSIGNED NOT NULL,
  team_id int(11) DEFAULT NULL,
  participant_firstName varchar(45) DEFAULT NULL,
  participant_lastName varchar(45) DEFAULT NULL,
  participant_email varchar(45) DEFAULT NULL,
  participant_contactNo varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table project
--

CREATE TABLE project (
  project_id int(11) NOT NULL,
  event_id int(11) DEFAULT NULL,
  team_id int(11) DEFAULT NULL,
  project_name varchar(45) DEFAULT NULL,
  project_type varchar(45) DEFAULT NULL,
  short_desc varchar(160) DEFAULT NULL,
  long_desc varchar(800) DEFAULT NULL,
  pitch_order int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table remarks
--

CREATE TABLE remarks (
  remarks_id int(11) NOT NULL,
  judge_id int(11) DEFAULT NULL,
  project_id int(11) DEFAULT NULL,
  remark varchar(800) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table scores
--

CREATE TABLE scores (
  score_id int(11) NOT NULL,
  judge_id int(11) DEFAULT NULL,
  criteria_id int(11) DEFAULT NULL,
  project_id int(11) DEFAULT NULL,
  score double DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table team
--

CREATE TABLE team (
  team_id int(11) NOT NULL,
  team_name varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table criteria
--
ALTER TABLE criteria
  ADD PRIMARY KEY (criteria_id),
  ADD KEY event_idx (event_id);

--
-- Indexes for table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (event_id);

--
-- Indexes for table judge
--
ALTER TABLE judge
  ADD PRIMARY KEY (judge_id),
  ADD KEY event_idx (event_id);

--
-- Indexes for table participants
--
ALTER TABLE participants
  ADD PRIMARY KEY (participant_id),
  ADD KEY participant_team_idx (team_id);

--
-- Indexes for table project
--
ALTER TABLE project
  ADD PRIMARY KEY (project_id),
  ADD KEY project_team_idx (team_id),
  ADD KEY project_event_idx (event_id);

--
-- Indexes for table remarks
--
ALTER TABLE remarks
  ADD PRIMARY KEY (remarks_id),
  ADD KEY remarks_judge_idx (judge_id),
  ADD KEY remarks_project_idx (project_id);

--
-- Indexes for table scores
--
ALTER TABLE scores
  ADD PRIMARY KEY (score_id),
  ADD KEY scores_judge_idx (judge_id),
  ADD KEY scores_criteria_idx (criteria_id),
  ADD KEY scores_project_idx (project_id);

--
-- Indexes for table team
--
ALTER TABLE team
  ADD PRIMARY KEY (team_id);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table criteria
--
ALTER TABLE criteria
  MODIFY criteria_id int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `event`
--
ALTER TABLE `event`
  MODIFY event_id int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table judge
--
ALTER TABLE judge
  MODIFY judge_id int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table participants
--
ALTER TABLE participants
  MODIFY participant_id int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table project
--
ALTER TABLE project
  MODIFY project_id int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table remarks
--
ALTER TABLE remarks
  MODIFY remarks_id int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table scores
--
ALTER TABLE scores
  MODIFY score_id int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table team
--
ALTER TABLE team
  MODIFY team_id int(11) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table criteria
--
ALTER TABLE criteria
  ADD CONSTRAINT criteria_event FOREIGN KEY (event_id) REFERENCES event (event_id) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table judge
--
ALTER TABLE judge
  ADD CONSTRAINT judge_event FOREIGN KEY (event_id) REFERENCES event (event_id) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table participants
--
ALTER TABLE participants
  ADD CONSTRAINT participant_team FOREIGN KEY (team_id) REFERENCES team (team_id) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table project
--
ALTER TABLE project
  ADD CONSTRAINT project_event FOREIGN KEY (event_id) REFERENCES event (event_id) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT project_team FOREIGN KEY (team_id) REFERENCES team (team_id) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table remarks
--
ALTER TABLE remarks
  ADD CONSTRAINT remarks_judge FOREIGN KEY (judge_id) REFERENCES judge (judge_id) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT remarks_project FOREIGN KEY (project_id) REFERENCES project (project_id) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table scores
--
ALTER TABLE scores
  ADD CONSTRAINT scores_criteria FOREIGN KEY (criteria_id) REFERENCES criteria (criteria_id) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT scores_judge FOREIGN KEY (judge_id) REFERENCES judge (judge_id) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT scores_project FOREIGN KEY (project_id) REFERENCES project (project_id) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
