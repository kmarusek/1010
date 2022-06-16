=== WP Client Reports Pro ===
Contributors: thejester12
Donate link: https://switchwp.com/wp-client-reports/
Tags:  reports, client reports, analytics, maintenance, updates, plugin updates, theme updates
Requires at least: 5.3.0
Tested up to: 5.8
Stable tag: 1.0.11
Requires PHP: 5.6.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Track your WordPress Core Updates, Plugin Updates, and Theme Updates. View update statistics and send an HTML email report to your clients.

== Description ==

The perfect plugin for agencies and site maintainers who regularly update WordPress and it's themes and plugins on a weekly, monthly, or quarterly basis. The plugin tracks what daily updates have happened and records them. 

You can use the beautiful reporting page to show what updates have happened within a certain amount of time such as last month, this month, or last 30 days. 

Send an HTML email with update statistics whenever you complete updates to show the value of your work to your client or other site stakeholders.


== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/wp-client-reports-pro` directory, or install the zipped plugin through the WordPress plugins screen directly.
2. Ensure that the free plugin WP Client Reports is installed from the WordPress plugin directory.
3. Activate the WP Client Reports Pro plugin through the 'Plugins' screen in WordPress.
4. Use the Settings->WP Client Reports screen to configure the default settings.
5. Use the Dashboard->Reports screen to view update stistics.


== Changelog ==

= 1.0.11 =
* Fixed syntax issue with a stray comma

= 1.0.10 =
* Add Site Maintenance Notes feature: Add dated notes with simple icons to keep a record of work done or actions taken and include them in reports
* Add support for Google Analytics V4 Properties
* Fix a formatting error in Stripe
* Change how UpdraftPlus backup sizes are calculated

= 1.0.9 =
* Fix issues related to Automatic Sending of Site Reports
* Better error messages for when a Google Analytics account doesn't have any properties
* Replace Uptime Robot stat "Days Without Issue" in favor of "Downtime Minutes" because it makes more sense given customizable time periods

= 1.0.8 =
* New Service: GiveWP
* New Service: Stripe Payments
* Fix issues with meta box headers in newer versions of WordPress

= 1.0.7 =
* New Feature: Loading spinners while reports are loading
* New Service: WP Engine Backups
* Fix various issues with automatic report sending while loaded through CRON job
* Fix issues with timezones using UTC offsets
* Fix some untranslatable strings
* Fix various small issues with Caldera Forms and Uptime Robot

= 1.0.6 =
* New Feature: Weekly or Monthly Automatic Report Sending
* Add Caldera Forms statistics service
* Fix formatting issues with Uptime Robot reports

= 1.0.5 =
* Add WP Forms statistics service
* Add Fomidable Forms statistics service
* Add Contact Form 7 statistics service 
* Add BackupBuddy backup statistics service
* Add SearchWP search statistics service
* Add Easy Digital Downloads earnings statistics service

= 1.0.4 =
* Add UpdraftPlus backup statistics service
* Add BackWPup backup statistics service
* Force timestamps to represent whole days

= 1.0.3 =
* Fix issue with form view tracking

= 1.0.2 =
* Fix issues with PHP versions less than 7

= 1.0.1 =
* Refactor to only include services when they are enabled
* Use transients when saving data in some situations to speed up options pages

= 1.0.0 =
* Initial Version


== Upgrade Notice ==

= 1.0.0 =
Initial Version
