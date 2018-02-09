<?php
//----------------------------------------------------------------------------
// This software is licensed under the MIT license. Use as you wish but give
// and take credit where due.
//
// Author: Paul Lemmons
//----------------------------------------------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
<?php
include 'include/RegFunctions.php';

if (isset($_POST['Confirm']))
{
   $db->query("delete from $UsersTable where Userid='".$_REQUEST['Userid']."'")
   or die ("Unable to delete userid record: ".sqlError());
   WriteToLog("User ".$_REQUEST['Userid']." Deleted");
   ?>
      <head>
         <title>
            Userid Deleted
         </title>
         <meta http-equiv="Content-Language" content="en-us" />
         <meta name="viewport" content="width=device-width, initial-scale=1.0" />
         <link rel="stylesheet" href="include/registration.css" type="text/css" />
      </head>
      <body>
         <h1 align=center>
            Userid <?php  print $_REQUEST['Userid']; ?> Deleted!
         </h1>
         <?php footer("Return to Userid List","Users.php")?>
      </body>
   </html>
<?php
}
else if (isset($_POST['Cancel']))
{
   header("refresh: 0; url=Users.php");
}
else
{
?>


       <head>
          <meta name="viewport" content="width=device-width, initial-scale=1.0" />
          <title>
             Delete User
          </title>
          <meta http-equiv="Content-Language" content="en-us" />
          <meta name="viewport" content="width=device-width, initial-scale=1.0" />
          <link rel="stylesheet" href="include/registration.css" type="text/css" />
       </head>

       <body>
          <form method="post" action="DelUser.php<?php  print "?Userid=".$_REQUEST['Userid']; ?>">
             <div style="text-align: center">
                <h1>
                   Deleting Userid
                </h1>
                <h2>
                   "<?php  print $_REQUEST['Userid']; ?>"
                </h2>
             </div>
             <p align="center">
             <input type="submit" value="Confirm Delete!" name="Confirm">
             <font size="5"><br />
             or</font><br />
             <input type="submit" value="Cancel" name="Cancel">
             </p>
          </form>
       </body>

   </html>
<?php
}
?>
