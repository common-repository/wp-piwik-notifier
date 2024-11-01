<?php
defined( 'ABSPATH' ) OR exit;
/**
 * Plugin Name: Piwik Notifier Bar
 * Plugin URI: https://github.com/scharc/wp-piwik-notifier
 * Description: Notifies users about current website uses Piwik to track user behaviour with cookies
 * Version: 0.3.1
 * Author: Marc Schütze
 * Author URI: https://github.com/scharc/wp-piwik-notifier
 * Text Domain: wp-piwik-notifier
 */

load_plugin_textdomain( 'wp-piwik-notifier', false, dirname(plugin_basename(__FILE__ )) . '/lang/' );	

//activation, deactivation and uninstall function in seperate file
//constans as well
//wppn-config.php
require_once('wppn-config.php');

//hook activation
register_activation_hook( __FILE__, 'wppn_activation' );
//hook deactivation
register_deactivation_hook( __FILE__, 'wppn_deactivation' );
//hook uninstall
register_uninstall_hook( __FILE__, 'wppn_uninstall' );

//------------------ AJAX ------------------------ //

//wp_ajax_* function is used for logged-in user
add_action( 'wp_ajax_wppn_ajax_set_cookie', 'wppn_ajax_set_cookie_callback' );
//wp_ajax_nopriv_* function is used for non-registered user
add_action( 'wp_ajax_nopriv_wppn_ajax_set_cookie', 'wppn_ajax_set_cookie_callback' );

/**
 *
 * function for ajax call from front-end to accept cookie's
 * $_REQUEST['nonce'] = anti spam 
 * nonce: number once, a generated number used once
 *
 * prints json message
 * 
 */
function wppn_ajax_set_cookie_callback() {
	
	if ( ! wp_verify_nonce( $_REQUEST[ 'nonce' ], 'wp-piwik-notifier')) {
			$result[ 'type' ] = 'error';
			$result[ 'message' ] = 'Bad Request';
			header("Content-type: application/json");
    		echo json_encode( $result );
    		exit;
		}
	//user is verified, set cookie
	wppn_set_cookie();
	$result[ 'type' ] = 'success';
	$result[ 'message' ] = 'Everything went well';
	header("Content-type: application/json");
    echo json_encode( $result );
    exit;
	}

//--------------- GET REQUEST --------------------//

//send_headers hook is used, runs before page rendering
add_action( 'send_headers', 'wppn_check_cookie' );

/**
 * When Javascript is disabled, a simple GET is used to set the cookie
 * otherwise hooks are used to prepare the notification bar
 * @return [type] [description]
 */
function wppn_check_cookie() {
	//check for GET Parameter AND if cookie is already set 
	if( isset($_GET['WPPN']) && ! isset( $_COOKIE['wppn-cookie'] ) ) {
		wppn_set_cookie();
	}
	//get options
	$options = get_option(WP_PIWIK_NOTIFIER_OPTION_NAME );
	//if option->piwik_url is empty bar will not show up
	if ( ! isset( $_COOKIE['wppn-cookie'] ) && ! empty($options[ 'piwik_url' ]) ) {
		add_action( 'wp_enqueue_scripts', 'wppn_scripts' );
		add_action( 'wp_footer', 'wppn_show_notifier_bar' );
	}
}

/**
 * register the necessary scripts and style for front-end
 * wp_localize_script is used to get the admin-ajax.php filepath as object in the main.js file
 * css_file is loaded from options 
 */
function wppn_scripts() {
	//js file is used for ajax and moving the bar from footer to right after the <body> tag
	//register js file
	wp_register_script( 'wppn-js', plugins_url('js/main.js', __FILE__ ), array('jquery') );
	//add needed definition for the admin-ajax-path at runtime
	wp_localize_script( 'wppn-js', 'wppnAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ))); 
	//enqueue the script
	wp_enqueue_script( 'wppn-js' );
	
	$options = get_option( WP_PIWIK_NOTIFIER_OPTION_NAME );
	$css_file = $options[ 'css_file' ];
	//register css file
	wp_register_style( 'wppn-css', $css_file );
	//enqueue css file
	wp_enqueue_style( 'wppn-css' );
}

/**
 * includes the bar file
 * for readability purpose of plugin source
 */
function wppn_show_notifier_bar() {
	// include the source file 
	// mostly html
	include ( 'inc/wppn-notifier-bar.php');
}


//-------------------- ADMIN -------------------- //
//register the settings with WP Settings API
add_action('admin_init', 'wppn_admin_init');
//add the menu to the option menu
add_action('admin_menu', 'wppn_settings_menu');
//notify admin if piwik url is not set
add_action('admin_notices', 'wppn_admin_notice');

function wppn_admin_init() {
	//register the settings field 'wppn_setting_field' to be used in the setting page
	//wppn_options_sanitize is the callback function
	//all options saved will go throught the callback function before stored in db
	register_setting('wppn_settings_field', WP_PIWIK_NOTIFIER_OPTION_NAME, 'wppn_options_sanitize');
	}

/**
 * sanitize options 
 * @param  [array] $options options from the settings page 
 * @return [array]          returns sanitized options
 */
function wppn_options_sanitize( $options) {
	//sanitize_text_field removes dangerouse user input in text
	$options['notifier_bar_text'] = sanitize_text_field( $options['notifier_bar_text'] );
	$options['notifier_bar_ok'] = sanitize_text_field( $options['notifier_bar_ok'] );
	$options['notifier_bar_ok'] = sanitize_text_field( $options['notifier_bar_ok'] );
	$options['notifier_bar_read_more'] = sanitize_text_field( $options['notifier_bar_read_more'] );
	//esc_url removes dangerouse non-url chars and escapes all special chars
	$options['piwik_url'] = esc_url($options['piwik_url'], array('http', 'https') );
	return $options;
}
/**
 * add settings item to the wp admin menu
 * titel: Piwik Nofitier
 * capability: user can manage options
 * settings to show: wppn_settings_page
 */
function wppn_settings_menu() {
	add_options_page( __('Piwik Notifier', 'wp_piwik_notifier'), __('Piwik Notifier', 'wp_piwik_notifier'), 'manage_options', 'wppn-settings', 'wppn_settings_page' );
}

/**
 * display the settings form 
 */
function wppn_settings_page() {
	//for tidiness moved html to seperate file
	include ( 'inc/wppn-settings-page.php' );
}

/**
 * Admin Nag for setting the Piwik URL
 * Only shows if current user can manage_options
 */
function wppn_admin_notice() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$option = get_option( WP_PIWIK_NOTIFIER_OPTION_NAME );
	if ( ! isset($option['piwik_url'] ) || empty( $option['piwik_url'] )){
		$options = get_option( WP_PIWIK_NOTIFIER_OPTION_NAME );
		?>
		<div class="update-nag">
        <strong>WP Piwik Notifier:</strong><br>
        <a href="<?php echo admin_url('options-general.php?page=wppn-settings') ?>"><?php _e( 'Please update the Piwik URL in the settings page', 'wp-piwik-notifier' ); ?></a><br>
      	</div>
    	<?php
	}
}

// -------------- Helper Functions --------------------- //

/**
 * sets the http cookie and the php_cookie for runtime evaluation
 * @return [type] [description]
 */
function wppn_set_cookie() {
		/**
		 * bool setcookie ( string $name [, string $value [, int $expire = 0 [, string $path [, string $domain [, bool $secure = false [, bool $httponly = false ]]]]]] );
		 * Ref.: http://www.php.net/manual/de/function.setcookie.php
		 */
		//cookie expires in 365 days
		$expires = time() + 60 * 60 * 365;
		setcookie( 'wppn-cookie', 'consent-given', $expires, COOKIEPATH, COOKIE_DOMAIN, false );
		
		// Headers with Cookie will be send with the next http request. 
		// To use it in the current php runtime, $_COOKIE has to be set
		$_COOKIE[ 'wppn-cookie' ] = 'true';
	}

/**
 * helper function to get all css file in the plugin dir as array to show in settings
 * to use a custom css file make a 'wp-piwik-notifier' folder in your current theme
 * and add a file called main.css. minify it!
 * @return [array] [assoc array filename => path]
 */
function wppn_get_css_files() {
	//absolute file path for file_exist()
	$css_folder = plugin_dir_path( __FILE__ );
	$css_files = array();
	foreach ( glob( $css_folder . "css/*.css" ) as $filename ) {
		//relative file path saved in array to display in frontend
    	$css_files[ basename( $filename, '.css' )] = plugins_url( 'css/' . basename( $filename ), __FILE__ );;
	}
	$theme_root = get_template_directory();
	if( file_exists( $theme_root . "/wp-piwik-notifier/main.css" )){
		$custom_css_file = get_template_directory_uri();
		$custom_css_file .= "/wp-piwik-notifier/main.css";
		$css_files['* ' . basename( $custom_css_file )] = $custom_css_file;
		}
	return $css_files;
}

// ---------------------- SHORT CODE -------------- //

/**
 * shortcode for the piwik iframe
 * to disable the tracking cookie 
 */
function wppn_iframe_shortcode(){
	$options = get_option( WP_PIWIK_NOTIFIER_OPTION_NAME );
	//if piwik_url is not set, just replace shortcode with empty string
	if( empty ( $options['piwik_url'] ) ){
		return "";
	}
	//returns $content with iframe 
	include( 'inc/wppn-piwik-iframe.php' );	
	return $content;
}
add_shortcode( 'piwik-iframe', 'wppn_iframe_shortcode' );

 
?>