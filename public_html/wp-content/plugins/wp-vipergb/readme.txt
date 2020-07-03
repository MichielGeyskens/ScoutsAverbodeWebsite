=== Plugin Name ===
Contributors: Justin_K
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=BUMTK5NRRG8UN
Tags: guestbook, vipergb, viper guestbook
Requires at least: 2.5
Tested up to: 4.5
Stable tag: 1.4.2

Create a stylish and user-friendly Guestbook for your Wordpress blog.  Designed to replicate the appearance and behavior of Viper Guestbook.


== Description ==

WP-ViperGB is a Wordpress plugin designed to replicate the appearance and behavior of the discontinued [Viper Guestbook](http://www.vipergb.de.vu/) project. It makes it easy to add a stylish and user-friendly guestbook to your blog.

Features:

* Create user-friendly guestbooks without writing a single line of code.
* Lives in a standard Wordpress page and uses comments for entries, so moderation and antispam functionality works as normal.
* Two-View layout provides one view for submitting entries and another for reading them.
* Automatic paging of entries to customizable length.
* Show icons for country, browser, and OS in visitor signatures.
* Admin-panel stylesheet selector allows easy skinning to suit your theme.
* No bloat: Uses existing Wordpress faculties so no custom database tables are required.
* Simple PHP template function allows programmers to manually embed standalone guestbooks in any template they wish.

For a Demo, see the [plugin's homepage](http://www.justin-klein.com/projects/wp-vipergb#demo).


== Installation ==

1. Download the most recent version of the plugin from [here](http://wordpress.org/extend/plugins/wp-vipergb/), unzip it, and upload the extracted files to your `/wp-content/plugins` directory.

2. Login to your Wordpress admin panel and activate the plugin.

3. Create a new Page (i.e. "Guestbook").

4. Navigate to Settings -> WP-ViperGB.

5. Use the provided dropdown to select the Page you created above.

6. Click "Save," and you're done! You can now enjoy your new guestbook.

Customization options are discussed on the [plugin's website](http://www.justin-klein.com/projects/wp-vipergb#customization).

== Frequently Asked Questions ==

[FAQ](http://www.justin-klein.com/projects/wp-vipergb#faq)


== Screenshots ==

[Demo Guestbook](http://www.justin-klein.com/projects/wp-vipergb#demo)


== Changelog ==
= 1.4.2 (2016-04-23) =
* Fix an erroneous message if saving the guestbook page as "<None>"
* Verified with WP 4.5

= 1.4.1 (2016-01-09) =
* Language Pack fix: Add a Domain Path
* Language Pack fix: Remove markup from a few translateable strings

= 1.4.0 (2016-01-09) =
* Add support for Wordpress Language Packs
* Add filter vgb_author_name, so you can customize how the author names are displayed (i.e. wrap them in a link, etc)
* Increase copyright year

= 1.3.18 (2015-12-02) =
* CSS fixes for TwentySixteen
* Tested with WP 4.4

= 1.3.17 (2015-08-08) =
* WP 4.3 defaults to 'comments off' for new pages.  WP-ViperGB will now detect this, & automatically enable comments on the page selected to be used as a guestbook (as required for it to function).

= 1.3.16 (2015-06-05) =
* XSS security fix ([this](https://make.wordpress.org/plugins/2015/04/20/fixing-add_query_arg-and-remove_query_arg-usage/))

= 1.3.15 (2015-04-28) =
* Add Arabic translation
* Fix a deprecated function call when processing embedded images
* CSS fixes for compatibility with twentyfourteen & twentyfifteen
* Tested with WP 4.2.1

= 1.3.14 (2015-02-06) =
* Trim leftover code from the "upload images" feature (removed long ago)
* Fix some harmless admin panel notices when saving options if WP_DEBUG is defined
* Fix some harmless frontend notices shown when the "Digg Pagination" option is enabled & WP_DEBUG is defined

= 1.3.13 (2014-12-22) =
* Updated Norwegian translation
* Add .gbPagination css class to avoid confusion with "pagination" (used in twentyfourteen & twentyfifteen)
* Add a note to admin page about using pretty permalinks with digg-style pagination
* CSS fixes for twentyfifteen
* Set language of default .po file
* Tested with WP4.1

= 1.3.12 (2014-12-12) =
* Added Norwegian translation (Thanks Hermod Svingerud)

= 1.3.11 (2014-11-27) =
* Fix potential vulneratility with some unescaped options in the admin panel
* Tested with WP 4.0.1

= 1.3.10 (2014-04-17) =
* Tested with WP 3.9
* Change email to link in the copyright notice

= 1.3.9 (2013-12-12) =
* Tested with WP 3.8

= 1.3.8 (2013-10-25) =
* Tested with WP 3.7

= 1.3.7 (2013-06-10) =
* Updated Russian translations

= 1.3.6 (2013-05-07) =
* Remove auth()

= 1.3.5 (2012-12-26) =
* Add "Prev" & "Next" strings in digg-style pagination to .po for localization
* Add "required" strings next to "Name" and "Email"
* Update the main .po file with the above & some other new items
* Convert some HTML5-invalid attributes (i.e. cellspacing, valign) to CSS 
* Updated Hungarian translations

= 1.3.4 (2012-12-19) =
* Add Spanish translation (Thanks William Galeano @ http://ingenero.com.co)

= 1.3.3 (2012-11-26) =
* Fix a few more WP_DEBUG warnings I missed in 1.3.2 

= 1.3.2  (2012-11-26) =
* Fix a couple harmless warnings that appear if WP_DEBUG is defined
* Update the 'Tested Up To' version

= 1.3.1 (2012-11-03) =
* Update Russian translations

= 1.3.0 (2012-10-02) =
* New option: Digg-style pagination (thanks Sascha Nebel!)

= 1.2.1 (2012-09-28) =
* New option: only allow registered blog users to sign the guestbook (to discourage spammers)
* The 'guestbook page' dropdown in the admin panel now lists both published and private pages

= 1.2.0 (2012-08-16) =
* Remove the ability to embed images in user signatures.  Due to a security exploit discovered in Easy-Comment-Uploads, the script upon which this feature was built, it had to be removed to satisfy Wordpress' safety guidelines and keep this plugin available.

= 1.1.30 (2012-03-25) =
* Add Dutch translation

= 1.1.29 (2012-03-24) =
* Add Hungarian translation
* Update Wordpress compatibility number

= 1.1.28 (2012-01-28) =
* Add Ukrainian translation
* More Swedish translation fixes

= 1.1.27 (2012-01-28) =
* Swedish translation fixes

= 1.1.26 (2012-01-27) =
* Add Swedish translation

= 1.1.25 (2011-12-09) =
* Add Danish translation
* Remove the wp-fb-autoconnect premium button from the "sign" page

= 1.1.24 (2011-12-01) =
* Remove the "subscribe_reloaded_show" action from the "sign" page

= 1.1.23 (2011-11-28) =
* Removed plugin sponsorship messages.  See [Automattic Bullies WordPress Plugin Developers -- Again](http://gregsplugins.com/lib/2011/11/26/automattic-bullies/).
* Update compatibility number

= 1.1.22 (2011-06-20) =
* Updated Turkish translation (now 100% complete)

= 1.1.21 (2011-06-20) =
* Add Turkish translation

= 1.1.20 (2011-06-14) =
* Add Polish translation
* Add Sponsorship message to admin panel
* Update 'Tested Up To' version

= 1.1.19 (2011-04-20) =
* Add French and Czech translations

= 1.1.18 (2011-04-11) =
* Still not working, attempt #2...

= 1.1.17 (2011-04-11) =
* Mark as compatible to 3.1
* Attempt to fix "The plugin does not have a valid header"

= 1.1.16 (2011-01-25) =
* Add German translation

= 1.1.15 (2011-01-13) =
* Updated Russian translation

= 1.1.14 (2011-01-12) =
* Add Italian translation

= 1.1.13 (2011-01-12) =
* Fix parse error

= 1.1.12 (2011-01-11) =
* Add Russian translation

= 1.1.11 (2011-01-07) =
* Even more localization

= 1.1.10 (2011-01-07) =
* Date format is now translatable

= 1.1.9 (2011-01-07) =
* One more untranslated string

= 1.1.8 (2011-01-07) =
* Add a few more untranslated strings

= 1.1.7 (2011-01-07) =
* Oops - more localization fixes

= 1.1.5 (2011-01-07) =
* Fix textdomain for localization

= 1.1.4 (2010-12-24) =
* Add missing UK flag
* Increase "tested up to" version number

= 1.1.3 (2010-12-13) =
* Works with SI CAPTCHA Anti-Spam plugin

= 1.1.2 (2010-10-30) =
* Add return URL to paypal donate button

= 1.1.1 (2010-08-24) =
* Get rid of i18n folder in /easy-comment-uploads (unused)

= 1.1.0 (2010-08-08) =
* Add localization support
* Add flag icon for Serbia

= 1.0.5 (2010-03-16) =
* Use php long tags instead of short tags for server compatability

= 1.0.4 (2010-03-12) =
* New version of easy-comment-uploads; should now work on Windows servers

= 1.0.3 (2010-03-10) =
* More CSS fixes for some themes

= 1.0.2 (2010-03-09) =
* CSS fixes

= 1.0.1 (2010-03-09) =
* Add version number to plugin code
* Small bug fixes & cleanups

= 1.0.0 (2010-03-08) =
* First Release


== Support ==

Please direct all support requests [here](http://www.justin-klein.com/projects/wp-vipergb#feedback)