<?php
/**
 * @var string $upload_url
 * @var string $upload_nonce
 */
?>
<div class="upload-theme">
    <p class="install-help">
		<?php _e(
			'If you have a mobile theme in a .zip format, you may install or update it by uploading it here.', 'mobili'
		); ?>
    </p>
    <form method="post" enctype="multipart/form-data" class="wp-upload-form" action="<?= $upload_url ?>">
        <input type="hidden" id="_wpnonce" name="_wpnonce" value="<?= $upload_nonce ?>">
        <label class="screen-reader-text" for="themezip"><?php _e('Theme zip file'); ?></label>
        <input type="file" id="themezip" name="themezip" accept=".zip">
        <input type="submit" name="install-theme-submit" id="install-theme-submit" class="button" value="<?php _e(
			'Install Now'
		); ?>" disabled="">
    </form>
</div>