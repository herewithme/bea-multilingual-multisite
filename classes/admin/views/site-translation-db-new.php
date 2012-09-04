<div class="wrap">
	<h3><?php _e( 'Group Translation' ); ?></h3>
	<p>
		<?php _e( 'You do not currently belong to any group translation. When connecting this site to other languages​​, you must connect in a common group.', 'bea-mm' ); ?>
	</p>
	
	<form action="" method="post">
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="group"><?php _e( 'Translation group', 'bea-mm' ); ?></label></th>
					<td>
						<select id="group" name="translation[group]">
							<?php foreach( $db_groups as $db_group ) : ?>
								<option value="<?php echo $db_group['name']; ?>"><?php echo $db_group['label']; ?></label>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="language-code"><?php _e( 'Code language', 'bea-mm' ); ?></label></th>
					<td>
						<input class="regular-text" type="text" value="" id="language-code" name="translation[language_code]" />
						<span class="description">
							<?php _e('This code language must be composed with ISO 639 language code (lowercase), an underscore and ISO 3166-1 alpha-2 country code (uppercase)', 'bea-mm'); ?>
							<br />
							<strong><?php _e('Sample', 'bea-mm'); ?></strong> <?php _e('French France : fr_FR, French Canada : fr_CA, Deutch : de_DE, British english: en_GB', 'bea-mm'); ?>
						</span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="public-label"><?php _e( 'Public label', 'bea-mm' ); ?></label></th>
					<td>
						<input class="regular-text" type="text" value="" id="public-label" name="translation[public_label]" />
						<span class="description"><?php _e('This parameter is used for display language on frontoffice.', 'bea-mm'); ?></span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="admin-label"><?php _e( 'Admin label', 'bea-mm' ); ?></label></th>
					<td>
						<input class="regular-text" type="text" value="" id="admin-label" name="translation[admin_label]" />
						<span class="description"><?php _e('This parameter is used for display language on backoffice.', 'bea-mm'); ?></span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="user-language"><?php _e( 'User language', 'bea-mm' ); ?></label></th>
					<td>
						<input class="regular-text" type="text" value="" id="user-language" name="translation[user_language]" />
						<span class="description"><?php _e('This parameter is used to redirect the country sites from browser settings.', 'bea-mm'); ?></span>
					</td>
				</tr>
			</tbody>
		</table>
		
		<p class="submit">
			<?php wp_nonce_field( 'save-translation' ); ?>
			<input type="submit" class="button-primary" name="save-translation" value="<?php _e( 'Save', 'bea-mm' ); ?>" />
		</p>
	</form>
</div>