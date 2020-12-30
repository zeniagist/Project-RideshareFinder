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
?>