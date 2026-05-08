START TRANSACTION;

CREATE TABLE `comments` (
  `id` int NOT NULL,
  `visitor_id` int NOT NULL,
  `log_id` int NOT NULL,
  `comment` TEXT(3000) NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `comments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

COMMIT;