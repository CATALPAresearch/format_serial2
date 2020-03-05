<?php

defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir . '/externallib.php');

function get_meta($courseID){
    try{
        global $USER, $COURSE;            
        $obj = new stdClass();
        $obj->course = new stdClass();
        $obj->course->id = (int)$courseID;            
        require_login($obj->course->id);
        $obj->course->context = context_course::instance($obj->course->id);
        $obj->course->global = $COURSE;
        $obj->user = new stdClass();
        $obj->user->id = $USER->id;
        $obj->user->loggedin = isloggedin();
        $obj->user->siteadmin = is_siteadmin($USER->id);
        $obj->user->enrolled = is_enrolled($obj->course->context, $USER->id);
        $obj->user->guest = is_guest($obj->course->context, $USER->id);
        $obj->user->viewing = is_viewing($obj->course->context, $USER->id);
        $obj->user->roles = array();
        $obj->user->global = $USER;       
        $roles = get_user_roles($obj->course->context, $USER->id);
        $obj->user->roles_raw = $roles;   
        $obj->user->student = false;
        $obj->user->teacher = false;    
        $obj->user->editingteacher = false;
        $obj->user->coursecreator = false;  
        $obj->user->manager = false; 
        foreach($roles as $key => $value){
            if(isset($value->shortname)){      
                switch($value->shortname){                    
                    case 'teacher':         $obj->user->teacher = true;
                                            break;
                    case 'editingteacher':  $obj->user->editingteacher = true;
                                            break;
                    case 'coursecreator':   $obj->user->coursecreator = true;
                                            break;
                    case 'manager':         $obj->user->manager = true;
                                            break;
                    case 'student':         $obj->user->student = true;
                                            break;
                }         
                $obj->user->roles[] = $value->shortname;
            }
        }     
        return $obj;    
    } catch(Exception $ex){
        return null;
    }
}

class format_ladtopics_external extends external_api { 

    public static function statistics_parameters(){
        return new external_function_parameters(
            array(
                'courseid' => new external_value(PARAM_INT, 'course id')
            )
        );
    }

    public static function statistics($courseid){
        global $CFG, $DB, $USER;
        $out = array();
        try{
            if(is_null($courseid)) throw new Exception("No course specified");            
            $context = get_meta($courseid);
            if($context->user->loggedin === false || $context->user->manager === false) throw new Exception("No Admin");
            $users = get_enrolled_users($context->course->context);
            $num_users = count_enrolled_users($context->course->context);
            $out['users'] = $users;
            $out['num_users'] = $num_users;
        } catch(Exception $ex){
            $out['debug'] = $ex->getMessage();
        }
        return array('data' => json_encode($out));
    }

    public static function statistics_is_allowed_from_ajax(){
        return true;
    }

    public static function statistics_returns(){
        return new external_single_structure(
            array(
                'data' => new external_value(PARAM_RAW, 'data')
            )
        );
    }  

    public static function notification_parameters(){
        return new external_function_parameters(
            array(              
                'courseid' => new external_value(PARAM_INT, 'course id'),
                'subject' => new external_value(PARAM_TEXT, 'course id'),
                'short' => new external_value(PARAM_TEXT, 'course id'),
                'text' => new external_value(PARAM_TEXT, 'course id')
            )
        );
    }

    public static function notification($courseid, $subject, $short, $text){
        global $CFG, $DB, $USER;
        $out = array();
        try{  
            if(is_null($courseid) || is_null($subject) || is_null($text)) throw new Exception("Invalid Parameter");           
            $meta = get_meta($courseid);
            $message = new \core\message\message();           
            $message->component = 'moodle';
            $message->name = 'instantmessage';
            $message->userfrom = $USER;
            $message->userto = $USER;
            $message->subject = $subject;
            //$message->fullmessage = $text;
            $message->fullmessageformat = FORMAT_MARKDOWN;
            $message->fullmessagehtml = $text;
            $message->smallmessage = $short;
            $message->notification = "0";
            //$message->contexturl = 'http://GalaxyFarFarAway.com';
            //$message->contexturlname = 'Context name';
            //$message->replyto = "random@example.com";      
            $message->courseid = $courseid; 
            $result = message_send($message);            
            $out['result'] = $result;        
        } catch(Exception $ex){
            $out['debug'] = $ex->getMessage();
        }
        return array('data' => json_encode($out));
    }

    public static function notification_is_allowed_from_ajax(){
        return true;
    }

    public static function notification_returns(){
        return new external_single_structure(
            array(
                'data' => new external_value(PARAM_RAW, 'data')
            )
        );
    }  
    
    // sss

    public static function sendmail_parameters(){
        return new external_function_parameters(
            array(              
                'courseid' => new external_value(PARAM_INT, 'course id'),
                'subject' => new external_value(PARAM_TEXT, 'course id'),
                'text' => new external_value(PARAM_TEXT, 'course id')
            )
        );
    }

    public static function sendmail($courseid, $subject, $message){
        global $CFG, $DB, $USER;
        $out = array();
        try{         
            if(is_null($courseid) || is_null($subject) || is_null($message)) throw new Exception("Missing Parameter");    
            $meta = get_meta($courseid); 
            $out['result'] = email_to_user($USER, $USER, $subject, $message, "", "", "", true);           
        } catch(Exception $ex){
            $out['debug'] = $ex->getMessage();
        }
        return array('data' => json_encode($out));
    }

    public static function sendmail_is_allowed_from_ajax(){
        return true;
    }

    public static function sendmail_returns(){
        return new external_single_structure(
            array(
                'data' => new external_value(PARAM_RAW, 'data')
            )
        );
    }     
    
    public static function getalluser_parameters(){
        return new external_function_parameters(
            array(
                'courseid' => new external_value(PARAM_INT, 'course id')
            )
        );
    }

    public static function getalluser($param){
        $out = array();
        try{
            if(is_null($param)) throw new Exception("No courseid");
            $context = get_meta((int)$param);
            if($context->user->loggedin === false || $context->user->manager === false) throw new Exception("No Admin");
            $enrolled = get_enrolled_users($context->course->context);
            $array = array();
            foreach($enrolled as $key=>$value){
                if(!isset($value->id)) continue;
                $user = new stdClass();
                $user->id = $value->id;               
                if(isset($value->username) && strlen($value->username) > 0) {
                    $username = ucfirst(strtolower($value->username));
                    $user->username = $username;                      
                    // Admin; marc.burchart@tu
                }
                if(isset($value->lastname) && strlen($value->lastname) > 0) {
                    $name = ucfirst(strtolower($value->lastname));                    
                    if(isset($value->middlename) && strlen($value->middlename) > 0) $name = ucfirst(strtolower($value->moddlename))." ".$name; 
                    if(isset($value->firstname) && strlen($value->firstname) > 0) $name = ucfirst(strtolower($value->firstname))." ".$name;
                    $user->name = $name;                   
                }
                if(isset($value->email) && strlen($value->email) > 0) {
                    $email = strtolower($value->email);
                    $user->email = $email;                   
                }                             
                $array[] = $user;
            }            
            $out['user'] = $array;
        } catch(Exception $ex){
            $out['debug'] = $ex->getMessage();
        }
        return array('data' => json_encode($out));
    }

    public static function getalluser_is_allowed_from_ajax(){
        return true;
    }

    public static function getalluser_returns(){
        return new external_single_structure(
            array(
                'data' => new external_value(PARAM_RAW, 'data')
            )
        );
    }


    /**
     * Obtain plugin name
     */
    public static function name_parameters() {
        //  VALUE_REQUIRED, VALUE_OPTIONAL, or VALUE_DEFAULT. If not mentioned, a value is VALUE_REQUIRED 
        return new external_function_parameters(
            array('courseid' => new external_value(PARAM_INT, 'id of course', VALUE_OPTIONAL))
        );
    }
    
    public static function name_is_allowed_from_ajax() { return true; }

    public static function name_returns() {
        return new external_single_structure(
                array(
                    'data' => new external_value(PARAM_TEXT, 'Plugin name')
                )
        );
    }
    public static function name($data) {
        return array(
            'data' => 'LAD Topics Format'
        );
    }

    /**
     * Update user
     */
    public static function updateuser_parameters() {
        //  VALUE_REQUIRED, VALUE_OPTIONAL, or VALUE_DEFAULT. If not mentioned, a value is VALUE_REQUIRED 
        return new external_function_parameters(
            array(
                'data' => new external_value(PARAM_RAW, 'id of course')                              
            )
        );
    }

    public static function updateuser($data){
        global $CFG, $DB, $USER;
        $out = array();
        try{      
            if(is_null($data)) throw new Exception("Keine Daten erhalten.");
            $data = json_decode($data);
            if(!is_int($data->courseid)) throw new Exception("Keine Kurse-ID");    
            $userid = $meta->user->id;        
            $meta = get_meta($data->courseid);            
            if(is_null($meta)) throw new Exception("Keine Meta-Daten erhalten");            
            if($meta->user->loggedin === true && $meta->user->manager === true){
                if(is_int($data->userid)) $userid = $data->userid;
            }
            $out['data'] = $data;
            if(!is_null($data->milestones)){                
                $date = new DateTime();
                $r = new stdClass();
                $r->userid=(int)$userid;
                $r->course=(int)$data->courseid;
                $r->milestones=$data->milestones;
                $r->settings=[];
                $r->timemodified=(int)$date->getTimestamp();        
                $transaction = $DB->start_delegated_transaction();
                $res = $DB->insert_record("ladtopics_milestones", $r);
                $transaction->allow_commit();
                $out['milestones'] = $data->milestones;
            }          
            if(!is_null($data->plan)){               
                function func($field, $courseid, $userid, $value){
                    global $CFG, $DB, $USER;
                    $r = new stdClass();
                    $r->userid = $userid;
                    $r->name = $field . '-course-' . (int)$courseid;
                    $exists = $DB->record_exists('user_preferences', array(
                        'name' => $field . '-course-' . (int)$courseid, 
                        'userid'=>$userid
                    ));
                    $res='nix';
                    if($exists != true){
                        $r->value=$value == NULL ? 0 : $value;
                        $transaction = $DB->start_delegated_transaction();
                        $res = $DB->insert_records("user_preferences", array($r));
                        $transaction->allow_commit(); 
                    } elseif($exists == true){                       
                        $transaction = $DB->start_delegated_transaction();                       
                        $res = $DB->set_field("user_preferences", 'value', $value, array(
                            'userid' => $userid,
                            'name' => $field . '-course-' . $courseid
                        ));                       
                        $transaction->allow_commit();                        
                    }     
                }                
                func("ladtopics_survey_results",(int)$data->courseid,(int)$userid,$data->plan);
                func("ladtopics_survey_done",(int)$data->courseid,(int)$userid, 0);
            }            
        } catch(Exception $ex){
            $out['debug'] = $ex->getMessage();
        }
        return array('data' => json_encode($out));
    }

    public static function updateuser_returns() {
        return new external_single_structure(
                array(
                    'data' => new external_value(PARAM_RAW, 'data')
                )
        );
    }    

    public static function updateuser_is_allowed_from_ajax() { return true; }
     
   
    /**
     * Get calendar data
     */

    public static function getcalendar_parameters() {        
        return new external_function_parameters(
            array(
                'courseid' => new external_value(PARAM_INT, 'course id')
            )
        );
    }
    public static function getcalendar_returns() {
        return new external_single_structure(
                array(
                    'data' => new external_value(PARAM_RAW, 'data')
                )
        );
    }    
    public static function getcalendar($data) {
        global $CFG, $DB, $USER;
        $transaction = $DB->start_delegated_transaction();        
        $cid = (int)$data;
        $uid = (int)$USER->id;
        $sql = '
            SELECT * FROM '.$CFG->prefix.'event
            WHERE ('.$CFG->prefix.'event.eventtype = \'site\') 
            OR ('.$CFG->prefix.'event.eventtype = \'user\' AND '.$CFG->prefix.'event.userid = '.$uid.')
            OR ('.$CFG->prefix.'event.eventtype = \'group\'
                AND '.$CFG->prefix.'event.courseid = '.$cid.'
                AND '.$CFG->prefix.'event.groupid in 
                (SELECT '.$CFG->prefix.'groups.id 
                    FROM '.$CFG->prefix.'groups
                    INNER JOIN '.$CFG->prefix.'groups_members
                    ON '.$CFG->prefix.'groups.id = '.$CFG->prefix.'groups_members.groupid
                WHERE '.$CFG->prefix.'groups_members.userid = '.$uid.')
            )
            OR ('.$CFG->prefix.'event.eventtype = \'course\' AND '.$CFG->prefix.'event.courseid = '.$cid.')
            OR ('.$CFG->prefix.'event.eventtype = \'category\' AND '.$CFG->prefix.'event.categoryid in
   		        (SELECT '.$CFG->prefix.'course_categories.id
                    FROM '.$CFG->prefix.'course_categories
                    INNER JOIN '.$CFG->prefix.'course
                    ON '.$CFG->prefix.'course_categories.id = '.$CFG->prefix.'course.category
                WHERE '.$CFG->prefix.'course.id = '.$cid.')
            )
            ORDER BY '.$CFG->prefix.'event.timestart ASC';                
        $data = $DB->get_records_sql($sql);
        $transaction->allow_commit();           
        return array('data'=>json_encode($data));
    }
    public static function getcalendar_is_allowed_from_ajax() { return true; }


    /*
     * Get logstore data
     **/    
     public static function logstore_parameters() {
        //  VALUE_REQUIRED, VALUE_OPTIONAL, or VALUE_DEFAULT. If not mentioned, a value is VALUE_REQUIRED 
        return new external_function_parameters(
            array(
                'courseid' => new external_value(PARAM_INT, 'course id')
            )
        );
    }
    public static function logstore_returns() {
        return new external_single_structure(
                array(
                    'data' => new external_value(PARAM_RAW, 'data'),
                    'user' => new external_value(PARAM_RAW, 'data')
                )
        );
    }
    public static function logstore($data) {
        global $CFG, $DB, $USER;

        $transaction = $DB->start_delegated_transaction(); 
        $query ='SELECT * FROM ' . $CFG->prefix . 'logstore_standard_log 
            WHERE userid=' . $USER->id . ' AND 
        ( 
            component=\'mod_glossary\' OR 
            component=\'mod_forum\' OR
            component=\'mod_wiki\' OR
            component=\'mod_studentquiz\' OR
            component=\'mod_assignment\' OR
            component=\'mod_quiz\'
        );';
        $data = $DB->get_records_sql($query);
        $transaction->allow_commit();
        $arr=array();
        foreach($data as $bu){
            $entry = array(
                'utc' => $bu->timecreated,
                'action_type' => $bu->component,
                'action'=> $bu->action//,
                //'data' => json_encode($bu),
            );
            array_push($arr, $entry);
        }
        // log cleaning

        //
        $user_data = array(
            'username' => $USER->username,
            'firstname' =>  $USER->firstname,
            'lastname' =>  $USER->lastname,
            'userid' =>  $USER->id
        );
        
        return array('data'=>json_encode($arr), 'user'=>json_encode($user_data));
    }
    public static function logstore_is_allowed_from_ajax() { return true; }
    

    /*
     * Get course structure
     **/    
     

     /*
  public static function logger_parameters() {
        return new external_function_parameters(                
            array(
                'data' => 
                    new external_single_structure(
                        array(
                        'courseid' => new external_value(PARAM_INT, 'id of course', VALUE_OPTIONAL),
                        'utc' => new external_value(PARAM_INT, 'utc time', VALUE_OPTIONAL),
                        'action' => new external_value(PARAM_TEXT, 'action', VALUE_OPTIONAL),
                        'entry' => new external_value(PARAM_RAW, 'log data', VALUE_OPTIONAL)
                    )
                )
            )
        );
    }
     */
     
     public static function coursestructure_parameters() {
        //  VALUE_REQUIRED, VALUE_OPTIONAL, or VALUE_DEFAULT. If not mentioned, a value is VALUE_REQUIRED 
        return new external_function_parameters(
            array(
                'courseid' => new external_value(PARAM_INT, 'course id'),
                'select' => 
                new external_single_structure(
                    array(
                        'modules' => new external_value(PARAM_RAW, 'modules'),
                        'sectionid' => new external_value(PARAM_INT, 'section id', VALUE_OPTIONAL),
                        'moduleid' => new external_value(PARAM_INT, 'module id', VALUE_OPTIONAL)   
                    )
                , 'select special items'
                )                              
            )
        );
    }

    public static function coursestructure_returns() {
        return new external_single_structure(
            array(
                'data' => new external_value(PARAM_RAW, 'data'),
                'debug' => new external_value(PARAM_RAW, 'debug')
                //,'user' => new external_value(PARAM_RAW, 'data')
            )
        );
    }


    public static function coursestructure($courseid, $select) {
        global $CFG, $DB, $USER;
        
        $out_data = array();        
        $out_debug = array();          

        // all allowed modules
        $allowed_modules = array("assign", "data", "hvp", "checklist", 
        "url", "studentquiz", "page", "feedback", "forum", "resource",
        "glossary", "quiz");

        if(is_array($select)){
            $addToQuery = "";                               
            $modules = json_decode($select["modules"]);
            foreach($modules as $value){
                if(in_array($value, $allowed_modules)){
                    $activityId = 0;
                    $params = array();
                    $params[] = (int)$courseid;
                    $params[] = (int)$courseid;
                    $params[] = (int)$courseid;
                    $params[] = $value;
                    $query = '
                        SELECT 
                        cm.instance AS instance_id,     
                        m.name AS instance_type, 
                        m.visible AS instance_visible,
                        f.name AS instance_title,
                        cm.id AS instance_url_id,
                        cm.course AS course_id, 
                        cm.module AS module_id, 
                        cm.section AS section_id, 
                        cs.name AS section_name,
                        cs.sequence AS section_sequence,
                        cs.section AS section_pos
                        FROM '.$CFG->prefix.'course_modules AS cm
                        JOIN '.$CFG->prefix.'modules AS m 
                        ON m.id = cm.module
                        JOIN '.$CFG->prefix.'course_sections AS cs 
                        ON cs.id = cm.section
                        RIGHT OUTER JOIN '.$CFG->prefix.$value.' AS f
                        ON cm.instance = f.id 
                        WHERE cm.course = ? AND cs.course = ? AND f.course = ? AND m.name = ?
                    ';
                    if(isset($select["sectionid"]) && !is_null($select["sectionid"])){
                        $query .= ' AND cs.id = ?';
                        $params[] = (int)$select["sectionid"];
                    }
                    if(isset($select["moduleid"]) && !is_null($select["moduleid"])){
                        $query .= ' AND cm.id = ?';
                        $params[] = (int)$select["moduleid"];
                    }
                    $transaction = $DB->start_delegated_transaction();
                    $res = $DB->get_records_sql($query, $params); 
                    $transaction->allow_commit();
                    foreach($res as $entry){   
                        $pos = -1;
                        if(gettype($entry->section_sequence) === "string" && sizeof($entry->section_sequence) > 0){
                            $sequence = explode(",", preg_replace("/[^0-9,]/", "", $entry->section_sequence));                            
                            $pos = array_search(strval($entry->instance_url_id), $sequence);                                              
                        }                  
                        $out = array(
                            'id' => $activityId++,
                            'course_id' => $entry->course_id,
                            'module_id' => $entry->module_id, 
                            'section_id' => $entry->section_id, 
                            'section_name' => $entry->section_name,
                            'instance_id' => $entry->instance_id,
                            'instance_url_id' => $entry->instance_url_id, 
                            'instance_type' => $entry->instance_type, 
                            'instance_title' => $entry->instance_title,
                            'section' => $entry->section_id, 
                            'name' => $entry->instance_title,
                            'pos_module' => $pos,
                            'pos_section' => $entry->section_pos
                        );                                                                        
                        array_push($out_data, $out);               
                    }                           
                }
            }
        }
        return array('data'=>json_encode($out_data), 'debug'=>json_encode($out_debug));        
    }

    public static function coursestructure_is_allowed_from_ajax() { return true; }

    /**
     * Collects log data from the client
     */
    public static function logger_parameters() {
        return new external_function_parameters(                
            array(
                'data' => 
                    new external_single_structure(
                        array(
                        'courseid' => new external_value(PARAM_INT, 'id of course', VALUE_OPTIONAL),
                        'utc' => new external_value(PARAM_INT, 'utc time', VALUE_OPTIONAL),
                        'action' => new external_value(PARAM_TEXT, 'action', VALUE_OPTIONAL),
                        'entry' => new external_value(PARAM_RAW, 'log data', VALUE_OPTIONAL)
                    )
                )
            )
        );
    }
    public static function logger_returns() {
        return new external_single_structure(
                array( 'response' => new external_value(PARAM_RAW, 'Server respons to the incomming log') )
        );
    }
    public static function logger($data) {
        global $CFG, $DB, $USER;
        
        $r = new stdClass();
        $r->name='format_ladtopics';
        $r->component='format_ladtopics';
        $r->eventname='\format_ladtopics\event\\' . $data['action'];
        $r->action=$data['action'];
        $r->target='course_format';
        $r->objecttable='ladtopics';
        $r->objectid=0;
        $r->crud='r';
        $r->edulevel=2;
        $r->contextid=120;
        $r->contextlevel=70;
        $r->contextinstanceid=86;
        $r->userid=$USER->id; 
        $r->courseid=(int)$data['courseid'];
        //$r->relateduserid=NULL;
        $r->anonymous=0;
        $r->other=$data['entry'];	 
        $r->timecreated=$data['utc'];
        $r->origin='web';	 
        $r->ip=$_SERVER['REMOTE_ADDR'];
        //$r->realuserid=NULL;
        
        $transaction = $DB->start_delegated_transaction();
        $res = $DB->insert_records("logstore_standard_log", array($r)); 
        $transaction->allow_commit();
        
        return array('response'=> json_encode('hello'));
    } 
    public static function logger_is_allowed_from_ajax() { return true; }

    

    /**
     * Dump log data from the client
     */
    public static function dumplog_parameters() {
        return new external_function_parameters(                
            array(
                'data' => 
                    new external_single_structure(
                        array(
                        'courseid' => new external_value(PARAM_INT, 'id of course', VALUE_OPTIONAL),
                        'userid' => new external_value(PARAM_INT, 'id of course', VALUE_OPTIONAL)
                    )
                )
            )
        );
    }
    public static function dumplog_returns() {
        return new external_single_structure(
                array( 'response' => new external_value(PARAM_RAW, 'Server respons to the incomming log') )
        );
    }
    public static function dumplog($data) {
        global $CFG, $DB, $USER;
        
        $transaction = $DB->start_delegated_transaction(); 
        $query ='SELECT * FROM ' . $CFG->prefix . 'logstore_standard_log 
            WHERE userid=' . $USER->id . ' AND 
            ( 
                component=\'mod_glossary\' OR 
                component=\'mod_forum\' OR
                component=\'mod_wiki\' OR
                component=\'mod_studentquiz\' OR
                component=\'mod_assignment\' OR
                component=\'mod_quiz\'
            );';
        $data = $DB->get_records_sql($query);
        $transaction->allow_commit();
        $arr=array();
        foreach($data as $bu){
            $entry = array(
                'utc' => $bu->timecreated,
                'action_type' => $bu->component,
                'action'=> $bu->action//,
                //'data' => json_encode($bu),
            );
            array_push($arr, $entry);
        }
        // log cleaning

        
        return array('response'=>json_encode($arr));
    } 
    public static function dumplog_is_allowed_from_ajax() { return true; }




    /**
     * Get milestones of a user
     */

    public static function getmilestones_parameters() {
        return new external_function_parameters(                
            array(
                'data' => 
                    new external_single_structure(
                        array(
                        'courseid' => new external_value(PARAM_INT, 'id of course', VALUE_OPTIONAL)
                        //'userid' => new external_value(PARAM_INT, 'utc time', VALUE_OPTIONAL)
                    )
                )
            )
        );
    }
    public static function getmilestones_returns() {
        return new external_single_structure(
                array( 'milestones' => new external_value(PARAM_RAW, 'Server respons to the incomming log') )
        );
    }
    public static function getmilestones($data) {
        global $CFG, $DB, $USER;
        (int)$data['userid'] = $USER->id;
        $transaction = $DB->start_delegated_transaction(); 
        $sql='
            SELECT t.milestones, t.settings, t.timemodified 
            FROM '.$CFG->prefix.'ladtopics_milestones AS t
            WHERE   
                t.course = ' . $data['courseid'] . ' 
                AND t.userid = ' . (int)$data['userid'] . '
            ORDER BY t.timemodified DESC
            LIMIT 1
            ;';
        $res = $DB->get_record_sql($sql);
        $transaction->allow_commit();

        return array('milestones'=> json_encode(array(
            'settings'=>$res->settings,
            'milestones'=>$res->milestones,
            'utc'=>$res->timemodified
        )));
    } 
    public static function getmilestones_is_allowed_from_ajax() { return true; }



    /**
     * Set milestones of a user
     */
    public static function setmilestones_parameters() {
        return new external_function_parameters(                
            array(
                'data' => 
                    new external_single_structure(
                        array(
                        'courseid' => new external_value(PARAM_INT, 'id of course', VALUE_OPTIONAL),
                        //'userid' => new external_value(PARAM_INT, 'user id', VALUE_OPTIONAL),
                        'milestones' => new external_value(PARAM_RAW, 'milestones', VALUE_OPTIONAL),
                        'settings' => new external_value(PARAM_RAW, 'settings', VALUE_OPTIONAL)
                    )
                )
            )
        );
    }
    public static function setmilestones_returns() {
        return new external_single_structure(
            array( 'response' => new external_value(PARAM_RAW, 'Server respons to the incomming log') )
        );
    }

    public static function setmilestones($data) {
        global $CFG, $DB, $USER;

        $date = new DateTime();
        $data['userid'] = (int)$USER->id;
        
        $r = new stdClass();
        $r->userid=(int)$data['userid'];
        $r->course=(int)$data['courseid'];
        $r->milestones=$data['milestones'];
        $r->settings=$data['settings'];
        $r->timemodified=(int)$date->getTimestamp();

        $transaction = $DB->start_delegated_transaction();
        $res = $DB->insert_records("ladtopics_milestones", array($r));
        $sql = '
            INSERT INTO '. $CFG->prefix .'ladtopics_milestones (user,course,milestones,settings,timemodified) 
            VALUES (' . (int)$data['userid'] . ',' . (int)$data['courseid'] . ',\'' . $data['milestones'] . '\',\'' . $data['settings']. '\',' . (int)$date->getTimestamp() . ')
            ;';
        //$res = $DB->execute($sql);
        $transaction->allow_commit();

        return array('response'=> json_encode( array($res, $data) ));
    } 
    public static function setmilestones_is_allowed_from_ajax() { return true; }

    /**
     * Get Milestone Plan
     */
    public static function getmilestoneplan_parameters() {
        return new external_function_parameters(                
            array(
                'data' => 
                    new external_single_structure(
                        array(
                            'courseid' => new external_value(PARAM_INT, 'id of course', VALUE_OPTIONAL),
                            'plan' => new external_value(PARAM_TEXT, 'the desired plan')
                        )
                    )
            )
        );      
    }
    public static function getmilestoneplan_returns() {
        return new external_single_structure(
                array('data' => new external_value(PARAM_TEXT, 'Server respons to the incomming log'))
        );
    }
    
    public static function getmilestoneplan($param){
        global $CFG, $DB, $USER;        
        $transaction = $DB->start_delegated_transaction(); 
        $params = array();
        $params[] = (int)$param['courseid'];
        $params[] = $param['plan'];
        $sql='
            SELECT milestones 
            FROM '.$CFG->prefix.'ladtopics_milestone_plans AS t
            WHERE   
                t.course = ? 
                AND t.plan = ?
            ORDER BY t.created DESC
            LIMIT 1
            ;';
        $res = $DB->get_record_sql($sql, $params);
        $transaction->allow_commit();
        return array('data'=> $res->milestones);       
    } 
    public static function getmilestoneplan_is_allowed_from_ajax() { return true; }

    /**
     * Get Milestone Plan
     */

    public static function setmilestoneplan_parameters() {
       return new external_function_parameters(                
            array(
                'data' => 
                    new external_single_structure(
                        array(
                        'courseid' => new external_value(PARAM_INT, 'id of course'),                        
                        'milestones' => new external_value(PARAM_RAW, 'milestones'),
                        'plan' => new external_value(PARAM_TEXT, 'plan')
                    )
                )
            )
        );
    }
    public static function setmilestoneplan_returns() {
        return new external_single_structure(
            array(
                'data' => new external_value(PARAM_RAW, 'data')                           
            )
        );
    }
    
    public static function setmilestoneplan($param) {       
        try{
            global $CFG, $DB;
            $data = array();
            $meta = get_meta($param["courseid"]);            
            if(is_null($meta)) throw new Exception("Keine Meta-Daten erhalten");            
            if($meta->user->loggedin === true && $meta->user->manager === true){
                $date = new DateTime();
                $c = new stdClass();
                $c->course = (int)$meta->course->id;   
                $c->author = (int)$meta->user->id;           
                $c->created = (int)$date->getTimestamp();
                $c->plan = $param['plan'];
                $c->milestones = $param['milestones'];
                $sql = 'SELECT id FROM '.$CFG->prefix.'ladtopics_milestone_plans WHERE course = ? AND plan = ? LIMIT 1';
                $transaction = $DB->start_delegated_transaction();
                $params = array();
                $params[] = (int)$meta->course->id;
                $params[] = strtolower($param['plan']);
                $res = $DB->get_records_sql($sql, $params);
                $transaction->allow_commit();
                $count = count($res);
                if($count !== 0){    
                    $id = reset($res);
                    $c->id = $id->id;
                    $transaction = $DB->start_delegated_transaction();                       
                    $res = $DB->update_record("ladtopics_milestone_plans", $c);     
                    $transaction->allow_commit();
                    if($res === true){
                        $data['success'] = true;                           
                    } else {
                        $data['success'] = false;
                        $data['debug'] = "Unbekannter Fehler.";
                    }                         
                } else {
                    $transaction = $DB->start_delegated_transaction();
                    $res = $DB->insert_records("ladtopics_milestone_plans", array($c));
                    $transaction->allow_commit();
                    $data['success'] = true; 
                }                                
            } else {
                $data['success'] = false;
                $data['debug'] = "Keine Berechtigung";
            }         
            return array('data'=>json_encode($data));            
        } catch (Exception $e){
            return array('data'=>json_encode(array('success' => false, 'debug' => json_encode($e))));
        }       
    } 

    public static function setmilestoneplan_is_allowed_from_ajax() { return true; }




    /**
     * Set and get user preferences
     */
    public static function userpreferences_parameters() {
        return new external_function_parameters(                
            array(
                'data' => 
                    new external_single_structure(
                        array(
                        'courseid' => new external_value(PARAM_INT, 'id of course', VALUE_OPTIONAL),
                        'fieldname' => new external_value(PARAM_TEXT, 'Name of the field'),
                        'setget' => new external_value(PARAM_TEXT, 'Get or Set'),
                        'value' => new external_value(PARAM_TEXT, 'Value of field', VALUE_OPTIONAL)
                    )
                )
            )
        );
    }
    public static function userpreferences_returns() {
        return new external_single_structure(
                array( 'response' => new external_value(PARAM_RAW, 'Server respons to the incomming log') )
        );
    }
    public static function userpreferences($data) {
        global $CFG, $DB, $USER;
        $userid = (int)$USER->id;
        
            $r = new stdClass();
            $r->userid = $userid;
            $r->name = $data['fieldname'] . '-course-' . $data['courseid'];
            $exists = $DB->record_exists('user_preferences', array(
                'name' => $data['fieldname'] . '-course-' . $data['courseid'], 
                'userid'=>$userid
            ));
            $res='nix';
            if($exists != true){
                $r->value=$data['value'] == NULL ? 0 : $data['value'];
                $transaction = $DB->start_delegated_transaction();
                $res = $DB->insert_records("user_preferences", array($r));
                $transaction->allow_commit();    
            } elseif($exists == true && $data['setget'] == 'get'){
                $transaction = $DB->start_delegated_transaction();
                $res = $DB->get_record("user_preferences", array(
                    'name' => $data['fieldname'] . '-course-' . $data['courseid'], 
                    'userid'=>$userid
                ));
                $transaction->allow_commit();
                
            } elseif($exists == true && $data['setget'] == 'set'){
                //$transaction = $DB->start_delegated_transaction();
                //$res = $DB->get_record("user_preferences", array(
                //    'name' => $data['fieldname'] . '-course-' . $data['courseid'], 
                //      'userid'=>$userid));
                //$transaction->allow_commit();
                //$r->id=$res->id;
                //$r->value=$data['value'];
                $transaction = $DB->start_delegated_transaction();
                //$res = $DB->set_record("user_preferences", array($r));
                $res = $DB->set_field("user_preferences", 'value', $data['value'], array(
                    'userid' => $userid,
                    'name' => $data['fieldname'] . '-course-' . $data['courseid']
                ));
                //$sql = 'UPDATE '. $CFG->prefix .'user_preferences SET value=\''. $data['value'] .'\' WHERE name=\'ladtopics_survey_done\' ;';
                //$res = $DB->set_records_sql($sql);
                $transaction->allow_commit();
                
            }     
        

        return array('response'=> json_encode( array($res, $data['setget']) ));
    } 
    public static function userpreferences_is_allowed_from_ajax() { return true; }

}// end class



?>