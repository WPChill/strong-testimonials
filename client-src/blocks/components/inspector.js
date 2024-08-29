/* eslint-disable no-undef */
/**
 * WordPress dependencies
 */
const { __ } = wp.i18n;
const { Component, Fragment } = wp.element;
const { InspectorControls } = wp.blockEditor;
const { SelectControl, Button, PanelBody, PanelRow } = wp.components;

/**
 * Inspector controls
 */
export default class Inspector extends Component {
	render() {
		const { attributes, setAttributes, onIdChange, selectOptions } =
			this.props;
		const { id, views, testimonials } = attributes;
		return (
			<Fragment>
				<InspectorControls>
					<PanelBody
						title={ __( 'View Settings' ) }
						initialOpen={ true }
					>
						{ stViews.views.length === 0 && (
							<Fragment>
								<p>
									{ __(
										"You don't seem to have any views."
									) }
								</p>
								<Button
									href={
										stViews.adminURL +
										'edit.php?post_type=wpm-testimonial&page=testimonial-views&action=add'
									}
									target="_blank"
									isDefault
								>
									{ __( 'Add New View' ) }
								</Button>
							</Fragment>
						) }

						{
							// eslint-disable-next-line no-undef
							stViews.views.length > 0 && (
								<Fragment>
									<SelectControl
										label={ __( 'Select View' ) }
										key={ id }
										value={ id }
										options={ selectOptions }
										onChange={ ( value ) =>
											onIdChange( parseInt( value ) )
										}
									/>
									{ id !== 0 && (
										<Button
											target="_blank"
											href={
												// eslint-disable-next-line no-undef
												stViews.adminURL +
												'edit.php?post_type=wpm-testimonial&page=testimonial-views&action=edit&id=' +
												id
											}
											isSecondary
										>
											{ __( 'Edit View' ) }
										</Button>
									) }
								</Fragment>
							)
						}
					</PanelBody>
				</InspectorControls>
			</Fragment>
		);
	}
}
