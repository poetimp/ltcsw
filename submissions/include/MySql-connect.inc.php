<?php
//----------------------------------------------------------------------------
// This software is licensed under the MIT license. Use as you wish but give
// and take credit where due.
//
// Author: Paul Lemmons
//----------------------------------------------------------------------------
?>
<?php
   $db = new PDO("mysql:host=$db_host;dbname=$db_database", $db_user,$db_password);

//-----------------------------------------------------------------------------
// Return a formatted string of a PDR Database error array
//-----------------------------------------------------------------------------
function sqlError($sql='')
{
global $db;

   $pdoErrString='PDOError: ';
   foreach ($db->errorInfo() as $pdoErr)
   {
      $pdoErrString.="[".$pdoErr."]"."<br><pre>$sql</pre>";
   }
   return $pdoErrString;
}

?>
