<?php
   $db = new PDO("mysql:host=$db_host;dbname=$db_database", $db_user,$db_password);

//-----------------------------------------------------------------------------
// Return a formatted string of a PDR Database error array
//-----------------------------------------------------------------------------
function sqlError()
{
global $db;

   $pdoErrString='PDOError: ';
   foreach ($db->errorInfo() as $pdoErr)
   {
      $pdoErrString.="[".$pdoErr."]";
   }
   return $pdoErrString;
}

?>
