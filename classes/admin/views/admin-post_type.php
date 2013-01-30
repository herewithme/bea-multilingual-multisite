<div id="bea_mm_messages" class="alert-message hidden"></div>
<div class="list-relations">
	<?php if ( $translation_factory -> have_translations() ) : ?>
		<ul>
			<?php while ( $translation_factory -> have_translations() ) :
				$translation_factory -> the_translation();
				
				// Skip current translation
				if ( $translation_factory->is_current_translation() ) {
					continue;
				}

				// Get translated id
				$current_id = $translation_factory->translation_exists() ? $translation_factory -> get_translation_id() : 0 ;
				$attrs = Bea_MM_Plugin::make_html_attrs( 
					array(
						'id' => 'bea-mm-translation-'.$translation_factory -> get_blog_id(),
						'data-blog_id' => $translation_factory -> get_blog_id(),
						'data-post_type' => $object->post_type,
						'data-nonce' => wp_create_nonce( 
							'bea-mm-search-'.$object->post_type.'-'.$translation_factory -> get_blog_id(),
							'bea-mm-search-'.$object->post_type.'-'.$translation_factory -> get_blog_id() 
						),
						'data-nonce-link' => wp_create_nonce( 
							'bea-mm-link-'.$object->post_type.'-'.$translation_factory -> get_blog_id(),
							'bea-mm-link-'.$object->post_type.'-'.$translation_factory -> get_blog_id() 
						),
						'data-nonce-unlink' => wp_create_nonce( 
							'bea-mm-unlink-'.$object->post_type.'-'.$translation_factory -> get_blog_id(),
							'bea-mm-unlink-'.$object->post_type.'-'.$translation_factory -> get_blog_id() 
						),
						'data-translated' => $current_id > 0 ? 'true' : 'false'
					)
				);
			?>
			<li <?php echo $attrs; ?>>
					<div class="spinner" ></div>
					<?php if( $current_id !== 0 ):
						switch_to_blog( $translation_factory -> get_blog_id() ); ?>
						<?php echo $translation_factory -> get_language_label( true ); ?> - <span class="controls"><a href="<?php echo get_edit_post_link( $current_id ); ?>"><?php echo get_the_title( $current_id ) ?></a> - <a href="#" class="edit-relation"><?php esc_html_e( 'Edit', 'bea-mm' ) ?></a> / <a href="#" class="del-item"><?php esc_html_e( 'Delete the relation', 'bea-mm' ) ?></span></a>
					<?php
						restore_current_blog();
					 else: ?>
						<?php echo $translation_factory -> get_language_label( true ); ?> - <span class="controls" ><a title="Insérer/modifier une relation" href="#" class="button add_relation"><?php esc_html_e( 'Add relation', 'bea-mm' ) ?></a></span>
					<?php endif; ?>
				<input type="hidden" value="<?php echo esc_attr( $current_id ); ?>" name="translations[<?php echo esc_attr( $translation_factory -> get_blog_id() ); ?>]" />
			</li>
		<?php endwhile; ?>
		</ul>
	<?php else: ?>
		<?php esc_html_e( 'No site to translate.' ); ?>
	<?php endif; ?>
</div>
<?php if( $translation_factory -> have_translations() ) : ?>
<div class="tools-relations">
	<div class="full-draft" id='bea_mm_create_all_drafts'>
		<?php
		$attrs = Bea_MM_Plugin::make_html_attrs( 
			array(
				'data-nonce' => wp_create_nonce( 'bea-mm-all-draft', 'bea-mm-all-draft-'.$object->post_type.'-'.$translation_factory -> get_blog_id().'-'.$object->ID ),
				'data-blog_id' => get_current_blog_id(),
				'data-post_type' => $object->post_type
			)
		);
		?>
		<button class="button button-primary add-draft" <?php echo $attrs; ?> type="button" ><?php esc_html_e( 'Create draft in all available languages', 'bea-mm' ) ?></button>
	</div>
	<div class="add-draft" id="bea_mm_create_drafts">
		<ul>
			<?php
			while ( $translation_factory -> have_translations() ) :
				$translation_factory -> the_translation();
				
				// Skip current translation
				if ( $translation_factory->is_current_translation() ) {
					continue;
				}
			?>
			<li>
				<label for='<?php echo esc_attr( 'bea-mm-draft-'.$translation_factory ->get_blog_id() ); ?>'> <?php echo esc_attr( $translation_factory ->get_language_label( true ) ); ?> </label>
				<input id="<?php echo esc_attr( 'bea-mm-draft-'.$translation_factory ->get_blog_id() ); ?>" type="checkbox" value="<?php echo esc_attr( $translation_factory ->get_blog_id() ); ?>">
			</li>
			<?php endwhile; ?>
		</ul>
		<?php
		$attrs = Bea_MM_Plugin::make_html_attrs( 
			array(
				'data-nonce' => wp_create_nonce( 'bea-mm-selected-drafts-'.get_current_blog_id(), 'bea-mm-selected-drafts' ),
				'data-post_type' => $object->post_type
			)
		);
		?>
		<input class="button button-primary add-draft" <?php echo $attrs; ?> type="button" value="<?php esc_attr_e( 'Create draft in selected languages', 'bea-mm' ) ?>">
	</div>
</div>
<?php endif; ?>
	<?php
	$attrs = Bea_MM_Plugin::make_html_attrs( 
		array(
			'data-blog_id' => get_current_blog_id(),
			'data-post_type' => $object->post_type
		)
	);
	?>
<div id="bea-mm-link" tabindex="-1" class='hidden' <?php echo $attrs; ?> >
	<div id="link-selector">
		<div id='bea-mm-link-options' >
			<input type="hidden" id="bea-mm-id" value="" />
		</div>
		<div id="bea-mm-search-panel">
			<div class="link-search-wrapper">
				<label>
					<span class="search-label"><?php _e( 'Search' ); ?></span>
					<input type="search" id="bea-mm-search-field" class="link-search-field" autocomplete="off" />
					<span class="spinner"></span>
				</label>
			</div>
			<div id="bea-mm-search-results" class="query-results">
				<ul></ul>
				<div class="river-waiting">
					<span class="spinner"></span>
				</div>
			</div>
			<div id="bea-mm-most-recent-results" class="query-results">
				<div class="query-notice"><em><?php _e( 'No search term specified. Showing recent items.' ); ?></em></div>
				<ul></ul>
				<div class="river-waiting">
					<span class="spinner"></span>
				</div>
			</div>
		</div>
	</div>
	<div class="submitbox">
		<div id="bea-mm-link-update">
			<input type="submit" value="<?php esc_attr_e( 'Add relation', 'bea-mm' ); ?>" class="button-primary" id="bea-mm-link-submit" name="bea-mm-link-submit">
		</div>
		<div id="bea-mm-link-cancel">
			<a class="submitdelete deletion" href="#"><?php _e( 'Cancel' ); ?></a>
		</div>
	</div>
</div>
<script id="bea-mm-tpl-add" type="text/html" >
	<a href="#" class="button add_relation"><?php esc_html_e( 'Add relation', 'bea-mm' ) ?></a>
</script>
<script id="bea-mm-tpl-edit" type="text/html" >
	<a href="<%= edit_link %>"><%= title %></a> - <a href="#" class="edit-relation"><?php esc_html_e( 'Edit', 'bea-mm' ) ?></a> / <a href="#" class="del-item"><?php esc_html_e( 'Delete the relation', 'bea-mm' ) ?></a>
</script>

<script id="bea-mm-search-line" type="text/html" >
	<% alt = false; _.each(results, function(line) {
		classes = alt ? 'alternate' : '';
		classes += line.title ? '' : ' no-title'; 
	%> 
		<li data-id="<%= line.ID %>" class="<%= classes %>"><span class="item-title"><%= line.title ? line.title : bea_mm_linkL10n.noTitle %></span> <span class="item-info"><%= line.info %></span></li> <% 
	alt = ! alt});
	;
	 %>
</script>
<script id="bea-mm-search-line-empty" type="text/html" >
	 <li class="unselectable"><span class="item-title"><em><%= bea_mm_linkL10n.noMatchesFound %></em></span></li>
</script>