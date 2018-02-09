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
   $db->query("delete from $JudgesTable             where JudgeID=".$_REQUEST['JudgeID'])
      or die ("Unable to delete Judge record: "        . sqlError());
   $db->query("delete from $JudgeAssignmentsTable   where JudgeID=".$_REQUEST['JudgeID'])
      or die ("Unable to delete Judge Assignment records: "  . sqlError());
   ?>
      <head>
         <meta http-equiv="Content-Language" content="en-us" />
         <meta name="viewport" content="width=device-width, initial-scale=1.0" />
         <link rel="stylesheet" href="include/registration.css" type="text/css" />
         <title>
            Judge Deleted
         </title>
         <meta http-equiv="Content-Language" content="en-us" />
         <meta name="viewport" content="width=device-width, initial-scale=1.0" />
         <link rel="stylesheet" href="include/registration.css" type="text/css" />
      </head>
      <body>
         <h1 align=center>
            Judge: <?php  print $_REQUEST['Name']; ?> Deleted!
         </h1>
         <?php footer("Return to Judge List","Judges.php")?>
      </body>
   </html>
<?php
}
else if (isset($_POST['Cancel']))
{
   header("refresh: 0; url=Judges.php");
}
else
{
?>


       <head>
          <title>
             Delete Judge
          </title>
          <meta http-equiv="Content-Language" content="en-us" />
          <meta name="viewport" content="width=device-width, initial-scale=1.0" />
          <link rel="stylesheet" href="include/registration.css" type="text/css" />
       </head>

       <body>
          <form method="post" action="DelJudge.php<?php  print "?JudgeID=".$_REQUEST['JudgeID']."&Name=".urlencode($_REQUEST['Name']); ?>">
             <div style="text-align: center">
                <h1>
                   Deleting Judge
                </h1>
                <h2>
                   "<?php  print $_REQUEST['Name']; ?>"
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
