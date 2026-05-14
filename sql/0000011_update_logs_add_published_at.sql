START TRANSACTION;

ALTER TABLE `logs`
  MODIFY `project_id` INT NULL DEFAULT NULL,
  ADD `published_at` TIMESTAMP NULL AFTER `views`,
  ADD `thumb_selected` INT NULL AFTER `views`,
  ADD `thumb_count` INT NULL AFTER `views`,
  DROP COLUMN `thumb_name`;

COMMIT;