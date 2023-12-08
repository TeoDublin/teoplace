CREATE TABLE `bills` (
  `id` int(11) NOT NULL,
  `day` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `billsGroup` varchar(255) NOT NULL,
  `cost` double(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `bills` (`id`, `day`, `name`, `billsGroup`, `cost`) VALUES
(1, 1, 'Now tv', 'Apps', 14.99),
(4, 28, 'Machina', 'Machina', 136.45),
(5, 27, 'Netflix', 'Apps', 14.99),
(8, 18, 'Disney', 'Apps', 8.99),
(9, 15, 'Paramount', 'Apps', 7.99),
(10, 13, 'Dazn', 'Apps', 34.99),
(11, 11, 'Prime video', 'Apps', 3.99),
(19, 24, 'Food&Smoke', 'Basics', 400.00),
(26, 25, 'Internet 1', 'Fastweb', 27.95),
(27, 24, 'Rent', 'Basics', 400.00),
(28, 31, 'Internet 2', 'Fastweb', 32.00);

ALTER TABLE `bills`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `bills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;