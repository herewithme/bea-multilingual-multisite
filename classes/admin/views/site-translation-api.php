<div class="wrap">
	<h3><?php _e( 'Translation' ); ?></h3>
	<p>
		<?php printf( __( 'This site belongs to the translation group <strong>"%s"</strong>, you can\'t edit this settings because this group is declared through the use of the code API into a plugin or functions.php file of your theme.', 'bea-mm' ), $current_group['label'] ); ?>
	</p>
	
	<table class="form-table">
		<tbody>
			<tr valign="top">
				<th scope="row"><?php _e('Language code', 'bea-mm'); ?></th>
				<td><?php echo esc_html($current_site->get_language_code()); ?></td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Public label', 'bea-mm'); ?></th>
				<td><?php echo esc_html($current_site->get_language_label()); ?></td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Admin label', 'bea-mm'); ?></th>
				<td><?php echo esc_html($current_site->get_language_label(true)); ?></td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('User agent', 'bea-mm'); ?></th>
				<td><?php echo esc_html($current_site->get_user_agent()); ?></td>
			</tr>
		</tbody>
	</table>
</div>