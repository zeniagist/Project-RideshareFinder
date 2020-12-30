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

// Departure Longitude Tolerance
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
    $maxLongitudeDeparture -= 360;
}

// ******
// Destination Longitude
// *******
// longitude out of range
$destinationLngOutOfRange = false;

// Destination Longitude Tolerance
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
    $maxLongitudeDestination -= 360;
}

// ******
// Departure Latitude
// *******

// Departure Latitude Tolerance
$deltaLatitudeDeparture = $searchRadius*180/12430;

// min Departure Latitude
$minLatitudeDeparture = $departureLatitude - $deltaLatitudeDeparture;
if($minLatitudeDeparture < -90){
    $minLatitudeDeparture = -90;
}

// max Departure Latitude
$maxLatitudeDeparture = $departureLatitude + $deltaLatitudeDeparture;
if($maxLatitudeDeparture > 90){
    $maxLatitudeDeparture = 90;
}

// ******
// Destination Latitude
// *******

// Destination Latitude Tolerance
$deltaLatitudeDestination = $searchRadius*180/12430;

// min Destination Latitude
$minLatitudeDestination = $destinationLatitude - $deltaLatitudeDestination;
if($minLatitudeDestination < -90){
    $minLatitudeDestination = -90;
}

// max Destination Latitude
$maxLatitudeDestination = $destinationLatitude + $deltaLatitudeDestination;
if($maxLatitudeDestination > 90){
    $maxLatitudeDestination = 90;
}

// build query
$sql = "SELECT * FROM carsharetrips WHERE";

// departure Longitude
if($departureLngOutOfRange){
    $sql .= " ((departureLongitude > '$minLongitudeDeparture') 
                OR (departureLongitude > '$maxLongitudeDeparture'))";
}else{
   $sql .= " (departureLongitude BETWEEN '$minLongitudeDeparture' AND '$maxLongitudeDeparture')"; 
}

// departure Latitude
 $sql .= " AND (departureLatitude BETWEEN '$minLatitudeDeparture' AND '$maxLatitudeDeparture')";
 
  //destination Longitude
if($destinationLngOutOfRange){
    $sql .= " AND ((destinationLongitude > '$minLongitudeDestination') 
                OR (destinationLongitude > '$maxLongitudeDestination'))";
}else{
  $sql .= " AND (destinationLongitude BETWEEN '$minLongitudeDestination' AND '$maxLongitudeDestination')"; 
}

// // destination Latitude
 $sql .= " AND (destinationLatitude BETWEEN '$minLatitudeDestination' AND '$maxLatitudeDestination')";
 
$result = mysqli_query($link, $sql);
    if(!$result){
      echo '<div class="alert alert-danger">Error running the query!</div>';
      exit;
    }


?>