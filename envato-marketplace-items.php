<?php
/*
Plugin Name: Envato Marketplace Items
Plugin URI: http://valendesigns.com/wordpress/envato-marketplace-items/
Description: Retrieves items from an Envato Marketplace and API set of your choice, then show the results as a sidebar thumbnail gallery.
Version: 1.0.0
Author: Derek Herman
Author URI: http://valendesigns.com
*/

/*  Copyright 2009  Derek Herman  (email : derek@valendesigns.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


if (!defined('WP_CONTENT_URL'))
	define('WP_CONTENT_URL', get_option('siteurl').'/wp-content');
if (!defined('WP_CONTENT_DIR'))
	define('WP_CONTENT_DIR', ABSPATH.'wp-content');

/**
 * Define the URL of the plugin's folder.
 */
define('MARKETPLACE_FOLDER', WP_CONTENT_URL.'/plugins/'.basename(dirname(__FILE__)).'/');
	
/*
 * Wordpress Hooks
 */
register_activation_hook(__FILE__, 'envato_marketplace_items_install');
register_deactivation_hook(__FILE__, 'envato_marketplace_items_uninstall');

/**
 * Install Envato Marketplace Items
 */
function envato_marketplace_items_install() 
{
  global $wpdb;
  
  $table = $wpdb->prefix.'envato_marketplace_items';

  $sql = 
  'CREATE TABLE IF NOT EXISTS '.$table .' ( 
    `id` bigint NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `api_id` varchar(250) NOT NULL,
    `api_set` varchar(250) NOT NULL,
    `api_params` varchar(250),
    `api_items` mediumint(2) NOT NULL,
    `referral_id` varchar(250),
    `api_title` varchar(250),
    `api_title_html` varchar(250),
    `api_content` longtext NOT NULL,
    `api_time` datetime NOT NULL default "0000-00-00 00:00:00",
    UNIQUE key (api_id)
  );';
  
  $wpdb->query($sql);
  
  // Add Default Settings
  add_option('envato_marketplace', 'themeforest');
  add_option('marketplace_set', 'popular');
  add_option('marketplace_items', '6');
  add_option('marketplace_heading_html', 'h2');
  add_option('marketplace_heading', 'Popular Files');
  
}

/**
 * Uninstall Envato Marketplace Items
 */
function envato_marketplace_items_uninstall() 
{
  global $wpdb;
  
  // Drop Table
  $table = $wpdb->prefix.'envato_marketplace_items';
  $sql = 'DROP TABLE '.$table;
  $wpdb->query($sql);
  
  // Delete Settings
  delete_option('envato_marketplace');
  delete_option('marketplace_set');
  delete_option('marketplace_param');
  delete_option('marketplace_items');
  delete_option('referral_id');
  delete_option('marketplace_heading_html');
  delete_option('marketplace_heading');
  
}

/**
 * WordPress Hooks (admin menu & stylesheet)
 */
add_action('admin_menu', 'envato_marketplace_items_add_page');
add_action('wp_print_styles', 'envato_marketplace_items_stylesheet');

/**
 * Action function to add settings menu
 */
function envato_marketplace_items_add_page() 
{
  add_options_page('Envato Marketplace Items Settings', 'Envato Marketplace', 8, basename(__FILE__), 'envato_marketplace_items_settings_page');
}

/**
 * Action function to add stylesheet
 */
function envato_marketplace_items_stylesheet() 
{
	if (!is_admin())
	{
    wp_enqueue_style( 'envato-marketplace-items', MARKETPLACE_FOLDER.'marketplace.css', array(), '1.0.0', 'all' );
  }
}

/**
 * Displays content for settings page
 */
function envato_marketplace_items_settings_page() 
{
  ?>
  <div class="wrap" style="max-width:700px;">
    <h2>Envato Marketplace Items Settings</h2>
    <p>The <strong>Envato Marketplace Items</strong> plugin retrieves items from an Envato Marketplace and API set of your choice, then shows the results as a sidebar 80px square thumbnail gallery. Anywhere on your blog you want to see the thumbnail gallery add &lt;?php if (function_exists('envato_marketplace_items')) { envato_marketplace_items(); } ?&gt;.</p>
    <p>To get started, decide on which Marketplace you want to pull your data from and then choose the type of files (API set) you want that data to be. </p>
    <p><strong>NOTE:</strong> If you pick User files or Category files you will need to add a second parameter. </p>
    <p>For the User set just add the username of the files you want to display. For the Category set add an existing cateory from the Marketplace you are trying to pull data from (make sure you spell it correctly or it will not work). For example, if I chose to get Popular Files I would <strong>leave the parameter blank</strong>. However, if I chose User Files, it would be something like <strong>valendesigns</strong>, and for Category Files it might be <strong>wordpress</strong> if I was looking for files from ThemeForest or <strong>video-players</strong> if it was FlashDen.</p>
    <p>Choosing the number of items is easy but rememer that you can only show at most 10 items for any one user at a time. Also, in most situations there will be about 30 or so files returned so adding some rediculous number will not make it so.</p>
    <p>If you want to add your referral ID to the end of the links, just enter it below or leave it blank it's not required.</p>
    <p>Lastly, if you want to change the Title Text and/or Title HTML you can do so with the settings below (remember to add the proper CSS to your stylesheet to make it look good). As well, the Title Text is not required and will not display the Title HTML if you leave it blank.</p>
    
    <form method="post" action="options.php">
      <input type="hidden" name="action" value="update" />
      <input type="hidden" name="page_options" value="envato_marketplace, marketplace_set, marketplace_param, marketplace_items, referral_id, marketplace_heading_html, marketplace_heading" />
      <?php wp_nonce_field('update-options'); ?>
      <p>
        <label for="envato_marketplace">Choose a Marketplace:</label><br />
        <select name="envato_marketplace" id="envato_marketplace" value="<?php echo get_option('envato_marketplace'); ?>" style="width: 172px;">
          <option name="flashden" value="flashden"<?php if(get_option('envato_marketplace') == "flashden") { echo ' selected'; } ?>>FlashDen</option>
          <option name="themeforest" value="themeforest"<?php if(get_option('envato_marketplace') == "themeforest") { echo ' selected'; } ?>>ThemeForest</option>
          <option name="videohive" value="videohive"<?php if(get_option('envato_marketplace') == "videohive") { echo ' selected'; } ?>>VideoHive</option>
          <option name="graphicriver" value="graphicriver"<?php if(get_option('envato_marketplace') == "graphicriver") { echo ' selected'; } ?>>GraphicRiver</option>
          <option name="audiojungle" value="audiojungle"<?php if(get_option('envato_marketplace') == "audiojungle") { echo ' selected'; } ?>>AudioJungle</option>
        </select>
      </p>
      <p>
        <label for="marketplace_set">Choose an API Set:</label><br />
        <select name="marketplace_set" id="marketplace_set" value="<?php echo get_option('marketplace_set'); ?>" style="width: 172px;">
          <option name="popular" value="popular"<?php if(get_option('marketplace_set') == "popular") { echo ' selected'; } ?>>Popular Files</option>
          <option name="new-files-from-user" value="new-files-from-user"<?php if(get_option('marketplace_set') == "new-files-from-user") { echo ' selected'; } ?>>User Files</option>
          <option name="new-files" value="new-files"<?php if(get_option('marketplace_set') == "new-files") { echo ' selected'; } ?>>Category Files</option>
        </select>
      </p>
      <p>
        <label for="marketplace_param">API Set Parameter (refer to text above):</label><br />
        <input type="text" name="marketplace_param" id="marketplace_param" value="<?php echo get_option('marketplace_param'); ?>" />
      </p>
      <p>
        <label for="marketplace_items"># of Items:</label><br />
        <input type="text" name="marketplace_items" id="marketplace_items" value="<?php echo get_option('marketplace_items'); ?>" style="width: 34px; text-align:center;" />
      </p>
      <p>
        <label for="referral_id">Referral User ID:</label><br />
        <input type="text" name="referral_id" id="referral_id" value="<?php echo get_option('referral_id'); ?>" />
      </p>
      <p>
        <label for="marketplace_heading_html">Title HTML:</label><br />
        <select name="marketplace_heading_html" id="marketplace_heading_html" value="<?php echo get_option('marketplace_heading_html'); ?>" style="width: 172px;">
          <?php $count = 1;
          while($count <= 6): ?>
            <option name="h<?php echo $count ?>" value="h<?php echo $count ?>"<?php if(get_option('marketplace_heading_html') == 'h'.$count) { echo ' selected'; } ?>>h<?php echo $count ?></option>
          <?php $count++; endwhile; ?>
        </select>
      </p>
      <p>
        <label for="marketplace_heading">Title Text:</label><br />
        <input type="text" name="marketplace_heading" id="marketplace_heading" value="<?php echo get_option('marketplace_heading'); ?>" />
      </p>

      <p class="submit">
        <input type="submit" name="Submit" value="Update Options" />
      </p>
    </form>
  </div>
  <?php
}

/**
 * Popular Marketplace items
 *
 * retrieves json data from an Envato Marketplace API once every hour and
 * inserts the HTML into your database as a simple way to cache the results
 * to reduce API calls.
 */
function envato_marketplace_items() 
{
  global $wpdb;
  
  // Get Marketplace
  $api_id = get_option('envato_marketplace');
  
  // Get Marketplace API Set
  $api_set = get_option('marketplace_set');
  
  // Get Marketplace API Set Parameter
  $get_api_param = get_option('marketplace_param');
  if ($api_set == 'new-files') {
    $api_url = $api_id.','.$get_api_param;
  } else if ($api_set == 'new-files-from-user') {
    $api_url = $get_api_param.','.$api_id;
  } else {
    $api_url = $api_id;
  }
  
  // Get Marketplace items and set $total
  $marketplace_items = preg_match("/[0-9]{1,2}/", get_option('marketplace_items'), $total);
  $total = $total[0];
  if ($api_set == 'new-files-from-user' && $total > 10) {
    $total = 10;
  }
  
  // Get Referral ID
  $get_api_referral = get_option('referral_id');
  if ($get_api_referral) {
    $api_referral = '?ref='.$get_api_referral;
  }
  
  // Get Heading Info
  $get_heading_html = get_option('marketplace_heading_html');
  $get_heading = get_option('marketplace_heading');
  
  // Select Items
  $sql = "SELECT * FROM {$wpdb->prefix}envato_marketplace_items WHERE api_id = '{$api_id}'";
  $api = $wpdb->get_results($sql, OBJECT);
  
  $difference = strtotime(date('Y-m-d H:i:s')) - strtotime($api[0]->api_time);
  $api_time_seconds = 3600;
  
  // If no DB entry OR an hour passed OR api_set, api_param, api_items, referral_id, api_title, or api_title_html has changed
  if (!$api[0]->api_id || ($difference >= $api_time_seconds) || ($api_set != $api[0]->api_set) || ($get_api_param != $api[0]->api_param) || ($total != $api[0]->api_items) || ($get_api_referral != $api[0]->referral_id) || ($get_heading != $api[0]->api_title) || ($get_heading_html != $api[0]->api_title_html)) {
  
    $json_url = "http://marketplace.envato.com/api/edge/".$api_set.":".$api_url.".json";
    $json_contents = @file_get_contents($json_url);
    
    // If @file_get_contents($json_url) returns true
    if ($json_contents) {
      
      // Decode json data
      $json_data = json_decode($json_contents, true);
      
      // Set $count to 1
      $count = 1;
      
      // Heading HTML
      if ($get_heading) { 
        $entry .= '<'.$get_heading_html.' class="marketplace-heading">'.$get_heading.'</'.$get_heading_html.'>';
        $entry .= "\n";
      }
      
      // Start Unordered List
      $entry .= "<ul id='envato-marketplace-items'>\n";

      // Foreach Item
      if ($api_set == 'popular') {
        foreach($json_data['popular']['items_last_week'] as $item) {
          if($count <= $total) {
            $entry .= '<li><a href="'.$item['url'].$api_referral.'" target="_blank" title="'.$item['item'].'"><img src="'.$item['thumbnail'].'" alt="'.$item['item'].'" height="80px" width="80px" /></a></li>'."\n";
            $count++;
          } else {
        		break;
        	}
        }
      } else {
        foreach($json_data[$api_set] as $item) {
          if($count <= $total) {
            $entry .= '<li><a href="'.$item['url'].$api_referral.'" target="_blank" title="'.$item['item'].'"><img src="'.$item['thumbnail'].'" alt="'.$item['item'].'" height="80px" width="80px" /></a></li>'."\n";
            $count++;
          } else {
        		break;
        	}
        }
      }
      // Clear float
      $entry .= "<br class='clear' />\n";
      
      // End List
      $entry .= "</ul>\n";
      
      // If $api returns empty insert else update
      if (!$api[0]->api_id) 
      {
        $sql = 'INSERT into '.$wpdb->prefix.'envato_marketplace_items (api_id, api_set, api_params, api_items, referral_id, api_title, api_title_html, api_content, api_time) VALUES("'.$api_id.'", "'.$api_set.'", "'.$get_api_param.'", "'.$total.'", "'.$get_api_referral.'", "'.$get_heading.'", "'.$get_heading_html.'",  "'.htmlentities($entry).'", "'.date('Y-m-d H:i:s').'")';
      } 
      else 
      {
        $sql = 'UPDATE '.$wpdb->prefix.'envato_marketplace_items SET api_set = "'.$api_set.'", api_params = "'.$get_api_param.'", api_items = "'.$total.'", referral_id = "'.$get_api_referral.'", api_title = "'.$get_heading.'", api_title_html = "'.$get_heading_html.'", api_content = "'.htmlentities($entry).'", api_time = "'.date('Y-m-d H:i:s').'" WHERE api_id = "'.$api_id.'"';
      }
      
      // Run query
      $query = $wpdb->query($sql);
      
      // Echo $entry
      echo $entry;
     
    
    } else { // else $json_url data has an error & return false if no entry in DB
      
      if ($api[0]->api_id) {
        
        // Echo api_content as HTML
        echo html_entity_decode($api[0]->api_content);
        
      } else {
      
        return false;
        
      }
      
      
    }
    
  } else { // Return the content of the database
  
    // Echo api_content as HTML
    echo html_entity_decode($api[0]->api_content);
    
  }
  
}