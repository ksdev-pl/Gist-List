
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('dynamic-table', require('./components/table/DynamicTable.vue'));
Vue.component('tags-cell', require('./components/table/TagsCell.vue'));
Vue.component('description-cell', require('./components/table/DescriptionCell.vue'));
Vue.component('owner-cell', require('./components/table/OwnerCell.vue'));
Vue.component('table-search-input', require('./components/table/TableSearchInput.vue'));
Vue.component('gists-index-page', require('./components/GistsIndexPage.vue'));
Vue.component('sidebar', require('./components/Sidebar.vue'));

Vue.filter('truncate', (value, length) => _.truncate(value, { length }));

import store from './store';

const app = new Vue({
    el: '#app',

    store,

    computed: {
        tagColors() {
            let tagColors = {};
            _.forEach(this.$store.state.tags, tag => {
                tagColors[tag] = this.stringToColor(tag);
            }, this);
            return tagColors;
        },
    },

    methods: {
        stringToColor: str => {
            if (str == '#none') {
                return '#777777'
            }
            let hash = 0;
            for (let i = 0; i < str.length; i++) {
                hash = str.charCodeAt(i) + ((hash << 5) - hash);
            }
            let color = '#';
            for (let j = 0; j < 3; j++) {
                let value = (hash >> (j * 8)) & 0xFF;
                color += ('00' + value.toString(16)).substr(-2);
            }
            let tc = TinyColor(color);
            let brightness = tc.getBrightness();
            if (brightness > 180) {
                tc.darken(brightness - 180);
            }
            return tc.toString();
        }
    }
});

/*** Window load ***/

$(window).on('load', function() {
    tools.hideLoaderMask();
});

/*** Document ready ***/

$(document).ready(function() {

    $('#intro-arrow-wrapper').fadeIn(3000);

    $('.btn-action').on('click', function() {
        $(this).attr('disabled', 'disabled');
        $(this).text('Please wait...');
    });

    // Przepisać poniższe w opaciu o vue

    let scrollableListHeight = $("div.scrollable-list").height();

    function refreshScrollableListHeight() {
        let windowHeight = $(window).height();
        let scrollableList = $("div.scrollable-list");
        let actionsWrapperHeight = $("#actions-wrapper").height();
        let remainingSpace = windowHeight - actionsWrapperHeight - 40;
        let newScrollableListHeight = Math.floor(remainingSpace / 41) * 41 + 1;

        if (remainingSpace < scrollableListHeight) {
            scrollableList.css("height", newScrollableListHeight);

            if (scrollableList.css("overflow-y") != "scroll") {
                scrollableList.css({
                    "overflow-y": "scroll"
                });
            }
        }
        else {
            if (scrollableList.css("overflow-y") == "scroll") {
                scrollableList.css({
                    "height": "inherit",
                    "overflow-y": "inherit"
                });
                // http://stackoverflow.com/questions/8840580/force-dom-redraw-refresh-on-chrome-mac
                scrollableList.hide().show(0);
            }
        }
    }

    $(window).resize(function() {
        refreshScrollableListHeight();
    });

    refreshScrollableListHeight();

    $('#accordion').on('shown.bs.collapse hidden.bs.collapse', function () {
        refreshScrollableListHeight();
    });

});

/*** Objects ***/

let tools = {

    hideLoaderMask: function() {
        $("#loader").fadeOut("fast");
        $(".mask").fadeOut("fast");
    },

    showLoaderMask: function() {
        $("#loader").fadeIn("fast");
        $(".mask").fadeIn("fast");
    }

};
