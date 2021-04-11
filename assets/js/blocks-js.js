/******/ (function(modules) {
	// webpackBootstrap
	/******/ // The module cache
	/******/ var installedModules = {}; // The require function
	/******/
	/******/ /******/ function __webpack_require__(moduleId) {
		/******/
		/******/ // Check if module is in cache
		/******/ if (installedModules[moduleId]) {
			/******/ return installedModules[moduleId].exports;
			/******/
		} // Create a new module (and put it into the cache)
		/******/ /******/ var module = (installedModules[moduleId] = {
			/******/ i: moduleId,
			/******/ l: false,
			/******/ exports: {}
			/******/
		}); // Execute the module function
		/******/
		/******/ /******/ modules[moduleId].call(module.exports, module, module.exports, __webpack_require__); // Flag the module as loaded
		/******/
		/******/ /******/ module.l = true; // Return the exports of the module
		/******/
		/******/ /******/ return module.exports;
		/******/
	} // expose the modules object (__webpack_modules__)
	/******/
	/******/
	/******/ /******/ __webpack_require__.m = modules; // expose the module cache
	/******/
	/******/ /******/ __webpack_require__.c = installedModules; // identity function for calling harmony imports with the correct context
	/******/
	/******/ /******/ __webpack_require__.i = function(value) {
		return value;
	}; // define getter function for harmony exports
	/******/
	/******/ /******/ __webpack_require__.d = function(exports, name, getter) {
		/******/ if (!__webpack_require__.o(exports, name)) {
			/******/ Object.defineProperty(exports, name, {
				/******/ configurable: false,
				/******/ enumerable: true,
				/******/ get: getter
				/******/
			});
			/******/
		}
		/******/
	}; // getDefaultExport function for compatibility with non-harmony modules
	/******/
	/******/ /******/ __webpack_require__.n = function(module) {
		/******/ var getter =
			module && module.__esModule
				? /******/ function getDefault() {
						return module['default'];
					}
				: /******/ function getModuleExports() {
						return module;
					};
		/******/ __webpack_require__.d(getter, 'a', getter);
		/******/ return getter;
		/******/
	}; // Object.prototype.hasOwnProperty.call
	/******/
	/******/ /******/ __webpack_require__.o = function(object, property) {
		return Object.prototype.hasOwnProperty.call(object, property);
	}; // __webpack_public_path__
	/******/
	/******/ /******/ __webpack_require__.p = ''; // Load entry module and return exports
	/******/
	/******/ /******/ return __webpack_require__((__webpack_require__.s = 4));
	/******/
})(
	/************************************************************************/
	/******/ [
		/* 0 */
		/***/ function(module, exports, __webpack_require__) {
			'use strict';

			Object.defineProperty(exports, '__esModule', {
				value: true
			});
			exports.StrongTestimonialViewEdit = undefined;

			var _extends =
				Object.assign ||
				function(target) {
					for (var i = 1; i < arguments.length; i++) {
						var source = arguments[i];
						for (var key in source) {
							if (Object.prototype.hasOwnProperty.call(source, key)) {
								target[key] = source[key];
							}
						}
					}
					return target;
				};

			var _inspector = __webpack_require__(5);

			var _inspector2 = _interopRequireDefault(_inspector);

			function _interopRequireDefault(obj) {
				return obj && obj.__esModule ? obj : { default: obj };
			}

			/**
 * Wordpress deps
 */

			var __ = wp.i18n.__;
			var _wp$element = wp.element,
				Component = _wp$element.Component,
				Fragment = _wp$element.Fragment,
				useEffect = _wp$element.useEffect;
			var withSelect = wp.data.withSelect;
			var _wp$components = wp.components,
				SelectControl = _wp$components.SelectControl,
				Spinner = _wp$components.Spinner,
				Toolbar = _wp$components.Toolbar,
				Button = _wp$components.Button;
			var BlockControls = wp.blockEditor.BlockControls;
			var StrongTestimonialViewEdit = (exports.StrongTestimonialViewEdit = function StrongTestimonialViewEdit(
				props
			) {
				var attributes = props.attributes,
					setAttributes = props.setAttributes;
				var id = attributes.id,
					views = attributes.views,
					status = attributes.status,
					testimonials = attributes.testimonials,
					mode = attributes.mode;

				useEffect(function() {
					setAttributes({ status: 'ready', views: st_views.views });

					if (id != 0) {
						_onIdChange(id);
					}
				}, []);
				var _onIdChange = function _onIdChange(id) {
					props.setAttributes({ status: 'ready', id: id });
				};

				var selectOptions = function selectOptions() {
					var options = [ { value: 0, label: __('None') } ];

					st_views.views.forEach(function(view) {
						options.push({ value: view.id, label: view.name });
					});

					return options;
				};

				var blockControls = React.createElement(
					BlockControls,
					null,
					st_views.views.length > 0 &&
						React.createElement(
							Toolbar,
							null,
							React.createElement(Button, { label: __('Edit View'), icon: 'edit', target: '_blank' })
						)
				);
				if (status === 'loading') {
					return [
						React.createElement(
							Fragment,
							null,
							React.createElement(
								'div',
								{ className: 'st-block-preview' },
								React.createElement(
									'div',
									{ className: 'st-block-preview__content' },
									React.createElement('div', { className: 'st-block-preview__logo' }, ' '),
									React.createElement(Spinner, null)
								)
							)
						)
					];
				}

				return [
					React.createElement(
						Fragment,
						null,
						React.createElement(
							_inspector2.default,
							_extends(
								{
									onIdChange: function onIdChange(id) {
										return _onIdChange(id);
									},
									selectOptions: selectOptions()
								},
								props
							)
						),
						React.createElement(
							'div',
							{ className: 'st-block-preview' },
							React.createElement(
								'div',
								{ class: 'st-block-preview__content' },
								React.createElement('div', { className: 'st-block-preview__logo' }),
								st_views.views.length === 0 &&
									React.createElement(
										Fragment,
										null,
										React.createElement('h6', null, __("You don't seem to have any views.")),
										React.createElement(
											Button,
											{
												href:
													st_views.adminURL +
													'edit.php?post_type=wpm-testimonial&page=testimonial-views&action=add',
												target: '_blank',
												isDefault: true
											},
											__('Add New View')
										)
									),
								st_views.views.length > 0 &&
									React.createElement(
										Fragment,
										null,
										React.createElement(SelectControl, {
											label: 'Select a view:',
											className: 'st-view-select',
											key: id,
											value: id,
											options: selectOptions(),
											onChange: function onChange(value) {
												return _onIdChange(parseInt(value));
											}
										}),
										id != 0 &&
											React.createElement(
												Button,
												{
													target: '_blank',
													href:
														st_views.adminURL +
														'edit.php?post_type=wpm-testimonial&page=testimonial-views&action=edit&id=' +
														id,
													isSecondary: true
												},
												__('Edit Settings')
											)
									)
							)
						)
					)
				];
			});

			exports.default = StrongTestimonialViewEdit;

			/***/
		} /* 4 */ /* 2 */ /* 3 */,
		,
		,
		,
		/* 1 */ /***/ function(module, exports, __webpack_require__) {
			'use strict';

			var _createClass = (function() {
				function defineProperties(target, props) {
					for (var i = 0; i < props.length; i++) {
						var descriptor = props[i];
						descriptor.enumerable = descriptor.enumerable || false;
						descriptor.configurable = true;
						if ('value' in descriptor) descriptor.writable = true;
						Object.defineProperty(target, descriptor.key, descriptor);
					}
				}
				return function(Constructor, protoProps, staticProps) {
					if (protoProps) defineProperties(Constructor.prototype, protoProps);
					if (staticProps) defineProperties(Constructor, staticProps);
					return Constructor;
				};
			})();

			var _edit = __webpack_require__(0);

			var _edit2 = _interopRequireDefault(_edit);

			function _interopRequireDefault(obj) {
				return obj && obj.__esModule ? obj : { default: obj };
			}

			function _classCallCheck(instance, Constructor) {
				if (!(instance instanceof Constructor)) {
					throw new TypeError('Cannot call a class as a function');
				}
			}

			/**
 * Import wp deps
 */

			var __ = wp.i18n.__;
			var registerBlockType = wp.blocks.registerBlockType;

			var StrongTestimonialView = (function() {
				function StrongTestimonialView() {
					_classCallCheck(this, StrongTestimonialView);

					this.registerBlock();
				}

				_createClass(StrongTestimonialView, [
					{
						key: 'registerBlock',
						value: function registerBlock() {
							this.blockName = 'strongtestimonials/view';

							this.blockAttributes = {
								id: {
									type: 'number',
									default: 0
								},
								mode: {
									type: 'string',
									default: 'display'
								}
							};

							registerBlockType(this.blockName, {
								title: 'Strong Testimonial View',
								description: __('Render ST View', 'strong-testimonials'),
								icon: 'editor-quote',
								category: 'common',
								supports: {
									html: false,
									customClassName: false
								},

								attributes: this.blockAttributes,
								edit: _edit2.default,
								save: function save() {
									return null;
								}
							});
						}
					}
				]);

				return StrongTestimonialView;
			})();

			var strongTestimonialsView = new StrongTestimonialView();

			/***/
		},
		/* 5 */
		/***/ function(module, exports, __webpack_require__) {
			'use strict';

			Object.defineProperty(exports, '__esModule', {
				value: true
			});

			var _createClass = (function() {
				function defineProperties(target, props) {
					for (var i = 0; i < props.length; i++) {
						var descriptor = props[i];
						descriptor.enumerable = descriptor.enumerable || false;
						descriptor.configurable = true;
						if ('value' in descriptor) descriptor.writable = true;
						Object.defineProperty(target, descriptor.key, descriptor);
					}
				}
				return function(Constructor, protoProps, staticProps) {
					if (protoProps) defineProperties(Constructor.prototype, protoProps);
					if (staticProps) defineProperties(Constructor, staticProps);
					return Constructor;
				};
			})();

			function _classCallCheck(instance, Constructor) {
				if (!(instance instanceof Constructor)) {
					throw new TypeError('Cannot call a class as a function');
				}
			}

			function _possibleConstructorReturn(self, call) {
				if (!self) {
					throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
				}
				return call && (typeof call === 'object' || typeof call === 'function') ? call : self;
			}

			function _inherits(subClass, superClass) {
				if (typeof superClass !== 'function' && superClass !== null) {
					throw new TypeError('Super expression must either be null or a function, not ' + typeof superClass);
				}
				subClass.prototype = Object.create(superClass && superClass.prototype, {
					constructor: { value: subClass, enumerable: false, writable: true, configurable: true }
				});
				if (superClass)
					Object.setPrototypeOf
						? Object.setPrototypeOf(subClass, superClass)
						: (subClass.__proto__ = superClass);
			}

			/**
 * WordPress dependencies
 */
			var __ = wp.i18n.__;
			var _wp$element = wp.element,
				Component = _wp$element.Component,
				Fragment = _wp$element.Fragment;
			var InspectorControls = wp.blockEditor.InspectorControls;
			var _wp$components = wp.components,
				SelectControl = _wp$components.SelectControl,
				Button = _wp$components.Button,
				PanelBody = _wp$components.PanelBody,
				PanelRow = _wp$components.PanelRow;

			/**
 * Inspector controls
 */

			var Inspector = (function(_Component) {
				_inherits(Inspector, _Component);

				function Inspector(props) {
					_classCallCheck(this, Inspector);

					return _possibleConstructorReturn(
						this,
						(Inspector.__proto__ || Object.getPrototypeOf(Inspector)).apply(this, arguments)
					);
				}

				_createClass(Inspector, [
					{
						key: 'render',
						value: function render() {
							var _props = this.props,
								attributes = _props.attributes,
								setAttributes = _props.setAttributes,
								onIdChange = _props.onIdChange,
								selectOptions = _props.selectOptions;
							var id = attributes.id,
								views = attributes.views,
								testimonials = attributes.testimonials;

							return React.createElement(
								Fragment,
								null,
								React.createElement(
									InspectorControls,
									null,
									React.createElement(
										PanelBody,
										{ title: __('View Settings'), initialOpen: true },
										st_views.views.length === 0 &&
											React.createElement(
												Fragment,
												null,
												React.createElement('p', null, __("You don't seem to have any views.")),
												React.createElement(
													Button,
													{
														href:
															st_views.adminURL +
															'edit.php?post_type=wpm-testimonial&page=testimonial-views&action=add',
														target: '_blank',
														isDefault: true
													},
													__('Add New View')
												)
											),
										st_views.views.length > 0 &&
											React.createElement(
												Fragment,
												null,
												React.createElement(SelectControl, {
													label: __('Select View'),
													key: id,
													value: id,
													options: selectOptions,
													onChange: function onChange(value) {
														return onIdChange(parseInt(value));
													}
												}),
												id != 0 &&
													React.createElement(
														Button,
														{
															target: '_blank',
															href:
																st_views.adminURL +
																'edit.php?post_type=wpm-testimonial&page=testimonial-views&action=edit&id=' +
																id,
															isSecondary: true
														},
														__('Edit View')
													)
											)
									)
								)
							);
						}
					}
				]);

				return Inspector;
			})(Component);

			exports.default = Inspector;

			/***/
		}
		/******/
	]
);
