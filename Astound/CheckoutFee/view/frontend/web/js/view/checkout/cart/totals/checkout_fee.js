define([
    'ko',
    'uiComponent',
    'Magento_Checkout/js/model/quote',
    'Magento_Catalog/js/price-utils',
    'Magento_Checkout/js/model/totals'

], function (ko, Component, quote, priceUtils, totals) {
    'use strict';
    var show_hide_checkout_fee_blockConfig = window.checkoutConfig.show_hide_checkout_fee_block;
    var fee_label = window.checkoutConfig.fee_label;

    return Component.extend({
        totals: quote.getTotals(),
        canVisibleCustomFeeBlock: show_hide_checkout_fee_blockConfig,
        feeLabel: ko.observable(fee_label),
        getValue: function () {
            var price = 0;
            if (this.totals() && totals.getSegment('fee')) {
                price = totals.getSegment('fee').value;
            }

            return priceUtils.formatPrice(price, quote.getBasePriceFormat());
        }
    });
});
