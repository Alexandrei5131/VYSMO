-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 19, 2023 at 12:44 AM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vysmo_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `account_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `datetime_created` datetime NOT NULL,
  `role` varchar(255) NOT NULL,
  `quick_login` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`account_id`, `email`, `password`, `datetime_created`, `role`, `quick_login`) VALUES
(1, 'neustadmin@gmail.com', '2dd1c647cdc86c79704f954260a028c0', '0000-00-00 00:00:00', 'Admin', 0);

-- --------------------------------------------------------

--
-- Table structure for table `account_qr`
--

CREATE TABLE `account_qr` (
  `accountQR_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `qrName` varchar(255) NOT NULL,
  `encryptedQrContent` varchar(255) NOT NULL,
  `keyQR` varchar(255) NOT NULL,
  `ivQR` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `account_reset_password`
--

CREATE TABLE `account_reset_password` (
  `resetPasswordID` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `resetKey` varchar(255) NOT NULL,
  `expiration` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `destination_list`
--

CREATE TABLE `destination_list` (
  `destination_id` int(11) NOT NULL,
  `typeOfDestination` varchar(255) NOT NULL,
  `destination` varchar(255) NOT NULL,
  `destinationName` varchar(255) NOT NULL,
  `destinationLink` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `bldgQR` varchar(255) NOT NULL,
  `keyScan` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `destination_list`
--

INSERT INTO `destination_list` (`destination_id`, `typeOfDestination`, `destination`, `destinationName`, `destinationLink`, `status`, `bldgQR`, `keyScan`) VALUES
(1, 'Department', 'CICT', 'College of Information and Communications Technology', 'https://maps.app.goo.gl/kYFAP8d83gUzmujT6', 0, '', ''),
(2, 'Department', 'COE', 'College of Engineering', 'https://maps.app.goo.gl/KnPmiB8smnHwrpWw5', 0, '', ''),
(3, 'Department', 'COA', 'College of Architecture', 'https://maps.app.goo.gl/crYGpvR91Nmi2RDj9', 0, '', ''),
(4, 'Department', 'COC', 'College of Criminology', 'https://maps.app.goo.gl/xioDBZwgHWvmaxUW8', 0, '', ''),
(5, 'Department', 'COED', 'College of Education', 'https://maps.app.goo.gl/SwNztLy1C3ixzkrY9', 0, '', ''),
(6, 'Department', 'CMBT', 'College of Management and Business Technology', 'https://maps.app.goo.gl/uYJ2QVLw5e6CDjjm8', 0, '', ''),
(7, 'Transactional', 'Marketing', 'Marketing Department', 'https://maps.app.goo.gl/Cgk7CdomwXx6oN2F8', 0, '', ''),
(8, 'Transactional', 'OAR', 'Office of Admission and Registration', 'https://maps.app.goo.gl/kWvmkPpxWD1xjykq6', 0, '', ''),
(9, 'Department', 'Library', 'Library', 'https://maps.app.goo.gl/ooAsmocmMUmkT7fLA', 0, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `evaluate_answer`
--

CREATE TABLE `evaluate_answer` (
  `ansScaleID` int(11) NOT NULL,
  `questionID` int(11) NOT NULL,
  `scaleID` int(11) NOT NULL,
  `evaluation_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `dateTake` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `evaluate_feedback`
--

CREATE TABLE `evaluate_feedback` (
  `ansFeedbackID` int(11) NOT NULL,
  `feedback` text NOT NULL,
  `evaluation_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `dateTake` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_list`
--

CREATE TABLE `evaluation_list` (
  `evaluation_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_question`
--

CREATE TABLE `evaluation_question` (
  `questionID` int(11) NOT NULL,
  `question` varchar(255) NOT NULL,
  `evaluation_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_scale`
--

CREATE TABLE `evaluation_scale` (
  `scaleID` int(11) NOT NULL,
  `scale` int(11) NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `evaluation_scale`
--

INSERT INTO `evaluation_scale` (`scaleID`, `scale`, `description`) VALUES
(1, 1, 'Very Dissatisfied'),
(2, 2, 'Dissatisfied'),
(3, 3, 'Neutral'),
(4, 4, 'Satisfied'),
(5, 5, 'Very Satisfied');

-- --------------------------------------------------------

--
-- Table structure for table `eval_exclusiveevent_qr`
--

CREATE TABLE `eval_exclusiveevent_qr` (
  `evalExclusive_id` int(11) NOT NULL,
  `evaluation_id` int(11) NOT NULL,
  `evaluationEventQR` varchar(255) NOT NULL,
  `keyScan` varchar(255) NOT NULL,
  `dateTime_generated` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `event_list`
--

CREATE TABLE `event_list` (
  `event_id` int(11) NOT NULL,
  `typeOfEvent` varchar(255) NOT NULL,
  `eventName` varchar(255) NOT NULL,
  `eventVenue` varchar(255) NOT NULL,
  `eventStart` date NOT NULL,
  `eventEnd` date NOT NULL,
  `evaluationForm` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `event_openqr`
--

CREATE TABLE `event_openqr` (
  `openEventQr_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `openEventQR` varchar(255) NOT NULL,
  `keyScan` varchar(255) NOT NULL,
  `dateTime_generated` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `guard_info`
--

CREATE TABLE `guard_info` (
  `guardInfo_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `firstName` varchar(255) NOT NULL,
  `middleName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `suffixName` varchar(50) NOT NULL,
  `gender` varchar(50) NOT NULL,
  `houseNumber` varchar(255) NOT NULL,
  `street` varchar(255) NOT NULL,
  `barangay` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `province` varchar(255) NOT NULL,
  `pic2x2` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `guard_logs`
--

CREATE TABLE `guard_logs` (
  `log_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `date_logs` date NOT NULL,
  `activity` varchar(255) NOT NULL,
  `time_logs` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `theme_color`
--

CREATE TABLE `theme_color` (
  `id` int(11) NOT NULL,
  `color` varchar(15) NOT NULL,
  `colorBG` varchar(15) NOT NULL,
  `colorBG1` varchar(15) NOT NULL,
  `colorBG2` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `theme_color`
--

INSERT INTO `theme_color` (`id`, `color`, `colorBG`, `colorBG1`, `colorBG2`) VALUES
(1, '#417e80', '#419d9e', '#419d9e', '#8c8c8c');

-- --------------------------------------------------------

--
-- Table structure for table `visitation_records`
--

CREATE TABLE `visitation_records` (
  `visitation_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `typeOfVisit` varchar(255) NOT NULL,
  `typeOfForm` varchar(255) NOT NULL,
  `destination_id` int(11) NOT NULL,
  `numberOfVisitor` int(11) NOT NULL,
  `timestampDestination` varchar(255) NOT NULL,
  `done` int(11) NOT NULL,
  `dateExpired` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `visitor_info`
--

CREATE TABLE `visitor_info` (
  `visitorInfo_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `firstName` varchar(255) NOT NULL,
  `middleInitial` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `suffixName` varchar(50) NOT NULL,
  `gender` varchar(50) NOT NULL,
  `mobileNumber` varchar(100) NOT NULL,
  `houseNumber` varchar(255) NOT NULL,
  `street` varchar(255) NOT NULL,
  `barangay` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `province` varchar(255) NOT NULL,
  `nationality` varchar(100) NOT NULL,
  `typeOfID` varchar(50) NOT NULL,
  `selfieWithID` varchar(255) NOT NULL,
  `frontID` varchar(255) NOT NULL,
  `backID` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `visit_event`
--

CREATE TABLE `visit_event` (
  `visitevent_id` int(11) NOT NULL,
  `visitation_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `eventPurpose` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `visit_officebldg`
--

CREATE TABLE `visit_officebldg` (
  `visitOfficeBldg_id` int(11) NOT NULL,
  `visitation_id` int(11) NOT NULL,
  `officeBldgPurpose` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `visit_openevent`
--

CREATE TABLE `visit_openevent` (
  `visitOpenEventID` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `suffixName` varchar(255) NOT NULL,
  `numberOfVisitor` int(11) NOT NULL,
  `dateVisit` date NOT NULL,
  `timestamp` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `visit_person`
--

CREATE TABLE `visit_person` (
  `visitPerson_id` int(11) NOT NULL,
  `visitation_id` int(11) NOT NULL,
  `personToVisit` varchar(100) NOT NULL,
  `personFirstName` varchar(255) NOT NULL,
  `personLastName` varchar(255) NOT NULL,
  `personSuffixName` varchar(150) NOT NULL,
  `personRelationship` varchar(255) NOT NULL,
  `personPurpose` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `visit_requested`
--

CREATE TABLE `visit_requested` (
  `requestVisit_id` int(11) NOT NULL,
  `visitation_id` int(11) NOT NULL,
  `dateSubmit` date NOT NULL,
  `appointmentVisit` date NOT NULL,
  `rejectReason` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `visit_timein_university`
--

CREATE TABLE `visit_timein_university` (
  `universityTimein id` int(11) NOT NULL,
  `visitation_id` int(11) NOT NULL,
  `visitor_account_id` int(11) NOT NULL,
  `dateVisit` date NOT NULL,
  `timestamp` varchar(255) NOT NULL,
  `guard_account_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`account_id`);

--
-- Indexes for table `account_qr`
--
ALTER TABLE `account_qr`
  ADD PRIMARY KEY (`accountQR_id`);

--
-- Indexes for table `account_reset_password`
--
ALTER TABLE `account_reset_password`
  ADD PRIMARY KEY (`resetPasswordID`),
  ADD KEY `accountID_accResetPassword_fk` (`account_id`);

--
-- Indexes for table `destination_list`
--
ALTER TABLE `destination_list`
  ADD PRIMARY KEY (`destination_id`);

--
-- Indexes for table `evaluate_answer`
--
ALTER TABLE `evaluate_answer`
  ADD PRIMARY KEY (`ansScaleID`);

--
-- Indexes for table `evaluate_feedback`
--
ALTER TABLE `evaluate_feedback`
  ADD PRIMARY KEY (`ansFeedbackID`);

--
-- Indexes for table `evaluation_list`
--
ALTER TABLE `evaluation_list`
  ADD PRIMARY KEY (`evaluation_id`),
  ADD KEY `eventID_evaluationlist_fk` (`event_id`);

--
-- Indexes for table `evaluation_question`
--
ALTER TABLE `evaluation_question`
  ADD PRIMARY KEY (`questionID`),
  ADD KEY `question_evaluationID_fk` (`evaluation_id`);

--
-- Indexes for table `evaluation_scale`
--
ALTER TABLE `evaluation_scale`
  ADD PRIMARY KEY (`scaleID`);

--
-- Indexes for table `eval_exclusiveevent_qr`
--
ALTER TABLE `eval_exclusiveevent_qr`
  ADD PRIMARY KEY (`evalExclusive_id`),
  ADD KEY `evaluationID_evaluationlist_fk` (`evaluation_id`);

--
-- Indexes for table `event_list`
--
ALTER TABLE `event_list`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `event_openqr`
--
ALTER TABLE `event_openqr`
  ADD PRIMARY KEY (`openEventQr_id`),
  ADD KEY `eventID_openQR_fk` (`event_id`);

--
-- Indexes for table `guard_info`
--
ALTER TABLE `guard_info`
  ADD PRIMARY KEY (`guardInfo_id`),
  ADD KEY `guardinfo_accountID_fk` (`account_id`);

--
-- Indexes for table `guard_logs`
--
ALTER TABLE `guard_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `accountID_logs_fk` (`account_id`);

--
-- Indexes for table `theme_color`
--
ALTER TABLE `theme_color`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `visitation_records`
--
ALTER TABLE `visitation_records`
  ADD PRIMARY KEY (`visitation_id`),
  ADD KEY `accountID_visitationrecords_fk` (`account_id`),
  ADD KEY `destinationID_visitationrecords_fk` (`destination_id`);

--
-- Indexes for table `visitor_info`
--
ALTER TABLE `visitor_info`
  ADD PRIMARY KEY (`visitorInfo_id`),
  ADD KEY `visitorinfo_accountID_fk` (`account_id`);

--
-- Indexes for table `visit_event`
--
ALTER TABLE `visit_event`
  ADD PRIMARY KEY (`visitevent_id`),
  ADD KEY `eventID_formEvent_fk` (`event_id`),
  ADD KEY `visitationID_visitEvent_fk` (`visitation_id`);

--
-- Indexes for table `visit_officebldg`
--
ALTER TABLE `visit_officebldg`
  ADD PRIMARY KEY (`visitOfficeBldg_id`),
  ADD KEY `visitationID_visitOfficeBldg_fk` (`visitation_id`);

--
-- Indexes for table `visit_openevent`
--
ALTER TABLE `visit_openevent`
  ADD PRIMARY KEY (`visitOpenEventID`),
  ADD KEY `fk_eventID_openEvent` (`event_id`);

--
-- Indexes for table `visit_person`
--
ALTER TABLE `visit_person`
  ADD PRIMARY KEY (`visitPerson_id`),
  ADD KEY `visitationID_visitPerson_fk` (`visitation_id`);

--
-- Indexes for table `visit_requested`
--
ALTER TABLE `visit_requested`
  ADD PRIMARY KEY (`requestVisit_id`),
  ADD KEY `visitationID_requestedVisit_fk` (`visitation_id`);

--
-- Indexes for table `visit_timein_university`
--
ALTER TABLE `visit_timein_university`
  ADD PRIMARY KEY (`universityTimein id`),
  ADD KEY `fk_visitorAccountID` (`visitor_account_id`),
  ADD KEY `fk_guardAccountID` (`guard_account_id`),
  ADD KEY `visitation_id_timeInUniversity_fk` (`visitation_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `account_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `account_qr`
--
ALTER TABLE `account_qr`
  MODIFY `accountQR_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `account_reset_password`
--
ALTER TABLE `account_reset_password`
  MODIFY `resetPasswordID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `destination_list`
--
ALTER TABLE `destination_list`
  MODIFY `destination_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `evaluate_answer`
--
ALTER TABLE `evaluate_answer`
  MODIFY `ansScaleID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `evaluate_feedback`
--
ALTER TABLE `evaluate_feedback`
  MODIFY `ansFeedbackID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `evaluation_list`
--
ALTER TABLE `evaluation_list`
  MODIFY `evaluation_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `evaluation_question`
--
ALTER TABLE `evaluation_question`
  MODIFY `questionID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `evaluation_scale`
--
ALTER TABLE `evaluation_scale`
  MODIFY `scaleID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `eval_exclusiveevent_qr`
--
ALTER TABLE `eval_exclusiveevent_qr`
  MODIFY `evalExclusive_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event_list`
--
ALTER TABLE `event_list`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event_openqr`
--
ALTER TABLE `event_openqr`
  MODIFY `openEventQr_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guard_info`
--
ALTER TABLE `guard_info`
  MODIFY `guardInfo_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guard_logs`
--
ALTER TABLE `guard_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `theme_color`
--
ALTER TABLE `theme_color`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `visitation_records`
--
ALTER TABLE `visitation_records`
  MODIFY `visitation_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `visitor_info`
--
ALTER TABLE `visitor_info`
  MODIFY `visitorInfo_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `visit_event`
--
ALTER TABLE `visit_event`
  MODIFY `visitevent_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `visit_officebldg`
--
ALTER TABLE `visit_officebldg`
  MODIFY `visitOfficeBldg_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `visit_openevent`
--
ALTER TABLE `visit_openevent`
  MODIFY `visitOpenEventID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `visit_person`
--
ALTER TABLE `visit_person`
  MODIFY `visitPerson_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `visit_requested`
--
ALTER TABLE `visit_requested`
  MODIFY `requestVisit_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `visit_timein_university`
--
ALTER TABLE `visit_timein_university`
  MODIFY `universityTimein id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `account_reset_password`
--
ALTER TABLE `account_reset_password`
  ADD CONSTRAINT `accountID_accResetPassword_fk` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`account_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `evaluation_list`
--
ALTER TABLE `evaluation_list`
  ADD CONSTRAINT `eventID_evaluationlist_fk` FOREIGN KEY (`event_id`) REFERENCES `event_list` (`event_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `evaluation_question`
--
ALTER TABLE `evaluation_question`
  ADD CONSTRAINT `question_evaluationID_fk` FOREIGN KEY (`evaluation_id`) REFERENCES `evaluation_list` (`evaluation_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `eval_exclusiveevent_qr`
--
ALTER TABLE `eval_exclusiveevent_qr`
  ADD CONSTRAINT `evaluationID_evaluationlist_fk` FOREIGN KEY (`evaluation_id`) REFERENCES `evaluation_list` (`evaluation_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `event_openqr`
--
ALTER TABLE `event_openqr`
  ADD CONSTRAINT `eventID_openQR_fk` FOREIGN KEY (`event_id`) REFERENCES `event_list` (`event_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `guard_info`
--
ALTER TABLE `guard_info`
  ADD CONSTRAINT `guardinfo_accountID_fk` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`account_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `guard_logs`
--
ALTER TABLE `guard_logs`
  ADD CONSTRAINT `accountID_logs_fk` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`account_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `visitation_records`
--
ALTER TABLE `visitation_records`
  ADD CONSTRAINT `accountID_visitationrecords_fk` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`account_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `destinationID_visitationrecords_fk` FOREIGN KEY (`destination_id`) REFERENCES `destination_list` (`destination_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `visitor_info`
--
ALTER TABLE `visitor_info`
  ADD CONSTRAINT `visitorinfo_accountID_fk` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`account_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `visit_event`
--
ALTER TABLE `visit_event`
  ADD CONSTRAINT `eventID_formEvent_fk` FOREIGN KEY (`event_id`) REFERENCES `event_list` (`event_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `visitationID_visitEvent_fk` FOREIGN KEY (`visitation_id`) REFERENCES `visitation_records` (`visitation_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `visit_officebldg`
--
ALTER TABLE `visit_officebldg`
  ADD CONSTRAINT `visitationID_visitOfficeBldg_fk` FOREIGN KEY (`visitation_id`) REFERENCES `visitation_records` (`visitation_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `visit_openevent`
--
ALTER TABLE `visit_openevent`
  ADD CONSTRAINT `fk_eventID_openEvent` FOREIGN KEY (`event_id`) REFERENCES `event_list` (`event_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `visit_person`
--
ALTER TABLE `visit_person`
  ADD CONSTRAINT `visitationID_visitPerson_fk` FOREIGN KEY (`visitation_id`) REFERENCES `visitation_records` (`visitation_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `visit_requested`
--
ALTER TABLE `visit_requested`
  ADD CONSTRAINT `visitationID_requestedVisit_fk` FOREIGN KEY (`visitation_id`) REFERENCES `visitation_records` (`visitation_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `visit_timein_university`
--
ALTER TABLE `visit_timein_university`
  ADD CONSTRAINT `guardAccountID_timeInUniv_fk` FOREIGN KEY (`guard_account_id`) REFERENCES `accounts` (`account_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `visitation_id_timeInUniversity_fk` FOREIGN KEY (`visitation_id`) REFERENCES `visitation_records` (`visitation_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `visitorAccountID_timeInUniv_fk` FOREIGN KEY (`visitor_account_id`) REFERENCES `accounts` (`account_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
