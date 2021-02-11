import Inspector from './inspector';

/**
 * Wordpress deps
 */

const { __ } = wp.i18n;
const { Component, Fragment } = wp.element;
const { withSelect } = wp.data;
const { SelectControl, Spinner, Toolbar, Button } = wp.components;
const { BlockControls } = wp.blockEditor;

export default class StrongTestimonialViewEdit extends Component {
	constructor(props) {
		super(...arguments);
		this.props.attributes.status = 'ready';
		this.props.attributes.views = st_views.views;
	}

	onIdChange(id) {
		this.props.setAttributes({ status: 'ready', id: id, views: st_views.views });
	}

	selectOptions() {
		let options = [{ value: 0, label: __('None')}];

		st_views.views.forEach(function(view) {
			options.push({ value: view.id, label: view.name });
		});

		return options;
	}

	render() {
		const { attributes, setAttributes } = this.props;
		const { id, views, status, testimonials, mode } = attributes;
		const blockControls = (
			<BlockControls>
				{views.length > 0 && (
					<Toolbar>
						<Button label={__('Edit View')} icon="edit" target="_blank" />
					</Toolbar>
				)}
			</BlockControls>
		);
		if (status === 'loading') {
			return [
				<Fragment>
					<div className="st-block-preview">
						<div className="st-block-preview__content">
							<div className="st-block-preview__logo"> </div>
							<Spinner />
						</div>
					</div>
				</Fragment>
			];
		}

		return [
			<Fragment>
				<Inspector onIdChange={(id) => this.onIdChange(id)} {...this.props} />
				<div className="st-block-preview">
					<div class="st-block-preview__content">
						<div className="st-block-preview__logo" />
						{views.length === 0 && (
							<Fragment>
								<h6>{__("You don't seem to have any views.")}</h6>
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
						{st_views.views.length > 0 && (
							<Fragment>
								<SelectControl
									label="Select a view:"
									className="st-view-select"
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
										{__('Edit Settings')}
									</Button>
								)}
							</Fragment>
						)}
					</div>
				</div>
			</Fragment>
		];
	}
}
