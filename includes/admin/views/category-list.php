<div class="view-category-list-panel">
	<div class="fc-search-wrap"></div>
	<ul class="view-category-list">
		<?php $args = array(
			'descendants_and_self'  => 0,
			'selected_cats'         => $view_cats_array,
			'popular_cats'          => false,
			'walker'                => new Walker_Testimonial_Category_Checklist(),
			'taxonomy'              => 'wpm-testimonial-category',
			'checked_ontop'         => true,
		); ?>
		<?php wp_terms_checklist( 0, $args ); ?>
	</ul>
</div>
