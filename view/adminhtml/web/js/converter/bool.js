/*eslint-disable */
/* jscs:disable */
define(["underscore", "Magento_PageBuilder/js/utils/object"], function (_underscore, _object) {
    /**
     * Copyright © Magento, Inc. All rights reserved.
     * See COPYING.txt for license details.
     */
    var BoolConverter = /*#__PURE__*/function () {
        "use strict";

        function BoolConverter() {}

        var _proto = BoolConverter.prototype;

        /**
         * Convert value to internal format
         *
         * @param {string} value
         * @returns {string | object}
         */
        _proto.fromDom = function fromDom(value) {
            var result = (0, _underscore.unescape)(value);
            return (result === 'false') ? '' : result;
        };

        /**
         * Convert value to knockout format
         *
         * @param {string} name
         * @param {Object} data
         * @returns {string}
         */
        _proto.toDom = function toDom(name, data) {
            return (0, _object.get)(data, name).length > 0 ? 'true' : 'false';
        };

        return BoolConverter;
    }();

    return BoolConverter;
});
