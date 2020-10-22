/*
|   ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
|      STICKY FORM WHEN SCROLLING FUNCTION
|   ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
*/

$(window).scroll(function(e){ 
    var $row      = $('#sticky-row');
    var $col      = $('#sticky-col');
    var $card     = $('#sticky-card');
    var $sticky   = $('#sticky-div');
    var $unsticky = $('#unsticky-div');

    var isPositionFixed = ($row.css('position') == 'fixed');

    if ($(this).scrollTop() > 200){ 
        $($row).appendTo($sticky);
        $sticky.css({
            'position': 'fixed',
            'z-index': 1,
            'top': '0px',
            'width': '100%'
        });
        $row.css({'height': '100%'});
        $col.removeClass('mt-5');
        $card.addClass('sticky-card');
    }
    if ($(this).scrollTop() < 200){
        $($row).appendTo($unsticky);
        $col.addClass('mt-5');
        $card.removeClass('sticky-card');
    } 
});