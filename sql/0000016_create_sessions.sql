START TRANSACTION;

CREATE TABLE `sessions` (
  `id` int NOT NULL,
  `user_id` INT NOT NULL,
  `token` VARCHAR(62) NOT NULL,
  `ip` VARCHAR(128) NULL,
  `agent` VARCHAR(128) NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `sessions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

COMMIT;
