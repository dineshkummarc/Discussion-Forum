<?php
session_start();
error_reporting(0);
$servername = "mysql.discussionthreads.online";
$username = "ahmedwab";
$password = "discussion1407";
$databaseName = "discussionthreads_discussion";

 $conn = new mysqli($servername,$username,$password,$databaseName);

 if ($_SESSION["user"] == NULL)
 {
    header('Location: login.php');
 }
 $accountusername = $_SESSION["user"];
 $topicid= $_SESSION['topicid'];
 $topicname= $_SESSION['topicname'];

 $query = "SELECT image FROM ACCOUNTS WHERE username='$accountusername' LIMIT 1";
 $result = mysqli_query($conn, $query);

$profileimage=NULL;
 while($row = mysqli_fetch_array($result))
 {
   $profileimage=$row['image'];
 }

 $sql = "SELECT * FROM NOTIFICATIONS  WHERE otheruser='$accountusername' AND viewed='0' ORDER BY time DESC";
 $result = $conn->query($sql);

$notif_num=$result->num_rows ;
echo "<div id='page'>";
 echo" <div id='topnav'>
         <a href='main.php'>Discussion Forum</a>
         <div id='topnav-right'>
         <a href='notifications.php'>" ."<img id='profileImage' src='images/bell.png'><p>".$notif_num."</p></a> 
         <a href='friends.php'>" ."<img id='profileImage' src='images/friends.png'></a> ";
         if($profileimage==NULL){
           echo      " <a href='profile.php?user=$accountusername'>" ."<img id='profileImage' src='images/default.png'></a>";

         }
         else{
  echo      " <a href='profile.php?user=$accountusername'>" ."<img id='profileImage' src='data:image/jpeg;base64,".base64_encode( $profileimage )."'></a>";
}
  echo"   
         </div>
       </div>";

       echo "<form  action='search.php' method='post' id='search-form'>
       <input type='text' id='search-text' name='search-text' placeholder='Search...'>
        <button type='submit' id='search-submit' name='search-submit' >Search</button>
       </form>";





?>



<!DOCTYPE html>
<html lang="en">
<head>
  <title>Forums</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="stylesheets/home-styles.css">
</head>
<body>

<div class="forums">
    <div class="thread-class">
    <div class="list-title">Recent</div>
    <?php
 $sql = "SELECT * FROM NOTIFICATIONS  WHERE otheruser='$accountusername'  ORDER BY time DESC";
 $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      // output data of each row
      while($row = $result->fetch_assoc()) {
        $username=$row["username"];
        $text=$row["text"];
        $date=$row["time"];


        echo'<div class="thread">';
          echo'<div class="thread-name">';
          $stmnt = "SELECT * FROM ACCOUNTS WHERE username='$username' ";
          $res = $conn->query($stmnt);
    
            while($r = $res->fetch_assoc()) {
              if(empty($r['image'])){
                echo "<a href='profile.php?user=".$username."'>";
                  echo "<img class='friend-image' src='images/default.png'/><br>";
                  echo "<p>".$username."</p></a>";
                }
              else{
                echo "<a href='profile.php?user=".$username."'>";
              echo '<img class="friend-image" src="data:image/jpeg;base64,'.base64_encode( $r['image'] ).'"/><br>';
              echo "<p>".$username."</p></a>";
            }
        }

          echo "<p>" ." ".$text,"</p>";
          echo '</div>';
       echo' <div class="thread-count">'.$date.'</div>';
    echo '</div>';
      }
    }
    else{
        echo "  You have no notifications";
    }
    $sql = "UPDATE NOTIFICATIONS SET viewed='1' WHERE otheruser='$accountusername'";

      if ($conn->query($sql) === TRUE) {
      echo "";
      } 

  
    
    ?>
    </div>
  </div>

</body>
</html>
