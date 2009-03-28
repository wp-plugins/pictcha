=== Pictcha ===
Contributors: Gilemon
Donate link: http://utyp.net/
Tags: spam, antispam, anti-spam, comments, comment, captcha, pictcha
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
2. Recycles concentration and time involved in solving the problem to improve itself by learning from human users.

= How It Works =

Pictcha generates image based CAPTCHAs which require the user to describe a random image. Pictcha is more secure than traditional text based CAPTCHAs which can be read via OCR programs.

= Feedback =

Please let me know what you think about the plugin and any suggestions you may have. If you use the plugin please rate it. If it doesn't work for you do let me know so I can fix it.

[Post Feedback](http://utyp.net/pictcha-sample.php "Post your feedback, suggestions or bug reports")

== Installation ==

1. Upload 'pictcha.php' to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Choose Security_level

*Note 1:* If you have Wordpress 2.7 or above you can simply go to 'Plugins' > 'Add New' in the Wordpress admin and search for "Pictcha" and install it from there.

*Note 2:* If your WordPress theme doesn't have the 'comment_form' hook (i.e. Pictcha won't show up with the comment form), enter the following code right before the closing '</form>' tag in the 'comments.php' file of the theme.

`<?php do_action('comment_form', $post->ID); ?>`

== Frequently Asked Questions ==

= How does Pictcha work? =

UTYP Pictcha is a protection in a form of an image retrieved from UTYP engine.

= Is JavaScript required to comment? =

No.

= Are Cookies required to comment? =

No, Pictcha does not use cookies.

= Does Pictcha support other languages? =

Not at the moment but the design of UTYP engine is made to learn new languages as people from different countries use it. Also if you wish to help with the translations of the interface please write to gilemon at nthinking.net.

= Can you add a feature X? =

Let me know, I will try if its useful enough and I have time for it.

== Screenshots ==

1. Pictcha screenshot ([Try the live demo](http://utyp.net/pictcha-sample.php "Pictcha Demo"))