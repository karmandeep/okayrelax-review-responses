<?php
/**
 * WHMCS SDK Sample Addon Module
 *
 * An addon module allows you to add additional functionality to WHMCS. It
 * can provide both client and admin facing user interfaces, as well as
 * utilise hook functionality within WHMCS.
 *
 * This sample file demonstrates how an addon module for WHMCS should be
 * structured and exercises all supported functionality.
 *
 * Addon Modules are stored in the /modules/addons/ directory. The module
 * name you choose must be unique, and should be all lowercase, containing
 * only letters & numbers, always starting with a letter.
 *
 * Within the module itself, all functions must be prefixed with the module
 * filename, followed by an underscore, and then the function name. For this
 * example file, the filename is "addonmodule" and therefore all functions
 * begin "addonmodule_".
 *
 * For more information, please refer to the online documentation.
 *
 * @see https://developers.whmcs.com/addon-modules/
 *
 * @copyright Copyright (c) WHMCS Limited 2017
 * @license http://www.whmcs.com/license/ WHMCS Eula
 */

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

// Require any libraries needed for the module to function.
// require_once __DIR__ . '/path/to/library/loader.php';
//
// Also, perform any initialization required by the service's library.

use WHMCS\Module\Addon\Review_Responses\Admin\AdminDispatcher;
//use WHMCS\Module\Addon\AddonModule\Client\ClientDispatcher;

/**
 * Define addon module configuration parameters.
 *
 * Includes a number of required system fields including name, description,
 * author, language and version.
 *
 * Also allows you to define any configuration parameters that should be
 * presented to the user when activating and configuring the module. These
 * values are then made available in all module function calls.
 *
 * Examples of each and their possible configuration parameters are provided in
 * the fields parameter below.
 *
 * @return array
 */
function review_responses_config()
{
    return array(
        'name' => 'Review Task Responses (Management)', // Display name for your module
        'description' => 'Review Task responses before client sees them, This Module is for the management to review the Assistant Responses', // Description displayed within the admin interface
        'author' => 'Karmandeep Singh', // Module author name
        'language' => 'english', // Default language
        'version' => '1.0', // Version number
        'fields' => array()
    );
}

/**
 * Activate.
 *
 * Called upon activation of the module for the first time.
 * Use this function to perform any database and schema modifications
 * required by your module.
 *
 * This function is optional.
 *
 * @return array Optional success/failure message
 */
function review_responses_activate()
{
	//admin_id is the person to whom the ticket was assigned.
	//reviewer_id is the person who is logged in.
	//Status unpublished, underreview, published
    // Create custom tables and schema required by your module
    $query = "CREATE TABLE `review_responses` (`id` INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY , 
											   `admin_id` INT( 10 ) NOT NULL, 
											   `tid` INT( 10 ) NOT NULL, 
											   `ticket_replies_id` INT( 10 ) NOT NULL, 
											   `userid` INT( 10 ) NOT NULL, 
											   `reviewer_id` INT( 10 ) NOT NULL DEFAULT 0, 
											   `status` INT( 10 ) NOT NULL DEFAULT 0, 
											   `notes` TEXT NOT NULL,
											   `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00', 
											   `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'  ) 
											   ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
    full_query($query);

    // Create custom tables and schema required by your module
    $query = "CREATE TABLE `review_responses_replies` (`id` INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
													   `review_responses_id` INT( 10 ) NOT NULL, 
													   `admin_id` INT( 10 ) NOT NULL DEFAULT 0, 
													   `reviewer_id` INT( 10 ) NOT NULL DEFAULT 0, 
													   `message` TEXT NOT NULL ,
													   `msgstatus` INT( 1 ) NOT NULL DEFAULT 0 ,
		   											   `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00', 
													   `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'  ) 
													   ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
    full_query($query);


    // Create custom tables and schema required by your module
    $query = "CREATE TABLE `review_responses_ticket_status_log` (`id` INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
													   		   `adminid` INT( 10 ) NOT NULL, 
													   		   `status` TEXT NOT NULL , 
													   		   `ticketid` INT( 10 ) NOT NULL, 
															   `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00', 
															   `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'  ) 
															   ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
    full_query($query);


    // Create custom tables and schema required by your module
    $query = "CREATE TABLE `review_responses_ticket_status_request` (`id` INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
													   		   		 `adminid` INT( 10 ) NOT NULL, 
													   		   		 `status` TEXT NOT NULL , 
													   		   		 `ticketid` INT( 10 ) NOT NULL, 
													   		   		 `approved` INT( 1 ) NOT NULL DEFAULT 0, 
													   		   		 `review_responses_id` INT( 10 ) NOT NULL DEFAULT 0, 
															   		 `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00', 
															   		 `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'  ) 
															   		  ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
    full_query($query);

    return array(
        'status' => 'success', // Supported values here include: success, error or info
        'description' => '',
    );
}

/**
 * Deactivate.
 *
 * Called upon deactivation of the module.
 * Use this function to undo any database and schema modifications
 * performed by your module.
 *
 * This function is optional.
 *
 * @return array Optional success/failure message
 */
function review_responses_deactivate()
{
    // Undo any database and schema modifications made by your module here
    $query = "DROP TABLE `review_responses`";
    full_query($query);

    $query = "DROP TABLE `review_responses_replies`";
    full_query($query);

    $query = "DROP TABLE `review_responses_ticket_status_log`";
    full_query($query);

    $query = "DROP TABLE `review_responses_ticket_status_request`";
    full_query($query);


    return array(
        'status' => 'success', // Supported values here include: success, error or info
        'description' => '',
    );
}

/**
 * Upgrade.
 *
 * Called the first time the module is accessed following an update.
 * Use this function to perform any required database and schema modifications.
 *
 * This function is optional.
 *
 * @return void
 */
function review_responses_upgrade($vars)
{
    /*$currentlyInstalledVersion = $vars['version'];

    /// Perform SQL schema changes required by the upgrade to version 1.1 of your module
    if ($currentlyInstalledVersion < 1.1) {
        $query = "ALTER `mod_addonexample` ADD `demo2` TEXT NOT NULL ";
        full_query($query);
    }

    /// Perform SQL schema changes required by the upgrade to version 1.2 of your module
    if ($currentlyInstalledVersion < 1.2) {
        $query = "ALTER `mod_addonexample` ADD `demo3` TEXT NOT NULL ";
        full_query($query);
    }*/
}

/**
 * Admin Area Output.
 *
 * Called when the addon module is accessed via the admin area.
 * Should return HTML output for display to the admin user.
 *
 * This function is optional.
 *
 * @see AddonModule\Admin\Controller@index
 *
 * @return string
 */
function review_responses_output($vars)
{
    // Get common module parameters
    $modulelink = $vars['modulelink']; // eg. addonmodules.php?module=addonmodule
    $version = $vars['version']; // eg. 1.0
    $_lang = $vars['_lang']; // an array of the currently loaded language variables

    // Get module configuration parameters
   /* $configTextField = $vars['Text Field Name'];
    $configPasswordField = $vars['Password Field Name'];
    $configCheckboxField = $vars['Checkbox Field Name'];
    $configDropdownField = $vars['Dropdown Field Name'];
    $configRadioField = $vars['Radio Field Name'];
    $configTextareaField = $vars['Textarea Field Name'];*/

    // Dispatch and handle request here. What follows is a demonstration of one
    // possible way of handling this using a very basic dispatcher implementation.

    $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

    $dispatcher = new AdminDispatcher();
    $response = $dispatcher->dispatch($action, $vars);
    echo $response;
}

/**
 * Admin Area Sidebar Output.
 *
 * Used to render output in the admin area sidebar.
 * This function is optional.
 *
 * @param array $vars
 *
 * @return string
 */
function review_responses_sidebar($vars)
{
    // Get common module parameters
    /*$modulelink = $vars['modulelink'];
    $version = $vars['version'];
    $_lang = $vars['_lang'];

    // Get module configuration parameters
    $configTextField = $vars['Text Field Name'];
    $configPasswordField = $vars['Password Field Name'];
    $configCheckboxField = $vars['Checkbox Field Name'];
    $configDropdownField = $vars['Dropdown Field Name'];
    $configRadioField = $vars['Radio Field Name'];
    $configTextareaField = $vars['Textarea Field Name'];

    $sidebar = '<p>Sidebar output HTML goes here</p>';
    return $sidebar;*/
}

/**
 * Client Area Output.
 *
 * Called when the addon module is accessed via the client area.
 * Should return an array of output parameters.
 *
 * This function is optional.
 *
 * @see AddonModule\Client\Controller@index
 *
 * @return array
 */
function review_responses_clientarea($vars)
{
    // Get common module parameters
    $modulelink = $vars['modulelink']; // eg. index.php?m=addonmodule
    $version = $vars['version']; // eg. 1.0
    $_lang = $vars['_lang']; // an array of the currently loaded language variables

    // Get module configuration parameters
    /*$configTextField = $vars['Text Field Name'];
    $configPasswordField = $vars['Password Field Name'];
    $configCheckboxField = $vars['Checkbox Field Name'];
    $configDropdownField = $vars['Dropdown Field Name'];
    $configRadioField = $vars['Radio Field Name'];
    $configTextareaField = $vars['Textarea Field Name'];

    // Dispatch and handle request here. What follows is a demonstration of one
    // possible way of handling this using a very basic dispatcher implementation.
	*/
    $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

    $dispatcher = new ClientDispatcher();
    return $dispatcher->dispatch($action, $vars);
}
