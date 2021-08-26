define([
    'jquery',
    'uiComponent',
    'ko'
], function($, Component, ko){
    "use strict";
    return Component.extend({
        defaults: {
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
            this.observePostUser();
            this.observeGetUser();
            this.observeGetProducts();
            this.observeSubmitButton();
            return this;
        },

        resetHtmlElementsVisibility: function () {
            this.form.style.display = "none";
            this.labelId.style.display = "none";
            this.labelWeatherType.style.display = "none";
            this.submitButton.style.display = "none";
            this.spanOutput.style.display = "none";
        },

        getHtmlElementsData: function () {
            this.form = document.getElementsByClassName("form")[0];
            this.labelId = document.getElementsByClassName("label-id")[0];
            this.labelWeatherType = document.getElementsByClassName("label-weathertype")[0];
            this.submitButton = document.getElementsByClassName("submit-button")[0];
            this.spanOutput = document.getElementsByClassName("span-output")[0];
            this.postUserButton = document.getElementsByName("postUser")[0];
            this.getUserButton = document.getElementsByName("getUser")[0];
            this.getProductsButton = document.getElementsByName("getProducts")[0];
        },

        enableHtmlElements: function () {
            this.resetHtmlElementsVisibility();
            this.form.style.display = "block";
            this.submitButton.style.display = "block";
            $(".form").show();
        },

        observePostUser: function () {
            let self = this;
            this.postUserButton.addEventListener("click", function() {
                self.requestType = 0;
                self.enableHtmlElements();
                self.labelId.style.display = "block";
                self.labelWeatherType.style.display = "block";
            }, false);
        },

        observeGetUser: function () {
            let self = this;
            this.getUserButton.addEventListener("click", function() {
                self.requestType = 1;
                self.enableHtmlElements();
                self.labelId.style.display = "block";
            }, false);
        },

        observeGetProducts: function () {
            let self = this;
            this.getProductsButton.addEventListener("click", function() {
                self.requestType = 2;
                self.enableHtmlElements();
                self.labelWeatherType.style.display = "block";
            }, false);
        },

        observeSubmitButton: function() {
            let self = this;
            this.submitButton.addEventListener("click", function() {
                self.userId = document.getElementsByClassName("user-id")[0].value;
                self.weatherTypeId = document.getElementsByClassName("weathertype-id")[0].value;
                if (!Number.isInteger(Number(self.userId))) {
                    alert('ID must be a positive integer, input given: ' + self.userId + '. Please try again');
                    return;
                }
                if (self.requestType !== 0) {
                    self.spanOutput.style.display = "block";
                }
                self.runAjaxRequest();
            }, false);
        },

        getCustomerById: function () {
            let self = this;
            $('body').trigger('processStart');
            $.ajax({
                url: this.generateRestApiUrl(),
                type: 'GET',
                dataType: 'json',
                cache: true,
                showLoader: true,
                success: function (data) {
                    self.outputData(JSON.stringify(data));
                    $('body').trigger('processStop');
                },
                error: function () {
                    alert("An error ocurred. User with ID: " + self.userId + " does not exist");
                    $('body').trigger('processStop');
                }
            });
        },

        getProductsByWeatherType: function () {
            let self = this;
            $('body').trigger('processStart');
            $.ajax({
                url: this.generateRestApiUrl(),
                type: 'GET',
                dataType: 'json',
                cache: true,
                showLoader: true
            }).done(function (data) {
                let products = self.getProductValuesFrom2DArray(data);
                self.outputData(JSON.stringify(products));
                $('body').trigger('processStop');
            });
        },

        postCustomerWeatherType: function () {
            let self = this;
            $('body').trigger('processStart');
            $.ajax({
                url: this.generateRestApiUrl(),
                type: 'POST',
                dataType: 'json',
                cache: true,
                showLoader: true,
                success: function() {
                    alert("Users: " + self.userId + " Weather Type was successfully changed");
                    $('body').trigger('processStop');
                },
                error: function () {
                    alert("An error ocurred. Weather Type of user: " + self.userId + " was not changed");
                    $('body').trigger('processStop');
                }
            });
        },

        runAjaxRequest: function () {
            switch (this.requestType) {
                case 0:
                    this.postCustomerWeatherType();
                    break;
                case 1:
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
                case 0:
                    url = this.url + 'rest/V1/api/user/' + this.userId + '/' + this.weatherTypeId;
                    break;
                case 1:
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
