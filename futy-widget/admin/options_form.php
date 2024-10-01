<?php
if ( ! defined('ABSPATH')) {
    die;
}

$wpLocale = get_locale();
$futyLocale = 'en';

if ($wpLocale === 'nl_NL') {
	$futyLocale = 'nl';
}
?>

<div class="wrap">
	<h1><?php echo esc_html(__('Futy.io Leadbots', 'futy')); ?></h1>

	<hr>

	<div class="futy-layout">
		<div class="futy-settings">
			<h2><?php echo esc_html(__('Settings', 'futy')); ?></h2>
			<p><?php echo __('You can design your own Leadbot or WhatsApp widget in the <a href="https://app.futy.io/implementation" target="_blank">Futy dashboard</a>. You can also find there your personal Futy key that you can copy and add in the input field below.', 'futy'); ?></p>
			<form method="post" action="#">
				<?php wp_nonce_field('update-futy-options'); ?>

				<table class="form-table">
					<tbody>
						<tr>
							<th scope="row">
								<label for="widget_code"><?php _e('Futy key', 'futy') ?>:</label>
							</th>
							<td>
								<input name="widget_code" id="widget_code" type="text"
									value="<?php echo sanitize_key($widget_code); ?>" class="regular-text">
							</td>
						</tr>

						<tr>
							<th scope="row">
								<label for="widget_visibility"><?php _e('Show on website', 'futy') ?>:</label>
							</th>
							<td>
								<select name="widget_visibility">
									<option value="visible" <?php if ( $widget_visibility === 'visible' ) { echo " selected='selected'"; } ?>><?php _e('Visible', 'futy') ?></option>
									<option value="hidden" <?php if ( $widget_visibility === 'hidden' ) { echo " selected='selected'"; } ?>><?php _e('Hidden', 'futy') ?></option>
								</select>
							</td>
						</tr>
					</tbody>
				</table>

				<div id="submit" class="submit">
					<input type="submit" name="submit_settings" class="button button-primary" value="<?php _e('Save settings', 'futy'); ?>">
				</div>
			</form>
		</div>

		<hr>

		<div class="futy-links">
			<h2><?php echo esc_html(__('Links', 'futy')); ?></h2>

			<a href="https://app.futy.io/register/?utm_source=wordpress&utm_medium=plugin&utm_campaign=plugin_dashboard&locale=<?php echo $futyLocale ?>" class="button" target="_blank"><?php echo esc_html(__('Register for free', 'futy')); ?></a>
			<a href="https://app.futy.io/login/?utm_source=wordpress&utm_medium=plugin&utm_campaign=plugin_dashboard&locale=<?php echo $futyLocale ?>" class="button" target="_blank"><?php echo esc_html(__('Login', 'futy')); ?></a>
			<a href="https://www.futy.io/blog/?utm_source=wordpress&utm_medium=plugin&utm_campaign=plugin_dashboard" class="button" target="_blank"><?php echo esc_html(__('Documentation', 'futy')); ?></a>
			<a href="https://www.futy.io/?utm_source=wordpress&utm_medium=plugin&utm_campaign=plugin_dashboard" class="button" target="_blank"><?php echo esc_html(__('Website', 'futy')); ?></a>
		</div>

		<hr>

		<div class="futy-examples">
			<h2><?php echo esc_html(__('Examples', 'futy')); ?></h2>

			<img src="<?php echo esc_url(plugins_url('../assets/images/whatsapp-oplossingen.png', __FILE__)); ?>" style="max-width: 750px;" alt="Futy Leadbots">
		</div>
	</div>
</div>