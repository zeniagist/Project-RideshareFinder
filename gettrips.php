<?php
session_start();
include('connection.php');

$user_id = $_SESSION['user_id'];

// selet all trips from database
$sql = "SELECT * FROM carsharetrips WHERE user_id='$user_id'";
$result = mysqli_query($link, $sql);

if(!$result){
  echo '<div class="alert alert-danger">Error running the get trip query!</div>';
  exit;
}
// print_r($result);

$count = mysqli_num_rows($result);

if($count <= 0){
  echo '<br /><div class="alert alert-warning">You have not created any trips yet!</div>';
  exit;
}

$row = mysqli_fetch_array($result, MYSQLI_ASSOC);

while($row){
    // check frequency
    if($row['regular']=="N"){
        // one-off trip
        $frequency = "One-off Journey";
        $time = $row['date'] . " at " . $row['time'];
    }else{
        $frequency = "Regular Journey";
        $array = [];
        
        // check days
        if($row['monday']==1){array_push($array, "Mon");}
        if($row['tuesday']==2){array_push($array, "Tue");}
        if($row['wednesday']==3){array_push($array, "Wed");}
        if($row['thursday']==4){array_push($array, "Thu");}
        if($row['friday']==5){array_push($array, "Fri");}
        if($row['saturday']==6){array_push($array, "Sat");}
        if($row['sunday']==7){array_push($array, "Sun");}
        
        // dashes between days
        $time = implode("-", $array) . " at " . $row['time'];
    }
}

?>