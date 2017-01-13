-- Before running this script perform the following steps:
--   1) create a database
--      mysql -uroot -p 
--      create database <dbName>
--   2) Create a userid to access the db
--      mysql -u root -p
--      grant all on <dbName> to <someID>@localhost identified by '<somePasswd>';
--   3) Change the prefix of all the tables
--      vi:
--      :%s/LTC_/<myorg_>/g
--   4) Run this script
--      mysql -u<someID> -p<somePasswd> -hlocalhost <dbName> < thisfile.sql
--
--   make note of all of the updates above so that you can update the config.php 
--   with your values.
--
-- Table structure for table `LTC_Churches`
--

DROP TABLE IF EXISTS `LTC_Churches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `LTC_Churches` (
  `ChurchID` int(3) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `ChurchName` varchar(255) NOT NULL DEFAULT '',
  `ChurchAddr` varchar(255) NOT NULL DEFAULT '',
  `ChurchCity` varchar(255) NOT NULL DEFAULT '',
  `ChurchState` char(2) NOT NULL DEFAULT '',
  `ChurchZip` varchar(10) NOT NULL DEFAULT '',
  `ChurchPhone` varchar(15) NOT NULL DEFAULT '',
  `CoordName` varchar(255) NOT NULL DEFAULT '',
  `CoordAddr` varchar(255) NOT NULL DEFAULT '',
  `CoordCity` varchar(255) NOT NULL DEFAULT '',
  `CoordState` char(2) NOT NULL DEFAULT '',
  `CoordZip` varchar(10) NOT NULL DEFAULT '',
  `CoordPhone` varchar(15) NOT NULL DEFAULT '',
  `ChurchEmail` varchar(255) NOT NULL DEFAULT '',
  `CoordEmail` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`ChurchID`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1 COMMENT='List of Churches';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `LTC_EventCoord`
--

DROP TABLE IF EXISTS `LTC_EventCoord`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `LTC_EventCoord` (
  `CoordID` int(3) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL DEFAULT '',
  `Address` varchar(255) NOT NULL DEFAULT '',
  `City` varchar(255) NOT NULL DEFAULT '',
  `State` char(2) NOT NULL DEFAULT '',
  `Zip` varchar(10) NOT NULL DEFAULT '',
  `Phone` varchar(15) NOT NULL DEFAULT '',
  `Email` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`CoordID`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `LTC_EventSchedule`
--

DROP TABLE IF EXISTS `LTC_EventSchedule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `LTC_EventSchedule` (
  `SchedID` int(8) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `EventID` int(3) unsigned zerofill NOT NULL DEFAULT '000',
  `StartTime` int(5) unsigned zerofill NOT NULL DEFAULT '00000',
  `EndTime` int(5) unsigned zerofill NOT NULL DEFAULT '00000',
  `RoomID` int(5) unsigned zerofill NOT NULL DEFAULT '00000',
  PRIMARY KEY (`SchedID`),
  KEY `StartTimeIX` (`StartTime`),
  KEY `EndTimIX` (`EndTime`),
  KEY `EventRoomIX` (`EventID`,`RoomID`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `LTC_Events`
--

DROP TABLE IF EXISTS `LTC_Events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `LTC_Events` (
  `EventID` int(3) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `EventName` varchar(255) NOT NULL DEFAULT '',
  `JudgingCatagory` varchar(255) NOT NULL DEFAULT '',
  `TeamEvent` char(1) NOT NULL DEFAULT 'N',
  `ConvEvent` char(1) NOT NULL DEFAULT 'Y',
  `MinGrade` char(3) NOT NULL DEFAULT '3',
  `MaxGrade` char(3) NOT NULL DEFAULT '12',
  `MinSize` int(2) NOT NULL DEFAULT '1',
  `MaxSize` int(2) NOT NULL DEFAULT '1',
  `Sex` char(1) NOT NULL DEFAULT '',
  `JudgesNeeded` int(2) unsigned NOT NULL DEFAULT '0',
  `MaxRooms` int(2) unsigned NOT NULL DEFAULT '1',
  `MaxEventSlots` int(3) unsigned NOT NULL DEFAULT '0',
  `MaxWebSlots` int(3) unsigned NOT NULL DEFAULT '0',
  `Duration` int(4) unsigned NOT NULL DEFAULT '0',
  `JudgeTrained` char(1) NOT NULL DEFAULT '',
  `TeenCoord` char(1) NOT NULL DEFAULT '',
  `EventAttended` char(1) NOT NULL DEFAULT '',
  `IndividualAwards` char(1) NOT NULL DEFAULT 'N',
  `CoordID` int(5) unsigned zerofill DEFAULT NULL,
  PRIMARY KEY (`EventID`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1 COMMENT='List of Events';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `LTC_ExtraOrders`
--

DROP TABLE IF EXISTS `LTC_ExtraOrders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `LTC_ExtraOrders` (
  `ChurchID` int(4) unsigned NOT NULL DEFAULT '0',
  `ItemType` varchar(255) NOT NULL DEFAULT '',
  `ItemCount` int(4) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ChurchID`,`ItemType`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `LTC_JudgeAssignments`
--

DROP TABLE IF EXISTS `LTC_JudgeAssignments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `LTC_JudgeAssignments` (
  `JudgeID` int(4) unsigned NOT NULL DEFAULT '0',
  `JudgeNumber` int(2) unsigned NOT NULL DEFAULT '0',
  `RoomID` int(5) unsigned zerofill NOT NULL DEFAULT '00000',
  `SchedID` int(8) unsigned zerofill NOT NULL DEFAULT '00000000',
  `ChurchID` int(3) unsigned zerofill NOT NULL DEFAULT '000',
  PRIMARY KEY (`JudgeID`,`RoomID`,`SchedID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `LTC_JudgeEvents`
--

DROP TABLE IF EXISTS `LTC_JudgeEvents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `LTC_JudgeEvents` (
  `JudgeID` int(4) unsigned NOT NULL DEFAULT '0',
  `JudgingCatagory` varchar(255) NOT NULL DEFAULT '0',
  KEY `JudgeEvents_X1` (`JudgeID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `LTC_JudgeTimes`
--

DROP TABLE IF EXISTS `LTC_JudgeTimes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `LTC_JudgeTimes` (
  `JudgeID` int(4) unsigned NOT NULL DEFAULT '0',
  `SchedID` int(8) unsigned zerofill NOT NULL DEFAULT '00000000',
  KEY `JudgeTimes_X1` (`JudgeID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `LTC_Judges`
--

DROP TABLE IF EXISTS `LTC_Judges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `LTC_Judges` (
  `JudgeID` int(4) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `LastName` varchar(255) NOT NULL DEFAULT '',
  `FirstName` varchar(255) NOT NULL DEFAULT '',
  `Address` varchar(255) NOT NULL DEFAULT '',
  `City` varchar(255) NOT NULL DEFAULT '',
  `State` char(2) NOT NULL DEFAULT '',
  `Zip` varchar(10) NOT NULL DEFAULT '',
  `Email` varchar(255) NOT NULL DEFAULT '',
  `Phone` varchar(15) NOT NULL DEFAULT '',
  `ChurchID` int(4) unsigned zerofill NOT NULL DEFAULT '0000',
  PRIMARY KEY (`JudgeID`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `LTC_Log`
--

DROP TABLE IF EXISTS `LTC_Log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `LTC_Log` (
  `Date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `UserID` varchar(10) NOT NULL DEFAULT '',
  `Action` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`Date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `LTC_Money`
--

DROP TABLE IF EXISTS `LTC_Money`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `LTC_Money` (
  `ChurchID` int(3) unsigned NOT NULL DEFAULT '0',
  `Amount` decimal(7,2) NOT NULL DEFAULT '0.00',
  `Annotation` varchar(255) NOT NULL DEFAULT '',
  `Date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  KEY `Index_ChurchID` (`ChurchID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `LTC_NonParticipants`
--

DROP TABLE IF EXISTS `LTC_NonParticipants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `LTC_NonParticipants` (
  `ChurchID` int(4) unsigned NOT NULL DEFAULT '0',
  `Name` varchar(255) NOT NULL DEFAULT '',
  `Phone` varchar(14) NOT NULL DEFAULT '',
  `Email` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `LTC_Participants`
--

DROP TABLE IF EXISTS `LTC_Participants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `LTC_Participants` (
  `ParticipantID` int(4) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `FirstName` varchar(255) NOT NULL DEFAULT '',
  `LastName` varchar(255) NOT NULL DEFAULT '',
  `Address` varchar(255) NOT NULL DEFAULT '',
  `City` varchar(255) NOT NULL DEFAULT '',
  `State` char(2) NOT NULL DEFAULT '',
  `Zip` varchar(10) NOT NULL DEFAULT '',
  `Grade` char(3) NOT NULL DEFAULT '',
  `Gender` char(1) NOT NULL DEFAULT '',
  `AttendConv` char(1) NOT NULL DEFAULT 'Y',
  `ShirtSize` char(3) NOT NULL DEFAULT '',
  `InfoToUniv` char(1) NOT NULL DEFAULT 'Y',
  `Email` varchar(255) DEFAULT NULL,
  `Comments` varchar(255) DEFAULT NULL,
  `ChurchID` int(3) NOT NULL DEFAULT '0',
  `Phone` varchar(15) NOT NULL DEFAULT '',
  `MealTicket` char(1) NOT NULL DEFAULT 'Y',
  PRIMARY KEY (`ParticipantID`),
  KEY `Participants_X1` (`ChurchID`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1 COMMENT='LTC Participants';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `LTC_Registration`
--

DROP TABLE IF EXISTS `LTC_Registration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `LTC_Registration` (
  `ChurchID` int(3) NOT NULL DEFAULT '0',
  `ParticipantID` int(4) unsigned zerofill NOT NULL DEFAULT '0',
  `EventID` int(3) NOT NULL DEFAULT '0',
  `SchedID` int(8) unsigned zerofill NOT NULL DEFAULT '00000000',
  `Award` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ParticipantID`,`EventID`),
  KEY `Registration_X1` (`ChurchID`),
  KEY `Registration_X2` (`SchedID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Regestration Information ';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `LTC_Rooms`
--

DROP TABLE IF EXISTS `LTC_Rooms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `LTC_Rooms` (
  `RoomID` int(5) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `RoomName` varchar(50) NOT NULL DEFAULT '',
  `RoomSeats` int(4) NOT NULL DEFAULT '0',
  `AllowConflicts` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`RoomID`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1 COMMENT='Rooms at Hotel';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `LTC_TeamMembers`
--

DROP TABLE IF EXISTS `LTC_TeamMembers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `LTC_TeamMembers` (
  `TeamID` int(4) unsigned zerofill NOT NULL DEFAULT '0000',
  `ParticipantID` int(4) unsigned zerofill NOT NULL DEFAULT '0000',
  `ChurchID` int(3) unsigned NOT NULL DEFAULT '0',
  `Award` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`TeamID`,`ParticipantID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `LTC_Teams`
--

DROP TABLE IF EXISTS `LTC_Teams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `LTC_Teams` (
  `TeamID` int(4) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `ChurchID` int(3) unsigned NOT NULL DEFAULT '0',
  `EventID` int(3) unsigned NOT NULL DEFAULT '0',
  `Comment` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`TeamID`),
  KEY `Teams_X1` (`ChurchID`),
  KEY `Teams_X2` (`EventID`)
) ENGINE=InnoDB AUTO_INCREMENT=100000 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `LTC_Users`
--

DROP TABLE IF EXISTS `LTC_Users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `LTC_Users` (
  `Userid` varchar(10) NOT NULL DEFAULT '',
  `ChurchID` int(3) NOT NULL DEFAULT '0',
  `Name` varchar(255) NOT NULL DEFAULT '',
  `Password` varchar(255) NOT NULL DEFAULT '',
  `Admin` char(1) NOT NULL DEFAULT 'N',
  `Status` char(1) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL,
  `lastLogin` datetime NOT NULL,
  `loginCount` int(6) NOT NULL,
  `failedLoginCount` int(6) DEFAULT NULL,
  `verificationCode` int(6) unsigned zerofill DEFAULT NULL,
  PRIMARY KEY (`Userid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='List of users';

--
-- Table structure for table `LTC_Resets`
--

DROP TABLE IF EXISTS `LTC_Resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `LTC_Resets` (
  `Userid` varchar(10)   NOT NULL DEFAULT '',
  `Code`   varchar(255)  NOT NULL DEFAULT '',
  `Created` int(11)      NOT NULL DEFAULT 0,
  PRIMARY KEY (`Code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Codes for password resets';


--
-- Add initial church and admin id
--

INSERT INTO `LTC_Churches` (`ChurchID`,`ChurchName`,`ChurchAddr`,`ChurchCity`,`ChurchState`,`ChurchZip`,`ChurchPhone`,`CoordName`,`CoordAddr`,`CoordCity`,`CoordState`,`CoordZip`,`CoordPhone`,`ChurchEmail`,`CoordEmail`) 
             VALUES (100,'First Church','123 The Rock','City of gold','AZ','77777','(777) 777-7777','My Name','123 The Rock','City of gold','AZ','77777','(777) 777-7777','my.ltc@somedomain.org','my.ltc@somedomain.org');

INSERT INTO `LTC_Users` (`Userid`,`ChurchID`,`Name`,`Password`,`Admin`,`Status`,`email`,`lastLogin`,`loginCount`,`failedLoginCount`,`verificationCode`) 
VALUES ('Admin',100,'Initial Admin account - delete me!','$2y$10$EV2iOBXUO9GGPCh/ZeBZRO/EH1LcCgNvwlzgeNHgDR5rZ1Mk1JiRq','Y','O','my.ltc@somedomain.org','0000-00-00 00:00:00',0,NULL,NULL);
