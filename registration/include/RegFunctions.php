<?php
//----------------------------------------------------------------------------
// This software is licensed under the MIT license. Use as you wish but give
// and take credit where due.
//
// Author: Paul Lemmons
//----------------------------------------------------------------------------
?>
<?php
#ini_set('display_errors', 1);
#ini_set('display_startup_errors', 1);
#error_reporting(E_ALL);
include 'include/config.php';
include 'include/auth.inc.php';
include 'include/MySql-connect.inc.php';
include __DIR__ . '/DatabaseFunctions.php';

//-----------------------------------------------------------------------------
// Check to see if we are a mobile device or not
//-----------------------------------------------------------------------------
$useragent=$_SERVER['HTTP_USER_AGENT'];
if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',$_SERVER['HTTP_USER_AGENT'])||
    preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($_SERVER['HTTP_USER_AGENT'],0,4))
   )
   $MOBILE=true;
else
   $MOBILE=false;
//-----------------------------------------------------------------------------
// Set Prices (Should be a database function, not hard coded as it is)
//-----------------------------------------------------------------------------
function GetPrices()
{
   $price["Registration"] = 75;
   $price["Shirt"]        = 15;
   $price["AdultMeal"]    = 25;
   $price["ChildMeal"]    = 15;
//   $price["FiveMeal"]     = 31;
   return $price;
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
function WriteToLog($LogEntry)
{
   $Userid = $_SESSION['Userid'];
   global $LogTable;
   global $db;

   $db->query("insert into $LogTable
                      (Date,
                       UserID,
                       Action
                      )
                values(Now(),
                       '$Userid',
                       '$LogEntry'
                      )
               ")
   or die ("Unable to write to log: ".sqlError());
}
//-----------------------------------------------------------------------------
// This function returns a list of all defined churches
//-----------------------------------------------------------------------------
function ChurchesDefined()
{
   global $ChurchesTable;
   global $db;

   $ChurchIDs=array();
   $churches = $db->query("select   ChurchID,
                                     ChurchName
                            from     $ChurchesTable
                            order by ChurchName
                            ")
               or die ("Unable to get church list:" . sqlError());

   while ($row = $churches->fetch(PDO::FETCH_ASSOC))
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
   global $ChurchesTable;
   global $RegistrationTable;
   global $TeamMembersTable;
   global $db;

   $ChurchIDs=array();
   $churches = $db->query("select   distinct
                                     c.ChurchID,
                                     c.ChurchName
                            from     $ChurchesTable     c,
                                     $RegistrationTable r
                            where    c.ChurchID = r.ChurchID
                            ")
               or die ("Unable to get church list from registration:" . sqlError());

   while ($row = $churches->fetch(PDO::FETCH_ASSOC))
   {
      $ChurchIDs[$row['ChurchID']] = $row['ChurchName'];
   }
   $churches = $db->query("select   distinct
                                     c.ChurchID,
                                     c.ChurchName
                            from     $ChurchesTable     c,
                                     $TeamMembersTable  t
                            where    c.ChurchID = t.ChurchID
                            ")
               or die ("Unable to get church list from team members:" . sqlError());

   while ($row = $churches->fetch(PDO::FETCH_ASSOC))
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
   global $db;

   $ParticipantIDs=array();
   $participants = $db->query("select   distinct
                                         p.ParticipantID,
                                         concat(p.LastName,', ',p.FirstName) ParticipantName
                                from     $ParticipantsTable p,
                                         $RegistrationTable r
                                where    p.ChurchID      = $ChurchID
                                and      p.ChurchID      = r.ChurchID
                                and      p.ParticipantID = r.ParticipantID
                            ")
               or die ("Unable to get Participant list from registration:" . sqlError());

   while ($row = $participants->fetch(PDO::FETCH_ASSOC))
   {
      $ParticipantIDs[$row['ParticipantID']] = $row['ParticipantName'];
   }
   $participants = $db->query("select   distinct
                                         p.ParticipantID,
                                         concat(p.LastName,', ',p.FirstName) ParticipantName
                                from     $ParticipantsTable p,
                                         $TeamMembersTable  t
                                where    p.ChurchID      = $ChurchID
                                and      p.ParticipantID = t.ParticipantID
                            ")
               or die ("Unable to get participant list from team members:" . sqlError());

   while ($row = $participants->fetch(PDO::FETCH_ASSOC))
   {
      $ParticipantIDs[$row['ParticipantID']] = $row['ParticipantName'];
   }
   natsort($ParticipantIDs);
   return $ParticipantIDs;
}
//-----------------------------------------------------------------------------
// This function returns a list of events a  participant has registered for
//-----------------------------------------------------------------------------
function ParticipantDetails($ParticipantID)
{
   global $ParticipantsTable;
   global $db;

   $details = $db->query("select   *
                          from     $ParticipantsTable
                          where    ParticipantID = $ParticipantID
                         ")
             or die ("Unable to get Participant details from Participant Table:" . sqlError());

   $row = $details->fetch(PDO::FETCH_ASSOC);
   return $row;
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
   global $db;

   $events = $db->query("select   distinct
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
             or die ("Unable to get Participant list from registration:" . sqlError());

   while ($row = $events->fetch(PDO::FETCH_ASSOC))
   {
      $eventIDs[$row['EventID']] = $row['EventName'];
   }
   $events = $db->query("select   distinct
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
             or die ("Unable to get participant list from team members:" . sqlError());

   while ($row = $events->fetch(PDO::FETCH_ASSOC))
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
   global $db;

   $results = $db->query("Select  TeamEvent,
                                   IndividualAwards
                           from    $EventsTable
                           where   EventID = $EventID
                        ")
            or die ("Unable to determine event type:" . sqlError());
   $row = $results->fetch(PDO::FETCH_ASSOC);
   $TeamEvent        = ($row['TeamEvent'] == 'Y');
   $IndividualAwards = ($row['IndividualAwards'] == 'Y');

   if ($TeamEvent)
   {
      $results = $db->query("SELECT t.TeamID,
                                     IFNULL(m.Award,'Not Assigned') Award
                              from   $TeamsTable       t,
                                     $TeamMembersTable m
                              where  t.TeamID        = m.TeamID
                              and    t.EventID       = $EventID
                              and    m.ParticipantID = $ParticipantID")
                 or die ("Unable to determine team membership:" . sqlError());

      $row = $results->fetch(PDO::FETCH_ASSOC);
      $TeamID     = $row['TeamID'];
      $Solo_Award = $row['Award'];

      $results = $db->query("Select  IFNULL(Award,'Not Assigned') Award
                              from    $RegistrationTable
                              where   EventID       = $EventID
                              and     ParticipantID = $TeamID
                           ")
                 or die ("Unable to determine individual Award:" . sqlError());
      $row = $results->fetch(PDO::FETCH_ASSOC);
      $Award = $row['Award'];

      if ($IndividualAwards)
      {
         $Award = "Team: ".$Award.", Individual: ".$Solo_Award;

      }
   }
   else
   {
      $results = $db->query("Select  IFNULL(Award,'Not Assigned') Award
                              from    $RegistrationTable
                              where   EventID       = $EventID
                              and     ParticipantID = $ParticipantID
                           ")
                 or die ("Unable to determine individual Award:" . sqlError());
      $row = $results->fetch(PDO::FETCH_ASSOC);
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
   global $db;

   $result = $db->query("select   ChurchName
                          from     $ChurchesTable
                          where    ChurchID      = $ChurchID
                         ")
             or die ("Unable to get ChurchID:" . sqlError());

   $row = $result->fetch(PDO::FETCH_ASSOC);
   return $row['ChurchName'];
}
//-----------------------------------------------------------------------------
// Return the name of a event given its numeric ID
//-----------------------------------------------------------------------------
function EventName($EventID)
{
   global $EventsTable;
   global $db;

   $result = $db->query("select   EventName
                          from     $EventsTable
                          where    EventID      = $EventID
                         ")
             or die ("Unable to get Event Name:" . sqlError());

   $row = $result->fetch(PDO::FETCH_ASSOC);
   return $row['EventName'];
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
   global $db;

   $cntResult = $db->query("select distinct count(*) as count
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
                or die ("Unable to determine team event count:" . sqlError());
   $cntRow = $cntResult->fetch(PDO::FETCH_ASSOC);
   $EventsCount['Team'] = $cntRow['count'];

   $cntResult = $db->query("select count(*) as count
                             from   $RegistrationTable r,
                                    $EventsTable       e
                             where  ParticipantID     = $ParticipantID
                             and    r.EventID         = e.EventID
                             and    e.TeamEvent       = 'N'
                            ")
                or die ("Unable to determine solo event count:" . sqlError());
   $cntRow = $cntResult->fetch(PDO::FETCH_ASSOC);
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
   global $db;
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
   $results = $db->query("select sum(ItemCount) count
                           from   $ExtraOrdersTable
                           where  ChurchID   = '$ChurchID'
                           and    ItemType   = 'AdultMeal'
                        ")
            or die ("Unable to get Extra Adult Meal Ticket Count:" . sqlError());
   $row = $results->fetch(PDO::FETCH_ASSOC);
   $ExtraAdultMealCount = isset($row['count']) ? $row['count'] : 0;
   //-----------------------------------------------------------------------
   // Get the number of extra Child-meal meal tickets
   //-----------------------------------------------------------------------
   $results = $db->query("select sum(ItemCount) count
                           from   $ExtraOrdersTable
                           where  ChurchID   = '$ChurchID'
                           and    ItemType   = 'ChildMeal'
                        ")
            or die ("Unable to get Extra Child Meal Ticket Count:" . sqlError());
   $row = $results->fetch(PDO::FETCH_ASSOC);
   $ExtraChildMealCount = isset($row['count']) ? $row['count'] : 0;
   //-----------------------------------------------------------------------
   // Get the number of extra t-shirts ordered
   //-----------------------------------------------------------------------
   $results = $db->query("select sum(ItemCount) count
                           from   $ExtraOrdersTable
                           where  ChurchID   = '$ChurchID'
                           and    ItemType in ('YM','YL','S','M','LG','XL','XX')
                        ")
            or die ("Unable to get Extra T-Shirt Count:" . sqlError());
   $row = $results->fetch(PDO::FETCH_ASSOC);
   $ExtraShirtCount = isset($row['count']) ? $row['count'] : 0;

   //-----------------------------------------------------------------------
   // Check with accounting to see what monies have been received
   //-----------------------------------------------------------------------
   $results = $db->query("select sum(Amount) MoneyInOut
                           from   $MoneyTable
                           where  ChurchID   = $ChurchID
                        ")
            or die ("Unable to get monies in and out:" . sqlError());
   $row = $results->fetch(PDO::FETCH_ASSOC);
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
function getRoomList($fullName='')
{
   static $roomList = array();
   global $RoomsTable;
   global $db;

   if (count($roomList) == 0)
   {
      if ($fullName == 'fullnames')
      {
         $result     = $db->query("select   RoomID,
                                             RoomName
                                    from     $RoomsTable
                                    order by RoomName
                                   ")
                     or die ("Unable to get room information: ".sqlError());
      }
      else
      {
         $result     = $db->query("select   RoomID,
                                             IF (RoomName REGEXP '-[a-z]$',
                                                SUBSTR(RoomName,1,LENGTH(RoomName)-2),
                                                RoomName)
                                             as RoomName
                                    from     $RoomsTable
                                    order by RoomName
                                   ")
                     or die ("Unable to get room information: ".sqlError());
      }
      //$roomList[0] = 'Unassigned';
      while ($row = $result->fetch(PDO::FETCH_ASSOC))
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
   global $db;

   $result = $db->query("select   IF (RoomName REGEXP '-[a-z]$',
                                   SUBSTR(RoomName,1,LENGTH(RoomName)-2),
                                          RoomName)
                                       as RoomName
                          from     $RoomsTable
                          where    RoomID      = $RoomID
                         ")
             or die ("Unable to get Room namme for RoomID $RoomID:" . sqlError());

   $row = $result->fetch(PDO::FETCH_ASSOC);
   return $row['RoomName'];
}
//-----------------------------------------------------------------------------
// Get Schedule Start Time
//-----------------------------------------------------------------------------
function getStartTime($SchedID)
{
   global $EventScheduleTable;
   global $db;

   $result = $db->query("select   StartTime
                          from     $EventScheduleTable
                          where    SchedID      = $SchedID
                         ")
             or die ("Unable to get Start Time for SchedID $SchedID:" . sqlError());

   $row = $result->fetch(PDO::FETCH_ASSOC);
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
   global $db;

   if (ScheduleEventTimeAvailable($EventID,$StartTime,$RoomID))
   {

      $result = $db->query("select   Duration
                             from     $EventsTable
                             where    EventID      = $EventID
                            ")
                or die ("Unable to get Duration for EventID $EventID:" . sqlError());

      $row = $result->fetch(PDO::FETCH_ASSOC);
      $EndTime = AddTime($StartTime,$row['Duration']);

      $db->query("insert into $EventScheduleTable
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
      or die ("Unable to write to EventSchedule: ".sqlError());
      WriteToLog("Added event $EventID to schedule at $StartTime in room $RoomID");
      return TRUE;
   }
   else
   {
      return FALSE;
   }
}
//-----------------------------------------------------------------------------
// Schedule an event - Returns true for success and false for failure
// StartYime = DHHMM  where Sunday is 1 and Saturday is 7
//-----------------------------------------------------------------------------
function ScheduleEventUpd($SchedID,$EventID,$StartTime,$RoomID)   //Returns Success or Failure
{
   global $RoomsTable,
          $EventsTable,
          $EventScheduleTable,
          $JudgeAssignmentsTable;
   global $db;

   //print "SchedID: [$SchedID], EventID: [$EventID], StartTime: [$StartTime],RoomID: [$RoomID]";
   if (ScheduleEventTimeAvailable($EventID,$StartTime,$RoomID))
   {

      // Pickup the current values in the schedule so we can see what changed

      $result = $db->query("select   RoomID,
                                     StartTime
                            from     $EventScheduleTable
                            where    SchedID      = $SchedID
                            ")
                or die ("Unable to get Duration for EventID $EventID:" . sqlError());

      $row = $result->fetch(PDO::FETCH_ASSOC);
      $origStartTime = $row['StartTime'];
      $origRoomID    = $row['RoomID'];

      // Build the appropriate updates

      $updated=0; // Assume that they did not update anything so we don't do an update unless we have to

      // Did we update the StartTime?
      if ($origStartTime != $StartTime)
      {
      // Calculate the EndTime from the startTime plus the defined Event duration
         $result = $db->query("select   Duration
                                from     $EventsTable
                                where    EventID      = $EventID
                               ")
                   or die ("Unable to get Duration for EventID $EventID:" . sqlError());

         $row = $result->fetch(PDO::FETCH_ASSOC);
         $EndTime = AddTime($StartTime,$row['Duration']);

         $setStart = " StartTime = '$StartTime',";
         $setEnd   = " EndTime   = '$EndTime'";
         $updated  = 1;
      }
      else
         $setStart = '';

      // Did we update the RoomID?
      if ($origRoomID != $RoomID)
      {
         $setRoomID = " RoomID    = $RoomID";
         $updated   = 1;
      }
      else
         $setRoomID = '';

      // Build and execute the update SQL if there was information updated
      if ($updated)
      {
         $sql = "update $EventScheduleTable set ";
         if ($setStart != '')
         {
            $sql .= $setStart;
            $sql .= $setEnd;
            if ($setRoomID != '')
               $sql .=',';
         }
         if ($setRoomID != '')
         {
            $sql .= $setRoomID;
//            print "<pre>update  $JudgeAssignmentsTable
//                        set    RoomID  = $RoomID
//                        where  SchedID = '$SchedID'
//                       </pre>";

            $db->query("update  $JudgeAssignmentsTable
                        set    RoomID  = $RoomID
                        where  SchedID = '$SchedID'
                       ")
            or die ("Unable to update Judge Room Assignment: ".sqlError());
         }

         $sql .= " where  SchedID = '$SchedID'";
//         print "<pre>$sql</pre>";
         $db->query($sql)
         or die ("Unable to update EventSchedule: ".sqlError());
      }
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
   global $db;

   $result     = $db->query("select   RoomName,
                                       StartTime
                                 from  $EventScheduleTable e,
                                       $RoomsTable         r
                                 where e.EventID = $EventID
                                 and   r.RoomID  = e.RoomID
                                 order by RoomName
                                ")
                  or die ("Unable to get room information: ".sqlError());

   while ($row = $result->fetch(PDO::FETCH_ASSOC))
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
   global $db;

   $result = $db->query("select   count(*) Count
                          from     $EventScheduleTable
                          where    EventID      = $EventID
                          and      StartTime    = $StartTime
                          and      RoomID       = $RoomID
                         ")
             or die ("Unable to get Event Count for EventID $EventID:" . sqlError());

   $row = $result->fetch(PDO::FETCH_ASSOC);
   $Count = $row['Count'];

   if ($Count == 0)
   {
      return FALSE;
   }
   else
   {
      $db->query("delete from     $EventScheduleTable
                          where    EventID      = $EventID
                          and      StartTime    = $StartTime
                          and      RoomID       = $RoomID
                         ")
             or die ("Unable to remove event from schedule:" . sqlError());
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
   global $db;

   $result = $db->query("select   Duration
                          from     $EventsTable
                          where    EventID      = $EventID
                         ")
             or die ("Unable to get Duration for EventID $EventID:" . sqlError());

   $row = $result->fetch(PDO::FETCH_ASSOC);
   $EndTime = AddTime($StartTime,$row['Duration']);

//   print   "<pre>select   count(*) Count
//                          from     $EventScheduleTable s,
//                                   $RoomsTable         r
//                          where    s.RoomID         = $RoomID
//                          and      r.RoomID         = $RoomID
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
   $result = $db->query("select   count(*) Count
                          from     $EventScheduleTable s,
                                   $RoomsTable         r
                          where    s.RoomID         = $RoomID
                          and      r.RoomID         = $RoomID
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
                          or die ("Unable to get schedule for the Event:" . sqlError());

   $row = $result->fetch(PDO::FETCH_ASSOC);
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
   global $db;

// First get the start and stop time for the event being verified
   $result = $db->query("select   StartTime,
                                   EndTime
                          from     $EventScheduleTable
                          where    SchedID = $checkSchedID
                         ")
             or die ("Unable to get schedule information for participant $ParticipantID:" . sqlError());
   $row = $result->fetch(PDO::FETCH_ASSOC);
   $checkStartTime = $row['StartTime'];
   $checkEndTime   = $row['EndTime'];

// Now get the list of the solo events that the person is signed up for
   $result = $db->query("select   StartTime,
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
             or die ("Unable to get solo schedule for participant $ParticipantID:" . sqlError());

// And check each even against the one they are wanting to sign up for. If there is overlap
// there is conflict

   $conflict=FALSE;
   while (! $conflict and $row = $result->fetch(PDO::FETCH_ASSOC))
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
      $result = $db->query("select s.StartTime,
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
      or die ("Unable to get team schedule for participant $ParticipantID:" . sqlError());

//      print "CheckStartTime: $checkStartTime, CheckEndTime: $checkEndTime<br />";
      while (! $conflict and $row = $result->fetch(PDO::FETCH_ASSOC))
      {
         $StartTime = $row['StartTime'];
         $EndTime   = $row['EndTime'];
         $EventName = $row['EventName'];
//         print "EventName: $EventName, StartTime: $StartTime, EndTime: $EndTime<br />";
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
// Check for a particular privilege. This and the set function are the
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
//  Because of the stupid ways we are managing multiple events in the same room we have to use
//  this hack. It looks for all rooms that have the same name (minus the last two characters)  and
//  counts the number of participants assigned. Then returns that count to the caller.
//-----------------------------------------------------------------------------
function slotsFilledInRoom($RoomName,$StartTime)
{
    global $RoomsTable,
           $RegistrationTable,
           $EventScheduleTable;
   global $db;

    $result = $db->query("select count(*) as Count
                           from   $EventScheduleTable s,
                                  $RoomsTable         r,
                                  $RegistrationTable  p
                           Where  r.RoomName like '$RoomName%'
                           and    s.StartTime = '$StartTime'
                           and    s.SchedID   = p.SchedID
                           and    s.RoomID    = r.RoomID")
              or die ("Unable to get slot usage for Room:$RoomName at start time $StartTime:" . sqlError());
   $row = $result->fetch(PDO::FETCH_ASSOC);
   return ($row['Count']);
}
//-----------------------------------------------------------------------------
//  Dump all system variables in a table for debugging
//-----------------------------------------------------------------------------
function dumpSysVars()
{
   if (isset($_POST))
   {
      ?>
      <table class='registrationTable' border=1>
         <tr>
            <th colspan="2" style='text-align: center'><b>POST</b></th>
         </tr>
         <tr>
            <td>Variable</td>
            <td>Value</td>
         </tr>
         <?php
            foreach ($_POST as $varName => $varValue)
            {
               print "<tr>\n";
               print "   <td>$varName</td>\n";
               print "   <td>$varValue&nbsp;</td>\n";
               print "</tr>\n";
            }
         ?>
      </table>
      <br />
      <?php
   }

   if (isset($_REQUEST))
   {
   ?>
      <table class='registrationTable' border=1>
         <tr>
            <th colspan="2" style='text-align: center'><b>REQUEST</b></th>
         </tr>
         <tr>
            <td>Variable</td>
            <td>Value</td>
         </tr>
         <?php
            foreach ($_REQUEST as $varName => $varValue)
            {
               print "<tr>\n";
               print "   <td>$varName</td>\n";
               print "   <td>$varValue&nbsp;</td>\n";
               print "</tr>\n";
            }
         ?>
      </table>
      <br />
      <?php
   }

   if (isset($_SESSION))
   {
   ?>
      <table class='registrationTable' border=1>
         <tr>
            <th colspan="2" style='text-align: center'><b>SESSION</b></th>
         </tr>
         <tr>
            <td>Variable</td>
            <td>Value</td>
         </tr>
         <?php
            foreach ($_SESSION as $varName => $varValue)
            {
               print "<tr>\n";
               print "   <td>$varName</td>\n";
               print "   <td>$varValue&nbsp;</td>\n";
               print "</tr>\n";
            }
         ?>
      </table>
      <br />
      <?php
   }

   if (isset($_SERVER))
   {
   ?>
      <table class='registrationTable'>
         <tr>
            <td colspan="2" style='text-align: center'><b>SERVER</b></td>
         </tr>
         <tr>
            <td>Variable</td>
            <td>Value</td>
         </tr>
         <?php
            foreach ($_SERVER as $varName => $varValue)
            {
               print "<tr>\n";
               print "   <td>$varName</td>\n";
               print "   <td>$varValue&nbsp;</td>\n";
               print "</tr>\n";
            }
         ?>
      </table>
      <br />
      <?php
   }
}
//-----------------------------------------------------------------------------
// Verify valid password format
// >= 7 Characters
// <= 32 Characters
// Mixed Case
// At least one number
// At least one special Character
// No spaces
//-----------------------------------------------------------------------------
function verifyPasswordFormat($password)
{
   if (strlen($password) < 7)
      return False;
//   print "Pass min len<br />\n";
   if (strlen($password) > 32)
      return False;
//   print "Pass max len<br />\n";
   if(!preg_match("/[a-z]/",$password))
      return False;
//   print "Pass lower leters<br />\n";
   if(!preg_match("/[A-Z]/",$password))
      return False;
//   print "Pass upper letters<br />\n";
   if(!preg_match("/[0-9]/",$password))
      return False;
//   print "Pass number<br />\n";
   if(!preg_match("/[`~!@#\$%^&*\(\)_+?\"';:,.<>\/\\\\[\]\{\}\|\-\=]/",$password)) // 123abc!@$XYZ
      return False;
//   print "Pass special chars<br />\n";
   if(preg_match("/\s+/",$password))
      return False;
//   print "Pass no spaces<br />\n";

   return true;
}
//-----------------------------------------------------------------------------
// Generate a valid password
//-----------------------------------------------------------------------------
// Generates a strong password of N length containing at least one lower case letter,
// one uppercase letter, one digit, and one special character. The remaining characters
// in the password are chosen at random from those four sets.
//
// The available characters in each set are user friendly - there are no ambiguous
// characters such as i, l, 1, o, 0, etc. This, coupled with the $add_dashes option,
// makes it much easier for users to manually type or speak their passwords.
//
// Note: the $add_dashes option will increase the length of the password by
// floor(sqrt(N)) characters.
function generatePassword($length = 9, $add_dashes = false, $available_sets = 'luds')
{
   $sets = array();
   if(strpos($available_sets, 'l') !== false)
      $sets[] = 'abcdefghjkmnpqrstuvwxyz';
   if(strpos($available_sets, 'u') !== false)
      $sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
   if(strpos($available_sets, 'd') !== false)
      $sets[] = '23456789';
   if(strpos($available_sets, 's') !== false)
      $sets[] = '!@#$%&*?';
   $all = '';
   $password = '';
   foreach($sets as $set)
   {
      $password .= $set[array_rand(str_split($set))];
      $all .= $set;
   }
   $all = str_split($all);
   for($i = 0; $i < $length - count($sets); $i++)
      $password .= $all[array_rand($all)];

   $password = str_shuffle($password);
   if(!$add_dashes)
      return $password;

   $dash_len = floor(sqrt($length));
   $dash_str = '';
   while(strlen($password) > $dash_len)
   {
      $dash_str .= substr($password, 0, $dash_len) . '-';
      $password = substr($password, $dash_len);
   }
   $dash_str .= $password;
   return $dash_str;
}
?>
