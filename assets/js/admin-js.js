/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// identity function for calling harmony imports with the correct context
/******/ 	__webpack_require__.i = function(value) { return value; };
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 1);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
	value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var ItemCreation = function () {
	function ItemCreation($element) {
		var _this = this;

		_classCallCheck(this, ItemCreation);

		this.$itemCreation = $element;
		this.$addButton = this.$itemCreation.next('input');
		this.$input = this.$addButton.next('input');
		this.fields = this.$input.val() === '' ? [] : JSON.parse(this.$input.val());

		this.initSortable();

		//events
		this.$addButton.on('click', function (e) {
			return _this.onAddButtonClick(e);
		});
		this.$itemCreation.on('click', '.wpmtst-item-creation__icon.delete', function (e) {
			return _this.onDeleteItemClick(e);
		});
		this.$itemCreation.on('click', '.wpmtst-item-creation__icon.toggle', function (e) {
			return _this.onToggleClick(e);
		});
		this.$itemCreation.on('change keyup paste', '.wpmtst-item-creation__field-property input', function (e) {
			return _this.onFieldPropertyChange(e);
		});
	}

	_createClass(ItemCreation, [{
		key: 'initSortable',
		value: function initSortable() {
			var _this2 = this;

			this.$itemCreation.sortable({
				handle: ".handle",
				update: function update(event, ui) {
					return _this2.onSortUpdate(event, ui);
				}
			});
		}
	}, {
		key: 'onFieldPropertyChange',
		value: function onFieldPropertyChange(e) {
			var input = jQuery(e.target);
			var item = input.parents('.wpmtst-item-creation__item');
			var fieldPropertyName = input.parent().data('name');

			if ('label' === fieldPropertyName) {
				item.find('.wpmtst-item-creation__description').html(input.val());
			}

			this.fields[item.index()][fieldPropertyName] = input.val();
			this.$input.val(JSON.stringify(this.fields));
		}
	}, {
		key: 'onSortUpdate',
		value: function onSortUpdate(event, ui) {
			var _this3 = this;

			this.fields = [];
			var items = this.$itemCreation.find('.wpmtst-item-creation__item');

			items.each(function (index) {
				var field = {};
				var fieldProperties = items.eq(index).find('.wpmtst-item-creation__field-property');

				fieldProperties.each(function (index) {

					var fieldProperty = fieldProperties.eq(index);
					var fieldPropertyName = fieldProperty.data('name');
					var fieldPropertyValue = fieldProperty.find('input').val();

					field[fieldPropertyName] = fieldPropertyValue;
				});

				_this3.fields.push(field);
				_this3.$input.val(JSON.stringify(_this3.fields));
			});
		}
	}, {
		key: 'onAddButtonClick',
		value: function onAddButtonClick(e) {
			this.fields.push({ value: '', label: 'Label' });
			this.$input.val(JSON.stringify(this.fields)).trigger('change');

			this.$itemCreation.append('<div class="wpmtst-item-creation__item"><div><div class="wpmtst-item-creation__link"><span class="wpmtst-item-creation__description">Label</span><div class="wpmtst-item-creation__controls"><span class="handle wpmtst-item-creation__icon" title="drag and drop to reorder"></span><span class="delete wpmtst-item-creation__icon" title="remove"></span></div><div class="wpmtst-item-creation__controls"><span class="toggle wpmtst-item-creation__icon" title="click to open or close"></span></div></div><div class="wpmtst-item-creation__field-properties"><div class="wpmtst-item-creation__field-property" data-name="value"><label>Value</label><input type="text" value=""></div><div class="wpmtst-item-creation__field-property" data-name="label"><label>Label</label><input type="text" value="Label"></div></div></div></div>');
		}
	}, {
		key: 'onDeleteItemClick',
		value: function onDeleteItemClick(e) {
			var item = jQuery(e.target).parents('.wpmtst-item-creation__item');

			//remove from array
			this.fields.splice(item.index(), 1);
			this.$input.val(JSON.stringify(this.fields)).trigger('change');

			//remove from dom
			item.remove();
		}
	}, {
		key: 'onToggleClick',
		value: function onToggleClick(e) {
			console.log('toggle click');
			jQuery(e.target).parents('.wpmtst-item-creation__item').toggleClass('wpmtst-item-creation__item--open');
		}
	}]);

	return ItemCreation;
}();

exports.default = ItemCreation;

/***/ }),
/* 1 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _itemCreation = __webpack_require__(0);

var _itemCreation2 = _interopRequireDefault(_itemCreation);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

/**
 * Strong Testimonials admin
 *
 * @namespace jQuery
 */

// Function to get the Max value in Array
Array.max = function (array) {
	return Math.max.apply(Math, array);
};

jQuery(document).ready(function ($) {

	// Convert "A String" to "a_string"
	function convertLabel(label) {
		return label.replace(/\s+/g, '_').replace(/\W/g, '').toLowerCase();
	}

	// Remove invalid characters
	function removeSpaces(word) {
		//return word.replace(/\s+/g, "_");
		return word.replace(/[^\w\s(?!\-)]/gi, '');
	}

	$.fn.showInlineBlock = function () {
		return this.css('display', 'inline-block');
	};

	/**
  * ----------------------------------------
  * General events
  * ----------------------------------------
  */

	// Add protocol if missing
	// Thanks http://stackoverflow.com/a/36429927/51600
	$('input[type=url]').change(function () {
		if (this.value.length && !/^https*:\/\//.test(this.value)) {
			this.value = 'http://' + this.value;
		}
	});

	$('ul.ui-tabs-nav li a').click(function () {
		$(this).blur();
	});

	$('.focus-next-field').change(function (e) {
		if ($(e.target).is(':checked')) {
			$(e.target).parent().next().find('input').focus().select();
		}
	});

	// toggle screenshots
	$('#toggle-screen-options').add('#screenshot-screen-options').click(function (e) {
		$(this).blur();
		$('#screenshot-screen-options').slideToggle();
	});

	// toggle screenshots
	$('#toggle-help').click(function (e) {
		$(this).toggleClass('closed open').blur();
		$('#help-section').slideToggle();
	});

	/**
  * ----------------------------------------
  * View List Table
  * ----------------------------------------
  */

	/**
  * Save sort order
  */
	$('table.wpm-testimonial_page_testimonial-views th.manage-column').on('click', function (e) {
		var columnName = $(this).attr('id');
		// get the opposite class
		var columnOrder = $(this).hasClass('asc') ? 'desc' : $(this).hasClass('desc') ? 'asc' : '';
		var data = {
			'action': 'wpmtst_save_view_list_order',
			'name': columnName,
			'order': columnOrder
		};
		$.get(ajaxurl, data, function (response) {});
	});

	/**
  * Sticky views
  */
	$('table.wpm-testimonial_page_testimonial-views').on('click', '.stickit', function (e) {
		var icon = $(this);
		icon.closest('.wp-list-table-wrap').find('.overlay').fadeIn(200);
		icon.blur().toggleClass('stuck');
		var id = $(this).closest('tr').find('td.id').html();
		var data = {
			'action': 'wpmtst_save_view_sticky',
			'id': id
		};
		$.get(ajaxurl, data, function (response) {
			if (response) {
				window.location.reload();
			}
		});
	});
});

var WPMTST_Admin = function () {
	function WPMTST_Admin() {
		_classCallCheck(this, WPMTST_Admin);

		this.initItemCreation();
	}

	_createClass(WPMTST_Admin, [{
		key: 'initAllControls',
		value: function initAllControls() {
			var $div = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : jQuery("body");

			this.initItemCreation($div);
		}
	}, {
		key: 'initItemCreation',
		value: function initItemCreation() {
			var $div = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : jQuery("body");

			$div.find('.wpmtst-item-creation').each(function (index) {
				new _itemCreation2.default(jQuery(this));
			});
		}
	}]);

	return WPMTST_Admin;
}();

window.WPMTST_Admin = new WPMTST_Admin();

/***/ })
/******/ ]);