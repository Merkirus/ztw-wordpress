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

    if(get_option('aap_announcement')){
        $announcements = get_option('aap_announcement');
    }
    else {
        $announcements = [];
    }

    if (isset($_POST['aap_do_change'])) {
      array_push($announcements, $_POST['aap_announcement']);
      echo '<div class="notice notice-success is-dismissible"><p>Announcement saved.</p></div>';
      update_option('aap_announcement', $announcements);
    }

    if (isset($_POST['aap_do_remove'])) {
      $announcements = get_option('aap_announcement');
      unset($announcements[(int)$_POST['id_removed_announcement']]);
      echo '<div class="notice notice-success is-dismissible"><p>Announcement removed.</p></div>';
      update_option('aap_announcement', $announcements);
    }

?>
  <div class="wrap">
    <h1>Announcement page</h1>
    <form name="aap_form" method="post">
      <input type="hidden" name="aap_do_change" value="Y">
      <p>Add new announcement</p>
      <textarea name="aap_announcement" rows="5" cols="50"></textarea>
      <p class="submit"><input type="submit" value="Submit"></p>
    </form>
  </div>
  <br>
  <div>
    <form name="aap_form_remove" method="post">
      <input type="hidden" name="aap_do_remove" value="Y">
      <?php foreach (get_option('aap_announcement') as $key=>$value) { ?>
      <div class="aap_item"><p class="wrapping_text"><?php echo $value; ?></p><button class="remove_button" type="submit" name="id_removed_announcement" value="<?php echo $key; ?>">X</button></div>
      <?php } ?>
    </form>
  </div>
<?php
  }

  function aap_place_announcement($content) {
    if (!get_option('aap_announcement')) {
      return $content;
    }
    $announcements = get_option('aap_announcement');
    $announcement_value = $announcements[array_rand($announcements, 1)];
    return '<div class="aap_announcement">
      <p>'.$announcement_value.'</p>
    </div>'.$content;
  }
  add_filter( 'the_content', 'aap_place_announcement' );

  function aap_register_styles() {
    wp_register_style('aap_styles', plugins_url('/css/style.css', __FILE__));
    wp_enqueue_style('aap_styles');
  }

  add_action('init', 'aap_register_styles');

?>
