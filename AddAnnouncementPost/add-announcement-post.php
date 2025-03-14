<?php
/**
 * Plugin Name: Add Announcement Post
 * Plugin URI: https://example.com/plugins/Add Announcement Post/
 * Description: Add announcement to post.
 * Version: 1.0
 * Requires at least: 5.0
 * Requires PHP: 7.2
 * Author: Grupa ZTW
 * Author URI: https://darksource.pl/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

  function aap_admin_actions_register_menu() {
    add_options_page("Add Announcement Post", "Manage announcements", "manage_options", "aap", "aap_admin_page");
  }

  add_action("admin_menu", "aap_admin_actions_register_menu");

  function aap_admin_page() {
    global $_POST;
    global $wpdb;

    if (isset($_POST['aap_do_change'])) {
      echo '<div class="notice notice-success is-dismissible"><p>Announcement saved.</p></div>';
      $wpdb->insert('announcements', array('announcement' => $_POST['aap_announcement']), array('%s'));
    }

    if (isset($_POST['aap_do_remove'])) {
      echo '<div class="notice notice-success is-dismissible"><p>Announcement removed.</p></div>';
      $wpdb->delete('announcements', array('announcement' => $_POST['id_removed_announcement']), array('%s'));
    }

?>
  <div class="wrap">
    <h1>Announcement page</h1>
    <form name="aap_form" method="post">
      <input type="hidden" name="aap_do_change" value="Y">
      <p>Add new announcement</p>
<?php wp_editor("", "aap_announcement"); ?>
      <p class="submit"><input type="submit" value="Submit"></p>
    </form>
  </div>
<?php
  }

  function ccv_increment_post_views() {
    if(is_single()) {
      global $post;
      $views = get_post_meta($post->ID, 'ccv_post_views', true);
      $views = ($views === '') ? 1 : $views + 1;
      update_post_meta($post->ID, 'ccv_post_views', $views);
      update_option('aap_views', $views);
    }
  }

  add_action('wp_head', 'ccv_increment_post_views');

  function aap_place_announcement($content) {
    global $wpdb;
    if (!get_option('aap_announcement')) {
      return $content;
    }
    $table_name = $wpdb->prefix . 'announcements';
    $announcements = $wpdb->get_results( "SELECT announcement FROM announcements");
    $r = rand(0, count($announcements));
$announcement_value = $announcements[array_rand($announcements, 1)]->announcement;
    $char_count = strlen(strip_tags($content));
    $char_count_html = '<p>Character count: ' . $char_count . '</p>';

    $views = get_option('aap_views');
    $views_html = '<p>Views count: ' . $views . '</p>';


    return '<div class="aap_announcement">
      <p>'.$announcement_value.'</p>
    </div>'.$content . '<div class="aap_small_details">' . $views_html . $char_count_html . '</div>';

  }
  add_filter( 'the_content', 'aap_place_announcement' );

  function aap_register_styles() {
    wp_register_style('aap_styles', plugins_url('/css/style.css', __FILE__));
    wp_enqueue_style('aap_styles');
  }

  add_action('init', 'aap_register_styles');

?>
