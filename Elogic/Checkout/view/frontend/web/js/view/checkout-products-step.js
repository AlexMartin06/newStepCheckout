define(
    [
        'ko',
        'uiComponent',
        'underscore',
        'Magento_Checkout/js/model/step-navigator',
        'Magento_Customer/js/model/customer',
        'mage/url',
        'Magento_Checkout/js/model/quote',
        'Magento_Catalog/js/price-utils'
    ],
    function (
        ko,
        Component,
        _,
        stepNavigator,
        customer,
        url,
        quote,
        priceUtils
    ) {
        'use strict';

        return Component.extend({
            defaults: {
                template: 'Elogic_Checkout/checkout-products'
            },

            isVisible: ko.observable(true),
            stepCode: 'checkoutProducts',
            stepTitle: window.checkoutConfig.customStepName,

            /**
             *
             * @returns {*}
             * @param config
             */
            initialize: function (config) {
                this._super();

                stepNavigator.registerStep(
                    this.stepCode,
                    null,
                    this.stepTitle,
                    this.isVisible,
                    _.bind(this.navigate, this),
                    5
                );

                this.other_value = config.other_value;

                return this;
            },

            getProducts: function () {
                Object.values(window.checkoutConfig.newStep.products).map((product, index) => {
                    product.productPostData = window.checkoutConfig.newStep.productsPostData[index];
                });
                return Object.values(window.checkoutConfig.newStep.products);
            },

            getBaseUrl: function () {
                url.setBaseUrl(BASE_URL);
                return url.build('pub/media/catalog/product');
            },

            /**
             * @param {*} price
             * @return {*|String}
             */
            getFormattedPrice: function (price) {
                //todo add format data
                return priceUtils.formatPrice(price, quote.getPriceFormat());
            },

            navigate: function () {},

            /**
             * @returns void
             */
            navigateToNextStep: function () {
                stepNavigator.next();
            }
        });
    }
);
