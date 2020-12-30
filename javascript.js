// define variables
var data, departureLongitude, departureLatitude, destinationLongitude, destinationLatitude, trip;

// create a geocoder object to use geocode
var geocoder = new google.maps.Geocoder();

//Ajax Call for the sign up form 
//Once the form is submitted
$("#signupform").submit(function(event){
  //prevent default php processing
  event.preventDefault();

  //collect user inputs
  var datatopost = $(this).serializeArray();
  // console.log(datatopost);

  //send them to signup.php using AJAX
  $.ajax({
    url: "signup.php",
    type: "POST",
    data: datatopost,
    // AJAX Call successful
    success: function(data){
      if(data){
        $("#signupmessage").html(data);
      }
    },
    // AJAX Call fails: show error AJAX Call error
    error: function(){
      $("#signupmessage").html("<div class='alert alert-danger'>There was an error with the AJAX Call. Please try again later</div>");
    }
  });
});

// AJAX Call for the login form
$("#loginform").submit(function(event){
  //prevent default php processing
  event.preventDefault();

  //collect user inputs
  var datatopost = $(this).serializeArray();

  //send them to login.php using AJAX
  $.ajax({
    url: "login.php",
    type: "POST",
    data: datatopost,
    // AJAX Call successful
    success: function(data){
      if(data == "success"){
        window.location = "search.php";
      }else{
        $('#loginMessage').html(data);
      }
    },
    // AJAX Call fails: show error AJAX Call error
    error: function(){
      $("#loginMessage").html("<div class='alert alert-danger'>There was an error with the AJAX Call. Please try again later</div>");
    }
  });
});  
        
// AJAX Call for the forgot password form
$("#forgotpasswordform").submit(function(event){
  //prevent default php processing
  event.preventDefault();

  //collect user inputs
  var datatopost = $(this).serializeArray();
  //send them to forgotpassword.php using AJAX
  $.ajax({
    url: "forgotpassword.php",
    type: "POST",
    data: datatopost,
    // AJAX Call successful
    success: function(data){
      $('#forgotpasswordmessage').html(data);
    },
    // AJAX Call fails: show error AJAX Call error
    error: function(){
      $("#forgotpasswordmessage").html("<div class='alert alert-danger'>There was an error with the AJAX Call. Please try again later</div>");
    }
  });
});

// Search Button
$("#searchForm").submit(function(event){
    //prevent default php processing
    event.preventDefault();
    
    //collect user inputs
    data = $(this).serializeArray();
    //   console.log(data);
    
    getSearchDepartureCoordinates();
});

// **************
// Functions
// **************

// Store Add Trip Departure Coordinates
function getSearchDepartureCoordinates(){
    // get coordinates of departure
    geocoder.geocode({
        'address': $("#departure").val()
    }, 
    function(results, status){
        if(status == google.maps.GeocoderStatus.OK){
            // console.log(results);
            
            // get latitude and logitude
            departureLongitude = results[0].geometry.location.lng();
            departureLatitude = results[0].geometry.location.lat();
            // add to data array
            data.push({name: 'departureLongitude', value: departureLongitude});
            data.push({name:'departureLatitude', value: departureLatitude});
            
            console.log(data);
            
            // coordinates of destination
            getSearchDestinationCoordinates();
        }else{
            // coordinates of destination with missing departure
            getSearchDestinationCoordinates();
        }
    });
}

// Store Add Trip Desitination Coordinates
function getSearchDestinationCoordinates(){
    // get coordinates of departure
    geocoder.geocode({
        'address': $("#destination").val()
    }, 
    function(results, status){
        if(status == google.maps.GeocoderStatus.OK){
            // console.log(results);
            
            // get latitude and logitude
            destinationLongitude = results[0].geometry.location.lng();
            destinationLatitude = results[0].geometry.location.lat();
            // add to data array
            data.push({name: 'destinationLongitude', value: destinationLongitude});
            data.push({name:'destinationLatitude', value: destinationLatitude});
            
            console.log(data);
            
            // Submit trip details
            submitSearchRequest();
        }else{
            // Submit trip details with missing Destination
            submitSearchRequest();
        }
    });
}

// Submit Add Trip details
function submitSearchRequest(){
//send to submitsearch.php using AJAX
    $.ajax({
        url: "submitsearch.php",
        type: "POST",
        data: data,
        // AJAX Call successful
        success: function(returnedData){
            $("#searchResults").html(returnedData);
            $("#tripResults").accordion({
                active: false,
                collapsible: true,
                heightStyle: "content",
                icons: false
            });
        },
        // AJAX Call fails: show error AJAX Call error
        error: function(){
          $("#searchResults").html(
              "<div class='alert alert-danger'>There was an error with the Search AJAX Call. Please try again later</div>"
          );
        }
    });
}