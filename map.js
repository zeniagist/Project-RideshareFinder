// set map options
var myLatLng = {lat: 51.5, lng:-0.1}
var mapOptions = {
    center: myLatLng,
    zoom: 7,
    mapTypeId: google.maps.MapTypeId.ROADMAP
};

// create map
var map = new google.maps.Map(document.getElementById('googleMap'), mapOptions);

//create a DirectionsService object to use the route method and get a result for our request
var directionsService= new google.maps.DirectionsService();

//create a DirectionsRenderer object which we will use to display the route
var directionsDisplay= new google.maps.DirectionsRenderer();

//bind the DirectionsRenderer to the map
directionsDisplay.setMap(map);

// define calcRoute function
function calcRoute(){
     var request={
            origin: document.getElementById("departure").value,
            destination: document.getElementById("destination").value,
            travelMode: google.maps.TravelMode.DRIVING, //WALKING, BYCYCLING, TRANSIT
            unitSystem: google.maps.UnitSystem.IMPERIAL
        };
        
        directionsService.route(request, function(result, status){
            if(status== google.maps.DirectionsStatus.OK){
                //display route
                directionsDisplay.setDirections(result);
            }else{
                // delete route from map
                // directionsDisplay.setDirections({routes:[]});
                
                // center map
                map.setCenter(myLatLng);
                
                // display error message
                $("#output").html("<div class='alert-danger'>Could not retrieve driving distance!</div>");
            }
        });
}

// create autocomplete object
var options = {
    types:['(cities)']
};

// create autocomplete objects for From
var input1 = document.getElementById("departure");
var autocomplete1 = new google.maps.places.Autocomplete(input1,options);

// create autocomplete objects for To
var input2 = document.getElementById("destination");
var autocomplete2 = new google.maps.places.Autocomplete(input2,options);