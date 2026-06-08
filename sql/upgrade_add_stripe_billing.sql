-- Add Stripe billing configuration fields + billing option.
--
-- IMPORTANT:
-- - Replace `PREFIX_` with your configured PRFX (often it's empty).
-- - Run this in your MySQL/MariaDB client.
--
-- 1) Add columns to SETUP
ALTER TABLE `PREFIX_SETUP`
  ADD COLUMN `STRIPE_PUBLISHABLE_KEY` varchar(255) NOT NULL DEFAULT '' AFTER `PP_SANDBOX`,
  ADD COLUMN `STRIPE_SECRET_KEY` varchar(255) NOT NULL DEFAULT '' AFTER `STRIPE_PUBLISHABLE_KEY`,
  ADD COLUMN `STRIPE_TEST_MODE` int(1) NOT NULL DEFAULT '1' AFTER `STRIPE_SECRET_KEY`;
--
-- 2) Add Stripe billing option (idempotent)
INSERT INTO `PREFIX_CONFIG_BILLING_OPTIONS` (`BILLING_OPTION`, `BILLING_NAME`, `ACTIVE`)
SELECT 'stripe_billing', 'Stripe', 0
WHERE NOT EXISTS (
  SELECT 1 FROM `PREFIX_CONFIG_BILLING_OPTIONS` WHERE `BILLING_OPTION` = 'stripe_billing'
);
