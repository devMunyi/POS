-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 05, 2021 at 08:52 AM
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
(1, 'Cereal'),
(2, 'Retail'),
(3, 'Wholesale');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_credit_limit`
--

CREATE TABLE `tbl_credit_limit` (
  `credit_id` int(11) NOT NULL,
  `cust_no` varchar(100) NOT NULL,
  `cust_name` varchar(100) NOT NULL,
  `credit_amount` decimal(8,2) NOT NULL,
  `data_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_credit_limit`
--

INSERT INTO `tbl_credit_limit` (`credit_id`, `cust_no`, `cust_name`, `credit_amount`, `data_created`, `date_updated`) VALUES
(1, '0112553167', '', '156.00', '2021-11-14 13:09:08', '2021-11-14 13:37:54');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_invoice`
--

CREATE TABLE `tbl_invoice` (
  `invoice_id` int(11) NOT NULL,
  `cashier_name` varchar(100) NOT NULL,
  `order_date` date NOT NULL,
  `order_updated_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `time_order` varchar(50) NOT NULL,
  `total` decimal(8,2) NOT NULL,
  `sale_profit` decimal(8,2) NOT NULL,
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

INSERT INTO `tbl_invoice` (`invoice_id`, `cashier_name`, `order_date`, `order_updated_date`, `time_order`, `total`, `sale_profit`, `paid`, `cash_balance`, `credit_balance`, `due_date`, `sale_type`, `customer_no`, `status`) VALUES
(1, 'Sam', '2021-11-14', '2021-11-14 13:47:43', '13:08', '25.00', '5.00', '30.00', '5.00', '0.00', '0000-00-00', 'Cash', '0112553167', 'Paid'),
(2, 'Sam', '2021-11-14', '2021-11-14 13:47:43', '13:23', '875.00', '149.00', '1000.00', '125.00', '0.00', '0000-00-00', 'Cash', '0112553167', 'Paid'),
(3, 'Sam', '2021-11-14', '2021-11-14 13:47:43', '13:25', '48.00', '2.00', '0.00', '0.00', '48.00', '2021-12-17', 'Credit', '0112553167', 'Unpaid'),
(4, 'Sam', '2021-11-14', '2021-11-14 13:47:43', '13:32', '90.00', '20.00', '100.00', '10.00', '0.00', '0000-00-00', 'Cash', '', 'Paid'),
(5, 'Sam', '2021-11-14', '2021-11-14 13:47:43', '13:34', '25.00', '5.00', '30.00', '5.00', '0.00', '0000-00-00', 'Cash', '', 'Paid'),
(6, 'Sam', '2021-11-14', '2021-11-14 13:47:43', '13:36', '20.00', '2.00', '20.00', '0.00', '0.00', '0000-00-00', 'Cash', '0112553167', 'Paid'),
(7, 'Sam', '2021-11-14', '2021-11-14 13:47:43', '13:37', '80.00', '20.00', '100.00', '20.00', '0.00', '0000-00-00', 'Cash', '0112553167', 'Paid');

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
  `qty` decimal(8,2) NOT NULL,
  `product_unit` varchar(20) NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `total` decimal(8,2) NOT NULL,
  `item_profit` decimal(8,2) NOT NULL,
  `order_date` date NOT NULL,
  `order_updated_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `sale_type` varchar(100) NOT NULL DEFAULT 'Cash',
  `status` varchar(100) NOT NULL DEFAULT 'Paid'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_invoice_detail`
--

INSERT INTO `tbl_invoice_detail` (`id`, `invoice_id`, `product_id`, `product_code`, `product_name`, `qty`, `product_unit`, `price`, `total`, `item_profit`, `order_date`, `order_updated_date`, `sale_type`, `status`) VALUES
(1, 1, 18, '#aeria', 'Aerial Washing Powder', '1.00', 'g', '25.00', '25.00', '5.00', '2021-11-14', '0000-00-00 00:00:00', 'Cash', 'Paid'),
(2, 2, 12, '#ndeng', 'Ndegu', '5.00', 'Kg', '95.00', '475.00', '25.00', '2021-11-14', '0000-00-00 00:00:00', 'Cash', 'Paid'),
(3, 2, 20, '#sugar', 'Mumias Sugar', '3.00', 'Kg', '120.00', '360.00', '120.00', '2021-11-14', '0000-00-00 00:00:00', 'Cash', 'Paid'),
(4, 2, 17, '#edent', 'Eden Tea', '2.00', 'g', '20.00', '40.00', '4.00', '2021-11-14', '0000-00-00 00:00:00', 'Cash', 'Paid'),
(5, 3, 21, '#meneg', 'Menengai Bar soap', '2.00', 'n/a', '24.00', '48.00', '2.00', '2021-11-14', '0000-00-00 00:00:00', 'Cash', 'Paid'),
(6, 4, 2, '#beans', 'Beans', '1.00', 'Kg', '90.00', '90.00', '20.00', '2021-11-14', '0000-00-00 00:00:00', 'Cash', 'Paid'),
(7, 5, 18, '#aeria', 'Aerial Washing Powder', '1.00', 'g', '25.00', '25.00', '5.00', '2021-11-14', '0000-00-00 00:00:00', 'Cash', 'Paid'),
(8, 6, 17, '#edent', 'Eden Tea', '1.00', 'g', '20.00', '20.00', '2.00', '2021-11-14', '0000-00-00 00:00:00', 'Cash', 'Paid'),
(9, 7, 19, '#cooki', 'Cooking Fat', '2.00', 'Kg', '40.00', '80.00', '20.00', '2021-11-14', '0000-00-00 00:00:00', 'Cash', 'Paid');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product`
--

CREATE TABLE `tbl_product` (
  `product_id` int(11) NOT NULL,
  `product_code` char(6) NOT NULL,
  `product_name` varchar(200) NOT NULL,
  `product_category` varchar(200) NOT NULL,
  `purchase_price` decimal(8,2) NOT NULL,
  `sell_price` decimal(8,2) NOT NULL,
  `product_profit` decimal(8,2) NOT NULL,
  `stock` decimal(8,2) NOT NULL,
  `min_stock` decimal(8,2) NOT NULL,
  `product_unit` varchar(200) NOT NULL,
  `description` varchar(200) NOT NULL,
  `img` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_product`
--

INSERT INTO `tbl_product` (`product_id`, `product_code`, `product_name`, `product_category`, `purchase_price`, `sell_price`, `product_profit`, `stock`, `min_stock`, `product_unit`, `description`, `img`) VALUES
(2, '#beans', 'Beans', 'Cereals', '75.00', '90.00', '20.00', '13.75', '5.50', 'Kg', '', '61582325b31df.jfif'),
(12, '#ndeng', 'Ndegu', 'Cereals', '90.00', '95.00', '5.00', '12.00', '7.00', 'Kg', 'Green Grams', '61582379a21fd.jpg'),
(15, '#wheat', 'Wheat Floor', 'Cereals', '60.00', '65.00', '5.00', '18.00', '5.00', 'Kg', '', '615823e384c3f.jfif'),
(17, '#edent', 'Eden Tea', 'Cereals', '18.00', '20.00', '2.00', '8.00', '5.00', 'g', '', '615824b786b86.jfif'),
(18, '#aeria', 'Aerial Washing Powder', 'Cereals', '20.00', '25.00', '5.00', '9.25', '10.00', 'g', '', '6158244d16ec0.jpg'),
(19, '#cooki', 'Cooking Fat', 'Retail', '30.00', '40.00', '10.00', '11.00', '10.00', 'Kg', '', '61582827447c0.jfif'),
(20, '#sugar', 'Mumias Sugar', 'Cereals', '80.00', '120.00', '40.00', '7.00', '5.00', 'Kg', 'White Mumias Sugar', '61582853d28ec.jpg'),
(21, '#meneg', 'Menengai Bar soap', 'Retail', '23.00', '24.00', '1.00', '16.00', '5.00', 'n/a', '', '6158289363ac9.jpg'),
(22, '#rice', 'Rice', 'Cereals', '80.00', '100.00', '20.00', '3.50', '5.00', 'Kg', '', '615828dd5db19.jfif');

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
-- Indexes for table `tbl_credit_limit`
--
ALTER TABLE `tbl_credit_limit`
  ADD PRIMARY KEY (`credit_id`);

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk1` (`invoice_id`);

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
  MODIFY `cat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_credit_limit`
--
ALTER TABLE `tbl_credit_limit`
  MODIFY `credit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_invoice`
--
ALTER TABLE `tbl_invoice`
  MODIFY `invoice_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_invoice_detail`
--
ALTER TABLE `tbl_invoice_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tbl_product`
--
ALTER TABLE `tbl_product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `tbl_repayments`
--
ALTER TABLE `tbl_repayments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_unit`
--
ALTER TABLE `tbl_unit`
  MODIFY `unit_id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_repayments`
--
ALTER TABLE `tbl_repayments`
  ADD CONSTRAINT `fk1` FOREIGN KEY (`invoice_id`) REFERENCES `tbl_invoice` (`invoice_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
