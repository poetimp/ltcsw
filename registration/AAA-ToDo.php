<?php

/*
 * TODO make sure every page has correct permissions applied to them
 *           Owner: None
 *           Status: Not Started
 *           Detailed description:
 *              Right now all pages have only one of two permissions assigned to them. Eithery everyone
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
 *              We need a report that lists the events and who ownes them
 * 
 * TODO Don't add "Event Director" to AddUser, Set the priv by selection in Evenet Directors
 *           Owner: None
 *           Status: Not Started
 *           Detailed description:
 *              Part of the security uplift. Adding a user should have "event directore" as one of the
 *              privs.
 * 
 * TODO When you delete or update an event director be sure to update user and or event  record
 *           Owner: None
 *           Status: Not Started
 *           Detailed description:
 *              Events and event directors are tied together.  Updates should be propigated in such a
 *              was as it makes sense.
 * 
 * TODO When you delete a user that is an EventDirector update Event Director record
 *           Owner: Paul
 *           Status: Canceled - Event directores will be rolled into users
 *           Detailed description:
 * 
 * TODO Delete all old users
 *           Owner: None
 *           Status: Not Started
 *           Detailed description:
 *              Users that have not logged in in two years should be deleted
 * 
 * TODO Add self registration page
 *           Owner: None
 *           Status: Not Started
 *           Detailed description:
 *              Add the ability for a person to self register. There will still need to be some sort of check
 *              and balance but they shold be able to choose their own userid and password.
 * 
 * TODO Add forgot password page
 *           Owner: None
 *           Status: Not Started
 *           Detailed description:
 *              Self explainatory
 * 
 * TODO Encrypt Password
 *           Owner: None
 *           Status: Not Started
 *           Detailed description:
 *              Self explainatory. 
 * 
 * TODO Allow peole to change their password
 *           Owner: None
 *           Status: Not Started
 *           Detailed description:
 *              Self explainatory. 
 * 
 * TODO Allow people to change their email address
 *           Owner: None
 *           Status: Not Started
 *           Detailed description:
 *              Self explainatory. 
 * 
 * TODO Update last login in user record
 *           Owner: None
 *           Status: Not Started
 *           Detailed description:
 *              Self explainatory. 
 * 
 * TODO Lock user after 5 invalid attempts
 *           Owner: None
 *           Status: Not Started
 *           Detailed description:
 *              Self explainatory. 
 * 
 * TODO Update the Users and AdminUsers page to accept the new privs
 *           Owner: None
 *           Status: Not Started
 *           Detailed description:
 *              Self explainatory. 
 * 
 * TODO Add JavaScript to inactivate conflicting fields in add/update user
 *           Owner: None
 *           Status: Not Started
 *           Detailed description:
 *              Self explainatory. 
 * 
 * TODO Add ability to move group to a new room
 *           Owner: Paul
 *           Status: Not Started
 *           Detailed description:
 *              Self explainatory. 
 * 
 * TODO Add ability to move individules to a new room
 *           Owner: Paul
 *           Status: Not Started
 *           Detailed description:
 *              Self explainatory. 
 * 
 * TODO Add ability to rename room
 *           Owner: Paul
 *           Status: Not Started
 *           Detailed description:
 *              Self explainatory. 
 * 
 * TODO Add ability to print a single event director's rostor
 *           Owner: Paul
 *           Status: Not Started
 *           Detailed description:
 *              Self explainatory. 
 * 
 * TODO Add CSS Styles to everything
 *           Owner: None
 *           Status: Not Started
 *           Detailed description:
 *              Self explainatory. 
 * 
 * TODO Block ability to order extra stuff after reg closes
 *           Owner: Paul
 *           Status: Completed
 *           Detailed description:
 *              Self explainatory. 
 * 
 * TODO Move reg status out of user record to convention record
 *           Owner: None
 *           Status: Not Started
 *           Detailed description:
 *              Not sure what I was thinking on this. Will leave it here for now as a reminder.
 * 
 * TODO Limit room reg to min of event catagory
 *           Owner: Paul
 *           Status: Not Started
 *           Detailed description:
 *              Rooms can easily overbook if multiple teams or age groups are all sceduled for the same
 *              room. This should not be allowed.
 * 
 * TODO Make confirmation pages timed
 *           Owner: None
 *           Status: Not Started
 *           Detailed description:
 *              Self explainatory. 
 * 
 * TODO Warn if leaving a page that has been updated without applying
 *           Owner: None
 *           Status: Not Started
 *           Detailed description:
 *              Self explainatory. 
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
 */
?>
