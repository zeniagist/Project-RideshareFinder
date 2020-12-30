<?php
//Start session
session_start();
//Connect to the database
include("connection.php");

//Define error messages
$missingDeparture = '<p><strong>Please enter your departure!</strong></p>';
$invalidDeparture = '<p><strong>Please enter a valid departure location!</strong></p>';
$missingDestination = '<p><strong>Please enter your destination!</strong></p>';
$invalidDestination = '<p><strong>Please enter a valid destination location!</strong></p>';

// Get inputs
$departure = $_POST["departure"];
$destination = $_POST["destination"];

//Get departure
if(!$departure){
    $errors .= $missingDeparture;   
}else{
    // check coordinates
    if(!$_POST["departureLatitude"] OR !$_POST["departureLongitude"]){
        $errors .= $invalidDeparture;  
    }else{
        $departureLatitude = $_POST["departureLatitude"];
        $departureLongitude = $_POST["departureLongitude"];
        $departure = filter_var($departure, FILTER_SANITIZE_STRING);
    }
}

//Get destination
if(!$destination){
    $errors .= $missingDestination;   
}else{
    // check coordinates
    if(!$_POST["destinationLatitude"] OR !$_POST["destinationLatitude"]){
        $errors .= $invalidDestination;  
    }else{
        $destinationLatitude = $_POST["destinationLatitude"];
        $destinationLongitude = $_POST["destinationLongitude"];
        $destination = filter_var($destination, FILTER_SANITIZE_STRING);
    }
}

//If there are any errors
if($errors){
    //print error message
    $resultMessage = '<div class="alert alert-danger">' . $errors .'</div>';
    echo $resultMessage;
    exit;
}

// set search radius
$searchRadius = 10; // 10 miles

// ******
// Departure Longitude
// *******
// longitude out of range
$departureLngOutOfRange = false;

// Departure Longitude
$deltaLongitudeDeparture = ($searchRadius * 360)/(24901*cos(deg2rad($departureLatitude)));

// min Departure Longitude
$minLongitudeDeparture = $departureLongitude - $deltaLongitudeDeparture;
if($minLongitudeDeparture < -180){
    $departureLngOutOfRange = true;
    $minLongitudeDeparture += 360;
}

// max Departure Longitude
$maxLongitudeDeparture = $departureLongitude + $deltaLongitudeDeparture;
if($maxLongitudeDeparture > 180){
    $departureLngOutOfRange = true;
    $minLongitudeDeparture -= 360;
}

// ******
// Destination Longitude
// *******
// longitude out of range
$destinationLngOutOfRange = false;

// Destination Longitude
$deltaLongitudeDestination = ($searchRadius * 360)/(24901*cos(deg2rad($destinationLatitude)));

// min Destination Longitude
$minLongitudeDestination = $destinationLongitude - $deltaLongitudeDeparture;
if($minLongitudeDestination < -180){
    $destinationLngOutOfRange = true;
    $minLongitudeDestination += 360;
}

// max Destination Longitude
$maxLongitudeDestination = $destinationLongitude + $deltaLongitudeDeparture;
if($maxLongitudeDestination > 180){
    $destinationLngOutOfRange = true;
    $minLongitudeDestination -= 360;
}








?>