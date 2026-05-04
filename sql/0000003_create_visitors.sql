START TRANSACTION;

CREATE TABLE `visitors` (
  `id` int NOT NULL,
  `ip` VARCHAR(128) NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

ALTER TABLE `visitors`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `visitors`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

COMMIT;