START TRANSACTION;

CREATE TABLE `views` (
  `id` int NOT NULL,
  `visitor_id` INT NOT NULL,
  `log_id` INT NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

ALTER TABLE `views`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `views`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

COMMIT;