"use strict";

function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function"); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, writable: true, configurable: true } }); Object.defineProperty(subClass, "prototype", { writable: false }); if (superClass) _setPrototypeOf(subClass, superClass); }

function _setPrototypeOf(o, p) { _setPrototypeOf = Object.setPrototypeOf ? Object.setPrototypeOf.bind() : function _setPrototypeOf(o, p) { o.__proto__ = p; return o; }; return _setPrototypeOf(o, p); }

function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = _getPrototypeOf(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = _getPrototypeOf(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return _possibleConstructorReturn(this, result); }; }

function _possibleConstructorReturn(self, call) { if (call && (_typeof(call) === "object" || typeof call === "function")) { return call; } else if (call !== void 0) { throw new TypeError("Derived constructors may only return object or undefined"); } return _assertThisInitialized(self); }

function _assertThisInitialized(self) { if (self === void 0) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return self; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }

function _getPrototypeOf(o) { _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf.bind() : function _getPrototypeOf(o) { return o.__proto__ || Object.getPrototypeOf(o); }; return _getPrototypeOf(o); }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }

/* jshint ignore:start */
var BWModuleFrontend = /*#__PURE__*/function () {
  /**
   * The main constructor method for the super class. This is called on init.
   *
   * @param  {object} settings An object of settings
   *
   * @return {void}
   */
  function BWModuleFrontend(settings) {
    _classCallCheck(this, BWModuleFrontend);

    // Init sentry
    this._initSentry(); // Add the settings


    this.settings = settings; // If we were provided an ID

    if (settings.id) {
      // Add the element ID
      this.id = settings.id; // Add the element

      this.element = document.querySelector('.fl-node-' + this.id); // If we can't find the element, abort

      if (!this.element) {
        this.warn("Error! Unable to locate element .fl-node-".concat(this.id));
        return;
      }
    } // Default elements


    this.elements = {}; // Bind the type of object this is 

    this.objectType = this._getObjectType(); // Init the child function

    this.init();
  }
  /**
   * Method to init the Sentry Object
   *
   * @return {void}
   */


  _createClass(BWModuleFrontend, [{
    key: "_initSentry",
    value: function _initSentry() {
      if (sentry_data.environment) {
        Sentry.init({
          dsn: 'https://9097fbf7ed4247e7a5425f1a0654f37e@sentry.io/1460499'
        });
      }
    }
    /**
     * Method to log something to the Sentry obecjt
     *
     * @param  {object} message The object to log
     *
     * @return {void}         
     */

  }, {
    key: "sentryLog",
    value: function sentryLog(message) {
      var data = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
      var level = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : 'error';

      if (Sentry !== undefined) {
        Sentry.captureMessage(message, {
          data: data,
          level: level,
          fingerprint: ['{{ default }}', sentry_data.environment]
        });
      }
    }
    /**
     * Method to get the name of the current object (used in debugging logs).
     *
     * @return {string} The name of the current object
     */

  }, {
    key: "_getObjectType",
    value: function _getObjectType() {
      return this.constructor.name;
    }
    /**
     * Method to log messages to the console.   
     *
     * @param  {mixed} message The mixed type to log
     *
     * @return {void}
     */

  }, {
    key: "debug",
    value: function debug(message) {
      // Get the arguments
      var arguments_object = arguments,
          // The default argument to log is just the message
      argument_to_log = message; // If the length is more than one, we need to make an array

      if (arguments_object.length > 1) {
        // Redeclare the argument to log as an array
        argument_to_log = []; // Add all the arguments

        for (var i = 0; i < arguments.length; i++) {
          argument_to_log.push(arguments[i]);
        }
      } // Log it


      console.debug(this.constructor.name + ' -', argument_to_log);
    }
    /**
     * Method to log messages to the console.   
     *
     * @param  {mixed} message The mixed type to log
     *
     * @return {void}
     */

  }, {
    key: "log",
    value: function log(message) {
      // Get the arguments
      var arguments_object = arguments,
          // The default argument to log is just the message
      argument_to_log = message; // If the length is more than one, we need to make an array

      if (arguments_object.length > 1) {
        // Redeclare the argument to log as an array
        argument_to_log = []; // Add all the arguments

        for (var i = 0; i < arguments.length; i++) {
          argument_to_log.push(arguments[i]);
        }
      } // Log it


      console.log(this.constructor.name + ' -', argument_to_log);
    }
    /**
     * Method to log warning messages to the console.   
     *
     * @param  {mixed} message The mixed type to log
     *
     * @return {void}
     */

  }, {
    key: "warn",
    value: function warn(message) {
      // Get the arguments
      var arguments_object = arguments,
          // The default argument to log is just the message
      argument_to_log = message; // If the length is more than one, we need to make an array

      if (arguments_object.length > 1) {
        // Redeclare the argument to log as an array
        argument_to_log = []; // Add all the arguments

        for (var i = 0; i < arguments.length; i++) {
          argument_to_log.push(arguments[i]);
        }
      } // Log it


      console.warn(this.constructor.name + ' -', argument_to_log);
    }
    /**
     * Method to log error messages to the console.   
     *
     * @param  {mixed} message The mixed type to log
     *
     * @return {void}
     */

  }, {
    key: "error",
    value: function error(message) {
      // Get the arguments
      var arguments_object = arguments,
          // The default argument to log is just the message
      argument_to_log = message; // If the length is more than one, we need to make an array

      if (arguments_object.length > 1) {
        // Redeclare the argument to log as an array
        argument_to_log = []; // Add all the arguments

        for (var i = 0; i < arguments.length; i++) {
          argument_to_log.push(arguments[i]);
        }
      }

      this.sentryLog(argument_to_log); // Log it

      console.error(this.constructor.name + ' -', argument_to_log);
    }
  }]);

  return BWModuleFrontend;
}();

;
/* jshint ignore:end */

/**
 * The main class used for the social icons.
 */

var BWSocialIcons = /*#__PURE__*/function (_BWModuleFrontend) {
  _inherits(BWSocialIcons, _BWModuleFrontend);

  var _super = _createSuper(BWSocialIcons);

  function BWSocialIcons() {
    _classCallCheck(this, BWSocialIcons);

    return _super.apply(this, arguments);
  }

  _createClass(BWSocialIcons, [{
    key: "init",
    value:
    /**
     * Method automatically called by the superclass
     *
     * @return {void} 
     */
    function init() {
      this.elements.socialIconsContainerElement = this.element.querySelector('.social-icons-container'); // If we need to affix the social share icon container, do that now.

      if (this.settings.isSticky) {
        this._bindAffixToShareIconsContainer(); // Recalculate on resize


        bind_callback_to_window_resize(this._reinitAffix, this);
      }
    }
    /**
     * Callback used to reinit affix. Used after a window is resized
     *
     * @return {void}
     */

  }, {
    key: "_reinitAffix",
    value: function _reinitAffix() {
      // Destroy affix
      jQuery(window).off('.affix');
      jQuery(this.elements.socialIconsContainerElement).removeClass("affix affix-top affix-bottom").removeData("bs.affix"); // Reinit

      this._bindAffixToShareIconsContainer();
    }
    /**
     * Method to handle binding the affix action to the share icons container.
     *
     * @return {void}
     */

  }, {
    key: "_bindAffixToShareIconsContainer",
    value: function _bindAffixToShareIconsContainer() {
      var $ = jQuery,
          self = this,
          // Get the offset of the element
      module_offset = this.elements.socialIconsContainerElement.getBoundingClientRect().top,
          // The WordPress admin bar height
      wordpress_admin_bar_height = get_wp_admin_bar_height(),
          // Get the header height
      header_height = get_header_height(),
          // The total offset is the header height plus the admin bar
      // height
      total_offset = window.scrollY + module_offset - wordpress_admin_bar_height - header_height;
      this.log('Calculated values:', {
        'Offset values:': [{
          'Module offset': module_offset
        }, {
          'WP Admin bar height': wordpress_admin_bar_height
        }, {
          'Height height': header_height
        }, {
          'Total offset': total_offset
        }]
      });
      $(this.elements.socialIconsContainerElement).on('affix.bs.affix', function () {
        $(this).css({
          top: header_height + wordpress_admin_bar_height + 'px'
        });
      }).on('affix-top.bs.affix', function () {
        $(this).removeAttr('style');
      }).affix({
        offset: {
          top: total_offset
        }
      });
      this.log('Affixing share icon container');
    }
  }]);

  return BWSocialIcons;
}(BWModuleFrontend);
//# sourceMappingURL=frontend.js.map
