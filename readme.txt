=== Plugin Name ===
Contributors: nperson
Donate link: http://flattr.com/thing/1120136/David-Persson
Tags: media, image, imagick, imagemagick
Requires at least: 3.4
Tested up to: 3.5.1
Stable tag: trunk
License: BSD-3-Clause
License URI: http://opensource.org/licenses/BSD-3-Clause

High-quality media processing for your blog.

== Description ==

This plugin adds high-quality media processing to your blog. At the plugin's core is [mm, the PHP media library](https://github.com/davidpersson/mm) which is throughly tested and in production use on several media heavy sites. A new settings panel allows you to configure two additional media versions that are generated alongside the built-in WordPress ones. For each version you may select one of the resizing methods, pick the target format and enable additional optimizations.

#### Available resizing methods:

* fit inside - Resizes media proportionally keeping both sides within given dimensions.
* fit outside - Resizes media proportionally keeping _smaller_ side within corresponding dimensions.
* crop - Crops media to provided dimensions.
* zoom & fit - Enlarges media proportionally by factor 2.
* zoom & crop - First crops an area (given by dimensions and enlarged by factor 2) out of the center of the media, then resizes that cropped area to given dimensions.
* fit & crop - First resizes media so that it fills out the given dimensions, then cuts off overlapping parts.

#### Available target formats:

* PNG
* JPEG

#### Available optimizations:

* compress slightly
* strip all metadata
* interlace

#### Requirements

This plugin requires PHP >= 5.3.0, the imagick PHP extension and WordPress >= 3.4.0.

#### Usage

Additional generated versions are made available as `fix0` and `fix1`. Embed them in your theme like this:

<code>
the_post_thumbnail('fix0');
the_post_thumbnail('fix1');
</code>

#### Issues

The project's main repository is hosted over at GitHub: 
https://github.com/davidpersson/wordpress_mm

Please post any bugs to the issues tracker there:
https://github.com/davidpersson/wordpress_mm/issues

Thank you for using the plugin!

#### Support

If you enjoy using this plugin and this open source project is of great
use to you or your company, please consider supporting it through donations, by
buying commercial support or by sponsoring a feature.

*Commercial support is available.* Support this project by buying commercial support. 
Please contact me for more details via nperson@gmx.de.

_Flattr_ is also a good way to donate money and show support for this project:
http://flattr.com/thing/1120136/David-Persson

*Sponsoring a feature* is new way to support the project. All features will be
open sourced and immediately made available under the same permissive license
for everybody.  

In order to sponsor a project write a mail to nperson@gmx.de with the subject
_Sponsor a feature_, stating which payment method you prefer and which feature
you'd like to sponsor. I'll then respond with further details.

#### Copyright & License

WordPress Mm is Copyright (c) 2013 David Persson if not otherwise stated. The
code is distributed under the terms of the BSD 3-clause License. For the
full license text see the license.txt file.

== Installation ==

Make sure you've got the imagick PHP extension installed. Please consult [the official PHP documentation](http://www.php.net/manual/en/imagick.installation.php) for more information on this topic.

Download the plugin by using the built-in WordPress plugin installer. You may also download the plugin manually. In this case place it in `wp-content/plugins/mm`.

Activate the plugin through the WordPress admin interface.

Regenerate existing images.


== Screenshots ==

1. Shows the status of the library used for processing the media.

2. Available resizing methods.

3. Several optimizations are available.
