<?php
/**
 * Templates class.
 *
 * @since 1.25
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Strong_Templates' ) ) :

class Strong_Templates {

	/**
	 * @var array
	 */
	public $templates;

	public function __construct() {
		$this->templates = $this->find_templates();
	}

	/**
	 * @param null $type
	 *
	 * @return array
	 */
	public function find_templates( $type = null ) {

		$search = array(
			'child_theme'  => array(
				'source' => __( 'Child Theme', 'strong-testimonials' ),
				'path'   => get_stylesheet_directory() . '/' . WPMTST,
				'uri'    => get_stylesheet_directory_uri() . '/' . WPMTST,
				'order'  => 2,
			),
			'parent_theme' => array(
				'source' => __( 'Parent Theme', 'strong-testimonials' ),
				'path'   => get_template_directory() . '/' . WPMTST,
				'uri'    => get_template_directory_uri() . '/' . WPMTST,
				'order'  => 3,
			),
			'plugin'       => array(
				'source' => __( 'Default', 'strong-testimonials' ),
				'path'   => WPMTST_TPL,
				'uri'    => WPMTST_TPL_URI,
				'order'  => 4,
			),
		);

		/**
		 * Filter the search paths.
		 */
		$search = apply_filters( 'wpmtst_template_search_paths', $search );

		/**
		 * Insert order if necessary so custom templates appear first.
		 *
		 * @since 2.22
		 */
		foreach ( $search as $key => $where ) {
			if ( ! isset( $where['source'] ) ) {
				$search[ $key ]['source'] = __( 'Custom', 'strong-testimonials' );
			}
			if ( ! isset( $where['order'] ) ) {
				$search[ $key ]['order'] = 1;
			}
		}

		uasort( $search, array( $this, 'sort_array_by_order' ) );

		$files = array();
		foreach ( $search as $key => $bases ) {
			$new_files = $this->scandir_top( $bases['path'], $bases['uri'], $type );
			if ( is_array( $new_files ) && $new_files ) {
				uasort( $new_files, array( $this, 'sort_array_by_name' ) );
				$files[ $bases['source'] ] = $new_files;
			}
		}

		// Filter the list of found templates
		$files = array_filter( apply_filters( 'wpmtst_templates_found', array_filter( $files ) ) );

		return $files;
	}

	/**
	 * @param null $types
	 *
	 * @return array
	 */
	public function get_templates( $types = null ) {
		return $this->get_templates_by_type( $types );
	}

	/**
	 * @param null $types
	 *
	 * @return array
	 */
	public function get_templates_by_type( $types = null ) {
		if ( ! $types )
			return $this->templates;

		$types    = (array) $types;
		$filtered = array();

		foreach ( $this->templates as $source => $source_templates ) {
			foreach ( $source_templates as $key => $template ) {
				if ( isset( $template['type'] ) && in_array( $template['type'], $types ) ) {
					$filtered[ $source ][ $key ] = $template;
				}
			}
		}

		return array_filter( $filtered );
	}

	/**
	 * Return list of templates by key.
	 *
	 * @return array
	 */
	public function get_template_keys() {
		$template_keys = array();
		foreach ( $this->templates as $source => $source_templates ) {
			$template_keys = array_merge( $template_keys, array_keys( $source_templates ) );
		}

		return $template_keys;
	}

	/**
	 * Get template attribute.
	 *
	 * @param           $atts
	 * @param string    $part
	 * @param bool|true $use_default
	 *
	 * @return string
	 */
	public function get_template_attr( $atts, $part = 'template', $use_default = true ) {
		// Build a list of potential template part names.
		$template_search = array();

		// [1]
		/*
		 * Divi Builder compatibility. Everybody has to be special.
		 * @since 2.22.0
		 * TODO Abstract this.
		 */
		if ( 'stylesheet' == $part ) {
			if ( isset( $atts['divi_builder'] ) && $atts['divi_builder'] && wpmtst_divi_builder_active() ) {
				$template_search[] = $atts['template'] .= '-divi';
			}
		}

		// [2]
		if ( isset( $atts['template'] ) ) {
			$template_search[] = $atts['template'];
		}

		// [3]
		if ( $use_default ) {
			$template_search[] = apply_filters( 'wpmtst_default_template', 'default:content', $atts );
		}

		// Search list of already found template files. Stop at first match.

		$template_info = false;
		foreach ( $template_search as $template_key ) {
			foreach ( $this->templates as $source => $source_templates ) {
				if ( isset( $source_templates[ $template_key ] ) ) {
					$template_info = $source_templates[ $template_key ];
					break 2;
				}
			}
		}

		// Return the requested part (name, template, stylesheet,etc.)

		if ( $template_info && isset( $template_info[ $part ] ) && $template_info[ $part ] ) {
			return $template_info[ $part ];
		}

		return '';
	}

	/**
	 * @param $path
	 * @param $uri
	 * @param $type
	 *
	 * @return array|bool
	 */
	public function scandir_top( $path, $uri, $type ) {

		if ( !is_dir( $path ) )
			return false;

		$files = array();

		$groups = scandir( $path );
		foreach ( $groups as $group ) {

			if ( '.' == $group[0] )
				continue;

			if ( is_dir( $path . '/' . $group ) ) {

				// find files in this directory
				$files_found = $this->scandir( $group, $path, $uri, array( 'php', 'css', 'js' ), $type );

				// directory becomes group name
				if ( $files_found ) {
					foreach ( $files_found as $template_type => $template_files ) {
						$new_key = $group . ':' . $template_type;
						$template_files['group'] = $group;
						$template_files['type']  = $template_type;
						$files[ $new_key ]       = $template_files;
					}
				}

			}
		}

		return $files;
	}

	/**
	 * @param      $group
	 * @param      $path
	 * @param      $uri
	 * @param null $extensions
	 * @param      $type
	 *
	 * @return array|bool
	 */
	public function scandir( $group, $path, $uri, $extensions = null, $type ) {

		if ( !is_dir( $path . '/' . $group ) )
			return false;

		if ( $extensions )
			$extensions = (array) $extensions;

		$files = array();

		// Template header tags
		$tags = apply_filters( 'wpmtst_template_header_tags', array(
			'name'        => 'Template Name',
			'description' => 'Description',
			'deps'        => 'Scripts',  // registered scripts
			'styles'      => 'Styles',   // registered styles or fonts
			'force'       => 'Force',    // dependent options
		) );

		// Bail if requested template type not found
		if ( $type && !file_exists( $path . '/' . $group . '/' . $type . '.php' ) )
			return false;

		// Process the files
		$results = scandir( $path . '/' . $group);
		foreach ( $results as $result ) {

			if ( '.' == $result[0] )
				continue;

			if ( is_dir( $path . '/' . $group . '/' . $result ) )
				continue;

			if ( !$extensions || preg_match( '~\.(' . implode( '|', $extensions ) . ')$~', $result ) ) {

				$filename = pathinfo( $result, PATHINFO_FILENAME );
				$ext      = pathinfo( $result, PATHINFO_EXTENSION );

				// Template, stylesheet, script, or other?
				switch ( $ext ) {
					case 'php':
						$key  = 'template';
						$base = $path;
						break;
					case 'css':
						$key  = 'stylesheet';
						$base = $uri;
						break;
					case 'js':
						$key  = 'script';
						$base = $uri;
						break;
					default:
						$key  = '';
						$base ='';
				}

				if ( $key )
					$files[ $filename ][$key] = $base . '/' . $group . '/' . $result;

				// Process tags
				if ( 'template' == $key ) {

					$file_data = get_file_data( $path . '/' . $group . '/' . $result, $tags );

					foreach ( $tags as $tag => $label ) {

						if ( 'name' == $tag ) {
							// Get the name
							if ( isset( $file_data['name'] ) && $file_data['name'] ) {
								$files[ $filename ]['name'] = $file_data['name'];
							}
							else {
								// Use the directory name
								$files[ $filename ]['name'] = ucwords( str_replace( array( '_', '-' ), ' ', basename( $path ) ) );
							}
						}
						else {
							$files[ $filename ][$tag] = $file_data[$tag];
						}

					}

					// Each template type may have its own screenshot
					if ( file_exists( $path . '/' . $group . '/' . $filename . '.png' ) ) {
						$files[ $filename ]['screenshot'] = $uri . '/' . $group. '/' . $filename . '.png';
					}
					elseif ( file_exists( $path . '/' . $group. '/' . $filename . '.jpg' ) ) {
						$files[ $filename ]['screenshot'] = $uri . '/' . $group. '/' . $filename . '.jpg';
					}

				}
			}

		}

		ksort( $files );
		return $files;
	}

	/**
	 * @param $a
	 * @param $b
	 *
	 * @return int
	 */
	public function sort_array_by_name( $a, $b ) {
		if ( ! isset( $a['name'] ) ) {
			$a['name'] = '';
		}
		if ( ! isset( $b['name'] ) ) {
			$b['name'] = '';
		}

		return strcmp( $a['name'], $b['name'] );
	}

	/**
	 * @param $a
	 * @param $b
	 *
	 * @return int
	 */
	public function sort_array_by_order( $a, $b ) {
		if ( ! isset( $a['order'] ) || ! isset( $b['order'] ) )
			return 0;

		if ( $a['order'] == $b['order'] )
			return 0;

		return ( $a['order'] < $b['order'] ) ? -1 : 1;
	}

	/**
	 * @param $a
	 * @param $b
	 *
	 * @return int
	 */
	public function sort_array_by_order_name( $a, $b ) {
		if ( ! isset( $a['name'] ) || ! isset( $b['name'] ) )
			return 0;

		if ( $a['order'] == $b['order'] )
			return strcasecmp( $a['name'], $b['name'] );

		return ( $a['order'] < $b['order'] ) ? -1 : 1;
	}

}

endif;
