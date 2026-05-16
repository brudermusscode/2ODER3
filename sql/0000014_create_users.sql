START TRANSACTION;

CREATE TABLE `users` (
  `id` int NOT NULL,
  `uuid` VARCHAR(255) NOT NULL,
  `nickname` VARCHAR(32) NOT NULL,
  `email` VARCHAR(62) NULL,
  `email_verified` BOOLEAN NOT NULL DEFAULT 0,
  `color` VARCHAR(32) NOT NULL DEFAULT "#3ff0be",
  `password` VARCHAR(255) NOT NULL,
  `agent` VARCHAR(128) NULL,
  `ip` VARCHAR(128) NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

COMMIT;
