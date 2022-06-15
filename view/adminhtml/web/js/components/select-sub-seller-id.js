define([
    'Magento_Ui/js/form/element/ui-select'
], function (Select) {
    'use strict';
    return Select.extend({
        /**
         * Parse data and set it to options.
         *
         * @param {Object} data - Response data object.
         * @returns {Object}
         */
        setParsed: function (data) {
            console.log("caralho");
            var option = this.parseData(data);
            if (data.error) {
                return this;
            }
            this.options([]);
            this.setOption(option);
            this.set('newOption', option);
        }
    });
});