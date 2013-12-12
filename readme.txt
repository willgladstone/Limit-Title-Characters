=== Limit a post title to X characters ===
Contributors: jpmurray
Donate link: http://pasunecompagnie.com/limit-a-post-title-to-x-characters/
Tags: post title, title, count, counter, twitter
Requires at least: 3.0
Tested up to: 3.3.1
Stable tag: 1.3.1

== Description ==

Limit posts title length as defined in options. Shows the current character count and stops the publication process if the length goes over.

Usefull to limit title characters dues to theme restrictions, or Twitter automatic posting, for examples.

Included in the plugin folders are necessary files to translate this plugin to you own language. If you want to send me a translation, please contact me via the URL below.

I can provide limited support <a href="http://pasunecompagnie.com/limit-a-post-title-to-x-characters/">here</a>. You can also submit bug reports to the same address.

== Credits ==

Thanks to...

* [Paul Solomon](http://InsiteWebsite.com/) *for tweaking the CSS and correcting some of my typos and spelling mistakes; finding that the scripts where loading in the front end too.*
* [marklaramee](http://wordpress.org/support/profile/marklaramee) *for the bug hunt in the character count validation.*
* [Gabriel Serafini](http://wordpress.org/support/profile/gserafini) *for debugging character count validation in post edition.*

== Installation ==

Install this plugin by going to Plugins >> Add New >> and type this plugin's name, from your own Wordpress installation.

**Some file names changed in version 1.2. People upgrading from version 1.1.1 and earlier *might* have to do a clean install.**


== Changelog ==

= 1.3.1 =
* Bug fix: Custom javascript where loading in the front end pages as well, and it was not needed.
* Bug fix: Character count would bug and think a title was under allowed character cound while editing an already submitted post.

= 1.3 =
* Changed everything in the source code that where referencing to french word so it's easier for the community to understand.
* Fixed a bug where counter would turn falsly red when opening an already saved post / draft.
* Link to option page in the plugin menu is fixed.
* Character verification checks if number of character is under or equal to the limit, and not only under it.
* Added support for l18n with english as default language.
* Translation added: fr_FR.

= 1.2 =
* Added an option page for the plugin.
* Administrators can define the limit of character the plugin will check in the option page.
* Administrators can define if they are subject to the plugin limitation or not.
* Changed plugin internal naming convention.
* Changed some of the plugin files' name. **Users upgrading from version 1.1.1 and earlier can have trouble due to name changes. They should do a clean install instead**
* Removed unnecessary files rendered useless.
* Changed style of the counter box.
* Corrected some typos and spelling mistakes.

= 1.1.1 =
* File path to some included files corrected.
* Corrected typos in the alerts.

= 1.1 =
* Correcting codes that could make the plugin break on certain situations.

= 1.0 =
* Release. Everything seems to work !