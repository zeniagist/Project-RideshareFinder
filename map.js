// set map options
var myLatLng = {lat: 51.5074, lng:-0.1278}
var mapOptions = {
    center: myLatLng,
    zoom: 7,
    mapTypeId: google.maps.MapTypeId.ROADMAP
};

// onload:
google.maps.event.addDomListner(window, 'load', initialize());

function initialize(){
// create map
var map = new google.maps.Map(document.getElementById('googleMap'), mapOptions);
}