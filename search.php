<?php
session_start();

if(!$_SESSION['user_id']) {
 header('Location: index.php');
 exit;
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible"content="IE=edge">
        <meta name="viewport"content="width=device-width, initial-scale=1">
        <title>Rideshare Finder</title>

        <!-- Bootstrap -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> 

        <link href="styling.css" rel="stylesheet">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Arvo&display=swap" rel="stylesheet">
        
        <!--Google Maps API-->
        <!--<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCFLMFaU5ZWKX-DheNPBrL1yE_ZVQmBvjo&libraries=places"></script>-->
        
        <style>
            /*Container*/
            #myContainer{
                margin-top: 30px;
                text-align: center;
                color: white;
            }
            
            h1{
                font-size: 4em;
            }
            
            .btn{
                border: none;
            }
            
            #departure, #destination{
                color: black;
            }
            
            #searchBtn{
                margin-top: 20px;
            }
            
            #signup{
                margin: 30px auto;
            }
            
            .signupButton{
                color: white;
            }
        </style>

    </head>
    
    <body>
        <!-- Navigation Bar -->
      <nav rule="navigation" class="navbar navbar-default navbar-fixed-top" id="custom-bootstrap-menu">
        <div class="container-fluid">
          <div class="navbar-header">
            <a href="mainpageloggedin.php" class="navbar-brand">Rideshare Finder</a>

            <button type="button" class="navbar-toggle" data-target="#navbarCollapse" data-toggle="collapse">
              <span class="sr-only">Toggle Navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>            
          </div>

          <div class="navbar-collapse collapse" id="navbarCollapse">
            <ul class="nav navbar-nav">
              <li class="active"><a href="search.php">Search</a></li>
              <li><a href="profile.php">Profile</a></li>
              <!--<li><a href="#">Help</a></li>-->
              <!--<li><a href="contactus.php">Contact Us</a></li>-->
              <li><a href="mainpageloggedin.php">My Trips</a></li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li>
                    <a href="profile.php">
                    <?php
                        echo $_SESSION['username'];
                    ?>
                    </a>
                </li>
                <li><a href="index.php?logout=1">Log out</a></li>
            </ul>

          </div>
        </div>
      </nav>
    
        <!--Container-->
        <div class="container-fluid" id="myContainer">
            <div class="row">
                <div class="col-lg-6 col-md-offset-3">
                    <h1>Plan Your Next Trip Now</h1>
                    <p class="lead">Save Money! Save the Environment!</p>
                    
                    <!--Search Form-->
                    <form class="form-inline" method="get" id="searchForm">
                        <div class="form-group">
                            <!--Departure-->
                            <label for="departure" class="sr-only">Departure</label>
                            <input type="text" placeholder="Departure" name="departure" id="departure">
                            
                            <!--Destination-->
                            <label for="destination" class="sr-only">Destination</label>
                            <input type="text" placeholder="Destination" name="destination" id="destination">
                        </div>
                    </form>
                    <!--Search Button-->
                    <input type="submit" value="Search" class="btn btn-lg purple" name="search" id="searchBtn"onclick="calcRoute();">
                    
                    <!--Google Map-->
                    <div id="googleMap"></div>
                    
                </div>
            </div>
        </div>

      <!-- Footer -->
      <div class="footer">
        <div class="container">
          <p>Zenia Gist Copyright&copy; 2020 -
            <?php 
              $today = date("Y");
              echo $today;
            ?>
          .</p>
        </div>
      </div>
    
    <!--Google Map API-->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCFLMFaU5ZWKX-DheNPBrL1yE_ZVQmBvjo&libraries=places"></script>
      <script src="javascript.js"></script>
      <script src="map.js"></script>
    </body>
    
    </html>