import Inspector from './inspector';

/**
 * Wordpress deps
 */

const { __ } = wp.i18n;
const { Component, Fragment, useEffect } = wp.element;
const { withSelect } = wp.data;
const { SelectControl, Spinner, Toolbar, Button } = wp.components;
const { BlockControls } = wp.blockEditor;

export const StrongTestimonialViewEdit = (props) => {
	const { attributes, setAttributes } = props;
	const { id, views, status, testimonials, mode } = attributes;

	useEffect(() => {
		setAttributes({ status: 'ready', views: st_views.views });

		if (id != 0) {
			onIdChange(id);
		}
	}, []);
	const onIdChange = (id) => {
		props.setAttributes({ status: 'ready', id: id });
	};

	const selectOptions = () => {
		let options = [ { value: 0, label: __('None') } ];

		st_views.views.forEach(function(view) {
			options.push({ value: view.id, label: view.name });
		});

		return options;
	};

	const blockControls = (
		<BlockControls>
			{st_views.views.length > 0 && (
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
			<Inspector onIdChange={(id) => onIdChange(id)} selectOptions={selectOptions()} {...props} />
			<div className="st-block-preview">
				<div class="st-block-preview__content">
					<div className="st-block-preview__logo" />
					{st_views.views.length === 0 && (
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
								key={id}
								value={id}
								options={selectOptions()}
								onChange={(value) => onIdChange(parseInt(value))}
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
};

export default StrongTestimonialViewEdit;
