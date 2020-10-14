# Covid 19 Local Dashboard (UK)

An application to show a summary of Covid-19 cases in UK
(Currently England council areas only).

This will show a summary of the rate and cases for 7 days (from most recent 10).
It will also show the cases for the last 10 days.

[Live site](https://covid19local.newmediathinking.com)

## Database setup

Use the following SQL to set up a local database.

````sql
-- Create syntax for TABLE 'areas'
CREATE TABLE `areas` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=475 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create syntax for TABLE 'cases'
CREATE TABLE `cases` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `area_id` int(11) DEFAULT NULL,
  `daily` int(11) DEFAULT NULL,
  `cumlitive` int(11) DEFAULT NULL,
  `rate` decimal(8,2) DEFAULT NULL,
  `date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `area_id` (`area_id`),
  KEY `date` (`date`)
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
````
# Import data

To import the CSV from the Coronavirus dashboard, run the following.
`php public/index.php cases-csv-fetch`

Or visit SITE_URL/cases-csv-fetch in the browser (You may need to increase
memory limit and max execution time).

If deploying its recommended to set up a cron job each day at 5pm to import 
the new data.
