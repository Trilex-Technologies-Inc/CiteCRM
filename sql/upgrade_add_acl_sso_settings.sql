-- Add ACL entry for the SSO Settings page
--
-- IMPORTANT:
-- - Replace `PREFIX_` with your configured PRFX (often it's empty).
-- - Run this in your MySQL/MariaDB client that hosts the CiteCRM database.
--
-- This will insert a DB-driven ACL row for the admin SSO settings page
-- and grant access to Admin only by default.

INSERT INTO `PREFIX_ACL` (`ACL_ID`, `page`, `Manager`, `Supervisor`, `Sale person`, `Admin`) VALUES
(70, 'auth:sso_settings', 0, 0, 0, 1);

-- If `ACL_ID` 70 is already used, change the ID to an unused integer.
