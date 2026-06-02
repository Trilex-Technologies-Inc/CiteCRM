-- IMPORTANT:
-- - Replace `PREFIX_` with your configured PRFX (often it's empty).
-- - Run this in your MySQL/MariaDB client.

ALTER TABLE `PREFIX_TABLE_CUSTOMER`
  ADD COLUMN `CUSTOMER_COUNTRY` varchar(3) default NULL

