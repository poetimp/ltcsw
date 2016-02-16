<?php
//----------------------------------------------------------------------------
// This software is licensed under the MIT license. Use as you wish but give
// and take credit where due.
//
// Author: Paul Lemmons
//----------------------------------------------------------------------------
?>
<?php
$fileName=$_REQUEST['fileName'];

if (preg_match("/\.jpg$|\.jpeg$/",$fileName))
   $fileType='jpeg';
else if (preg_match("/\.gif$/",$fileName))
   $fileType='gif';
else if (preg_match("/\.png$/",$fileName))
   $fileType='png';
header("Content-Type: image/$fileType");
readfile($fileName);
?>
