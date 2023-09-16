$('document').ready(function() {

    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']

    var date = new Date(),
        year = date.getFullYear(),
        day = ("0" + date.getDate()).slice(-2),
        month = months[date.getMonth()];

    $('.date-time .date').text(`${day} / ${month} / ${year}`);

    function startTime() {
        var today = new Date(),
            hour = today.getHours(),
            minutes= today.getMinutes(),
            seconds = today.getSeconds(),
            session = "AM";

        if (hour == 0) {
            hour = 12;
        }

        if (hour > 12) {
            hour = hour - 12;
            session = "PM";
        }
        minutes = checkTime(minutes);
        seconds = checkTime(seconds);
        $('.date-time .time').text(`${hour} : ${minutes}  ${session}`);
        setTimeout(startTime, 500);
    }
    function checkTime(i) {
        if (i < 10) { i = "0" + i }; // add zero in front of numbers < 10
        return i;
    }

    startTime();
});