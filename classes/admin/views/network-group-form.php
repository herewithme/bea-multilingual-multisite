<div id="form-add-translation-group" class="wrap">
	<h3><?php _e('Add a translation group', 'bea-mm'); ?></h3>
	
	<?php settings_errors('bea-mm-network'); ?>
	<form method="post" action="#form-add-translation-group">
		<table class="form-table">
			<tr class="form-field form-required">
				<th scope="row"><?php _e('Name', 'bea-mm'); ?></th>
				<td>
					<input required="required" class="regular-text" type="text" name="ngroup[name]" />
					<br />
					<span class="description">
						<?php _e('This name is used on DB. (All characters in lowercase and no weird) <strong>This value can not be changed</strong>', 'bea-mm'); ?>
					</span>
				</td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><?php _e('Label', 'bea-mm'); ?></th>
				<td>
					<input required="required" class="regular-text" type="text" name="ngroup[label]" />
					<br />
					<span class="description"><?php _e('This parameter is used for display translation group on backoffice.', 'bea-mm'); ?></span>
				</td>
			</tr>
		</table>
		
		<p class="submit">
			<?php wp_nonce_field( 'add-translation-group' ); ?>
			<input class="button-primary" name="add-translation-group" type="submit" value="<?php _e('Add group', 'bea-mm'); ?>" />
		</p>
	</form>
</div>