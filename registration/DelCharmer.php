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
   $db->query("delete from $CharmersTable where charmerID='".$_REQUEST['id']."'")
   or die ("Unable to delete charmer record: ".sqlError());
   WriteToLog("Charmer ".$_REQUEST['name']." Deleted");
   ?>
      <head>
         <title>
            Charmer Deleted
         </title>
         <meta http-equiv="Content-Language" content="en-us" />
         <meta name="viewport" content="width=device-width, initial-scale=1.0" />
         <link rel="stylesheet" href="include/registration.css" type="text/css" />
      </head>
      <body>
         <h1 align="center">
            Charmer <?php  print $_REQUEST['name']; ?> Deleted!
         </h1>
         <?php footer("Return to Charmers List","Charmers.php")?>
      </body>
   </html>
<?php
}
else if (isset($_POST['Cancel']))
{
   header("refresh: 0; url=Charmers.php");
}
else
{
?>


       <head>
          <meta name="viewport" content="width=device-width, initial-scale=1.0" />

          <title>
             Delete Charmer
          </title>
          <meta http-equiv="Content-Language" content="en-us" />
          <meta name="viewport" content="width=device-width, initial-scale=1.0" />
          <link rel="stylesheet" href="include/registration.css" type="text/css" />
       </head>

       <body>
          <form method="post" action="DelCharmer.php<?php  print "?id=".$_REQUEST['id']."&name=".$_REQUEST['name']; ?>">
             <div style="text-align: center">
                <h1>
                   Deleting Charmer
                </h1>
                <h2>
                   "<?php  print $_REQUEST['name']; ?>"
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
