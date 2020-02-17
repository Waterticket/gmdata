$('.js-pscroll').each(function(){
    var ps = new PerfectScrollbar(this);

    $(window).on('resize', function(){
        ps.update();
    })
});