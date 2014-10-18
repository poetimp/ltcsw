<?php
include 'include/RegFunctions.php';

if (isset($_POST['Confirm']))
{
   $RoomName=$_REQUEST['RoomName'];

   mysql_query("delete from $RoomsTable where RoomName='$RoomName'")
       or die ("Unable to delete Room record: " . mysql_error());

   ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
      <head>
         <title>
            Room Deleted
         </title>
      </head>
      <body style="background-color: rgb(217, 217, 255);">
         <h1 align=center>
            Room <?php  print $_REQUEST['RoomName']; ?> Deleted!
         </h1>
         <?php footer("Return to Room List","Rooms.php")?>

      </body>
   </html>
<?php
}
else if (isset($_POST['Cancel']))
{
   header("refresh: 0; url=Rooms.php");
}
else
{
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">

       <head>
          <title>
             Delete Room
          </title>
       </head>

       <body style="background-color: rgb(217, 217, 255);">
          <form method="post" action="DelRoom.php<?php  print "?RoomName=".urlencode($_REQUEST['RoomName']); ?>">
             <center>
                <h1>
                   Deleting Room
                </h1>
                <h2>
                   "<?php  print $_REQUEST['RoomName']; ?>"
                </h2>
             </center>
             <p align="center">
             <input type="submit" value="Confirm Delete!" name="Confirm">
             <font size="5"><br>
             or</font><br>
             <input type="submit" value="Cancel" name="Cancel">
             </p>
          </form>
       </body>

   </html>
<?php
}
?>