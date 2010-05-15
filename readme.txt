=== Plugin Name ===
Contributors: valendesigns
Donate link: http://valendesigns.com/wordpress/envato-marketplace-items/
Tags: envato, api, gallery, themeforest, flashden, envato
Requires at least: 2.8
Tested up to: 3.0
Stable tag: 1.0.3

Retrieves items from an Envato Marketplace and API set of your choice, then show the results as a sidebar thumbnail gallery.

== Description ==

The **Envato Marketplace Items** plugin retrieves items from an Envato Marketplace and API set of your choice, then caches and shows the results as a gallery of 80px square thumbnails. Anywhere on your blog you would like to see the thumbnail gallery add `<?php if (function_exists('envato_marketplace_items')) { envato_marketplace_items(); } ?>`.  

== Installation ==

1. Upload the `envato-marketplace-itmes` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Place `<?php if (function_exists('envato_marketplace_items')) { envato_marketplace_items(); } ?>` in your theme (sidebar.php recommended). Or, use the built in widget.

== Frequently Asked Questions ==

= I get errors about file_get_contents() =

Requires the PHP function `file_get_contents()`. Check with your host to make sure it's enabled or even available.

== Screenshots ==

1. The options page.

== Changelog ==

= 1.0.3 =
* Added 3dOcean & CodeCanyon
* Added widget capabilities

= 1.0.2 =
* Changed API call from FlashDen to ActiveDen

= 1.0.1 =
* Added PHP 4 support

= 1.0.0 =
* Added plugin to svn