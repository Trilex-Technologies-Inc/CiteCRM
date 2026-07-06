-- Create table for email open/click tracking
-- IMPORTANT: replace `PREFIX_` with your configured PRFX (often it's empty)

CREATE TABLE IF NOT EXISTS `PREFIX_EMAIL_TRACKING` (
  `TRACK_ID` int(11) NOT NULL AUTO_INCREMENT,
  `ACCOUNT_ID` int(11) NOT NULL DEFAULT 0,
  `EMAIL_ID` varchar(100) NOT NULL DEFAULT '',
  `RECIPIENT` varchar(255) NOT NULL DEFAULT '',
  `EVENT` varchar(20) NOT NULL DEFAULT '', -- 'open' | 'click'
  `LINK` varchar(2000) NOT NULL DEFAULT '',
  `IP` varchar(45) NOT NULL DEFAULT '',
  `USER_AGENT` varchar(1024) NOT NULL DEFAULT '',
  `REFERRER` varchar(2000) NOT NULL DEFAULT '',
  `META` text,
  `CREATED_AT` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`TRACK_ID`),
  KEY `EMAIL_ID` (`EMAIL_ID`),
  KEY `RECIPIENT` (`RECIPIENT`),
  KEY `EVENT` (`EVENT`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
