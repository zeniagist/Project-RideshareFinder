// Hide all the date and time checkbox inputs
$(".regular").hide();
$(".oneoff").hide();
$(".regular2").hide();
$(".oneoff2").hide();

// **************
// Add Trips Radio Buttons
// **************
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

// **************
// Edit Trips Radio Buttons
// **************
// Select radio buttons
var myRadio = $('input[name="regular2"]');
myRadio.click(function(){
    if($(this).is(':checked')){
        if($(this).val() == "Y"){// Regular Commute is selected
            $(".oneoff2").hide();
            $(".regular2").show();
        }else{// One-off Commute is selected
            $(".regular2").hide();
            $(".oneoff2").show();
        }
    }
});

// Calendar
$('input[name="date"], input[name="date2"]').datepicker({
    numberOfMonths: 1,
    showAnim: "fadeIn",
    dateFormat: "D M d, yy",
    minDate: +1,
    maxDate: "+12M",
    showWeek: true
});

