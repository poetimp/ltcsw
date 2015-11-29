<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
<?php
include 'include/RegFunctions.php';

if (isset($_POST['Confirm']))
{
   $db->query("delete from $UsersTable where Userid='".$_REQUEST['Userid']."'")
   or die ("Unable to delete userid record: ".sqlError($db->errorInfo()));
   WriteToLog("User ".$_REQUEST['Userid']." Deleted");
   ?>
      <head>
         <title>
            Userid Deleted
         </title>
      </head>
      <body style="background-color: rgb(217, 217, 255);">
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
          <title>
             Delete User
          </title>
       </head>

       <body style="background-color: rgb(217, 217, 255);">
          <form method="post" action="DelUser.php<?php  print "?Userid=".$_REQUEST['Userid']; ?>">
             <center>
                <h1>
                   Deleting Userid
                </h1>
                <h2>
                   "<?php  print $_REQUEST['Userid']; ?>"
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
