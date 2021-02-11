import Edit from './components/edit';

/**
 * Import wp deps
 */

const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;

class StrongTestimonialView {

    constructor() {
        this.registerBlock();
    }

    registerBlock() {

        this.blockName = 'strongtestimonials/view';

        this.blockAttributes = {               
                id: {
                    type: 'number',
                    default: 0,
                },
                mode: {
                    type: 'string',
                    default: 'display',
                }
        };

        registerBlockType( this.blockName , {
            title: 'Strong Testimonial View',
            description: __( 'Render ST View', 'strong-testimonials'),
            icon: 'editor-quote',
            category: 'common',
            supports: {
                html: false,
                customClassName: false,
            },

            attributes: this.blockAttributes,
            edit: Edit,
            save: () => {
                return null;
            },
        });
    }
}

let strongTestimonialsView = new StrongTestimonialView();