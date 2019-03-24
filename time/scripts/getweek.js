Date.prototype.getWeek = function(start) {
    //Calcing the starting point
    start = start || 0;
    var today = new Date(this.setHours(0, 0, 0, 0));
    var day = today.getDay() - start;
    var date = today.getDate() - day;

    // Grabbing Start/End Dates
    var StartDate = new Date(today.setDate(date));
    var EndDate = new Date(today.setDate(date + 6));
    return [StartDate, EndDate];
}

// test code
var Dates = new Date().getWeek();
alert(Dates[0].toLocaleDateString() + ' to ' + Dates[1].toLocaleDateString())