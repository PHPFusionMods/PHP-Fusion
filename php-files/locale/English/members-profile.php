<?php
// Members List
$locale['400'] = "Members List";
$locale['401'] = "User Name";
$locale['402'] = "User Type";
$locale['403'] = "There are no user names beginning with ";
$locale['404'] = "Show All";
// View User Groups
$locale['410'] = "View User Group";
$locale['411'] = "%u user";
$locale['412'] = "%u users";
// User Profile
$locale['420'] = "Member Profile";
$locale['422'] = "Statistics";
$locale['423'] = "User Groups";
// Edit Profile
$locale['440'] = "Edit Profile";
$locale['441'] = "Profile successfully updated";
$locale['442'] = "Unable to update Profile:";
// Edit Profile Form
$locale['460'] = "Update Profile";
// Update Profile Errors
$locale['480'] = "You must specify a user name & email address.";
$locale['481'] = "User name contains invalid characters.";
$locale['482'] = "The user name ".(isset($_POST['user_name']) ? $_POST['user_name'] : "")." is in use.";
$locale['483'] = "Invalid email address.";
$locale['484'] = "The email address ".(isset($_POST['user_email']) ? $_POST['user_email'] : "")." is in use.";
$locale['485'] = "New passwords do not match.";
$locale['486'] = "Invalid password, use alpha numeric characters only.<br>
Password must be a minimum of 6 characters long.";
$locale['487'] = "<b>Warning:</b> unexpected script execution.";
?>