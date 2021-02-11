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
	constructor(props) {
		super(...arguments);
	}

	selectOptions() {
		let options = [ { value: 0, label: __('None') } ];

		this.props.attributes.views.forEach(function(view) {
			options.push({ value: view.id, label: view.name });
		});

		return options;
	}

	render() {
		const { attributes, setAttributes } = this.props;
		const { id, views, testimonials } = attributes;
		return (
			<Fragment>
				<InspectorControls>
					<PanelBody title={__('View Settings')} initialOpen={true}>
						{views.length === 0 && (
							<Fragment>
								<p>{__("You don't seem to have any views.")}</p>
								<Button
									href={
										st_views.adminURL +
										'edit.php?post_type=wpm-testimonial&page=testimonial-views&action=add'
									}
									target="_blank"
									isDefault
								>
									{__('Add New View')}
								</Button>
							</Fragment>
						)}

						{views.length > 0 && (
							<Fragment>
								<SelectControl
									label={__('Select View')}
									value={id}
									options={this.selectOptions()}
									onChange={(id) => setAttributes({ id })}
								/>
								{id != 0 && (
									<Button
										target="_blank"
										href={
											st_views.adminURL +
											'edit.php?post_type=wpm-testimonial&page=testimonial-views&action=edit&id=' +
											id
										}
										isSecondary
									>
										{__('Edit View')}
									</Button>
								)}
							</Fragment>
						)}
					</PanelBody>
				</InspectorControls>
			</Fragment>
		);
	}
}
