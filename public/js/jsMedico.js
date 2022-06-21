$("#fechaTurno").flatpickr({
    enableTime: false,
    dateFormat: "Y-m-d",
    minDate: new Date().fp_incr(1),
    defaultDate: new Date().fp_incr(1),
    "disable": [
        function(date) {
           return (date.getDay() === 0 || date.getDay() === 6);  // disable weekends
        }
    ],
    "locale": {
        "firstDayOfWeek": 0 // set start day of week to Monday
    }
});

$("#hour").keydown(function() {
    return false
  });




