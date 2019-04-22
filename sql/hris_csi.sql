-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 22, 2019 at 05:29 AM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 7.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hris_csi`
--

-- --------------------------------------------------------

--
-- Table structure for table `applicant_achievement`
--

CREATE TABLE `applicant_achievement` (
  `ind` bigint(20) NOT NULL,
  `applicant_id` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `achievement` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `applicant_education`
--

CREATE TABLE `applicant_education` (
  `applicant_id` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hs_name` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hs_graduate` date DEFAULT NULL,
  `educ_attainment` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `course` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_graduated` date DEFAULT NULL,
  `school_name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `applicant_emergency`
--

CREATE TABLE `applicant_emergency` (
  `ind` bigint(20) NOT NULL,
  `applicant_id` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `relationship` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `applicant_employement`
--

CREATE TABLE `applicant_employement` (
  `ind` bigint(20) NOT NULL,
  `applicant_id` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `employer_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `applicant_information`
--

CREATE TABLE `applicant_information` (
  `ind` bigint(20) NOT NULL,
  `applicant_id` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lname` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fname` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mname` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL,
  `citizenship` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `birtdate` date NOT NULL,
  `birthplace` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_no` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city_add` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city_add2` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city_city` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city_prov` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city_zip` varchar(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `personal_email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prov_add` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prov_add2` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prov_city` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prov_prov` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prov_zip` varchar(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `apply_position` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expected_salary` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apply_date` date NOT NULL,
  `created_by` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lu_by` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lu_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `applicant_licence`
--

CREATE TABLE `applicant_licence` (
  `ind` bigint(20) NOT NULL,
  `applicant_id` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `licence_no` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_issue` date DEFAULT NULL,
  `date_valid` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `applicant_training`
--

CREATE TABLE `applicant_training` (
  `ind` bigint(20) NOT NULL,
  `applicant_id` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `certificate_no` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_issue` date DEFAULT NULL,
  `date_valid` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `assets`
--

CREATE TABLE `assets` (
  `ind` bigint(20) NOT NULL,
  `asset_date` datetime NOT NULL,
  `class` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty` int(11) NOT NULL,
  `logbook_no` int(11) DEFAULT NULL,
  `asset_tagno` bigint(20) NOT NULL,
  `assigned_person` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transferred` int(2) NOT NULL DEFAULT '0',
  `transferred_person` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lu_by` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lu_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` int(2) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `assets`
--

INSERT INTO `assets` (`ind`, `asset_date`, `class`, `qty`, `logbook_no`, `asset_tagno`, `assigned_person`, `description`, `transferred`, `transferred_person`, `created_by`, `created_date`, `lu_by`, `lu_date`, `deleted`) VALUES
(1, '2018-10-01 08:00:00', 'Class A', 2, 2018003, 2000810, '2018-049', 'Computer', 0, '', '', '2018-10-10 17:37:19', 'admin', '2018-10-11 11:38:32', 0),
(2, '2018-10-11 20:59:00', 'Class A', 2, 20180000, 201535, '2018-026', 'Computer', 0, '2018-049', 'admin', '2018-10-10 18:29:56', 'admin', '2018-10-11 11:35:22', 0),
(3, '2018-10-13 05:00:00', 'Class B', 5, 20154, 33116, '2018-046', 'Desktop', 0, '2018-049', 'admin', '2018-10-10 18:33:17', 'admin', '2018-10-10 18:33:17', 0),
(4, '2018-10-12 17:00:00', 'Class B', 5, 1616, 146586, '2018-048', 'Cup', 0, '2018-049', 'admin', '2018-10-10 18:35:51', 'admin', '2018-10-10 18:35:51', 0);

-- --------------------------------------------------------

--
-- Table structure for table `certificates_trainings`
--

CREATE TABLE `certificates_trainings` (
  `ind` bigint(20) NOT NULL,
  `company_id` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `certificate_no` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_issue` date NOT NULL,
  `date_valid` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `childrens_information`
--

CREATE TABLE `childrens_information` (
  `ind` bigint(20) NOT NULL,
  `company_id` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `birthdate` date DEFAULT NULL,
  `occupation` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `childrens_information`
--

INSERT INTO `childrens_information` (`ind`, `company_id`, `name`, `birthdate`, `occupation`) VALUES
(1, '2018-042', 'Althea Janelle Ramos Santos', '2015-09-10', ''),
(2, '2018-021', 'Mikayla Anica Villanueva Esperida', '2018-01-01', ''),
(3, '2018-020', 'Mark Alvin Lyran Petras', '2008-05-23', ''),
(4, '2018-020', 'Mark Johanes Petras', '2009-07-29', ''),
(5, '2018-020', 'Mark John Petras', '2012-05-11', ''),
(6, '2018-020', 'Mariesckanne Princess Petras ', '2015-01-07', '');

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE `company` (
  `id` bigint(20) NOT NULL,
  `company_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lu_by` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lu_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `company`
--

INSERT INTO `company` (`id`, `company_name`, `active`, `created_by`, `created_date`, `lu_by`, `lu_date`) VALUES
(1, 'Circuit Solutions Incorporated', 'yes', 'admin', '2018-06-18 10:30:35', 'admin', '2018-06-18 13:59:06'),
(2, 'New Company', 'no', 'admin', '2018-06-18 13:29:50', 'admin', '2018-06-18 13:41:09'),
(3, 'SamPLE', 'no', 'admin', '2018-06-18 13:46:49', 'admin', '2018-06-18 18:40:54'),
(4, 'Company SSS', 'yes', 'admin', '2018-06-18 13:46:56', 'admin', '2018-06-19 11:14:44'),
(5, 'Trial Compan', 'yes', 'admin', '2018-08-29 17:55:33', 'admin', '2018-08-29 18:02:45');

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `ind` bigint(20) NOT NULL,
  `department_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_ind` bigint(20) NOT NULL DEFAULT '1',
  `active` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_date` datetime NOT NULL,
  `lu_by` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lu_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`ind`, `department_name`, `company_ind`, `active`, `created_by`, `created_date`, `lu_by`, `lu_date`) VALUES
(8, 'PMD', 1, 'yes', 'marlon', '2017-12-07 16:37:45', 'admin', '2018-01-02 09:33:35'),
(10, 'Sales', 1, 'yes', 'marlon', '2017-12-07 16:53:19', 'admin', '2017-12-19 11:58:35'),
(11, 'Fulfillment', 1, 'yes', 'marlon', '2017-12-07 16:53:46', 'admin', '2018-01-02 09:33:46'),
(12, 'Supply Chain', 1, 'yes', 'marlon', '2017-12-07 16:54:00', 'admin', '2017-12-13 14:08:58'),
(13, 'Research and Development', 1, 'yes', 'marlon', '2017-12-07 16:54:23', 'admin', '2018-01-02 17:23:18'),
(24, 'Finance', 1, 'yes', 'admin', '2018-02-10 21:10:41', 'admin', '2018-02-10 21:10:41');

-- --------------------------------------------------------

--
-- Table structure for table `education_background`
--

CREATE TABLE `education_background` (
  `company_id` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hs_name` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hs_graduate` date DEFAULT NULL,
  `educ_attainment` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `course` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_graduated` date DEFAULT NULL,
  `school_name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `education_background`
--

INSERT INTO `education_background` (`company_id`, `hs_name`, `hs_graduate`, `educ_attainment`, `course`, `date_graduated`, `school_name`) VALUES
('2014-001', '', '0000-00-00', '', 'BS ELECTRONICS ENGINEERING', '0000-00-00', 'FAR EASTERN UNIVERSITY'),
('2018-003', '', '0000-00-00', 'Vocational', 'TVET Electro-Mechanical Technology', '2017-10-21', 'Don Bosco Technical Institute - Makati'),
('2018-004', '', '0000-00-00', 'Vocational', 'Industrial Electronics - TVET', '0000-00-00', 'Don Bosco Technical Institute - Mandaluyong'),
('2018-020', '', '0000-00-00', 'Undergraduate', 'Instrumentation and Control Engineering Technology', '0000-00-00', 'Technological University of the Philippines'),
('2018-021', '', '0000-00-00', 'Bachelor degree', 'Bachelor of Science in Electronics and Communications Engineering', '2017-04-04', 'Pamantasan ng Lungsod ng Pasig'),
('2018-026', '', '0000-00-00', 'Bachelor degree', 'Bachelor of Science in Accountancy', '2017-04-08', 'Eastern Samar State University'),
('2018-028', '', '0000-00-00', 'Bachelor degree', 'Bachelor of Science in Business Administration major in Information Management', '2009-04-20', 'Institute of Creative Computer Technology'),
('2018-031', '', '0000-00-00', 'Bachelor degree', 'Bachelor of Science in Industrial Technology major in Electronics Technology', '2018-05-01', 'Eulogio \"Amang\" Rodriguez institute of Science and Technology'),
('2018-035', '', '0000-00-00', 'Bachelor degree', 'Bachelor of Science in Electronics and Communications Engineering', '2018-08-01', 'Far Eastern University - Institute of Technology'),
('2018-036', '', '0000-00-00', 'Bachelor degree', 'Bachelor of Science in Electronics and Communications Engineering', '2018-05-11', 'Rizal Technological University'),
('2018-038', '', '0000-00-00', 'Vocational', 'Computer Hardware Services', '2013-02-13', 'Gateways Institute of Science and Technology'),
('2018-042', '', '0000-00-00', 'Bachelor degree', 'Bachelor of Science in Accounting Technology', '2016-05-28', 'University of Luzon'),
('2018-044', '', '0000-00-00', 'Vocational', 'TVET Electro-Mechanical Technology', '0000-00-00', 'Don Bosco Technical Institute - Makati'),
('2018-045', '', '0000-00-00', 'Vocational', 'TVET Electro-Mechanical Technology', '0000-00-00', 'Don Bosco Technical Institute - Makati'),
('2018-046', '', '0000-00-00', 'Bachelor degree', 'Bachelor of Science in Electronics and Communications Engineering', '2014-04-02', 'Cagayan State University'),
('2018-047', '', '0000-00-00', 'Bachelor degree', 'Bachelor of Science in Electronics and Communications Engineering', '2014-05-14', 'Polytechnic University of the Philippines'),
('2018-048', '', '0000-00-00', 'Bachelor degree', 'Bachelor of Science in Accounting Technology', '2018-05-10', 'Rizal Technological University'),
('2018-049', 'Guronasyon Foundation Inc. National High School', '2014-03-13', 'Bachelor degree', 'Bachelor of Science in Information Technology', '2018-03-01', 'University of Rizal System - Binangonan'),
('2018-051', '', '0000-00-00', 'Bachelor degree', 'Bachelor of Science in Electronics and Communications Engineering', '2016-04-08', 'New Era University'),
('2018-053', '', '0000-00-00', 'Bachelor degree', 'Bachelor of Science in Industrial Technology major in Electronics ', '2018-06-20', 'Bulacan State University'),
('2018-054', '', '0000-00-00', 'Bachelor degree', 'Bachelor of Science in Business Administration major in Marketing Management', '2018-05-10', 'Rizal Technological University'),
('2018-1940', 'test', '2014-04-28', 'Bachelor degree', 'Test', '2018-04-26', 'Test'),
('2018-616', '', '0000-00-00', 'Bachelor degree', 'Bachelor of  Science in Information Technology', '2015-04-18', 'STI Santa Rosa'),
('2018-7675', '', '0000-00-00', 'Bachelor degree', 'Bachelor of  Science in Information Technology', '2015-04-18', 'STI Santa Rosa');

-- --------------------------------------------------------

--
-- Table structure for table `emergency_contact`
--

CREATE TABLE `emergency_contact` (
  `ind` bigint(20) NOT NULL,
  `company_id` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `relationship` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `emergency_contact`
--

INSERT INTO `emergency_contact` (`ind`, `company_id`, `name`, `address`, `contact`, `relationship`) VALUES
(1, '2018-049', 'Carmen Abad Reyes', '0031 Cable Way St., Macamot, Binangonan, Rizal', '09351166505', 'Mother'),
(2, '2018-042', 'Lina Valdez', 'Masagana St., Caloocan City', '09387148125', 'Mother'),
(3, '2018-026', 'Cecile C. Bernate', 'Brgy. Addition Hills, Mandaluyong City', '09289466271', 'Cousin'),
(4, '2018-048', 'Ma. Fe Aguilar', '326 Villamayor Pag-asa, Binangonan, Rizal', '09206222620', 'Mother'),
(5, '2018-028', 'Ahrvie Valdez', 'Pasig City', '09056461302', 'Husband'),
(6, '2018-035', 'Leticia Sarmiento', '1871 B Felix Huertas St. Sta. Cruz Manila', '09175206190', 'Mother'),
(7, '2018-036', 'Nora Loren', 'Block 24 Lot 4B, Phase 2B Elisa Homes, Molino IV, Bacoor City, Cavite', '09299576745', 'Mother'),
(8, '2018-003', 'Rafael Benedicto', '355 Bo. Sta. Rita, Tala, Caloocan City', '09475931570', 'Brother'),
(9, '2018-031', 'Imelda Villorente', '#4 ROTC Huntes Galilan, Brgy. Tatalon, Quezon City', '09367914491', 'Mother'),
(10, '2018-004', 'Narlyn Cruz', '536 Brgy. San Jose, Sitio IV, Mandaluyong City', '09426522368', 'Mother'),
(11, '2018-038', 'Rolando Caburnay', '851 E. Pantaleon St. Mandaluyong City', '09222108437', 'Father'),
(12, '2018-053', 'Jenny-Mar Holgado', 'B3 L10 Flores St. Pleasant Hills San Jose Del Monte, Bulacan', '09328739152', 'Sister'),
(13, '2018-054', 'Jennifer Dimaala', '24 Alley 6 P. rosales, Sta. Ana Pateros, Metro Manila', '09101305980', 'Mother'),
(14, '2018-047', 'Elmer  Dayao', '472 M.H. Del Pilar St., Brgy. San Isidro, Antipolo City', '09076626640', 'Father'),
(15, '2018-046', 'Anamarie Apiado', 'B5 L5 Greentown Ext. Mambog 3, Bacoor, Cavite', '09164815048', 'Sister'),
(16, '2018-045', 'Amelia Jamangal', '1659 Lancer St. Brgy. Sun Valley, ParaÃ±aque City', '09059069721', 'Aunt'),
(17, '2018-044', 'Dianne Lastrella', '2364 Unit 6A Alabastro St., San Andress, Bukid, Manila', '09354743081', 'Partner'),
(18, '2018-051', 'Albert Morillo', '4175 Christian Dior St. Bloomingdale Homes,  Angono Rizal', '09055109952', 'Father'),
(19, '2018-616', 'Richelle Belarmino Cortez', 'Circuit Solutions Inc. #14-B Belvedere Tower 15 San Miguel Ave., Ortigas Center, Pasig City', '09972172527', 'Sister'),
(20, '2018-7675', 'Richelle Belarmino Cortez', 'Circuit Solutions Inc. #14-B Belvedere Tower 15 San Miguel Ave., Ortigas Center, Pasig City', '09972172527', 'Sister');

-- --------------------------------------------------------

--
-- Table structure for table `employee_achievement`
--

CREATE TABLE `employee_achievement` (
  `ind` bigint(20) NOT NULL,
  `company_id` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `achievement` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_compensation`
--

CREATE TABLE `employee_compensation` (
  `ind` bigint(20) NOT NULL,
  `company_id` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `salary_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Daily',
  `weekly_days` int(11) NOT NULL DEFAULT '6',
  `bank_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payroll_account_no` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `taxable_allowance` decimal(11,2) NOT NULL DEFAULT '0.00',
  `taxable_allowance_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'prorated',
  `non_taxable_allowance` decimal(11,2) NOT NULL DEFAULT '0.00',
  `non_taxable_allowance_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'prorated',
  `basic_salary` decimal(11,2) NOT NULL DEFAULT '0.00',
  `daily_rate` decimal(11,2) NOT NULL DEFAULT '0.00',
  `hourly_rate` decimal(11,2) NOT NULL DEFAULT '0.00',
  `tax_shield` decimal(11,2) NOT NULL DEFAULT '0.00',
  `gross_salary` decimal(11,2) NOT NULL DEFAULT '0.00',
  `hrs_per_day` decimal(8,2) NOT NULL DEFAULT '0.00',
  `with_category_rate` int(2) NOT NULL DEFAULT '0',
  `category_rate_id` bigint(20) NOT NULL DEFAULT '0',
  `created_by` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lu_by` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lu_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` int(2) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employee_compensation`
--

INSERT INTO `employee_compensation` (`ind`, `company_id`, `salary_type`, `weekly_days`, `bank_name`, `payroll_account_no`, `taxable_allowance`, `taxable_allowance_type`, `non_taxable_allowance`, `non_taxable_allowance_type`, `basic_salary`, `daily_rate`, `hourly_rate`, `tax_shield`, `gross_salary`, `hrs_per_day`, `with_category_rate`, `category_rate_id`, `created_by`, `created_date`, `lu_by`, `lu_date`, `deleted`) VALUES
(1, '2018-048', 'Semi-Monthly', 6, 'das', '0252', '2050.00', 'prorated', '3500.00', 'fix', '6677.34', '614.15', '0.00', '0.00', '12227.34', '9.60', 0, 0, 'admin', '2018-08-14 16:06:31', 'admin', '2018-08-28 16:50:05', 0),
(2, '2018-004', 'Semi-Monthly', 6, 'dsfs', '12323', '0.00', 'prorated', '2500.00', 'prorated', '6677.33', '614.40', '64.00', '0.00', '9177.33', '9.60', 0, 0, 'admin', '2018-08-14 16:07:23', 'admin', '2018-10-22 15:13:54', 0),
(3, '2018-031', 'Daily', 6, 'BPI', '123456', '0.00', 'prorated', '0.00', 'prorated', '13354.67', '0.00', '0.00', '0.00', '13354.67', '9.60', 0, 0, 'admin', '2018-08-22 10:36:13', 'admin', '2018-09-03 16:28:04', 0),
(4, '2018-1940', 'Daily', 6, '', '', '0.00', 'prorated', '100.00', 'prorated', '1000.00', '0.00', '0.00', '0.00', '1100.00', '9.60', 0, 0, 'admin', '2018-08-22 11:21:02', 'admin', '2018-08-22 11:21:02', 0),
(5, '2018-003', 'Semi-Monthly', 6, 'fdswq', '12312', '0.00', 'prorated', '3500.00', 'prorated', '6677.33', '614.40', '64.00', '0.00', '10177.33', '9.60', 1, 1, 'admin', '2018-08-28 16:46:25', 'admin', '2019-01-17 18:16:57', 0),
(6, '2018-035', 'Semi-Monthly', 6, 'fdsf', '213', '0.00', 'prorated', '3500.00', 'prorated', '7003.38', '644.40', '67.13', '0.00', '10503.38', '9.60', 0, 0, 'admin', '2018-08-28 16:47:39', 'admin', '2019-01-17 14:30:49', 0),
(7, '2018-044', 'Semi-Monthly', 6, 'dsf', '0', '0.00', 'prorated', '2500.00', 'fix', '6677.34', '614.40', '64.00', '0.00', '9177.34', '9.60', 0, 0, 'admin', '2018-08-28 16:48:57', 'admin', '2019-01-17 14:31:08', 0),
(9, '2018-028', 'Daily', 6, '', '', '0.00', 'prorated', '0.00', 'prorated', '18000.00', '0.00', '0.00', '0.00', '18000.00', '9.60', 0, 0, 'admin', '2018-09-05 10:26:06', 'admin', '2018-09-05 10:26:06', 0),
(10, '2018-026', 'Daily', 6, '', '', '0.00', 'prorated', '0.00', 'prorated', '13354.67', '0.00', '0.00', '0.00', '13354.67', '9.60', 0, 0, 'admin', '2018-09-05 10:30:44', 'admin', '2018-09-05 10:30:44', 0),
(11, '2018-049', 'Semi-Monthly', 6, 'asa', '123456', '0.00', 'prorated', '0.00', 'prorated', '6677.33', '614.40', '64.00', '0.00', '6677.33', '9.60', 1, 1, 'admin', '2018-09-05 10:48:26', 'admin', '2019-01-31 17:47:22', 0),
(12, '2018-7675', 'Semi-Monthly', 6, 'Metro Bank', '2649648', '0.00', 'prorated', '5600.00', 'prorated', '6677.34', '614.40', '64.00', '0.00', '12277.34', '9.60', 0, 0, 'admin', '2018-09-07 17:42:13', 'admin', '2019-01-17 14:35:10', 0),
(13, '2018-021', 'Daily', 6, '', '', '0.00', 'prorated', '0.00', 'prorated', '13354.67', '0.00', '0.00', '0.00', '13354.67', '0.00', 0, 0, 'admin', '2018-09-20 17:10:24', 'admin', '2018-09-20 17:10:24', 0),
(14, '2018-616', 'Semi-Monthly', 6, 'BPI', '26465', '0.00', 'prorated', '2500.00', 'prorated', '6677.34', '614.40', '64.00', '0.00', '9177.34', '9.60', 0, 0, 'admin', '2018-09-20 18:14:33', 'admin', '2019-01-17 14:34:21', 0),
(15, '2018-053', 'Semi-Monthly', 6, 'BPI', '123654789', '0.00', 'prorated', '2700.00', 'prorated', '6677.34', '614.40', '64.00', '0.00', '9377.34', '0.00', 0, 0, 'admin', '2018-09-26 11:31:07', 'admin', '2018-09-26 11:31:39', 0);

-- --------------------------------------------------------

--
-- Table structure for table `employee_information`
--

CREATE TABLE `employee_information` (
  `company_id` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_ind` bigint(20) NOT NULL DEFAULT '1',
  `department` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `team` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `employment_status` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_employed` date DEFAULT NULL,
  `contract_duration` date DEFAULT NULL,
  `healthcard` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `regular_date` date DEFAULT NULL,
  `biometrics_id` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employee_information`
--

INSERT INTO `employee_information` (`company_id`, `company_ind`, `department`, `team`, `employment_status`, `position`, `date_employed`, `contract_duration`, `healthcard`, `regular_date`, `biometrics_id`) VALUES
('2010-1234', 1, 'PMD', 'Administrative Support', 'Regular', 'Employee', '2000-01-01', '0000-00-00', '123456', '2000-01-01', '0'),
('2014-001', 1, 'FULFILLMENT', 'Cost Estimate', 'Regular', 'PROJECT MANAGEMENT SPECIALIST', '2014-01-01', '0000-00-00', '', '2015-01-03', '0'),
('2018-003', 1, 'Fulfillment', 'Project Management and Compliance', 'Regular', 'Project Management Specialist', '2018-01-22', '0000-00-00', '', '2018-07-20', '7'),
('2018-004', 1, 'Fulfillment', 'Project Management and Compliance', 'Project Based', 'Project Management Specialist', '2018-02-05', '2018-08-03', '', '0000-00-00', '5'),
('2018-020', 1, 'Fulfillment', 'Project Management and Compliance', 'Probationary', 'Project Management Specialist', '2018-03-20', '2018-09-20', '', '0000-00-00', '0'),
('2018-021', 1, 'Fulfillment', 'Project Management and Compliance', 'Project Based', 'Project Management Specialist', '2018-03-19', '2018-09-19', '', '0000-00-00', '59'),
('2018-026', 1, 'Fulfillment', 'Project Management and Compliance', 'Project Based', 'Project Management Specialist', '2018-04-23', '2018-10-23', '', '0000-00-00', '0'),
('2018-028', 1, 'Fulfillment', 'Project Management and Compliance', 'Probationary', 'Project Management Specialist', '2018-04-23', '2018-10-23', '', '0000-00-00', '0'),
('2018-031', 1, 'Fulfillment', 'Project Management and Compliance', 'Project Based', 'Project Management Specialist', '2018-05-02', '2018-11-02', '', '0000-00-00', '0'),
('2018-035', 1, 'Fulfillment', 'Project Management and Compliance', 'Project Based', 'Project Management Specialist', '2018-05-21', '2019-02-14', '', '0000-00-00', '13'),
('2018-036', 1, 'Fulfillment', 'Project Management and Compliance', 'Project Based', 'Project Management Specialist', '2018-05-28', '2018-11-28', '', '0000-00-00', '0'),
('2018-038', 1, 'Fulfillment', 'Project Management and Compliance', 'Probationary', 'Project Management Specialist', '2018-06-04', '2018-12-04', '', '0000-00-00', '0'),
('2018-042', 1, 'Fulfillment', 'Project Management and Compliance', 'Probationary', 'Project Management Specialist', '2018-06-25', '2018-12-25', '', '0000-00-00', '0'),
('2018-044', 1, 'Fulfillment', 'Project Management and Compliance', 'Project Based', 'Project Management Specialist', '2018-07-04', '2018-01-04', '', '0000-00-00', '9'),
('2018-045', 1, 'Fulfillment', 'Project Management and Compliance', 'Project Based', 'Project Management Specialist', '2018-07-04', '2019-01-04', '', '0000-00-00', '38'),
('2018-046', 1, 'Fulfillment', 'Project Management and Compliance', 'Project Based', 'Project Management Specialist', '2018-07-03', '2019-01-03', '', '0000-00-00', '0'),
('2018-047', 1, 'Fulfillment', 'Project Management and Compliance', 'Project Based', 'Project Management Specialist', '2018-07-11', '2019-01-11', '', '0000-00-00', '62'),
('2018-048', 1, 'Fulfillment', 'Project Management and Compliance', 'Project Based', 'Project Management Specialist', '2018-07-03', '2019-01-03', '', '0000-00-00', '26'),
('2018-049', 1, 'Fulfillment', 'Project Management and Compliance', 'Project Based', 'Project Management Specialist', '2018-07-04', '2019-01-04', '', '0000-00-00', '0'),
('2018-051', 1, 'Fulfillment', 'Project Management and Compliance', 'Project Based', 'Project Management Specialist', '2018-06-16', '2018-01-16', '', '0000-00-00', '46'),
('2018-053', 1, 'Fulfillment', 'Project Management and Compliance', 'Probationary', 'Project Management Specialist', '2018-07-23', '2019-01-23', '', '0000-00-00', '0'),
('2018-054', 1, 'Fulfillment', 'Project Management and Compliance', 'Probationary', 'Project Management Specialist', '2018-07-26', '2019-01-25', '', '0000-00-00', '0'),
('2018-1940', 1, 'Research and Development', 'Software', 'Probationary', 'Test', '2018-07-04', '2019-01-04', '', '0000-00-00', '0'),
('2018-616', 1, 'Research and Development', 'Software', 'Regular', 'Developer', '2017-11-16', '0000-00-00', '123415345341', '2018-05-16', '11'),
('2018-7675', 1, 'Research and Development', 'Software', 'Regular', 'Developer', '2017-11-16', '0000-00-00', '123415345341', '2018-05-16', '19');

-- --------------------------------------------------------

--
-- Table structure for table `employee_leave`
--

CREATE TABLE `employee_leave` (
  `id` int(11) NOT NULL,
  `company_id` varchar(20) NOT NULL,
  `leave_id` int(11) NOT NULL,
  `used_leave` decimal(8,2) NOT NULL DEFAULT '0.00',
  `balance_leave` decimal(8,2) NOT NULL DEFAULT '0.00',
  `leave_count` decimal(8,2) NOT NULL,
  `total_increment` int(11) NOT NULL DEFAULT '0',
  `deleted` int(11) NOT NULL DEFAULT '0',
  `created_by` varchar(255) NOT NULL,
  `created_date` datetime NOT NULL,
  `lu_by` varchar(255) NOT NULL,
  `lu_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employee_leave`
--

INSERT INTO `employee_leave` (`id`, `company_id`, `leave_id`, `used_leave`, `balance_leave`, `leave_count`, `total_increment`, `deleted`, `created_by`, `created_date`, `lu_by`, `lu_date`) VALUES
(1, '2018-004', 9, '0.50', '29.50', '29.50', 0, 0, 'admin', '2019-02-13 16:56:58', 'admin', '2019-02-13 16:56:58'),
(2, '2018-049', 9, '1.00', '9.00', '9.00', 0, 0, 'admin', '2019-02-13 18:04:43', 'admin', '2019-02-13 18:04:43'),
(3, '2018-003', 14, '0.00', '1.00', '30.00', 0, 0, 'admin', '2019-02-14 10:52:38', 'admin', '2019-02-14 10:52:38');

-- --------------------------------------------------------

--
-- Table structure for table `employee_payroll_group`
--

CREATE TABLE `employee_payroll_group` (
  `ind` bigint(20) NOT NULL,
  `company_id` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `department` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payroll_group_ind` bigint(20) NOT NULL,
  `created_by` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lu_by` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lu_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` int(2) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employee_payroll_group`
--

INSERT INTO `employee_payroll_group` (`ind`, `company_id`, `department`, `name`, `payroll_group_ind`, `created_by`, `created_date`, `lu_by`, `lu_date`, `deleted`) VALUES
(1, '2018-004', 'Fulfillment', 'Abueva, Calvin', 1, 'admin', '2018-08-28 17:07:27', 'admin', '2018-09-21 11:31:21', 1),
(2, '2018-003', 'Fulfillment', 'Agoncillo, Judy Ann', 1, 'admin', '2018-08-28 17:07:27', 'admin', '2019-01-17 14:37:53', 1),
(3, '2018-035', 'Fulfillment', 'Alandy, Adrian ', 3, 'admin', '2018-08-28 17:12:31', 'admin', '2019-01-17 14:37:44', 1),
(4, '2018-044', 'Fulfillment', 'Angelo,Michael', 3, 'admin', '2018-08-28 17:12:31', 'admin', '2019-01-17 14:37:44', 1),
(5, '2018-048', 'Fulfillment', 'Chiu, Kimberly Sue', 3, 'admin', '2018-08-28 17:12:31', 'admin', '2019-04-15 10:14:25', 0),
(6, '2018-053', 'Fulfillment', 'Pingris, Jean Marc', 3, 'admin', '2018-09-03 15:24:20', 'admin', '2018-09-03 15:24:20', 1),
(7, '2018-053', 'Fulfillment', 'Pingris, Jean Marc', 3, 'admin', '2018-09-03 15:25:39', 'admin', '2018-09-04 10:50:04', 1),
(8, '2018-053', 'Fulfillment', 'Pingris, Jean Marc', 3, 'admin', '2018-09-03 15:26:11', 'admin', '2018-09-04 10:50:04', 1),
(9, '2018-053', 'Fulfillment', 'Pingris, Jean Marc', 1, 'admin', '2018-09-04 10:53:59', 'admin', '2018-09-04 10:54:04', 0),
(10, '2018-616', 'Research and Development', 'Beckham, David', 1, 'admin', '2018-09-07 16:06:38', 'admin', '2019-01-17 14:37:53', 1),
(11, '2018-7675', 'Research and Development', 'Bequillo, Kath', 3, 'admin', '2018-09-07 17:42:11', 'admin', '2019-01-17 14:37:44', 1),
(12, '2018-004', 'Fulfillment', 'Abueva, CalviÃ±', 5, 'admin', '2018-09-21 11:31:30', 'admin', '2019-04-15 10:09:40', 0),
(13, '2018-021', 'Fulfillment', 'Herras,Mark Angelo', 4, 'admin', '2018-10-05 18:22:11', 'admin', '2018-10-05 18:23:24', 1),
(14, '2018-021', 'Fulfillment', 'Herras,Mark Angelo', 4, 'admin', '2018-10-05 18:24:27', 'admin', '2018-10-05 18:24:35', 1),
(15, '2018-021', 'Fulfillment', 'Herras, Mark Angelo', 4, 'admin', '2018-10-05 18:31:43', 'admin', '2019-04-15 10:16:28', 0),
(16, '2018-031', 'Fulfillment', 'Malik,Zain', 4, 'admin', '2018-10-05 18:31:43', 'admin', '2018-10-05 18:31:43', 0),
(17, '2018-003', 'Fulfillment', 'Agoncillo, Judy Ann', 7, 'admin', '2019-01-17 14:38:07', 'admin', '2019-04-15 10:11:04', 0),
(18, '2018-035', 'Fulfillment', 'Alandy,Adrian ', 7, 'admin', '2019-01-17 14:38:08', 'admin', '2019-01-17 14:38:08', 0),
(19, '2018-044', 'Fulfillment', 'Angelo, Michael', 7, 'admin', '2019-01-17 14:38:08', 'admin', '2019-04-15 10:12:02', 0),
(20, '2018-616', 'Research and Development', 'Beckham, David', 7, 'admin', '2019-01-17 14:38:08', 'admin', '2019-04-15 10:13:26', 0),
(21, '2018-7675', 'Research and Development', 'Bequillo, Kath', 7, 'admin', '2019-01-17 14:38:08', 'admin', '2019-04-15 10:14:01', 0),
(22, '2018-049', 'Fulfillment', 'Reyes,Christine', 4, 'admin', '2019-01-31 17:48:49', 'admin', '2019-01-31 17:48:49', 0),
(23, '2018-045', 'Fulfillment', 'Gil, Pedro', 1, 'admin', '2019-04-15 10:15:03', 'admin', '2019-04-15 10:15:03', 0),
(24, '2018-051', 'Fulfillment', 'Hermosa, Christine', 1, 'admin', '2019-04-15 10:15:57', 'admin', '2019-04-15 10:15:57', 0),
(25, '2018-047', 'Fulfillment', 'Magdayao, Shaina', 3, 'admin', '2019-04-15 10:17:24', 'admin', '2019-04-15 10:17:24', 0);

-- --------------------------------------------------------

--
-- Table structure for table `employee_schedule`
--

CREATE TABLE `employee_schedule` (
  `id` bigint(20) NOT NULL,
  `company_id` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `template_id` bigint(20) NOT NULL,
  `deleted` int(2) NOT NULL DEFAULT '0',
  `created_by` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lu_by` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lu_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employee_schedule`
--

INSERT INTO `employee_schedule` (`id`, `company_id`, `template_id`, `deleted`, `created_by`, `created_date`, `lu_by`, `lu_date`) VALUES
(1, '2018-045', 1, 0, 'admin', '0000-00-00 00:00:00', 'admin', '2019-04-15 10:15:03'),
(2, '2018-051', 3, 0, 'admin', '0000-00-00 00:00:00', 'admin', '2019-04-15 10:15:57'),
(3, '2018-021', 3, 0, 'admin', '0000-00-00 00:00:00', 'admin', '2019-04-15 10:16:28'),
(4, '2018-047', 3, 0, 'admin', '0000-00-00 00:00:00', 'admin', '2019-04-15 10:17:24'),
(5, '2018-031', 3, 0, 'admin', '0000-00-00 00:00:00', 'admin', '0000-00-00 00:00:00'),
(6, '2018-054', 3, 0, 'admin', '0000-00-00 00:00:00', 'admin', '0000-00-00 00:00:00'),
(7, '2018-046', 3, 0, 'admin', '0000-00-00 00:00:00', 'admin', '0000-00-00 00:00:00'),
(8, '2018-042', 3, 0, 'admin', '0000-00-00 00:00:00', 'admin', '0000-00-00 00:00:00'),
(9, '2018-036', 3, 0, 'admin', '0000-00-00 00:00:00', 'admin', '0000-00-00 00:00:00'),
(10, '2018-053', 3, 0, 'admin', '0000-00-00 00:00:00', 'admin', '2018-09-04 10:54:04'),
(11, '2018-049', 3, 0, 'admin', '0000-00-00 00:00:00', 'admin', '0000-00-00 00:00:00'),
(12, '2018-038', 3, 0, 'admin', '0000-00-00 00:00:00', 'admin', '0000-00-00 00:00:00'),
(13, '2010-1234', 3, 0, 'admin', '0000-00-00 00:00:00', 'admin', '0000-00-00 00:00:00'),
(14, '2018-020', 3, 0, 'admin', '0000-00-00 00:00:00', 'admin', '0000-00-00 00:00:00'),
(15, '2018-026', 3, 0, 'admin', '0000-00-00 00:00:00', 'admin', '0000-00-00 00:00:00'),
(16, '2018-028', 3, 0, 'admin', '0000-00-00 00:00:00', 'admin', '0000-00-00 00:00:00'),
(17, '2018-1940', 3, 0, 'admin', '0000-00-00 00:00:00', 'admin', '0000-00-00 00:00:00'),
(18, '2018-004', 1, 0, 'admin', '0000-00-00 00:00:00', 'admin', '2019-04-15 10:09:40'),
(19, '2018-003', 3, 0, 'admin', '2018-09-03 15:22:31', 'admin', '2019-04-15 10:11:04'),
(20, '2018-035', 3, 0, 'admin', '2018-09-03 17:21:59', 'admin', '2019-01-15 13:34:29'),
(21, '2018-044', 3, 0, 'admin', '2018-09-03 17:21:59', 'admin', '2019-04-15 10:12:02'),
(22, '2018-048', 3, 0, 'admin', '2018-09-03 17:21:59', 'admin', '2019-04-15 10:14:25'),
(23, '2018-616', 3, 0, 'admin', '2018-09-07 16:06:38', 'admin', '2019-04-15 10:13:26'),
(24, '2018-7675', 3, 0, 'admin', '2018-09-07 17:42:11', 'admin', '2019-04-15 10:14:01');

-- --------------------------------------------------------

--
-- Table structure for table `employee_schedule_request`
--

CREATE TABLE `employee_schedule_request` (
  `id` bigint(20) NOT NULL,
  `company_id` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `template_id` bigint(20) NOT NULL,
  `start_date` date NOT NULL,
  `infinit` int(2) NOT NULL DEFAULT '0',
  `status` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ON',
  `end_date` date NOT NULL,
  `deleted` int(2) NOT NULL DEFAULT '0',
  `approved_1_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approved_2_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approved_1` int(11) NOT NULL DEFAULT '0',
  `approved_2` int(11) NOT NULL DEFAULT '0',
  `request_status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `created_by` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lu_by` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lu_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employee_schedule_request`
--

INSERT INTO `employee_schedule_request` (`id`, `company_id`, `template_id`, `start_date`, `infinit`, `status`, `end_date`, `deleted`, `approved_1_id`, `approved_2_id`, `approved_1`, `approved_2`, `request_status`, `created_by`, `created_date`, `lu_by`, `lu_date`) VALUES
(5, '2018-003', 2, '2018-09-04', 0, 'ON', '2018-09-05', 0, NULL, NULL, 0, 0, 'PENDING', 'admin', '2018-09-04 13:26:14', 'admin', '2018-09-04 13:26:14'),
(6, '2018-003', 1, '2019-01-17', 0, 'ON', '2019-02-18', 1, NULL, NULL, 0, 0, 'PENDING', 'admin', '2018-09-04 14:56:52', 'admin', '2018-10-09 18:21:56'),
(9, '2018-049', 3, '2019-02-01', 0, 'ON', '2019-02-28', 0, NULL, NULL, 0, 0, 'PENDING', 'admin', '2019-02-13 18:03:28', 'admin', '2019-02-13 18:03:28'),
(13, '2018-004', 2, '2019-03-14', 0, 'ON', '2019-03-14', 0, '2018-004', '2018-004', 1, 1, 'APPROVED', 'Admin', '2019-03-14 10:10:03', 'Admin', '2019-03-14 10:10:03'),
(14, '2018-003', 1, '2019-03-14', 0, 'ON', '2019-03-14', 0, '2018-004', '2018-004', 1, 1, 'APPROVED', 'Admin', '2019-03-14 15:06:59', 'Admin', '2019-03-14 15:06:59'),
(15, '2018-035', 1, '2019-03-14', 0, 'ON', '2019-03-14', 0, '2018-004', '2018-004', 1, 1, 'APPROVED', 'Admin', '2019-03-14 15:07:21', 'Admin', '2019-03-14 15:07:21'),
(16, '2018-044', 1, '2019-03-14', 0, 'ON', '2019-03-14', 0, '2018-004', '2018-004', 1, 1, 'APPROVED', 'Admin', '2019-03-14 15:07:21', 'Admin', '2019-03-14 15:07:21'),
(17, '2018-616', 1, '2019-03-14', 0, 'ON', '2019-03-14', 0, '2018-004', '2018-004', 1, 1, 'APPROVED', 'Admin', '2019-03-14 15:07:21', 'Admin', '2019-03-14 15:07:21'),
(18, '2018-7675', 1, '2019-03-14', 0, 'ON', '2019-03-14', 0, '2018-004', '2018-004', 1, 1, 'APPROVED', 'Admin', '2019-03-14 15:07:22', 'Admin', '2019-03-14 15:07:22'),
(19, '2014-001', 1, '2019-03-14', 0, 'ON', '2019-03-14', 0, '2018-004', '2018-004', 1, 1, 'CANCELLED', 'Admin', '2019-03-14 15:08:30', 'Admin', '2019-03-14 15:08:30');

-- --------------------------------------------------------

--
-- Table structure for table `employment_history`
--

CREATE TABLE `employment_history` (
  `ind` bigint(20) NOT NULL,
  `company_id` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `employer_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employment_history`
--

INSERT INTO `employment_history` (`ind`, `company_id`, `employer_name`, `position`, `start_date`, `end_date`) VALUES
(1, '2018-028', 'AC Trojan Industries, Inc.', 'Account Executive', '2017-09-27', '2018-04-01'),
(2, '2018-028', 'Quantum Solutions (Phil) Inc.', 'Supervisor', '2013-10-15', '2017-01-31'),
(3, '2018-028', 'AVT Printing Services', 'Administrative Assistant', '2012-01-01', '2013-10-15'),
(4, '2018-028', 'AVT Printing Services', 'Finishing Area Unit Head', '2008-11-01', '2011-12-01'),
(5, '2018-1940', 'test', 'test', '2000-02-02', '2000-03-03');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` bigint(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `created_by` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `modified_by` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `modified` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=Active, 0=Block'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `date`, `created_by`, `created`, `modified_by`, `modified`, `status`) VALUES
(1, 'trial', '2018-10-04', 'admin', '2018-10-04 09:53:06', 'admin', '2018-10-04 09:54:01', 0),
(2, 'Trial Event', '2018-10-04', 'admin', '2018-10-04 09:58:05', 'admin', '2018-10-04 09:58:05', 1),
(3, 'Super Holiday', '2018-10-10', 'admin', '2018-10-09 17:59:37', 'admin', '2018-10-09 18:00:17', 0),
(4, 'Super Holiday', '2018-10-10', 'admin', '2018-10-09 17:59:42', 'admin', '2018-10-09 17:59:42', 1),
(5, 'Trial', '2018-10-10', 'admin', '2018-10-09 18:04:04', 'admin', '2018-10-09 18:05:35', 0),
(6, 'tris', '2018-10-10', 'admin', '2018-10-09 18:17:29', 'admin', '2018-10-09 18:19:07', 0),
(7, 'treasaad', '2018-10-10', 'admin', '2018-10-09 18:19:15', 'admin', '2018-10-09 18:19:18', 0),
(8, 'Trial', '2018-11-05', 'admin', '2018-11-05 15:38:34', 'admin', '2018-11-05 15:38:34', 1);

-- --------------------------------------------------------

--
-- Table structure for table `family_background`
--

CREATE TABLE `family_background` (
  `company_id` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `father_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `father_birthdate` date DEFAULT NULL,
  `father_status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `father_occupation` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mother_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mother_birthdate` date DEFAULT NULL,
  `mother_status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mother_occupation` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `spouse_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `spouse_birthdate` date DEFAULT NULL,
  `spouse_occupation` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `family_background`
--

INSERT INTO `family_background` (`company_id`, `father_name`, `father_birthdate`, `father_status`, `father_occupation`, `mother_name`, `mother_birthdate`, `mother_status`, `mother_occupation`, `spouse_name`, `spouse_birthdate`, `spouse_occupation`) VALUES
('2014-001', '', '0000-00-00', '', '', '', '0000-00-00', '', '', '', '0000-00-00', ''),
('2018-003', 'Numeriano Benedicto', '0000-00-00', 'Widow', 'Deceased', 'Evelyn Benedicto', '0000-00-00', 'Widow', 'Deceased', '', '0000-00-00', ''),
('2018-004', 'Rommel Argales', '0000-00-00', 'Married', 'Painter', 'Narlyn Cruz', '0000-00-00', 'Married', 'Housewife', '', '0000-00-00', ''),
('2018-020', 'Delio Petras', '0000-00-00', 'Married', 'Technician', 'Anna Abad', '0000-00-00', 'Married', 'Housewife', 'Joann Petras', '0000-00-00', 'Housewife'),
('2018-021', 'Ricardo Esperida', '0000-00-00', 'Married', 'Tailor', 'Milagros Esperida', '0000-00-00', 'Married', 'Housewife', '', '0000-00-00', ''),
('2018-026', 'Samuel Delmonte Villareal', '1944-09-18', 'Married', '', 'Liberata Calvo Villareal', '1950-12-23', '', '', '', '0000-00-00', ''),
('2018-028', 'Virgilio Dalupe', '0000-00-00', 'Married', 'Retired Soldier', 'Elizabeth Befrangco', '0000-00-00', 'Married', 'Housewife', 'Ahrvie Bautista Valdez', '1989-06-29', 'Graphic Artist'),
('2018-031', 'Ronaldo Villorente', '0000-00-00', 'Married', 'Auto-Mechanic', 'Imelda Villorente', '0000-00-00', 'Married', 'Housewife', '', '0000-00-00', ''),
('2018-035', 'Lurico Sarmiento', '0000-00-00', 'Married', 'Retired CPA', 'Leticia Sarmiento', '0000-00-00', 'Married', 'Retired Bookkeeper ', '', '0000-00-00', ''),
('2018-036', 'Los Santos Loren', '1969-05-18', 'Married', 'Tricycle Driver', 'Nora Loren', '1970-04-01', 'Married', 'Housewife', '', '0000-00-00', ''),
('2018-038', 'Rolando Caburnay', '0000-00-00', 'Married', 'Surveyor/Lineman', 'Annielyn Caburnay', '0000-00-00', 'Married', 'Factory Worker', '', '0000-00-00', ''),
('2018-042', 'Richard Romeo Ramos', '1987-11-12', 'Married', 'OFW - Saudi', 'Lina Alcanzaren Valdez', '1988-04-12', 'Married', 'Housewife', '', '0000-00-00', ''),
('2018-044', 'Angelo Novelo', '0000-00-00', 'Married', 'Farmer', 'Amelia Novelo', '0000-00-00', 'Married', 'Farmer', '', '0000-00-00', ''),
('2018-045', 'Amado Encallado', '0000-00-00', 'Married', 'Farmer', 'Rosalie Encallado', '0000-00-00', 'Married', 'Housewife', '', '0000-00-00', ''),
('2018-046', 'Felimon Cabadido', '0000-00-00', 'Widow', 'Farmer', 'Melita Cabadido', '0000-00-00', '', 'Housewife', '', '0000-00-00', ''),
('2018-047', 'Elmer Dayao', '0000-00-00', 'Married', 'Fruit Vendor', 'Meridy Dayao', '0000-00-00', 'Married', 'Fruit Vendor', '', '0000-00-00', ''),
('2018-048', 'Frederick Jimmifer Serrano Aguilar', '0000-00-00', 'Married', '', 'Ma Fe Villamarin Aguilar', '0000-00-00', 'Married', '', '', '0000-00-00', ''),
('2018-049', 'Danilo Margarejo Reyes', '1959-09-25', 'Married', 'Driver', 'Carmen Abad Reyes', '1967-05-15', 'Married', 'Housewife', '', '0000-00-00', ''),
('2018-051', 'Alberto Morillo', '0000-00-00', 'Married', 'Businessman', 'Lilian Morillo', '0000-00-00', 'Married', 'Housewife', '', '0000-00-00', ''),
('2018-053', 'Armando Ornedo', '0000-00-00', 'Married', 'Retired School Service Operator', 'Edith Ornedo', '0000-00-00', 'Married', 'Director', '', '0000-00-00', ''),
('2018-054', 'Luisito Dimaala', '0000-00-00', 'Married', '', 'Jennifer Dimaala', '0000-00-00', 'Married', 'Housekeeping ', '', '0000-00-00', ''),
('2018-1940', 'test', '1959-02-22', 'Married', 'test', 'test', '1967-05-15', 'Single', 'test', '', '0000-00-00', ''),
('2018-616', 'Miguel Belarmino', '1958-05-08', 'Married', 'None', 'Renita Belarmino', '1969-07-05', 'Married', 'None', '', '0000-00-00', ''),
('2018-7675', 'Miguel Belarmino', '1962-05-08', 'Married', 'None', 'Renita Belarmino', '1968-07-05', 'Married', 'None', '', '0000-00-00', '');

-- --------------------------------------------------------

--
-- Table structure for table `goverment_number`
--

CREATE TABLE `goverment_number` (
  `company_id` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sss_number` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tin_number` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pagibig_number` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `philhealth_number` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tax_status_ind` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `goverment_number`
--

INSERT INTO `goverment_number` (`company_id`, `sss_number`, `tin_number`, `pagibig_number`, `philhealth_number`, `tax_status_ind`) VALUES
('2014-001', '12-3456789-0', '123-456-789', '1080-0068-8393', '19-200627934-8', 0),
('2018-003', '3423001599', '000000000', '121216587906', '190261150666', 2),
('2018-004', '3459892523', '712078042', '121198413226', '020268963201', 2),
('2018-020', '3382988931', '420166536', '102003200052', '030504539209', 11),
('2018-021', '3413620207', '266757688', '121016708150', '010253689442', 4),
('2018-026', '3474662343', '000000000', '121222695413', '000000000000', 2),
('2018-028', '3414167976', '271385289', '121068208648', '080511228283', 3),
('2018-031', '3475364909', '346430850', '121224643458', '000000000000', 2),
('2018-035', '3472796567', '000000000', '121225383698', '022502881837', 2),
('2018-036', '3456706063', '000000000', '121225605191', '000000000000', 2),
('2018-038', '3422142420', '314120318', '121084706567', '020506734607', 2),
('2018-042', '0240558189', '334609534', '121189797660', '050255258238', 4),
('2018-044', '0506847738', '949404154', '121218577616', '100500941264', 2),
('2018-045', '0426563095', '456067610', '121078583010', '080512482264', 2),
('2018-046', '0124890057', '465903346', '121140511612', '020265185399', 2),
('2018-047', '3476268444', '000000000', '121227101065', '020270187820', 2),
('2018-048', '3476899703', '000000000', '121228790807', '030263070859', 2),
('2018-049', '3473990171', '000000000', '121225134482', '030262880903', 2),
('2018-051', '3465876342', '337164024', '121193718471', '030259288009', 2),
('2018-053', '3427005216', '310940095', '121144860331', '210250773271', 2),
('2018-054', '3475350489', '000000000', '121224522164', '012508901087', 2),
('2018-1940', '200000000', '123154', '05642012', '005945320', 2),
('2018-616', '123456', '789456', '654321', '987654', 2),
('2018-7675', '123654', '123456', '68547', '258963', 2);

-- --------------------------------------------------------

--
-- Table structure for table `goverment_upload`
--

CREATE TABLE `goverment_upload` (
  `ind` bigint(20) NOT NULL,
  `company_id` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `goverment` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` int(11) NOT NULL,
  `created_by` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `goverment_upload`
--

INSERT INTO `goverment_upload` (`ind`, `company_id`, `goverment`, `file`, `type`, `size`, `created_by`, `created_date`) VALUES
(2, '2018-004', 'SSS', '32450-background.jpg', 'image/jpeg', 186793, 'admin', '2018-10-09 18:26:12'),
(3, '2018-035', 'PAGIBIG', '98773-chrysanthemum.jpg', 'image/jpeg', 879394, 'admin', '2018-11-23 15:52:39'),
(4, '2018-035', 'SSS', '29246-tulips.jpg', 'image/jpeg', 620888, 'admin', '2018-11-23 15:53:22'),
(5, '2018-035', 'TIN', '39898-f44f072ee9b77cb030cc6632b9ea746b.jpg', 'image/jpeg', 22912, 'admin', '2019-01-15 13:49:42'),
(6, '2018-035', 'PHILHEALTH', '35837-f44f072ee9b77cb030cc6632b9ea746b.jpg', 'image/jpeg', 22912, 'admin', '2019-01-15 13:55:03');

-- --------------------------------------------------------

--
-- Table structure for table `group_schedule`
--

CREATE TABLE `group_schedule` (
  `ind` bigint(20) NOT NULL,
  `group_ind` bigint(20) NOT NULL,
  `type` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Regular Shift',
  `reg_in` time DEFAULT '08:00:00',
  `reg_out` time DEFAULT '17:00:00',
  `mon_in` time DEFAULT NULL,
  `mon_out` time DEFAULT NULL,
  `mon` int(2) NOT NULL DEFAULT '1',
  `tue_in` time DEFAULT NULL,
  `tue_out` time DEFAULT NULL,
  `tue` int(2) NOT NULL DEFAULT '1',
  `wed_in` time DEFAULT NULL,
  `wed_out` time DEFAULT NULL,
  `wed` int(11) NOT NULL DEFAULT '1',
  `thu_in` time DEFAULT NULL,
  `thu_out` time DEFAULT NULL,
  `thu` int(2) NOT NULL DEFAULT '1',
  `fri_in` time DEFAULT NULL,
  `fri_out` time DEFAULT NULL,
  `fri` int(2) NOT NULL DEFAULT '1',
  `sat_in` time DEFAULT NULL,
  `sat_out` time DEFAULT NULL,
  `sat` int(2) NOT NULL DEFAULT '0',
  `sun_in` time DEFAULT NULL,
  `sun_out` time DEFAULT NULL,
  `sun` int(2) NOT NULL DEFAULT '0',
  `flexihrs` decimal(8,2) NOT NULL DEFAULT '8.00',
  `deleted` int(2) NOT NULL DEFAULT '0',
  `created_by` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lu_by` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lu_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `group_schedule`
--

INSERT INTO `group_schedule` (`ind`, `group_ind`, `type`, `reg_in`, `reg_out`, `mon_in`, `mon_out`, `mon`, `tue_in`, `tue_out`, `tue`, `wed_in`, `wed_out`, `wed`, `thu_in`, `thu_out`, `thu`, `fri_in`, `fri_out`, `fri`, `sat_in`, `sat_out`, `sat`, `sun_in`, `sun_out`, `sun`, `flexihrs`, `deleted`, `created_by`, `created_date`, `lu_by`, `lu_date`) VALUES
(1, 1, 'Regular Shift', '08:30:00', '18:30:00', NULL, NULL, 1, NULL, NULL, 1, NULL, NULL, 1, NULL, NULL, 1, NULL, NULL, 1, NULL, NULL, 0, NULL, NULL, 0, '8.00', 0, 'admin', '2018-08-28 16:51:36', 'admin', '2018-08-28 17:06:17'),
(2, 2, 'Regular Shift', '08:00:00', '17:00:00', NULL, NULL, 1, NULL, NULL, 1, NULL, NULL, 1, NULL, NULL, 1, NULL, NULL, 1, NULL, NULL, 0, NULL, NULL, 0, '8.00', 1, 'admin', '2018-08-28 17:09:12', 'admin', '2018-08-28 17:12:09'),
(3, 3, 'Regular Shift', '08:00:00', '17:00:00', NULL, NULL, 1, NULL, NULL, 1, NULL, NULL, 1, NULL, NULL, 1, NULL, NULL, 1, NULL, NULL, 0, NULL, NULL, 0, '8.00', 0, 'admin', '2018-08-28 17:09:17', 'admin', '2018-08-28 17:11:49');

-- --------------------------------------------------------

--
-- Table structure for table `leave_template`
--

CREATE TABLE `leave_template` (
  `id` bigint(20) NOT NULL,
  `leave_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `days_leave` int(3) NOT NULL,
  `paid` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `available_status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lu_by` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lu_date` datetime NOT NULL,
  `deleted` smallint(2) NOT NULL DEFAULT '0',
  `leave_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `increment` int(11) DEFAULT '0',
  `year` int(11) DEFAULT '0',
  `max_days` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `leave_template`
--

INSERT INTO `leave_template` (`id`, `leave_name`, `days_leave`, `paid`, `available_status`, `created_by`, `created_date`, `lu_by`, `lu_date`, `deleted`, `leave_type`, `increment`, `year`, `max_days`) VALUES
(1, 'My Leave', 10, 'yes', 'Regular', 'admin', '2018-06-19 10:44:58', 'admin', '2018-06-20 11:43:52', 1, '', 0, 0, 0),
(2, 'Sick Leave', 5, 'Yes', 'Regular', 'admin', '2018-06-19 15:06:35', 'admin', '2018-06-20 13:37:11', 1, '', 0, 0, 0),
(3, 'Leave', 99, 'No', 'Contractual', 'admin', '2018-06-19 15:07:36', '', '2018-06-19 17:26:06', 1, '', 0, 0, 0),
(4, 'Vacation Leave', 5, 'Yes', 'Contractual', 'admin', '2018-06-19 15:10:29', 'admin', '2018-06-20 14:35:47', 0, '', 0, 0, 0),
(5, 'TEST', 95, 'No', 'test', 'admin', '2018-06-19 17:30:23', 'admin', '2018-06-19 17:30:47', 1, '', 0, 0, 0),
(6, 'VL', 56, 'Yes', 'None', 'admin', '2018-06-19 17:35:42', 'admin', '2018-06-19 17:35:50', 1, '', 0, 0, 0),
(9, 'Leave Now', 10, 'Yes', 'Regular', 'admin', '2018-06-20 11:44:21', 'admin', '2019-02-12 18:13:55', 0, 'Prorated', 0, 0, 0),
(10, 'Val', 5, 'No', 'Contractual', 'admin', '2018-06-20 11:45:11', 'admin', '2018-06-20 14:10:57', 1, '', 0, 0, 0),
(12, 'SASDA', 5, 'No', 'dsadsa', 'admin', '2018-06-20 13:37:49', 'admin', '2018-06-20 13:38:12', 1, '', 0, 0, 0),
(13, 'Sick Leave', 9, 'Yes', 'Regular', 'admin', '2018-06-20 14:52:46', 'admin', '2018-06-20 14:52:46', 0, '', 0, 0, 0),
(14, 'Maternity Leave', 30, 'Yes', 'Regular', 'admin', '2018-06-20 14:54:52', 'admin', '2018-06-20 14:54:52', 0, '', 0, 0, 0),
(15, 'Leave Without Pay', 365, 'No', '', 'admin', '2019-02-12 18:13:25', 'admin', '2019-02-12 18:13:25', 0, 'Fixed', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `licence_information`
--

CREATE TABLE `licence_information` (
  `ind` bigint(20) NOT NULL,
  `company_id` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `licence_no` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_issue` date DEFAULT NULL,
  `date_valid` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medical_allergies`
--

CREATE TABLE `medical_allergies` (
  `ind` bigint(20) NOT NULL,
  `company_id` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `allergies_description` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medical_illness`
--

CREATE TABLE `medical_illness` (
  `ind` bigint(20) NOT NULL,
  `company_id` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `illness_history` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `covered_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medical_records`
--

CREATE TABLE `medical_records` (
  `company_id` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `class_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `class_description` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `allergie` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `drugtest_result` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `test_location` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_tested` date NOT NULL,
  `hypertension` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `underweight` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `overweight` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `other_reason` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reason_remarks` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `medical_records`
--

INSERT INTO `medical_records` (`company_id`, `class_type`, `class_description`, `allergie`, `drugtest_result`, `test_location`, `date_tested`, `hypertension`, `underweight`, `overweight`, `other_reason`, `reason_remarks`) VALUES
('2018-049', 'Class A', 'Fit to work', '', 'Passed', 'Sacred Heart Multispecialty Clinic And Diagnostic Center Inc.', '2018-07-23', 'no', 'no', 'no', 'no', ''),
('2018-042', 'Class A', 'Fit to work', '', 'Passed', 'Sacred Heart Multispecialty Clinic And Diagnostic Center Inc.', '2018-06-23', 'no', 'no', 'no', 'no', ''),
('2018-026', 'Class A', 'Fit to work', '', 'Passed', 'Sacred Heart Multispecialty Clinic And Diagnostic Center Inc.', '2018-04-17', 'no', 'no', 'no', 'no', ''),
('2018-048', 'Class A', 'Fit to work', '', 'Passed', 'Sacred Heart Multispecialty Clinic And Diagnostic Center Inc.', '2018-07-02', 'no', 'no', 'no', 'no', ''),
('2018-028', 'Class A', 'Fit to work', '', 'Passed', 'Sacred Heart Multispecialty Clinic And Diagnostic Center Inc.', '2018-04-18', 'no', 'no', 'no', 'no', ''),
('2018-035', 'Class A', 'Fit to work', '', 'Passed', 'Sacred Heart Multispecialty Clinic And Diagnostic Center Inc.', '2018-05-17', 'no', 'no', 'no', 'no', ''),
('2018-036', 'Class A', 'Fit to work', '', '', 'Sacred Heart Multispecialty Clinic And Diagnostic Center Inc.', '0000-00-00', 'no', 'no', 'no', 'no', ''),
('2018-021', 'Class A', 'Fit to work', '', 'Passed', 'Sacred Heart Multispecialty Clinic And Diagnostic Center Inc.', '2018-03-15', 'no', 'no', 'no', 'no', ''),
('2018-003', 'Class A', 'Fit to work', '', 'Passed', 'Sacred Heart Multispecialty Clinic And Diagnostic Center Inc.', '2018-01-17', 'no', 'no', 'no', 'no', ''),
('2018-031', 'Class A', 'Fit to work', '', 'Passed', 'Sacred Heart Multispecialty Clinic And Diagnostic Center Inc.', '2018-06-23', 'no', 'no', 'no', 'no', ''),
('2018-004', 'Class A', 'Fit to work', '', 'Passed', 'Sacred Heart Multispecialty Clinic And Diagnostic Center Inc.', '2018-02-01', 'no', 'no', 'no', 'no', ''),
('2018-038', 'Class A', 'Fit to work', '', 'Passed', 'Sacred Heart Multispecialty Clinic And Diagnostic Center Inc.', '2018-06-02', 'no', 'no', 'no', 'no', ''),
('2018-020', 'Class A', 'Fit to work', '', 'Passed', 'Sacred Heart Multispecialty Clinic And Diagnostic Center Inc.', '2018-03-10', 'no', 'no', 'no', 'no', ''),
('2018-053', 'Class A', 'Fit to work', '', 'Passed', 'Sacred Heart Multispecialty Clinic And Diagnostic Center Inc.', '2018-07-16', 'no', 'no', 'no', 'no', ''),
('2018-054', 'Class A', 'Fit to work', '', 'Passed', 'Sacred Heart Multispecialty Clinic And Diagnostic Center Inc.', '2018-07-21', 'no', 'no', 'no', 'no', ''),
('2018-047', 'Class A', 'Fit to work', '', 'Passed', 'Sacred Heart Multispecialty Clinic And Diagnostic Center Inc.', '2018-07-07', 'no', 'no', 'no', 'no', ''),
('2018-046', 'Class A', 'Fit to work', '', 'Passed', 'Sacred Heart Multispecialty Clinic And Diagnostic Center Inc.', '2018-08-10', 'no', 'no', 'no', 'no', ''),
('2018-045', 'Class A', 'Fit to work', '', 'Passed', 'Sacred Heart Multispecialty Clinic And Diagnostic Center Inc.', '2018-06-21', 'no', 'no', 'no', 'no', ''),
('2018-044', 'Class A', 'Fit to work', '', 'Passed', 'Sacred Heart Multispecialty Clinic And Diagnostic Center Inc.', '2018-06-21', 'no', 'no', 'no', 'no', ''),
('2018-051', 'Class A', 'Fit to work', '', 'Passed', 'Sacred Heart Multispecialty Clinic And Diagnostic Center Inc.', '2018-07-07', 'no', 'no', 'no', 'no', ''),
('2018-1940', 'Class A', 'Fit to work', '', 'Passed', 'Sacred Heart Multispecialty Clinic And Diagnostic Center Inc.', '0000-00-00', 'no', 'no', 'no', 'no', ''),
('2018-616', 'Class A', 'Fit to work', '', 'Passed', 'Sacred Heart Multispecialty Clinic And Diagnostic Center Inc.', '2017-10-26', 'no', 'no', 'no', 'no', ''),
('2018-7675', 'Class A', 'Fit to work', '', 'Passed', 'Sacred Heart Multispecialty Clinic And Diagnostic Center Inc.', '2017-10-25', 'no', 'no', 'no', 'no', ''),
('2014-001', 'Class A', 'Fit to work', '', '', '', '0000-00-00', 'no', 'no', 'no', 'no', '');

-- --------------------------------------------------------

--
-- Table structure for table `memo_upload`
--

CREATE TABLE `memo_upload` (
  `ind` bigint(20) NOT NULL,
  `company_id` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `memo_number` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` int(11) NOT NULL,
  `lu_by` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lu_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `memo_upload`
--

INSERT INTO `memo_upload` (`ind`, `company_id`, `memo_number`, `file`, `type`, `size`, `lu_by`, `lu_date`) VALUES
(1, '2018-004', 'RND-012', '69191-background.jpg', 'image/jpeg', 186793, 'admin', '2018-10-10 16:25:38'),
(2, '2018-004', 'RND-013', '40148-background.jpg', 'image/jpeg', 186793, 'admin', '2018-10-16 10:57:04'),
(3, '2018-616', 'DBF20123', '40551-f44f072ee9b77cb030cc6632b9ea746b.jpg', 'image/jpeg', 22912, 'admin', '2019-01-15 14:34:59');

-- --------------------------------------------------------

--
-- Table structure for table `notice_decision`
--

CREATE TABLE `notice_decision` (
  `ind` bigint(20) NOT NULL,
  `company_id` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `memo_number` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_release` date DEFAULT NULL,
  `date_return` date DEFAULT NULL,
  `sanction` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_date` datetime NOT NULL,
  `lu_by` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lu_date` datetime NOT NULL,
  `finalize` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remarks` varchar(2000) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notice_decision`
--

INSERT INTO `notice_decision` (`ind`, `company_id`, `memo_number`, `date_release`, `date_return`, `sanction`, `created_by`, `created_date`, `lu_by`, `lu_date`, `finalize`, `remarks`) VALUES
(1, '2018-004', 'RND-012', '2018-10-01', '2018-10-02', '3 days Suspension', 'admin', '2018-10-04 18:46:43', 'admin', '2018-10-10 16:26:29', 'yes', 'Trial'),
(2, '2018-004', 'RND-013', '2018-10-18', '2018-10-21', '3 days Suspension', 'admin', '2018-10-10 16:29:49', 'admin', '2018-10-16 11:22:07', 'yes', 'No remarks'),
(3, '2018-616', 'DBF20123', NULL, NULL, '', 'admin', '2019-01-15 14:31:23', 'admin', '2019-01-15 14:31:23', 'no', ''),
(4, '2018-616', 'RZZND124232', NULL, NULL, '', 'admin', '2019-01-15 14:41:48', 'admin', '2019-01-15 14:41:48', 'no', '');

-- --------------------------------------------------------

--
-- Table structure for table `notice_explain`
--

CREATE TABLE `notice_explain` (
  `ind` bigint(20) NOT NULL,
  `company_id` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `report_by` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `violation_class` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `violation_desc` varchar(2000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `memo_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_release` date DEFAULT NULL,
  `date_return` date DEFAULT NULL,
  `created_by` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_date` datetime NOT NULL,
  `lu_by` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lu_date` datetime NOT NULL,
  `finalize` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notice_explain`
--

INSERT INTO `notice_explain` (`ind`, `company_id`, `report_by`, `violation_class`, `violation_desc`, `memo_number`, `date_release`, `date_return`, `created_by`, `created_date`, `lu_by`, `lu_date`, `finalize`) VALUES
(1, '2018-004', 'Marlon Belarmino', '1-Verbal Warning', 'Late', 'RND-012', '2018-10-04', '2018-10-31', 'admin', '2018-10-04 18:46:21', 'admin', '2018-10-04 18:46:43', 'yes'),
(2, '2018-004', 'marlon', '1-Verbal Warning', 'Eating', 'RND-013', '2018-10-10', '2018-10-11', 'admin', '2018-10-10 16:29:49', 'admin', '2018-10-10 16:29:49', 'yes'),
(3, '2018-616', 'Marlon Belarmino', '2-Written Warning', 'Over Work', 'RZZND124232', '2018-10-16', '2018-10-17', 'admin', '2018-10-16 10:47:46', 'admin', '2019-01-15 14:41:48', 'yes'),
(4, '2018-616', 'Micole Arevalo', '1-Verbal Warning', 'Wala Lang', 'DBF20123', '2019-01-15', '2019-01-17', 'admin', '2019-01-15 14:31:23', 'admin', '2019-01-15 14:31:23', 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `payroll_group`
--

CREATE TABLE `payroll_group` (
  `ind` bigint(20) NOT NULL,
  `company_ind` bigint(20) NOT NULL,
  `group_name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `group_desc` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lu_by` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lu_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` int(2) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payroll_group`
--

INSERT INTO `payroll_group` (`ind`, `company_ind`, `group_name`, `group_desc`, `created_by`, `created_date`, `lu_by`, `lu_date`, `deleted`) VALUES
(1, 1, 'Group 1', 'After some months it got edited to an inline code', 'admin', '2018-08-28 16:51:36', 'admin', '2018-08-28 16:51:36', 0),
(2, 1, 'Group 2', '', 'admin', '2018-08-28 17:09:12', 'admin', '2018-08-28 17:12:09', 1),
(3, 1, 'Group 2', '', 'admin', '2018-08-28 17:09:17', 'admin', '2018-08-28 17:09:17', 0),
(4, 1, 'CSI Group', 'Trial Group With Descriptions. Saving!', 'admin', '2018-09-03 16:18:28', 'admin', '2018-09-03 16:18:28', 0),
(5, 1, 'CSI Group 3', 'No SSS', 'admin', '2018-09-21 11:30:58', 'admin', '2018-09-21 11:30:58', 0),
(6, 1, 'CSI Group 34', 'tris', 'admin', '2018-11-19 11:13:03', 'admin', '2018-11-19 11:13:03', 0),
(7, 1, 'Demo Group', 'Trial', 'admin', '2019-01-17 14:37:16', 'admin', '2019-01-17 14:37:16', 0);

-- --------------------------------------------------------

--
-- Table structure for table `payroll_settings`
--

CREATE TABLE `payroll_settings` (
  `id` bigint(20) NOT NULL,
  `group_ind` bigint(20) NOT NULL,
  `overtime_app` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Yes',
  `late_app` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Yes',
  `undertime_app` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Yes',
  `night_diff_app` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Yes',
  `absent_app` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Yes',
  `holiday_app` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Yes',
  `ecola_app` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'No',
  `with_sss` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Yes',
  `with_philhealth` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Yes',
  `with_pagibig` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Yes',
  `with_withholdingtax` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Yes',
  `minimum_wage` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'No',
  `with_previous_emp` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'No',
  `exclude_payroll` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'No',
  `exclude_tar` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'No',
  `lunch_out` time NOT NULL DEFAULT '12:00:00',
  `lunch_in` time NOT NULL DEFAULT '13:00:00',
  `lunch_hrs` int(11) NOT NULL DEFAULT '1',
  `late_settings_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'accumulated',
  `late_allow_min` decimal(8,2) NOT NULL DEFAULT '0.00',
  `gov_deduction` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Every Payroll',
  `tax_deduction` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Every Payroll',
  `before_holiday_absent` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Yes',
  `after_holiday_absent` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'No',
  `created_by` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lu_by` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lu_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `deleted` smallint(2) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payroll_settings`
--

INSERT INTO `payroll_settings` (`id`, `group_ind`, `overtime_app`, `late_app`, `undertime_app`, `night_diff_app`, `absent_app`, `holiday_app`, `ecola_app`, `with_sss`, `with_philhealth`, `with_pagibig`, `with_withholdingtax`, `minimum_wage`, `with_previous_emp`, `exclude_payroll`, `exclude_tar`, `lunch_out`, `lunch_in`, `lunch_hrs`, `late_settings_type`, `late_allow_min`, `gov_deduction`, `tax_deduction`, `before_holiday_absent`, `after_holiday_absent`, `created_by`, `created_date`, `lu_by`, `lu_date`, `deleted`) VALUES
(1, 1, 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'No', 'Yes', 'Yes', 'Yes', 'Yes', 'No', 'No', 'No', 'No', '12:00:00', '13:00:00', 1, 'accumulated', '15.00', 'Every Payroll', 'Every Payroll', 'Yes', 'No', 'admin', '2018-08-28 16:51:36', 'admin', '2018-09-21 10:15:27', 0),
(2, 2, 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', '12:00:00', '13:00:00', 1, 'accumulated', '0.00', 'Every Payroll', 'Every Payroll', 'Yes', 'No', 'admin', '2018-08-28 17:09:12', 'admin', '2018-08-28 17:12:09', 1),
(3, 3, 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'No', 'Yes', 'Yes', 'Yes', 'Yes', 'No', 'No', 'No', 'No', '12:00:00', '13:00:00', 1, 'accumulated', '15.00', 'Every Payroll', 'Every Payroll', 'Yes', 'Yes', 'admin', '2018-08-28 17:09:17', 'admin', '2018-08-28 17:10:47', 0),
(4, 4, 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', '12:00:00', '13:00:00', 1, 'accumulated', '0.00', 'Every Payroll', 'Every Payroll', 'Yes', 'No', 'admin', '2018-09-03 16:18:28', 'admin', '2018-09-03 16:18:28', 0),
(5, 5, 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'No', 'No', 'No', 'No', '12:00:00', '13:00:00', 1, 'accumulated', '15.00', 'Every Payroll', 'Every Payroll', 'Yes', 'No', 'admin', '2018-09-21 11:30:58', 'admin', '2018-09-25 11:46:32', 0),
(6, 6, 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'No', 'Yes', 'Yes', 'Yes', 'Yes', 'No', 'No', 'No', 'No', '12:00:00', '13:00:00', 1, 'accumulated', '0.00', 'Every Payroll', 'Every Payroll', 'Yes', 'No', 'admin', '2018-11-19 11:13:03', 'admin', '2018-11-19 11:13:03', 0),
(7, 7, 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'No', 'Yes', 'Yes', 'Yes', 'Yes', 'No', 'No', 'No', 'No', '12:00:00', '13:00:00', 1, 'accumulated', '0.00', 'Every Payroll', 'Every Payroll', 'Yes', 'No', 'admin', '2019-01-17 14:37:16', 'admin', '2019-01-17 14:37:16', 0);

-- --------------------------------------------------------

--
-- Table structure for table `personal_information`
--

CREATE TABLE `personal_information` (
  `emp_no` bigint(20) NOT NULL,
  `company_id` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lname` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fname` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mname` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL,
  `citizenship` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `birtdate` date DEFAULT NULL,
  `birthplace` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_no` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `csi_email` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_email` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city_add` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city_add2` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city_city` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city_prov` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city_zip` varchar(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prov_add` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prov_add2` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prov_city` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prov_prov` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prov_zip` varchar(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lu_by` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lu_date` datetime NOT NULL,
  `basic_salary` decimal(11,2) NOT NULL,
  `allowance` decimal(11,2) NOT NULL,
  `taxable_allowance` int(2) NOT NULL DEFAULT '0',
  `gross_income` decimal(11,2) NOT NULL,
  `active` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `resigned_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_information`
--

INSERT INTO `personal_information` (`emp_no`, `company_id`, `lname`, `fname`, `mname`, `gender`, `citizenship`, `status`, `birtdate`, `birthplace`, `contact_no`, `csi_email`, `personal_email`, `city_add`, `city_add2`, `city_city`, `city_prov`, `city_zip`, `prov_add`, `prov_add2`, `prov_city`, `prov_prov`, `prov_zip`, `created_by`, `created_date`, `lu_by`, `lu_date`, `basic_salary`, `allowance`, `taxable_allowance`, `gross_income`, `active`, `resigned_date`) VALUES
(1, '2018-049', 'Reyes', 'Christine', 'Abad ', 'Female', 'Filipino', 'Single', '1997-10-27', 'Rizal Medical Center, Pasig City', '09068726332', 'caanis@circuit-solutions.net', 'rhieann.anis@gmail.com', '0031 Cable way, Macamot, Binangonan', '', 'Rizal', 'Rizal', '1940', '0031 Cable way, Macamot, Binangonan', '', 'Rizal', 'Rizal', '1940', 'admin', '2018-08-13 08:40:40', 'admin', '2018-08-13 08:40:40', '14000.00', '0.00', 0, '14000.00', 'yes', NULL),
(2, '2018-042', 'Palanca', 'Rica', 'Peralejo', 'Female', 'Filipino', 'Single', '1993-11-11', 'Mejia St., Malasiqui, Pangasinan', '09467355119', 'raramos@circuit-solutions.net', 'ricapearlramos11@yahoo.com.ph', 'Masagana St.', '', 'Caloocan City', 'Pangasinan', '1400', 'Bonifacio St., Malasiqui', '', 'Pangasinan', 'Pangasinan', '2421', 'admin', '2018-08-13 09:09:44', 'admin', '2018-08-13 09:09:44', '15000.00', '0.00', 0, '15000.00', 'yes', NULL),
(3, '2018-026', 'Soberano', 'Hope Elizabeth', 'Hanley', 'Female', 'Filipino', 'Single', '1992-01-25', 'Campidhan, San Julian, Eastern Samar', '9055997522', 'lcvillareal@circuit-solutions.net', 'lvillareal19@yahoo.com', 'Isabel Building, Brgy. Pleasant Hills', '', 'Mandaluyong City', 'Mandaluyong City', '1550', 'Campidhan, San Julian', '', 'Eastern Samar', 'Samar', '6814', 'admin', '2018-08-13 09:25:34', 'admin', '2018-08-13 09:25:34', '13354.67', '0.00', 0, '13354.67', 'yes', NULL),
(4, '2018-048', 'Chiu', 'Kimberly Sue', 'Yap', 'Female', 'Filipino', 'Single', '1997-08-09', 'Our Lady of Lourdes Hospital, Sta. Mesa, Manila', '09265581484', 'ksaguilar@circuit-solutions.net', 'kimberlynmaeaguilar@gmail.com', '326 Villamayor Pag-asa, Binangonan', '', 'Rizal', 'Rizal', '1940', '326 Villamayor Pag-asa, Binangonan', '', 'Rizal', 'Rizal', '1940', 'admin', '2018-08-13 10:38:25', 'admin', '2019-04-15 10:14:25', '13354.67', '0.00', 0, '13354.67', 'yes', NULL),
(5, '2018-028', 'Sullivan', 'Krista', 'CariÃ±o', 'Female', 'Filipino', 'Married', '1989-07-01', 'Fort Bonifacio, Makati City', '09153215755', 'bdvaldez@circuit-solutions.net', 'belledalupe@gmail.com', '196 51st St., Soldiers Village, Sta. Lucia', '', 'Pasig City', 'Pasig', '1608', '196 51st St., Soldiers Village, Sta. Lucia', '', 'Pasig City', 'Pasig', '1608', 'admin', '2018-08-13 13:29:02', 'admin', '2018-08-13 13:29:02', '18000.00', '0.00', 0, '18000.00', 'yes', NULL),
(6, '2018-035', 'Alandy', 'Adrian ', 'Louis', 'Male', 'Filipino', 'Single', '1993-06-16', 'Manila', '09065278354', 'lcsarmiento@circuit-solutions.net', 'sarmiento_lanz@yahoo.com', '1871 B Felix Huertas St. Sta. Cruz', '', 'Manila', 'Cainta, Rizal', '1014', '1871 B Felix Huertas St. Sta. Cruz', '', 'Manila', 'Cainta, Rizal', '1014', 'admin', '2018-08-13 13:53:04', 'admin', '2019-01-15 13:34:29', '14000.00', '0.00', 0, '14000.00', 'yes', NULL),
(7, '2018-036', 'Pangilinan', 'Michael ', 'Mediario', 'Male', 'Filipino', 'Single', '1997-08-27', 'Rosario, Pasig City', '09357476440', 'mmloren@circuit-solutions.net', 'michaeljohnloren027@gmail.com', 'Block 24 Lot 4B, Phase 2B Elisa Homes, Molino IV', '', 'Bacoor City, Cavite', 'Cavite', '4102', 'Block 24 Lot 4B, Phase 2B Elisa Homes, Molino IV', '', 'Bacoor City, Cavite', 'Cavite', '4102', 'admin', '2018-08-13 14:10:44', 'admin', '2018-08-13 14:10:44', '13354.67', '0.00', 0, '13354.67', 'yes', NULL),
(8, '2018-021', 'Herras', 'Mark Angelo', 'Santos ', 'Male', 'Filipino', 'Single', '1989-03-19', 'Dr. Jose Fabella Memorial Hospital', '09276022746', 'mmesperida@circuit-solutions.net', 'markesperida096@gmail.com', '#121 Dr. Pilapil St., Sagad', '', 'Pasig City', 'Pasig', '1600', '#121 Dr. Pilapil St., Sagad', '', 'Pasig City', 'Pasig', '1600', 'admin', '2018-08-13 14:36:11', 'admin', '2019-04-15 10:16:28', '13354.67', '0.00', 0, '13354.67', 'yes', NULL),
(9, '2018-003', 'Agoncillo', 'Judy Ann', 'Santos', 'Female', 'Filipino', 'Single', '1986-04-08', 'Sta. Rita, Tala, Caloocan City', '09076260395', 'mrbenedicto@circuit-solutions.net', 'corzbenedicto@gmail.com', '267 Bo. Sta. Rita, Tala', '', 'Caloocan City', 'Caloocan', '1427', '267 Bo. Sta. Rita, Tala', '', 'Caloocan City', 'Caloocan', '1427', 'admin', '2018-08-13 14:58:23', 'admin', '2019-04-15 10:11:03', '13354.67', '0.00', 0, '13354.67', 'yes', NULL),
(10, '2018-031', 'Malik', 'Zain', 'Javadd', 'Male', 'Filipino', 'Single', '1997-04-12', 'Quezon City', '09263388676', 'govillorente@circuit-solutions.net', 'genesis_villorente12@yahoo.com', '#4 ROTC Huntes Galilan, Brgy. Tatalon', '', 'Quezon City', 'Bicol', '1113', 'Guinobatan, Albay', '', 'Bicol', 'Bicol', '4503', 'admin', '2018-08-13 15:40:37', 'admin', '2018-08-13 15:40:37', '13354.67', '0.00', 0, '13354.67', 'yes', NULL),
(11, '2018-004', 'Abueva', 'CalviÃ±', 'Cruz', 'Male', 'Filipino', 'Single', '1998-11-09', 'Mandaluyong City', '09066601398', 'rcargales@circuit-solutions.net', 'romeaubrey9@gmail.com', '536 Brgy. San Jose, Sitio IV', '', 'Mandaluyong City', 'Mandaluyong City', '1550', '536 Brgy. San Jose, Sitio IV', '', 'Mandaluyong City', 'Mandaluyong City', '1550', 'admin', '2018-08-13 16:01:59', 'admin', '2019-04-15 10:09:39', '13354.67', '0.00', 0, '13354.67', 'yes', NULL),
(12, '2018-038', 'Romeo', 'Terrence', 'Vitanzos', 'Male', 'Filipino', 'Single', '1992-07-05', 'Gueset, Bugallon, Pangasinan', '09053758145', 'rpcaburnay@circuit-solutions.net', 'caburnayroland05@gmail.com', '0991 MRR Track, Barangka Itaas', '', 'Mandaluyong City', 'Mandaluyong City', '1550', '0991 MRR Track, Barangka Itaas', '', 'Mandaluyong City', 'Mandaluyong City', '1550', 'admin', '2018-08-13 16:20:43', 'admin', '2018-08-13 16:20:43', '20000.00', '0.00', 0, '20000.00', 'yes', NULL),
(13, '2018-020', 'Santos', 'Arwind', 'Abad ', 'Male', 'Filipino', 'Married', '1983-04-03', 'Manila', '09125766307', 'mapetras@circuit-solutions.net', 'mpetras18@gmail.com', '48B Magsaysay St., Bo. Magsaysay, Tondo', '', 'Manila', 'Manila', '1013', '48B Magsaysay St., Bo. Magsaysay, Tondo', '', 'Manila', 'Manila', '1013', 'admin', '2018-08-13 16:57:54', 'admin', '2018-08-13 16:57:54', '13354.67', '0.00', 0, '13354.67', 'yes', NULL),
(14, '2018-053', 'Pingris', 'Jean Marc', '', 'Male', 'Filipino', 'Single', '1987-05-28', 'Manila', '09208910022', 'maornedo@circuit-solutions.net', 'markarmandalonzoornedo@gmail.com', 'B3 L10 Flores St. Pleasant Hills San Jose Del Monte', '', 'Bulacan', 'Bulacan', '3023', 'B3 L10 Flores St. Pleasant Hills San Jose Del Monte', '', 'Bulacan', 'Bulacan', '3023', 'admin', '2018-08-13 17:38:17', 'admin', '2018-09-04 10:54:03', '16000.00', '0.00', 0, '16000.00', 'yes', NULL),
(15, '2018-054', 'Mercado', 'Jennylyn', 'Vergara', 'Female', 'Filipino', 'Single', '1998-05-23', 'Pateros, Metro Manila', '09554722767', 'jwdimaala@circuit-solutions.net', 'djenlouis@gmail.com', '24 Alley 6 P. rosales, Sta. Ana Pateros', '', 'Metro Manila', 'Metro Manila', '1621', '24 Alley 6 P. rosales, Sta. Ana Pateros', '', 'Metro Manila', 'Metro Manila', '1621', 'admin', '2018-08-13 18:34:18', 'admin', '2018-08-13 18:34:18', '13354.67', '0.00', 0, '13354.67', 'yes', NULL),
(16, '2018-047', 'Magdayao', 'Shaina', 'Garcia ', 'Female', 'Filipino', 'Single', '1994-02-15', 'Ilas Norte, Dao, Capiz', '09183294239', 'eddayao@circuit-solutions.net', 'ermalyn15dayao@gmail.com', '1007 Gastambide St., Sampaloc', '', 'Manila', 'Manila', '1008', '472 M.H. Del Pilar St., Brgy. San Isidro', '', 'Antipolo City', 'Antipolo', '1870', 'admin', '2018-08-14 14:56:12', 'admin', '2019-04-15 10:17:24', '14000.00', '0.00', 0, '14000.00', 'yes', NULL),
(17, '2018-046', 'Miguel', 'Juan', 'San', 'Male', 'Filipino', 'Single', '1992-08-06', 'centro, PeÃ±ablanca, Cagayan', '09473324512', 'mdcabadido@circuit-solutions.net', 'maccabadido@yahoo.com', 'Block 6, Lot 5, Greentown Extension, Mambog 3, Bacoor ', '', 'Cavite', 'Cavite', '4102', 'Block 6, Lot 5, Greentown Extension, Mambog 3, Bacoor ', '', 'Cavite', 'Cavite', '4102', 'admin', '2018-08-14 15:13:26', 'admin', '2018-08-14 15:13:26', '13354.67', '1500.00', 0, '14854.67', 'yes', NULL),
(18, '2018-045', 'Gil', 'Pedro', 'Jan ', 'Male', 'Filipino', 'Single', '1991-07-09', 'Mauban, Quezon', '09456959962', 'apencallado@circuit-solutions.net', 'aldwin_09_dwain@yahoo.com', '1659 Lancer St., Culdesac, Brgy. Sun Valley', '', 'ParaÃ±aque City', 'Mauban, Quezon', '1700', '1659 Lancer St., Culdesac, Brgy. Sun Valley', '', 'ParaÃ±aque City', 'Mauban, Quezon', '1700', 'admin', '2018-08-14 15:28:56', 'admin', '2019-04-15 10:15:02', '13354.67', '0.00', 0, '13354.67', 'yes', NULL),
(19, '2018-044', 'Angelo', 'Michael', '', 'Male', 'Filipino', 'Single', '1987-01-07', 'San Jose st. Goa Camarines, Sur', '09153938860', 'fmnovelo@circuit-solutions.net', 'fj90820@gmail.com', '2364 Unit 6A Alabastro St., San Andress, Bukid', '2233 Rd.3, Fabie State, Sta Ana', 'Manila', 'Manila', '1017', '285 San Jose St., 4th Goa', '', 'Camarines Sur', 'Camarines Sur', '4422', 'admin', '2018-08-14 15:48:39', 'admin', '2019-04-15 10:12:01', '13354.67', '0.00', 0, '13354.67', 'yes', NULL),
(20, '2018-051', 'Hermosa', 'Christine', '', 'Female', 'Filipino', 'Single', '1994-03-01', 'Morong, Rizal', '09055109942', 'csmorillo@circuit-solutions.net', 'morillo.christine016@gmail.com', '175 Christian Dior St., Bloomingdale Homes, Brgy. San Pedro, Angono', '', 'Rizal', 'Rizal', '1930', '175 Christian Dior St., Bloomingdale Homes, Brgy. San Pedro, Angono', '', 'Rizal', 'Rizal', '1930', 'admin', '2018-08-14 16:00:17', 'admin', '2019-04-15 10:15:56', '15000.00', '0.00', 0, '15000.00', 'yes', NULL),
(21, '2010-1234', 'Sample', 'Beverly', 'Sample', 'Female', 'Filipino', 'Single', '2000-01-01', 'Sample', '09123456789', 'sample@circuit.com', 'sample@yahoo.com', 'Sample', 'Sample', 'Sample', 'Sample', '4114', 'Sample', 'Sample', 'Sample', 'Sample', '4114', 'admin', '2018-06-29 09:36:21', 'admin', '2018-06-29 09:36:21', '12345.00', '12345.00', 0, '24690.00', 'yes', NULL),
(22, '2018-1940', 'Test', 'Test', 'Test', 'Female', 'Filipino', 'Single', '2010-10-27', 'Pasig', '09999999990', 'test@circuit.com', 'test@gmail.com', '0031', '0031', 'Pasig', 'Rizal', '1940', '0031', '0031', 'Pasig', 'Rizal', '1940', 'admin', '2018-08-22 11:15:41', 'admin', '2018-08-22 11:15:41', '1000.00', '100.00', 0, '1100.00', 'yes', NULL),
(23, '2018-616', 'Beckham', 'David', 'Cruz', 'Male', 'Filipino', 'Single', '1989-11-25', 'Pasig City', '09972172527', 'mcbelarmino@circuit-solutions.net', 'belarminomarlon@yahoo.com', 'Pasig City', 'San Miguel', 'Pasig City', 'Metro Manila~Pasig', '', 'Pasig City', 'San Miguel', 'Pasig City', 'Metro Manila~Pasig', '', 'admin', '2018-09-07 16:06:37', 'admin', '2019-04-15 10:13:25', '10.00', '10.00', 0, '20.00', 'yes', NULL),
(24, '2018-7675', 'Bequillo', 'Kath', 'Alcoseba', 'Female', 'Filipino', 'Single', '1989-11-25', 'Macabebe Pampanga', '09972172527', 'mcbelarmino@circuit-solutions.net', 'belarminomarlon@yahoo.com', 'Pasig City', 'San Miguel', 'Pasig City', 'Metro Manila~Pasig', '1800', 'Pasig City', 'San Miguel', 'Pasig City', 'Metro Manila~Pasig', '1800', 'admin', '2018-09-07 17:42:10', 'admin', '2019-04-15 10:14:01', '10.00', '20.00', 0, '30.00', 'yes', NULL),
(25, '2014-001', 'DELA CRUZ', 'JUAN', 'BONIFACIO', 'Male', '', 'Single', '1990-11-01', '', '9236455123', 'jbdelacruz@circuit-solutions.net', '', '#01 SAMPAGUITA ST., SUNSHINE VILLAGE, BRGY. SAN ANTONIO, PASIG CITY', '', '', '', '', '#01 SAMPAGUITA ST., SUNSHINE VILLAGE, BRGY. SAN ANTONIO, PASIG CITY', '', '', '', '', 'admin', '2018-09-14 13:41:11', '', '2018-09-14 13:41:11', '8000.00', '3000.00', 0, '11000.00', 'yes', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `profile_picture`
--

CREATE TABLE `profile_picture` (
  `id` bigint(10) NOT NULL,
  `company_id` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `profile_picture`
--

INSERT INTO `profile_picture` (`id`, `company_id`, `file`, `type`, `size`) VALUES
(3, '2018-5555', '53620-jellyfish.jpg', 'image/jpeg', 758),
(2, '2018-035', '90053-penguins.jpg', 'image/jpeg', 760);

-- --------------------------------------------------------

--
-- Table structure for table `rate_category`
--

CREATE TABLE `rate_category` (
  `id` bigint(20) NOT NULL,
  `category_name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rate` decimal(11,2) NOT NULL DEFAULT '0.00',
  `created_by` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_date` datetime NOT NULL,
  `lu_by` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lu_date` datetime NOT NULL,
  `deleted` int(2) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rate_category`
--

INSERT INTO `rate_category` (`id`, `category_name`, `description`, `rate`, `created_by`, `created_date`, `lu_by`, `lu_date`, `deleted`) VALUES
(1, 'Manila', 'Manila Rate', '512.00', 'admin', '0000-00-00 00:00:00', 'admin', '2019-01-21 14:04:42', 0),
(5, 'Province Rate', 'Province Rate', '415.00', 'admin', '0000-00-00 00:00:00', 'admin', '0000-00-00 00:00:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `sanction_iplementation`
--

CREATE TABLE `sanction_iplementation` (
  `ind` bigint(20) NOT NULL,
  `memo_ind` bigint(20) NOT NULL,
  `company_id` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `memo_number` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `plan_date` date DEFAULT NULL,
  `actual_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `schedule_template`
--

CREATE TABLE `schedule_template` (
  `ind` bigint(20) NOT NULL,
  `template` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Regular Shift',
  `reg_in` time DEFAULT '08:00:00',
  `reg_out` time DEFAULT '17:00:00',
  `mon_in` time DEFAULT NULL,
  `mon_out` time DEFAULT NULL,
  `mon` int(2) NOT NULL DEFAULT '1',
  `tue_in` time DEFAULT NULL,
  `tue_out` time DEFAULT NULL,
  `tue` int(2) NOT NULL DEFAULT '1',
  `wed_in` time DEFAULT NULL,
  `wed_out` time DEFAULT NULL,
  `wed` int(11) NOT NULL DEFAULT '1',
  `thu_in` time DEFAULT NULL,
  `thu_out` time DEFAULT NULL,
  `thu` int(2) NOT NULL DEFAULT '1',
  `fri_in` time DEFAULT NULL,
  `fri_out` time DEFAULT NULL,
  `fri` int(2) NOT NULL DEFAULT '1',
  `sat_in` time DEFAULT NULL,
  `sat_out` time DEFAULT NULL,
  `sat` int(2) NOT NULL DEFAULT '0',
  `sun_in` time DEFAULT NULL,
  `sun_out` time DEFAULT NULL,
  `sun` int(2) NOT NULL DEFAULT '0',
  `flexihrs` decimal(8,2) NOT NULL DEFAULT '8.00',
  `lunch_out` time NOT NULL DEFAULT '12:00:00',
  `lunch_in` time NOT NULL DEFAULT '13:00:00',
  `lunch_hrs` decimal(8,2) NOT NULL DEFAULT '1.00',
  `schedule_desc` varchar(5000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted` int(2) NOT NULL DEFAULT '0',
  `created_by` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lu_by` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lu_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `schedule_template`
--

INSERT INTO `schedule_template` (`ind`, `template`, `type`, `reg_in`, `reg_out`, `mon_in`, `mon_out`, `mon`, `tue_in`, `tue_out`, `tue`, `wed_in`, `wed_out`, `wed`, `thu_in`, `thu_out`, `thu`, `fri_in`, `fri_out`, `fri`, `sat_in`, `sat_out`, `sat`, `sun_in`, `sun_out`, `sun`, `flexihrs`, `lunch_out`, `lunch_in`, `lunch_hrs`, `schedule_desc`, `deleted`, `created_by`, `created_date`, `lu_by`, `lu_date`) VALUES
(1, 'Template trial 3', 'Irregular Shift', NULL, NULL, '08:00:00', '17:00:00', 1, '09:00:00', '18:00:00', 1, '07:00:00', '16:00:00', 1, '10:00:00', '19:00:00', 1, '11:00:00', '20:00:00', 1, '00:00:00', '00:00:00', 0, '00:00:00', '00:00:00', 0, '9.00', '12:00:00', '13:00:00', '1.00', 'my\'s', 0, 'admin', '2018-08-30 10:06:41', 'admin', '2018-09-07 14:44:04'),
(2, 'Trial Template 2', 'Regular Shift', '10:00:00', '16:00:00', NULL, NULL, 0, NULL, NULL, 1, NULL, NULL, 1, NULL, NULL, 1, NULL, NULL, 1, NULL, NULL, 1, NULL, NULL, 0, '8.00', '12:00:00', '13:00:00', '1.00', 'my\'s', 0, 'admin', '2018-08-30 10:29:08', 'admin', '2018-09-07 14:47:23'),
(3, 'Trial ', 'Regular Shift', '09:30:00', '19:30:00', NULL, NULL, 1, NULL, NULL, 1, NULL, NULL, 1, NULL, NULL, 1, NULL, NULL, 0, NULL, NULL, 1, NULL, NULL, 0, '8.00', '12:00:00', '14:00:00', '2.00', 'my\'s', 0, 'admin', '2018-08-30 18:06:01', 'admin', '2019-01-17 14:35:59'),
(4, 'Flexi Template', 'Flexi Shift', NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, NULL, 0, NULL, NULL, 1, NULL, NULL, 1, NULL, NULL, 1, NULL, NULL, 1, '8.00', '12:00:00', '13:00:00', '1.00', 'my\'s', 0, 'admin', '2018-09-05 14:27:54', 'admin', '2018-09-07 14:47:37'),
(5, 'Free Time Template', 'Free Shift', NULL, NULL, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, 0, '8.00', '12:00:00', '13:00:00', '1.00', 'my\'s', 0, 'admin', '2018-09-05 14:51:40', 'admin', '2018-09-07 14:47:43');

-- --------------------------------------------------------

--
-- Table structure for table `siblings_information`
--

CREATE TABLE `siblings_information` (
  `ind` bigint(20) NOT NULL,
  `company_id` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `birthdate` date DEFAULT NULL,
  `occupation` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `suspension`
--

CREATE TABLE `suspension` (
  `ind` bigint(20) NOT NULL,
  `company_id` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `memo_number` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `schedule_date` date DEFAULT NULL,
  `actual_date` date DEFAULT NULL,
  `created_by` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_date` date NOT NULL,
  `lu_by` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lu_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `suspension`
--

INSERT INTO `suspension` (`ind`, `company_id`, `memo_number`, `schedule_date`, `actual_date`, `created_by`, `created_date`, `lu_by`, `lu_date`) VALUES
(1, '2018-004', 'RND-013', '2018-10-22', '2018-10-22', 'admin', '2018-10-16', 'admin', '2018-10-16'),
(2, '2018-004', 'RND-013', '2018-10-25', '2018-10-25', 'admin', '2018-10-16', 'admin', '2018-10-16');

-- --------------------------------------------------------

--
-- Table structure for table `tax_status`
--

CREATE TABLE `tax_status` (
  `ind` int(11) NOT NULL,
  `status_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_description` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lu_by` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` int(2) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tax_status`
--

INSERT INTO `tax_status` (`ind`, `status_code`, `status_description`, `created_by`, `created_at`, `lu_by`, `updated_at`, `deleted`) VALUES
(1, 'Z', 'zero exemtion', 'marlon', '2018-06-18 13:51:44', 'marlon', '2018-06-18 13:51:44', 0),
(2, 'S', 'single', 'marlon', '2018-06-18 13:52:34', 'marlon', '2018-06-18 13:52:34', 0),
(3, 'ME', 'married', 'marlon', '2018-06-18 13:52:34', 'marlon', '2018-06-18 13:52:34', 0),
(4, 'S1', 'single with 1 qualified dependent child', 'marlon', '2018-06-18 13:53:53', 'marlon', '2018-06-18 13:53:53', 0),
(5, 'S2', 'single with 2 qualified dependent children', 'marlon', '2018-06-18 13:53:53', 'marlon', '2018-06-18 13:53:53', 0),
(6, 'S3', 'single with 3 qualified dependent children', 'marlon', '2018-06-18 13:54:33', 'marlon', '2018-06-18 13:54:33', 0),
(7, 'S4', 'single with 4 qualified dependent children', 'marlon', '2018-06-18 13:54:33', 'marlon', '2018-06-18 13:54:33', 0),
(8, 'ME1', 'married with 1 qualified dependent child', 'marlon', '2018-06-18 13:55:37', 'marlon', '2018-06-18 13:55:37', 0),
(9, 'ME2', 'married with 2 qualified dependent children', 'marlon', '2018-06-18 13:55:37', 'marlon', '2018-06-18 13:55:37', 0),
(10, 'ME3', 'married with 3 qualified dependent children', 'marlon', '2018-06-18 13:56:18', 'marlon', '2018-06-18 13:56:18', 0),
(11, 'ME4', 'married with 4 qualified dependent children', 'marlon', '2018-06-18 13:56:18', 'marlon', '2018-06-18 13:56:18', 0),
(12, 'TR2', 'Trial 2', 'Marlon Belarmino', '2018-10-03 02:46:12', 'Marlon Belarmino', '2018-10-03 02:50:16', 0);

-- --------------------------------------------------------

--
-- Table structure for table `team`
--

CREATE TABLE `team` (
  `ind` bigint(20) NOT NULL,
  `department_ind` bigint(20) NOT NULL,
  `team_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_date` datetime NOT NULL,
  `lu_by` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lu_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `team`
--

INSERT INTO `team` (`ind`, `department_ind`, `team_name`, `active`, `created_by`, `created_date`, `lu_by`, `lu_date`) VALUES
(7, 8, 'Employee Engagement', 'yes', 'marlon', '2017-12-07 16:38:00', 'marlon', '2017-12-07 16:57:09'),
(9, 11, 'Product Specialist', 'yes', 'marlon', '2017-12-07 16:54:50', 'marlon', '2017-12-07 16:54:50'),
(10, 11, 'Cost Estimate', 'yes', 'marlon', '2017-12-07 16:54:59', 'marlon', '2017-12-12 10:01:29'),
(11, 11, 'Workshop', 'yes', 'marlon', '2017-12-07 16:55:07', 'marlon', '2017-12-07 16:55:07'),
(12, 11, 'Project Management and Compliance', 'yes', 'marlon', '2017-12-07 16:55:46', 'marlon', '2017-12-07 16:55:46'),
(13, 11, 'Tech. Field Engr.', 'yes', 'marlon', '2017-12-07 16:56:08', 'marlon', '2017-12-07 16:56:08'),
(14, 11, 'Service Desk', 'yes', 'marlon', '2017-12-07 16:56:24', 'marlon', '2017-12-07 16:56:24'),
(15, 11, 'Phone Support', 'yes', 'marlon', '2017-12-07 16:56:39', 'marlon', '2017-12-07 16:56:39'),
(16, 8, 'Talent Acquisition and Management', 'yes', 'marlon', '2017-12-07 16:57:53', 'marlon', '2017-12-07 16:57:53'),
(17, 8, 'Learning and Development', 'yes', 'marlon', '2017-12-07 16:58:10', 'marlon', '2017-12-07 16:58:10'),
(18, 8, 'Payroll', 'yes', 'marlon', '2017-12-07 16:58:17', 'marlon', '2017-12-07 16:58:17'),
(19, 8, 'Administrative Support', 'yes', 'marlon', '2017-12-07 16:58:32', 'marlon', '2017-12-07 16:58:32'),
(21, 13, 'Hardware', 'yes', 'marlon', '2017-12-07 16:58:51', 'marlon', '2017-12-07 16:58:51'),
(22, 10, 'Purchasing', 'yes', 'marlon', '2017-12-07 16:59:10', 'marlon', '2017-12-07 16:59:10'),
(24, 12, 'Relationship', 'yes', 'marlon', '2017-12-07 16:59:27', 'admin ', '2017-12-12 16:54:10'),
(25, 12, 'Acquisition', 'yes', 'marlon', '2017-12-07 16:59:37', 'admin ', '2017-12-12 16:54:05'),
(32, 13, 'Software', 'yes', 'admin', '2017-12-19 10:40:24', 'admin', '2017-12-19 10:40:49'),
(34, 11, 'Workshop', 'yes', 'admin', '2017-12-19 10:48:18', 'admin', '2017-12-19 10:48:18'),
(36, 10, 'Warehouse', 'yes', 'admin', '2017-12-19 10:48:48', 'admin', '2017-12-19 10:48:48'),
(37, 12, 'Relationship', 'yes', 'admin', '2017-12-19 10:49:08', 'admin', '2017-12-19 10:49:08'),
(42, 11, 'Service Desk ', 'yes', 'admin', '2017-12-29 10:08:49', 'admin', '2017-12-29 10:08:49'),
(43, 12, 'Acquisition', 'no', 'admin', '2018-06-13 18:44:00', 'admin', '2018-06-13 18:44:09');

-- --------------------------------------------------------

--
-- Table structure for table `user_account`
--

CREATE TABLE `user_account` (
  `ind` bigint(20) NOT NULL,
  `company_id` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_type_id` bigint(20) NOT NULL,
  `username` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `department` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lu_by` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lu_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_account`
--

INSERT INTO `user_account` (`ind`, `company_id`, `user_type_id`, `username`, `password`, `department`, `email`, `active`, `created_by`, `created_date`, `lu_by`, `lu_date`) VALUES
(10, '2017-439', 1, 'admin', '81dc9bdb52d04dc20036dbd8313ed055', 'People Management', 'mcbelarmino@circuit-solutions.net', 'yes', '', '2018-10-05 14:24:13', '', '2018-10-05 14:24:13'),
(28, '2010-1234', 1, 'sample', '202cb962ac59075b964b07152d234b70', 'People Management', 'mcbelarmino@circuit-solutions.net', 'yes', '', '2018-10-05 14:24:13', '', '2018-10-05 14:24:13'),
(30, '2018-004', 1, 'Kingkongs', '9e60e2bae0a15b428631454425bfead3', '', '', 'no', '', '2018-10-05 14:24:13', 'admin', '2018-10-05 14:33:26'),
(31, '2018-003', 1, 'Juday', '5e6005bdbd165dc5ffca27ec429f392b', '', '', 'yes', '', '2018-10-05 14:24:13', 'admin', '2018-10-05 14:53:34'),
(32, '2018-035', 2, 'malandi', 'f42b060e2f6269ef65aac822c2366f67', '', '', 'yes', '', '2018-10-05 14:24:13', 'admin', '2018-10-17 11:35:25'),
(33, '2018-5454', 1, 'manchi', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'yes', '', '2018-10-05 14:24:13', '', '2018-10-05 14:24:13'),
(34, '2018-5555', 1, 'marlon', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'yes', 'admin', '2018-10-22 16:53:01', 'admin', '2018-10-22 16:53:01');

-- --------------------------------------------------------

--
-- Table structure for table `user_module`
--

CREATE TABLE `user_module` (
  `id` bigint(20) NOT NULL,
  `module_code` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `module_name` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `module_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Module',
  `created_by` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lu_by` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lu_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` int(2) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_module`
--

INSERT INTO `user_module` (`id`, `module_code`, `module_name`, `module_type`, `created_by`, `created_date`, `lu_by`, `lu_date`, `deleted`) VALUES
(1, 'employee', 'Employee Module', 'Module', 'admin', '2018-10-04 13:11:43', 'admin', '2018-10-04 13:11:43', 0),
(2, 'emp_upload_valid_info', 'Employee Upload/Validate Information', 'Module', 'admin', '2018-10-04 13:11:43', 'admin', '2018-10-04 13:11:43', 0),
(3, 'notice_explain', 'Notice to Explain', 'Module', 'admin', '2018-10-04 13:11:43', 'admin', '2018-10-04 13:11:43', 0),
(4, 'notice_decision', 'Notice of Decision', 'Module', 'admin', '2018-10-04 13:11:43', 'admin', '2018-10-04 13:11:43', 0),
(5, 'compensation', 'Compensation Details', 'Module', 'admin', '2018-10-04 13:11:43', 'admin', '2018-10-04 13:11:43', 0),
(6, 'schedule', 'Schedule Details', 'Module', 'admin', '2018-10-04 13:11:43', 'admin', '2018-10-04 13:11:43', 0),
(7, 'applicant', 'Applicant Details', 'Module', 'admin', '2018-10-04 13:11:43', 'admin', '2018-10-04 13:11:43', 0),
(8, 'assets', 'Assets Details', 'Module', 'admin', '2018-10-04 13:11:43', 'admin', '2018-10-04 13:11:43', 0),
(9, 'company', 'Company Details', 'Module', 'admin', '2018-10-04 13:11:43', 'admin', '2018-10-04 13:11:43', 0),
(10, 'department', 'Department Details', 'Module', 'admin', '2018-10-04 13:11:43', 'admin', '2018-10-04 13:11:43', 0),
(11, 'team', 'Team Details', 'Module', 'admin', '2018-10-04 13:11:43', 'admin', '2018-10-04 13:11:43', 0),
(12, 'payroll_group', 'Payroll Group Details', 'Module', 'admin', '2018-10-04 13:11:43', 'admin', '2018-10-04 13:11:43', 0),
(13, 'leaved', 'Leaved Settings', 'Module', 'admin', '2018-10-04 13:11:43', 'admin', '2018-10-04 13:11:43', 0),
(14, 'upload_employee', 'Upload Employee Details', 'Module', 'admin', '2018-10-04 13:11:43', 'admin', '2018-10-04 13:11:43', 0),
(15, 'create_user', 'Create User Module', 'Module', 'admin', '2018-10-04 13:11:43', 'admin', '2018-10-04 13:11:43', 0),
(16, 'user_type', 'Create/Manage User Type', 'Module', 'admin', '2018-10-04 13:11:43', 'admin', '2018-10-04 13:11:43', 0),
(17, 'employee_master', 'Employee Master Report', 'Report', 'admin', '2018-10-04 13:11:43', 'admin', '2018-10-04 13:11:43', 0),
(18, 'assets_report', 'Assets Report', 'Report', 'admin', '2018-10-04 13:11:43', 'admin', '2018-10-04 13:11:43', 0),
(19, 'rate_category', 'Rate Category Details', 'Module', 'admin', '2018-10-04 13:11:43', 'admin', '2018-10-04 13:11:43', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_module_access`
--

CREATE TABLE `user_module_access` (
  `id` bigint(20) NOT NULL,
  `user_type_id` int(20) NOT NULL,
  `employee` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'all',
  `emp_upload_valid_info` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'all',
  `notice_explain` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'none',
  `notice_decision` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'none',
  `compensation` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'none',
  `schedule` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'all',
  `applicant` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'all',
  `assets` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'none',
  `company` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'none',
  `department` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'all',
  `team` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'all',
  `payroll_group` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'none',
  `leaved` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'none',
  `upload_employee` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'all',
  `create_user` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'none',
  `user_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'none',
  `employee_master` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'none',
  `assets_report` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'none',
  `rate_category` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'none',
  `created_by` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lu_by` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lu_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` int(2) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_module_access`
--

INSERT INTO `user_module_access` (`id`, `user_type_id`, `employee`, `emp_upload_valid_info`, `notice_explain`, `notice_decision`, `compensation`, `schedule`, `applicant`, `assets`, `company`, `department`, `team`, `payroll_group`, `leaved`, `upload_employee`, `create_user`, `user_type`, `employee_master`, `assets_report`, `rate_category`, `created_by`, `created_date`, `lu_by`, `lu_date`, `deleted`) VALUES
(2, 2, 'all', 'all', 'none', 'none', 'all', 'all', 'all', 'all', 'all', 'all', 'all', 'none', 'none', 'all', 'all', 'all', 'none', 'none', 'none', 'admin', '2018-10-05 18:34:10', 'admin', '2018-10-05 18:34:10', 0),
(3, 1, 'all', 'all', 'all', 'all', 'all', 'all', 'all', 'all', 'all', 'all', 'all', 'all', 'all', 'all', 'all', 'all', 'all', 'all', 'all', 'admin', '2018-10-05 18:34:10', 'admin', '2018-10-05 18:34:10', 0),
(4, 3, 'all', 'all', 'none', 'none', 'none', 'all', 'all', 'none', 'none', 'all', 'all', 'none', 'none', 'all', 'none', 'none', 'none', 'none', 'none', 'admin', '2018-10-08 14:27:34', 'admin', '2018-10-08 14:27:34', 0),
(5, 4, 'all', 'all', 'none', 'none', 'none', 'all', 'all', 'none', 'none', 'all', 'all', 'none', 'none', 'all', 'none', 'none', 'none', 'none', 'none', 'admin', '2018-10-08 14:31:19', 'admin', '2018-10-08 14:31:34', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_type`
--

CREATE TABLE `user_type` (
  `id` bigint(20) NOT NULL,
  `type_name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_description` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lu_by` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lu_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_type`
--

INSERT INTO `user_type` (`id`, `type_name`, `type_description`, `created_by`, `created_date`, `lu_by`, `lu_date`, `deleted`) VALUES
(1, 'Admin', 'Can Access All on the Database', 'admin', '2018-10-04 13:50:52', 'admin', '2018-10-04 13:50:52', 0),
(2, 'People Management', 'HR Department', 'admin', '2018-10-05 18:34:10', 'admin', '2018-10-08 14:27:03', 0),
(3, 'Research and Development', 'R&D Users', 'admin', '2018-10-08 14:27:34', 'admin', '2018-10-08 14:27:34', 0),
(4, 'Trial', 'Trial to delete', 'admin', '2018-10-08 14:31:19', 'admin', '2018-10-08 14:31:34', 1);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_employee_schedule`
-- (See below for the actual view)
--
CREATE TABLE `view_employee_schedule` (
`id` bigint(20)
,`template_id` bigint(20)
,`name` varchar(62)
,`company_id` varchar(20)
,`template` varchar(200)
,`type` varchar(200)
,`reg_in` time
,`reg_out` time
,`mon_in` time
,`mon_out` time
,`mon` int(2)
,`tue_in` time
,`tue_out` time
,`tue` int(2)
,`wed_in` time
,`wed_out` time
,`wed` int(11)
,`thu_in` time
,`thu_out` time
,`thu` int(2)
,`fri_in` time
,`fri_out` time
,`fri` int(2)
,`sat_in` time
,`sat_out` time
,`sat` int(2)
,`sun_in` time
,`sun_out` time
,`sun` int(2)
,`flexihrs` decimal(8,2)
,`lunch_out` time
,`lunch_in` time
,`lunch_hrs` decimal(8,2)
,`lu_by` varchar(20)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_employee_schedule_request`
-- (See below for the actual view)
--
CREATE TABLE `view_employee_schedule_request` (
`id` bigint(20)
,`template_id` bigint(20)
,`name` varchar(62)
,`company_id` varchar(20)
,`template` varchar(200)
,`type` varchar(200)
,`reg_in` time
,`reg_out` time
,`mon_in` time
,`mon_out` time
,`mon` int(2)
,`tue_in` time
,`tue_out` time
,`tue` int(2)
,`wed_in` time
,`wed_out` time
,`wed` int(11)
,`thu_in` time
,`thu_out` time
,`thu` int(2)
,`fri_in` time
,`fri_out` time
,`fri` int(2)
,`sat_in` time
,`sat_out` time
,`sat` int(2)
,`sun_in` time
,`sun_out` time
,`sun` int(2)
,`flexihrs` decimal(8,2)
,`lunch_out` time
,`lunch_in` time
,`lunch_hrs` decimal(8,2)
,`start_date` date
,`end_date` date
,`status` varchar(15)
,`lu_by` varchar(20)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_payrollgroup_emp`
-- (See below for the actual view)
--
CREATE TABLE `view_payrollgroup_emp` (
`empname` varchar(93)
,`company_id` varchar(20)
,`company_ind` bigint(20)
,`company_name` varchar(100)
,`payroll_group_ind` bigint(20)
,`group_name` varchar(200)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_schedule_emp`
-- (See below for the actual view)
--
CREATE TABLE `view_schedule_emp` (
`emp_no` bigint(20)
,`empname` varchar(93)
,`company_id` varchar(20)
,`company_ind` bigint(20)
,`company_name` varchar(100)
,`template_id` bigint(20)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_user_list`
-- (See below for the actual view)
--
CREATE TABLE `view_user_list` (
`id` bigint(20)
,`empname` varchar(93)
,`user_type_id` bigint(20)
,`company_id` varchar(20)
,`type_name` varchar(200)
,`username` varchar(20)
,`password` varchar(1000)
);

-- --------------------------------------------------------

--
-- Structure for view `view_employee_schedule`
--
DROP TABLE IF EXISTS `view_employee_schedule`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_employee_schedule`  AS  select `b`.`id` AS `id`,`b`.`template_id` AS `template_id`,concat(`a`.`lname`,', ',`a`.`fname`) AS `name`,`a`.`company_id` AS `company_id`,`c`.`template` AS `template`,`c`.`type` AS `type`,`c`.`reg_in` AS `reg_in`,`c`.`reg_out` AS `reg_out`,`c`.`mon_in` AS `mon_in`,`c`.`mon_out` AS `mon_out`,`c`.`mon` AS `mon`,`c`.`tue_in` AS `tue_in`,`c`.`tue_out` AS `tue_out`,`c`.`tue` AS `tue`,`c`.`wed_in` AS `wed_in`,`c`.`wed_out` AS `wed_out`,`c`.`wed` AS `wed`,`c`.`thu_in` AS `thu_in`,`c`.`thu_out` AS `thu_out`,`c`.`thu` AS `thu`,`c`.`fri_in` AS `fri_in`,`c`.`fri_out` AS `fri_out`,`c`.`fri` AS `fri`,`c`.`sat_in` AS `sat_in`,`c`.`sat_out` AS `sat_out`,`c`.`sat` AS `sat`,`c`.`sun_in` AS `sun_in`,`c`.`sun_out` AS `sun_out`,`c`.`sun` AS `sun`,`c`.`flexihrs` AS `flexihrs`,`c`.`lunch_out` AS `lunch_out`,`c`.`lunch_in` AS `lunch_in`,`c`.`lunch_hrs` AS `lunch_hrs`,`b`.`lu_by` AS `lu_by` from ((`personal_information` `a` join `employee_schedule` `b` on((`a`.`company_id` = `b`.`company_id`))) join `schedule_template` `c` on((`b`.`template_id` = `c`.`ind`))) where ((`a`.`active` = 'Yes') and (`b`.`deleted` = 0)) ;

-- --------------------------------------------------------

--
-- Structure for view `view_employee_schedule_request`
--
DROP TABLE IF EXISTS `view_employee_schedule_request`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_employee_schedule_request`  AS  select `b`.`id` AS `id`,`b`.`template_id` AS `template_id`,concat(`a`.`lname`,', ',`a`.`fname`) AS `name`,`a`.`company_id` AS `company_id`,`c`.`template` AS `template`,`c`.`type` AS `type`,`c`.`reg_in` AS `reg_in`,`c`.`reg_out` AS `reg_out`,`c`.`mon_in` AS `mon_in`,`c`.`mon_out` AS `mon_out`,`c`.`mon` AS `mon`,`c`.`tue_in` AS `tue_in`,`c`.`tue_out` AS `tue_out`,`c`.`tue` AS `tue`,`c`.`wed_in` AS `wed_in`,`c`.`wed_out` AS `wed_out`,`c`.`wed` AS `wed`,`c`.`thu_in` AS `thu_in`,`c`.`thu_out` AS `thu_out`,`c`.`thu` AS `thu`,`c`.`fri_in` AS `fri_in`,`c`.`fri_out` AS `fri_out`,`c`.`fri` AS `fri`,`c`.`sat_in` AS `sat_in`,`c`.`sat_out` AS `sat_out`,`c`.`sat` AS `sat`,`c`.`sun_in` AS `sun_in`,`c`.`sun_out` AS `sun_out`,`c`.`sun` AS `sun`,`c`.`flexihrs` AS `flexihrs`,`c`.`lunch_out` AS `lunch_out`,`c`.`lunch_in` AS `lunch_in`,`c`.`lunch_hrs` AS `lunch_hrs`,`b`.`start_date` AS `start_date`,`b`.`end_date` AS `end_date`,`b`.`status` AS `status`,`b`.`lu_by` AS `lu_by` from ((`personal_information` `a` join `employee_schedule_request` `b` on((`a`.`company_id` = `b`.`company_id`))) join `schedule_template` `c` on((`b`.`template_id` = `c`.`ind`))) where ((`a`.`active` = 'Yes') and (`b`.`deleted` = 0)) ;

-- --------------------------------------------------------

--
-- Structure for view `view_payrollgroup_emp`
--
DROP TABLE IF EXISTS `view_payrollgroup_emp`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_payrollgroup_emp`  AS  select ucase(concat(`a`.`lname`,', ',`a`.`fname`,' ',`a`.`mname`)) AS `empname`,`a`.`company_id` AS `company_id`,`b`.`company_ind` AS `company_ind`,`c`.`company_name` AS `company_name`,`d`.`payroll_group_ind` AS `payroll_group_ind`,`e`.`group_name` AS `group_name` from ((((`personal_information` `a` left join `employee_information` `b` on((`a`.`company_id` = `b`.`company_id`))) join `company` `c` on((`b`.`company_ind` = `c`.`id`))) left join `employee_payroll_group` `d` on((`a`.`company_id` = `d`.`company_id`))) join `payroll_group` `e` on((`d`.`payroll_group_ind` = `e`.`ind`))) where ((`a`.`active` = 'yes') and (`d`.`deleted` = 0)) ;

-- --------------------------------------------------------

--
-- Structure for view `view_schedule_emp`
--
DROP TABLE IF EXISTS `view_schedule_emp`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_schedule_emp`  AS  select `a`.`emp_no` AS `emp_no`,ucase(concat(`a`.`lname`,', ',`a`.`fname`,' ',`a`.`mname`)) AS `empname`,`a`.`company_id` AS `company_id`,`b`.`company_ind` AS `company_ind`,`c`.`company_name` AS `company_name`,`d`.`template_id` AS `template_id` from (((`personal_information` `a` left join `employee_information` `b` on((`a`.`company_id` = `b`.`company_id`))) join `company` `c` on((`b`.`company_ind` = `c`.`id`))) left join `employee_schedule` `d` on((`a`.`company_id` = `d`.`company_id`))) where ((`a`.`active` = 'yes') and (`d`.`deleted` = 0)) ;

-- --------------------------------------------------------

--
-- Structure for view `view_user_list`
--
DROP TABLE IF EXISTS `view_user_list`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_user_list`  AS  select `a`.`ind` AS `id`,concat(`b`.`lname`,', ',`b`.`fname`,' ',`b`.`mname`) AS `empname`,`a`.`user_type_id` AS `user_type_id`,`a`.`company_id` AS `company_id`,`c`.`type_name` AS `type_name`,`a`.`username` AS `username`,`a`.`password` AS `password` from ((`user_account` `a` join `personal_information` `b` on((`a`.`company_id` = `b`.`company_id`))) join `user_type` `c` on((`a`.`user_type_id` = `c`.`id`))) where ((`b`.`active` = 'yes') and (`a`.`active` = 'yes')) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applicant_achievement`
--
ALTER TABLE `applicant_achievement`
  ADD PRIMARY KEY (`ind`);

--
-- Indexes for table `applicant_emergency`
--
ALTER TABLE `applicant_emergency`
  ADD PRIMARY KEY (`ind`);

--
-- Indexes for table `applicant_employement`
--
ALTER TABLE `applicant_employement`
  ADD PRIMARY KEY (`ind`);

--
-- Indexes for table `applicant_information`
--
ALTER TABLE `applicant_information`
  ADD PRIMARY KEY (`ind`);

--
-- Indexes for table `applicant_licence`
--
ALTER TABLE `applicant_licence`
  ADD PRIMARY KEY (`ind`);

--
-- Indexes for table `applicant_training`
--
ALTER TABLE `applicant_training`
  ADD PRIMARY KEY (`ind`);

--
-- Indexes for table `assets`
--
ALTER TABLE `assets`
  ADD PRIMARY KEY (`ind`),
  ADD UNIQUE KEY `asset_tagno` (`asset_tagno`);

--
-- Indexes for table `certificates_trainings`
--
ALTER TABLE `certificates_trainings`
  ADD PRIMARY KEY (`ind`);

--
-- Indexes for table `childrens_information`
--
ALTER TABLE `childrens_information`
  ADD PRIMARY KEY (`ind`);

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`ind`);

--
-- Indexes for table `education_background`
--
ALTER TABLE `education_background`
  ADD PRIMARY KEY (`company_id`);

--
-- Indexes for table `emergency_contact`
--
ALTER TABLE `emergency_contact`
  ADD PRIMARY KEY (`ind`);

--
-- Indexes for table `employee_achievement`
--
ALTER TABLE `employee_achievement`
  ADD PRIMARY KEY (`ind`);

--
-- Indexes for table `employee_compensation`
--
ALTER TABLE `employee_compensation`
  ADD PRIMARY KEY (`ind`);

--
-- Indexes for table `employee_information`
--
ALTER TABLE `employee_information`
  ADD PRIMARY KEY (`company_id`);

--
-- Indexes for table `employee_leave`
--
ALTER TABLE `employee_leave`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_payroll_group`
--
ALTER TABLE `employee_payroll_group`
  ADD PRIMARY KEY (`ind`);

--
-- Indexes for table `employee_schedule`
--
ALTER TABLE `employee_schedule`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_schedule_request`
--
ALTER TABLE `employee_schedule_request`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employment_history`
--
ALTER TABLE `employment_history`
  ADD PRIMARY KEY (`ind`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `family_background`
--
ALTER TABLE `family_background`
  ADD PRIMARY KEY (`company_id`);

--
-- Indexes for table `goverment_number`
--
ALTER TABLE `goverment_number`
  ADD PRIMARY KEY (`company_id`);

--
-- Indexes for table `goverment_upload`
--
ALTER TABLE `goverment_upload`
  ADD PRIMARY KEY (`ind`);

--
-- Indexes for table `group_schedule`
--
ALTER TABLE `group_schedule`
  ADD PRIMARY KEY (`ind`);

--
-- Indexes for table `leave_template`
--
ALTER TABLE `leave_template`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `licence_information`
--
ALTER TABLE `licence_information`
  ADD PRIMARY KEY (`ind`);

--
-- Indexes for table `medical_allergies`
--
ALTER TABLE `medical_allergies`
  ADD PRIMARY KEY (`ind`);

--
-- Indexes for table `medical_illness`
--
ALTER TABLE `medical_illness`
  ADD PRIMARY KEY (`ind`);

--
-- Indexes for table `memo_upload`
--
ALTER TABLE `memo_upload`
  ADD PRIMARY KEY (`ind`);

--
-- Indexes for table `notice_decision`
--
ALTER TABLE `notice_decision`
  ADD PRIMARY KEY (`ind`);

--
-- Indexes for table `notice_explain`
--
ALTER TABLE `notice_explain`
  ADD PRIMARY KEY (`ind`);

--
-- Indexes for table `payroll_group`
--
ALTER TABLE `payroll_group`
  ADD PRIMARY KEY (`ind`);

--
-- Indexes for table `payroll_settings`
--
ALTER TABLE `payroll_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_information`
--
ALTER TABLE `personal_information`
  ADD PRIMARY KEY (`emp_no`);

--
-- Indexes for table `profile_picture`
--
ALTER TABLE `profile_picture`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rate_category`
--
ALTER TABLE `rate_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sanction_iplementation`
--
ALTER TABLE `sanction_iplementation`
  ADD PRIMARY KEY (`ind`);

--
-- Indexes for table `schedule_template`
--
ALTER TABLE `schedule_template`
  ADD PRIMARY KEY (`ind`);

--
-- Indexes for table `siblings_information`
--
ALTER TABLE `siblings_information`
  ADD PRIMARY KEY (`ind`);

--
-- Indexes for table `suspension`
--
ALTER TABLE `suspension`
  ADD PRIMARY KEY (`ind`);

--
-- Indexes for table `tax_status`
--
ALTER TABLE `tax_status`
  ADD PRIMARY KEY (`ind`);

--
-- Indexes for table `team`
--
ALTER TABLE `team`
  ADD PRIMARY KEY (`ind`);

--
-- Indexes for table `user_account`
--
ALTER TABLE `user_account`
  ADD PRIMARY KEY (`ind`);

--
-- Indexes for table `user_module`
--
ALTER TABLE `user_module`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_module_access`
--
ALTER TABLE `user_module_access`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_type`
--
ALTER TABLE `user_type`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `applicant_achievement`
--
ALTER TABLE `applicant_achievement`
  MODIFY `ind` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `applicant_emergency`
--
ALTER TABLE `applicant_emergency`
  MODIFY `ind` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `applicant_employement`
--
ALTER TABLE `applicant_employement`
  MODIFY `ind` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `applicant_information`
--
ALTER TABLE `applicant_information`
  MODIFY `ind` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `applicant_licence`
--
ALTER TABLE `applicant_licence`
  MODIFY `ind` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `applicant_training`
--
ALTER TABLE `applicant_training`
  MODIFY `ind` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `assets`
--
ALTER TABLE `assets`
  MODIFY `ind` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `certificates_trainings`
--
ALTER TABLE `certificates_trainings`
  MODIFY `ind` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `childrens_information`
--
ALTER TABLE `childrens_information`
  MODIFY `ind` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `ind` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `emergency_contact`
--
ALTER TABLE `emergency_contact`
  MODIFY `ind` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `employee_achievement`
--
ALTER TABLE `employee_achievement`
  MODIFY `ind` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_compensation`
--
ALTER TABLE `employee_compensation`
  MODIFY `ind` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `employee_leave`
--
ALTER TABLE `employee_leave`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `employee_payroll_group`
--
ALTER TABLE `employee_payroll_group`
  MODIFY `ind` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `employee_schedule`
--
ALTER TABLE `employee_schedule`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `employee_schedule_request`
--
ALTER TABLE `employee_schedule_request`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `employment_history`
--
ALTER TABLE `employment_history`
  MODIFY `ind` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `goverment_upload`
--
ALTER TABLE `goverment_upload`
  MODIFY `ind` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `group_schedule`
--
ALTER TABLE `group_schedule`
  MODIFY `ind` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `leave_template`
--
ALTER TABLE `leave_template`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `licence_information`
--
ALTER TABLE `licence_information`
  MODIFY `ind` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `medical_allergies`
--
ALTER TABLE `medical_allergies`
  MODIFY `ind` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `medical_illness`
--
ALTER TABLE `medical_illness`
  MODIFY `ind` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `memo_upload`
--
ALTER TABLE `memo_upload`
  MODIFY `ind` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `notice_decision`
--
ALTER TABLE `notice_decision`
  MODIFY `ind` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `notice_explain`
--
ALTER TABLE `notice_explain`
  MODIFY `ind` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `payroll_group`
--
ALTER TABLE `payroll_group`
  MODIFY `ind` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `payroll_settings`
--
ALTER TABLE `payroll_settings`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `personal_information`
--
ALTER TABLE `personal_information`
  MODIFY `emp_no` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `profile_picture`
--
ALTER TABLE `profile_picture`
  MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `rate_category`
--
ALTER TABLE `rate_category`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `sanction_iplementation`
--
ALTER TABLE `sanction_iplementation`
  MODIFY `ind` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `schedule_template`
--
ALTER TABLE `schedule_template`
  MODIFY `ind` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `siblings_information`
--
ALTER TABLE `siblings_information`
  MODIFY `ind` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `suspension`
--
ALTER TABLE `suspension`
  MODIFY `ind` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tax_status`
--
ALTER TABLE `tax_status`
  MODIFY `ind` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `team`
--
ALTER TABLE `team`
  MODIFY `ind` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `user_account`
--
ALTER TABLE `user_account`
  MODIFY `ind` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `user_module`
--
ALTER TABLE `user_module`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `user_module_access`
--
ALTER TABLE `user_module_access`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user_type`
--
ALTER TABLE `user_type`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
