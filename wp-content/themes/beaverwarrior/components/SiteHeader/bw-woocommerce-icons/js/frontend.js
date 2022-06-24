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

var BWWooCommerceIcons = /*#__PURE__*/function (_BWModuleFrontend) {
  _inherits(BWWooCommerceIcons, _BWModuleFrontend);

  var _super = _createSuper(BWWooCommerceIcons);

  function BWWooCommerceIcons() {
    _classCallCheck(this, BWWooCommerceIcons);

    return _super.apply(this, arguments);
  }

  _createClass(BWWooCommerceIcons, [{
    key: "init",
    value:
    /**
     * Method automatically called by the superclass
     *
     * @return {void} 
     */
    function init() {
      // Handle clicking for the Add to Cart buttons
      this._handleUpdateCartQuantities();
    }
    /**
     * Method designated to listen for an event that tells us that an item
     * has been added to the WC cart via AJAX.
     *
     * @return {void}
     */

  }, {
    key: "_handleUpdateCartQuantities",
    value: function _handleUpdateCartQuantities() {
      var _this = this;

      var elem = document.querySelector('html');
      elem.addEventListener('wc-update-cart-quantity', function (e) {
        _this._updateCartQuantityBadge(e);
      }, false);
    }
  }, {
    key: "_updateCartQuantityBadge",
    value: function _updateCartQuantityBadge(data) {
      var icon_badges = this.element.querySelectorAll('.cart-icon-badge');
      var new_quantity = data.detail.cartItemQuantity; // It's possible to have multiple badges in the same module even though that'd
      // be dumb

      for (var i = 0; i < icon_badges.length; i++) {
        var badge = icon_badges[i];
        var badge_parent = badge.closest('.woocommerce-icon');

        if (data.detail.cartItemQuantity > 0) {
          badge_parent.classList.add('woocommerce-icon-has-badge');
        } else {
          badge_parent.classList.remove('woocommerce-icon-has-badge');
        }

        badge.innerHTML = new_quantity;
      }
    }
  }]);

  return BWWooCommerceIcons;
}(BWModuleFrontend);
//# sourceMappingURL=frontend.js.map
