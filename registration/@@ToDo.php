<?php

/*
 * TODO make sure every page has correct permissions applied to them
 * TODO Link Users with EventDirector Privs to EventDirectors table
 * TODO Update reports to show Event Directors owned Events
 * TODO Don't add "Event Director" to AddUser, Set the priv by selection in Event Directors
 * TODO When you delete or update an event director be sure to update user record
 * TODO When you delete a user that is an EventDirector update Event Director record
 * TODO Delete all old users
 * TODO Add self registration page
 * TODO Add forgot password page
 * TODO Encrypt Password
 * TODO Allow people to change their password
 * TODO Allow people to change their email address
 * TODO Update last login in user record
 * TODO Lock user after 5 invalid attempts
 * TODO Update the Users and AdminUsers page to accept the new privs
 * TODO Add JavaScript to inactivate conflicting fields in add/update user
 * TODO Add ability to move group to a new room
 * TODO Add ability to move individuals to a new room
 * TODO Add ability to rename room
 * TODO Add ability to print a single event director's roster
 * TODO Add CSS Styles to everything
 * TODO Block ability to order extra stuff after reg closes
 * TODO Move reg status out of user record to convention record
 * TODO Limit room reg to min of event category
 * TODO Make confirmation pages timed
 * TODO Warn if leaving a page that has been updated without applying
 * TODO Change admin page to indicate how many judges needed over all
 * TODO Add to admin pages the number of chamrers needed

Database Changes
drop table LTC_ALL_Conventions;
drop table LTC_ALL_LOG;
rename table LTC_ALL_Users to LTC_PHX_Users;
alter table LTC_PHX_Users drop column ConvCode;
alter table LTC_PHX_Users add column email varchar(255) not null;
alter table LTC_PHX_Users add column lastLogin datetime not null;
alter table LTC_PHX_Users add column loginCount int(6) not null;
alter table LTC_PHX_Users add column failedLloginCount int(6) not null;
alter table LTC_PHX_Users add column verificationCode INT(6) ZEROFILL NULL Default null;

*/

?>
