START TRANSACTION;

CREATE TABLE `user_verifications` (
  `id` int NOT NULL,
  `user_id` INT NOT NULL,
  `email` VARCHAR(62) NOT NULL,
  `token` VARCHAR(62) NOT NULL,
  `code` VARCHAR(4) NOT NULL,
  `ip` VARCHAR(128) NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

ALTER TABLE `user_verifications`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `user_verifications`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

COMMIT;
