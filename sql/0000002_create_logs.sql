START TRANSACTION;

CREATE TABLE `logs` (
  `id` int NOT NULL,
  `project_id` int NOT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `thumb_name` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

COMMIT;