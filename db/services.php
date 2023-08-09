<?php

/**
 * Web service local plugin template external functions and service definitions.
 *
 * @package    localwstemplate
 * @copyright  2017 Niels Seidel
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


$functions = array( 
        'format_serial2_analytics' => array(
                'classname'   => 'format_serial2_external',
                'methodname'  => 'analytics',
                'classpath'   => 'course/format/serial2/api.php',
                'description' => 'Obtain the plugin name',
                'type'        => 'read',
                'ajax'        => true 
        ),
        'format_serial2_completionprogress' => array(
                'classname'   => 'format_serial2_external',
                'methodname'  => 'completionProgress',
                'classpath'   => 'course/format/serial2/api.php',
                'description' => 'Obtain the plugin name',
                'type'        => 'read',
                'ajax'        => true 
        ),
        'format_serial2_get_surveys' => array(
                'classname'   => 'format_serial2_external',
                'methodname'  => 'get_surveys',
                'classpath'   => 'course/format/serial2/api.php',
                'description' => 'Obtain the plugin name',
                'type'        => 'read',
                'ajax'        => true 
        ),
        'format_serial2_get_goal' => array(
                'classname'   => 'format_serial2_external',
                'methodname'  => 'get_goal',
                'classpath'   => 'course/format/serial2/api.php',
                'description' => 'Obtain the plugin name',
                'type'        => 'read',
                'ajax'        => true 
        ),
        'format_serial2_overview' => array(
                'classname'   => 'format_serial2_external',
                'methodname'  => 'overview',
                'classpath'   => 'course/format/serial2/api.php',
                'description' => 'Obtain the plugin name',
                'type'        => 'read',
                'ajax'        => true 
        ),
        'format_serial2_reflectionread' => array(
                'classname'   => 'format_serial2_external',
                'methodname'  => 'reflectionRead',
                'classpath'   => 'course/format/serial2/api.php',
                'description' => 'Obtain the plugin name',
                'type'        => 'read',
                'ajax'        => true 
        ),
        'format_serial2_reflectioncreate' => array(
                'classname'   => 'format_serial2_external',
                'methodname'  => 'reflectionCreate',
                'classpath'   => 'course/format/serial2/api.php',
                'description' => 'Obtain the plugin name',
                'type'        => 'read',
                'ajax'        => true 
        ),
                
        'format_serial2_limesurvey' => array(
                'classname'   => 'format_serial2_external',
                'methodname'  => 'limesurvey',
                'classpath'   => 'course/format/serial2/api.php',
                'description' => 'xxx',
                'type'        => 'read',
                'ajax'        => true 
        ),
        'format_serial2_statistics' => array(
                'classname'   => 'format_serial2_external',
                'methodname'  => 'statistics',
                'classpath'   => 'course/format/serial2/api.php',
                'description' => 'Obtain the plugin name',
                'type'        => 'read',
                'ajax'        => true 
        ),
        'format_serial2_notification' => array(
                'classname'   => 'format_serial2_external',
                'methodname'  => 'notification',
                'classpath'   => 'course/format/serial2/api.php',
                'description' => 'Obtain the plugin name',
                'type'        => 'read',
                'ajax'        => true 
        ),
        'format_serial2_sendmail' => array(
                'classname'   => 'format_serial2_external',
                'methodname'  => 'sendmail',
                'classpath'   => 'course/format/serial2/api.php',
                'description' => 'Obtain the plugin name',
                'type'        => 'read',
                'ajax'        => true 
        ),
        'format_serial2_getalluser' => array(
                'classname'   => 'format_serial2_external',
                'methodname'  => 'getalluser',
                'classpath'   => 'course/format/serial2/api.php',
                'description' => 'Obtain the plugin name',
                'type'        => 'read',
                'ajax'        => true 
        ),
        'format_serial2_name' => array(
                'classname'   => 'format_serial2_external',
                'methodname'  => 'name',
                'classpath'   => 'course/format/serial2/api.php',
                'description' => 'Obtain the plugin name',
                'type'        => 'read',
                'ajax'        => true 
        ),
        'format_serial2_logstore' => array(
                'classname'   => 'format_serial2_external',
                'methodname'  => 'logstore',
                'classpath'   => 'course/format/serial2/api.php',
                'description' => 'Obtain calendar events from database',
                'type'        => 'read',
                'ajax'        => true 
        ),        
        'format_serial2_getcalendar' => array(
                'classname'   => 'format_serial2_external',
                'methodname'  => 'getcalendar',
                'classpath'   => 'course/format/serial2/api.php',
                'description' => 'Get the calendar data from moodle native calendar',
                'type'        => 'read',
                'ajax'        => true 
        ),
        'format_serial2_coursestructure' => array(
                'classname'   => 'format_serial2_external',
                'methodname'  => 'coursestructure',
                'classpath'   => 'course/format/serial2/api.php',
                'description' => 'Obtain course structure from database',
                'type'        => 'read',
                'ajax'        => true 
        ),
        'format_serial2_logger' => array(
                'classname'   => 'format_serial2_external',
                'methodname'  => 'logger',
                'classpath'   => 'course/format/serial2/api.php',
                'description' => 'Obtain logger date from database',
                'type'        => 'write',
                'ajax'        => true,
                'capabilities'  => 'format/serial2:view', 
        ),
        'format_serial2_updateuser' => array(
                'classname'   => 'format_serial2_external',
                'methodname'  => 'updateuser',
                'classpath'   => 'course/format/serial2/api.php',
                'description' => 'Obtain logger date from database',
                'type'        => 'write',
                'ajax'        => true
        ),
        'format_serial2_getmilestones' => array(
                'classname'   => 'format_serial2_external',
                'methodname'  => 'getmilestones',
                'classpath'   => 'course/format/serial2/api.php',
                'description' => 'Obtain mielstones from database',
                'type'        => 'read',
                'ajax'        => true
        ),        
        'format_serial2_setmilestones' => array(
                'classname'   => 'format_serial2_external',
                'methodname'  => 'setmilestones',
                'classpath'   => 'course/format/serial2/api.php',
                'description' => 'Save mielstones to database',
                'type'        => 'write',
                //'capabilities'  => 'format/serial2:view',
                'ajax'        => true
        ),
        'format_serial2_getmilestoneplan' => array(
                'classname'   => 'format_serial2_external',
                'methodname'  => 'getmilestoneplan',
                'classpath'   => 'course/format/serial2/api.php',
                'description' => 'Obtain mielstones from database',
                'type'        => 'read',
                'ajax'        => true
        ),
        'format_serial2_setmilestoneplan' => array(
                'classname'   => 'format_serial2_external',
                'methodname'  => 'setmilestoneplan',
                'classpath'   => 'course/format/serial2/api.php',
                'description' => 'Obtain mielstones from database',
                'type'        => 'read',
                'ajax'        => true
        ),
        'format_serial2_userpreferences' => array(
                'classname'   => 'format_serial2_external',
                'methodname'  => 'userpreferences',
                'classpath'   => 'course/format/serial2/api.php',
                'description' => 'Set and get user pref',
                'type'        => 'write',
                'ajax'        => true
        ),
        'format_serial2_policyacceptance' => array(
                'classname'   => 'format_serial2_external',
                'methodname'  => 'policyacceptance',
                'classpath'   => 'course/format/serial2/api.php',
                'description' => 'Get polics acceptance',
                'type'        => 'write',
                'ajax'        => true
        )

);