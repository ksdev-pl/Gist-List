// ---------------------------------------------------------------------------------------------------------------------
// Require modules
// ---------------------------------------------------------------------------------------------------------------------

window._ = require('lodash');
window.$ = window.jQuery = require('jquery');

require('bootstrap-sass');

const Vue = require('vue');
const VueResource = require('vue-resource');

const GistsIndexPage = require('../components/GistsIndexPage.vue');

const TinyColor = require('tinycolor2');

// ---------------------------------------------------------------------------------------------------------------------
// Setup app
// ---------------------------------------------------------------------------------------------------------------------

Vue.config.debug = true;
Vue.use(VueResource);
Vue.http.headers.common['X-CSRF-TOKEN'] = $('meta[name="csrf-token"]').attr('content');

new Vue({
    el: 'body',
    components: {
        GistsIndexPage
    },
    data: {
        store: window.hasOwnProperty('store') ? window.store : {}
    },
    computed: {
        tagColors() {
            let tagColors = {};
            _.forEach(this.store.tags, tag => {
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


// ---------------------------------------------------------------------------------------------------------------------
// ?
// ---------------------------------------------------------------------------------------------------------------------


/*** Globals ***/

/*Messenger.options = {
    extraClasses: 'messenger-fixed messenger-on-top messenger-on-right',
    theme: 'flat'
};*/

/*** Window load ***/

$(window).load(function() {
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
