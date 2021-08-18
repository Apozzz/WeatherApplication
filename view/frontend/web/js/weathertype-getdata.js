define([
    'jquery',
    'uiComponent',
    'ko'
], function($, Component, ko){
    "use strict";
    return Component.extend({
        defaults: {
            weatherType: '',
            url: '',
            fetchSize: 1,
            isAllWeathers: ko.observable(true),
            paginated: ko.observableArray([]),
            pageNumber: ko.observable(0),
            sizePerPage: ko.observable(1),
            totalPagesCount: ko.observable(),
            hasPrevious: ko.observable(),
            hasNext: ko.observable(),
            returnData: ko.observableArray(['Getting Data'])
        },

        initialize: function () {
            this._super();
            this.initDataFetchFunctions();
            return this;
        },

        initDataFetchFunctions: function () {
            this.fetchSize = this.sizePerPage() * 2;
            if (this.weatherType === null) {
                this.getAllWeatherTypeRecords();
                return;
            } else {
                this.isAllWeathers(false);
                this.getGivenWeatherTypeRecord();
            }
        },

        getGivenWeatherTypeRecord: function () {
            let self = this;
            $('body').trigger('processStart');
            $.ajax({
                url: this.generateRestApiUrl(),
                type: 'GET',
                dataType: 'json',
                cache: true,
                showLoader: true,
                error: function () {
                    $('body').trigger('processStop');
                    self.isAllWeathers(true);
                    self.getAllWeatherTypeRecords();
                }
            }).done(function (data) {
                self.returnData(self.processData(data));
                self.totalPagesCount(self.getTotalPages());
                self.paginated(self.returnData());
                $('body').trigger('processStop');
            });
        },

        getAllWeatherTypeRecords: function () {
            let self = this;
            $('body').trigger('processStart');
            $.ajax({
                url: this.generateRestApiUrl(),
                type: 'GET',
                dataType: 'json',
                cache: true,
                showLoader: true
            }).done(function (data) {
                self.returnData(self.processData(data));
                self.totalPagesCount(self.getTotalPages());
                self.paginated(self.getPaginated(self.returnData()));
                $('body').trigger('processStop');
            });
        },

        getHasPrevious: function() {
            return this.pageNumber() !== 0;
        },

        getHasNext: function() {
            return this.pageNumber() !== this.totalPagesCount();
        },

        next: function() {
            if (this.pageNumber() + 1 === this.totalPagesCount() && this.fetchSize <= this.returnData().length) {
                this.fetchSize = Math.round(this.fetchSize * 1.5);
                this.afterFetchChange();
            }

            if(this.pageNumber() < this.totalPagesCount()) {
                this.pageNumber(this.pageNumber() + 1);
                this.paginated(this.getPaginated());
            }
        },

        previous: function() {
            if(this.pageNumber() !== 0) {
                this.pageNumber(this.pageNumber() - 1);
                this.paginated(this.getPaginated());
            }
        },

        getTotalPages: function() {
            let div = Math.floor(this.returnData().length / this.sizePerPage());
            div += this.returnData().length % this.sizePerPage() > 0 ? 1 : 0;

            return div - 1;
        },

        getPaginated: function() {
            const first = this.pageNumber() * this.sizePerPage();

            return this.returnData().slice(first, Number(first) + Number(this.sizePerPage()));
        },

        recalculate: function() {
            this.fetchSize = (this.sizePerPage() * 2);
            this.afterFetchChange();
            if (this.pageNumber() > this.totalPagesCount()) {
                this.pageNumber(this.totalPagesCount());
            }
            this.paginated(this.getPaginated());
        },

        afterFetchChange: function () {
            if (this.fetchSize > this.returnData().length) {
                this.getGivenWeatherTypeRecord();
            }
            this.totalPagesCount(this.getTotalPages());
        },

        processData: function(data) {
            let processedArray = [];
            if (this.isAllWeathers() === true) {
                $.each(data, function() {
                    $.each(this, function(key, value) {
                        processedArray.push(value);
                    });

                    //Only one iteration, cause other information is not needed
                    return false;
                });

                return processedArray;
            } else {
                processedArray.push(data);
            }

            return processedArray;
        },

        generateRestApiUrl: function() {
            if (this.isAllWeathers() === true) {
                return this.url + 'rest/V1/weathertype?searchCriteria[sortOrders][0][field]=name&searchCriteria[sortOrders][0][direction]=ASC&searchCriteria[pageSize]=' + this.fetchSize;
            }

            return this.url + 'rest/V1/weathertype/name/' + this.weatherType;
        }
    });
});
