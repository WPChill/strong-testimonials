<h2><?php _e( 'Let\'s Get Started', 'strong-testimonials' ); ?></h2>
<p class="lead-description"><?php _e( 'This plugin is different than others you may have tried.', 'strong-testimonials' ); ?></p>

<div class="feature-section two-col">
    <div class="col">
        <h3><?php _e('How to Add the Form', 'strong-testimonials' ); ?></h3>
        <p>1. <?php printf( __( '<a href="%s">Check the custom fields</a>. The default set of fields are designed to suit most situations. Add or remove fields as you see fit.', 'strong-testimonials' ), admin_url( 'edit.php?post_type=wpm-testimonial&page=testimonial-fields' ) ); ?>
        </p>
        <p>2. <?php printf( __( 'Create a <a href="%s">view</a>.', 'strong-testimonials' ), admin_url( 'edit.php?post_type=wpm-testimonial&page=testimonial-views' ) ); ?>
            <?php _e( 'A view is simply a group of settings with an easy-to-use editor.', 'strong-testimonials' ); ?>
	        <?php _e( 'Select <strong>Form</strong> mode.', 'strong-testimonials' ); ?>
        </p>
        <p><?php _e( '3. Add the view using its unique shortcode (which you will see after you save it) or the Strong Testimonials widget.', 'strong-testimonials' ); ?></p>
    </div>

    <div class="col">
        <h3><?php _e( 'How to Display Your Testimonials', 'strong-testimonials' ); ?></h3>
        <p>1. <?php printf( __( '<a href="%s">Enter your testimonials</a> if necessary. The plugin will not read existing testimonials you may have from another plugin or theme. It will not import testimonials.', 'strong-testimonials' ), admin_url( 'edit.php?post_type=wpm-testimonial' ) ); ?></p>
        <p>2. <?php printf( __( 'Create a <a href="%s">view</a>.', 'strong-testimonials' ), admin_url( 'edit.php?post_type=wpm-testimonial&page=testimonial-views' ) ); ?>
            <?php _e( 'A view is simply a group of settings with an easy-to-use editor.', 'strong-testimonials' ); ?>
	        <?php _e( 'Select <strong>Display</strong> mode.', 'strong-testimonials' ); ?>
        </p>
        <p><?php _e( '3. Add the view using its unique shortcode (which you will see after you save it) or the Strong Testimonials widget.', 'strong-testimonials' ); ?></p>
    </div>

    <div class="col">
        <h3><?php _e( 'How to Add a Slideshow', 'strong-testimonials' ); ?></h3>
        <p>1. <?php printf( __( '<a href="%s">Enter your testimonials</a> if necessary. The plugin will not read existing testimonials you may have from another plugin or theme. It will not import testimonials.', 'strong-testimonials' ), admin_url( 'edit.php?post_type=wpm-testimonial' ) ); ?></p>
        <p>2. <?php printf( __( 'Create a <a href="%s">view</a>.', 'strong-testimonials' ), admin_url( 'edit.php?post_type=wpm-testimonial&page=testimonial-views' ) ); ?>
			<?php _e( 'A view is simply a group of settings with an easy-to-use editor.', 'strong-testimonials' ); ?>
	        <?php _e( 'Select <strong>Slideshow</strong> mode.', 'strong-testimonials' ); ?>
        </p>
        <p><?php _e( '3. Add the view using its unique shortcode (which you will see after you save it) or the Strong Testimonials widget.', 'strong-testimonials' ); ?></p>
    </div>

    <div class="col">
        <h3><?php _e( 'How to Display the Number of Testimonials', 'strong-testimonials' ); ?></h3>
        <p><?php printf( __( 'Use the %s shortcode.', 'strong-testimonials' ), '<code>&#91;testimonial_count&#93;</code>' ); ?>
			<?php _e( 'For example:', 'strong-testimonials' ); ?></p>
        <p><span class="code"><?php printf( __( 'Read some of our %s testimonials!', 'strong-testimonials' ), '&#91;testimonial_count&#93;' ); ?></span></p>
        <p><?php printf( __( 'To count for a specific category, add the %s attribute with the category slug.', 'strong-testimonials' ), '<code>category</code>' ); ?>
			<?php _e( 'For example:', 'strong-testimonials' ); ?></p>
        <p><span class="code"><?php printf( __( 'Here\'s what %s local clients say', 'strong-testimonials' ), '&#91;testimonial_count category="local"&#93;' ); ?></span></p>
    </div>

    <div class="col">
        <h3><?php _e( 'How to Translate', 'strong-testimonials' ); ?></h3>
        <p><?php _e( 'Strong Testimonials is compatible with WPML, Polylang and WP Globus.', 'strong-testimonials' ); ?></p>
        <p><?php _e( 'In WPML and Polylang, domains are added to the <strong>String Translation</strong> pages. Those domains encompass the form fields, the form messages, the notification email, and the "Read more" link text in your views. They are updated automatically when any of those settings change.', 'strong-testimonials' ); ?></p>
    </div>
</div>

<div class="feature-section three-col">
    <div class="col">
        <h3><?php _e( 'Support', 'strong-testimonials' ); ?></h3>
        <?php
        $links = array();

        $links[] = sprintf( '<a href="%s" target="_blank">%s</a>',
            esc_url( 'http://wordpress.org/support/plugin/strong-testimonials' ),
            __( 'Use the public support forum', 'strong-testimonials' ) );

        $links[] = __( 'or', 'strong-testimonials' ) . ' ' . sprintf( '<a href="%s" target="_blank">%s</a>',
            esc_url( 'https://support.strongplugins.com' ),
            __( 'submit a private support ticket', 'strong-testimonials' ) );

        $links[] = __( 'or', 'strong-testimonials' ) . ' ' . sprintf( '<a href="%s" target="_blank">%s</a>',
            esc_url( 'https://strongplugins.com/contact/' ),
            __( 'contact the developer', 'strong-testimonials' ) );
        ?>
        <ul>
            <?php foreach ( $links as $link ) : ?>
            <li><?php echo $link; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="col">
        <h3><?php _e( 'Tutorials', 'strong-testimonials' ); ?></h3>
        <?php
        $links = array();

        $links[] = sprintf( '<a href="%s" target="_blank">%s</a>',
            esc_url( 'https://support.strongplugins.com/article/troubleshooting-strong-testimonials/' ),
            __( 'Troubleshooting', 'strong-testimonials' ) );

        $links[] = sprintf( '<a href="%s" target="_blank">%s</a>',
            esc_url( 'https://support.strongplugins.com/article/youtube-twitter-instagram-strong-testimonials/' ),
            __( 'How to add YouTube or Twitter', 'strong-testimonials' ) );

        $links[] = sprintf( '<a href="%s" target="_blank">%s</a>',
            esc_url( 'https://support.strongplugins.com/article/custom-css-strong-testimonials/' ),
            __( 'Using custom CSS', 'strong-testimonials' ) );

        $links[] = sprintf( '<a href="%s" target="_blank">%s</a>',
            esc_url( 'https://support.strongplugins.com/article/enable-comments-strong-testimonials/' ),
            __( 'How to enable comments', 'strong-testimonials' ) );

        $links[] = sprintf( '<a href="%s" target="_blank">%s</a>',
            esc_url( 'https://support.strongplugins.com/article/complete-example-customizing-form/' ),
            __( 'How to customize the form', 'strong-testimonials' ) );
        ?>
        <ul>
            <?php foreach ( $links as $link ) : ?>
            <li><?php echo $link; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="col">
        <h3><?php _e( 'Demos', 'strong-testimonials' ); ?></h3>
        <?php
        $links = array();

        $links[] = sprintf( '<a href="%s" target="_blank">%s</a>',
            esc_url( 'https://strongdemos.com/strong-testimonials/display-examples/' ),
            __( 'Display examples', 'strong-testimonials' ) );

        $links[] = sprintf( '<a href="%s" target="_blank">%s</a>',
            esc_url( 'https://strongdemos.com/strong-testimonials/slideshow-examples/' ),
            __( 'Slideshow examples', 'strong-testimonials' ) );

        $links[] = sprintf( '<a href="%s" target="_blank">%s</a>',
            esc_url( 'https://strongdemos.com/strong-testimonials/form-examples/' ),
            __( 'Form examples', 'strong-testimonials' ) );
        ?>
        <ul>
            <?php foreach ( $links as $link ) : ?>
            <li><?php echo $link; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
