<?php
/**
 * Plugin Name: Newly Added Post Highlighter
 * Plugin URI: https://example.com/plugins/Newly Added Post Highlighter/
 * Description: Highlight newly added posts with tag.
 * Version: 1.0
 * Requires at least: 5.0
 * Requires PHP: 7.2
 * Author: Grupa ZTW
 * Author URI: https://darksource.pl/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

  function naph_admin_actions_register_menu() {
    add_options_page("Newly Added Post Highlighter", "New Post Highlighter", "manage_options", "naph", "naph_admin_page");
  }

  add_action("admin_menu", "naph_admin_actions_register_menu");

  function naph_admin_page() {
    global $_POST;

    if (isset($_POST['naph_do_change'])) {
      if ($_POST['naph_do_change'] == 'Y') {
	$opDays = $_POST['naph_days'];
	echo '<div class="notice notice-success is-dismissible"><p>Settings saved.</p></div>';
	update_option('naph_days', $opDays);
      }
    }

    $opDays = get_option('naph_days');
?>
    <div class="wrap">
      <h1>Newly Added Post Highlighter</h1>
      <form name="naph_form" method="post">
        <input type="hidden" name="naph_do_change" value="Y">
	<p>Highlight post title for <input type="number" name="naph_days" min="0" max="30" value="<?php echo $opDays ?>"> days</p>
        <p class="submit"><input type="submit" value="Submit"></p>
      </form>
    </div>
<?php
  }
?>
