=== Meta Tags ===
Author: divpusher
Author URI: https://divpusher.com
Contributors: divpusher
Donate link: https://divpusher.com/
Tags: meta tags, seo, edit meta tags, search engine optimization, facebook open graph, twitter cards, schema.org
Requires at least: 4.7.0
Tested up to: 5.3.2
Stable tag: 2.1.2
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0

A powerful plugin to edit meta tags on all your pages, posts, categories, tags and Custom Post Types from one easy-to-use table. WooCommerce is supported as well. Facebook's OpenGraph and Twitter Cards are included.



== Description ==

A powerful plugin to edit meta tags on all your pages, posts, categories, tags and (public) Custom Post Types from one easy-to-use table. WooCommerce is supported as well. Facebook's OpenGraph and Twitter Cards are included.

We’d love to hear your feedbacks and suggestions, please let us know on our support forums!

You can also help us [develop the plugin on GitHub](https://github.com/divpusher/wordpress-meta-tags)!


== Latest Featured News ==
- You don't have to set autopilot manually anymore for newly added items!
- Added a back button to tag editor page
- Visit the [changelog](https://wordpress.org/plugins/meta-tags/#developers) for more info


== Setup ==

After activating the plugin visit Settings/Meta tags. There you can set up meta tags for each page, posts, etc.


== Screenshots ==

1. The meta tag editor table in Settings / Meta tags
2. The meta tag editor form


== Frequently Asked Questions ==

None yet.


== Changelog ==

= 2.1.2 =
* Update: replaced deprecated get_woocommerce_term_meta() with get_term_meta() function
* Fix: meta tags for authors and post tags are now appear properly

= 2.1.1 =
* Update: you don't have to set autopilot manually anymore for newly added items
* Update: bulk actions were removed
* Update: added a back button to tag editor page
* Update: plugin is tested up to WP 5.2.1
* Update: language files
* Fix: page type detection fine tunings
* Fix: meta tags didn't appear on Custom Post Type category and tag pages
* Fix: our notification appeared when any plugin was updated

= 2.1.0 =
* Update: added support for Custom Post Types
* Update: unassigned (empty) taxonomies are displayed from now on
* Update: plugin is tested up to WP 5.1.1
* Update: language files
* Fix: WooCommerce items are now only displayed if it's installed

= 2.0.2 =
* Update: plugin is tested up to WP 5.0.0
* Update: added a direct link to the meta tag editor in each page/post/product editor
* Update: language files

= 2.0.1 =
* Update: added pagination for table, so it won't crash if there are too many items to display
* Update: CSS code is now commented
* Update: language files

= 2.0.0 =
* Update: complete refactoring, switching to more maintainable OOP code
* Update: brand new interface: now you can control all meta tags in one central table easily
* Update: removed page title setting, because you can define it elsewhere natively
* Fix: added some missing tags

= 1.2.7 =
* Fix: first product disappeared on WooCommerce shop page
* Fix: page title setting now appears

= 1.2.6 =
* Fix: meta tags are now saved properly

= 1.2.5 =
* Update: refactor, DRY, less code but same results

= 1.2.4 =
* Fix: meta tags will now appear properly if the page is set as Posts page in Settings / Reading
* Fix: in some cases meta tag settings disappeared, now they shouldn't

= 1.2.3 =
* Fix: meta tags now appear properly on WooCommerce pages

= 1.2.2 =
* Update: added support for woocommerce products
* Fix: notices

= 1.2.1 =
* Fix: some strings were not translation ready
* Fix: support notice on plugin page now appears only once, after activation

= 1.2.0 =
* POST inputs are now sanitized before saving
* Added nonce and permission check

= 1.1 =
* The first version of this plugin! Enjoy! :)