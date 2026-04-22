-- If you get: "Data too long for column 'CUSTOMER_ZIP'"
-- Increase the column width to support ZIP+4 and non-US postal codes.
--
-- IMPORTANT:
-- - Replace `PREFIX_` with your configured PRFX (often it's empty).
-- - Run this in your MySQL/MariaDB client.

ALTER TABLE `PREFIX_TABLE_CUSTOMER`
  MODIFY COLUMN `CUSTOMER_ZIP` varchar(20) DEFAULT NULL;

