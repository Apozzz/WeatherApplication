define([
    'jquery',
    'uiComponent',
    'ko',
    "mage/translate"
], function($, Component, ko, $t){
    "use strict";
    return Component.extend({
        defaults: {
            PARAM_COEFICIENT_RECALCULATE: 2,
            PARAM_COEFICIENT_NEXT: 1.5,
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
            returnData: ko.observableArray([$t('Getting Data')])
        },

        initialize: function () {
            this._super();
            this.initDataFetchFunctions();
            return this;
        },

        initDataFetchFunctions: function () {
            this.fetchSize = this.sizePerPage() * 2;
            if (!this.weatherType) {
                this.getAllWeatherTypeRecords();
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
                self.recalculateOutputDataAfterFetch(data, false);
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
                self.recalculateOutputDataAfterFetch(data, true);
                $('body').trigger('processStop');
            });
        },

        getHasPrevious: function () {
            return this.pageNumber() !== 0;
        },

        getHasNext: function () {
            return this.pageNumber() !== this.totalPagesCount();
        },

        next: function () {
            if (this.isFetchOfAdditionalDataNeededForNext()) {
                this.fetchAdditionalDataForNext();
            }
            this.moveNextPage();
        },

        previous: function () {
            this.movePreviousPage();
        },

        getTotalPages: function () {
            let totalPagesCount = Math.floor(this.returnData().length / this.sizePerPage());
            totalPagesCount += this.returnData().length % this.sizePerPage() > 0 ? 1 : 0;

            return totalPagesCount - 1;
        },

        getPaginated: function () {
            const first = this.pageNumber() * this.sizePerPage();

            return this.returnData().slice(first, Number(first) + Number(this.sizePerPage()));
        },

        recalculate: function () {
            this.fetchAdditionalDataForRecalculate();
            this.fixCurrentPageOutsideOfBounds();
            this.paginated(this.getPaginated());
        },

        afterFetchChange: function () {
            if (this.fetchSize > this.returnData().length) {
                this.getAllWeatherTypeRecords();
            }
            this.totalPagesCount(this.getTotalPages());
        },

        processData: function (data) {
            if (this.isAllWeathers()) {
                return this.getWeatherTypeValuesFrom2DArray(data);
            }

            return data;
        },

        generateRestApiUrl: function () {
            if (this.isAllWeathers()) {
                return this.url + 'rest/V1/weathertype?searchCriteria[sortOrders][0][field]=name&searchCriteria[sortOrders][0][direction]=ASC&searchCriteria[pageSize]=' + this.fetchSize;
            }

            return this.url + 'rest/V1/weathertype/name/' + this.weatherType;
        },

        getWeatherTypeValuesFrom2DArray: function (data) {
            let processedData = [];

            $.each(data, function() {
                $.each(this, function(key, value) {
                    processedData.push(value);
                });

                return false;
            });

            return processedData;
        },

        fetchAdditionalDataForNext: function () {
            this.fetchSize = Math.round(this.fetchSize * this.PARAM_COEFICIENT_NEXT);
            this.afterFetchChange();
        },

        fetchAdditionalDataForRecalculate: function () {
            this.fetchSize = this.sizePerPage() * this.PARAM_COEFICIENT_RECALCULATE;
            this.afterFetchChange();
        },

        isFetchOfAdditionalDataNeededForNext: function () {
            return (this.pageNumber() + 1 === this.totalPagesCount() && this.fetchSize <= this.returnData().length);
        },

        moveNextPage: function () {
            if (this.pageNumber() < this.totalPagesCount()) {
                this.pageNumber(this.pageNumber() + 1);
                this.paginated(this.getPaginated());
            }
        },

        movePreviousPage: function () {
            if(this.pageNumber() !== 0) {
                this.pageNumber(this.pageNumber() - 1);
                this.paginated(this.getPaginated());
            }
        },

        fixCurrentPageOutsideOfBounds: function () {
            if (this.pageNumber() > this.totalPagesCount()) {
                this.pageNumber(this.totalPagesCount());
            }
        },

        recalculateOutputDataAfterFetch: function (data, isAllWeatherTypes) {
            this.returnData(this.processData(data));
            this.totalPagesCount(this.getTotalPages());
            if (isAllWeatherTypes) {
                this.paginated(this.getPaginated(this.returnData()));
                return;
            }
            this.paginated(this.returnData());
        }
    });
});
