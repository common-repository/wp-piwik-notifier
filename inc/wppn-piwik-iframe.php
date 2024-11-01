<?php
ob_start();
$lang = explode('-', get_bloginfo('language'));
$lang = $lang[0];
?>
<iframe src="<?php echo $options['piwik_url']; ?>index.php?module=CoreAdminHome&amp;action=optOut&amp;language=<?php echo $lang; ?>" style="border-style: none; width: 100%; height: 100%;"></iframe>
<?php
$content = ob_get_clean();
?>