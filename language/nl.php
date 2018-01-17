<?php
/*
 * Please note - if you are translating this file, that any quotes that have a backslash (\") need that
 * back slash to escape the extra quotes in the string.
 * Any variables in this file need to stay in the same format.
 *
 * If you have any questions at all about this file, please contact me through my CodeCanyon profile.
 * http://codecanyon.net/user/Luminary
 */

// All Pages - Globals
// --------------------------------------------------------------------------------------------------
$accessErrorHeader			= "Access Error";
$permissionDenied			= "Permission Denied. You can not access this page.";
$pageNotFoundHeader			= "Page Not Found &mdash; 404 Error";
$pageNotFoundQuip			= "Can not find the page";
$thankYouText				= "Thank you,";

$cancelBtn					= "Cancel";
$closeBtn					= "Close";
$okBtn						= "OK";
$yesBtn						= "Yes";
$noBtn						= "No";
$saveChangesBtn				= "Save Changes";
$saveBtn					= "Save";
$deleteBtn					= "Delete";
$updateBtn					= "Update";
$selectOption				= "Select...";

$taskedLogoAltText			= "Tasked Task Management";
$passwordsNotMatchMsg		= "Passwords do not match, please check your entries.";

$loginNavLink				= "Account Login";
$createAccNavLink			= "Create Account";
$resetPassNavLink			= "Reset Password";

// Calendar Include File
// --------------------------------------------------------------------------------------------------
$todayLink					= "Today";
$newEventLink				= "New Event";
$monthLink					= "Month";
$weekLink					= "Week";
$dayLink					= "Day";
$noTimesSet					= "No times have been set";
$sharedEvent				= "Shared Event";
$pulicEvent					= "Public Event";
$eventPostedBy				= "Posted By";
$editEvent					= "Edit";
$deleteEvent				= "Delete";

// Activate Account
// --------------------------------------------------------------------------------------------------
$activatePageTitle			= "Account Activation";
$activateText1				= "Thank you for verifying your email address and activating your account.";
$activateText2				= "Your account has been activated, and you can now log in.";
$activateText3				= "All Set! Go ahead and Sign In";
$activateText4				= "You have all ready verified your email address and activated your account.";
$activateText5				= "Your account has been activated, and you can now log in.";
$activateText6				= "Get Started by Signing In";
$activateText7				= "Please check your email for the Account Activation Link.";
$activateText8				= "You cannot directly access this page. please use the link that has been sent to your email.";

// Login
// --------------------------------------------------------------------------------------------------
$loginPageTitle				= "User Log In";
$loginPageQuip				= "Enter your ".$set['siteName']." account information and click Sign In.";
$emailAddyField				= "Email Address";
$emailAddyFieldHelp			= "A Valid Email Address. Your Email Address is also used to log in.";
$passwordField				= "Password";
$passwordFieldHelp1			= "Choose a password for your new account.";
$repeatText					= "Repeat";
$repeatPassHelp				= "Repeat Password. Passwords MUST Match.";
$signInBtn					= "Sign In";
$createAccountTitle			= "Create Account";
$createAccountQuip			= "Register a New ".$set['siteName']." Account.";
$firstNameField				= "First Name";
$lastNameField				= "Last Name";
$captchaCodeField			= "Captcha Code";
$createAccountBtn			= "Create My Account";
$resetPasswordTitle			= "Reset Password";
$resetPasswordQuip			= "Reset Your ".$set['siteName']." Account Password";
$resetPassHelp				= "The email address associated with your account.";
$resetPassBtn				= "Reset Password";
$accEmailAddyReq			= "Account Email Address is required.";
$accPassReq					= "Account Password is required.";
$loginFailedMsg				= "Log in failed. Please check your entries.";
$accInactiveMsg				= "Your account is not currently active, and you can not log in.";
$accNotFoundMsg				= "Account not found for that email address and Password. Please check your entries.";

// Reset Password Email
$resetPassEmailTitle		= "Your ".$set['siteName']." Password has been Reset";
$resetPassEmail1			= "Your temporary password is:";
$resetPassEmail2			= "Please take the time to change your password to something you can easily remember.
You can change your password on your My Profile page after logging into your account. There you can update your password, as well as your account details.";
$resetPassEmail3			= "You can log into your account with your email address and new password at:";

$passwordResetMsg			= "Your Password has been reset. Please check your email for your new password, and instructions on how to update your account.";
$validEmailAddyReq			= "A Valid Email Address is required.";
$newPassReq					= "An Account Password is required.";
$captchaCodeReq				= "The Captcha Code is required.";
$newAccountErrorMsg			= "Something went wrong, and your New Account could not be created.";
$duplicateAccountMsg		= "There is all ready an account registered with that email address.";

// New Account Email
$newAccountEmailSubject		= "Your ".$set['siteName']." Account has been created";
$newAccountEmail1			= "Your new Account details:";
$newAccountEmail2			= "Username: Your email address<br>Password:";
$newAccountEmail3			= "You must activate your account before you will be able to log in. Please click (or copy/paste) the following link to activate your account:<br>";
$newAccountEmail4			= "Once you have activated your new account and logged in, please take the time to update your account profile details.";
$newAccountEmail5			= "You can log in to your account at";
$newAccountCreatedMsg		= "Your New Account has been created and an email has been sent.";
$incorrectCaptchaMsg		= "The Captcha Code is incorrect. Please try entering it in again.";

// Header Include
// --------------------------------------------------------------------------------------------------
$headerIncTitle				= "Task Management";

// Footer Include
// --------------------------------------------------------------------------------------------------
$recentActivityTitle		= "Recent Activity";
$activeCategoriesTitle		= "Active Categories";
$copyrightText				= "Copyright";

// Navigation Include
// --------------------------------------------------------------------------------------------------
$profileNavLink				= "Profile";
$signOutNavLink				= "Sign Out";
$dashboardNavLink			= "Dashboard";
$calendarNavLink			= "Calendar";
$tasksNavLink				= "Tasks";
$openTasksNavLink			= "Open Tasks";
$closedTasksNavLink			= "Closed Tasks";
$newTaskNavLink				= "New Task";
$catsNavLink				= "Categories";
$catListNavLink				= "Categories List";
$newCatNavLink				= "New Category";
$usersNavLink				= "Users";
$userListNavLink			= "Users List";
$newUserNavLink				= "New User";
$siteSettingsNavLink		= "Site Settings";
$signOutQuip				= ", Are you sure you want to Sign Out of your account?";
$signOutBtn					= "Sign Out";
$searchTooltip				= "Search";
$searchPlaceholder			= "Enter single search term and press enter";
$addNewTaskModal			= "Add a New Task";
$addNewTaskQuip				= "Categories must first be created before you can add a New Task.";
$taskTitleField				= "Task Title";
$taskTitleHelp				= "Give your new Task a short Title";
$categoryField				= "Category";
$categoryHelp				= "Select a Category for this Task.";
$priorityField				= "Priority";
$priorityHelp				= "Low, Normal, High, Important etc.";
$statusField				= "Status";
$statusHelp					= "New, In Progress etc.";
$perentCompField			= "Percent Complete";
$perentCompHelp				= "Numbers Only — no percent symbol.";
$startDateField				= "Start Date";
$dueDateField				= "Due Date";
$taskDescField				= "Task Description";
$addToCalCheckbox			= "Add to Your Calendar";
$addToCalHelp				= "Checking this option will add the task to your calendar on the Task's Due Date.";
$newCatModal				= "Add a New Category";
$catNameField				= "Category Name";
$catNameHelp				= "Give the New Category a Short Name";
$catDescField				= "Category Description";
$newUserModal				= "Add a New User Account";
$newUserEmailHelp			= "A valid email address. The new account information will be sent to this address.";
$generatePassTooltip		= "Generate Password";
$showPlainText				= "Show Plain Text";
$hidePlainText				= "Hide Plain Text";
$usersText					= "User's";
$inactiveAccountCheckbox	= "Inactive Account";
$inactiveAccountHelp		= "Inactive Accounts require the User to activate the Account via a link sent to the account email address.";
$taskTitleReq				= "The Task Title is required.";
$taskCatReq					= "The Task Category is required.";
$taskPriorityReq			= "The Task Priority is required.";
$taskStatusReq				= "The Task Status is required.";
$taskPercentCompReq			= "The Task Percent Complete is required.";
$taskStartDateReq			= "The Task Start Date is required.";
$taskDueDateReq				= "The Task Due Date is required.";
$taskDescReq				= "The Task Description is required.";
$taskedSavedMsg1			= "The New Task has been saved, and has been added to your Calendar.";
$taskedSavedMsg2			= "The New Task has been saved.";
$catNameReq					= "The Category Name is required.";
$catDescReq					= "The Category Description is required.";
$newCatSavedMsg				= "The New Category has been saved.";
$userEmailReq				= "The User's Email Address is required.";
$newUserPassReq				= "The New Account Password is required.";
$userFirstReq				= "The User's First Name is required.";
$userLastReq				= "The User's Last Name is required.";
$accountExists1				= "There is all ready an account for the email";
$newUserAccCreatedMsg1		= "The New User account has been created, and set to Active.";
$newUserAccCreatedMsg2		= "The New User account has been created, and an activation email has been sent.";

// New User Email
$newUserEmailSubject		= "Your ".$set['siteName']." Account has been created";
$newUserEmail1				= "Your new Account details:";
$newUserEmail2				= "Username: Your email address<br>Password:";
$newUserEmail3				= "You must activate your account before you will be able to log in. Please click (or copy/paste) the following link to activate your account:<br>";
$newUserEmail4				= "Once you have activated your new account and logged in, please take the time to update your account profile details.";

// Dashboard
// --------------------------------------------------------------------------------------------------
$openText					= "Open";
$activeText					= "Active";
$upcomingText				= "Upcoming";
$newTaskTooltip				= "New Task";
$newCatTooltip				= "New Category";
$noOpenTasks				= "No Open Tasks Found.";
$noteText					= "Note";
$notesText					= "Notes";
$viewTaskTooltip			= "View Task";
$viewCatTooltip				= "View Category";
$percCompleteText			= "% Complete";
$percentSymbol				= "%";
$completeTooltip			= "Complete";
$editTooltip				= "Edit";
$deleteTooltip				= "Delete";
$completeTaskQuip1			= "Mark the Task";
$completeTaskQuip2			= "as closed/complete?";
$deleteTaskQuip				= "Are you sure you want to permanently DELETE the task";
$upcomingDatesTitle			= "Upcoming Dates";
$noUpcomingDatesMsg			= "No Upcoming Dates Found.";
$noDateDetails				= "No Details entered for this date.";
$startDateTD				= "Start Date:";
$startTimeTD				= "Start Time:";
$endDateTD					= "End Date:";
$endTimeTD					= "End Time:";
$deleteDateQuip				= "Are you sure you want to permanently DELETE the date entry for";
$taskMarkedCompMsg			= "The Task has been marked as completed.";
$taskedDeletedMsg			= "The Task and all Task Notes have been permanently deleted.";
$dateDeletedMsg				= "The Date has been permanently deleted.";
$taskText					= "Task";
$tasksText					= "Tasks";
$categoryText				= "Category";
$categoriesText				= "Categories";
$dateText					= "Date";
$datesText					= "Dates";
$openTasksToggle			= "Open All Tasks";
$closeTasksToggle			= "Close All Tasks";
$openDatesToggle			= "Open All Dates";
$closeDatesToggle			= "Close All Dates";
$toggleLink					= "Toggle";

// Calendar
// --------------------------------------------------------------------------------------------------
$calendarPageTitle			= "My Calendar";
$calendarPageQuip			= "Click on an Event Title for more information &amp; options.";
$editEventModal				= "Edit Event Date";
$dateFormatHelp				= "Format: YYYY-MM-DD";
$startTimeField				= "Start Time";
$timeFormatHelp				= "Format: HH:MM or leave blank for an All Day event.";
$endDateField				= "End Date";
$endTimeField				= "End Time";
$eventTitleField			= "Event Title";
$eventDescField				= "Event Description";
$eventDescHelp				= "Description of the Event (not required).";
$newDateModal				= "Add a New Event Date";
$selectColorField			= "Select Date Color";
$selectColorHelp			= "Choose a color for the Date.";
$saveDateBtn				= "Save Date";
$dateTitleReq				= "The Date Title is required.";
$startDateReq				= "The Start Date is required.";
$dateSavedMsg				= "The New Event Date has been saved.";
$dateDeletedErrorMsg		= "An error was encountered, and the Date could not be deleted.";
$dateUpdatedMsg				= "The Event Date has been updated.";
$dateUpdatedErrorMsg		= "An error was encountered, and the Date could not be updated.";

// Open Tasks
// --------------------------------------------------------------------------------------------------
$taskTitleTH				= "Title";
$taskCatTH					= "Category";
$priorityTH					= "Priority";
$statusTH					= "Status";
$taskCompErrorMsg			= "An Error was encountered, and the Task could not be marked as completed.";
$taskDeleteErrorMsg			= "An Error was encountered, and the Task could not be deleted.";

// Closed Tasks
// --------------------------------------------------------------------------------------------------
$noClosedTasksMsg			= "No Closed Tasks Found.";
$dateClosedTH				= "Date Closed";
$reopenTaskText				= "Reopen";
$reopenTaskQuip				= "Reopen the Task";
$taskReopenedMsg			= "The Task has been reopened.";
$taskReopenErrorMsg			= "An Error was encountered, and the Task could not be reopened.";

// View Tasks
// --------------------------------------------------------------------------------------------------
$taskNotesText				= "Task Notes";
$reopenTaskBtn				= "Reopen Task";
$completeTaskBtn			= "Complete Task";
$taskClosedMsg				= "This Task has been completed/closed.";
$taskDetailsText			= "Task Details";
$titleText					= "Title";
$descText					= "Description";
$taskSettingsTitle			= "Task Settings";
$taskDatesTitle				= "Task Dates";
$dateDueField				= "Date Due";
$dateClosedField			= "Date Closed";
$noNotesMsg					= "No Notes have been entered.";
$editNoteModal				= "Edit Note";
$deleteNoteQuip				= "Are you sure you want to permanently DELETE this note?";
$addNoteTitle				= "Add a Task Note";
$taskUpdatedMsg				= "The Task has been updated.";
$taskNoteReq				= "The Task Note is required.";
$taskNoteUpdatedMsg			= "The Task Note has been updated.";
$taskNoteDeletedMsg			= "The Task Note has been permanently deleted.";
$taskNoteSavedMsg			= "The New Task Note has been saved.";

// Categories List
// --------------------------------------------------------------------------------------------------
$descTH						= "Description";
$dateCreatedTH				= "Date Created";
$activeText					= "Active";
$inactiveText				= "Inactive";
$deleteCatQuip1				= "Are you sure you want to permanently DELETE the Category";
$deleteCatQuip2				= "This will permanently DELETE the Category, and all of it's Tasks and Notes.";
$catDeletedMsg				= "The Category, Task and all Task Notes have been permanently deleted.";
$catDeleteErrorMsg			= "An Error was encountered, and the Category could not be deleted.";

// View Category
// --------------------------------------------------------------------------------------------------
$editCatTooltip				= "Edit Category";
$lastUpdatedText			= "Last Updated";

// Edit Category
// --------------------------------------------------------------------------------------------------
$catDetailsTitle			= "Category Details";
$catSettingsTitle			= "Category Settings";
$catDateReq					= "The Category Date Created is required.";
$catUpdatedMsg				= "The Category has been updated.";

// User List
// --------------------------------------------------------------------------------------------------
$noUsersFoundMsg			= "No Users Found.";
$usersNameTH				= "User's Name";
$lastLoginTH				= "Last Login";
$viewUserTooltip			= "View User";
$disbaledTooltip			= "Disabled";
$emailUserTooltip			= "Email User";
$deleteUserQuip1			= "Are you sure you want to permanently DELETE the User Account for";
$deleteUserQuip2			= "This will permanently DELETE the User's Account, and ALL Dates, Categories, Tasks and Notes the User has saved.";
$primAdminDeleteError		= "You can not delete the Primary Admin Account.";
$userDeletedMsg				= "The User's Account and ALL Dates, Categories, Tasks and Task Notes have been permanently deleted.";

// View User
// --------------------------------------------------------------------------------------------------
$viewUserTitle				= "User";
$userDetailsTitle			= "User Details";
$usersEmailField			= "User's Email";
$changeUserPassTitle		= "Change User's Account Password";
$changeUserPassQuip			= "You can change the User's Account Password. Leave the Password fields blank to keep the current Password.";
$newPassField				= "New Password";
$clearFieldsText			= "Clear Fields";
$userStatusTitle			= "User Status";
$inactiveAccText			= "Inactive Account";
$activeAccText				= "Active Account";
$accTypeField				= "Account Type";
$siteAdminText				= "Site Administrator";
$joinDateField				= "Join Date";
$userNotesTitle				= "User Notes";
$privNotesField				= "Private Notes";
$privNotesHelp				= "Internal Only Notes, The User can not see these notes.";
$userAccUpdatedMsg			= "The User's Account has been updated.";
$neverText					= "Never";

// Site Settings
// --------------------------------------------------------------------------------------------------
$globalSettingsTitle		= "Global Settings";
$installURLField			= "Installation URL";
$installURLHelp				= "Used in email notifications. Must include the trailing slash.";
$siteNameField				= "Site Name";
$siteNameHelp				= "Appears in the Footer and other various locations throughout Tasked.";
$siteEmailField				= "Site Email";
$siteEmailHelp				= "Used as the Reply to address in email notifications.";
$localTitle					= "Localization";
$selectLangField			= "Select Language";
$optionArabic				= "Arabic";
$optionBulgarian			= "Bulgarian";
$optionChechen				= "Chechen";
$optionCzech				= "Czech";
$optionDanish				= "Danish";
$optionEnglish				= "English";
$optionCanadianEnglish		= "Canadian English";
$optionBritishEnglish		= "British English";
$optionEspanol				= "Espanol";
$optionFrench				= "French";
$optionGerman				= "German";
$optionCroatian				= "Croatian";
$optionHungarian			= "Hungarian";
$optionArmenian				= "Armenian";
$optionIndonesian			= "Indonesian";
$optionItalian				= "Italian";
$optionJapanese				= "Japanese";
$optionKorean				= "Korean";
$optionDutch				= "Dutch";
$optionPortuguese			= "Portuguese";
$optionRomanian				= "Romanian";
$optionSwedish				= "Swedish";
$optionThai					= "Thai";
$optionVietnamese			= "Vietnamese";
$optionCantonese			= "Cantonese";
$globalOptionsTitle			= "Global Options";
$enableRegField				= "Enable Self-Registrations?";
$enableRegHelp				= "Set to No to disable the ability for anonymous users Creating New Accounts.";
$enableWeatherField			= "Enable Weather Widget?";
$enableWeatherHelp			= "Set to No to disable the displaying of the Weather Widget in the Sidebar/Footer.";
$enableCalField				= "Enable Calendar Widget?";
$enableCalHelp				= "Set to No to disable the displaying of the Calendar Widget in the Sidebar/Footer.";
$installURLReq				= "The Installation URL is required";
$siteNameReq				= "The Site Name is required.";
$siteEmailReq				= "The Site Email Address is required.";
$siteSettingsUpdatedMsg		= "The Global Site Settings has been updated.";

// Profile
// --------------------------------------------------------------------------------------------------
$profilePageTitle			= "My Account Profile";
$profileDetailsTitle		= "Profile Details";
$changePasswordTitle		= "Change Account Password";
$changePasswordQuip			= "You can change your Account Password. Leave the Password fields blank to keep your current Password.";
$currentText				= "Current";
$currentPasswordHelp		= "Your Current Account Password.";
$locationTitle				= "Location";
$locationQuip				= "Your City and State for the Weather Widget. Format: City, ST (Two Letter Abbreviation for State).";
$cityStateField				= "City &amp; State";
$yourFisrtNameReq			= "Your First Name is required.";
$yourLastNameReq			= "Your Last Name is required.";
$yourEmailReq				= "Your Email Address is required.";
$accountProfileUpdatedMsg	= "Your Account Profile has been updated.";
$currentPassError			= "Your Current Account Password is incorrect. Please check your entry.";
