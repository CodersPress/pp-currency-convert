<?php
/*
Plugin Name: Premiumpress - Currency Converter
Plugin URI: http://coderspress.com/
Description: This plugin converts the default premiumpress currency to the users choice.
Version: 2015.0515
Updated: 15th May 2015
Author: sMarty 
Author URI: http://coderspress.com
WP_Requires: 3.8.1
WP_Compatible: 4.2.2
License: http://creativecommons.org/licenses/GPL/2.0
*/

add_action( 'init', 'cc_plugin_updater' );
function cc_plugin_updater() {
	if ( is_admin() ) { 
	include_once( dirname( __FILE__ ) . '/updater.php' );
		$config = array(
			'slug' => plugin_basename( __FILE__ ),
			'proper_folder_name' => 'pp-currency-convert',
			'api_url' => 'https://api.github.com/repos/CodersPress/pp-currency-convert',
			'raw_url' => 'https://raw.github.com/CodersPress/pp-currency-convert/master',
			'github_url' => 'https://github.com/CodersPress/pp-currency-convert',
			'zip_url' => 'https://github.com/CodersPress/pp-currency-convert/zipball/master',
			'sslverify' => true,
			'access_token' => 'b516f14d0e7b08713bdd48f945c1fce36b698aab',
		);
		new WP_CC_UPDATER( $config );
	}
}
add_action('admin_menu', 'currency_convert');
function currency_convert() {
	add_menu_page('Distance NOT SET', 'Distance NOT SET', 'administrator', __FILE__, 'currency_convert_setting_page',plugins_url('/images/dollar_sign.png', __FILE__));
	add_action( 'admin_init', 'register_currency_convert' );
}
function register_currency_convert() {
   	register_setting("currency-convert-settings-group", "currency_convert_alert_message");
}
function currency_convert_defaults()
{
    $option = array(
        "currency_convert_alert_message" => "this is a test",
    );
  foreach ( $option as $key => $value )
    {
       if (get_option($key) == NULL) {
        update_option($key, $value);
       }
    }
    return;
}
register_activation_hook(__FILE__, "currency_convert");

function currency_convert_setting_page() {
if ($_REQUEST['settings-updated']=='true') {
echo '<div id="message" class="updated fade"><p><strong>Plugin settings saved.</strong></p></div>';
}
?>
<div class="wrap">
    <h2>PremiumPress - Currency Converter</h2>
    <hr />
<form method="post" action="options.php">
    <?php settings_fields("currency-convert-settings-group");?>
    <?php do_settings_sections("currency-convert-settings-group");?>
    <table class="widefat" style="width:600px;">

 <thead style="background:#2EA2CC;color:#fff;">
            <tr>
                <th style="color:#fff;">Message Settings</th>
                <th style="color:#fff;"></th>
                <th style="color:#fff;"></th>
            </tr>
        </thead>
<tr>
<td>This message appears when a users sets a new currency.</td>
<td></td>
<td></td>
 </tr>

<tr>
<td>Alert Message:</td>
<td><input type="text" size="40" id="currency_convert_alert_message" name="currency_convert_alert_message" value="<?php echo get_option("currency_convert_alert_message");?>"/><br /></td>
<td></td>
 </tr>
<tr>
<td></td>
<td></td>
<td></td>
 </tr>

  </table>
    <?php submit_button(); ?>
</form>
</div>
<?php
}
function pp_currency_convert(){
?>
<script type="text/javascript">
jQuery('.modal-footer > form:nth-child(1)').attr("action", "");
var latitude = "<?=$_SESSION['mylocation']['lat'];?>";
if (latitude  === '') {
    jQuery(".wlt_shortcode_distance").replaceWith("<span class='wlt_shortcode_distance'><?php echo get_option("distance_message");?><a data-target='#MyLocationModal' data-toggle='modal' onclick='GMApMyLocation();' href='javascript:void(0);'> <i class='fa fa-refresh'  style='cursor:pointer;color:<?php echo get_option('distance_icon_color');?>;'></i></a></span>");
}
</script>
<?php
}
add_action('wp_footer','pp_currency_convert');
?>