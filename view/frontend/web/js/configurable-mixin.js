define([
    'jquery',
    'jquery/ui',
    'mage/utils/wrapper',
    'underscore'
], function(wrapper, $, _) {
    'use strict';

    const createBannerList = function (bannerUrl) {
        let li = document.createElement("li");
        li.setAttribute('class', 'weathertype-banner-bullets');
        let img = document.createElement("img");
        img.setAttribute('class', 'weathertype-banner-media');
        img.src = bannerUrl;
        li.appendChild(img);

        return li;
    }

    const setWeatherTypeBanners = function (banner) {
        const selectedChild = $('input.selected_configurable_option');
        this.attachShadow({mode: open});
        let ul = document.createElement("ul");
        ul.setAttribute('class', 'weathertype-banner-listing');
        _.each(banner, function (key, value) {
            if (selectedChild === key) {
                value.forEach(({ image }) => {
                    ul.appendChild(createBannerList(image));
                });
            }
        });
        document.getElementsByClassName('weathertype-banner-listing');
    }

    return function (configurable) {
        return wrapper.wrap(configurable, function (configurable, config, element) {
            configurable(config, element);

            setWeatherTypeBanners (config.spConfig.banner)
        });
    }
});
