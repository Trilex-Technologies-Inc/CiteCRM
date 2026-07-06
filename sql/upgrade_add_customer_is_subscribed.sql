-- Add an "is_subscribed" flag to customers.
--
-- IMPORTANT:
-- - Replace `PREFIX_` with your configured PRFX (often it's empty).
-- - Run this in your MySQL/MariaDB client.

ALTER TABLE `PREFIX_TABLE_CUSTOMER`
  ADD COLUMN `CUSTOMER_IS_SUBSCRIBED` tinyint(1) NOT NULL DEFAULT 0;
