START TRANSACTION;

CREATE TABLE `reports` (
  `id` int NOT NULL,
  `visitor_id` INT NOT NULL,
  `reference_id` INT NOT NULL,
  `type` VARCHAR(24) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `reports`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

COMMIT;