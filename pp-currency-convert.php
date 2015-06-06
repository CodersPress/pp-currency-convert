<?php
/*
Plugin Name: Premiumpress - Currency Converter
Plugin URI: http://coderspress.com/
Description: This plugin converts the default premiumpress currency to the users choice.
Version: 2015.0606
Updated: 6th June 2015
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

add_action('admin_menu', 'currency_menu');
function currency_menu() {
	add_menu_page('Currency Converter', 'Currency Converter', 'administrator', __FILE__, 'currency_settings_page',plugins_url('/images/currency.png', __FILE__));
	add_action( 'admin_init', 'register_currency_settings' );
}
function register_currency_settings() {
    register_setting("currency-settings-group", "currency_alert_message");
    register_setting("currency-settings-group", "currency_types");
    register_setting("currency-settings-group", "currency_loading");
    register_setting("currency-settings-group", "currency_first_option");
}
function currency_defaults()
{
    $option = array(
        "currency_alert_message" => "Use this converter as a guide ONLY! All prices are charged in USD",
		"currency_types" => "AED\r\nARS\r\nAUD\r\nBRL\r\nGBP\r\nCAD\r\nCLP\r\nCNY\r\nCZK\r\nDKK\r\nDOP\r\nEGP\r\nEUR\r\nHKD\r\nHUF\r\nINR\r\nIDR\r\nILS\r\nJPY\r\nMYR\r\nMXN\r\nNZD\r\nNOK\r\nPKR\r\nPLN\r\nRUB\r\nSGD\r\nZAR\r\nKRW\r\nSEK\r\nCHF\r\nTWD\r\nTHB\r\nTRY\r\nUSD",
		"currency_loading" => "(Converting...)",
		"currency_first_option" => "Currency Guide",
    );
  foreach ( $option as $key => $value )
    {
       if (get_option($key) == NULL) {
        update_option($key, $value);
       }
    }
    return;
}
register_activation_hook(__FILE__, "currency_defaults");

function currency_settings_page() {
if ($_REQUEST['settings-updated']=='true') {
echo '<div id="message" class="updated fade"><p><strong>Currency settings Changed.</strong></p></div>';
}
?>
<div class="wrap">
    
<h2>Currency Setup and Types</h2>

    <hr />
    <form method="post" action="options.php">
        <?php settings_fields( "currency-settings-group");?>
        <?php do_settings_sections( "currency-settings-group");?>
        <table class="widefat" style="width:800px;">
			<thead style="background:#2EA2CC;color:#fff;">
                <tr>
                    <th style="color:#fff;">First <b>Select</b> option...</th>
                    <th style="color:#fff;"></th>
                </tr>
            </thead>
            <tr>
                <td>Appears first in the drop-down-box options. It has no value:</td>
                <td>
                    <input type="text" name="currency_first_option" value="<?php echo get_option( "currency_first_option");?>" size="20">
                </td>
            </tr>
			<tr>
			<thead style="background:#2EA2CC;color:#fff;">
                <tr>
                    <th style="color:#fff;">Loading...</th>
                    <th style="color:#fff;"></th>
                </tr>
            </thead>
            <tr>
                <td>Loading message appended to prices while converting:</td>
                <td>
                    <input type="text" name="currency_loading" value="<?php echo get_option( "currency_loading");?>" size="20">
                </td>
            </tr>
			<tr>
            <thead style="background:#2EA2CC;color:#fff;">
                <tr>
                    <th style="color:#fff;">Alert Message...</th>
                    <th style="color:#fff;"></th>
                </tr>
            </thead>
            <tr>
                <td>Notice message pops when a user changes currency:</td>
                <td>
                    <input type="text" name="currency_alert_message" value="<?php echo get_option( "currency_alert_message");?>" size="60">
                </td>
            </tr>
			<tr>
<thead style="background:#2EA2CC;color:#fff;">
                <tr>
                    <th style="color:#fff;">Currency Types...</th>
                    <th style="color:#fff;"></th>
                </tr>
            </thead>
            <tr>
                <td>3 Letter <a href="http://www.xe.com/iso4217.php" target="_blank">ISO codes</a> you want to allow users to chose from.<br /> <br /><b>Enter one per line...</b></td>
                <td>
                    <textarea name="currency_types" cols=40 rows=20><?php echo get_option( "currency_types");?></textarea>
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>
</div>

<?php
}

function jquery_currency_js()
{
    wp_register_script( 'currency-script', plugins_url( '/js/jquery.currency.js', __FILE__ ) );
    wp_enqueue_script( 'currency-script' );
}
add_action( 'wp_enqueue_scripts', 'jquery_currency_js' );

function currency_shortcode() {
    if (isset($_POST['currency-selected'])) {
        $_SESSION['currency-selected'] = $_POST['currency-selected'];
    }
    $STRING = '<form style="display:inline;" action="" method="post" id="currency">';
    $STRING .= '<select id="currency-selected" name="currency-selected">';
    $STRING .= '<option value="" disabled selected style="display:none;">' . get_option( "currency_first_option") . '</option>';
    $curriencies = explode("\r\n", get_option( "currency_types"));
    foreach($curriencies as $values) {
		if(!$values){continue;}
        $STRING .= '<option value="'.$values.'" '.(($_SESSION["currency-selected"] == $values) ? 'selected="selected"' : "").'>'.$values.'</option>';
    }
    $STRING .= '</select>';
    $STRING .= '</form>';
    return $STRING;
}
add_shortcode('CURRENCYCONVERTER', 'currency_shortcode');

function currency_footer_script(){
?>
<script type="text/javascript">
jQuery(document).ready(function () {

selectedCurrency = "<?=$_SESSION['currency-selected'];?>";

        jQuery("#currency-selected").on("change", function () {
			alert("<?php echo get_option( "currency_alert_message");?>");
            jQuery("#currency").submit();
        });

		if (selectedCurrency) {

        jQuery("#finalprice1, a.btn-lg").text(function (_, text) {
            return text.replace(/\(|\)/g, "");
        });

		jQuery(".wlt_shortcode_price, #finalprice1, a.btn-lg, .badge.badge-success").html(function(i,symbol){
			return symbol.replace( /\<?php echo $GLOBALS['CORE_THEME']['currency']['symbol'];?>/ig, '' );
		});

        <!-- PAYMENT OPTIONS -->

		if (jQuery('h4.modal-title').length) {
		var payment_price = jQuery("h4.modal-title").text().match(/((?:[0-9]+,)*[0-9]+(?:\.[0-9]+)?)/)[1];
		jQuery( "<span> : <span class='payment_price'>" + payment_price + "</span></span>" ).appendTo( "h4.modal-title" );
		jQuery(".payment_price").currency({
                region: selectedCurrency,
                convertFrom: "<?php echo $GLOBALS['CORE_THEME']['currency']['code'];?>",
                convertLocation: "<?php echo plugins_url("/convert.php", __FILE__ );?>",convertLoading: "<?php echo get_option( 'currency_loading');?>"
            });
		}

        <!-- END PAYMENT OPTIONS -->

        <!-- HOME PAGE & SEARCH LISTINGS -->

            jQuery(".wlt_shortcode_price").currency({
                region: selectedCurrency,
                convertFrom: "<?php echo $GLOBALS['CORE_THEME']['currency']['code'];?>",
                convertLocation: "<?php echo plugins_url("/convert.php", __FILE__ );?>",convertLoading: "<?php echo get_option( 'currency_loading');?>"
            });

        <!-- HOME PAGE & SEARCH LISTINGS -->

        <!-- SINGLE LISTINGS -->
        jQuery("#finalprice1").currency({
            region: selectedCurrency,
            convertFrom: "<?php echo $GLOBALS['CORE_THEME']['currency']['code'];?>",
            convertLocation: "<?php echo plugins_url("/convert.php", __FILE__ );?>",convertLoading: "<?php echo get_option( 'currency_loading');?>"
        });

        <!-- END SINGLE LISTINGS -->

        <!-- PACKAGE PRICING -->

		jQuery(".panel-title.price").each(function ( i ) {
        jQuery(this).attr('id', 'price'+i);
        jQuery(this).next('.days').attr('id', 'pack'+i);
		jQuery( "<b><span class='package_price'>" + jQuery('#price'+i).text().match(/((?:[0-9]+,)*[0-9]+(?:\.[0-9]+)?)/)[1] + "</span><span> : </span></b>" ).prependTo( "#pack"+i );

		});

        jQuery(".package_price").currency({
             region: selectedCurrency,
             convertFrom: "<?php echo $GLOBALS['CORE_THEME']['currency']['code'];?>",
             convertLocation: "<?php echo plugins_url("/convert.php", __FILE__ );?>",convertLoading: "<?php echo get_option( 'currency_loading');?>"
        });

        <!-- END PACKAGE PRICING -->

        <!-- ADD LISTING -->

        jQuery(".badge.badge-success").currency({
            region: selectedCurrency,
            convertFrom: "<?php echo $GLOBALS['CORE_THEME']['currency']['code'];?>",
            convertLocation: "<?php echo plugins_url("/convert.php", __FILE__ );?>",convertLoading: "<?php echo get_option( 'currency_loading');?>"
        });

        <!-- END ADD LISTING -->

}
    });
</script>
<?php } 
add_action('wp_footer','currency_footer_script');
?>