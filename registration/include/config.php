<?php
//===================================================================
// Should be settable online but not there yet so have to set here
//===================================================================
$systemDown = 0;

//===================================================================
// Database connectivity.
//===================================================================
if ($_SERVER['SERVER_NAME'] == 'localhost')
{
   $db_host     = "localhost";
   $db_user     = "***REMOVED***";
   $db_database = "***REMOVED***";
   $db_password = "***REMOVED***";
}
else
{
   $db_host     = "***REMOVED***";
   $db_user     = "***REMOVED***";
   $db_database = "***REMOVED***";
   $db_password = "***REMOVED***";
}


//===================================================================
// Table prefix to be prepended to each table name.
//===================================================================

$db_prefix            = "LTC_PHX_";

//===================================================================
// Establish the names of all of the tables.
//===================================================================
$UsersTable           = $db_prefix.'Users';
$ChurchesTable        = $db_prefix.'Churches';
$ConventionsTable     = $db_prefix.'Conventions';
$EventsTable          = $db_prefix.'Events';
$ExtraOrdersTable     = $db_prefix.'ExtraOrders';
$JudgeAssignmentsTable= $db_prefix.'JudgeAssignments';
$JudgeEventsTable     = $db_prefix.'JudgeEvents';
$JudgeTimesTable      = $db_prefix.'JudgeTimes';
$JudgesTable          = $db_prefix.'Judges';
$LogTable             = $db_prefix.'Log';
$MoneyTable           = $db_prefix.'Money';
$NonParticipantsTable = $db_prefix.'NonParticipants';
$ParticipantsTable    = $db_prefix.'Participants';
$RegistrationTable    = $db_prefix.'Registration';
$RoomsTable           = $db_prefix.'Rooms';
$EventScheduleTable   = $db_prefix.'EventSchedule';
$TeamsTable           = $db_prefix.'Teams';
$UsersTable           = $db_prefix.'Users';
$TeamMembersTable     = $db_prefix.'TeamMembers';
$EventCoordTable      = $db_prefix.'EventCoord';

// print_r($_SESSION);
// print "<br>UsersTable: $UsersTable";
// print "<br>ChruchTable: $ChurchesTable";
// print "<br>ConventionTable: $ConventionsTable";
// print "<br>EventsTable: $EventsTable";
// print "<br>ExtraOrdersTable: $ExtraOrdersTable";
// print "<br>JudgeEventsTable: $JudgeEventsTable";
// print "<br>JudgeTimesTable: $JudgeTimesTable";
// print "<br>JudgesTable: $JudgesTable";
// print "<br>LogTable: $LogTable";
// print "<br>MoneyTable: $MoneyTable";
// print "<br>NonParticipantsTable: $NonParticipantsTable";
// print "<br>ParticipantsTable: $ParticipantsTable";
// print "<br>RegistrationTable: $RegistrationTable";
// print "<br>RoomsTable: $RoomsTable";
// print "<br>EventScheduleTable: $EventScheduleTable";
// print "<br>TeamsTable: $TeamsTable";
// print "<br>UsersTable: $UsersTable";
// print "<br>TeamMembersTable: $TeamMembersTable";
// print "<br>EventCoordTable: $EventCoordTable";
