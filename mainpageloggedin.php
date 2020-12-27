<?php
session_start();

if(!$_SESSION['user_id']) {
 header('Location: index.php');
 exit;
}

include('connection.php');

$user_id = $_SESSION['user_id'];

//get username and email
$sql = "SELECT * FROM users WHERE user_id='$user_id'";
$result = mysqli_query($link, $sql);
if(!$result){
    echo "error when selecting user_id from users table!";
}

$count = mysqli_num_rows($result);

if(!$count){
  echo '<div class="alert alert-danger">Error</div>';
  exit;
}

$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
$picture = $row['profilepicture'];
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible"content="IE=edge">
        <meta name="viewport"content="width=device-width, initial-scale=1">
        <title>My Notes</title>

        <!-- Bootstrap -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> 

        <link href="styling.css" rel="stylesheet">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Arvo&display=swap" rel="stylesheet">

        <style>
          #container{
            margin-top:120px;
          }
          
            .preview{
              height: 30px;
              border-radius: 50%;
            }
            
            .preview2{
              height: auto;
              max-width: 100%;
              border-radius: 50%;
            }

          #allNotes, #done, #notepad, .delete{
            display: none;
          }

          .buttons{
            margin-bottom: 20px;
          }

          textarea{
            width: 100%;
            max-width: 100%;
            min-width: 100%;
            font-size: 16px;
            line-height: 1.5em;
            border-left-width: 20px;
            border-color: #B2C0B9;
            color: #B2C0B9;
            background-color: #FBEFFF;
            padding: 10px;
          }
          
          .noteheader{
              border: 1px solid grey;
              border-radius: 10px;
              margin-bottom: 10px;
              cursor: pointer;
              padding: 0 10px;
              background: linear-gradient(#FFFFFF, #ECEAE7);
          }
          
          .text{
              font-size: 20px;
              overflow: hidden;
              white-space: nowrap;
              text-overflow: ellipsis;
          }
          
          .timetext{
              overflow: hidden;
              white-space: nowrap;
              text-overflow: ellipsis;
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
              <li><a href="search.php">Search</a></li>
              <li><a href="profile.php">Profile</a></li>
              <!--<li><a href="#">Help</a></li>-->
              <!--<li><a href="contactus.php">Contact Us</a></li>-->
              <li class="active"><a href="mainpageloggedin.php">My Trips</a></li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li>
                    <a href="#">
                        <div data-toggle="modal" data-target="#updatepicture">
                            <!--<img src='https://cdn.pixabay.com/photo/2017/01/06/05/28/car-1957037_960_720.jpg' class='preview'>-->
                            <?php
                            if(!file_exists($picture) || $picture == ''){
                                echo "<img src='https://cdn.pixabay.com/photo/2017/01/06/05/28/car-1957037_960_720.jpg' class='preview'>";
                            }else{
                                echo "<img src='$picture' class='preview'>";
                            }
                            ?>
                        </div>
                    </a>
                </li>
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
      
      <!-- Update profile picture -->
      <form method="post" id="updatepictureform" enctype="multipart/form-data">
        <div class="modal" id="updatepicture" role="dialog" aria-labelledby="#myModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">

              <div class="modal-header">
                <button class="close" data-dismiss="modal">&times;</button>
                <h4 id="myModalLabel">Upload Picture:</h4>
              </div>

              <div class="modal-body">
                
              <div id="updatePictureMessage">
                <!-- Update Username message from PHP File -->
              </div>
              
              <!--Profile Picture-->
              <div>
                <?php
                if(!file_exists($picture) || $picture == '' ){
                    echo "<img src='https://cdn.pixabay.com/photo/2017/01/06/05/28/car-1957037_960_720.jpg' class='preview2' id='preview2'>";
                }else{
                    echo "<img src='$picture' class='preview2' id='preview2'>";
                }
                ?>
              </div>

                <div class="form-group">
                  <label for="picture">Select a Picture:</label>
                  <input type="file" name="picture" id="picture">
                </div>

              </div>

              <div class="modal-footer">                
                <input class="btn purple" name="updateusername" type="submit" value="Submit">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
              </div>

            </div>
          </div>
        </div>
      </form>
      
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

      <!-- Container -->
      <div class="container" id="container">
        <div class="row">
        </div>
      </div>
    
    <script src="notes/mytrips.js"></script>
    <script src="profile.js"></script>
    </body>
    
    </html>