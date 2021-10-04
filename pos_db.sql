-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 04, 2021 at 09:46 AM
-- Server version: 10.1.32-MariaDB
-- PHP Version: 7.2.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pos_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_category`
--

CREATE TABLE `tbl_category` (
  `cat_id` int(11) NOT NULL,
  `cat_name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_category`
--

INSERT INTO `tbl_category` (`cat_id`, `cat_name`) VALUES
(1, 'Cereals'),
(2, 'Retail'),
(3, 'Wholesale');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_invoice`
--

CREATE TABLE `tbl_invoice` (
  `invoice_id` int(11) NOT NULL,
  `cashier_name` varchar(100) NOT NULL,
  `order_date` date NOT NULL,
  `time_order` varchar(50) NOT NULL,
  `total` decimal(8,2) NOT NULL,
  `sale_profit` float(10,0) NOT NULL,
  `paid` decimal(8,2) NOT NULL,
  `cash_balance` decimal(8,2) NOT NULL,
  `credit_balance` decimal(8,2) NOT NULL,
  `due_date` date NOT NULL,
  `sale_type` varchar(20) NOT NULL,
  `customer_no` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Paid'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_invoice`
--

INSERT INTO `tbl_invoice` (`invoice_id`, `cashier_name`, `order_date`, `time_order`, `total`, `sale_profit`, `paid`, `cash_balance`, `credit_balance`, `due_date`, `sale_type`, `customer_no`, `status`) VALUES
(2, 'Sam', '2021-10-02', '14:02', '180.00', 46, '200.00', '20.00', '0.00', '0000-00-00', 'Cash', '', 'Paid'),
(5, 'Sam', '2021-10-02', '19:34', '90.00', 20, '0.00', '0.00', '0.00', '2021-10-06', 'Credit', '0112553167', 'Cleared'),
(6, 'Sam', '2021-10-02', '20:20', '190.00', 10, '200.00', '10.00', '0.00', '0000-00-00', 'Cash', '', 'Paid'),
(7, 'Sam', '2021-10-02', '20:21', '500.00', 162, '500.00', '0.00', '0.00', '0000-00-00', 'Cash', '', 'Paid'),
(8, 'Sam', '2021-10-03', '14:53', '205.00', 45, '250.00', '45.00', '0.00', '0000-00-00', 'Cash', '', 'Paid');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_invoice_detail`
--

CREATE TABLE `tbl_invoice_detail` (
  `id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_code` char(6) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `qty` int(11) NOT NULL,
  `product_unit` varchar(20) NOT NULL,
  `price` float NOT NULL,
  `total` float NOT NULL,
  `item_profit` float(10,0) NOT NULL,
  `order_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_invoice_detail`
--

INSERT INTO `tbl_invoice_detail` (`id`, `invoice_id`, `product_id`, `product_code`, `product_name`, `qty`, `product_unit`, `price`, `total`, `item_profit`, `order_date`) VALUES
(3, 2, 20, '#sugar', 'Mumias Sugar', 1, 'Kg', 120, 120, 40, '2021-10-02'),
(4, 2, 17, '#edent', 'Eden Tea', 3, 'g', 20, 60, 6, '2021-10-02'),
(7, 5, 2, '#beans', 'Beans', 1, 'Kg', 90, 90, 20, '2021-10-02'),
(8, 6, 12, '#ndeng', 'Ndegu', 2, 'Kg', 95, 190, 10, '2021-10-02'),
(9, 7, 20, '#sugar', 'Mumias Sugar', 4, 'Kg', 120, 480, 160, '2021-10-02'),
(10, 7, 17, '#edent', 'Eden Tea', 1, 'g', 20, 20, 2, '2021-10-02'),
(11, 8, 2, '#beans', 'Beans', 2, 'Kg', 90, 180, 40, '2021-10-03'),
(12, 8, 18, '#aeria', 'Aerial Washing Powder', 1, 'g', 25, 25, 5, '2021-10-03');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product`
--

CREATE TABLE `tbl_product` (
  `product_id` int(11) NOT NULL,
  `product_code` char(6) NOT NULL,
  `product_name` varchar(200) NOT NULL,
  `product_category` varchar(200) NOT NULL,
  `purchase_price` float(10,0) NOT NULL,
  `sell_price` float(10,0) NOT NULL,
  `product_profit` float(10,0) NOT NULL,
  `stock` int(11) NOT NULL,
  `min_stock` int(11) NOT NULL,
  `product_unit` varchar(200) NOT NULL,
  `description` varchar(200) NOT NULL,
  `img` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_product`
--

INSERT INTO `tbl_product` (`product_id`, `product_code`, `product_name`, `product_category`, `purchase_price`, `sell_price`, `product_profit`, `stock`, `min_stock`, `product_unit`, `description`, `img`) VALUES
(2, '#beans', 'Beans', 'Cereals', 70, 90, 20, 15, 5, 'Kg', '', '61582325b31df.jfif'),
(12, '#ndeng', 'Ndegu', 'Cereals', 90, 95, 5, 17, 7, 'Kg', 'Green Grams', '61582379a21fd.jpg'),
(15, '#wheat', 'Wheat Floor', 'Cereals', 60, 65, 5, 19, 5, 'Kg', '', '615823e384c3f.jfif'),
(17, '#edent', 'Eden Tea', 'Cereals', 18, 20, 2, 11, 5, 'g', '', '615824b786b86.jfif'),
(18, '#aeria', 'Aerial Washing Powder', 'Cereals', 20, 25, 5, 19, 10, 'g', '', '6158244d16ec0.jpg'),
(19, '#cooki', 'Cooking Fat', 'Retail', 30, 40, 10, 17, 10, 'Kg', '', '61582827447c0.jfif'),
(20, '#sugar', 'Mumias Sugar', 'Cereals', 80, 120, 40, 12, 5, 'Kg', 'White Mumias Sugar', '61582853d28ec.jpg'),
(21, '#meneg', 'Menengai Bar soap', 'Retail', 23, 24, 1, 20, 5, 'n/a', '', '6158289363ac9.jpg'),
(22, '#rice', 'Rice', 'Cereals', 80, 100, 20, 10, 5, 'Kg', '', '615828dd5db19.jfif'),
(23, 'test', 'test', 'Cereals', 50, 60, 0, 10, 5, 'Kg', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_repayments`
--

CREATE TABLE `tbl_repayments` (
  `id` int(11) NOT NULL,
  `cashier_name` varchar(50) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `creditor_no` varchar(50) NOT NULL,
  `amount_paid` decimal(8,2) NOT NULL,
  `date_paid` date NOT NULL,
  `credit_balance` decimal(8,2) NOT NULL,
  `due_date` date NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Paid'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_repayments`
--

INSERT INTO `tbl_repayments` (`id`, `cashier_name`, `invoice_id`, `creditor_no`, `amount_paid`, `date_paid`, `credit_balance`, `due_date`, `status`) VALUES
(1, 'Sam', 1, '0112553167', '100.00', '2021-10-02', '0.00', '2021-09-09', 'Paid'),
(2, 'Sam', 5, '0112553167', '100.00', '2021-10-02', '0.00', '2021-10-06', 'Paid');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_unit`
--

CREATE TABLE `tbl_unit` (
  `unit_id` int(2) NOT NULL,
  `unit_name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_unit`
--

INSERT INTO `tbl_unit` (`unit_id`, `unit_name`) VALUES
(1, 'g'),
(2, 'Kg'),
(3, 'n/a'),
(4, 'pieces');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `user_id` int(11) NOT NULL,
  `username` varchar(200) NOT NULL,
  `fullname` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `role` varchar(15) NOT NULL,
  `is_active` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`user_id`, `username`, `fullname`, `password`, `role`, `is_active`) VALUES
(1, 'Sam', 'Samwel Wambugu', '4321', 'Admin', 1),
(2, 'user', 'Samwel Wambugu', '1234', 'Operator', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_category`
--
ALTER TABLE `tbl_category`
  ADD PRIMARY KEY (`cat_id`),
  ADD UNIQUE KEY `cat_name` (`cat_name`),
  ADD UNIQUE KEY `cat_name_2` (`cat_name`);

--
-- Indexes for table `tbl_invoice`
--
ALTER TABLE `tbl_invoice`
  ADD PRIMARY KEY (`invoice_id`);

--
-- Indexes for table `tbl_invoice_detail`
--
ALTER TABLE `tbl_invoice_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_product`
--
ALTER TABLE `tbl_product`
  ADD PRIMARY KEY (`product_id`),
  ADD UNIQUE KEY `product_code` (`product_code`,`product_name`);

--
-- Indexes for table `tbl_repayments`
--
ALTER TABLE `tbl_repayments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_unit`
--
ALTER TABLE `tbl_unit`
  ADD PRIMARY KEY (`unit_id`),
  ADD UNIQUE KEY `nm_satuan` (`unit_name`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_category`
--
ALTER TABLE `tbl_category`
  MODIFY `cat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_invoice`
--
ALTER TABLE `tbl_invoice`
  MODIFY `invoice_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_invoice_detail`
--
ALTER TABLE `tbl_invoice_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tbl_product`
--
ALTER TABLE `tbl_product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `tbl_repayments`
--
ALTER TABLE `tbl_repayments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_unit`
--
ALTER TABLE `tbl_unit`
  MODIFY `unit_id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
