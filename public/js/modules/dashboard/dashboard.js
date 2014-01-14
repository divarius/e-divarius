$(function() {
    $('#refresh').bind('click', function() {
        console.log('refreash');
        $calendar.fullCalendar('refetchEvents');
        $calendar.fullCalendar('unselect');
    })
});
