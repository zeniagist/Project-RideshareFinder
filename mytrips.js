// Hide all the date and time checkbox inputs
$(".regular").hide();
$(".oneoff").hide();

// Select radio buttons
var myRadio = $('input[name="regular"]');
myRadio.click(function(){
    if($(this).is(':checked')){
        if($(this).val() == "Y"){// Regular Commute is selected
            $(".oneoff").hide();
            $(".regular").show();
        }else{// One-off Commute is selected
            $(".regular").hide();
            $(".oneoff").show();
        }
    }
});

// Calendar
$('input[name="date"]').datepicker({
    numberOfMonths: 1,
    showAnim: "fadeIn",
    dateFormat: "D M d, yy",
    minDate: +1,
    maxDate: "+12M",
    showWeek: true
});