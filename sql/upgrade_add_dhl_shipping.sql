-- Add DHL Express (MyDHL API) credential fields.
--
-- IMPORTANT:
-- - Replace `PREFIX_` with your configured PRFX (often it's empty).
-- - Run this in your MySQL/MariaDB client.
--
ALTER TABLE `PREFIX_SETUP`
  ADD COLUMN `DHL_KEY` varchar(255) NOT NULL DEFAULT '',
  ADD COLUMN `DHL_SECRET` varchar(255) NOT NULL DEFAULT '',
  ADD COLUMN `DHL_ACCOUNT` varchar(32) NOT NULL DEFAULT '';
