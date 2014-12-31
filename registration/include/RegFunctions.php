<?php
include 'include/auth.inc.php';
include 'include/MySql-connect.inc.php';
$ChurchesTable        = GetTable('Churches');
$ConventionsTable     = GetTable('Conventions');
$EventsTable          = GetTable('Events');
$ExtraOrdersTable     = GetTable('ExtraOrders');
$JudgeAssignmentsTable= GetTable('JudgeAssignments');
$JudgeEventsTable     = GetTable('JudgeEvents');
$JudgeTimesTable      = GetTable('JudgeTimes');
$JudgesTable          = GetTable('Judges');
$LogTable             = GetTable('Log');
$MoneyTable           = GetTable('Money');
$NonParticipantsTable = GetTable('NonParticipants');
$ParticipantsTable    = GetTable('Participants');
$RegistrationTable    = GetTable('Registration');
$RoomsTable           = GetTable('Rooms');
$EventScheduleTable   = GetTable('EventSchedule');
$TeamsTable           = GetTable('Teams');
$UsersTable           = GetTable('Users');
$TeamMembersTable     = GetTable('TeamMembers');
$EventCoordTable      = GetTable('EventCoord');

// print_r($_SESSION);
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

//-----------------------------------------------------------------------------
// Set Prices (Should be a database function, not hard coded as it is)
//-----------------------------------------------------------------------------
function GetPrices()
{
   $price["Registration"] = 60;
   $price["Shirt"]        = 15;
   $price["AdultMeal"]    = 25;
   $price["ChildMeal"]    = 15;
//   $price["FiveMeal"]     = 31;
   return $price;
}
//-----------------------------------------------------------------------------
// Translate Table Names
//-----------------------------------------------------------------------------
function GetTable($TableName)
{
   return(isset($_SESSION[$TableName]) ? $_SESSION[$TableName] : "<error>");
}

//-----------------------------------------------------------------------------
// Format a dollar amount so that it is readable
//-----------------------------------------------------------------------------
function FormatMoney($Amount)
{
   if ($Amount >= 0)
      return ('$'.number_format($Amount,2));
   else
      return ('<font color=red><b>($'.number_format($Amount,2).')</b></font>');
}
//-----------------------------------------------------------------------------
// Make an entry in the log database
//-----------------------------------------------------------------------------
function WriteToGlobalLog($LogEntry)
{
   $Userid = $_SESSION['Userid'];

   mysql_query("insert into LTC_ALL_Log
                      (Date,
                       UserID,
                       Action
                      )
                values(Now(),
                       '$Userid',
                       '$LogEntry'
                      )
               ")
   or die ("Unable to write to global log: ".mysql_error());
}
//-----------------------------------------------------------------------------
// Make an entry in the log database
//-----------------------------------------------------------------------------
function WriteToLog($LogEntry)
{
   $Userid = $_SESSION['Userid'];
   $LogTable = GetTable('Log');

   mysql_query("insert into $LogTable
                      (Date,
                       UserID,
                       Action
                      )
                values(Now(),
                       '$Userid',
                       '$LogEntry'
                      )
               ")
   or die ("Unable to write to log: ".mysql_error());
}
//-----------------------------------------------------------------------------
// This function returns a list of all defined churches
//-----------------------------------------------------------------------------
function ChurchesDefined()
{
   global $ChurchesTable;

   $ChurchIDs=array();
   $churches = mysql_query("select   ChurchID,
                                     ChurchName
                            from     $ChurchesTable
                            order by ChurchName
                            ")
               or die ("Unable to get church list:" . mysql_error());

   while ($row = mysql_fetch_assoc($churches))
   {
      $ChurchIDs[$row['ChurchID']] = $row['ChurchName'];
   }
   natsort($ChurchIDs);
   return $ChurchIDs;
}

//-----------------------------------------------------------------------------
// This function returns a list of churches that have at least one participant
// registered in at least one event.
//-----------------------------------------------------------------------------
function ChurchesRegistered()
{
   global $ChurchesTable,
          $RegistrationTable,
          $TeamMembersTable;
   $ChurchIDs=array();
   $churches = mysql_query("select   distinct
                                     c.ChurchID,
                                     c.ChurchName
                            from     $ChurchesTable     c,
                                     $RegistrationTable r
                            where    c.ChurchID = r.ChurchID
                            ")
               or die ("Unable to get church list from registration:" . mysql_error());

   while ($row = mysql_fetch_assoc($churches))
   {
      $ChurchIDs[$row['ChurchID']] = $row['ChurchName'];
   }
   $churches = mysql_query("select   distinct
                                     c.ChurchID,
                                     c.ChurchName
                            from     $ChurchesTable     c,
                                     $TeamMembersTable  t
                            where    c.ChurchID = t.ChurchID
                            ")
               or die ("Unable to get church list from team members:" . mysql_error());

   while ($row = mysql_fetch_assoc($churches))
   {
      $ChurchIDs[$row['ChurchID']] = $row['ChurchName'];
   }
   natsort($ChurchIDs);
   return $ChurchIDs;
}

//-----------------------------------------------------------------------------
// Given a ChurchID this function will return a list of participants that are
// registered in at least one event.
//-----------------------------------------------------------------------------
function ActiveParticipants($ChurchID)
{
   global $ParticipantsTable,
          $RegistrationTable,
          $TeamMembersTable;

   $ParticipantIDs=array();
   $participants = mysql_query("select   distinct
                                         p.ParticipantID,
                                         concat(p.FirstName,' ',p.LastName) ParticipantName
                                from     $ParticipantsTable p,
                                         $RegistrationTable r
                                where    p.ChurchID      = $ChurchID
                                and      p.ChurchID      = r.ChurchID
                                and      p.ParticipantID = r.ParticipantID
                            ")
               or die ("Unable to get Participant list from registration:" . mysql_error());

   while ($row = mysql_fetch_assoc($participants))
   {
      $ParticipantIDs[$row['ParticipantID']] = $row['ParticipantName'];
   }
   $participants = mysql_query("select   distinct
                                         p.ParticipantID,
                                         concat(p.LastName,', ',p.FirstName) ParticipantName
                                from     $ParticipantsTable p,
                                         $TeamMembersTable  t
                                where    p.ChurchID      = $ChurchID
                                and      p.ParticipantID = t.ParticipantID
                            ")
               or die ("Unable to get participant list from team members:" . mysql_error());

   while ($row = mysql_fetch_assoc($participants))
   {
      $ParticipantIDs[$row['ParticipantID']] = $row['ParticipantName'];
   }
   natsort($ParticipantIDs);
   return $ParticipantIDs;
}
//-----------------------------------------------------------------------------
// This function returns a list of events a  participant has registered for
//-----------------------------------------------------------------------------
function ParticipantEvents($ParticipantID)
{
   global $ParticipantsTable,
          $RegistrationTable,
          $TeamMembersTable,
          $EventsTable,
          $TeamsTable;
   $events = mysql_query("select   distinct
                                   r.EventID,
                                   e.EventName
                          from     $ParticipantsTable p,
                                   $RegistrationTable r,
                                   $EventsTable       e
                          where    p.ParticipantID = $ParticipantID
                          and      r.ParticipantID = p.ParticipantID
                          and      e.EventID       = r.EventID
                          and      e.TeamEvent     = 'N'
                         ")
             or die ("Unable to get Participant list from registration:" . mysql_error());

   while ($row = mysql_fetch_assoc($events))
   {
      $eventIDs[$row['EventID']] = $row['EventName'];
   }
   $events = mysql_query("select   distinct
                                   t.EventID,
                                   e.EventName
                          from     $ParticipantsTable p,
                                   $TeamMembersTable  m,
                                   $EventsTable       e,
                                   $TeamsTable        t
                          where    p.ParticipantID = $ParticipantID
                          and      m.ParticipantID = p.ParticipantID
                          and      e.EventID       = t.EventID
                          and      t.TeamID        = m.TeamID
                         ")
             or die ("Unable to get participant list from team members:" . mysql_error());

   while ($row = mysql_fetch_assoc($events))
   {
      $eventIDs[$row['EventID']] = $row['EventName'];
   }
   natsort($eventIDs);
   return $eventIDs;
}

//-----------------------------------------------------------------------------
// Determine the award a participant received in a given event
//-----------------------------------------------------------------------------
function ParticipantAward($ParticipantID,$EventID)
{
   global $EventsTable,
          $TeamsTable,
          $TeamMembersTable,
          $RegistrationTable;

   $results = mysql_query("Select  TeamEvent,
                                   IndividualAwards
                           from    $EventsTable
                           where   EventID = $EventID
                        ")
            or die ("Unable to determine event type:" . mysql_error());
   $row = mysql_fetch_assoc($results);
   $TeamEvent        = ($row['TeamEvent'] == 'Y');
   $IndividualAwards = ($row['IndividualAwards'] == 'Y');

   if ($TeamEvent)
   {
      $results = mysql_query("SELECT t.TeamID,
                                     IFNULL(m.Award,'Not Assigned') Award
                              from   $TeamsTable       t,
                                     $TeamMembersTable m
                              where  t.TeamID        = m.TeamID
                              and    t.EventID       = $EventID
                              and    m.ParticipantID = $ParticipantID")
                 or die ("Unable to determine team membership:" . mysql_error());

      $row = mysql_fetch_assoc($results);
      $TeamID     = $row['TeamID'];
      $Solo_Award = $row['Award'];

      $results = mysql_query("Select  IFNULL(Award,'Not Assigned') Award
                              from    $RegistrationTable
                              where   EventID       = $EventID
                              and     ParticipantID = $TeamID
                           ")
                 or die ("Unable to determine individual Award:" . mysql_error());
      $row = mysql_fetch_assoc($results);
      $Award = $row['Award'];

      if ($IndividualAwards)
      {
         $Award = "Team: ".$Award.", Individual: ".$Solo_Award;

      }
   }
   else
   {
      $results = mysql_query("Select  IFNULL(Award,'Not Assigned') Award
                              from    $RegistrationTable
                              where   EventID       = $EventID
                              and     ParticipantID = $ParticipantID
                           ")
                 or die ("Unable to determine individual Award:" . mysql_error());
      $row = mysql_fetch_assoc($results);
      $Award = $row['Award'];
   }
   return $Award;
}
//-----------------------------------------------------------------------------
// Return the name of a church given its numeric ID
//-----------------------------------------------------------------------------
function ChurchName($ChurchID)
{
   global $ChurchesTable;

   $result = mysql_query("select   ChurchName
                          from     $ChurchesTable
                          where    ChurchID      = $ChurchID
                         ")
             or die ("Unable to get ChurchID:" . mysql_error());

   $row = mysql_fetch_assoc($result);
   return $row['ChurchName'];
}

//-----------------------------------------------------------------------------
// Return an array with two indicies ('Solo' and 'Team'). The values associated
// with each index represents the number of events the participant is in of
// that type.
//-----------------------------------------------------------------------------
function EventCounts($ParticipantID)
{
   global $RegistrationTable,
          $EventsTable,
          $TeamMembersTable,
          $TeamsTable;

   $cntResult = mysql_query("select distinct count(*) as count
                             from   $RegistrationTable r,
                                    $EventsTable       e,
                                    $TeamMembersTable  m,
                                    $TeamsTable        t
                             where  m.ChurchID        = r.ChurchID
                             and    t.ChurchID        = r.ChurchID
                             and    m.ParticipantID   = $ParticipantID
                             and    r.ParticipantID   = m.TeamID
                             and    t.TeamID          = m.TeamID
                             and    r.EventID         = e.EventID
                             and    t.EventID         = e.EventID
                             and    e.TeamEvent       = 'Y'
                            ")
                or die ("Unable to determine team event count:" . mysql_error());
   $cntRow = mysql_fetch_assoc($cntResult);
   $EventsCount['Team'] = $cntRow['count'];

   $cntResult = mysql_query("select count(*) as count
                             from   $RegistrationTable r,
                                    $EventsTable       e
                             where  ParticipantID     = $ParticipantID
                             and    r.EventID         = e.EventID
                             and    e.TeamEvent       = 'N'
                            ")
                or die ("Unable to determine solo event count:" . mysql_error());
   $cntRow = mysql_fetch_assoc($cntResult);
   $EventsCount['Solo'] = $cntRow['count'];

   return $EventsCount;
}
function ChurchExpenses($ChurchID)
{

   global $ChurchesTable,
            $EventsTable,
            $RegistrationTable,
            $ParticipantsTable,
            $TeamMembersTable,
            $ExtraOrdersTable,
            $MoneyTable;
   //-----------------------------------------------------------------------
   // Get the cost for various items
   //-----------------------------------------------------------------------
   $price          = GetPrices();
   $RegCost        = $price["Registration"];
   $ShirtCost      = $price["Shirt"];
   $AdultMealCost  = $price["AdultMeal"];
   $ChildMealCost  = $price["ChildMeal"];

   //-----------------------------------------------------------------------
   // Get the active participant list and put it in sql "in" clause format
   //-----------------------------------------------------------------------
   $participantList  = ActiveParticipants($ChurchID);
   $ParticipantCount = count($participantList);
   if ($ParticipantCount > 0)
   {
      $inClause = "(";
      foreach ($participantList as $participantID=>$participantName)
         $inClause.="$participantID,";
      $inClause=trim($inClause,",").")";
   }


   //-----------------------------------------------------------------------
   // Get the number of extra Adult-meal meal tickets
   //-----------------------------------------------------------------------
   $results = mysql_query("select sum(ItemCount) count
                           from   $ExtraOrdersTable
                           where  ChurchID   = '$ChurchID'
                           and    ItemType   = 'AdultMeal'
                        ")
            or die ("Unable to get Extra Adult Meal Ticket Count:" . mysql_error());
   $row = mysql_fetch_assoc($results);
   $ExtraAdultMealCount = isset($row['count']) ? $row['count'] : 0;
   //-----------------------------------------------------------------------
   // Get the number of extra Child-meal meal tickets
   //-----------------------------------------------------------------------
   $results = mysql_query("select sum(ItemCount) count
                           from   $ExtraOrdersTable
                           where  ChurchID   = '$ChurchID'
                           and    ItemType   = 'ChildMeal'
                        ")
            or die ("Unable to get Extra Child Meal Ticket Count:" . mysql_error());
   $row = mysql_fetch_assoc($results);
   $ExtraChildMealCount = isset($row['count']) ? $row['count'] : 0;
   //-----------------------------------------------------------------------
   // Get the number of extra t-shirts ordered
   //-----------------------------------------------------------------------
   $results = mysql_query("select sum(ItemCount) count
                           from   $ExtraOrdersTable
                           where  ChurchID   = '$ChurchID'
                           and    ItemType in ('YM','YL','S','M','LG','XL','XX')
                        ")
            or die ("Unable to get Extra T-Shirt Count:" . mysql_error());
   $row = mysql_fetch_assoc($results);
   $ExtraShirtCount = isset($row['count']) ? $row['count'] : 0;

   //-----------------------------------------------------------------------
   // Check with accounting to see what monies have been received
   //-----------------------------------------------------------------------
   $results = mysql_query("select sum(Amount) MoneyInOut
                           from   $MoneyTable
                           where  ChurchID   = $ChurchID
                        ")
            or die ("Unable to get monies in and out:" . mysql_error());
   $row = mysql_fetch_assoc($results);
   $MoneyInOut = $row['MoneyInOut'];

   //-----------------------------------------------------------------------
   // Calculate the costs of all of this
   //-----------------------------------------------------------------------
   $costDetails["ParticipantCount"]     = $ParticipantCount;
   $costDetails["ExtraAdultMealCount"]  = $ExtraAdultMealCount;
   $costDetails["ExtraChildMealCount"]  = $ExtraChildMealCount;
   $costDetails["ExtraShirtCount"]      = $ExtraShirtCount;

   $costDetails["Participant"]          = $ParticipantCount    * $RegCost;
   $costDetails["ExtraAdultMeals"]      = $ExtraAdultMealCount * $AdultMealCost;
   $costDetails["ExtraChildMeals"]      = $ExtraChildMealCount * $ChildMealCost;
   $costDetails["ExtraShirts"]          = $ExtraShirtCount     * $ShirtCost;
   
   $costDetails["Total"]       = $costDetails["Participant"] +
                                 $costDetails["ExtraAdultMeals"] +
                                 $costDetails["ExtraChildMeals"] +
                                 $costDetails["ExtraShirts"];
   
   $costDetails["Balance"]     = $costDetails["Total"] + $MoneyInOut;
   $costDetails["MoneyInOut"]  = $MoneyInOut * -1;
   
   return($costDetails);
}

function footer($linkText, $linkURL)
{
   print "<p align=\"center\">\n";
   print "   <a href=\"Admin.php\">Return to Admin Home</a>\n";
   if ($linkText != "")
   {
      print "   <big><b>|</b></big>\n";
      print "   <a href=\"$linkURL\">$linkText</a>\n";
   }
   print "</p>\n";

}
//-----------------------------------------------------------------------------
// Get the list of available rooms
//-----------------------------------------------------------------------------
function getRoomList($fullName)
{
   static $roomList = array();
   global $RoomsTable;

   if (count($roomList) == 0)
   {
      if (isset($fullName) and $fullName == 'fullnames')
      {
         $result     = mysql_query("select   RoomID,
                                             RoomName
                                    from     $RoomsTable
                                    order by RoomName
                                   ")
                     or die ("Unable to get room information: ".mysql_error());
      }
      else
      {
         $result     = mysql_query("select   RoomID,
                                             IF (RoomName REGEXP '-[a-z]$',
                                                SUBSTR(RoomName,1,LENGTH(RoomName)-2), 
                                                RoomName)
                                             as RoomName
                                    from     $RoomsTable
                                    order by RoomName
                                   ")
                     or die ("Unable to get room information: ".mysql_error());
      }
      //$roomList[0] = 'Unassigned';
      while ($row = mysql_fetch_assoc($result))
      {
         $roomList[$row['RoomID']] = $row['RoomName'];
      }
   }
   asort($roomList);
   return $roomList;
}
//-----------------------------------------------------------------------------
// Get room name given RoomID (strips the stupid room itterater at the end of the name)
//-----------------------------------------------------------------------------
function getRoomName($RoomID)
{
   global $RoomsTable;

   $result = mysql_query("select   IF (RoomName REGEXP '-[a-z]$',
										         SUBSTR(RoomName,1,LENGTH(RoomName)-2), 
													RoomName)
			                          as RoomName
                          from     $RoomsTable
                          where    RoomID      = $RoomID
                         ")
             or die ("Unable to get Room namme for RoomID $RoomID:" . mysql_error());

   $row = mysql_fetch_assoc($result);
   return $row['RoomName'];
}


//-----------------------------------------------------------------------------
// Get Schedule Start Time
//-----------------------------------------------------------------------------
function getStartTime($SchedID)
{
   global $EventScheduleTable;

   $result = mysql_query("select   StartTime
                          from     $EventScheduleTable
                          where    SchedID      = $SchedID
                         ")
             or die ("Unable to get Start Time for SchedID $SchedID:" . mysql_error());

   $row = mysql_fetch_assoc($result);
   return $row['StartTime'];
}

//-----------------------------------------------------------------------------
// Schedule an event - Returns true for success and false for failure
// StartYime = DHHMM  where Sunday is 1 and Saturday is 7
//-----------------------------------------------------------------------------
function ScheduleEventAdd($EventID,$StartTime,$RoomID)   //Returns Success or Failure
{
   global $RoomsTable,
          $EventsTable,
          $EventScheduleTable;

   if (ScheduleEventTimeAvailable($EventID,$StartTime,$RoomID))
   {

      $result = mysql_query("select   Duration
                             from     $EventsTable
                             where    EventID      = $EventID
                            ")
                or die ("Unable to get Duration for EventID $EventID:" . mysql_error());

      $row = mysql_fetch_assoc($result);
      $EndTime = AddTime($StartTime,$row['Duration']);

      mysql_query("insert into $EventScheduleTable
                         (EventID,
                          StartTime,
                          EndTime,
                          RoomID
                         )
                   values($EventID,
                          $StartTime,
                          $EndTime,
                          $RoomID
                         )
                  ")
      or die ("Unable to write to EventSchedule: ".mysql_error());
      return TRUE;
   }
   else
   {
      return FALSE;
   }
}

//-----------------------------------------------------------------------------
// Get a list of rooms and the times it is scheduled for an event
//-----------------------------------------------------------------------------
function ScheduleEventGet($EventID)                             //Returns a list of schedule ID's
{
   global $EventScheduleTable,
          $RoomsTable;

   $result     = mysql_query("select   RoomName,
                                       StartTime
                                 from  $EventScheduleTable e,
                                       $RoomsTable         r
                                 where e.EventID = $EventID
                                 and   r.RoomID  = e.RoomID
                                 order by RoomName
                                ")
                  or die ("Unable to get room information: ".mysql_error());

   while ($row = mysql_fetch_assoc($result))
   {
      $roomList[$row['RoomName']] = $row['StartTime'];
   }

   return $roomList;
}

//-----------------------------------------------------------------------------
// Remove an event from the schedule
// StartTime = DHHMM  where Sunday is 1 and Saturday is 7
//-----------------------------------------------------------------------------
function ScheduleEventDel($EventID,$StartTime,$RoomID)                             //Returns Success or Failure
{
   global $EventScheduleTable;

   $result = mysql_query("select   count(*) Count
                          from     $EventScheduleTable
                          where    EventID      = $EventID
                          and      StartTime    = $StartTime
                          and      RoomID       = $RoomID
                         ")
             or die ("Unable to get Event Count for EventID $EventID:" . mysql_error());

   $row = mysql_fetch_assoc($result);
   $Count = $row['Count'];

   if ($Count == 0)
   {
      return FALSE;
   }
   else
   {
      mysql_query("delete from     $EventScheduleTable
                          where    EventID      = $EventID
                          and      StartTime    = $StartTime
                          and      RoomID       = $RoomID
                         ")
             or die ("Unable to remove event from schedule:" . mysql_error());
      return TRUE;
   }

}

//-----------------------------------------------------------------------------
// Returns True if time is available and False if it is not. A time slot
// is only available if it does not overlap in any way with another even in the
// same room.
// StartTime = DHHMM  where Sunday is 1 and Saturday is 7
//-----------------------------------------------------------------------------
function ScheduleEventTimeAvailable($EventID,$StartTime,$RoomID)
{
   global $RoomsTable,
          $EventsTable,
          $EventScheduleTable;

   $result = mysql_query("select   Duration
                          from     $EventsTable
                          where    EventID      = $EventID
                         ")
             or die ("Unable to get Duration for EventID $EventID:" . mysql_error());

   $row = mysql_fetch_assoc($result);
   $EndTime = AddTime($StartTime,$row['Duration']);

//   print   "<pre>select   count(*) Count
//                          from     $EventScheduleTable s,
//                                   $RoomsTable         r
//                          where    s.RoomID         = $RoomID
//                          and      r.RoomID         = $RoomID
//                          and      r.AllowConflicts = FALSE
//                          and (
//                                 (  $StartTime >= StartTime
//                                    and
//                                    $StartTime <= EndTime
//                                 )
//                                 or
//                                 (
//                                    $EndTime >= StartTime
//                                    and
//                                    $EndTime <= EndTime
//                                 )
//                              )
//                            </pre>";
//
   $result = mysql_query("select   count(*) Count
                          from     $EventScheduleTable s,
                                   $RoomsTable         r
                          where    s.RoomID         = $RoomID
                          and      r.RoomID         = $RoomID
                          and      r.AllowConflicts = FALSE
                          and (
                                 (  $StartTime >= StartTime
                                    and
                                    $StartTime <= EndTime
                                 )
                                 or
                                 (
                                    $EndTime >= StartTime
                                    and
                                    $EndTime <= EndTime
                                 )
                              )
                         ")
                          or die ("Unable to get schedule for the Event:" . mysql_error());

   $row = mysql_fetch_assoc($result);
   $Count = $row['Count'];

   return ($Count == 0);  // 0 = Time available !0 = Time not available
}

//-----------------------------------------------------------------------------
// Returns the event name time is occupied otherwise returns ""
// StartTime = DHHMM  where Sunday is 1 and Saturday is 7
//-----------------------------------------------------------------------------
function ScheduleGetEventName($checkSchedID,$ParticipantID)
{
   global $RoomsTable,
          $EventsTable,
          $TeamsTable,
          $TeamMembersTable,
          $RegistrationTable,
          $EventScheduleTable;

// First get the start and stop time for the event being verified
   $result = mysql_query("select   StartTime,
                                   EndTime
                          from     $EventScheduleTable
                          where    SchedID = $checkSchedID
                         ")
             or die ("Unable to get schedule information for participant $ParticipantID:" . mysql_error());
   $row = mysql_fetch_assoc($result);
   $checkStartTime = $row['StartTime'];
   $checkEndTime   = $row['EndTime'];

// Now get the list of the solo events that the person is signed up for
   $result = mysql_query("select   StartTime,
                                   EndTime,
                                   EventName
                          from     $EventScheduleTable s,
                                   $RegistrationTable  r,
                                   $EventsTable        e
                          where    r.ParticipantID = $ParticipantID
                          and      r.SchedID       = s.SchedID
                          and      r.EventID       = e.eventID
                          and      e.TeamEvent     = 'N'
                          and      e.ConvEvent     = 'C'
                         ")
             or die ("Unable to get solo schedule for participant $ParticipantID:" . mysql_error());

// And check each even against the one they are wanting to sign up for. If there is overlap
// there is conflict

   $conflict=FALSE;
   while (! $conflict and $row = mysql_fetch_assoc($result))
   {
      $StartTime = $row['StartTime'];
      $EndTime   = $row['EndTime'];
      $EventName = $row['EventName'];

      if (($checkStartTime >= $StartTime and $checkStartTime <= $EndTime) or
          ($checkEndTime   >= $StartTime and $checkEndTime   <= $EndTime))
         $conflict=TRUE;
   }

// If there is no solo conflict, check out the teams
   if (! $conflict)
   {
//      print "<pre>
//                             select s.StartTime,
//                                    s.EndTime,
//                                    e.EventName
//                             from   $RegistrationTable  r,
//                                    $EventsTable        e,
//                                    $TeamsTable         t,
//                                    $TeamMembersTable   m,
//                                    $EventScheduleTable s
//                             where  r.ParticipantID = t.TeamID
//                             and    t.TeamID        = m.TeamID
//                             and    m.ParticipantID = $ParticipantID
//                             and    e.EventID       = r.EventID
//                             and    e.TeamEvent     = 'Y'
//                             and    e.ConvEvent     = 'C'
//                             and    r.SchedID       = s.SchedID
//                                   </pre>";
      $result = mysql_query("select s.StartTime,
                                    s.EndTime,
                                    e.EventName
                             from   $RegistrationTable  r,
                                    $EventsTable        e,
                                    $TeamsTable         t,
                                    $TeamMembersTable   m,
                                    $EventScheduleTable s
                             where  r.ParticipantID = t.TeamID
                             and    t.TeamID        = m.TeamID
                             and    m.ParticipantID = $ParticipantID
                             and    e.EventID       = r.EventID
                             and    e.TeamEvent     = 'Y'
                             and    e.ConvEvent     = 'C'
                             and    r.SchedID       = s.SchedID
                            ")
      or die ("Unable to get team schedule for participant $ParticipantID:" . mysql_error());

//      print "CheckStartTime: $checkStartTime, CheckEndTime: $checkEndTime<br>";
      while (! $conflict and $row = mysql_fetch_assoc($result))
      {
         $StartTime = $row['StartTime'];
         $EndTime   = $row['EndTime'];
         $EventName = $row['EventName'];
//         print "EventName: $EventName, StartTime: $StartTime, EndTime: $EndTime<br>";
         if (($checkStartTime >= $StartTime and $checkStartTime <= $EndTime) or
             ($checkEndTime   >= $StartTime and $checkEndTime   <= $EndTime))
            $conflict=TRUE;
      }
   }
// Return our findings
   if ($conflict)
      return $EventName;
   else
      return "";
}

//-----------------------------------------------------------------------------
// Add minutes to a time value in the form DHHMM
//-----------------------------------------------------------------------------
function AddTime($Time,$Minutes)
{
   $day  = substr($Time,0,1);
   $hour = substr($Time,1,2);
   $min  = substr($Time,3,2);

   $TimeMinutes = ($day*1440) + ($hour*60) + ($min) + $Minutes;

   $days  = intval ($TimeMinutes  /  1440);
   $hours = intval(($TimeMinutes - (1440*$days)) / 60);
   $mins  = intval(($TimeMinutes - (1440*$days) - (60*$hours)));

   return ($days*10000)+($hours*100)+$mins;
}

//-----------------------------------------------------------------------------
// Represent Time value (DHHMM) in readable format
//-----------------------------------------------------------------------------
function TimeToStr($Time)
{
   $dayNames = array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");

   $day  = substr($Time,0,1);
   $hour = substr($Time,1,2);
   $min  = substr($Time,3,2);

   return $dayNames[$day-1]." ".($hour>12 ? $hour-12: $hour-0).":".$min.($hour>=12 ? "pm": "am");
}

//-----------------------------------------------------------------------------
// Set the various "bits" in the priv string. This and the "has" function are the
// only the only two functions that should ever have to deal with the specific
// locations of the various privileges.
//-----------------------------------------------------------------------------
function setPrivs($privs)
{

   $privString="NNNNN";
   foreach($privs as $colName => $colValue)
   {
      switch ($colName)
      {
         case "privGlobalAdmin":
            if ($privString{0} == 'N') $privString{0} = $colValue;
            if ($privString{1} == 'N') $privString{1} = $colValue;
            if ($privString{2} == 'N') $privString{2} = $colValue;
            if ($privString{3} == 'N') $privString{3} = $colValue;
            if ($privString{4} == 'N') $privString{4} = $colValue;
            break;

         case "privAdmin":
            if ($privString{1} == 'N') $privString{1} = $colValue;
            if ($privString{2} == 'N') $privString{2} = $colValue;
            if ($privString{3} == 'N') $privString{3} = $colValue;
            break;

         case "privChurchCoord":
            if ($privString{2} == 'N') $privString{2} = $colValue;
            break;

         case "privEventDirector":
            if ($privString{3} == 'N') $privString{3} = $colValue;
            break;

         case "privTally":
            if ($privString{1} == 'N') $privString{1} = $colValue;
            if ($privString{2} == 'N') $privString{2} = $colValue;
            if ($privString{3} == 'N') $privString{3} = $colValue;
            if ($privString{4} == 'N') $privString{4} = $colValue;
            break;
      }
   }
   $_SESSION['privs'] = $privString;
   return($privString);
}
//-----------------------------------------------------------------------------
// Check for a particular privilage. This and the set function are the
// only the only two functions that should ever have to deal with the specific
// locations of the various privileges.
//-----------------------------------------------------------------------------
function hasPriv($priv)
{
   switch ($priv)
   {
      case "GlobalAdmin":
         return (substr($_SESSION['privs'],0,1) == 'Y');

      case "Admin":
         return (substr($_SESSION['privs'],1,1) == 'Y');

      case "ChurchCoord":
         return (substr($_SESSION['privs'],2,1) == 'Y');

      case "EventDirector":
         return (substr($_SESSION['privs'],3,1) == 'Y');

      case "Tally":
         return (substr($_SESSION['privs'],4,1) == 'Y');
   }
}
//-----------------------------------------------------------------------------
//  Because of the stupid ways we are manageing multiple events in the same room we have to use
//  this hack. It looks for all rooms that have the same name (minus the last two characters)  and 
//  counts the number of participants assigned. Then returns that count to the caller.
//-----------------------------------------------------------------------------
function slotsFilledInRoom($RoomName,$StartTime)
{
    global $RoomsTable,
           $RegistrationTable,
           $EventScheduleTable;

    $result = mysql_query("select count(*) as Count
                           from   $EventScheduleTable s,  
                                  $RoomsTable         r,      
                                  $RegistrationTable  p 
                           Where  r.RoomName like '$RoomName%' 
                           and    s.StartTime = '$StartTime' 
                           and    s.SchedID   = p.SchedID 
                           and    s.RoomID    = r.RoomID")
              or die ("Unable to get slot usage for Room:$RoomName at start time $StartTime:" . mysql_error());
   $row = mysql_fetch_assoc($result);
   return ($row['Count']);
}
?>
