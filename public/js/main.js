$(document).ready(function() {

    $('#intro-arrow-wrapper').fadeIn().fadeOut().fadeIn().fadeOut();

    var stringToColour = function(str) {
        var hash = 0;
        for (var i = 0; i < str.length; i++) {
            hash = str.charCodeAt(i) + ((hash << 5) - hash);
        }
        var colour = '#';
        for (var i = 0; i < 3; i++) {
            var value = (hash >> (i * 8)) & 0xFF;
            colour += ('00' + value.toString(16)).substr(-2);
        }
        return colour;
    };

    $('span.tag').each(function() {
        $(this).css('background-color', stringToColour($(this).text()))
    });

    if ($(window).height() < 1050) {
        var numberOfRows = 10;
    }
    else {
        var numberOfRows = 15;
    }

    var table = $('table#gists').DataTable({
        order: [6, 'desc'],
        lengthMenu: [10, 15, 25, 50, 100],
        iDisplayLength: numberOfRows
    });

    $('div.dataTables_filter input').focus();

    $(document).on(
        'keyup search input paste cut',
        'input',
        function() {
            if ($(this).val()) {
                $('a.filter-all').removeClass('active');
            }
            else {
                if (!$('.list-group-item.active')[0]) {
                    $('a.filter-all').addClass('active');
                }
            }
        }
    );

    function resetFilter() {
        $('input').val('');
        table.search('').columns().search('').draw();
    }

    function toggleActive(element) {
        $('.list-group-item.active').removeClass('active');
        $(element).addClass('active');
    }

    $(document).on('click', 'a.search-filter', function(event) {
        event.preventDefault();
        resetFilter();
        if ($(this).hasClass('filter-tag')) {
            var filterString = $(this).find('span.tag').text();
            table.column(0).search(filterString).draw();
            toggleActive(this);
        }
        else if ($(this).hasClass('row-filter-tag')) {
            var filterString = $(this).find('span.tag').text();
            table.column(0).search(filterString).draw();
            var element = $('a.search-filter.list-group-item')
                .find('span:contains(' + filterString + ')')
                .parent();
            toggleActive(element);
        }
        else if ($(this).hasClass('filter-my-gists')) {
            var userLogin = $('#user-login').text();
            table.column(3).search(userLogin).draw();
            toggleActive(this);
        }
        else if ($(this).hasClass('filter-starred')) {
            table.column(1).search('starred', true).draw();
            toggleActive(this);
        }
        else if ($(this).hasClass('row-filter-owner')) {
            var filterString = $(this).text();
            table.column(3).search(filterString).draw();
            if ($(this).text() == $('#user-login').text()) {
                toggleActive('.filter-my-gists');
            }
            else {
                toggleActive('.filter-starred');
            }
        }
        else if ($(this).hasClass('filter-no-tag')) {
            table.column(0).search('Without Tag').draw();
            toggleActive(this);
        }
        else if ($(this).hasClass('filter-public')) {
            table.column(4).search('public').draw();
            toggleActive(this);
        }
        else if ($(this).hasClass('filter-private')) {
            table.column(4).search('private').draw();
            toggleActive(this);
        }
        else {
            // $(this).hasClass('filter-all')
            toggleActive(this);
        }
    });

    $('.btn-action').on('click', function() {
        $(this).attr('disabled', 'disabled');
        $(this).text('Please wait...');
    });

    $('.btn-backup').on('click', function() {
        var btnBackup = $(this);
        $.fileDownload('/gists/backup', {
           prepareCallback: function (url) {
               btnBackup
                   .attr('disabled', 'disabled')
                   .text('Please wait...');

               $(".alert-info").fadeIn().delay(6000).fadeOut();
           },
           successCallback: function (url) {
               btnBackup
                   .text('Backup Gists')
                   .removeAttr('disabled')
           },
           failCallback: function (html, url) {

               if (html == 'WAIT_A_MINUTE') {
                   $(".alert-info").hide();
                   $(".alert-warning").fadeIn().delay(6000).fadeOut();
               }
               else {
                   $(".alert-info").hide();
                   $(".alert-danger").fadeIn();
               }

               btnBackup
                   .text('Backup Gists')
                   .removeAttr('disabled')
           }
        });
    });

    var scrollableListHeight = $("div.scrollable-list").height();

    function refreshScrollableListHeight() {
        var gistsWrapperHeight = $("#gists_wrapper").height();
        var scrollableList = $("div.scrollable-list");
        var remainingSpace = gistsWrapperHeight - 381;
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

    $("#gists_length").find("select").on("change", function() {
        refreshScrollableListHeight();
    });

    refreshScrollableListHeight();

});
