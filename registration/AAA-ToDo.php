<?php
//----------------------------------------------------------------------------
// This software is licensed under the MIT license. Use as you wish but give
// and take credit where due.
//
// Author: Paul Lemmons
//----------------------------------------------------------------------------
?>
<?php

/*
 * TODO make sure every page has correct permissions applied to them
 *           Owner: None
 *           Status: Not Started
 *           Detailed description:
 *              Right now all pages have only one of two permissions assigned to them. Either everyone
 *              can see the page or only admins. As we develop the security model so that there are more
 *              options than just those two each page needs to  be updated in such a way that it knows
 *              who can access it. And it may be  a mixture of roles.
 *
 * TODO Link Users with EventDirector Privs to EventDirectors table
 *           Owner: None
 *           Status: Not Started
 *           Detailed description:
 *              There is no sense in having a separate list of event directors from uses. There should just
 *              be users with varying privs.
 *
 * TODO Update reports to show Event Directors owned Events
 *           Owner: Paul
 *           Status: Not Started
 *           Detailed description:
 *              We need a report that lists the events and who owns them
 *
 * TODO Don't add "Event Director" to AddUser, Set the priv by selection in Event Directors
 *           Owner: None
 *           Status: Not Started
 *           Detailed description:
 *              Part of the security uplift. Adding a user should have "event director" as one of the
 *              privs.
 *
 * TODO When you delete or update an event director be sure to update user and or event  record
 *           Owner: None
 *           Status: Not Started
 *           Detailed description:
 *              Events and event directors are tied together.  Updates should be propagated in such a
 *              was as it makes sense.
 *
 * TODO When you delete a user that is an EventDirector update Event Director record
 *           Owner: Paul
 *           Status: Canceled - Event directors will be rolled into users
 *           Detailed description:
 *
 * TODO Delete all old users
 *           Owner: None
 *           Status: Not Started
 *           Detailed description:
 *              Users that have not logged in in two years should be deleted
 *              To do this we will need to keep track of when people login
 *
 * TODO Add self registration page
 *           Owner: None
 *           Status: Not Started
 *           Detailed description:
 *              Add the ability for a person to self register. There will still need to be some sort of check
 *              and balance but they should be able to choose their own userid and password.
 *
 * TODO Add forgot password page
 *           Owner: None
 *           Status: Not Started
 *           Detailed description:
 *              Self explanatory
 *
 * TODO Encrypt Password
 *           Owner: None
 *           Status: Not Started
 *           Detailed description:
 *              Self explanatory.
 *
 * TODO Allow people to change their password
 *           Owner: None
 *           Status: Not Started
 *           Detailed description:
 *              Self explanatory.
 *
 * TODO Allow people to change their email address
 *           Owner: None
 *           Status: Not Started
 *           Detailed description:
 *              Self explanatory.
 *
 * TODO Update last login in user record
 *           Owner: None
 *           Status: Not Started
 *           Detailed description:
 *              Self explanatory.
 *
 * TODO Lock user after 5 invalid attempts
 *           Owner: None
 *           Status: Not Started
 *           Detailed description:
 *              Self explanatory.
 *
 * TODO Update the Users and AdminUsers page to accept the new privs
 *           Owner: None
 *           Status: Not Started
 *           Detailed description:
 *              Self explanatory.
 *
 * TODO Add JavaScript to inactivate conflicting fields in add/update user
 *           Owner: None
 *           Status: Not Started
 *           Detailed description:
 *              Self explanatory.
 *
 * DONE Add ability to move scheduled event to a new room
 *           Owner: Paul
 *           Status: COMPLETED
 *           Detailed description:
 *              Self explanatory.
 *
 * TODO Add ability to move individuals to a new room
 *           Owner: Paul
 *           Status: Not Started
 *           Detailed description:
 *              Self explanatory.
 *
 * DONE Add ability to rename room
 *           Owner: Paul
 *           Status: COMPLETED
 *           Detailed description:
 *              Self explanatory.
 *
 * DONE Remove "Allow Conflicts" from rooms
 *           Owner: Paul
 *           Status: Completed
 *           Detailed description:
 *              There was a thought at one time that we could manage the multiple events in the
 *              same room by allowing conflicts. That was a stupid idea. Let's get rid of that.
 *
 * TODO Remove "Allow Conflicts" from rooms Database
 *           Owner: Paul
 *           Status: Not Started
 *           Detailed description:
 *              There was a thought at one time that we could manage the multiple events in the
 *              same room by allowing conflicts. That was a stupid idea. Let's get rid of that.
 *
 * TODO Add ability to print a single event director's roster
 *           Owner: Paul
 *           Status: Not Started
 *           Detailed description:
 *              Self explanatory.
 *
 * TODO Add CSS Styles to everything
 *           Owner: None
 *           Status: Not Started
 *           Detailed description:
 *              Self explanatory.
 *
 * DONE Block ability to order extra stuff after reg closes
 *           Owner: Paul
 *           Status: COMPLETED
 *           Detailed description:
 *              Self explanatory.
 *
 * TODO Move reg status out of user record to convention record
 *           Owner: None
 *           Status: Not Started
 *           Detailed description:
 *              Not sure what I was thinking on this. Will leave it here for now as a reminder.
 *
 * DONE Limit room reg to min of event category
 *           Owner: Paul
 *           Status: COMPLETED 12/31/14
 *           Detailed description:
 *              Rooms can easily overbook if multiple teams or age groups are all scheduled for the same
 *              room. This should not be allowed.
 *
 * TODO Make confirmation pages timed
 *           Owner: None
 *           Status: Not Started
 *           Detailed description:
 *              Self explanatory.
 *
 * TODO Warn if leaving a page that has been updated without applying
 *           Owner: None
 *           Status: Not Started
 *           Detailed description:
 *              Self explanatory.
 *
 * TODO Change admin page to indicate how many judges needed over all
 *           Owner: None
 *           Status: Not Started
 *           Detailed description:
 *              Right now there is a suggested number of judges that should be assigned by each
 *              congregation. It would be nice to see how we are doing over all.
 *
 * TODO Add to admin pages the number of chamrers needed
 *           Owner: None
 *           Status: Not Started
 *           Detailed description:
 *              Like Judging we need charmers. If a count of charmers signed up and what was needed
 *              was shown on the registration page it could encourage congregations to sign up charmers.
 *
 * TODO Rename MaxWebSlots to MaxEventSlots and eliminate MaxRooms
 *           Owner: None
 *           Status: Not Started
 *           Detailed description:
 *              This is legacy coding. At one time there was both electronic AND paper signup. This
 *              hack was to limit the number of web entries so that there would be some paper slots
 *              open at convention. The paper signup has been eliminated.
 *
 *              The MaxRooms is also legacy code. It was there before there was actual room
 *              management.
 */
?>
