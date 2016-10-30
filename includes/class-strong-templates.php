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
			'plugin'       => array(
				'path' => WPMTST_TPL,
				'uri'  => WPMTST_TPL_URI, ),
			'parent_theme' => array(
				'path' => get_template_directory() . '/' . WPMTST,
				'uri'  => get_template_directory_uri() . '/' . WPMTST, ),
			'child_theme'  => array(
				'path' => get_stylesheet_directory() . '/' . WPMTST,
				'uri'  => get_stylesheet_directory_uri() . '/' . WPMTST,
			),
		);

		/**
		 * Filter the search paths.
		 */
		$search = apply_filters( 'wpmtst_template_search_paths', $search );

		$files = array();
		foreach ( $search as $key => $bases ) {
			$new_files = $this->scandir_top( $bases['path'], $bases['uri'], $type );
			if ( is_array( $new_files ) ) {
				$files = array_merge( $files, $new_files );
			}
		}

		/**
		 * Filter the list of found templates.
		 */
		$files = array_filter( apply_filters( 'wpmtst_templates_found', array_filter( $files ) ) );

		// Sort by name
		uasort( $files, array( $this, 'sort_array_by_name' ) );

		// Sort by key
		//ksort( $files );

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
		if ( !$types )
			return $this->templates;

		$types = (array) $types;

		$filtered = array();
		foreach( $this->templates as $key => $template ) {
			// Thanks http://stackoverflow.com/a/4260168/51600
			//$filtered[$group] = array_intersect_key( $templates, array_flip( $types ) );
			// Instead:
			if ( in_array( $template['type'], $types ) ) {
				$filtered[ $key ] = $template;
			}
		}

		return array_filter( $filtered );
	}

	/**
	 * Get template attribute.
	 *
	 * Array
	 * 	(
	 * [stylesheet] => http://strong.dev/wp-content/plugins/strong-testimonials/templates/default-dark/content.css
	 * [template] => M:\wp\work\strong\master\wp-content\plugins\strong-testimonials/templates/default-dark/content.php
	 * [name] => Default Dark
	 * [description] => A version of the default template for dark themes.
	 * [deps] =>
	 * [force] =>
	 * [group] => default-dark
	 * [type] => content
	 * )
	 *
	 * @param           $atts
	 * @param string    $part
	 * @param bool|true $use_default
	 *
	 * @return string
	 */
	public function get_template_attr( $atts, $part = 'template', $use_default = true ) {

		// establish default
		$default_template = isset( $atts['form'] ) ? 'default:form' : 'default:content';
		$default_template = apply_filters( 'wpmtst_default_template', $default_template, $atts );

		$template = isset( $atts['template'] ) ? $atts['template'] : $default_template;

		// check existence
		$found = in_array( $template, array_keys( $this->templates ) );

		$template_info = false;

		if ( $found ) {
			$template_info = $this->templates[ $template ];
		} elseif ( $use_default ) {
			$template_info = $this->templates[ $default_template ];
		}

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
		if ( !isset( $a['name'] ) || !isset( $b['name'] ) )
			return 0;

		if ( $a['name'] == $b['name'] )
			return 0;

		return ( $a['name'] < $b['name'] ) ? -1 : 1;
	}

}

endif;
