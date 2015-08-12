=== YouTube information Widget ===
Contributors: elhardoum
Tags: cache, subscribe,  video, video player, widget,  youtube, Youtube channel, youtube user, Youtube-video, youtube plugin
Requires at least: 3.0.1
Tested up to: 4.2.4
Stable tag: 2.1.0.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Author URI: http://go.elegance-style.com/sam
Donate link: http://go.elegance-style.com/donate/

Embeds into a widget information about your YouTube channel: last uploads, popular uploads and statistics and summary.

== Description ==

This plugin allows you to embed information about your YouTube channel, including the last uploads, popular uploads, channel statistics including subscribers count, views count, and the about information, and also, a subscribe button next to your channel icon. Also supports shortcodes letting you generate more than a shortcode.

**Features:**

Embed information and data about your YouTube channel, including:

* An avatar and a link to your channel,
* A subscribe button,
* The last x uploads of your channel, if found,
* The top x popular uploads you have,
* information about your channel: about summary, total subscribers, total uploads, views and comments.

**How to use:**

After you install and activate the plugin, navigate to your widgets dashboard, and add a widget named "YouTube Information Widget". Quickly set it up by adding your account/channel username or slug or ID, and fill out the other optional options if needed.

As of 2.1, this plugin has added shortcodes tool, and which you can generate these shortcodes to use from settings > YIW Shortcode Gen. . Use the admin form there to generate as many shortcodes as you want, and add them anywhere around your site, like in a widget, post, page, PHP template ...

A premium version of this plugin is going to be available soon on <a href="http://codecanyon.net/user/elhardoum/portfolio?ref=elhardoum">CodeCanyon</a> to feature more cool stuff you could think of.

**How it works:**

This plugin uses <a href="https://developers.google.com/youtube/v3/">YouTube API V3</a> to retrieve dynamic data from feed URLs, and then, places those data in your options database to access, use and modify anytime. As those data are stored in the database, then this process is good for optimization and it is like caching and thus those data are renewed ( cache clearing process ) after X hours of your choice.

**Support:**

Post all of your support questions in the plugin <a href="http://wordpress.org/support/plugin/youtube-information-widget">support forum</a>, or by <a href="https://twitter.com/intent/tweet?text=@samuel_elh%20">tweeting to Samuel Elh</a>, or by posting me a message throughout my contact form here <a href="http://sam.elegance-style.com">Samuel Elh</a>.

**Rate & Review:**

Wether you liked this plugin, enjoyed it, or you found a bug and you thought why not reporting it, please leave your reviews and feedbacks in the plugin <a href="http://wordpress.org/support/view/plugin-reviews/youtube-information-widget">reviews page</a>.
Thanks!

== Installation ==

* Install and activate the plugin:

1. Upload the plugin to your plugins directory, or use the WordPress installer.
2. Activate the plugin through the \'Plugins\' menu in WordPress.
3. Update settings, add the plugin widget and, Enjoy!


== Frequently Asked Questions ==

= How can I find my channel ID or username? =

If you can see your channel at all, the URL is in the browser address bar.
If you can find your channel in YouTube search, then search it and copy its link address, it should contain an ID. for instance: UCF0pVplsI8R5kcAqgtoRqoA ( youtube dot com /channel/UCF0pVplsI8R5kcAqgtoRqoA ) as a channel ID, and mullenweg ( youtube dot com /user/mullenweg ) as a username.

For more support questions, please visit the [support forum](http://wordpress.org/support/plugin/youtube-information-widget) for this plugin.

== Screenshots ==

1. Plugin's widget in the widgets area.
2. Last uploads tab.
3. Popular uploads tab.
4. Channel information ( summary ) tab.
5. Shortcodes generator.

== Changelog ==

= 1.0 =
* Initial ( Beta ) release.

= 1.1 =
* fixed encoding issue.

= 1.2 =
* Fixing wp.org plugin author related issues.

= 2.0 =
* Rewritten plugin from scratch, improved caching and performance.

= 2.1 =
* Improved even more, added shortcodes tool.