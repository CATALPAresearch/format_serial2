<template>
    <div>
        <!-- Questionnaire banner 
        <div hidden v-if="surveyRequired && courseid == aple1801" style="margin:0;">
            <div style="margin: 10px 0;" class="row">
                <span class="col-4 d-flex" style="font-weight:bold; font-size:1.2em; color:#000;">
                    Helfen Sie uns und tragen Sie dazu bei die Lernangeboten besser auf Ihre Bedürfnisse anzupassen:
                </span>
                <a style="border-radius:10px; font-weight:bold; color:#fff !important;" class="btn btn-primary btn-lg"
                    src="https://aple.fernuni-hagen.de/mod/questionnaire/view.php?id=1659">
                    Jetzt an der Befragung teilnehmen!
                </a>
            </div>
        </div>
        -->
        <!-- Questionnaire modal -->
        <div v-if="surveyRequired && aple1801.includes(courseid)" 
            class="modal fade" 
            id="questionnaireModal" 
            data-keyboard="false" 
            data-backdrop="static" 
            tabindex="-1" role="dialog" aria-labelledby="questionnaireModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-body">
                    <span class="pt-4 pb-4 col-12 d-flex" style="font-weight:bold; font-size:2.1em; color:#333;">
                    Bitte nehmen Sie sich 10 Minuten Zeit für unsere Befragung, damit wir das Lernangebot besser an Ihre Bedürfnisse anpassen können. <br><br>Vielen Dank! 
                    </span>
                </div>
                <div class="modal-footer text-center">
                    <a style="border-radius:10px; font-weight:bold; color:#fff !important;" class="btn btn-primary btn-lg"
                        href="https://aple.fernuni-hagen.de/mod/questionnaire/complete.php?id=1659">
                        Jetzt an der Befragung teilnehmen!
                    </a>
                </div>
                </div>
            </div>
        </div>

        <h3 class="mb-3">Semesterübersicht</h3>
        
        <div class="row mb-3 form-group">
            <div class="col-8">
                <label for="select-goal" style="font-size:11px;">Mein Ziel ist es </label>
                <div style="background-color:#e9f5f9; color:#333; display:inline-block; width:140px; height:20px;">
                    <select id="select-goal" @change="switchGoal($event)"
                        class="pt-1 pb-1 fa-caret-down lad-select mr-0" aria-label=".form-select-sm example"
                        style="display:inline-block; color:#333; border:none;width:128px;height:20px;font-weigth:bold;font-size:11px;">
                        <option :selected="currentGoal=='mastery'" value="mastery">den Kurs zu meistern</option>
                        <option :selected="currentGoal=='passing'" value="passing">den Kurs zu bestehen</option>
                        <option :selected="currentGoal=='overview'" value="overview">einen Überblick zu bekommen</option>
                    </select>
                    <i class="fa fa-caret fa-caret-down mr-0"
                        style="color:#333; padding-right:2px; font:FontAwesome; height:20px; text-rendering: auto;-moz-osx-font-smoothing: grayscale;"></i>
                </div>
            </div>
        </div>
        
        <!-- -->
        <div class="dashboard" style="display:block;">
            <div v-if="$store.state.policyAccepted" class="col-md-12">
                <ul class="nav nav-tabs dashboard-tab">
                    <li class="nav-item active">
                        <router-link to="/" class="nav-link" v-on:click="log('dashboard_overview_open',0)">Übersicht</router-link>
                        <a hidden class="nav-link active" v-on:click="log('dashboard_overview_open',0)" data-toggle="tab" href="#learningoverview" role="tab">Übersicht</a>
                    </li>
                    <li class="nav-item">
                        <router-link to="/completion" class="nav-link" v-on:click="log('dashboard_completion_open',0)">Genutzte Lernangebote</router-link>
                        <a hidden class="nav-link" v-on:click="log('dashboard_completion_open',0)" data-toggle="tab" href="#learningstatus" role="tab">Genutzte Lernangebote</a>
                    </li>
                    <li class="nav-item">
                        <router-link to="/strategies" class="nav-link" v-on:click="log('dashboard_strategy_open',0)">Lernstrategien</router-link>
                        <a hidden class="nav-link" v-on:click="log('dashboard_stratgy_open',0)" data-toggle="tab" href="#learningstrategy" role="tab">Lernstrategien</a>
                    </li>

                    <li hidden class="nav-item ">
                        <a class="nav-link" v-on:click="log('dashboard_time-management_open',0)" data-toggle="tab" href="#timemanagement" role="tab">Zeitmanagement</a></li>
                    <li hidden class="nav-item">
                        <a class="nav-link" v-on:click="log('dashboard_strategy_open',0)" data-toggle="tab" href="#strategy" role="tab">Lernen gestalten</a>
                    </li>
                    <li hidden class="nav-item">
                        <a class="nav-link" v-on:click="log('dashboard_communication_open',0)"  data-toggle="tab" href="#communication" role="tab">Kommunikation</a>
                    </li>
                    <li hidden class="nav-item">
                        <a class="nav-link" v-on:click="log('dashboard_assessment_open',0)"  data-toggle="tab" href="#quiz" role="tab">Quiz</a>
                    </li>
                </ul>
                <br>
                <router-view 
                    style="display:block;"
                    v-on:log="log" 
                    v-bind:course="course"
                    v-bind:aple1801="aple1801"
                    v-bind:currentGoal="currentGoal"
                    v-bind:surveyRequired="surveyRequired"
                    v-bind:surveyLink="surveyLink"
                    ></router-view>
            </div>
        </div>
        <hr class="mb-3 mt-3" />
        
        <div v-if="aple1801.includes(courseid)" class="mb-3 mt-3" style="width:100%;height:auto;">
            <video controls="true" style="width:100%;max-height:400px;">
                <source v-if="!controlgroup" src="https://nise81.com/1801-Intro-SS2023_Versuchsgruppe.mp4" type="video/mp4">
                <source v-if="controlgroup" src="https://nise81.com/1801-Intro-SS2023_Kontrollgruppe.mp4" type="video/mp4"></source>
                Leider können wir Ihnen das Begrüßungsvideo nicht zeigen, da Ihr Browser keine Videos unterstützt.
            </video>
        </div>
    </div>
</template>

<style>
ul.dashboard-tab li.nav-item a, ul.dashboard-tab.nav-tabs a  {
    border: none;
    border-bottom: solid 3px #fff;
    
}

ul.dashboard-tab .nav-link:hover {
    border: none;
    border-bottom: solid 3px #008fac;
}

ul.dashboard-tab .nav-link a.active {
    border: none;
    border-bottom: solid 3px #008fac;
}

.single-section .dashboard {
    display:none;
}

.survey-button{
    border-radius:10px; 
    margin: 4px auto; 
    z-index:10440; 
    font-weight:bold; 
    background-color:#e79c63; 
    opacity: 0.94;
    color:#fff !important;
}
.survey-button:hover{
    opacity: 0.9;
}
</style>

<script>

    /*
    <!--<dashboard-overview ref="childDashboardOverview" v-on:log="log" v-bind:course="course"></dashboard-overview>-->
                        <div id="dashboardsectionexclude"></div>
                        <!-- format_options["dashboardsectionexclude"] -->
    <!--<dashboard-completion ref="childDashboardCompletion" v-on:log="log" v-bind:course="course" v-bind:milestones="milestones"></dashboard-completion>-->
    
    <!-- <dashboard-strategy ref="childDashboardStrategy" v-on:log="log" v-bind:course="course" v-bind:milestones="milestones"></dashboard-strategy>
                        -->
    
    */
import Logger from './scripts/logger';
import Communication from './scripts/communication';
import CourseOverview from './components/courseOverview';
import CourseCompletion from './components/courseCompletion';
import LearningStrategy from './components/learningStrategies';

export default {
    data: function () {
        return {
            name: 'LAD topics',
            aple1801: [2, 5, 20, 24, 26, 8],
            courseid: -1,
            context: {},
            logger: null,
            surveyRequired: true,
            surveyLink: '',
            questionnaireid: 1659,
            controlgroup: false,
            currentGoal: 'mastery'
        }
    },
    components: {
        CourseOverview,
        CourseCompletion,
        LearningStrategy
    },
    mounted: function () {
        this.courseid = this.$store.state.courseid;
        // assign user to the control group if their user id is even 
        this.controlgroup = this.$store.state.userid % 2 == 0 ? true : false;
        // do not assign user to the control group if they are not in the course aple1801/24 (operating systems etc.)
        this.controlgroup = this.aple1801.includes(this.$store.state.courseid) ? this.controlgroup : false;
        // do not assign user to the control group if they are accessing the system on localhost
        this.controlgroup = window.location.hostname == 'localhost' ? false : this.controlgroup;
        
        this.context.courseId = this.$store.state.courseid; // TODO
        this.logger = new Logger(this.context.courseId, {
            context: "format_ladtopics",
            outputType: 1,
            url: this.$store.state.url
        });
        this.logger.init();
        if(this.aple1801.includes(this.$store.state.courseid) && this.$store.state.policyAccepted){
            this.prepareSurvey();
        }
        this.getGoal();
        //$('#questionnaireModal').modal('show'); 
    },
    methods: {
        getGoal: async function () {
            const response = await Communication.webservice(
                'get_goal',
                {
                    'data': {
                        'courseid': parseInt(this.$store.getters.getCourseid, 10)
                    }
                }
            );
            if (response.success) {
                let tmp = JSON.parse(JSON.parse(response.data));
                if(tmp && tmp.hasOwnProperty('to')){
                    this.currentGoal = tmp.to;
                    this.$forceUpdate();
                }
            } else {
                if (response.data) {
                    console.log('Faulty response of webservice /get_goal/', response.data);
                } else {
                    console.log('No connection to webservice /get_goal/');
                }
            }
            
        },
        switchGoal: async function (event) {
            const now = new Date();
            const response = await Communication.webservice(
                'logger',
                {
                    'data': {
                        'courseid': parseInt(this.$store.getters.getCourseid, 10),
                        'utc': parseInt(now.getTime(), 10),
                        'action': 'change_goal',
                        'entry': JSON.stringify({ form: this.currentGoal, to: event.target.value })
                    }
                }
            );
            if (response.success) {
                console.log(JSON.parse(response.data));
            } else {
                if (response.data) {
                    console.log('Faulty response of webservice /logger/', response.data);
                } else {
                    console.log('No connection to webservice /logger/');
                }
            }
            this.currentGoal = event.target.value;
            this.$forceUpdate();
        },
        log: function (key, values) {
            var a = this.logger ? this.logger.add(key, values) : null;
        },
        prepareSurvey: async function () {
            // which surveys have been done already
            if(this.$store.getters.getisModerator){
                return;
            }
            const response = await Communication.webservice(
                'get_surveys',
                { 
                    courseid: this.$store.getters.getCourseid,
                    moduleid: this.questionnaireid
                }
            );
            if (response.success) {
                response.data = JSON.parse(response.data);
                console.log("QUESTIONNAIRE: ",response.data, this.$store.getters.getCourseid, this.questionnaireid)
                if(response.data.submitted){
                    console.log('questionnaire submitted at '+response.data.submitted);
                }else if(this.aple1801.includes(this.courseid)){
                    $('#questionnaireModal').modal('show');
                    //$('body').prepend("<a target='new' class='btn btn-lg fixed-top w-50 survey-button' href='https://aple.fernuni-hagen.de/mod/questionnaire/view.php?id="+ this.questionnaireid +"'>Helfen Sie uns das Lernangebot zu verbessern und nehmen Sie an unserer Befragung teil.</a>");
                }
                
            } else {
                if (response.data) {
                    console.log('Faulty response of webservice /get_surveys/', response.data);
                } else {
                    console.log('No connection to webservice /get_surveys/');
                }
            }
        },
    },
    computed: {
        alertType: function () {
            return this.$store.getters.getAlertType;
        },
        showAlert: function () {
            return this.$store.getters.getAlertState;
        },
        alertMessage: function () {
            return this.$store.getters.getAlertMessage;
        }
    }
}
</script>