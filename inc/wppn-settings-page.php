<?php
$options = get_option( WP_PIWIK_NOTIFIER_OPTION_NAME );
?>

<div class="wrap">
    <h2><?php _e( 'WP Piwik Notifier Setting', 'wp-piwik-notifier' ); ?></h2>
    <form method="post" action="options.php">
        <?php settings_fields( 'wppn_settings_field' ); ?>
        <table class="form-table">            
        	<tr>
            	<th scope="row">
                	<label for="notifier-bar-text" class="description"><?php _e( 'Notifier Bar Text', 'wp-piwik-notifier' ); ?></label>
                </th>
                <td>
                    <fieldset>
                        <legend></legend>
                        <textarea name="wp_piwik_notifier_options[notifier_bar_text]" rows="5" cols="30" id="notifier-bar-text" class="all-options" ><?php echo $options[ 'notifier_bar_text' ]; ?></textarea>
                        <br>
                        
                    </fieldset>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="notifier-bar-ok"><?php _e( 'Notifier Bar OK Button', 'wp-piwik-notifier' ); ?></label>
                </th>
                <td>
                    <fieldset>
                        <legend></legend>
                        <input id="notifier-bar-ok" type="text" class="regular-text" name="wp_piwik_notifier_options[notifier_bar_ok]" value="<?php echo $options[ 'notifier_bar_ok' ] ?>" 
                    </fieldset>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="notifier-bar-read-more"><?php _e( 'Read More Text', 'wp-piwik-notifier' ); ?></label>
                </th>
                <td>
                    <fieldset>
                        <legend></legend>
                        <input id="notifier-bar-read-more" type="text" class="regular-text" name="wp_piwik_notifier_options[notifier_bar_read_more]" value="<?php echo $options[ 'notifier_bar_read_more' ] ?>" 
                    </fieldset>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="notifier-bar-page"><?php _e( 'Front End Page', 'wp-piwik-notifier' ); ?></label>
                </th>
                <td>
                    <select id="notifier-bar-page" name="wp_piwik_notifier_options[post_id]">
                        <?php
                        $post_options = array(
                            'sort_order' => 'ASC', 
                            'sort_column' => 'post_title',
                            'post_status' => 'draft,pending,publish'
                        );
                        $pages = get_pages( $post_options );
                        foreach( $pages as $page )
                        {
                        ?>
                            <option value="<?php echo $page->ID; ?>" <?php echo ( $page->ID == $options[ 'post_id' ] ) ? 'selected' : ''; ?>><?php echo $page->post_title; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                    <p class="description"><?php _e( 'Select the Page for the Settings.', 'wp-piwik-notifier' ); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="css-selector"><?php _e( 'Select Style Sheet', 'wp-piwik-notifier' ); ?></label>
                </th>
                <td>
                    <select id="css-selector" name="wp_piwik_notifier_options[css_file]">
                        <?php
                        $css_files = wppn_get_css_files();
                        foreach( $css_files as $filename => $filepath )
                        {
                        ?>
                            <option value="<?php echo $filepath ?>" <?php echo ( $filepath == $options[ 'css_file' ] ) ? 'selected' : ''; ?>><?php echo $filename ?></option>
                        <?php
                        }
                        ?>
                    </select>
                    <p class="description"><?php _e( 'Custom Style Sheet is marked with a *', 'wp-piwik-notifier'); ?></p>
                </td>
            </tr>
            
            <tr>
                <th scope="row">
                    <label for="notifier-bar-piwik-url"><?php _e( 'URL to Piwik', 'wp-piwik-notifier' ); ?></label>
                </th>
                <td>
                    <fieldset>
                        <legend></legend>
                        <input id="notifier-bar-piwik-url" type="text" class="regular-text" name="wp_piwik_notifier_options[piwik_url]" value="<?php echo $options[ 'piwik_url' ] ?>" 
                    </fieldset>
                    <p class="description"><?php _e( 'URL to Piwik with trailing slash: http://stats.piwik.org/', 'wp-piwik-notifier' ); ?></p>
                </td>
            </tr>
            
        </table>
        
        <p class="submit">
            <input id="submit" class="button button-primary" type="submit" value="<?php _e( 'Save changes', 'cookie-notification-jc' ); ?>" />
        </p>
    </form>
</div>