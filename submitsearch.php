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

// destination Latitude
 $sql .= " AND (destinationLatitude BETWEEN '$minLatitudeDestination' AND '$maxLatitudeDestination')";
 
//  Do not display current user
// $user_id = $_SESSION['user_id'];
// $sql .= " WHERE user_id!='$user_id'";
 
$result = mysqli_query($link, $sql);
if(!$result){
  echo '<div class="alert alert-danger">Unable to execute the search radius query</div>';
  exit;
}

// No trips in the search radius
if(mysqli_num_rows($result) == 0){
    echo '<div class="alert alert-warning noresults"><strong>There are no journeys matching your search</strong></div>';
    exit;
}

// Message with destination and daparture message that trips were found
echo '<div class="alert resultsFound">
        From ' . $departure . ' to '. $destination . '<br /><br />Closest Journeys:
      </div>';

// Trips found in search radius
echo '<div id="searchResultsDiv">';

while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
    // get trip details
    //check frequency
    if($row['regular']=="N"){
        // one-off journey
        $frequency = "One-off journey";
        $time = $row['date']." at " .$row['time'];
        
        // date of trip
        $source = $row['date'];
        // date is in same format as today's date
        $tripDate = DateTime::createFromFormat('D M d, Y', $source);
        
        // today's date
        $today = date('D M d, Y');
        // today's date is in same format as date
        $todayDate = DateTime::createFromFormat('D M d, Y', $today);
        
        // compate today's date with date of trip
        if($tripDate > $todayDate){
            // go to next trip
            continue;
        }
    }else{
        $frequency = "Regular Journey"; 
        $array = [];
            if($row['monday']==1){array_push($array,"Mon");}
            if($row['tuesday']==2){array_push($array,"Tue");}
            if($row['wednesday']==3){array_push($array,"Wed");}
            if($row['thursday']==4){array_push($array,"Thu");}
            if($row['friday']==5){array_push($array,"Fri");}
            if($row['saturday']==6){array_push($array,"Sat");}
            if($row['sunday']==7){array_push($array,"Sun");}
        $time = implode("-", $array)." at " .$row['time'];
    }
    
    $departure = $row['departure'];
    $destination = $row['destination'];
    $price = $row['price'];
    $seatsavailable = $row['seatsavailable'];
    
    // get user_id
    $person_id = $row['user_id'];
    
    // run query to get user details
    $sql2 = "SELECT * FROM users 
            WHERE user_id='$person_id'
            LIMIT 1
            ";
    // run query to file
    $result2 = mysqli_query($link, $sql);
    if(!$result2){
      echo '<div class="alert alert-danger">Unable to execute a query looking for users in the users table</div>';
      exit;
    }
    
    // check table for users
    $row2 = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $first_name = $row2['first_name'];
    $gender = $row2['gender'];
    $moreinformation = $row2['moreinformation'];
    $profilepicture = $row2['profilepicture'];
    $phonenumber = $row2['phonenumber'];
    
    // print trip
    echo "hello";
}

echo '</div>';
?>