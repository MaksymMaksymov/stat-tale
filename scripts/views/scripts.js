$( window ).scroll(function() {
    if ($(window).scrollTop() > 50) {
        $('.data_table').addClass('fixTableHead');
    } else {
        $('.data_table').removeClass('fixTableHead');
    }
});