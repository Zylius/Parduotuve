var order = $('.order');
order.click(function () {
    $(this).addClass('text-success');
    var value = $.cookie('basket');
    if (value !== undefined) {
       value = JSON.parse(value);
       if (!(value.constructor === Array)) {
            value = [];
       }
    } else {
        value = [];
    }

    var index = value.indexOf($(this).attr( "data-id" ));
    if(index != -1){
        $(this).removeClass('text-success');
        value.splice(index, 1);
    } else {
        value.push($(this).attr( "data-id" ));
        $(this).addClass('text-success');
    }
    $.cookie('basket', JSON.stringify(value));
});
order.each(function(){
    var value = $.cookie('basket');
    if (value !== undefined) {
        value = JSON.parse(value);
        if (!(value.constructor === Array)) {
            value = [];
        }
    } else {
        value = [];
    }

    if(value.indexOf($(this).attr( "data-id" )) != -1){
        $(this).addClass('text-success');
    }
});
