'use strict';
module.exports = function( grunt ) {

  // load all tasks
  require( 'load-grunt-tasks' )( grunt, { scope: 'devDependencies' } );

  grunt.config.init( {
    pkg: grunt.file.readJSON( 'package.json' ),

    dirs: {
      css: '/assets/css',
      js: '/assets/js'
    },
		// Generate POT files.
		makepot: {
			options: {
				type: 'wp-plugin',
				domainPath: 'languages',
				potHeaders: {
					'report-msgid-bugs-to': 'https://github.com/strong-testimonials/strong-testimonials/issues',
					'language-team': 'LANGUAGE <EMAIL@ADDRESS>'
				}
			},
			frontend: {
				options: {
					potFilename: 'strong-testimonials.pot',
					exclude: [
						'node_modules/.*',
						'tests/.*',
						'tmp/.*'
					],
					processPot: function ( pot ) {
						return pot;
					}
				}
			}
		},

		po2mo: {
			files: {
				src: '<%= dirs.lang %>/*.po',
				expand: true
			}
		},
    checktextdomain: {
      standard: {
        options: {
          text_domain: [ 'strong-testimonials' ], //Specify allowed domain(s)
          create_report_file: 'true',
          keywords: [ //List keyword specifications
            '__:1,2d',
            '_e:1,2d',
            '_x:1,2c,3d',
            'esc_html__:1,2d',
            'esc_html_e:1,2d',
            'esc_html_x:1,2c,3d',
            'esc_attr__:1,2d',
            'esc_attr_e:1,2d',
            'esc_attr_x:1,2c,3d',
            '_ex:1,2c,3d',
            '_n:1,2,4d',
            '_nx:1,2,4c,5d',
            '_n_noop:1,2,3d',
            '_nx_noop:1,2,3c,4d'
          ]
        },
        files: [
          {
            src: [
              '**/*.php',
              '!**/node_modules/**',
            ], //all php
            expand: true
          } ]
      }
    },
    cssmin: {
      target: {
        files: [
          {
            expand: true,
            cwd: 'assets/css',
            src: [ '*.css', '!*.min.css' ],
            dest: 'assets/css',
            ext: '.min.css'
          } ]
      }
    },

    uglify: {
      jsfiles: {
        files: [ {
          expand: true,
          cwd   : 'public/js/',
          src   : [
            '*.js',
            '**/*.js',
            '!*.min.js',
            '!**/*.min.js',
          ],
          dest  : 'public/js/',
          ext   : '.min.js'
        } ]
      },
    },

    clean: {
      css: [ 'assets/css/*.min.css', '!assets/css/jquery-ui.min.css' ],
      js: [ 'public/js/*.min.js', 'public/js/**/*.min.js' ],
      init: {
        src: ['build/']
      },
    },
    copy: {
      build: {
        expand: true,
        src: [
          '**',
          '!node_modules/**',
          '!vendor/**',
          '!build/**',
          '!readme.md',
          '!README.md',
          '!phpcs.ruleset.xml',
          '!Gruntfile.js',
          '!package.json',
          '!package-lock.json',
          '!composer.json',
          '!composer.lock',
          '!set_tags.sh',
          '!postcss.config.js',
          '!webpack.config.js',
          '!**.zip',
          '!SECURITY.md',
          '!nbproject/**' ],
        dest: 'build/'
      }
    },

    compress: {
      build: {
        options: {
          pretty: true,                           // Pretty print file sizes when logging.
          archive: '<%= pkg.name %>-<%= pkg.version %>.zip'
        },
        expand: true,
        cwd: 'build/',
        src: ['**/*'],
        dest: '<%= pkg.name %>/'
      }
    },

  } );

  grunt.loadNpmTasks( 'grunt-contrib-clean' );
  grunt.loadNpmTasks( 'grunt-contrib-cssmin' );
  grunt.loadNpmTasks( 'grunt-contrib-uglify-es' );

  grunt.registerTask( 'textdomain', [
    'checktextdomain'
  ] );
  grunt.registerTask( 'mincss', [  // Minify CSS
    'clean:css',
    'cssmin'
  ] );
  // Build task
  grunt.registerTask( 'build-archive', [
    'clean:init',
    'copy',
    'compress:build',
    'clean:init'
  ] );
};