# Browsing History

This is the development repository for Browsing History, a WordPress plugin that saves user's browsing history and lists them. You can also download the plugin package installation from the [WordPress.org Plugin Directory](https://wordpress.org/plugins/browsing-history/).

Contributors: takashimatsuyama  
Donate link:  
Tags: browsing history, accessibility, design  
Requires at least: 4.8  
Tested up to: 6.0  
Requires PHP: 5.4.0  
Stable tag: 1.3.1  
License: GPLv2 or later  
License URI: http://www.gnu.org/licenses/gpl-2.0.html  

Save user’s browsing history and list them.

##  Description

Save user’s browsing history and list them.

This plugin is simple. You can save the user's browsing history just a install and display them anywhere you want with just a shortcode.

The logged-in user's data is saved in the user meta. Other user's data is saved to Web Storage (localStorage).

##  Usage

* **Shortcode:** `[ccc_browsing_history_list_results title="" posts_per_page="" class="" style=""]`

You can show only specific post types.
* **Shortcode:** `[ccc_browsing_history_list_results post_type="post"]`
* **Shortcode:** `[ccc_browsing_history_list_results post_type="post, page, custom"]`

default: "any", It doesn't need "post_type".

##  Installation

1. Upload `browsing-history` to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Use shortcodes to display the browsing history list.

##  Changelog

### 1.3.1
Tested on WordPress 6.0.

### 1.3.0
Add a shortcode attribute to show only specific post types.

### 1.2.2
Fixed PHP 8.0 warning.

### 1.2.1
Modify the handle names of wp_enqueue_script.

### 1.2.0
Modify markup of thumbnails and modify CSS.

### 1.1.0
Add shortcode attribute (`style=""`) and modify CSS.

### 1.0.0
Initial release.