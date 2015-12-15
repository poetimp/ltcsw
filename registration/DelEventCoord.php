<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
<?php
include 'include/RegFunctions.php';

if (isset($_POST['Confirm']))
{
   $db->query("delete from $EventCoordTable          where CoordID=".$_REQUEST['CoordID']) or die ("Unable to delete Coordinator record: "        . sqlError());
   $db->query("update $EventsTable  set CoordID=NULL where CoordID=".$_REQUEST['CoordID']) or die ("Unable to delete registration record: " . sqlError());

   WriteToLog("Coordinator ".$_REQUEST['CoordID']." was deleted");
   ?>
      <head>
         <title>
            Coordinator Deleted
         </title>
      </head>
      <body style="background-color: rgb(217, 217, 255);">
         <h1 align=center>
            Coordinator <?php  print $_REQUEST['CoordName']; ?> Deleted!
         </h1>
         <?php footer("Return to Coordinator List","Coordinators.php")?>
      </body>
   </html>
<?php
}
else if (isset($_POST['Cancel']))
{
   header("refresh: 0; url=Coordinators.php");
}
else
{
?>
       <head>
          <title>
             Delete Coordinator
          </title>
       </head>

       <body style="background-color: rgb(217, 217, 255);">
          <form method="post" action="DelEventCoord.php<?php  print "?CoordID=".$_REQUEST['CoordID']."&CordName=".urlencode($_REQUEST['CoordName']); ?>">
             <center>
                <h1>
                   Deleting Coordinator
                </h1>
                <h2>
                   "<?php  print $_REQUEST['CoordName']; ?>"
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