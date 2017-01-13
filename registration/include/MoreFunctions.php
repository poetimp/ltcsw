<?php
//----------------------------------------------------------------------------
// Added this file for functions shared between applications without including
// the entire RegFunction.php file which includes auth.inc.php
//----------------------------------------------------------------------------

//-----------------------------------------------------------------------------
// Abstracted way to read array. See GET() and POST()
//-----------------------------------------------------------------------------
function SAFE($array, $key, $default = null) {
    return isset($array[$key]) ? $array[$key] : $default;
}

//-----------------------------------------------------------------------------
// Abstracted way to read the $_GET array
//-----------------------------------------------------------------------------
function GET($key, $default = null) {
    return SAFE($_GET, $key, $default);
}

//-----------------------------------------------------------------------------
// Abstracted way to read the $_POST array
//-----------------------------------------------------------------------------
function POST($key, $default = null) {
    return SAFE($_POST, $key, $default);
}

//-----------------------------------------------------------------------------
// Redirect browser to different page
//-----------------------------------------------------------------------------
function redirect($url, $exit = true) {
    header('location: ' . $url);
    if ($exit)
        exit;
}

//-----------------------------------------------------------------------------
// Redirect browser to different page
//-----------------------------------------------------------------------------
function base_url(){
    if(strtolower($_SERVER['SERVER_NAME']) == 'ltcsw.org'){
        return 'http://ltcsw.org/';
    } else {
        return 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/';
    }
}

//-----------------------------------------------------------------------------
// Lookup Participant by FirstName, Lastname, and ChurchID
// Currently used in schedule app alpha
//-----------------------------------------------------------------------------

function ParticipantLookup($FirstName, $LastName, $ChurchID) {
    global $ParticipantsTable;
    $sql = sprintf('SELECT * FROM %s WHERE FirstName = %s AND LastName = %s AND ChurchID = %s',
            $ParticipantsTable,
            escape($FirstName),
            escape($LastName),
            escape($ChurchID));
    $results = Query($sql);
    return isset($results[0]) ? $results[0] : false;
}

if (!function_exists('TimeToStr')) {

//-----------------------------------------------------------------------------
// Represent Time value (DHHMM) in readable format
//-----------------------------------------------------------------------------
    function TimeToStr($Time) {
        $dayNames = array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");

        $day = substr($Time, 0, 1);
        $hour = substr($Time, 1, 2);
        $min = substr($Time, 3, 2);

        return $dayNames[$day - 1] . " " . ($hour > 12 ? $hour - 12 : $hour - 0) . ":" . $min . ($hour >= 12 ? "pm" : "am");
    }

}