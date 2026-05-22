START TRANSACTION;

ALTER TABLE `views`
  RENAME COLUMN visitor_id TO client_id,
  ADD COLUMN client_type VARCHAR(32) NOT NULL DEFAULT 'Bruder\\Model\\Visitor' AFTER client_id;

ALTER TABLE `comments`
  RENAME COLUMN `visitor_id` TO `client_id`,
  ADD COLUMN `client_type` VARCHAR(32) NOT NULL DEFAULT 'Bruder\\Model\\Visitor' AFTER client_id;

ALTER TABLE `reactions`
  RENAME COLUMN `visitor_id` TO `client_id`,
  ADD COLUMN `client_type` VARCHAR(32) NOT NULL DEFAULT 'Bruder\\Model\\Visitor' AFTER client_id;

ALTER TABLE `reports`
  RENAME COLUMN `visitor_id` TO `user_id`,
  MODIFY COLUMN `type` VARCHAR(32) NOT NULL;

ALTER TABLE `reports`
  RENAME COLUMN `type` TO `reference_type`;

COMMIT;
