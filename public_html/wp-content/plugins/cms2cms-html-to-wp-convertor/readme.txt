﻿=== CMS2CMS: HTML to WordPress Converter  ===
Contributors: cms2cms
Tags: html to wordpress, html to wordpress migration, convert html to wordpress, migrate html to wordpress, pages, images, plugin, importer
Requires at least: 3.0.0
Tested up to: 4.4
Stable tag: 3.6.2
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin will help to convert your static HTML website to WordPress in a matter of a few minutes.

== Description ==

A simple and trouble-free way to convert HTML website data to WordPress. All you need to do is just to register, set up the migration and go through the Demo. 
Once the Free Demo has been completed, you will be redirected to CMS2CMS website to finalize the migration.

*Note. It supports content migration only, the theme won’t be converted into WordPress theme.*

= Currently Supported Entities that can be migrated from HTML to WordPress: =

* *pages*
* *content images*
* *internal links*
* *tag and meta tags*

= Additional Migration Options provided by CMS2CMS service are: =

- clear WordPress data before migration
- 301 redirects from old URLs to new URLs
- contact forms
- keep HTML formatting for posts and pages

= Features of Automated Migration with CMS2CMS: =

* Simple - no need for coding skills to complete it.
* Saves time - it takes 15 minutes on average, depending on the size of your site.
* Free Demo migration - move up to 10 pages to see it in action.
* 24/7 live chat support - no need to search for support on forums.

This plugin installs the connection bridge on your WordPress, which is needed for data interaction between the HTML and WordPress websites.

For more details on automated conversion from HTML to WordPress, see here:
http://www.cms2cms.com/supported-cms/html-to-wordpress-migration/?utm_source=HTML&utm_medium=Link&utm_campaign=WordPress_plugins

*Website design (styles, themes) isn’t migrated.*

= Steps to Take before Migration =

1. Install WordPress and make sure that Html and WordPress websites are available online.

2. Have Html website FTP access details at hand (host, username, password) - you use them to install connection bridge on Html website.

3. Set up custom URL structure for WordPress (it should be set before the migration for internal links to work correctly, if you change them after migration, site navigation will be broken).

4. Install WordPress plugins supported by CMS2CMS (if you want to migrate metadata or image galleries).

= Video =
[youtube https://www.youtube.com/watch?v=TIlq-zgJwGo]

= Our Support Team is available via phone, Live Chat and Email (Technical support) during the following hours: =

- Phone support – 24 hours a day, Monday – Friday
- Live Chat support – 24 hours a day, Monday – Friday
- Technical support – from 11 am to 7 pm GMT +2, Monday – Friday

== Frequently Asked Questions ==

= Your website is unreachable =
<p>If your website cannot be reached, pay attention to the following points:
Make sure your site is available online at the moment.
It’s possible that your firewall blocks certain IP requests. Contact your system administrator or hosting provider support for details about this issue.</p>

= Your server responds with 401 Unauthorized =
<p>If you get this error, try the following solutions:
1. Ensure that access to your site content is not blocked by HTTP Basic Authentication (http://en.wikipedia.org/wiki/Basic_access_authentication). HTTP Basic Authentication is a protection method which requests additional login and password to access webpage or other resource.
2. Make sure that your website content is available on the Internet during the Migration process.</p>

= Your server responds with 403 Forbidden =
This error means that access to certain files or folders is limited. Find below the possible solutions:
1. Your firewall may be causing this by blocking access to the server for our IP addresses. Please, contact your hosting provider and ask them to add the following IPs to the white-list:
<ul>
<li>5.58.76.130</li>
<li>204.62.12.42</li>
<li>204.62.12.24</li>
<li>88.214.254.75</li>
<li>93.77.238.130</li>
<li>Port 80</li>
</ul>

<p>This is done to enable data exchange between your websites. After the migration is complete, you’ll be able to remove our IPs from the white list.
Check the access permissions. For ‘cms2cms’ folder specify the file permissions 755. For files in the ‘cms2cms’ folder specify permissions 644.
Find out whether there are access restrictions for bridge file. Usually, restrictions are specified in .htaccess file. Contact your system administrator for details.</p>

= Your server responds with 413 Request Entity Too Large =
<p>It indicates that the request is too large for your server. These are possible solutions:
Increase values for the following parameters: ‘memory_limit’ and ‘post_max_size’ in PHP configuration.
If the module suhosin for PHP is installed on the server, increase the parameter ‘suhosin.post.max_value_length’. Usually, the value of 32 Mb is enough.</p>

= Your server responds with 500 Server Error =
<p>Incorrect permissions for bridge folder are the most common reason of this internal server error.
<li>for \'cms2cms\' folder, specify the file permissions 755</li>
<li>for \'index.php\', \'bridge.php\' and \'key.php\' files in \'cms2cms\' folder, specify the permissions 644</li>
If it won’t help, contact your system administrator who can provide you with server logs access for further error detection. You can also request technical assistance from your hosting provider.</p>

= Failed to connect to host / Operation timed out / Nothing was returned from the server / The connection to your server has timed out =
<p>Each of these errors indicates that your website cannot be reached online. Solutions are as follows:
Make sure your site is available online at the moment.
It’s possible that your firewall blocks certain IP requests. Contact your system administrator or hosting provider support for details about this issue.</p>

= POST Method Not Allowed =
This is a server error. Contact your system administrator or your hosting provider support to have POST method allowed for your server.

= Site is connected already by another account =
Each CMS2CMS user has the unique key which can be found in the Bridge file. So, the Bridge of user A is different from the Bridge of user B. If you have downloaded a bridge using one account, but you try to migrate with this bridge under another account, you get this error.

To fix it, you should either download the bridge again under the account you are going to use for migration or login to the account you used previously to download the Bridge file.

= Invalid response received =
Сontact us at support@cms2cms.com.

= An error occurred when trying to connect to your site =
Сontact us at support@cms2cms.com.

= An unknown error occurred =
Сontact us at support@cms2cms.com.

== Screenshots ==

1. /assets/screenshot-1.png
2. /assets/screenshot-2.png
3. /assets/screenshot-3.png

== Changelog ==

= 2.0.3 =
* New plugin with wizard inside

= 2.0.2 =
* Bug fixes

= 2.0.1 =
* Improved Connection Bridge

= 1.0.3 =
* Bug fixes

= 1.0.2 =
* Bug fixes

= 1.0.1 =
* Added German Language
* Minor fixes of html

= 1.0 =
* Initial commit
