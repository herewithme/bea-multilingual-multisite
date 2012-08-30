<div id="form-add-language" class="wrap">
	<h3><?php _e('Add a translation group', 'bea-mm'); ?></h3>
	
	<form method="post" action="">
		<table class="form-table">
			<tr class="form-field form-required">
				<th scope="row"><?php _e('Code language', 'bea-mm'); ?></th>
				<td>
					<input class="regular-text" type="text" name="ngroup[code]" />
					<br />
					<span class="description">
						<?php _e('This code language must be composed with ISO 639 language code (lowercase), an underscore and ISO 3166-1 alpha-2 country code (uppercase)', 'bea-mm'); ?>
						<br />
						<strong><?php _e('Sample', 'bea-mm'); ?></strong> <?php _e('French France : fr_FR, French Canada : fr_CA, Deutch : de_DE, British english: en_GB', 'bea-mm'); ?>
					</span>
				</td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><?php _e('Admin Label', 'bea-mm'); ?></th>
				<td>
					<input class="regular-text" type="text" name="ngroup[admin_label]" />
					<br />
					<span class="description"><?php _e('This parameter is used for display language on backoffice.', 'bea-mm'); ?></span>
				</td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><?php _e('Public Label', 'bea-mm'); ?></th>
				<td>
					<input class="regular-text" type="text" name="ngroup[public_label]" />
					<br />
					<span class="description"><?php _e('This parameter is used for display language on frontoffice.', 'bea-mm'); ?></span>
				</td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><?php _e('User Agent', 'bea-mm'); ?></th>
				<td>
					<input class="regular-text" type="text" name="ngroup[user_agent]" />
					<br />
					<span class="description"><?php _e('This parameter is used to redirect the country sites from browser settings.', 'bea-mm'); ?></span>
				</td>
			</tr>
		</table>
		
		<p class="submit">
			<?php wp_nonce_field( 'add-ms-language' ); ?>
			<input class="button-primary" name="add-ms-language" type="submit" value="<?php _e('Add language', 'bea-mm'); ?>" />
		</p>
	</form>
</div>