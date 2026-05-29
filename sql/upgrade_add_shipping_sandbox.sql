-- Add UPS/FedEx sandbox toggles (0=live, 1=sandbox).
--
-- IMPORTANT:
-- - Replace `PREFIX_` with your configured PRFX (often it's empty).
-- - Run this in your MySQL/MariaDB client.
--
ALTER TABLE `PREFIX_SETUP`
  ADD COLUMN `UPS_SANDBOX` int(1) NOT NULL DEFAULT '0',
  ADD COLUMN `FEDEX_SANDBOX` int(1) NOT NULL DEFAULT '0';
