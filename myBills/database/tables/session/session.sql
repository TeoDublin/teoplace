CREATE TABLE `session` (
  `id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `insert_at` datetime NOT NULL,
  `expiry` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `session`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `session`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;