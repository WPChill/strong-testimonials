<?php

/**
 * Class Strong_Testimonials_Exporter
 *
 * exports testimonials along with their featured media
 *
 * @since 2.36
 */
class Strong_Testimonials_Exporter {

	public $args       = array();
	public $query_done = false;

	public function __construct() {
		add_action( 'export_wp', array( $this, 'export_wp' ), 10, 1 );
		add_filter( 'export_query', array( $this, 'export_query' ), 10, 1 );
	}

	public function export_wp( $args ) {
		$this->args = $args;
		add_filter( 'query', array( $this, 'export_query_filter' ), 10, 1 );
	}

	public function export_query_filter( $query ) {

		global $wpdb;
		if ( false === $this->query_done && 0 === strpos( $query, "SELECT ID FROM {$wpdb->posts} " ) ) {
			$this->query_done = true;
			remove_filter( 'query', array( $this, 'export_query_filter' ), 10 );
			$query = apply_filters( 'export_query', $query );
		}
		return $query;
	}

	public function export_query( $query ) {
		global $wpdb;

		if ( isset( $this->args['content'] ) && 'wpm-testimonial' === $this->args['content'] ) {

			$attachments = $wpdb->get_results( "SELECT ID, guid, post_parent FROM {$wpdb->posts} WHERE post_type = 'attachment'", OBJECT_K );
			if ( empty( $attachments ) ) {
				return $query;
			}

			$ids = array();

			// get attachments who are post thumbnails
			$posts = $wpdb->get_col( $query );
			if ( $posts ) {
				$ids = $wpdb->get_col( sprintf( "SELECT meta_value FROM {$wpdb->postmeta} WHERE meta_key = '_thumbnail_id' AND post_id IN(%s)", implode( ',', $posts ) ) );
			}

			// get atachments who have a post parent.
			foreach ( $attachments as $id => $att ) {
				if ( in_array( $att->post_parent, $posts ) ) {
					$ids[] = $id;
				}
			}

			$ids = array_unique( $ids );
			if ( count( $ids ) === 0 ) {
				return $query;
			}

			if ( 0 === strpos( $query, "SELECT ID FROM {$wpdb->posts} INNER JOIN {$wpdb->term_relationships} " ) ) {
				// replace INNER JOIN with LEFT JOIN.
				$query = str_replace( "SELECT ID FROM {$wpdb->posts} INNER JOIN {$wpdb->term_relationships} ", "SELECT ID FROM {$wpdb->posts} LEFT JOIN {$wpdb->term_relationships} ", $query );
			}
			$query .= sprintf( " OR {$wpdb->posts}.ID IN (%s) ", implode( ',', $ids ) );

		}
		return $query;
	}


}

new Strong_Testimonials_Exporter();

