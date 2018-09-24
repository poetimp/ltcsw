<?php
//----------------------------------------------------------------------------
// This software is licensed under the MIT license. Use as you wish but give
// and take credit where due.
//
// Author: Paul Lemmons
//----------------------------------------------------------------------------
include 'include/RegFunctions.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
<?php

if (isset($_POST['Confirm']))
{
   $db->query("delete from $EventCoordTable          where CoordID=".$_REQUEST['CoordID']) or die ("Unable to delete Coordinator record: "        . sqlError());
   $db->query("update $EventsTable  set CoordID=NULL where CoordID=".$_REQUEST['CoordID']) or die ("Unable to delete registration record: " . sqlError());

   WriteToLog("Coordinator ".$_REQUEST['CoordID']." was deleted");
   ?>
      <head>
         <meta name="viewport" content="width=device-width, initial-scale=1.0" />
         <title>
            Coordinator Deleted
         </title>
         <meta http-equiv="Content-Language" content="en-us" />
         <meta name="viewport" content="width=device-width, initial-scale=1.0" />
         <link rel="stylesheet" href="include/registration.css" type="text/css" />
      </head>
      <body>
         <h1 align="center">
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
          <meta http-equiv="Content-Language" content="en-us" />
          <meta name="viewport" content="width=device-width, initial-scale=1.0" />
          <link rel="stylesheet" href="include/registration.css" type="text/css" />
       </head>

       <body>
          <form method="post" action="DelEventCoord.php<?php  print "?CoordID=".$_REQUEST['CoordID']."&CordName=".urlencode($_REQUEST['CoordName']); ?>">
             <div style="text-align: center">
                <h1>
                   Deleting Coordinator
                </h1>
                <h2>
                   "<?php  print $_REQUEST['CoordName']; ?>"
                </h2>
             </div>
             <p align="center">
             <input type="submit" value="Confirm Delete!" name="Confirm" />
             <font size="5"><br />
             or</font><br />
             <input type="submit" value="Cancel" name="Cancel" />
             </p>
          </form>
       </body>

   </html>
<?php
}
?>