define([
    'jquery',
    'uiComponent',
    'ko',
    'mage/translate'
], function($, Component, ko, $t){
    "use strict";
    return Component.extend({
        defaults: {
            PARAM_REQUEST_TYPE_POST_USER: 0,
            PARAM_REQUEST_TYPE_GET_USER: 1,
            PARAM_REQUEST_TYPE_GET_PRODUCTS: 2,
            url: '',
            form: '',
            labelId: '',
            labelWeatherType: '',
            submitButton: '',
            spanOutput: '',
            postUserButton: '',
            getUserButton: '',
            getProductsButton: '',
            requestType: 0,
            weatherTypeId: null,
            userId: null,
            outputData: ko.observable()
        },

        initialize: function () {
            this._super();
            this.getHtmlElementsData();

            return this;
        },

        resetHtmlElementsVisibility: function () {
            $(this.form).hide();
            $(this.labelId).hide();
            $(this.labelWeatherType).hide();
            $(this.submitButton).hide();
            $(this.spanOutput).hide();
        },

        getHtmlElementsData: function () {
            this.form = $('.form').get();
            this.labelId = $('.content-id').get();
            this.labelWeatherType = $('.content-weathertype').get();
            this.submitButton = $('.submit-data').get();
            this.spanOutput = $('.output').get();
            this.postUserButton = $('.postUser').get();
            this.getUserButton = $('.getUser').get();
            this.getProductsButton = $('.getProducts').get();
        },

        enableHtmlElements: function () {
            this.resetHtmlElementsVisibility();
            $(this.form).show();
            $(this.submitButton).show();
        },

        clickedPostUser: function () {
            this.requestType = this.PARAM_REQUEST_TYPE_POST_USER;
            this.enableHtmlElements();
            $(this.labelId).show();
            $(this.labelWeatherType).show();
        },

        clickedGetUser: function () {
            this.requestType = this.PARAM_REQUEST_TYPE_GET_USER;
            this.enableHtmlElements();
            $(this.labelId).show();
        },

        clickedGetProducts: function () {
            this.requestType = this.PARAM_REQUEST_TYPE_GET_PRODUCTS;
            this.enableHtmlElements();
            $(this.labelWeatherType).show();
        },

        clickedSubmitButton: function() {
            this.userId = $('.user-id').val();
            this.weatherTypeId = $('.weathertype-id').val();
            if (!Number.isInteger(Number(this.userId))) {
                alert($t('ID must be a positive integer, input given: ') + this.userId + $t('. Please try again'));

                return;
            }
            if (this.requestType !== 0) {
                $(this.spanOutput).show();
            }
            this.runAjaxRequest();
        },

        getCustomerById: function () {
            let self = this;
            $.ajax({
                url: this.generateRestApiUrl(),
                type: 'GET',
                dataType: 'json',
                cache: true,
                showLoader: true,
                beforeSend: function () {
                    $('body').trigger('processStart');
                },
                complete: function () {
                    $('body').trigger('processStop');
                }
            }).done(function (data) {
                self.outputData(JSON.stringify(data));
            }).fail(function () {
                alert($t("An error occurred. User with ID: ") + self.userId + $t(" does not exist"));
            });
        },

        getProductsByWeatherType: function () {
            let self = this;
            $.ajax({
                url: this.generateRestApiUrl(),
                type: 'GET',
                dataType: 'json',
                cache: true,
                showLoader: true,
                beforeSend: function () {
                    $('body').trigger('processStart');
                },
                complete: function () {
                    $('body').trigger('processStop');
                }
            }).done(function (data) {
                let products = self.getProductValuesFrom2DArray(data);
                self.outputData(JSON.stringify(products));
            });
        },

        postCustomerWeatherType: function () {
            let self = this;
            $.ajax({
                url: this.generateRestApiUrl(),
                type: 'POST',
                dataType: 'json',
                cache: true,
                showLoader: true,
                beforeSend: function () {
                    $('body').trigger('processStart');
                },
                complete: function () {
                    $('body').trigger('processStop');
                }
            }).done(function () {
                alert($t("Operation has been completed"));
            }).fail(function () {
                alert($t("An error occurred. Weather Type of user: ") + self.userId + $t(" was not changed"));
            });
        },

        runAjaxRequest: function () {
            switch (this.requestType) {
                case this.PARAM_REQUEST_TYPE_POST_USER:
                    this.postCustomerWeatherType();
                    break;
                case this.PARAM_REQUEST_TYPE_GET_USER:
                    this.getCustomerById();
                    break;
                default:
                    this.getProductsByWeatherType();
                    break;
            }
        },

        generateRestApiUrl: function() {
            let url;
            switch (this.requestType) {
                case this.PARAM_REQUEST_TYPE_POST_USER:
                    url = this.url + 'rest/V1/api/user/' + this.userId + '/' + this.weatherTypeId;
                    break;
                case this.PARAM_REQUEST_TYPE_GET_USER:
                    url = this.url + 'rest/V1/api/user/' + this.userId;
                    break;
                default:
                    url = this.url + 'rest/V1/api/product-weather' +
                        '?searchCriteria[filter_groups][0][filters][0][field]=product_weathertype&searchCriteria[filter_groups][0][filters][0][value]=' + this.weatherTypeId;
                    break;
            }

            return url;
        },

        getProductValuesFrom2DArray: function (data) {
            let productsArray = [];
            $.each(data, function() {
                $.each(this, function(key, value) {
                    productsArray.push(["id: " + value['id'], "sku: " + value['sku'], "name: " + value['name']]);
                });

                return false;
            });

            return productsArray;
        }
    });
});
