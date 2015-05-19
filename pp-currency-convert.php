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

function currency_convert() {

}

register_activation_hook(__FILE__, "currency_convert");

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
    $STRING = '<form action="" method="post" id="currency">';
    $STRING .= '<select id="currency-selected" name="currency-selected">';
    $STRING .= '<option value="" disabled selected style="display:none;">Currency</option>';
    $get_currencies = get_option('submissionfields');
    $curriencies = explode("\n", $get_currencies['99']['values']);
    foreach($curriencies as $values) {
		$value = preg_replace("/\s+/", "", $values);
		if(!$value){continue;}
        $STRING .= '<option value="'.$value.'" '.(($_SESSION["currency-selected"] == $value) ? 'selected="selected"' : "").'>'.$value.'</option>';
    }
    $STRING .= '</select>';
    $STRING .= '</form>';
    return $STRING;
}
add_shortcode('CURRENCYSELECT', 'currency_shortcode');

function currency_footer_script(){
?>
<script type="text/javascript">
    jQuery(document).ready(function() {
		
	jQuery("#finalprice1, a.btn-lg").text(function (_, text) {
		    return text.replace(/\(|\)/g, "");
		});

					<!-- HOME PAGE & SEARCH LISTINGS -->
		jQuery(".itemdata").each(function () {
		    var post_ids = jQuery(this).attr("class").match(/itemid(\d+)/)[1];
		    var cid = "cid" + post_ids;
		    jQuery(".itemid" + post_ids).find(".wlt_shortcode_price").attr("id", cid);
		    jQuery.post('<?php echo plugins_url( "/get_currency.php", __FILE__ );?>', 'val=' + post_ids, function (currency_code) {
		        selectedCurrency = "<?=$_SESSION['currency-selected'];?>";
		        if (!selectedCurrency) {
		            selectedCurrency = currency_code;
		        }

		        jQuery("#" + cid).currency({
		            region: selectedCurrency,
		            convertFrom: currency_code,
		            convertLocation: "<?php echo plugins_url("/convert.php", __FILE__ );?>"
		        });
		    });
		});
					<!-- HOME PAGE & SEARCH LISTINGS -->

					<!-- SINGLE LISTINGS -->
jQuery("#SINGLEIMAGEDISPLAY > img:nth-child(1)").each(function () {

		    var post_id = jQuery(this).attr("alt").match(/no-image-(\d+)/)[1];
		    var cid = "cid" + post_id;
		    jQuery("#finalprice1, a.btn-lg").attr("id", cid);
		    jQuery.post('<?php echo plugins_url( "/get_currency.php", __FILE__ );?>', 'val=' + post_id, function (currency_code) {
		        selectedCurrency = "<?=$_SESSION['currency-selected'];?>";
		        if (!selectedCurrency) {
		            var selectedCurrency = currency_code;
		        }

				var price_amount = jQuery("#" + cid).text().match(/(\d+)/)[1];
				jQuery("#" + cid).html(price_amount);


		        jQuery("#finalprice1, #" + cid).currency({
		            region: selectedCurrency,
		            convertFrom: currency_code,
		            convertLocation: "<?php echo plugins_url("/convert.php", __FILE__ );?>"
		        });

			setTimeout(function(){
				var set_price = jQuery("#" + cid).text().match(/((?:[0-9]+,)*[0-9]+(?:\.[0-9]+)?)/)[1];
					jQuery("input[name=mc_gross]").val(set_price);
					jQuery("input[name=amount]").val(set_price);
					jQuery("input[name=c_currency]").val(selectedCurrency);
					jQuery("input[name=currency_code]").val(selectedCurrency);
				}, 2500);

		    });

		});
					<!-- END SINGLE LISTINGS -->


		jQuery("#currency-selected").on("change", function () {
		    jQuery("#currency").submit();
		});
});
</script>
<?php } 
add_action('wp_footer','currency_footer_script');
?>