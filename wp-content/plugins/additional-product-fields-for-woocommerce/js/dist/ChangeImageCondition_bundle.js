var RedNaoChangeImageCondition =
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
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
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
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/app/Dynamics/Conditions/ChangeImageCondition/ChangeImageConditionProcessor.tsx");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/app/Dynamics/Conditions/ChangeImageCondition/ChangeImageConditionProcessor.tsx":
/*!********************************************************************************************!*\
  !*** ./src/app/Dynamics/Conditions/ChangeImageCondition/ChangeImageConditionProcessor.tsx ***!
  \********************************************************************************************/
/*! exports provided: ChangeImageConditionProcessor */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "ChangeImageConditionProcessor", function() { return ChangeImageConditionProcessor; });
/* harmony import */ var FormBuilder_Core_Conditions_ConditionProcessorBase__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! FormBuilder/Core/Conditions/ConditionProcessorBase */ "FormBuilder/Core/Conditions/ConditionProcessorBase");
/* harmony import */ var FormBuilder_Core_Conditions_ConditionProcessorBase__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(FormBuilder_Core_Conditions_ConditionProcessorBase__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var FormBuilder_Fields_Core_ConditionOptions__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! FormBuilder/Fields/Core/ConditionOptions */ "FormBuilder/Fields/Core/ConditionOptions");
/* harmony import */ var FormBuilder_Fields_Core_ConditionOptions__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(FormBuilder_Fields_Core_ConditionOptions__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var shared_core_Events_EventManager__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! shared/core/Events/EventManager */ "shared/core/Events/EventManager");
/* harmony import */ var shared_core_Events_EventManager__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(shared_core_Events_EventManager__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var FormBuilder_Events_InitializeAdditionalCondition__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! FormBuilder/Events/InitializeAdditionalCondition */ "FormBuilder/Events/InitializeAdditionalCondition");
/* harmony import */ var FormBuilder_Events_InitializeAdditionalCondition__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(FormBuilder_Events_InitializeAdditionalCondition__WEBPACK_IMPORTED_MODULE_3__);
function _typeof(obj) { if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

function _possibleConstructorReturn(self, call) { if (call && (_typeof(call) === "object" || typeof call === "function")) { return call; } return _assertThisInitialized(self); }

function _assertThisInitialized(self) { if (self === void 0) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return self; }

function _get(target, property, receiver) { if (typeof Reflect !== "undefined" && Reflect.get) { _get = Reflect.get; } else { _get = function _get(target, property, receiver) { var base = _superPropBase(target, property); if (!base) return; var desc = Object.getOwnPropertyDescriptor(base, property); if (desc.get) { return desc.get.call(receiver); } return desc.value; }; } return _get(target, property, receiver || target); }

function _superPropBase(object, property) { while (!Object.prototype.hasOwnProperty.call(object, property)) { object = _getPrototypeOf(object); if (object === null) break; } return object; }

function _getPrototypeOf(o) { _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) { return o.__proto__ || Object.getPrototypeOf(o); }; return _getPrototypeOf(o); }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function"); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, writable: true, configurable: true } }); if (superClass) _setPrototypeOf(subClass, superClass); }

function _setPrototypeOf(o, p) { _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) { o.__proto__ = p; return o; }; return _setPrototypeOf(o, p); }





var ChangeImageConditionProcessor =
/*#__PURE__*/
function (_ConditionProcessorBa) {
  _inherits(ChangeImageConditionProcessor, _ConditionProcessorBa);

  function ChangeImageConditionProcessor() {
    var _getPrototypeOf2;

    var _this;

    _classCallCheck(this, ChangeImageConditionProcessor);

    for (var _len = arguments.length, args = new Array(_len), _key = 0; _key < _len; _key++) {
      args[_key] = arguments[_key];
    }

    _this = _possibleConstructorReturn(this, (_getPrototypeOf2 = _getPrototypeOf(ChangeImageConditionProcessor)).call.apply(_getPrototypeOf2, [this].concat(args)));
    _this.Options = void 0;
    _this.Field = void 0;
    return _this;
  }

  _createClass(ChangeImageConditionProcessor, [{
    key: "Initialize",
    value: function Initialize(formBuilder, condition) {
      throw Error("Invalid initialization, please use initialize with fields instead");
    }
  }, {
    key: "ExecuteFalseAction",
    value: function ExecuteFalseAction(immediate) {
      var options = this.Options.OptionsToDisable;
      var _iteratorNormalCompletion = true;
      var _didIteratorError = false;
      var _iteratorError = undefined;

      try {
        for (var _iterator = options[Symbol.iterator](), _step; !(_iteratorNormalCompletion = (_step = _iterator.next()).done); _iteratorNormalCompletion = true) {
          var currentOption = _step.value;
          if (this.Options.DisableWhenTrue) this.Field.EnableOption(currentOption);else this.Field.DisableOption(currentOption);
        }
      } catch (err) {
        _didIteratorError = true;
        _iteratorError = err;
      } finally {
        try {
          if (!_iteratorNormalCompletion && _iterator["return"] != null) {
            _iterator["return"]();
          }
        } finally {
          if (_didIteratorError) {
            throw _iteratorError;
          }
        }
      }
    }
  }, {
    key: "ExecuteTruAction",
    value: function ExecuteTruAction(immediate) {
      var options = this.Options.OptionsToDisable;
      var _iteratorNormalCompletion2 = true;
      var _didIteratorError2 = false;
      var _iteratorError2 = undefined;

      try {
        for (var _iterator2 = options[Symbol.iterator](), _step2; !(_iteratorNormalCompletion2 = (_step2 = _iterator2.next()).done); _iteratorNormalCompletion2 = true) {
          var currentOption = _step2.value;
          if (this.Options.DisableWhenTrue) this.Field.DisableOption(currentOption);else this.Field.EnableOption(currentOption);
        }
      } catch (err) {
        _didIteratorError2 = true;
        _iteratorError2 = err;
      } finally {
        try {
          if (!_iteratorNormalCompletion2 && _iterator2["return"] != null) {
            _iterator2["return"]();
          }
        } finally {
          if (_didIteratorError2) {
            throw _iteratorError2;
          }
        }
      }
    }
  }, {
    key: "InitializeWidthFields",
    value: function InitializeWidthFields(field, condition) {
      var chain = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : null;
      this.Field = field;
      return _get(_getPrototypeOf(ChangeImageConditionProcessor.prototype), "Initialize", this).call(this, field.FormBuilder, condition, chain);
    }
  }]);

  return ChangeImageConditionProcessor;
}(FormBuilder_Core_Conditions_ConditionProcessorBase__WEBPACK_IMPORTED_MODULE_0__["ConditionProcessorBase"]);
shared_core_Events_EventManager__WEBPACK_IMPORTED_MODULE_2__["EventManager"].Subscribe(FormBuilder_Events_InitializeAdditionalCondition__WEBPACK_IMPORTED_MODULE_3__["InitializeAdditionalCondition"], function (e) {
  if (e.Condition.Type == FormBuilder_Fields_Core_ConditionOptions__WEBPACK_IMPORTED_MODULE_1__["ConditionTypeEnum"].ChangeImage) return new ChangeImageConditionProcessor().InitializeWidthFields(e.Field, e.Condition, e.chain);
});

/***/ }),

/***/ "FormBuilder/Core/Conditions/ConditionProcessorBase":
/*!**************************************************************************************************!*\
  !*** external "RedNaoFormBuilder.default('FormBuilder/Core/Conditions/ConditionProcessorBase')" ***!
  \**************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = RedNaoFormBuilder.default('FormBuilder/Core/Conditions/ConditionProcessorBase');

/***/ }),

/***/ "FormBuilder/Events/InitializeAdditionalCondition":
/*!************************************************************************************************!*\
  !*** external "RedNaoFormBuilder.default('FormBuilder/Events/InitializeAdditionalCondition')" ***!
  \************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = RedNaoFormBuilder.default('FormBuilder/Events/InitializeAdditionalCondition');

/***/ }),

/***/ "FormBuilder/Fields/Core/ConditionOptions":
/*!****************************************************************************************!*\
  !*** external "RedNaoFormBuilder.default('FormBuilder/Fields/Core/ConditionOptions')" ***!
  \****************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = RedNaoFormBuilder.default('FormBuilder/Fields/Core/ConditionOptions');

/***/ }),

/***/ "shared/core/Events/EventManager":
/*!******************************************************************************!*\
  !*** external "RedNaoSharedCore.default('shared/core/Events/EventManager')" ***!
  \******************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = RedNaoSharedCore.default('shared/core/Events/EventManager');

/***/ })

/******/ });
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly9SZWROYW9bbmFtZV0vd2VicGFjay9ib290c3RyYXAiLCJ3ZWJwYWNrOi8vUmVkTmFvW25hbWVdLy4vc3JjL2FwcC9EeW5hbWljcy9Db25kaXRpb25zL0NoYW5nZUltYWdlQ29uZGl0aW9uL0NoYW5nZUltYWdlQ29uZGl0aW9uUHJvY2Vzc29yLnRzeCIsIndlYnBhY2s6Ly9SZWROYW9bbmFtZV0vZXh0ZXJuYWwgXCJSZWROYW9Gb3JtQnVpbGRlci5kZWZhdWx0KCdGb3JtQnVpbGRlci9Db3JlL0NvbmRpdGlvbnMvQ29uZGl0aW9uUHJvY2Vzc29yQmFzZScpXCIiLCJ3ZWJwYWNrOi8vUmVkTmFvW25hbWVdL2V4dGVybmFsIFwiUmVkTmFvRm9ybUJ1aWxkZXIuZGVmYXVsdCgnRm9ybUJ1aWxkZXIvRXZlbnRzL0luaXRpYWxpemVBZGRpdGlvbmFsQ29uZGl0aW9uJylcIiIsIndlYnBhY2s6Ly9SZWROYW9bbmFtZV0vZXh0ZXJuYWwgXCJSZWROYW9Gb3JtQnVpbGRlci5kZWZhdWx0KCdGb3JtQnVpbGRlci9GaWVsZHMvQ29yZS9Db25kaXRpb25PcHRpb25zJylcIiIsIndlYnBhY2s6Ly9SZWROYW9bbmFtZV0vZXh0ZXJuYWwgXCJSZWROYW9TaGFyZWRDb3JlLmRlZmF1bHQoJ3NoYXJlZC9jb3JlL0V2ZW50cy9FdmVudE1hbmFnZXInKVwiIl0sIm5hbWVzIjpbIkNoYW5nZUltYWdlQ29uZGl0aW9uUHJvY2Vzc29yIiwiT3B0aW9ucyIsIkZpZWxkIiwiZm9ybUJ1aWxkZXIiLCJjb25kaXRpb24iLCJFcnJvciIsImltbWVkaWF0ZSIsIm9wdGlvbnMiLCJPcHRpb25zVG9EaXNhYmxlIiwiY3VycmVudE9wdGlvbiIsIkRpc2FibGVXaGVuVHJ1ZSIsIkVuYWJsZU9wdGlvbiIsIkRpc2FibGVPcHRpb24iLCJmaWVsZCIsImNoYWluIiwiRm9ybUJ1aWxkZXIiLCJDb25kaXRpb25Qcm9jZXNzb3JCYXNlIiwiRXZlbnRNYW5hZ2VyIiwiU3Vic2NyaWJlIiwiSW5pdGlhbGl6ZUFkZGl0aW9uYWxDb25kaXRpb24iLCJlIiwiQ29uZGl0aW9uIiwiVHlwZSIsIkNvbmRpdGlvblR5cGVFbnVtIiwiQ2hhbmdlSW1hZ2UiLCJJbml0aWFsaXplV2lkdGhGaWVsZHMiXSwibWFwcGluZ3MiOiI7O1FBQUE7UUFDQTs7UUFFQTtRQUNBOztRQUVBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBOztRQUVBO1FBQ0E7O1FBRUE7UUFDQTs7UUFFQTtRQUNBO1FBQ0E7OztRQUdBO1FBQ0E7O1FBRUE7UUFDQTs7UUFFQTtRQUNBO1FBQ0E7UUFDQSwwQ0FBMEMsZ0NBQWdDO1FBQzFFO1FBQ0E7O1FBRUE7UUFDQTtRQUNBO1FBQ0Esd0RBQXdELGtCQUFrQjtRQUMxRTtRQUNBLGlEQUFpRCxjQUFjO1FBQy9EOztRQUVBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQSx5Q0FBeUMsaUNBQWlDO1FBQzFFLGdIQUFnSCxtQkFBbUIsRUFBRTtRQUNySTtRQUNBOztRQUVBO1FBQ0E7UUFDQTtRQUNBLDJCQUEyQiwwQkFBMEIsRUFBRTtRQUN2RCxpQ0FBaUMsZUFBZTtRQUNoRDtRQUNBO1FBQ0E7O1FBRUE7UUFDQSxzREFBc0QsK0RBQStEOztRQUVySDtRQUNBOzs7UUFHQTtRQUNBOzs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7QUNsRkE7QUFFQTtBQUVBO0FBQ0E7QUFLTyxJQUFNQSw2QkFBYjtBQUFBO0FBQUE7QUFBQTs7QUFBQTtBQUFBOztBQUFBOztBQUFBOztBQUFBO0FBQUE7QUFBQTs7QUFBQTtBQUFBLFVBQ1dDLE9BRFg7QUFBQSxVQUVXQyxLQUZYO0FBQUE7QUFBQTs7QUFBQTtBQUFBO0FBQUEsK0JBSXNCQyxXQUp0QixFQUkyREMsU0FKM0QsRUFJd0c7QUFDaEcsWUFBTUMsS0FBSyxDQUFDLG1FQUFELENBQVg7QUFDSDtBQU5MO0FBQUE7QUFBQSx1Q0FTdUJDLFNBVHZCLEVBUzJDO0FBQ25DLFVBQUlDLE9BQU8sR0FBQyxLQUFLTixPQUFMLENBQWFPLGdCQUF6QjtBQURtQztBQUFBO0FBQUE7O0FBQUE7QUFFbkMsNkJBQXlCRCxPQUF6Qiw4SEFDQTtBQUFBLGNBRFFFLGFBQ1I7QUFDSSxjQUFHLEtBQUtSLE9BQUwsQ0FBYVMsZUFBaEIsRUFDSSxLQUFLUixLQUFMLENBQVdTLFlBQVgsQ0FBd0JGLGFBQXhCLEVBREosS0FHSSxLQUFLUCxLQUFMLENBQVdVLGFBQVgsQ0FBeUJILGFBQXpCO0FBR1A7QUFWa0M7QUFBQTtBQUFBO0FBQUE7QUFBQTtBQUFBO0FBQUE7QUFBQTtBQUFBO0FBQUE7QUFBQTtBQUFBO0FBQUE7QUFBQTtBQVl0QztBQXJCTDtBQUFBO0FBQUEscUNBdUJxQkgsU0F2QnJCLEVBdUJ5QztBQUNqQyxVQUFJQyxPQUFPLEdBQUMsS0FBS04sT0FBTCxDQUFhTyxnQkFBekI7QUFEaUM7QUFBQTtBQUFBOztBQUFBO0FBRWpDLDhCQUF5QkQsT0FBekIsbUlBQ0E7QUFBQSxjQURRRSxhQUNSO0FBQ0ksY0FBRyxLQUFLUixPQUFMLENBQWFTLGVBQWhCLEVBQ0ksS0FBS1IsS0FBTCxDQUFXVSxhQUFYLENBQXlCSCxhQUF6QixFQURKLEtBR0ksS0FBS1AsS0FBTCxDQUFXUyxZQUFYLENBQXdCRixhQUF4QjtBQUdQO0FBVmdDO0FBQUE7QUFBQTtBQUFBO0FBQUE7QUFBQTtBQUFBO0FBQUE7QUFBQTtBQUFBO0FBQUE7QUFBQTtBQUFBO0FBQUE7QUFXcEM7QUFsQ0w7QUFBQTtBQUFBLDBDQXFDMEJJLEtBckMxQixFQXFDbURULFNBckNuRCxFQXFDdUg7QUFBQSxVQUFqQ1UsS0FBaUMsdUVBQVosSUFBWTtBQUMvRyxXQUFLWixLQUFMLEdBQVdXLEtBQVg7QUFDQSwyR0FBd0JBLEtBQUssQ0FBQ0UsV0FBOUIsRUFBMkNYLFNBQTNDLEVBQXFEVSxLQUFyRDtBQUNIO0FBeENMOztBQUFBO0FBQUEsRUFBbURFLHlHQUFuRDtBQTJDQUMsNEVBQVksQ0FBQ0MsU0FBYixDQUFzREMsOEdBQXRELEVBQW9GLFVBQUFDLENBQUMsRUFBRTtBQUNuRixNQUFHQSxDQUFDLENBQUNDLFNBQUYsQ0FBWUMsSUFBWixJQUFrQkMsMEZBQWlCLENBQUNDLFdBQXZDLEVBQ0ksT0FBTyxJQUFJeEIsNkJBQUosR0FBb0N5QixxQkFBcEMsQ0FBMERMLENBQUMsQ0FBQ2xCLEtBQTVELEVBQWtFa0IsQ0FBQyxDQUFDQyxTQUFwRSxFQUF5R0QsQ0FBQyxDQUFDTixLQUEzRyxDQUFQO0FBRVAsQ0FKRCxFOzs7Ozs7Ozs7OztBQ3JEQSxpRzs7Ozs7Ozs7Ozs7QUNBQSwrRjs7Ozs7Ozs7Ozs7QUNBQSx1Rjs7Ozs7Ozs7Ozs7QUNBQSw2RSIsImZpbGUiOiJDaGFuZ2VJbWFnZUNvbmRpdGlvbl9idW5kbGUuanMiLCJzb3VyY2VzQ29udGVudCI6WyIgXHQvLyBUaGUgbW9kdWxlIGNhY2hlXG4gXHR2YXIgaW5zdGFsbGVkTW9kdWxlcyA9IHt9O1xuXG4gXHQvLyBUaGUgcmVxdWlyZSBmdW5jdGlvblxuIFx0ZnVuY3Rpb24gX193ZWJwYWNrX3JlcXVpcmVfXyhtb2R1bGVJZCkge1xuXG4gXHRcdC8vIENoZWNrIGlmIG1vZHVsZSBpcyBpbiBjYWNoZVxuIFx0XHRpZihpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXSkge1xuIFx0XHRcdHJldHVybiBpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXS5leHBvcnRzO1xuIFx0XHR9XG4gXHRcdC8vIENyZWF0ZSBhIG5ldyBtb2R1bGUgKGFuZCBwdXQgaXQgaW50byB0aGUgY2FjaGUpXG4gXHRcdHZhciBtb2R1bGUgPSBpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXSA9IHtcbiBcdFx0XHRpOiBtb2R1bGVJZCxcbiBcdFx0XHRsOiBmYWxzZSxcbiBcdFx0XHRleHBvcnRzOiB7fVxuIFx0XHR9O1xuXG4gXHRcdC8vIEV4ZWN1dGUgdGhlIG1vZHVsZSBmdW5jdGlvblxuIFx0XHRtb2R1bGVzW21vZHVsZUlkXS5jYWxsKG1vZHVsZS5leHBvcnRzLCBtb2R1bGUsIG1vZHVsZS5leHBvcnRzLCBfX3dlYnBhY2tfcmVxdWlyZV9fKTtcblxuIFx0XHQvLyBGbGFnIHRoZSBtb2R1bGUgYXMgbG9hZGVkXG4gXHRcdG1vZHVsZS5sID0gdHJ1ZTtcblxuIFx0XHQvLyBSZXR1cm4gdGhlIGV4cG9ydHMgb2YgdGhlIG1vZHVsZVxuIFx0XHRyZXR1cm4gbW9kdWxlLmV4cG9ydHM7XG4gXHR9XG5cblxuIFx0Ly8gZXhwb3NlIHRoZSBtb2R1bGVzIG9iamVjdCAoX193ZWJwYWNrX21vZHVsZXNfXylcbiBcdF9fd2VicGFja19yZXF1aXJlX18ubSA9IG1vZHVsZXM7XG5cbiBcdC8vIGV4cG9zZSB0aGUgbW9kdWxlIGNhY2hlXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLmMgPSBpbnN0YWxsZWRNb2R1bGVzO1xuXG4gXHQvLyBkZWZpbmUgZ2V0dGVyIGZ1bmN0aW9uIGZvciBoYXJtb255IGV4cG9ydHNcbiBcdF9fd2VicGFja19yZXF1aXJlX18uZCA9IGZ1bmN0aW9uKGV4cG9ydHMsIG5hbWUsIGdldHRlcikge1xuIFx0XHRpZighX193ZWJwYWNrX3JlcXVpcmVfXy5vKGV4cG9ydHMsIG5hbWUpKSB7XG4gXHRcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsIG5hbWUsIHsgZW51bWVyYWJsZTogdHJ1ZSwgZ2V0OiBnZXR0ZXIgfSk7XG4gXHRcdH1cbiBcdH07XG5cbiBcdC8vIGRlZmluZSBfX2VzTW9kdWxlIG9uIGV4cG9ydHNcbiBcdF9fd2VicGFja19yZXF1aXJlX18uciA9IGZ1bmN0aW9uKGV4cG9ydHMpIHtcbiBcdFx0aWYodHlwZW9mIFN5bWJvbCAhPT0gJ3VuZGVmaW5lZCcgJiYgU3ltYm9sLnRvU3RyaW5nVGFnKSB7XG4gXHRcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsIFN5bWJvbC50b1N0cmluZ1RhZywgeyB2YWx1ZTogJ01vZHVsZScgfSk7XG4gXHRcdH1cbiBcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsICdfX2VzTW9kdWxlJywgeyB2YWx1ZTogdHJ1ZSB9KTtcbiBcdH07XG5cbiBcdC8vIGNyZWF0ZSBhIGZha2UgbmFtZXNwYWNlIG9iamVjdFxuIFx0Ly8gbW9kZSAmIDE6IHZhbHVlIGlzIGEgbW9kdWxlIGlkLCByZXF1aXJlIGl0XG4gXHQvLyBtb2RlICYgMjogbWVyZ2UgYWxsIHByb3BlcnRpZXMgb2YgdmFsdWUgaW50byB0aGUgbnNcbiBcdC8vIG1vZGUgJiA0OiByZXR1cm4gdmFsdWUgd2hlbiBhbHJlYWR5IG5zIG9iamVjdFxuIFx0Ly8gbW9kZSAmIDh8MTogYmVoYXZlIGxpa2UgcmVxdWlyZVxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy50ID0gZnVuY3Rpb24odmFsdWUsIG1vZGUpIHtcbiBcdFx0aWYobW9kZSAmIDEpIHZhbHVlID0gX193ZWJwYWNrX3JlcXVpcmVfXyh2YWx1ZSk7XG4gXHRcdGlmKG1vZGUgJiA4KSByZXR1cm4gdmFsdWU7XG4gXHRcdGlmKChtb2RlICYgNCkgJiYgdHlwZW9mIHZhbHVlID09PSAnb2JqZWN0JyAmJiB2YWx1ZSAmJiB2YWx1ZS5fX2VzTW9kdWxlKSByZXR1cm4gdmFsdWU7XG4gXHRcdHZhciBucyA9IE9iamVjdC5jcmVhdGUobnVsbCk7XG4gXHRcdF9fd2VicGFja19yZXF1aXJlX18ucihucyk7XG4gXHRcdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShucywgJ2RlZmF1bHQnLCB7IGVudW1lcmFibGU6IHRydWUsIHZhbHVlOiB2YWx1ZSB9KTtcbiBcdFx0aWYobW9kZSAmIDIgJiYgdHlwZW9mIHZhbHVlICE9ICdzdHJpbmcnKSBmb3IodmFyIGtleSBpbiB2YWx1ZSkgX193ZWJwYWNrX3JlcXVpcmVfXy5kKG5zLCBrZXksIGZ1bmN0aW9uKGtleSkgeyByZXR1cm4gdmFsdWVba2V5XTsgfS5iaW5kKG51bGwsIGtleSkpO1xuIFx0XHRyZXR1cm4gbnM7XG4gXHR9O1xuXG4gXHQvLyBnZXREZWZhdWx0RXhwb3J0IGZ1bmN0aW9uIGZvciBjb21wYXRpYmlsaXR5IHdpdGggbm9uLWhhcm1vbnkgbW9kdWxlc1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5uID0gZnVuY3Rpb24obW9kdWxlKSB7XG4gXHRcdHZhciBnZXR0ZXIgPSBtb2R1bGUgJiYgbW9kdWxlLl9fZXNNb2R1bGUgP1xuIFx0XHRcdGZ1bmN0aW9uIGdldERlZmF1bHQoKSB7IHJldHVybiBtb2R1bGVbJ2RlZmF1bHQnXTsgfSA6XG4gXHRcdFx0ZnVuY3Rpb24gZ2V0TW9kdWxlRXhwb3J0cygpIHsgcmV0dXJuIG1vZHVsZTsgfTtcbiBcdFx0X193ZWJwYWNrX3JlcXVpcmVfXy5kKGdldHRlciwgJ2EnLCBnZXR0ZXIpO1xuIFx0XHRyZXR1cm4gZ2V0dGVyO1xuIFx0fTtcblxuIFx0Ly8gT2JqZWN0LnByb3RvdHlwZS5oYXNPd25Qcm9wZXJ0eS5jYWxsXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLm8gPSBmdW5jdGlvbihvYmplY3QsIHByb3BlcnR5KSB7IHJldHVybiBPYmplY3QucHJvdG90eXBlLmhhc093blByb3BlcnR5LmNhbGwob2JqZWN0LCBwcm9wZXJ0eSk7IH07XG5cbiBcdC8vIF9fd2VicGFja19wdWJsaWNfcGF0aF9fXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLnAgPSBcIlwiO1xuXG5cbiBcdC8vIExvYWQgZW50cnkgbW9kdWxlIGFuZCByZXR1cm4gZXhwb3J0c1xuIFx0cmV0dXJuIF9fd2VicGFja19yZXF1aXJlX18oX193ZWJwYWNrX3JlcXVpcmVfXy5zID0gXCIuL3NyYy9hcHAvRHluYW1pY3MvQ29uZGl0aW9ucy9DaGFuZ2VJbWFnZUNvbmRpdGlvbi9DaGFuZ2VJbWFnZUNvbmRpdGlvblByb2Nlc3Nvci50c3hcIik7XG4iLCJpbXBvcnQge0NvbmRpdGlvblByb2Nlc3NvckJhc2V9IGZyb20gXCJGb3JtQnVpbGRlci9Db3JlL0NvbmRpdGlvbnMvQ29uZGl0aW9uUHJvY2Vzc29yQmFzZVwiO1xyXG5pbXBvcnQge0ZCRmllbGRCYXNlTW9kZWx9IGZyb20gXCJGb3JtQnVpbGRlci9GaWVsZHMvQ29yZS9GQkZpZWxkQmFzZS5Nb2RlbFwiO1xyXG5pbXBvcnQge0NvbmRpdGlvblR5cGVFbnVtLCBWYWxpZGF0aW9uQ29uZGl0aW9uT3B0aW9uc30gZnJvbSBcIkZvcm1CdWlsZGVyL0ZpZWxkcy9Db3JlL0NvbmRpdGlvbk9wdGlvbnNcIjtcclxuaW1wb3J0IHtEaXNhYmxlQ29uZGl0aW9uT3B0aW9uc30gZnJvbSBcIkZvcm1CdWlsZGVyL0ZpZWxkcy9Db3JlL0NvbmRpdGlvbnMvRGlzYWJsZUNvbmRpdGlvbk9wdGlvbnNcIjtcclxuaW1wb3J0IHtFdmVudE1hbmFnZXJ9IGZyb20gXCJzaGFyZWQvY29yZS9FdmVudHMvRXZlbnRNYW5hZ2VyXCI7XHJcbmltcG9ydCB7SW5pdGlhbGl6ZUFkZGl0aW9uYWxDb25kaXRpb259IGZyb20gXCJGb3JtQnVpbGRlci9FdmVudHMvSW5pdGlhbGl6ZUFkZGl0aW9uYWxDb25kaXRpb25cIjtcclxuaW1wb3J0IHtDb250YWluZXJEYXRhUmV0cmlldmVyfSBmcm9tIFwiRm9ybUJ1aWxkZXIvQ29yZS9Gb3JtQnVpbGRlci5Nb2RlbFwiO1xyXG5pbXBvcnQge0ZCTXVsdGlwbGVPcHRpb25zQmFzZU1vZGVsfSBmcm9tIFwiRm9ybUJ1aWxkZXIvRmllbGRzL0NvcmUvRkJNdWx0aXBsZU9wdGlvbnNCYXNlLk1vZGVsXCI7XHJcbmltcG9ydCB7RXhlY3V0aW9uQ2hhaW59IGZyb20gXCJGb3JtQnVpbGRlci9Db3JlL0V4ZWN1dGlvbkNoYWluXCI7XHJcblxyXG5leHBvcnQgY2xhc3MgQ2hhbmdlSW1hZ2VDb25kaXRpb25Qcm9jZXNzb3IgZXh0ZW5kcyBDb25kaXRpb25Qcm9jZXNzb3JCYXNle1xyXG4gICAgcHVibGljIE9wdGlvbnM6RGlzYWJsZUNvbmRpdGlvbk9wdGlvbnM7XHJcbiAgICBwdWJsaWMgRmllbGQ6RkJNdWx0aXBsZU9wdGlvbnNCYXNlTW9kZWw7XHJcblxyXG4gICAgcHVibGljIEluaXRpYWxpemUoZm9ybUJ1aWxkZXI6IENvbnRhaW5lckRhdGFSZXRyaWV2ZXIsIGNvbmRpdGlvbjogVmFsaWRhdGlvbkNvbmRpdGlvbk9wdGlvbnMpOiB0aGlzIHtcclxuICAgICAgICB0aHJvdyBFcnJvcihcIkludmFsaWQgaW5pdGlhbGl6YXRpb24sIHBsZWFzZSB1c2UgaW5pdGlhbGl6ZSB3aXRoIGZpZWxkcyBpbnN0ZWFkXCIpO1xyXG4gICAgfVxyXG5cclxuXHJcbiAgICBFeGVjdXRlRmFsc2VBY3Rpb24oaW1tZWRpYXRlOiBib29sZWFuKSB7XHJcbiAgICAgICAgbGV0IG9wdGlvbnM9dGhpcy5PcHRpb25zLk9wdGlvbnNUb0Rpc2FibGU7XHJcbiAgICAgICAgZm9yKGxldCBjdXJyZW50T3B0aW9uIG9mIG9wdGlvbnMpXHJcbiAgICAgICAge1xyXG4gICAgICAgICAgICBpZih0aGlzLk9wdGlvbnMuRGlzYWJsZVdoZW5UcnVlKVxyXG4gICAgICAgICAgICAgICAgdGhpcy5GaWVsZC5FbmFibGVPcHRpb24oY3VycmVudE9wdGlvbik7XHJcbiAgICAgICAgICAgIGVsc2VcclxuICAgICAgICAgICAgICAgIHRoaXMuRmllbGQuRGlzYWJsZU9wdGlvbihjdXJyZW50T3B0aW9uKTtcclxuXHJcblxyXG4gICAgICAgIH1cclxuXHJcbiAgICB9XHJcblxyXG4gICAgRXhlY3V0ZVRydUFjdGlvbihpbW1lZGlhdGU6IGJvb2xlYW4pIHtcclxuICAgICAgICBsZXQgb3B0aW9ucz10aGlzLk9wdGlvbnMuT3B0aW9uc1RvRGlzYWJsZTtcclxuICAgICAgICBmb3IobGV0IGN1cnJlbnRPcHRpb24gb2Ygb3B0aW9ucylcclxuICAgICAgICB7XHJcbiAgICAgICAgICAgIGlmKHRoaXMuT3B0aW9ucy5EaXNhYmxlV2hlblRydWUpXHJcbiAgICAgICAgICAgICAgICB0aGlzLkZpZWxkLkRpc2FibGVPcHRpb24oY3VycmVudE9wdGlvbik7XHJcbiAgICAgICAgICAgIGVsc2VcclxuICAgICAgICAgICAgICAgIHRoaXMuRmllbGQuRW5hYmxlT3B0aW9uKGN1cnJlbnRPcHRpb24pO1xyXG5cclxuXHJcbiAgICAgICAgfVxyXG4gICAgfVxyXG5cclxuXHJcbiAgICBJbml0aWFsaXplV2lkdGhGaWVsZHMoZmllbGQ6IEZCRmllbGRCYXNlTW9kZWwsIGNvbmRpdGlvbjogRGlzYWJsZUNvbmRpdGlvbk9wdGlvbnMsY2hhaW46RXhlY3V0aW9uQ2hhaW49bnVsbCk6IHRoaXMge1xyXG4gICAgICAgIHRoaXMuRmllbGQ9ZmllbGQgYXMgYW55O1xyXG4gICAgICAgIHJldHVybiBzdXBlci5Jbml0aWFsaXplKGZpZWxkLkZvcm1CdWlsZGVyLCBjb25kaXRpb24sY2hhaW4pO1xyXG4gICAgfVxyXG59XHJcblxyXG5FdmVudE1hbmFnZXIuU3Vic2NyaWJlPEluaXRpYWxpemVBZGRpdGlvbmFsQ29uZGl0aW9uPihJbml0aWFsaXplQWRkaXRpb25hbENvbmRpdGlvbixlPT57XHJcbiAgICBpZihlLkNvbmRpdGlvbi5UeXBlPT1Db25kaXRpb25UeXBlRW51bS5DaGFuZ2VJbWFnZSlcclxuICAgICAgICByZXR1cm4gbmV3IENoYW5nZUltYWdlQ29uZGl0aW9uUHJvY2Vzc29yKCkuSW5pdGlhbGl6ZVdpZHRoRmllbGRzKGUuRmllbGQsZS5Db25kaXRpb24gYXMgRGlzYWJsZUNvbmRpdGlvbk9wdGlvbnMsZS5jaGFpbik7XHJcblxyXG59KSIsIm1vZHVsZS5leHBvcnRzID0gUmVkTmFvRm9ybUJ1aWxkZXIuZGVmYXVsdCgnRm9ybUJ1aWxkZXIvQ29yZS9Db25kaXRpb25zL0NvbmRpdGlvblByb2Nlc3NvckJhc2UnKTsiLCJtb2R1bGUuZXhwb3J0cyA9IFJlZE5hb0Zvcm1CdWlsZGVyLmRlZmF1bHQoJ0Zvcm1CdWlsZGVyL0V2ZW50cy9Jbml0aWFsaXplQWRkaXRpb25hbENvbmRpdGlvbicpOyIsIm1vZHVsZS5leHBvcnRzID0gUmVkTmFvRm9ybUJ1aWxkZXIuZGVmYXVsdCgnRm9ybUJ1aWxkZXIvRmllbGRzL0NvcmUvQ29uZGl0aW9uT3B0aW9ucycpOyIsIm1vZHVsZS5leHBvcnRzID0gUmVkTmFvU2hhcmVkQ29yZS5kZWZhdWx0KCdzaGFyZWQvY29yZS9FdmVudHMvRXZlbnRNYW5hZ2VyJyk7Il0sInNvdXJjZVJvb3QiOiIifQ==