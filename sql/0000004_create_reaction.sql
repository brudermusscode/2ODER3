START TRANSACTION;

CREATE TABLE `reactions` (
  `id` int NOT NULL,
  `visitor_id` int NOT NULL,
  `type` VARCHAR(24) NOT NULL,
  `emote` VARCHAR(32) NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

ALTER TABLE `reactions`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `reactions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

COMMIT;