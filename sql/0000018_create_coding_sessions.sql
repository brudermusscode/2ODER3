START TRANSACTION;

CREATE TABLE `coding_sessions` (
  `id` int NOT NULL,
  `project_id` int NOT NULL,
  `title` TEXT NULL,
  `finished_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

ALTER TABLE `coding_sessions`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `coding_sessions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

COMMIT;
