/*** Globals ***/

Messenger.options = {
    extraClasses: 'messenger-fixed messenger-on-top messenger-on-right',
    theme: 'flat'
};

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

    var scrollableListHeight = $("div.scrollable-list").height();

    function refreshScrollableListHeight() {
        var windowHeight = $(window).height();
        var scrollableList = $("div.scrollable-list");
        var actionsWrapperHeight = $("#actions-wrapper").height();
        var remainingSpace = windowHeight - actionsWrapperHeight - 40;
        var newScrollableListHeight = Math.floor(remainingSpace / 41) * 41 + 1;

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

var tools = {

    hideLoaderMask: function() {
        $("#loader").fadeOut("fast");
        $(".mask").fadeOut("fast");
    },

    showLoaderMask: function() {
        $("#loader").fadeIn("fast");
        $(".mask").fadeIn("fast");
    }

};
