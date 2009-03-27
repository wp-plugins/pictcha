=== Pictcha ===
Contributors: Gilemon
Donate link: http://utyp.net/
Tags: spam, antispam, anti-spam, comments, comment, captcha, clickcha, pictcha
Requires at least: 2.3
Tested up to: 2.7.1
Stable tag: trunk

Pictcha is a very unique & secure CAPTCHA.

== Description ==

UTYP Pictcha is a protection in a form of an image retrieved from UTYP engine, which can be embedded inside web forms, and which will filter out spams.
When someone uses UTYP Pictcha the UTYP engine learns and gets more accurate. 

[Click here](http://utyp.net/pictcha-sample.php "Pictcha Demo") to see Pictcha in action.

= Features =

1. Simple and ludic CAPTCHA .
2. Recycles concentration and time involve in solving the problem to improve itself by learning from human users.

= How It Works =

Clickcha generates image based CAPTCHAs which require the user to describe a random image. Clickcha is more secure than traditional text based CAPTCHAs which can be read via OCR programs.

= Feedback =

Please let me know what you think about the plugin and any suggestions you may have. If you use the plugin please rate it. If it doesn't work for you do let me know so I can fix it.

[Post Feedback](http://utyp.net/pictcha-sample.php "Post your feedback, suggestions or bug reports")

== Installation ==

1. Upload `clickcha.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

`<?php do_action('comment_form', $post->ID); ?>`

*Note 2:* If you have Wordpress 2.7 or above you can simply to ‘Plugins’ > ‘Add New’ in the Wordpress admin and search for “pictcha” and install it from there.

== Frequently Asked Questions ==

= How does Clickcha work? =

UTYP Pictcha is a protection in a form of an image retrieved from UTYP engine.

= Is JavaScript required to comment? =

No.

= Are Cookies required to comment? =

No, Pictcha does not use cookies.

= What happens with pingbacks and trackbacks? =

Clickcha does not filter pingbacks and trackbacks.

= Does Pictcha support other languages? =

Not at the moment but the design of UTYP engine is made to learn new languages as people from different countries use it. Also if you wish to help with the translations of the interface please write to gilemon at nthinking.net.

= Can you add a feature X? =

Let me know, I will try if its useful enough and I have time for it.

== Screenshots ==

1. Pictcha screenshot ([Try the live demo](http://utyp.net/pictcha-sample.php "Clickcha Demo"))