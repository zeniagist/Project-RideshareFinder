// set map options
var myLatLng = {lat: 51.5074, lng:-0.1278}
var mapOptions = {
    center: myLatLng,
    zoom: 7,
    mapTypeId: google.maps.MapTypeId.ROADMAP
};
// create autocomplete object
var input1 = document.getElementById('departure');
var input2 = document.getElementById('destination');
var options = {
    types: ['(cities)']
}

var autocomplete1 = new google.maps.places.Autocomplete(input1, options);
var autocomplete2 = new google.maps.places.Autocomplete(input2, options);

// onload:
google.maps.event.addDomListner(window, 'load', initialize());

function initialize(){
// create map
var map = new google.maps.Map(document.getElementById('googleMap'), mapOptions);
}