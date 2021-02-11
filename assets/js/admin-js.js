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
/******/ 	return __webpack_require__(__webpack_require__.s = 3);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */,
/* 1 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
	value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var AddonsPage = function () {
	function AddonsPage() {
		var _this = this;

		_classCallCheck(this, AddonsPage);

		if (!jQuery('body').hasClass('wpm-testimonial_page_strong-testimonials-addons')) {
			return;
		}

		this.reloadButton = jQuery('#wpmtst-reload-extensions');

		//events
		this.reloadButton.on('click', function (e) {
			return _this.onReloadExtensionsClick(e);
		});
	}

	_createClass(AddonsPage, [{
		key: 'onReloadExtensionsClick',
		value: function onReloadExtensionsClick(e) {
			e.preventDefault();

			this.reloadButton.addClass('updating-message');

			jQuery.ajax({
				type: "POST",
				data: { action: "wpmtst_reload_extensions", nonce: this.reloadButton.data('nonce') },
				url: ajaxurl,
				success: function success(response) {
					location.reload();
				}
			});
		}
	}]);

	return AddonsPage;
}();

exports.default = AddonsPage;

/***/ }),
/* 2 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
	value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Notice = function () {
	function Notice($element) {
		var _this = this;

		_classCallCheck(this, Notice);

		this.$element = $element;
		this.key = $element.data('key');
		this.nonce = $element.data('nonce');

		this.$element.on('click', '.notice-dismiss', function () {
			return _this.onDismissClick();
		});
	}

	_createClass(Notice, [{
		key: 'onDismissClick',
		value: function onDismissClick() {

			this.$element.remove();

			jQuery.ajax({
				type: "POST",
				data: { action: "wpmtst_dismiss_notice", nonce: this.nonce, key: this.key },
				url: ajaxurl
			});
		}
	}]);

	return Notice;
}();

exports.default = Notice;

/***/ }),
/* 3 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _notice = __webpack_require__(2);

var _notice2 = _interopRequireDefault(_notice);

var _AddonsPage = __webpack_require__(1);

var _AddonsPage2 = _interopRequireDefault(_AddonsPage);

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
	$('input[type=url]').on('change', function () {
		if (this.value.length && !/^https*:\/\//.test(this.value)) {
			this.value = 'http://' + this.value;
		}
	});

	$('ul.ui-tabs-nav li a').on('click', function () {
		$(this).trigger('blur');
	});

	$('.focus-next-field').on('change', function (e) {
		if ($(e.target).is(':checked')) {
			$(e.target).parent().next().find('input').focus().trigger('select');
		}
	});

	// toggle screenshots
	$('#toggle-screen-options').add('#screenshot-screen-options').on('click', function (e) {
		$(this).trigger('blur');
		$('#screenshot-screen-options').slideToggle();
	});

	// toggle screenshots
	$('#toggle-help').on('click', function (e) {
		$(this).toggleClass('closed open').trigger('blur');
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
		icon.trigger('blur').toggleClass('stuck');
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

		this.initNotices();
		this.initAddonsPage();
	}

	_createClass(WPMTST_Admin, [{
		key: 'initNotices',
		value: function initNotices() {
			var $div = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : jQuery("body");

			$div.find('.wpmtst-notice').each(function (index) {
				new _notice2.default(jQuery(this));
			});
		}
	}, {
		key: 'initAddonsPage',
		value: function initAddonsPage() {
			new _AddonsPage2.default(jQuery(this));
		}
	}]);

	return WPMTST_Admin;
}();

window.WPMTST_Admin = new WPMTST_Admin();

/***/ })
/******/ ]);