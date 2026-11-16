-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 16, 2026 at 05:57 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `final_project`
--

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `cid` int(100) NOT NULL,
  `cusername` varchar(100) NOT NULL,
  `cpassword` varchar(100) NOT NULL,
  `cname` varchar(100) NOT NULL,
  `csurname` varchar(100) NOT NULL,
  `tel` varchar(10) NOT NULL,
  `address` varchar(10) NOT NULL,
  `soi` varchar(20) NOT NULL,
  `street` varchar(20) NOT NULL,
  `sub_district` varchar(20) NOT NULL,
  `district` varchar(20) NOT NULL,
  `province` varchar(20) NOT NULL,
  `post_id` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`cid`, `cusername`, `cpassword`, `cname`, `csurname`, `tel`, `address`, `soi`, `street`, `sub_district`, `district`, `province`, `post_id`) VALUES
(1, 'Mgxsek', 'Sek281046', 'นันทสิทธิ์', 'เพชรศรี', '0622915447', '18/71 หมู่', 'เย็นใจ', 'มีชา', 'ปากแตระ', 'ระโนด', 'สงขลา', '90140'),
(2, 'darin', '12345678', 'รินรดา', 'กิตติฤดีกุล', '0961926502', '100', 'บ้านดอกไม้', 'ช่อกุกลาบ', 'บ้านบาตร', 'ป้อมปราบศัตรูพ่าย', 'กรุงเทพมหานคร', '10100'),
(5, 'kwangkim', '12345678', 'กาญจนา', 'สิงห์แหลม', '0928808416', '136/1', 'พัลลภา', '', 'รอบเมือง', 'เมือง', 'ปราจีนบุรี', '25000');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `eid` int(10) NOT NULL,
  `ename` varchar(50) NOT NULL,
  `esurname` varchar(50) NOT NULL,
  `eusername` varchar(50) NOT NULL,
  `epassword` varchar(50) NOT NULL,
  `role` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`eid`, `ename`, `esurname`, `eusername`, `epassword`, `role`) VALUES
(1, 'Admin', 'Admin', 'admin', 'admin', 'admin'),
(3, 'Kongpana', 'Wongsoison', 'Kongpana', '12345678', 'CEO'),
(4, 'Kanjana', 'Singlam', 'Kan', '12345678', 'staff');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `order_date` date NOT NULL,
  `cid` int(100) NOT NULL,
  `total_amount` decimal(65,0) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `order_date`, `cid`, `total_amount`, `payment_method`, `status`) VALUES
(1, '2026-11-14', 1, 11000, 'QR PromptPay', 'จัดส่งสำเร็จ'),
(2, '2026-11-14', 1, 11000, 'QR PromptPay', 'จัดส่งสำเร็จ'),
(3, '2026-11-14', 1, 11000, 'QR PromptPay', 'จัดส่งสำเร็จ'),
(6, '2026-11-14', 1, 11000, 'QR PromptPay', 'กำลังจัดส่ง'),
(7, '2026-11-14', 1, 11000, 'QR PromptPay', 'กำลังจัดส่ง'),
(8, '2026-11-14', 1, 33000, 'Bank Transfer', 'กำลังจัดส่ง'),
(9, '2026-11-14', 1, 33000, 'Bank Transfer', 'กำลังจัดส่ง'),
(10, '2026-11-14', 2, 201500, 'Bank Transfer', 'รอดำเนินการ'),
(11, '2026-11-14', 2, 43200, 'Bank Transfer', 'รอดำเนินการ'),
(12, '2026-11-14', 2, 28900, 'QR PromptPay', 'รอดำเนินการ'),
(13, '2026-11-14', 2, 28900, 'QR PromptPay', 'รอดำเนินการ'),
(14, '2026-11-14', 2, 28900, 'QR PromptPay', 'กำลังจัดส่ง'),
(15, '2026-11-14', 2, 28900, 'QR PromptPay', 'กำลังจัดส่ง'),
(16, '2026-11-14', 2, 11000, 'QR PromptPay', 'จัดส่งสำเร็จ'),
(17, '2026-11-15', 2, 408000, 'Bank Transfer', 'จัดส่งสำเร็จ'),
(18, '2026-11-15', 3, 146056, 'QR PromptPay', 'จัดส่งสำเร็จ'),
(19, '2026-11-15', 5, 2000, 'QR PromptPay', 'รอดำเนินการ'),
(20, '2026-11-16', 5, 3700, 'QR PromptPay', 'จัดส่งสำเร็จ'),
(21, '2026-11-16', 5, 700, 'QR PromptPay', 'รอดำเนินการ'),
(22, '2026-11-16', 5, 20000, 'QR PromptPay', 'จัดส่งสำเร็จ'),
(23, '2026-11-16', 2, 1000, 'Bank Transfer', 'รอดำเนินการ'),
(24, '2026-11-16', 2, 6800, 'Bank Transfer', 'รอดำเนินการ'),
(25, '2026-11-16', 2, 700, 'QR PromptPay', 'รอดำเนินการ'),
(26, '2026-11-16', 2, 1700, 'QR PromptPay', 'รอดำเนินการ'),
(27, '2026-11-16', 1, 3700, 'Bank Transfer', 'รอดำเนินการ'),
(28, '2026-11-16', 1, 700, 'QR PromptPay', 'รอดำเนินการ'),
(29, '2026-11-16', 2, 700, 'QR PromptPay', 'รอดำเนินการ'),
(30, '2026-11-16', 2, 700, 'QR PromptPay', 'รอดำเนินการ'),
(31, '2026-11-16', 2, 700, 'QR PromptPay', 'รอดำเนินการ'),
(32, '2026-11-16', 2, 700, 'QR PromptPay', 'รอดำเนินการ');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `detail_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `wid` int(11) NOT NULL,
  `quantity` int(50) NOT NULL,
  `unit_price` int(50) NOT NULL,
  `subtotal` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`detail_id`, `order_id`, `wid`, `quantity`, `unit_price`, `subtotal`) VALUES
(1, 6, 6, 2, 5500, 11000),
(2, 7, 6, 2, 5500, 11000),
(3, 8, 20, 1, 33000, 33000),
(4, 9, 20, 1, 33000, 33000),
(5, 10, 5, 3, 20000, 60000),
(6, 10, 20, 4, 33000, 132000),
(7, 10, 1, 4, 1000, 4000),
(8, 10, 6, 1, 5500, 5500),
(9, 11, 1, 4, 1000, 4000),
(10, 11, 3, 1, 700, 700),
(11, 11, 6, 1, 5500, 5500),
(12, 11, 20, 1, 33000, 33000),
(13, 12, 3, 2, 700, 1400),
(14, 12, 12, 1, 4500, 4500),
(15, 12, 5, 1, 20000, 20000),
(16, 12, 2, 1, 2000, 2000),
(17, 12, 1, 1, 1000, 1000),
(18, 13, 3, 2, 700, 1400),
(19, 13, 12, 1, 4500, 4500),
(20, 13, 5, 1, 20000, 20000),
(21, 13, 2, 1, 2000, 2000),
(22, 13, 1, 1, 1000, 1000),
(23, 14, 3, 2, 700, 1400),
(24, 14, 12, 1, 4500, 4500),
(25, 14, 5, 1, 20000, 20000),
(26, 14, 2, 1, 2000, 2000),
(27, 14, 1, 1, 1000, 1000),
(28, 15, 3, 2, 700, 1400),
(29, 15, 12, 1, 4500, 4500),
(30, 15, 5, 1, 20000, 20000),
(31, 15, 2, 1, 2000, 2000),
(32, 15, 1, 1, 1000, 1000),
(33, 16, 2, 1, 2000, 2000),
(34, 16, 12, 2, 4500, 9000),
(35, 17, 2, 1, 2000, 2000),
(36, 17, 8, 1, 400000, 400000),
(37, 17, 14, 3, 2000, 6000),
(38, 18, 5, 7, 20000, 140000),
(39, 18, 4, 1, 2100, 2100),
(40, 18, 21, 4, 989, 3956),
(41, 19, 2, 1, 2000, 2000),
(42, 20, 11, 1, 3000, 3000),
(43, 20, 3, 1, 700, 700),
(44, 21, 3, 1, 700, 700),
(45, 22, 5, 1, 20000, 20000),
(46, 23, 1, 1, 1000, 1000),
(47, 24, 2, 2, 2000, 4000),
(48, 24, 4, 1, 2100, 2100),
(49, 24, 3, 1, 700, 700),
(50, 25, 3, 1, 700, 700),
(51, 26, 1, 1, 1000, 1000),
(52, 26, 3, 1, 700, 700),
(53, 27, 1, 3, 1000, 3000),
(54, 27, 3, 1, 700, 700),
(55, 28, 3, 1, 700, 700),
(56, 29, 3, 1, 700, 700),
(57, 30, 3, 1, 700, 700),
(58, 31, 3, 1, 700, 700),
(59, 32, 3, 1, 700, 700);

-- --------------------------------------------------------

--
-- Table structure for table `winetbl`
--

CREATE TABLE `winetbl` (
  `wid` int(5) NOT NULL,
  `wname` varchar(80) NOT NULL,
  `country` varchar(20) NOT NULL,
  `region` varchar(80) NOT NULL,
  `year` year(4) NOT NULL,
  `wtype` varchar(20) NOT NULL,
  `wstyle` varchar(10) NOT NULL,
  `grape` varchar(80) NOT NULL,
  `bottlclosure` varchar(10) NOT NULL,
  `%abv` varchar(10) NOT NULL,
  `cost` int(5) NOT NULL,
  `price` int(5) NOT NULL,
  `quantity` int(6) NOT NULL,
  `wimg` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `winetbl`
--

INSERT INTO `winetbl` (`wid`, `wname`, `country`, `region`, `year`, `wtype`, `wstyle`, `grape`, `bottlclosure`, `%abv`, `cost`, `price`, `quantity`, `wimg`) VALUES
(1, 'Fantini Edizione Bianco', 'Italy', 'Abruzzo', '2022', 'White Wine', 'Blend', 'Fiano,Grillo,Pecorino', 'cork', '15.0%', 716, 1000, 258, 'Fantini Edizione Bianco.png'),
(2, 'Luigi Bosca', 'Argentina', 'Mendoza', '2023', 'Red Wine', 'Single', 'Malbec', 'cork', '14.4%', 944, 2000, 78, 'Luigi Bosca.png'),
(3, 'Yellow Tail Pink Moscato', 'Australia', 'South Eastern Australia', '2021', 'Rosé Wine', 'Single', 'Muscat Blanc', 'screw', '12.0%', 436, 700, 300, 'Yellow Tail Pink Moscato.png'),
(4, 'Famille Berrouet\r\nVieux Château Saint André Montagne-Saint-Émilion', 'France', 'Bordeaux / Libournais / Saint-Émilion / Montagne-Saint-Émilion', '2015', 'Red Wine', 'Blend', 'Malbec, Merlot, Petit Verdot, Cabernet Sauvignon, Cabernet Franc', 'cork', '13.0%', 942, 2100, 180, 'Famille Berrouet.png'),
(5, 'Tenuta San Guido Sassicaia', 'Italy', 'Central Italy / Toscana / Bolgheri / Bolgheri Sassicaia', '1999', 'Red Wine', 'Blend', 'Cabernet Sauvignon, Cabernet Franc', 'cork', '13.0%', 16896, 20000, 6, 'Tenuta San Guido.png'),
(6, 'Tenuta Luce ', 'Italy', 'Central Italy / Toscana', '2015', 'Red Wine', 'Blend', 'Merlot,Sangiovese', 'cock', '15.0%', 4055, 5500, 36, 'Tenuta Luce.png'),
(7, 'Tua Rita Keir', 'Italy', 'Central Italy / Toscana', '2017', 'Red Wine', 'Single', 'Shiraz', 'cork', '15.0%', 2593, 6000, 20, 'Tua Rita Keir.png'),
(8, 'Domaine de La Romanée-Conti Montrachet Grand Cru', 'France', 'Bourgogne / Côte de Beaune / Puligny-Montrachet / Montrachet Grand Cru', '2017', 'White Wine', 'Single', 'Chardonnay', 'cork', '13.5%', 364954, 400000, 6, 'Domaine de La Romanée-Conti Montrachet Grand Cru.png'),
(9, 'Marqués de Murrieta Castillo Ygay Gran Reserva Especial Blanco', 'Spain', 'Rioja', '1998', 'White Wine', 'Blend', 'Malvasia, Viura', 'cork', '13.5%', 1660, 2200, 180, 'Marqués de Murrieta Castillo Ygay Gran Reserva Especial Blanco.png'),
(10, 'Turnbull Leopoldina', 'United States', 'California / North Coast / Napa County / Napa Valley / Oakville', '2022', 'Red Wine', 'Single', 'Cabernet Sauvignon', 'cork', '14.7%', 3379, 4000, 36, 'Turnbull Leopoldina.png'),
(11, 'VIK Winery La Piu Belle', 'Chile', 'Central Valley / Rapel Valley / Cachapoal Valley', '2011', 'Red Wine', 'Blend', 'Shiraz/Syrah, Merlot, Cabernet Sauvignon, Cabernet Franc, Carménère', 'cork', '14.0%', 2593, 3000, 66, 'VIK Winery La Piu Belle.png'),
(12, 'Montes Folly', 'Chile', 'Central Valley / Rapel Valley / Colchagua Valley', '2005', 'Red Wine', 'Single', 'Shiraz', 'cork', '15.0%', 3319, 4500, 30, 'Montes Folly.png'),
(13, 'Jacques Selosse Substance Blanc de Blancs Brut Champagne Grand Cru \'Avize\' N.V.', 'France', 'Champagne / Côte des Blancs / Champagne Grand Cru \'Avize\'', '1986', 'Sparkling wine', 'Single', 'Chardonnay', 'cork', '12.0%', 42240, 45000, 3, 'Jacques Selosse Substance Blanc de Blancs Brut Champagne Grand Cru \'Avize\' N.V..png'),
(14, 'Agusti Torello Mata Cava Kripta Gran Reserva Brut Nature', '	Spain', 'Cava', '2008', 'Sparkling wine', 'Blend', 'Macabeo,Parellada,Xarel-lo', 'cork', '11.5%', 1556, 2000, 12, 'Agusti Torello Mata Cava Kripta Gran Reserva Brut Nature.png'),
(15, 'Moët & Chandon Ice Impérial (Demi-Sec) Rosé Champagne N.V.', 'France', 'Champagne', '2017', 'Sparkling wine', 'Blend', 'Pinot Meunier, Pinot Noir, Chardonnay', 'cork', '13.5', 2196, 2700, 24, 'Moët & Chandon Ice Impérial (Demi-Sec) Rosé Champagne N.V..png'),
(16, 'Royal Tokaji Essencia', 'Hungary', 'Tokaj', '2000', 'Dessert wine', 'Blend', 'Yellow Muscat, Hárslevelű, Furmint', 'cork', '2.0%', 22820, 25000, 6, 'Royal Tokaji Essencia.png'),
(17, 'Donnafugata Ben Ryé Passito di Pantelleria', 'Italy', 'Southern Italy / Terre Siciliane / Moscato di Pantelleria / Passito di Panteller', '2014', 'Dessert wine', 'Single', 'Zibibbo', 'cork', '14.4%', 2282, 2700, 60, 'Donnafugata Ben Ryé Passito di Pantelleria.png'),
(18, 'Viña Maipo Dulzino Moscato', 'Chile', 'Central Valley', '2017', 'Dessert wine', 'Single', 'Moscato', 'screw', '9.5%', 429, 500, 240, 'Viña Maipo Dulzino Moscato.png'),
(19, 'Quinta do Noval Vintage Port', 'Portugal', 'Northern Portugal / Duriense / Porto', '1970', 'Fortified Wine', 'Single', 'Touriga Nacional', 'cork', '20.0%', 42240, 35000, 12, 'Quinta do Noval Vintage Port.png'),
(20, 'Warre\'s Vintage Port', 'Portugal', 'Northern Portugal / Duriense / Porto', '2003', 'Fortified Wine', 'Single', 'Touriga Nacional', 'cork', '20.0%', 32102, 33000, 66, 'Warre\'s Vintage Port.png'),
(21, 'Robert Mondavi Private Selection Cabernet Sauvignon', 'USA', 'California', '2022', 'Red Wine', 'Single ', ' Cabernet Sauvignon', 'Cork', ' 13.5%', 690, 989, 50, 'Robert Mondavi Private Selection Cabernet Sauvignon.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`cid`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`eid`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`detail_id`,`order_id`,`wid`);

--
-- Indexes for table `winetbl`
--
ALTER TABLE `winetbl`
  ADD PRIMARY KEY (`wid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `cid` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `eid` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `winetbl`
--
ALTER TABLE `winetbl`
  MODIFY `wid` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
