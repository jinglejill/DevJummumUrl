-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 26, 2018 at 05:11 AM
-- Server version: 5.6.33
-- PHP Version: 5.6.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dev_jummum_om`
--

-- --------------------------------------------------------

--
-- Table structure for table `branch`
--

CREATE TABLE `branch` (
  `BranchID` int(11) NOT NULL,
  `DbName` varchar(30) NOT NULL,
  `MainBranchID` int(11) NOT NULL,
  `BranchNo` varchar(50) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Street` varchar(200) NOT NULL,
  `District` varchar(50) NOT NULL,
  `Province` varchar(50) NOT NULL,
  `PostCode` varchar(20) NOT NULL,
  `SubDistrictID` int(11) NOT NULL,
  `DistrictID` int(11) NOT NULL,
  `ProvinceID` int(11) NOT NULL,
  `ZipCodeID` int(11) NOT NULL,
  `Country` varchar(50) NOT NULL,
  `Map` varchar(100) NOT NULL,
  `PhoneNo` varchar(100) NOT NULL,
  `TableNum` int(11) NOT NULL,
  `CustomerNumMax` int(11) NOT NULL,
  `EmployeePermanentNum` int(11) NOT NULL,
  `Status` int(11) NOT NULL,
  `TakeAwayFee` int(11) NOT NULL,
  `ServiceChargePercent` float NOT NULL,
  `PercentVat` float NOT NULL,
  `PriceIncludeVat` tinyint(4) NOT NULL,
  `CustomerApp` tinyint(4) NOT NULL,
  `ImageUrl` varchar(200) NOT NULL,
  `StartDate` datetime NOT NULL,
  `Remark` varchar(200) NOT NULL,
  `LedStatus` tinyint(4) NOT NULL,
  `ModifiedUser` varchar(50) NOT NULL,
  `ModifiedDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `branch`
--

INSERT INTO `branch` (`BranchID`, `DbName`, `MainBranchID`, `BranchNo`, `Name`, `Street`, `District`, `Province`, `PostCode`, `SubDistrictID`, `DistrictID`, `ProvinceID`, `ZipCodeID`, `Country`, `Map`, `PhoneNo`, `TableNum`, `CustomerNumMax`, `EmployeePermanentNum`, `Status`, `TakeAwayFee`, `ServiceChargePercent`, `PercentVat`, `PriceIncludeVat`, `CustomerApp`, `ImageUrl`, `StartDate`, `Remark`, `LedStatus`, `ModifiedUser`, `ModifiedDate`) VALUES
(1, 'DEV_JUMMUM_OM_MAMARIN7', 1, '00001', 'JUMMUM', '1234 เซ็นทรัลพระราม 3 ชั้น 3 แขวงบางโพงพางแ', 'ยานนาวา', 'กรุงเทพมหานคร', '10120', 0, 0, 0, 0, 'ไทย', '', '098-111-8888', 15, 45, 20, 1, 5, 10, 7, 0, 1, 'jummum.jpg', '2018-02-13 04:16:54', 'เทส', 1, 'jinglejill@hotmail.com', '2018-08-24 16:14:59'),
(2, 'DEV_JUMMUM_OM_MAMARIN7_2', 2, '9', 'JUMMUM2', 'Y', 'g', 'กรุงเทพมหานคร', '56789', 0, 0, 0, 0, 'ไทย', '', '8887776666', 0, 0, 0, 1, 0, 0, 7, 0, 1, 'jummum2.jpg', '2018-02-13 10:16:41', '', 0, '(null)', '2018-07-26 07:32:40'),
(3, 'DEV_JUMMUM_OM_MAMARIN7_3', 3, 'e', 'JUMMUM3', 'D', 's', 'กรุงเทพมหานคร', 's', 0, 0, 0, 0, 'ไทย', '', 'sa', 0, 0, 0, 1, 0, 0, 0, 0, 1, 'jummum3.jpg', '2018-02-14 03:39:16', '', 1, 'choosak.oun@gmail.com', '2018-08-09 01:00:19'),
(4, 'DEV_JUMMUM_OM_MAMARIN7_4', 4, '4', 'JUMMUM4', 'Fg', 'hh', 'กรุงเทพมหานคร', 'gb', 0, 0, 0, 0, 'ไทย', '', 'v', 0, 0, 0, 3, 0, 0, 0, 0, 1, 'jummum4.jpg', '2018-02-27 10:00:54', '', 0, 'jill', '2018-02-27 10:00:37'),
(5, 'DEV_JUMMUM_OM_MAMARIN7_5', 5, '00001', 'JUMMUM5', '1234 เซ็นทรัลพระราม 3 ชั้น 3 แขวงบางโพงพางแ', 'ยานนาวา', 'กรุงเทพมหานคร', '10120', 0, 0, 0, 0, 'ไทย', '', '098-111-8888', 15, 45, 20, 1, 5, 10, 7, 0, 1, 'jummum5.jpg', '2018-02-13 04:16:54', 'เทส', 1, 'jinglejill@hotmail.com', '2018-08-07 16:48:00'),
(6, 'DEV_JUMMUM_OM_MAMARIN7_6', 6, '00001', 'JUMMUM6', '1234 เซ็นทรัลพระราม 3 ชั้น 3 แขวงบางโพงพางแ', 'ยานนาวา', 'กรุงเทพมหานคร', '10120', 0, 0, 0, 0, 'ไทย', '', '098-111-8888', 15, 45, 20, 1, 5, 10, 7, 0, 1, 'jummum6.jpg', '2018-02-13 04:16:54', 'เทส', 0, 'choosak@jummum.co', '2018-08-08 16:39:02');

-- --------------------------------------------------------

--
-- Table structure for table `device`
--

CREATE TABLE `device` (
  `DeviceID` int(11) NOT NULL,
  `DbName` varchar(50) NOT NULL,
  `DeviceToken` varchar(152) NOT NULL,
  `ModifiedUser` varchar(50) NOT NULL,
  `ModifiedDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `forgotpassword`
--

CREATE TABLE `forgotpassword` (
  `ForgotPasswordID` int(11) NOT NULL,
  `CodeReset` varchar(200) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `RequestDate` datetime NOT NULL,
  `Status` tinyint(4) NOT NULL COMMENT '1=request,2=password changed',
  `DbName` varchar(50) NOT NULL,
  `ModifiedUser` varchar(50) NOT NULL,
  `ModifiedDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `led`
--

CREATE TABLE `led` (
  `LedID` int(11) NOT NULL,
  `BranchID` int(11) NOT NULL,
  `Remark` varchar(100) NOT NULL,
  `ModifiedUser` varchar(50) NOT NULL,
  `ModifiedDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `led`
--

INSERT INTO `led` (`LedID`, `BranchID`, `Remark`, `ModifiedUser`, `ModifiedDate`) VALUES
(1, 1, '', '', '0000-00-00 00:00:00'),
(2, 2, '', '', '0000-00-00 00:00:00'),
(3, 3, '', '', '0000-00-00 00:00:00'),
(4, 4, '', '', '0000-00-00 00:00:00'),
(5, 5, '', '', '0000-00-00 00:00:00'),
(6, 6, '', '', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `setting`
--

CREATE TABLE `setting` (
  `SettingID` int(11) NOT NULL,
  `KeyName` varchar(50) NOT NULL,
  `Value` varchar(500) NOT NULL,
  `Type` tinyint(4) NOT NULL,
  `Remark` varchar(200) NOT NULL,
  `ModifiedUser` varchar(50) NOT NULL,
  `ModifiedDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `setting`
--

INSERT INTO `setting` (`SettingID`, `KeyName`, `Value`, `Type`, `Remark`, `ModifiedUser`, `ModifiedDate`) VALUES
(1, '001t', 'Oop!', 2, '', '', '2018-07-25 11:58:23'),
(2, '001m', 'คุณต้องการยกเลิก & คืนเงิน ใช่หรือไม่', 2, '', '', '2018-07-25 11:58:31'),
(3, '002t', 'Oop!', 2, '', '', '2018-07-25 11:59:23'),
(4, '002m', 'คุณต้องการส่งคำร้อง & คืนเงิน ใช่หรือไม่', 2, '', '', '2018-07-25 11:59:23'),
(5, '003m', 'บิลนี้ได้ส่งเข้าครัวเพื่อปรุงอาหารแล้วค่ะ โปรดรอสักครู่นะคะ', 2, '', '', '2018-07-25 12:00:14'),
(6, '004m', 'อาหารถูกส่งให้ลูกค้าไปแล้วค่ะ', 2, '', '', '2018-07-25 12:00:14'),
(7, '005t', 'รายการอาหารที่ลูกค้าสั่ง', 2, '', '', '2018-07-25 12:01:00'),
(8, '006m', 'Order no. #%@%@', 2, '', '', '2018-08-21 17:11:23'),
(9, '007m', 'Table: %@', 2, '', '', '2018-07-25 12:03:29'),
(10, '008m', 'ส่งเข้าครัว', 2, '', '', '2018-07-25 12:04:53'),
(11, '009m', 'เสิร์ฟ', 2, '', '', '2018-07-25 12:04:47'),
(12, '010m', 'ใส่ห่อ', 2, '', '', '2018-07-25 12:06:15'),
(13, '011m', 'ไม่ใส่', 2, '', '', '2018-07-25 12:09:21'),
(14, '012m', 'เพิ่ม', 2, '', '', '2018-07-25 12:09:21'),
(15, '013m', 'หมายเหตุ: ', 2, '', '', '2018-07-25 12:09:21'),
(16, '014m', 'รวมทั้งหมด', 2, '', '', '2018-07-25 12:10:09'),
(17, '015m', 'ใส่ห่อ %@', 2, '', '', '2018-07-25 12:13:34'),
(18, '016m', 'Receipt no: %@ ส่งเข้าครัวไปก่อนหน้านี้แล้วค่ะ', 2, '', '', '2018-07-25 12:20:07'),
(19, '017m', 'Receipt no: %@ ได้ส่งให้ลูกค้าไปก่อนหน้านี้แล้วค่ะ', 2, '', '', '2018-07-25 12:20:07'),
(20, '018t', 'ยกเลิก & คืนเงิน', 2, '', '', '2018-07-25 12:46:52'),
(21, '019t', 'ส่งคำร้อง & คืนเงิน', 2, '', '', '2018-07-25 12:46:52'),
(22, '020m', 'ใส่เหตุผลในการขอคืนเงิน', 2, '', '', '2018-07-25 12:58:52'),
(23, '021m', 'กรุณากรอกรายละเอียดด้านล่างนี้', 2, '', '', '2018-07-25 12:58:54'),
(24, '022m', 'เหตุผลในการขอคืนเงิน', 2, '', '', '2018-07-25 12:57:52'),
(25, '023m', 'กรุณาเลือกเหตุผลในการขอคืนเงิน', 2, '', '', '2018-07-25 13:03:58'),
(26, '024m', 'เหตุผลเพิ่มเติมในการขอเงินคืน', 2, '', '', '2018-07-25 13:08:00'),
(27, '024t', 'เบอร์โทร.', 2, '', '', '2018-07-25 13:08:23'),
(28, '025m', 'สถานะบิลนี้มีการเปลี่ยนแปลง กรุณาตรวจสอบอีกครั้งก่อนทำรายการ', 2, '', '', '2018-07-25 13:08:50'),
(29, '026m', 'กรุณาเลือกเหตุผลที่ขอคืนเงิน', 2, '', '', '2018-07-25 13:10:42'),
(30, '027m', 'กรุณาใส่เบอร์โทร', 2, '', '', '2018-07-25 13:10:42'),
(31, '028m', 'กรุณาใส่จำนวนเงิน', 2, '', '', '2018-07-25 13:11:13'),
(32, '029t', 'กรุณาใส่จำนวนเงินระหว่าง %@', 2, '', '', '2018-07-25 13:14:18'),
(33, '029m', 'THB 0.01 to %@', 2, '', '', '2018-07-25 13:14:18'),
(34, '030m', 'กรุณาใส่รายละเอียดเหตุผลในการขอเงินคืน', 2, '', '', '2018-07-25 13:14:18'),
(35, '031m', 'กรุณาใส่เบอร์โทร', 2, '', '', '2018-07-25 13:14:18'),
(36, '032m', 'ส่งคำสั่งยกเลิก & คืนเงินสำเร็จ', 2, '', '', '2018-07-27 12:01:42'),
(37, '033m', 'ส่งคำร้องขอคืนเงินสำเร็จ', 2, '', '', '2018-07-27 12:04:12'),
(38, '034m', 'ลูกค้าสั่ง', 2, '', '', '2018-07-25 14:32:43'),
(39, '035m', 'กำลังทำ', 2, '', '', '2018-07-25 14:35:12'),
(40, '036m', 'เสิร์ฟแล้ว', 2, '', '', '2018-07-25 14:35:12'),
(41, '037m', 'Pending cancel', 2, '', '', '2018-07-25 14:35:12'),
(42, '038m', 'Order dispute in process', 2, '', '', '2018-07-25 14:35:12'),
(43, '039m', 'Order cancelled', 2, '', '', '2018-07-25 14:35:12'),
(44, '040m', 'Order dispute finished', 2, '', '', '2018-07-25 14:35:12'),
(45, '041m', 'Negotiate', 2, '', '', '2018-07-25 14:35:12'),
(46, '042m', 'Review dispute', 2, '', '', '2018-07-25 14:35:12'),
(47, '043m', 'Review dispute in process', 2, '', '', '2018-07-25 14:35:12'),
(48, '044m', 'Order dispute finished', 2, '', '', '2018-07-25 14:35:12'),
(49, '045t', 'ลืมรหัสผ่าน', 2, '', '', '2018-07-25 14:35:40'),
(50, '046m', 'คุณใส่อีเมลไม่ถูกต้อง', 2, '', '', '2018-07-25 14:36:13'),
(51, '047m', 'ไม่มีอีเมลนี้ในระบบ', 2, '', '', '2018-07-25 14:37:44'),
(52, '048m', 'เราได้ส่งอีเมลให้คุณแล้ว กรุณาเช็คอีเมลของคุณ', 2, '', '', '2018-07-25 14:37:44'),
(53, '049m', 'Setup Credentials', 2, '', '', '2018-07-25 14:52:22'),
(54, '050m', 'Name', 2, '', '', '2018-07-25 14:52:22'),
(55, '051m', 'Password', 2, '', '', '2018-07-25 14:52:22'),
(56, '052t', 'Warning', 2, '', '', '2018-07-25 14:53:58'),
(57, '052m', 'Application is expired', 2, '', '', '2018-07-25 14:53:58'),
(58, '053t', 'Warning', 2, '', '', '2018-07-25 15:02:53'),
(59, '053m', 'Memory fail', 2, '', '', '2018-07-25 15:02:53'),
(60, '054t', 'Welcome', 2, '', '', '2018-07-25 15:03:56'),
(61, '054m', 'Pay for your order, earn and track rewards, ckeck your balance and more, all from your mobile device', 2, '', '', '2018-07-25 15:03:56'),
(62, '055m', '◻︎ จำฉันไว้ในระบบ', 2, '', '', '2018-07-25 15:13:54'),
(63, '056m', '◼︎ จำฉันไว้ในระบบ', 2, '', '', '2018-07-25 15:13:54'),
(64, '057m', 'There is problem with facebook login, please try again', 2, '', '', '2018-07-25 15:17:39'),
(65, '058m', 'อีเมล/พาสเวิร์ด ไม่ถูกต้อง', 2, '', '', '2018-07-25 15:17:39'),
(66, '059m', 'ข้อกำหนดและเงื่อนไข', 2, '', '', '2018-07-25 15:20:25'),
(67, '060m', 'ตั้งค่าระบบการสั่งอาหารด้วยตนเอง', 2, '', '', '2018-07-25 15:20:25'),
(68, '061m', 'Log out', 2, '', '', '2018-07-25 15:20:25'),
(69, '062m', 'ออกจากระบบสำเร็จ', 2, '', '', '2018-07-25 15:21:33'),
(70, '063t', 'รายละเอียดการสั่งอาหาร', 2, '', '', '2018-07-25 15:25:33'),
(71, '064m', '%ld รายการ', 2, '', '', '2018-07-25 15:28:45'),
(72, '065m', 'Vat %@%%', 2, '', '', '2018-07-25 15:30:58'),
(73, '066m', 'Vat', 2, '', '', '2018-07-25 15:30:58'),
(74, '067m', 'ยอดรวมทั้งสิ้น', 2, '', '', '2018-07-25 15:31:18'),
(75, '068m', 'ส่วนลด %@%%', 2, '', '', '2018-07-25 15:33:14'),
(76, '069m', 'ส่วนลด', 2, '', '', '2018-07-25 15:33:49'),
(77, '070m', 'ยอดรวม', 2, '', '', '2018-07-25 15:34:51'),
(78, '071m', 'Service charge %@%%', 2, '', '', '2018-07-25 15:38:00'),
(79, '072m', 'ลูกค้าของคุณต้องการยกเลิกบิลนี้  ด้วยเหตุผลด้านล่างนี้ กรุณากด Confirm เพื่อ Refund เงินคืนลูกค้า', 2, '', '', '2018-07-25 15:45:23'),
(80, '073m', 'เหตุผล: ', 2, '', '', '2018-07-25 15:48:03'),
(81, '074m', 'จำนวนเงินที่ขอคืน: ', 2, '', '', '2018-07-25 15:48:03'),
(82, '075m', 'เบอร์โทรติดต่อ: ', 2, '', '', '2018-07-25 15:48:03'),
(83, '076t', 'Confirm', 2, '', '', '2018-07-25 15:51:51'),
(84, '077t', 'Cancel', 2, '', '', '2018-07-25 15:51:51'),
(85, '078m', 'ลูกค้าของคุณ Open dispute ด้วยเหตุผลด้านล่างนี้ กรุณากด Confirm เพื่อ Refund เงินคืนลูกค้า หรือหากต้องการแก้ไขรายการกรุณากด Negotiate', 2, '', '', '2018-07-25 15:52:24'),
(87, '079m', 'รายละเอียดเหตุผล: ', 2, '', '', '2018-07-25 15:57:49'),
(88, '080t', 'Negotiate', 2, '', '', '2018-07-25 16:03:41'),
(89, '081m', 'คำร้องขอยกเลิกออเดอร์เสร็จสิ้น', 2, '', '', '2018-07-25 16:04:38'),
(90, '082m', 'กระบวนการ Open dispute เสร็จสิ้น', 2, '', '', '2018-07-25 16:08:41'),
(91, '083m', 'Open dispute ได้มีการแก้ไขตามด้านล่าง โปรดรอการยืนยันจากลูกค้าสักครู่', 2, '', '', '2018-07-25 16:15:11'),
(92, '084m', 'หลังจากคุยกับเจ้าหน้าที่ Jummum แล้ว มีการแก้ไขจำนวนเงิน refund ใหม่ ตามด้านล่างนี้ กรุณากด Confirm เพื่อ Refund เงินคืนลูกค้า หรือหากต้องการแก้ไขรายการกรุณากด Negotiate', 2, '', '', '2018-07-25 16:16:32'),
(93, '085m', 'กระบวนการ Open dispute ดำเนินการเสร็จสิ้นแล้ว', 2, '', '', '2018-07-25 16:43:05'),
(94, '086t', 'สำเร็จ', 2, '', '', '2018-07-25 17:01:17'),
(95, '086m', 'คุณส่งคำร้องขอแก้ไขจำนวนเงินที่จะ refund สำเร็จ จะมีเจ้าหน้าที่ติดต่อกลับไปภายใน 24 ชม.', 2, '', '', '2018-07-25 17:01:17'),
(96, '087t', 'สำเร็จ', 2, '', '', '2018-07-25 17:01:37'),
(97, '087m', 'ลูกค้าของคุณจะได้รับเงินคืนภายใน 48 ชม.', 2, '', '', '2018-07-25 17:02:16'),
(98, '088t', 'สำเร็จ', 2, '', '', '2018-07-25 17:02:07'),
(99, '088m', 'กระบวนการ refund เงินคืนลูกค้าสำเร็จ', 2, '', '', '2018-07-25 17:02:07'),
(100, '089t', 'ข้อมูลส่วนตัว', 2, '', '', '2018-07-25 17:02:58'),
(101, '090m', 'ชื่อร้าน', 2, '', '', '2018-07-25 17:03:52'),
(102, '091m', 'อีเมล', 2, '', '', '2018-07-25 17:04:56'),
(103, '092m', 'ชื่อ', 2, '', '', '2018-07-25 17:04:56'),
(104, '093t', 'ข้อกำหนดและเงื่อนไขของ JUMMUM OM', 2, '', '', '2018-07-27 14:21:02'),
(105, '094t', 'ข้อกำหนดและเงื่อนไขของ JUMMUM OM', 2, '', '', '2018-07-27 14:21:05'),
(106, '095t', 'ตั้งค่าระบบการสั่งอาหารด้วยตนเอง', 2, '', '', '2018-07-25 17:07:36'),
(107, '096m', 'เปิด', 2, '', '', '2018-07-25 17:10:12'),
(108, '0976m', 'ปิด', 2, '', '', '2018-07-25 17:10:12'),
(109, '098m', 'ตามที่ตั้งค่าไว้', 2, '', '', '2018-07-25 17:10:12'),
(110, '099m', 'ตั้งค่าสำเร็จ', 2, '', '', '2018-07-25 17:10:12'),
(111, '100m', 'Processing...', 2, '', '', '2018-07-25 17:11:14'),
(113, 'reminderInterval', '180', 0, 'ทุก 3 นาที', '', '2018-07-31 19:11:59'),
(114, '101m', 'ติดต่อ JUMMUM', 2, '', '', '2018-07-25 17:11:14'),
(115, '102t', 'ติดต่อ JUMMUM', 2, '', '', '2018-07-25 17:11:14'),
(116, 'UpdateVersion1.2.1', '1', 0, '', '', '2018-08-21 13:21:18'),
(117, '103t', 'It\'s time to update', 2, '', '', '2018-08-20 14:23:43'),
(118, '103m', 'A newer version of the app is available for you, please update to get the latest features.', 2, '', '', '2018-08-22 18:10:33'),
(119, 'UpdateVersion1.2', '1', 0, '', '', '2018-08-21 13:21:14'),
(120, '104t', 'ตั้งค่าเครื่องพิมพ์', 2, '', '', '2018-08-24 02:17:14'),
(121, '105t', 'เลือกเครื่องพิมพ์', 2, '', '', '2018-08-24 02:17:14'),
(122, '106t', 'พิมพ์', 2, '', '', '2018-08-24 02:17:14');

-- --------------------------------------------------------

--
-- Table structure for table `useraccount`
--

CREATE TABLE `useraccount` (
  `UserAccountID` int(11) NOT NULL,
  `BranchID` int(11) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(250) NOT NULL,
  `DeviceToken` varchar(64) NOT NULL,
  `FullName` varchar(150) NOT NULL,
  `NickName` varchar(50) NOT NULL,
  `BirthDate` datetime NOT NULL,
  `Email` varchar(100) NOT NULL,
  `PhoneNo` varchar(100) NOT NULL,
  `LineID` varchar(100) NOT NULL,
  `RoleID` int(11) NOT NULL,
  `ModifiedUser` varchar(50) NOT NULL,
  `ModifiedDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `useraccount`
--

INSERT INTO `useraccount` (`UserAccountID`, `BranchID`, `Username`, `Password`, `DeviceToken`, `FullName`, `NickName`, `BirthDate`, `Email`, `PhoneNo`, `LineID`, `RoleID`, `ModifiedUser`, `ModifiedDate`) VALUES
(1, 1, 'jinglejill@hotmail.com', '37bc854596cb721fca1da704fd385296bf881462013f09ffd80af72f0e92b5bc', '', 'thidaporn', '', '1998-01-05 00:00:00', 'jinglejill@hotmail.com', '0845552222', '', 0, '3', '2018-07-22 08:03:56'),
(2, 2, 'natee.rueng@gmail.com', '37bc854596cb721fca1da704fd385296bf881462013f09ffd80af72f0e92b5bc', '', 'Natee', '', '1998-01-05 00:00:00', 'natee.rueng@gmail.com', '0891212882', '', 0, '3', '2018-07-22 08:03:56'),
(3, 6, 'choosak@jummum.co', '37bc854596cb721fca1da704fd385296bf881462013f09ffd80af72f0e92b5bc', '', 'Choosak Ounjitti', '', '1998-01-05 00:00:00', 'nah', '0863311199', '', 0, '3', '2018-07-22 08:03:56'),
(4, 3, 'rinpaphat@jummum.co', '37bc854596cb721fca1da704fd385296bf881462013f09ffd80af72f0e92b5bc', '', 'Rinpaphat Pipachtanapakdee', '', '1998-01-05 00:00:00', 'rinpaphat@jummum.co', '0988413241', '', 0, '3', '2018-07-22 08:03:56'),
(5, 5, 'wasawat@jummum.co', '37bc854596cb721fca1da704fd385296bf881462013f09ffd80af72f0e92b5bc', '', 'Wasawat Kittaweesinpitiya', '', '1998-01-05 00:00:00', 'wasawat@jummum.co', '0641288886', '', 0, '3', '2018-07-22 08:03:56');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `branch`
--
ALTER TABLE `branch`
  ADD PRIMARY KEY (`BranchID`);

--
-- Indexes for table `device`
--
ALTER TABLE `device`
  ADD PRIMARY KEY (`DeviceID`);

--
-- Indexes for table `forgotpassword`
--
ALTER TABLE `forgotpassword`
  ADD PRIMARY KEY (`ForgotPasswordID`);

--
-- Indexes for table `led`
--
ALTER TABLE `led`
  ADD PRIMARY KEY (`LedID`);

--
-- Indexes for table `setting`
--
ALTER TABLE `setting`
  ADD PRIMARY KEY (`SettingID`);

--
-- Indexes for table `useraccount`
--
ALTER TABLE `useraccount`
  ADD PRIMARY KEY (`UserAccountID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `branch`
--
ALTER TABLE `branch`
  MODIFY `BranchID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
--
-- AUTO_INCREMENT for table `device`
--
ALTER TABLE `device`
  MODIFY `DeviceID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `forgotpassword`
--
ALTER TABLE `forgotpassword`
  MODIFY `ForgotPasswordID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `led`
--
ALTER TABLE `led`
  MODIFY `LedID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `setting`
--
ALTER TABLE `setting`
  MODIFY `SettingID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123;
--
-- AUTO_INCREMENT for table `useraccount`
--
ALTER TABLE `useraccount`
  MODIFY `UserAccountID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
