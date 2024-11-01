<?php
define( 'WP_PIWIK_NOFITIER_VERSION', '1.1' );
define( 'WP_PIWIK_NOFITIER_ROOT_PATH', plugin_dir_path( __FILE__ ) );
define( 'WP_PIWIK_NOFITIER_ROOT_URL', plugin_dir_url( __FILE__ ) );
define( 'WP_PIWIK_NOTIFIER_OPTION_NAME', 'wp_piwik_notifier_options' );

/**
 * activation stuff
 * register options
 * create a new page for the piwik iframe
 */
function wppn_activation() {
	//check if current user can active plugins
    if ( ! current_user_can( 'activate_plugins' ) )
        return;
    //check if it is the first time 
    if ( ! get_option( WP_PIWIK_NOTIFIER_OPTION_NAME ) ) {
        //content of the new page
        $post_content = __( 'Write a long Text about Cookies, Tracking and so forth.
            Maybe link to WIKIPEDIA or some other ressource.
            Be sure to include the shortcode for the disable-cookie iframe:

            [piwik-iframe]
            ', 'wp-piwik-notifier');
        //settings of the new page
        $page_options = array(
            'post_content' => $post_content,
            'post_title' => __( 'Privacy and Piwik Infos', 'wp-piwik-notifier' ),
            'post_status' => 'draft',
            'post_type' => 'page',
            'ping_status' => 'closed',
            'comment_status' => 'closed'
            );
        //add a new post; status draft
        $post_id = wp_insert_post( $page_options );
        $options = array(
            "notifier_bar_text" => __( 'This Website uses Cookies to help build a better experience for you! Are you fine with this?', 'wp-piwik-notifier' ),
            "notifier_bar_ok" => __( 'OK','wp-piwik-notifier' ),
            "notifier_bar_read_more" => __( 'Read more or deactivate the tracking-cookie', 'wp-piwik-notifier' ),
            "post_id" => $post_id,
            "piwik_url" => "",
            "css_file" => ""
            );
        update_option( WP_PIWIK_NOTIFIER_OPTION_NAME, $options );
    }
}

/**
 * cleanup after deactivation
 * privacy page set to draft
 */
function wppn_deactivation() {
	if ( ! current_user_can( 'activate_plugins' ) )
        return;
    $plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
    check_admin_referer( "deactivate-plugin_{$plugin}" );
    $options = get_option( WP_PIWIK_NOTIFIER_OPTION_NAME );
    if( get_page( $options[ 'post_id' ])) {
        $page_update = array(
            'post_status' => 'draft',
            'ID' => $options[ 'post_id' ]
            );
        wp_update_post( $page_update );
    }
}
/**
 * cleanup after removal
 * del all options from db
 * @return [type] [description]
 */
function wppn_uninstall() {
	if ( ! current_user_can( 'activate_plugins' ) )
        return;
    check_admin_referer( 'bulk-plugins' );
    // Important: Check if the file is the one
    // that was registered during the uninstall hook.
    if ( __FILE__ != WP_UNINSTALL_PLUGIN )
        return;
    delete_option( WP_PIWIK_NOTIFIER_OPTION_NAME );
}
?>