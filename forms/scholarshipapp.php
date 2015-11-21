<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
<?php
$emailToWho = 'cepym1@msn.com';
// $emailToWho = 'the_mama@cox.net';
// $emailToWho = 'paullem@cox.net';

$referring_page = $_SERVER ['PHP_SELF'];

$ip         = isset ( $_POST ['ip'] )         ? $_POST ['ip']         : '';
$httpref    = isset ( $_POST ['httpref'] )    ? $_POST ['httpref']    : '';
$httpagent  = isset ( $_POST ['httpagent'] )  ? $_POST ['httpagent']  : '';
$subject    = isset ( $_POST ['subject'] )    ? $_POST ['subject']    : '';
$appname    = isset ( $_POST ['appname'] )    ? $_POST ['appname']    : '';
$appphone   = isset ( $_POST ['appphone'] )   ? $_POST ['appphone']   : '';
$appaddr    = isset ( $_POST ['appaddr'] )    ? $_POST ['appaddr']    : '';
$appcity    = isset ( $_POST ['appcity'] )    ? $_POST ['appcity']    : '';
$appstate   = isset ( $_POST ['appstate'] )   ? $_POST ['appstate']   : '';
$appzip     = isset ( $_POST ['appzip'] )     ? $_POST ['appzip']     : '';
$appemail   = isset ( $_POST ['appemail'] )   ? $_POST ['appemail']   : '';
$apphs      = isset ( $_POST ['apphs'] )      ? $_POST ['apphs']      : '';
$apphsc     = isset ( $_POST ['apphsc'] )     ? $_POST ['apphsc']     : '';
$apphsph    = isset ( $_POST ['apphsph'] )    ? $_POST ['apphsph']    : '';
$appgpa     = isset ( $_POST ['appgpa'] )     ? $_POST ['appgpa']     : '';
$appact     = isset ( $_POST ['appact'] )     ? $_POST ['appact']     : '';
$appsat     = isset ( $_POST ['appsat'] )     ? $_POST ['appsat']     : '';
$appcong    = isset ( $_POST ['appcong'] )    ? $_POST ['appcong']    : '';
$appcongph  = isset ( $_POST ['appcongph'] )  ? $_POST ['appcongph']  : '';
$sponsname  = isset ( $_POST ['sponsname'] )  ? $_POST ['sponsname']  : '';
$sponsph    = isset ( $_POST ['sponsph'] )    ? $_POST ['sponsph']    : '';
$appattend  = isset ( $_POST ['appattend'] )  ? $_POST ['appattend']  : '';
$appthsyear = isset ( $_POST ['appthsyear'] ) ? $_POST ['appthsyear'] : '';
$appevctr   = isset ( $_POST ['appevctr'] )   ? $_POST ['appevctr']   : '';
$appspcl    = isset ( $_POST ['appspcl'] )    ? $_POST ['appspcl']    : '';
$refname1   = isset ( $_POST ['refname1'] )   ? $_POST ['refname1']   : '';
$refphone1  = isset ( $_POST ['refphone1'] )  ? $_POST ['refphone1']  : '';
$refemail1  = isset ( $_POST ['refemail1'] )  ? $_POST ['refemail1']  : '';
$refname2   = isset ( $_POST ['refname2'] )   ? $_POST ['refname2']   : '';
$refphone2  = isset ( $_POST ['refphone2'] )  ? $_POST ['refphone2']  : '';
$refemail2  = isset ( $_POST ['refemail2'] )  ? $_POST ['refemail2']  : '';
$appspneed  = isset ( $_POST ['appspneed'] )  ? $_POST ['appspneed']  : '';
$appaacu    = isset ( $_POST ['appaacu'] )    ? $_POST ['appaacu']    : '';
$appaacuap  = isset ( $_POST ['appaacuap'] )  ? $_POST ['appaacuap']  : '';
$appalcu    = isset ( $_POST ['appalcu'] )    ? $_POST ['appalcu']    : '';
$appalcuap  = isset ( $_POST ['appalcuap'] )  ? $_POST ['appalcuap']  : '';
$appaocu    = isset ( $_POST ['appaocu'] )    ? $_POST ['appaocu']    : '';
$appaocuap  = isset ( $_POST ['appaocuap'] )  ? $_POST ['appaocuap']  : '';
$appahu     = isset ( $_POST ['appahu'] )     ? $_POST ['appahu']     : '';
$appahuap   = isset ( $_POST ['appahuap'] )   ? $_POST ['appahuap']   : '';
$appacc     = isset ( $_POST ['appacc'] )     ? $_POST ['appacc']     : '';
$appaccap   = isset ( $_POST ['appaccap'] )   ? $_POST ['appaccap']   : '';
$appiacu    = isset ( $_POST ['appiacu'] )    ? $_POST ['appiacu']    : '';
$appilcu    = isset ( $_POST ['appilcu'] )    ? $_POST ['appilcu']    : '';
$appiocu    = isset ( $_POST ['appiocu'] )    ? $_POST ['appiocu']    : '';
$appihu     = isset ( $_POST ['appihu'] )     ? $_POST ['appihu']     : '';
$appicc     = isset ( $_POST ['appicc'] )     ? $_POST ['appicc']     : '';
$appmajor   = isset ( $_POST ['appmajor'] )   ? $_POST ['appmajor']   : '';
$Submit     = isset ( $_POST ['Submit'] )     ? $_POST ['Submit']     : '';

if ($Submit != '')
{
   $todayis = date ( "l, F j, Y, g:i a [T]" );

   $appbstsch = stripslashes ( $appspcl );
   $appbstsch = htmlspecialchars ( $appspcl, ENT_QUOTES );
   $appbstsch = str_replace ( "\r\n", "\n", $appspcl );
   $appbstsch = str_replace ( "\n", "<br>", $appspcl );

   $message = "<html>\n";
   $message .= "   <head>\n";
   $message .= "      <meta content=\"text/html; charset=ISO-8859-1\" http-equiv=\"content-type\">\n";
   $message .= "      <title>Scholarship Application</title>\n";
   $message .= "   </head>\n";
   $message .= "   <body>\n";
   $message .= "      <h1 align=center>\n";
   $message .= "         Scholarship Application\n";
   $message .= "      </h1>\n";
   $message .= "      <b>Personal Data<br></b>\n";
   $message .= "      <hr style=\"width: 100%; height: 2px; font-weight: bold;\">\n";
   $message .= "      <table border=\"0\" cellpadding=\"2\" cellspacing=\"2\">\n";
   $message .= "         <tr>\n";
   $message .= "            <td width=\"25px\">&nbsp;</td>\n";
   $message .= "            <td width=\"250px\"><b>Name:</b></td>\n";
   $message .= "            <td>$appname</td>\n";
   $message .= "         </tr>\n";
   $message .= "         <tr>\n";
   $message .= "            <td>&nbsp;</td>\n";
   $message .= "            <td><b>Address:</b></td>\n";
   $message .= "            <td>$appaddr</td>\n";
   $message .= "         </tr>\n";
   $message .= "         <tr>\n";
   $message .= "            <td>&nbsp;</td>\n";
   $message .= "            <td>&nbsp;</td>\n";
   $message .= "            <td>$appcity, $appstate $appzip</td>\n";
   $message .= "         </tr>\n";
   $message .= "         <tr>\n";
   $message .= "            <td>&nbsp;</td>\n";
   $message .= "            <td><b>Email:</b></td>\n";
   $message .= "            <td>$appemail</td>\n";
   $message .= "         </tr>\n";
   $message .= "      </table>\n";
   $message .= "      <br>\n";
   $message .= "      <br>\n";
   $message .= "      <b>School Information</b><br>\n";
   $message .= "      <hr style=\"width: 100%; height: 2px; font-weight: bold;\">\n";
   $message .= "      <table border=\"0\" cellpadding=\"2\" cellspacing=\"2\">\n";
   $message .= "         <tr>\n";
   $message .= "            <td width=\"25px\">&nbsp;</td>\n";
   $message .= "            <td width=\"250px\"><b>High School</b></td>\n";
   $message .= "            <td>$apphs</td>\n";
   $message .= "         </tr>\n";
   $message .= "         <tr>\n";
   $message .= "            <td>&nbsp;</td>\n";
   $message .= "            <td><b>Classification</b></td>\n";
   $message .= "            <td>$apphsc</td>\n";
   $message .= "         </tr>\n";
   $message .= "         <tr>\n";
   $message .= "            <td>&nbsp;</td>\n";
   $message .= "            <td><b>GPA</b></td>\n";
   $message .= "            <td>$appgpa</td>\n";
   $message .= "         </tr>\n";
   $message .= "         <tr>\n";
   $message .= "            <td>&nbsp;</td>\n";
   $message .= "            <td><b>ACT</b></td>\n";
   $message .= "            <td>$appact</td>\n";
   $message .= "         </tr>\n";
   $message .= "         <tr>\n";
   $message .= "            <td>&nbsp;</td>\n";
   $message .= "            <td><b>SAT</b></td>\n";
   $message .= "            <td>$appsat</td>\n";
   $message .= "         </tr>\n";
   $message .= "      </table>\n";
   $message .= "      <br>\n";
   $message .= "      <br>\n";
   $message .= "      <b>Church Information</b><br>\n";
   $message .= "      <hr style=\"width: 100%; height: 2px; font-weight: bold;\">\n";
   $message .= "      <br>\n";
   $message .= "      <table border=\"0\" cellpadding=\"2\" cellspacing=\"2\">\n";
   $message .= "         <tr>\n";
   $message .= "            <td width=\"25px\">&nbsp;</td>\n";
   $message .= "            <td width=\"250px\"><b>Home Congregation</b></td>\n";
   $message .= "            <td>$appcong</td>\n";
   $message .= "         </tr>\n";
   $message .= "         <tr>\n";
   $message .= "            <td>&nbsp;</td>\n";
   $message .= "            <td><b>Phone</b></td>\n";
   $message .= "            <td>$appcongph</td>\n";
   $message .= "         </tr>\n";
   $message .= "      </table>\n";
   $message .= "      <br>\n";
   $message .= "      <br>\n";
   $message .= "      <b>LTC Information</b><br>\n";
   $message .= "      <hr style=\"width: 100%; height: 2px; font-weight: bold;\">\n";
   $message .= "      <br>\n";
   $message .= "      <table border=\"0\" cellpadding=\"2\" cellspacing=\"2\">\n";
   $message .= "         <tr>\n";
   $message .= "            <td width=\"25px\">&nbsp;</td>\n";
   $message .= "            <td width=\"250px\"><b>Church LTC Sponsor</b></td>\n";
   $message .= "            <td>$sponsname</td>\n";
   $message .= "         </tr>\n";
   $message .= "         <tr>\n";
   $message .= "            <td>&nbsp;</td>\n";
   $message .= "            <td><b>Phone</b></td>\n";
   $message .= "            <td>$sponsph</td>\n";
   $message .= "         </tr>\n";
   $message .= "         <tr>\n";
   $message .= "            <td>&nbsp;</td>\n";
   $message .= "            <td><b>Years at LTC</b></td>\n";
   $message .= "            <td>$appattend</td>\n";
   $message .= "         </tr>\n";
   $message .= "         <tr>\n";
   $message .= "            <td>&nbsp;</td>\n";
   $message .= "            <td><b>Number of Events Entered</b></td>\n";
   $message .= "            <td>$appevctr</td>\n";
   $message .= "         </tr>\n";
   $message .= "      </table>\n";
   $message .= "      <br>\n";
   $message .= "      <b><u>Special Circumstances</u></b><br>\n";
   $message .= "      $appspcl<br>\n";
   $message .= "      <br>\n";
   $message .= "      <br>\n";
   $message .= "      <br>\n";
   $message .= "      <b>References</b><br>\n";
   $message .= "      <hr style=\"width: 100%; height: 2px; font-weight: bold;\">\n";
   $message .= "      <table border=0>\n";
   $message .= "          <tr><td vidth=25px><td>\n";
   $message .= "             <table border=0>\n";
   $message .= "                <tr>\n";
   $message .= "                   <td>1.</td><td>Name:</td><td>$refname1</td>\n";
   $message .= "                </tr>\n";
   $message .= "                <tr>\n";
   $message .= "                   <td>&nbsp;</td><td>Phone:</td><td>$refphone1</td>\n";
   $message .= "                </tr>\n";
   $message .= "                <tr>\n";
   $message .= "                   <td>&nbsp;</td><td>Email:</td><td>$refemail1</td>\n";
   $message .= "                </tr>\n";
   $message .= "                <tr>\n";
   $message .= "                   <td>2.</td><td>Name:</td><td>$refname2</td>\n";
   $message .= "                </tr>\n";
   $message .= "                <tr>\n";
   $message .= "                   <td>&nbsp;</td><td>Phone:</td><td>$refphone2</td>\n";
   $message .= "                </tr>\n";
   $message .= "                <tr>\n";
   $message .= "                   <td>&nbsp;</td><td>Email:</td><td>$refemail2</td>\n";
   $message .= "                </tr>\n";
   $message .= "             </table>\n";
   $message .= "          </td></td></tr>\n";
   $message .= "      </table>\n";
   $message .= "      <br>\n";
   $message .= "      <br>\n";
   $message .= "      <br>\n";
   $message .= "      <b>College Preference</b><br>\n";
   $message .= "      <hr style=\"width: 100%; height: 2px; font-weight: bold;\">\n";
   $message .= "      <br>\n";
   $message .= "      <b><u>College Rank (Junior):</u></b><br>\n";
   $message .= "      <table border=\"0\" cellpadding=\"2\" cellspacing=\"2\">\n";
   $message .= "         <tr>\n";
   $message .= "            <td width=\"25px\">&nbsp;</td>\n";
   $message .= "            <td width=\"250px\">Abilene Christian University</td>\n";
   $message .= "            <td>$appaacu</td>\n";
   $message .= "         </tr>\n";
   $message .= "         <tr>\n";
   $message .= "            <td>&nbsp;</td>\n";
   $message .= "            <td>Lubbock Christian University</td>\n";
   $message .= "            <td>$appalcu</td>\n";
   $message .= "         </tr>\n";
   $message .= "         <tr>\n";
   $message .= "            <td>&nbsp;</td>\n";
   $message .= "            <td>Oklahoma Christian University</td>\n";
   $message .= "            <td>$appaocu</td>\n";
   $message .= "         </tr>\n";
   $message .= "         <tr>\n";
   $message .= "            <td>&nbsp;</td>\n";
   $message .= "            <td>Harding University</td>\n";
   $message .= "            <td>$appahu</td>\n";
   $message .= "         </tr>\n";
   $message .= "      </table>\n";
   $message .= "      <br>\n";
   $message .= "      <br>\n";
   $message .= "      <b><u>College Preference (Senior):</u></b><br>\n";
   $message .= "      <br>\n";
   $message .= "      <table border=\"0\" cellpadding=\"2\" cellspacing=\"2\">\n";
   $message .= "         <tr>\n";
   $message .= "            <td width=\"25px\">&nbsp;</td>\n";
   $message .= "            <td width=\"250px\">Abilene Christian University</td>\n";
   $message .= "            <td>$appiacu</td>\n";
   $message .= "         </tr>\n";
   $message .= "         <tr>\n";
   $message .= "            <td>&nbsp;</td>\n";
   $message .= "            <td>Lubbock Christian University</td>\n";
   $message .= "            <td>$appilcu</td>\n";
   $message .= "         </tr>\n";
   $message .= "         <tr>\n";
   $message .= "            <td>&nbsp;</td>\n";
   $message .= "            <td>Oklahoma Christian University</td>\n";
   $message .= "            <td>$appiocu</td>\n";
   $message .= "         </tr>\n";
   $message .= "         <tr>\n";
   $message .= "            <td>&nbsp;</td>\n";
   $message .= "            <td>Harding University</td>\n";
   $message .= "            <td>$appihu</td>\n";
   $message .= "         </tr>\n";
   $message .= "      </table>\n";
   $message .= "      <br>\n";
   $message .= "      <b><u>Intended Major:</u></b><br>\n";
   $message .= "      $appmajor<br>\n";
   $message .= "      <br>\n";
   $message .= "      <center><i><small>Form submitted: $todayis</small></i></center><br>\n";
   $message .= "   </body>\n";
   $message .= "</html>\n";
   print "$message";

   if (isset ( $appemail ))
      $emailToWho .= ",$appemail";

   $header = 'MIME-Version: 1.0' . "\r\n";
   $header .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

   mail ( $emailToWho, "LTC Scholarship Application - $appname", $message, $header );
   exit ();
}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <title>Scholarship Application - LTCSW.</title>
      <meta name="description"  content="Form to submit your LTCSW scholarship application online." />
      <meta name="keywords"     content="" />
      <meta name="owner"        content="Board of Directors for the Leadership Training for Christ Southwest Region" />
      <meta name="copyright"    content="2002-2017 - Leadership Training for Christ Southwest Region, Phoenix, Arizona" />
      <meta name="author"       content="LTCSW Board of Directors" />
      <meta name="rating"       content="General" />
      <meta name="language"     content="en-us" />
      <meta name="robots"       content="INDEX,FOLLOW,IMAGEINDEX,NOIMAGECLICK" />
      <meta name="googlebot"    content="noarchive,nosnippet" />
      <meta name="country"      content="United States (usa)" />
      <meta name="state"        content="Arizona" />
      <meta name="geo.region"   content="US-AZ" />
      <meta name="distribution" content="Local" />
      <meta http-equiv="reply-to"         content="" />
      <meta http-equiv="charset"          content="ISO-8859-1" />
      <meta http-equiv="content-type"     content="text/html; charset=iso-8859-1" />
      <meta http-equiv="content-language" content="English" />

      <link rel="shortcut icon" href="favicon.ico" />
      <!-- =========================================================================================== -->
      <!--  Steal the stylesheets directly from our Joomla site so that this for looks like it fits in -->
      <!-- =========================================================================================== -->
      <link rel="stylesheet"
         href="http://ltcsw.org/j3/templates/siteground-j16-46/css/general.css"
         type="text/css" />
      <link rel="stylesheet"
         href="http://ltcsw.org/j3/templates/siteground-j16-46/css/personal.css"
         type="text/css" />
   </head>
   <body class="page_bg">
      <div class="wrapper">
         <header>
            <div class="sitename">
               <!-- <h1><a href="/j3">Leadership Training for Christ Southwest Region</a></h1> -->
            </div>
         </header>
         <div class="nopad" align=center>
            <table class="blog" cellpadding="0" cellspacing="0" style="border: 0">
               <tr>
                  <td>
                     <h2 style="text-align:center" >
                        Online Application for Scholarships<br>
                        (High School Junior and Seniors Only)
                     </h2>
                     <table class="contentpaneopen">
                        <tr>
                           <td valign="top" colspan="2">

                           <form name="scholarshipapp" method="post"
                              action="<?php print $referring_page?>">
                              <fieldset class="input">
                                 <p>
                                    <b><u>Home Information:</u></b><br><br>
                                 </p>
                                 <p>
                                    Name:   <br><input type="text" name="appname"  size="50"><br>
                                    Phone:  <br><input type="text" name="appphone" size="24" maxlength="24"><br>
                                    Address:<br><input type="text" name="appaddr"  size="50" maxlength="50"><br><br>
                                    City:       <input type="text" name="appcity"  size="20">&nbsp;
                                    State:      <input type="text" name="appstate" size="2"  maxlength="2">
                                    Zip:        <input type="text" name="appzip"   size="5"  maxlength="5">
                                 </p>
                                 <p>
                                    E-mail<i> (optional)</i>:<br> <input type="text" name="appemail" size="50" maxlength="63">
                                 </p>
                                 <p>
                                    <br><b><u>School Information:</u></b><br><br>
                                 </p>
                                 <p>
                                    High School Currently Attending:<br> <input type="text" name="apphs" size="50"><br>
                                    Your Classification:            <br>
                                    <input type="radio" name="apphsc" value="Senior">Senior
                                    <input type="radio" name="apphsc" value="Junior">Junior
                                    <input type="radio" name="apphsc" value="Sophomore">Sophomore
                                    <input type="radio" name="apphsc" value="Frehsman">Freshman
                                 </p>
                                 <p>
                                    GPA: &nbsp;<input type="text" name="appgpa" size="6" maxlength="6"> &nbsp; &nbsp;
                                    SAT: &nbsp;<input type="text" name="appsat" size="6" maxlength="6"> &nbsp; &nbsp;
                                    ACT: &nbsp;<input type="text" name="appact" size="6" maxlength="6">
                                 </p>
                                 <p>
                                    <br><b><u>Church Information:</u><br></b><br>
                                    Home Congregation:<br><input type="text" name="appcong"   size="50"><br>
                                    Phone:            <br><input type="text" name="appcongph" size="24" maxlength="24">
                                 </p>
                                 <p>
                                    <br><b><u>Leadership Training for Christ Information:</u></b><br><br>
                                 </p>
                                 <p>
                                    Your Church LTC Sponsor:<br><input type="text" name="sponsname" size="50"><br>
                                    LTC Sponsor's Phone:    <br><input type="text" name="sponsph"   size="24" maxlength="24"><br><br>
                                    How many years have you attended LTC Conventions?
                                                            <br><input type="text" name="appattend" size="2"><br><br>
                                    How many events are you entered in this year (pre-convention and convention combined)?
                                                                <input type="text" name="appevctr" size="5"><br><br>
                                 </p>
                                 <p>
                                    <b><u>Essay</u></b><br><br>
                                    Write a one page essay
                                    using a word processor describing how LTC has
                                    helped you in your school, church and community.
                                    Include specific leadership activities you have
                                    been involved in that have helped you to develop
                                    additional leadership qualities. Explain how you
                                    plan to use these leadership qualities in the
                                    future and in college.
                                 </p>
                                 <p>
                                    <b>Please email the document to the
                                    Scholarship Coordinator at:<br>
                                    </b><br>
                                    <a href=mailto:<?php print $emailToWho?>><?php print $emailToWho?></a>
                                 </p>
                                 <p>
                                    <br>If there are special circumstances that
                                    would help the scholarship committee please
                                    include those here: <br><br>
                                    <textarea rows="7" name="appspcl" cols="50"></textarea>
                                 </p>

                                 <p>
                                    <br><b><u>References</u></b><br><br>
                                    Please list two
                                    individuals and their phone numbers and/or
                                    e-mails who would be willing to give you a
                                    reference for this scholarship. Please give one
                                    from church and one from school.<br><br>
                                 </p>
                                 <table border=0>
                                    <tr>
                                       <td>1.</td>
                                       <td>Name:</td>
                                       <td><input type="text" name="refname1" size="24"></td>
                                    </tr>
                                    <tr>
                                       <td>&nbsp;</td>
                                       <td>Phone:</td>
                                       <td><input type="text" name="refphone1" size="24" maxlength="24"></td>
                                    </tr>
                                    <tr>
                                       <td>&nbsp;</td>
                                       <td>Email:</td>
                                       <td><input type="text" name="refemail1" size="24"></td>
                                    </tr>
                                    <tr>
                                       <td>2.</td>
                                       <td>Name:</td>
                                       <td><input type="text" name="refname2" size="24"></td>
                                    </tr>
                                    <tr>
                                       <td>&nbsp;</td>
                                       <td>Phone:</td>
                                       <td><input type="text" name="refphone2" size="24" maxlength="24"></td>
                                    </tr>
                                    <tr>
                                       <td>&nbsp;</td>
                                       <td>Email:</td>
                                       <td><input type="text" name="refemail2"
                                          size="24"></td>
                                    </tr>
                                 </table>
                                 <p>&nbsp;</p>
                                 <p>
                                    <b><u>College Preference</u> (JUNIORS):</b><br><br>
                                    List your 1st, 2nd, and 3rd choices for schools
                                    you would seriously consider attending:<br><br>
                                 </p>

                                 <table border="0" width="100%">
                                    <tr>
                                       <th width="9%" valign="bottom" align="center">Rank</th>
                                       <th width="36%" valign="bottom" align="left">College</th>
                                       <th width="55%" colspan="2" valign="bottom" align="left">
                                          Have you Applied to this School yet?
                                       </th>
                                    </tr>
                                    <tr>
                                       <td width="9%" valign="top" align="center">
                                          <input type="text" name="appaacu" size="1" value="0">
                                       </td>
                                       <td width="36%" valign="top" align="left">
                                          Abilene Christian University
                                       </td>
                                       <td width="17%" valign="top" align="left">
                                          <input type="radio" value="Yes" name="appaacuap">Yes
                                       </td>
                                       <td width="38%" valign="top" align="left">
                                          <input type="radio" value="No" name="appaacuap" checked>No
                                       </td>
                                    </tr>
                                    <tr>
                                       <td width="9%" valign="top" align="center">
                                          <input type="text" name="appalcu" size="1" value="0">
                                       </td>
                                       <td width="36%" valign="top" align="left">
                                          Lubbock Christian University
                                       </td>
                                       <td width="17%" valign="top" align="left">
                                          <input type="radio" value="Yes" name="appalcuap">Yes
                                       </td>
                                       <td width="38%" valign="top" align="left">
                                          <input type="radio" value="No" name="appalcuap" checked>No
                                       </td>
                                    </tr>
                                    <tr>
                                       <td width="9%" valign="top" align="center">
                                          <input type="text" name="appaocu" size="1" value="0">
                                       </td>
                                       <td width="36%" valign="top" align="left">
                                          Oklahoma Christian University
                                       </td>
                                       <td width="17%" valign="top" align="left">
                                          <input type="radio" value="Yes" name="appaocuap">Yes
                                       </td>
                                       <td width="38%" valign="top" align="left">
                                          <input type="radio" value="No" name="appaocuap" checked>No
                                       </td>
                                    </tr>
                                    <tr>
                                       <td width="9%" valign="top" align="center">
                                          <input type="text" name="appahu" size="1" value="0">
                                       </td>
                                       <td width="36%" valign="top" align="left">
                                          Harding University
                                       </td>
                                       <td width="17%" valign="top" align="left">
                                          <input type="radio" value="Yes" name="appahuap">Yes
                                       </td>
                                       <td width="38%" valign="top" align="left">
                                          <input type="radio" value="No" name="appahuap" checked>No
                                       </td>
                                    </tr>
                                 </table>
                                 <p>
                                    <b><u>College Preference</u> (SENIORS):</b><br><br>
                                    Indicate the school you are planning to attend:<br><br>
                                 </p>
                                 <table border="0" width="100%">
                                    <tr>
                                       <th width="35%" valign="bottom" align="left">
                                          College
                                       </th>
                                       <th width="56%" colspan="2" valign="bottom" align="left">
                                          Are you planning to&nbsp;<br>
                                          attend this school?
                                       </th>
                                    </tr>
                                    <tr>
                                       <td width="35%" valign="top" align="left">
                                       Abilene Christian University</td>
                                       <td width="12%" valign="top" align="left">
                                          <input type="radio" value="Yes" name="appiacu">Yes
                                       </td>
                                       <td width="44%" valign="top" align="left">
                                          <input type="radio" value="No" name="appiacu" checked> No
                                       </td>
                                    </tr>
                                    <tr>
                                       <td width="35%" valign="top" align="left">
                                          Lubbock Christian University
                                       </td>
                                       <td width="12%" valign="top" align="left">
                                          <input type="radio" value="Yes" name="appilcu">Yes
                                       </td>
                                       <td width="44%" valign="top" align="left">
                                          <input type="radio" value="No" name="appilcu" checked> No
                                       </td>
                                    </tr>
                                    <tr>
                                       <td width="35%" valign="top" align="left">
                                          Oklahoma Christian University
                                       </td>
                                       <td width="12%" valign="top" align="left">
                                          <input type="radio" value="Yes" name="appiocu">Yes
                                       </td>
                                       <td width="44%" valign="top" align="left">
                                          <input type="radio" value="No" name="appiocu" checked> No
                                       </td>
                                    </tr>
                                    <tr>
                                       <td width="35%" valign="top" align="left">
                                          Harding University
                                       </td>
                                       <td width="12%" valign="top" align="left">
                                          <input type="radio" value="Yes" name="appihu">Yes
                                       </td>
                                       <td width="44%" valign="top" align="left">
                                          <input type="radio" value="No" name="appihu" checked>No
                                       </td>
                                    </tr>
                                 </table>
                                 <p>
                                    <b><i>Senior scholarships are restricted to Seniors
                                    who have applied and been accepted by that
                                    University.</i></b>
                                 </p>
                                 <p>
                                    <br>What is your college major? (if known):<br><br>
                                    <input type="text" name="appmajor" size="50">
                                 </p>
                                 <p><b>Note:</b> Most leadership scholarships
                                    from Christian colleges are not combined with other
                                    leadership scholarships. They are, however, usually
                                    used in combination with other types of scholarship
                                    programs (such as academic scholarship programs).
                                    If you receive an LTC scholarship, consult the
                                    college from which the scholarship is provided for
                                    details of specific scholarship programs.
                                 </p>
                                 <p>&nbsp;</p>
                                 <p>
                                    <input type="submit" value="Submit" name="Submit">
                                 </p>
                              </form>
                              <br>
                           </td>
                        </tr>
                     </table>
                  </div>
               </td>
            </tr>
         </table>
      </div>
   </body>
</html>
