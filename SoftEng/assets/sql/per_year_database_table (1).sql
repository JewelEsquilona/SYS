-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 02, 2024 at 08:50 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `per year database table`
--

-- --------------------------------------------------------

--
-- Table structure for table `2024-2025`
--

CREATE TABLE `2024-2025` (
  `ID` int(11) NOT NULL,
  `Alumni_ID_Number` int(11) NOT NULL,
  `Student_Number` varchar(20) NOT NULL,
  `Last_Name` varchar(50) NOT NULL,
  `First_Name` varchar(50) NOT NULL,
  `Middle_Name` varchar(50) DEFAULT NULL,
  `College` varchar(100) NOT NULL,
  `Department` varchar(100) NOT NULL,
  `Section` varchar(50) NOT NULL,
  `Year_Graduated` year(4) NOT NULL,
  `Contact_Number` varchar(15) NOT NULL,
  `Personal_Email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `2024-2025`
--

INSERT INTO `2024-2025` (`ID`, `Alumni_ID_Number`, `Student_Number`, `Last_Name`, `First_Name`, `Middle_Name`, `College`, `Department`, `Section`, `Year_Graduated`, `Contact_Number`, `Personal_Email`) VALUES
(4, 4, '21239078', 'no', 'no', 'no', 'CAS', 'PSYCH', 'PS4B', '2025', '09204107806', 'no@gmail.com'),
(6, 6, '21239045', 'yes', 'yes', 'yes', 'CAS', 'PSYCH', 'PS4B', '2025', '09204107803', 'yes@gmail.com'),
(7, 7, '21239074', 'low', 'low', 'low', 'CAS', 'PSYCH', 'PS4A', '2024', '09204107808', 'low@gmail.com'),
(8, 8, '001', 'Smith', 'John', 'A.', 'CITCS', 'BSCS', 'CS4A', '2024', '09123456789', 'john.smith@email.com'),
(9, 9, '002', 'Johnson', 'Mary', 'B.', 'CITCS', 'BSIT', 'IT4B', '2025', '09234567890', 'mary.johnson@email.com'),
(10, 10, '003', 'Williams', 'James', 'C.', 'CAS', 'MASCOM', 'MC4C', '2024', '09345678901', 'james.williams@email.com'),
(11, 11, '004', 'Brown', 'Patricia', 'D.', 'CITCS', 'ACT', 'ACT4A', '2025', '09456789012', 'patricia.brown@email.com'),
(12, 12, '21239010', 'Jones', 'Michael', 'E.', 'CAS', 'PSYCH', 'PS4D', '2024', '09567890123', 'michael.jones@email.com'),
(13, 13, '21239011', 'Smith', 'Laura', 'A.', 'CITCS', 'BSCS', 'CS4A', '2024', '09567890124', 'laura.smith@email.com'),
(14, 14, '21239013', 'Davis', 'Sarah', 'C.', 'CAS', 'MASCOM', 'MC4C', '2024', '09567890126', 'sarah.davis@email.com'),
(15, 15, '21239016', 'Taylor', 'Emily', 'G.', 'CAS', 'PSYCH', 'PS4A', '2024', '09567890129', 'emily.taylor@email.com'),
(16, 16, '21239018', 'Gonzales', 'Maria', 'I.', 'CAS', 'MASCOM', 'MC4B', '2024', '09567890131', 'maria.gonzales@email.com'),
(17, 17, '212390168', 'oki', 'oki', 'oki', 'CAS', 'PSYCH', 'PSY4D', '2025', '09204107898', 'oki@gmail.com'),
(18, 18, '21239018', 'nel', 'nel', 'nel', 'BSBA', 'ACN', 'ACN2', '2024', '09204107808', 'nel@gmail.com'),
(19, 19, '21239018', 'jade', 'jade', 'jade', 'CITCS', 'IT', 'IT4B', '2024', '09204107890', 'jade@gmail.com');

--
-- Triggers `2024-2025`
--
DELIMITER $$
CREATE TRIGGER `before_insert_2024_2025` BEFORE INSERT ON `2024-2025` FOR EACH ROW BEGIN
    IF NEW.ID IS NULL OR NEW.ID = 0 THEN
        SET NEW.ID = (SELECT IFNULL(MAX(ID), 0) + 1 FROM `2024-2025`);
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_insert_alumni_id` BEFORE INSERT ON `2024-2025` FOR EACH ROW BEGIN
    DECLARE new_alumni_id INT;
    SELECT COALESCE(MAX(Alumni_ID_Number), 0) + 1 INTO new_alumni_id FROM `2024-2025`;
    SET NEW.Alumni_ID_Number = new_alumni_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `2024-2025_ed`
--

CREATE TABLE `2024-2025_ed` (
  `ID` int(11) NOT NULL,
  `Alumni_ID_Number` int(11) NOT NULL,
  `Employment` varchar(50) DEFAULT NULL,
  `Employment_Status` varchar(50) DEFAULT NULL,
  `Present_Occupation` varchar(100) DEFAULT NULL,
  `Name_of_Employer` varchar(100) DEFAULT NULL,
  `Address_of_Employer` varchar(255) DEFAULT NULL,
  `Number_of_Years_in_Present_Employer` int(11) DEFAULT NULL,
  `Type_of_Employer` varchar(100) DEFAULT NULL,
  `Major_Line_of_Business` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `2024-2025_ed`
--

INSERT INTO `2024-2025_ed` (`ID`, `Alumni_ID_Number`, `Employment`, `Employment_Status`, `Present_Occupation`, `Name_of_Employer`, `Address_of_Employer`, `Number_of_Years_in_Present_Employer`, `Type_of_Employer`, `Major_Line_of_Business`) VALUES
(4, 4, 'Self-employed', NULL, '', '', '', NULL, '', ''),
(6, 6, 'Actively looking for a job', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(9, 7, 'Self-employed', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(10, 8, 'Employed', 'Regular/Permanent', 'Software Engineer', 'Tech Solutions', '123 Tech Lane', 2, 'Private', 'Information Technology'),
(11, 9, 'Self-Employed', 'Independent', 'Freelance Web Developer', 'Self-Employed', '456 Freelancer Ave', 1, 'Self-Employed', 'Web Development'),
(12, 10, 'Employed', 'Contractual', 'Marketing Associate', 'Ad Agency', '789 Marketing Blvd', 2, 'Private', 'Advertising'),
(13, 11, 'Employed', 'Regular/Permanent', 'IT Support Specialist', 'Global Tech', '7', 0, 'Computer Specialist', ''),
(14, 12, 'Employed', 'Regular/Permanent', 'Clinical Psychologist', 'Health Services', '321 Wellness Way', 4, 'Private', 'Healthcare'),
(15, 13, 'Employed', 'Contractual', 'Software Engineer', 'Tech Innovators', '456 Tech Ave', 2, 'Private', 'Technology'),
(16, 14, 'Employed', 'Part-time (seeking full-time)', 'Journalist', 'Daily News', '123 Media Lane', 8, 'Public', 'Media'),
(17, 15, 'Employed', 'Temporary', 'Therapist', 'Community Health Services', '234 Wellness St', 7, 'Private', 'Healthcare'),
(18, 16, 'Employed', 'Contractual', 'Public Relations Specialist', 'PR Agency', '901 PR Ave', 3, 'Private', 'Media'),
(19, 17, 'Employed', 'Temporary', 'oki', 'oki', 'oki', 9, 'oki', 'oki'),
(20, 18, 'Employed', 'Temporary', 'nel', 'nel', 'nel', 8, 'nel', 'nel'),
(21, 19, 'Employed', 'Casual', 'jade', 'jade', 'jade', 4, 'jade', 'jade');

--
-- Triggers `2024-2025_ed`
--
DELIMITER $$
CREATE TRIGGER `before_insert_2024_2025_ed` BEFORE INSERT ON `2024-2025_ed` FOR EACH ROW BEGIN
    DECLARE duplicate_count INT;
    SELECT COUNT(*) INTO duplicate_count
    FROM `2024-2025_ed`
    WHERE Alumni_ID_Number = NEW.Alumni_ID_Number;

    IF duplicate_count > 0 THEN
        UPDATE `2024-2025_ed`
        SET created_at = NOW()  
        WHERE Alumni_ID_Number = NEW.Alumni_ID_Number
        AND Alumni_ID_Number < NEW.Alumni_ID_Number;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `college` varchar(255) NOT NULL,
  `department` varchar(255) NOT NULL,
  `section` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `college`, `department`, `section`) VALUES
(1, 'BSBA', 'ACN', 'ACN1'),
(2, 'BSBA', 'ACN', 'ACN4'),
(3, 'BSBA', 'ACN', 'ACN2'),
(4, 'BSBA', 'ACN', 'ACN3'),
(9, 'CITCS', 'CS', 'CS4A'),
(10, 'CITCS', 'CS', 'CS4B'),
(11, 'CITCS', 'CS', 'CS4C'),
(12, 'CITCS', 'CS', 'CS4D'),
(17, 'CAS', 'MASCOM', 'MAS4A'),
(18, 'CAS', 'MASCOM', 'MAS4B'),
(19, 'CAS', 'MASCOM', 'MAS4C'),
(20, 'CAS', 'MASCOM', 'MAS4D'),
(21, 'CITCS', 'IT', 'IT4A'),
(22, 'CITCS', 'IT', 'IT4B'),
(23, 'CITCS', 'IT', 'IT4C'),
(24, 'CITCS', 'IT', 'IT4D'),
(25, 'CAS', 'PSYCH', 'PSY4A'),
(26, 'CAS', 'PSYCH', 'PSY4B'),
(27, 'CAS', 'PSYCH', 'PYS4C'),
(28, 'CAS', 'PSYCH', 'PSY4D'),
(29, 'CITCS', 'ACT', 'ACT2A'),
(30, 'CITCS', 'ACT', 'ACT2B'),
(31, 'CITCS', 'ACT', 'ACT2C'),
(32, 'CITCS', 'ACT', 'ACT2D');

-- --------------------------------------------------------

--
-- Table structure for table `role_id_counter`
--

CREATE TABLE `role_id_counter` (
  `role` enum('Admin','Registrar','Dean','Program Chair','Alumni') NOT NULL,
  `last_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role_id_counter`
--

INSERT INTO `role_id_counter` (`role`, `last_id`) VALUES
('Admin', 0),
('Registrar', 0),
('Dean', 0),
('Program Chair', 0),
('Alumni', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Admin','Registrar','Dean','Program Chair','Alumni') NOT NULL,
  `unique_number` varchar(50) DEFAULT NULL,
  `college` varchar(255) DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `users`
--
DELIMITER $$
CREATE TRIGGER `before_insert_admin` BEFORE INSERT ON `users` FOR EACH ROW BEGIN
    DECLARE new_id INT;

    -- Increment the last_id for Admin
    UPDATE role_id_counter SET last_id = LAST_INSERT_ID(last_id + 1) WHERE role = 'Admin';

    -- Get the new ID
    SET new_id = LAST_INSERT_ID();

    -- Set the new ID for the new row
    SET NEW.unique_number = CONCAT('AD', LPAD(new_id, 5, '0'));
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_insert_dean` BEFORE INSERT ON `users` FOR EACH ROW BEGIN
    DECLARE new_id INT;

    -- Increment the last_id for Dean
    UPDATE role_id_counter SET last_id = LAST_INSERT_ID(last_id + 1) WHERE role = 'Dean';

    -- Get the new ID
    SET new_id = LAST_INSERT_ID();

    -- Set the new ID for the new row
    SET NEW.unique_number = CONCAT('DN', LPAD(new_id, 5, '0'), '-', NEW.college);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_insert_program_chair` BEFORE INSERT ON `users` FOR EACH ROW BEGIN
    DECLARE new_id INT;

    -- Increment the last_id for Program Chair
    UPDATE role_id_counter SET last_id = LAST_INSERT_ID(last_id + 1) WHERE role = 'Program Chair';

    -- Get the new ID
    SET new_id = LAST_INSERT_ID();

    -- Set the new ID for the new row
    SET NEW.unique_number = CONCAT('PC', LPAD(new_id, 5, '0'), '-', NEW.department);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_insert_registrar` BEFORE INSERT ON `users` FOR EACH ROW BEGIN
    DECLARE new_id INT;

    -- Increment the last_id for Registrar
    UPDATE role_id_counter SET last_id = LAST_INSERT_ID(last_id + 1) WHERE role = 'Registrar';

    -- Get the new ID
    SET new_id = LAST_INSERT_ID();

    -- Set the new ID for the new row
    SET NEW.unique_number = CONCAT('RG', LPAD(new_id, 5, '0'));
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `2024-2025`
--
ALTER TABLE `2024-2025`
  ADD PRIMARY KEY (`Alumni_ID_Number`),
  ADD UNIQUE KEY `ID` (`ID`);

--
-- Indexes for table `2024-2025_ed`
--
ALTER TABLE `2024-2025_ed`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_alumni_id` (`Alumni_ID_Number`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_id_counter`
--
ALTER TABLE `role_id_counter`
  ADD PRIMARY KEY (`role`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `unique_number` (`unique_number`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `2024-2025`
--
ALTER TABLE `2024-2025`
  MODIFY `Alumni_ID_Number` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `2024-2025_ed`
--
ALTER TABLE `2024-2025_ed`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `2024-2025_ed`
--
ALTER TABLE `2024-2025_ed`
  ADD CONSTRAINT `fk_alumni_id` FOREIGN KEY (`Alumni_ID_Number`) REFERENCES `2024-2025` (`Alumni_ID_Number`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
