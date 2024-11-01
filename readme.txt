=== Plugin Name ===
Contributors: scharc
Donate link: https://github.com/scharc/wp-piwik-notifier
Tags: piwik, tracking, cookie, eu-law
Requires at least: 3.0.1
Tested up to: 3.9
Stable tag: 0.3.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WP-Piwik-Notifier shows a Notifier Bar in the frontend. It informs your user that you use Piwik for tracking with the possibility to deactivate the tracking-cookie via iframe 

== Description ==

This Plugin was strongly motivated by the needs for German law. It is demanded that you show a user that you use Piwik and Piwik by itself uses a tracking cookie to track you through the whole website.
Instead of modifing all the themes to show a small bar at the top of every page, i decided to write a small plugin to do the trick for me. 

* Adds Bar with custom Text to Top of every Page
* Button with custom Text to confirm to tracking
* Add a new Page to your site to display the Piwik-Iframe to disable the Piwik-Tracking-Cookie
* Add a Shortcode for the Piwik-Iframe to disable the Tracking-Cookie [piwik-iframe]
* Custom CSS File for your own style to match your current theme

Development of the Plugin is managed through [GitHub](https://github.com/scharc/wp-piwik-notifier "GitHub RePo") Please help me make it a better plugin and send me pull-requests

Have a cool custom style for the Notifier Bar? Send me a pull request via GitHub or post it here in the support section

== Installation ==

1. Upload the folder `wp-piwik-notifier` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to the Settings of the Piwik Notifier Plugin and add your custom messages and the URL to your piwik instance
4. Write your own CSS File to match your theme and save it to the /wp-content/themes/YOUR-CURRENT-THEME/wp-piwik-notifier/main.css` You can select it in the Settings Menu
5. Have fun

== Changelog ==

0.1 Init Commit
0.2 Bug Fixes
0.2.1 l18n-bug fixes
0.2.2 readme fixes
0.2.3 german translation added