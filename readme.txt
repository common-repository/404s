=== 404s  ===
Contributors: Tomas
Author URI: Tomas
Donate link: https://paypal.me/sunpayment
Tags:404, broken link, page not found error, redirect, 404 redirect, 404 to 301, SEO, custom 404 page, Webmaster Tools, export 404s
Requires at least: 3.2
Tested up to: 6.4.2
Stable tag: 3.5.9
License: GPLv3 or later

fix all kinds of 404s, fix broken link & images automatically,log each 404,redirect each broken link to specific URL,404 mail alert,export 404s,redirect 404 to home page or any URL using 301, stop 404 Page Not Found happen again

== Description ==

wordpress 404s plugin will fix broken links and broken images for you, this is all in one solution for 404 page not found errors. :)    

👉 <a href="https://tooltips.org/product/wordpress-404s-plugin/" target="_blank">Free Download</a> | <a href="https://tooltips.org/wordpress-tooltip-plugin/wordpress-404s-plugin/" target="_blank">404s Document</a> | <a href="https://tooltips.org/contact-us/" target="_blank">404s Support</a>| <a href="https://tooltips.org/forums" target="_blank">Support Forums</a>

<h4>Features of WordPress 404s Plugin:</h4>
Automatically log each 404 pages, referrers URL, IP, browser..., opt to mail 404s alert to admin, create post for each 404 url manually, redirect 404 to home page or any url using 301..., help you fix broken links and stop 404 Page Not Found happen again. 

> #### 404s Features:
> * __404 Logs__: help you fix broken links, in settings panel, you can view logs for each broken links, include broken URL, referrers URL, User IP, User Agent, Browser, Date... and so on. Wordpress 404s plugin will not trace bots, spiders, crawlers, for example google bot or bing bot...etc 
> * __Fix broken images automatically__: broken image is not good, you can opt to use a existed specific image to replace broken images in your wordpress.   
> * __Fix broken links__:you can create a real post for each broken links, for example, you can create a post your.com/best-apple-iphone for the broken link your.com/apples, and create another post your.com/buy-iphone for broken link your.com/buy. In backend, you can edit / delete / manage / customized 404s posts, also you will find 404 menu item "Add New", in which you can create new post manually and assign this post with a 404 url via "what is the 404 URL" metabox
> * __Options to automatically redirect 404 page to wordpress home page__: 404 users is vauleable too, in "404 Global Settings" panel, you can set up to redirect 404 error page to home page.  
> * __Options to automatically redirect 404 pages to any existed pages__: do not waste user traffic, redirect 404 pages your existed URL will keep 404 users stay on your site, in "404 Global Settings" panel, you can set up to redirect 404 error page to specific posts or not, by default, we will use wordpress default 404.php template as 404 page, but in back end, you can enter any URLs as 404 page, 404 users will be redirected to your specific URL automatically, you can enter any URL in the filed, it can be your landing page, shop page, login page, category.... and url, even URL of another site!
> * __Options to automatically send 404 error email notification__: 404 bad links is bad for SEO rank of your site, 404s mail notification will help you fix 404 page errors asap,  in "404 Global Settings" panel, you can opt to send 404 URL alert to webmaster's mail box, by default, this function is disabled, but if you enabled 404 not found error mail notification function,  wordpress 404s plugin will send 404 alert to admin email when it happen, also you can change the default admin email as any email address
> * __Options to automatically redirect 404 pages to your pages using 301 or 302__: using 301 redirect will improve your SEO rank, because search engine will index your 301 links, I recommend you enable this option to use 301 redirect. In "404 Global Settings" panel, you can set up to redirect 404 users with 301 moved permanently status code or 302 status code, by default wordpress 404s plugin will follow wordpress default function to use 302, you can opt to use 301 redirect 404 ereor not found pages.
> * __Options to delete 404 logs__: by default, wordpress 404s plugin will store 404s logs in mysql database,  in "404 Global Settings" panel, you can one click to delete all 404 logs, also we are developed a new version to allow admin export 404 logs
> * __Options to stop add new 404 logs in database__: by default, wordpress 404s plugin will trace all 404 page not found error URLs and insert these 404 error logs into database, you can stop adding 404 error logs in mysql database
> * __404 logs paginate navigation__: in 404s menu item, you can view all 404 logs in 404s lists, we have added paginate navigation for help admin check 404 pages easier, and avoid load too many records from mysql database in one time
> * __Export 404s records to CSV__: you can export all 404 pages with user informations to CSV file, CSV file can be used in excel, you can use excel to sort & analyze 404s error pages and 404 users, in "404 Global Settings" panel, just click  "Export 404s" menu item to export 404s records into CSV file.
> * more...
---

*Please note, if you set up "redirect 404 error page to specific posts" option and "redirect 404 error page to home page" option at the same time, the priority of "redirect 404 error page to specific posts" option is higher than "redirect 404 error page to home page" option*

  
More amazing features are being developed and any feature request is welcome.

<h4>My Other Plugins You Might Also Like:</h4>
<li><a href='https://tooltips.org/features-of-wordpress-tooltips-plugin/' target='blank'>WordPress Tooltips Pro</a></li>
<li><a href='https://wordpress.org/plugins/wordpress-tooltips/' target='blank'>WordPress Tooltips Free</a></li>
<li><a href='https://wordpress.org/plugins/frequently-asked-questions/' target='blank'>WordPress Frequently Asked Questions</a></li>
<li><a href='https://wordpress.org/plugins/private-password-posts/' target='blank'>Private Password Posts</a></li>

== WordPress 404s Plugin Change log ==
= Version 3.5.9 =
>[Corrected an error of new users having no data and another issue](https://tooltips.org/wordpress-404s-plugin-by-wordpress-tooltips-3-5-9-released/)  


= Version 3.5.1 =
follow wordpress security standard to use esc_attr to escaped all output
use sanitize_text_field to check and filter $_SERVER and $_REQUEST...etc too
Thank You for guidance

= Version 3.4.9 =
fix problem in "Stop Insert New 404 Log Records into Database"
fix option "Clear 404 logs now"
follow wordpress security standard to use sanitize_text_field to enhance security for field values which submit by super admin in wordpress admin 
Thanks for the report

= Version 3.4.1 =
>[How to fixed broken images in wordpress automatically](https://tooltips.org/how-to-fixed-broken-images-in-wordpress-automatically-wordpress-404s-plugin-3-4-1-released/)  


= Version 3.3.5 =
Support wordpress 5.5
Removed unused functions and comments, removed debug codes, clean codes, clean unused css codes

= Version 3.3.3 =
wordpress 404s plugin support multiple language, we use load_plugin_textdomain() to load plugin's translated srtings, you can translate it with your language and put your .mo file in languages folder

= Version 3.2.9 =
* New option in 404 Settings Panel: "Stop Insert New 404 Log Records into Database", if you enable this option, we will not trace / record new 404 page URLs into site database 
* Improved 404 plugin menu item

= Version 3.2.5 =
Support create post for each 404 error urls via wordperss editor, for example, you can create a post your.com/best-apple-iphone for the 404 error url your.com/apples, and create another post your.com/buy-iphone for 404 error url your.com/buy
In backend, you will find new menu item "All 404s", in which you can edit / delete / manage / custom 404s posts
Also you will find new menu item "Add New", in which you can create new post manually and assign this post with a 404 url via "what is the 404 URL" metabox    


= Version 3.1.3 =
Export 404s records to CSV: you can export all 404 pages with user informations to CSV file, CSV file can be used in excel, you can use excel to sort & analyze 404s error pages and 404 users, in "404 Global Settings" panel, just click  "Export 404s" menu item to export 404s records into CSV file.

= Version 3.0.3 =
Added more detailed description in setting panels to hlep users setp up easier 


= Version 2.9.3 =
In "404 Global Settings" panel, option to set up to redirect 404 error page to specific posts, if users enter an error page url, when they open the 404 error pages, they will go to be redirected to your specific page, it can be your landing page, shop page, login page, category.... and url, even URL of another site!

= Version 2.8.3 =
Option to send 404 URL alert to webmaster's mail box, you can enable or disable 404 alert in 404 global settings panel
If you enable 404 error alert email notification, you can customize the email address which used to receive 404 errors notification

= Version 2.5.5 =
In wordpress 404s settings panel, added "Redirect 404 with 301 moved permanently status code or 302 code" option box 
Added more description

= Version 2.4.7 =
Fixed a php warning in log, which generated in paginate links of "404 Page Not Found Log" panel,  caused by a few site did not use https
  
= Version 2.4.5 =
Fix the php warning in apache log, which caused by some pages have no $_SERVER['HTTP_REFERER']

= Version 2.4.3 =
In 404s trace page in admin area, added paginate navigation links, if the number of 404 records more than 20,
we will show paginate navigation links at the bottom of the 404s page, you will see navigation links pre,  1,2,3..., next.. and so on
per page will show 20 404 records

= Version 1.4.3 =
Support redirect 404 error page users to custom url
In 404s Global Settings panel, you will find the in the select box "Redirect 404 to HomePage", we added new option "Custom URL", 
if you select "Custom URL", URL input box will shown under the select box, you can enter custom url in here, 
if you select other options in the select box, the url input box will be hide
in front end, when users view a page which do not exist, he will be redirected to the custom url     

Instead of 'wp_redirect' function, we use wp_safe_redirect with 301 status to get a better and safe redirection for users and improve seo rank

= Version 1.2.1 =
opt to redirect 404 error page to home page
You will find "Redirect 404 to HomePage ? " option box in "404 Global Settings" panel, opt to redirect 404 error page to home page, so users come from search engines will not open 404 errror pages, they will go to home page directly

= Version 1.1.1 =
Improved design in back end 404 trace page, will not messed up again when user enter a very long URL

= Version 1.0.9 =
Added "Global Setting Panel" in admin area
You can remove 404 Logs now
When change settings in admin area, we will show a notify bar after the settings changed successful
Improved UI in setting panel
Fixed the bug of UI messed up when emptyed log

= Version 1.0 =
* Spell out that the license is GPLv3
* Finished the first version
* General code clean up


== Installation ==

1:Upload the 404s plugin to your blog
2:Activate it 
3: You will find 404s menu item in admin area, just click 404s menu item, you can find all 404 records in a table.
1, 2, 3: You're done!

== Frequently Asked Questions ==
FAQs can be found here: https://tooltips.org/

== Screenshots ==
1. 404 global settings panel
2. 404s Log Panel
3. Create post for each 404 error page not found URL
4. Manage 404 posts
5. Export 404s logs to excel csv file for SEO analyze... 

== Changelog ==
= Version 3.5.1 =
follow wordpress security standard to use esc_attr to escaped all output
use sanitize_text_field to check and filter $_SERVER and $_REQUEST...etc too
Thank You for guidance

= Version 3.4.9 =
fix problem in "Stop Insert New 404 Log Records into Database"
fix option "Clear 404 logs now"
follow wordpress security standard to use sanitize_text_field to enhance security for field values which submit by super admin in wordpress admin
Thanks for the report

= Version 3.3.5 =
Support wordpress 5.5
Removed unused functions and comments, removed debug codes, clean codes, clean unused css codes

= Version 3.3.3 =
wordpress 404s plugin support multiple language, we use load_plugin_textdomain() to load plugin's translated srtings, you can translate it with your language and put your .mo file in languages folder

= Version 3.3.1 =
Follow wordpress standard to imporoved menus of wordpress 404s plugin

= Version 3.2.9 =
* New option in 404 Settings Panel: "Stop Insert New 404 Log Records into Database", if you enable this option, we will not trace / record new 404 page URLs into site database 
* Improved 404 plugin menu item

= Version 3.2.5 =
Support create post for each 404 error urls, for example, you can create a post your.com/best-apple-iphone for the 404 error url your.com/apples, and create another post your.com/buy-iphone for 404 error url your.com/buy  

= Version 3.1.3 =
Export 404s records to CSV: you can export all 404 pages with user informations to CSV file, CSV file can be used in excel, you can use excel to sort & analyze 404s error pages and 404 users, in "404 Global Settings" panel, just click  "Export 404s" menu item to export 404s records into CSV file.

= Version 3.0.3 =
Added more detailed description in setting panels to hlep users setp up easier

= Version 2.9.3 =
In "404 Global Settings" panel, option to set up to redirect 404 error page to specific posts, if users enter an error page url, when they open the 404 error pages, they will go to be redirected to your specific page, it can be your landing page, shop page, login page, category.... and url, even URL of another site!

= Version 2.8.3 =
Option to send 404 URL alert to webmaster's mail box, you can enable or disable 404 alert in 404 global settings panel
If you enable 404 error alert email notification, you can customize the email address which used to receive 404 errors notification

= Version 2.5.7 =
Added screenshot for 404 global settings panel
 
= Version 2.5.5 =
In wordpress 404s settings panel, added "Redirect 404 with 301 moved permanently status code or 302 code" option box 
Added more description


= Version 2.4.7 =
Fixed a php warning in log, which generated in paginate links of "404 Page Not Found Log" panel,  caused by a few site did not use https

= Version 2.4.5 =
Fix the php warning in apache log, which caused by some pages have no $_SERVER['HTTP_REFERER']

= Version 2.4.3 =
In 404s trace page in admin area, added paginate navigation links, if the number of 404 records more than 20,
we will show paginate navigation links at the bottom of the 404s page, you will see navigation links pre,  1,2,3..., next.. and so on
per page will show 20 404 records

= Version 1.4.3 =
Support redirect 404 error page users to custom url
In 404s Global Settings panel, you will find the in the select box "Redirect 404 to HomePage", we added new option "Custom URL", 
if you select "Custom URL", URL input box will shown under the select box, you can enter custom url in here, 
if you select other options in the select box, the url input box will be hide
in front end, when users view a page which do not exist, he will be redirected to the custom url     

Instead of 'wp_redirect' function, we use wp_safe_redirect with 301 status to get a better and safe redirection for users and improve seo rank

= Version 1.2.1 =
opt to redirect 404 error page to home page
You will find "Redirect 404 to HomePage ? " option box in "404 Global Settings" panel, opt to redirect 404 error page to home page, so users come from search engines will not open 404 errror pages, they will go to home page directly

= Version 1.1.1 =
Improved design in back end 404 trace page, will not messed up again when user enter a very long URL

= Version 1.0.9 =
Added "Global Setting Panel" in admin area
You can remove 404 Logs now
When change settings in admin area, we will show a notify bar after the settings changed successful
Improved UI in setting panel
Fixed the bug of UI messed up when emptyed log

= Version 1.0 =
* Spell out that the license is GPLv3
* Finished the first version
* General code clean up
