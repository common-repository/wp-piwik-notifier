<?php
$options = get_option( WP_PIWIK_NOTIFIER_OPTION_NAME );
?>
<div class="wppn-wrapper">
	<div class="wppn-container">
		<div class="wppn-cookie-text">
			<a href="?WPPN" class="wppn-ajax wppn-link" data-nonce="<?php echo wp_create_nonce( 'wp-piwik-notifier' ); ?>">
			<?php echo $options['notifier_bar_text']; ?></a>
			<a href="?WPPN" class="wppn-ajax wppn-link" data-nonce="<?php echo wp_create_nonce( 'wp-piwik-notifier' ); ?>"><button class="wppn-btn wp-piwik-notifier-button"><?php echo $options['notifier_bar_ok']; ?></button></a>
		</div>
		<div class="wppn-read-more">
			<a href="<?php echo get_permalink( $options[ 'post_id' ]); ?>"><?php echo $options['notifier_bar_read_more']; ?></a>
		</div>
	</div>
</div>