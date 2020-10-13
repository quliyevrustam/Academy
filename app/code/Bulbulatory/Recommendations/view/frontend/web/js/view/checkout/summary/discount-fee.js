define(
    [
        'jquery',
        'Magento_Checkout/js/view/summary/abstract-total',
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/model/totals',
        'Magento_Catalog/js/price-utils'
    ],
    function ($, Component, quote, totals, priceUtils) {
        "use strict";
        return Component.extend({
            defaults: {
                template: 'Bulbulatory_Recommendations/checkout/summary/discount-fee'
            },

            totals: quote.getTotals(),

            isDisplayedDiscountTotal: function ()
            {
                var recommendation_discount = totals.getSegment('custom_recommendation_discount').value;

                return recommendation_discount !== undefined && Math.abs(recommendation_discount) !== 0;
            },

            getDiscountTotal: function ()
            {
                var price = totals.getSegment('custom_recommendation_discount').value;

                return this.getFormattedPrice(price);
            }
        });
    }
);