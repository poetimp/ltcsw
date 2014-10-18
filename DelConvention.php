<?php
include 'include/RegFunctions.php';
if ($Admin != 'Y')
{
   header("refresh: 0; URL=Admin.php");
   die();
}

if (isset($_POST['Confirm']))
{
   $ConvCode=$_REQUEST['ConvCode'];

   mysql_query("delete from $ConventionsTable where ConvCode='$ConvCode'")
       or die ("Unable to delete Convention record: " . mysql_error());

   ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
      <head>
         <title>
            Convention Deleted
         </title>
      </head>
      <body style="background-color: rgb(217, 217, 255);">
         <h1 align=center>
            Convention <?php  print $_REQUEST['ConvName']; ?> Deleted!
         </h1>
\         <?php footer("Return to Convention List","Conventions.php")?>
      </body>
   </html>
<?php
}
else if (isset($_POST['Cancel']))
{
   header("refresh: 0; url=Conventions.php");
}
else
{
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">

       <head>
          <title>
             Delete Convention
          </title>
       </head>

       <body style="background-color: rgb(217, 217, 255);">
          <form method="post" action="DelConvention.php<?php  print "?ConvName=".urlencode($_REQUEST['ConvName'])
                                                                   ."&ConvCode=".urlencode($_REQUEST['ConvCode']); ?>">
             <center>
                <h1>
                   Deleting Convention
                </h1>
                <h2>
                   "<?php  print $_REQUEST['ConvName']; ?>"
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