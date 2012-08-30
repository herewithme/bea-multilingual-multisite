<?php if ( is_array($api_groups) && !empty($api_groups) ) : ?>
	<div class="wrap">
		<h3>
			<?php _e("Registered translation groups by plugin API", 'bea-mm'); ?>
		</h3>
		
		<p><?php _e('Groups registered by the API are not editable. You need to change your source code to apply any changes.', 'bea-mm'); ?></p>
		<table class="widefat">
			<thead>
				<tr>
					<th scope="col"><?php _e('Name', 'bea-mm'); ?></th>
					<th scope="col"><?php _e('Label', 'bea-mm'); ?></th>
					<th scope="col"><?php _e('Websites', 'bea-mm'); ?></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th scope="col"><?php _e('Name', 'bea-mm'); ?></th>
					<th scope="col"><?php _e('Label', 'bea-mm'); ?></th>
					<th scope="col"><?php _e('Websites', 'bea-mm'); ?></th>
				</tr>
			</tfoot>
			<tbody id="the-list" class="list:groups">
				<?php
				$class = 'alternate';
				$i = 0;
				foreach ( (array) $api_groups as $group ) :
					$i++;
					$class = ( $class == 'alternate' ) ? '' : 'alternate';
					?>
					<tr class="<?php echo $class; ?>">
						<td class="name column-name"><?php echo esc_html($group['name']); ?></td>
						<td class="name column-name"><?php echo esc_html($group['label']); ?></td>
						<td class="name column-name">
							<?php if ( is_array($group['blogs']) && !empty($group['blogs']) ) : ?>
								<ul class="ul-square" style="margin-top:0;margin-bottom:0;padding:0;">
									<?php foreach( $group['blogs'] as $blog ) : ?>
										<li><a href="<?php echo trailingslashit($blog->get_permalink()).'wp-admin/'; ?>"><?php echo $blog->get_language_label(); ?></a> (<?php echo $blog->get_permalink(); ?>)</li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
<?php endif;?>