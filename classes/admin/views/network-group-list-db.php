<div class="wrap">
	<h3><?php _e('Registred translation groups by network settings', 'bea-mm'); ?></h3>
	
	<form action="<?php self::get_admin_url(); ?>" method="post">
		<?php if ( is_array($db_groups) && !empty($db_groups) ) : ?>
			<p><?php _e('These groups were added by this page, you can edit and delete them. To delete or edit a site connected to a group, you must go to the network edit page.', 'bea-mm'); ?></p>
			<table class="widefat">
				<thead>
					<tr>
						<th class="manage-column"><?php _e('Delete?', 'bea-mm'); ?></th>
						<th scope="col"><?php _e('Name', 'bea-mm'); ?></th>
						<th scope="col"><?php _e('Label', 'bea-mm'); ?></th>
						<th scope="col"><?php _e('Websites', 'bea-mm'); ?></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th class="manage-column"><?php _e('Delete?', 'bea-mm'); ?></th>
						<th scope="col"><?php _e('Name', 'bea-mm'); ?></th>
						<th scope="col"><?php _e('Label', 'bea-mm'); ?></th>
						<th scope="col"><?php _e('Websites', 'bea-mm'); ?></th>
					</tr>
				</tfoot>
				<tbody id="the-list" class="list:groups">
					<?php
					$class = 'alternate';
					$i = 0;
					foreach ( (array) $db_groups as $group ) :
						$i++;
						$class = ( $class == 'alternate' ) ? '' : 'alternate';
						?>
						<tr class="<?php echo $class; ?>">
							<td class="manage-column column-cb check-column" style="text-align:center;"><input name="groupsites[<?php echo $group['name']; ?>][delete]" type="checkbox" value="1" /></td>
							<td class="name column-name"><input type="text" class="widefat" name="groupsites[<?php echo $group['name']; ?>][name]" value="<?php echo esc_attr(stripslashes($group['name'])); ?>" readonly="readonly" /></td>
							<td class="name column-name"><input type="text" class="widefat" name="groupsites[<?php echo $group['name']; ?>][label]" value="<?php echo esc_attr(stripslashes($group['label'])); ?>" /></td>
							<td class="name column-name">
								<?php if ( is_array($group['blogs']) && !empty($group['blogs']) ) : ?>
									<ul class="ul-square" style="margin-top:0;margin-bottom:0;padding:0;">
										<?php foreach( $group['blogs'] as $blog ) : ?>
											<li><a href="<?php echo $blog->get_permalink(); ?>"><?php echo $blog->get_language_label(); ?></a></li>
										<?php endforeach; ?>
									</ul>
								<?php else : ?>
									<p><?php printf(__('Add a site to this group by editing site into the <a href="%s">sites management page</a>.', 'bea-mm'), network_admin_url('sites.php')); ?></p>
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			
			<p class="submit">
				<?php wp_nonce_field( 'save-translation-group' ); ?>
				<input class="button-primary" name="save-translation-group" type="submit" value="<?php _e('Save groups', 'bea-mm'); ?>" />
			</p>
		</form>
	<?php else : ?>
		<p><?php _e('You do not have a translation groups.', 'bea-mm'); ?></p>
		<p><?php _e('To start, connect the two sites contents of your network, you must create a translation group. Groups generally translations correspond to the name of a country. This country will usually several translations. Once the group is created, you can add an site.', 'bea-mm'); ?></p>
	<?php endif;?>
</div>