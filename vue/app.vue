<template>
    <div>
        <div v-if="surveyRequired" class="alert alert-warning" style="margin:0;">
            Do survey
        </div>
        <div class="dashboard" style="display:block;">
            <div class="col-md-12">
                <ul class="nav nav-tabs dashboard-tab">
                    <li class="nav-item active">
                        <a class="nav-link active" v-on:click="log('dashboard_overview_open',0)" data-toggle="tab" href="#learningoverview" role="tab">Übersicht</a></li>
                    <li hidden class="nav-item">
                        <a class="nav-link" v-on:click="log('dashboard_completion_open',0)" data-toggle="tab" href="#learningstatus" role="tab">Genutzte Lernangebote</a></li>
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
                <div class="tab-content" style="display:block;">
                    <div class="tab-pane fade show active" id="learningoverview" role="tabpanel">
                        <course-overview 
                            ref="childDashboardOverview" 
                            v-on:log="log" 
                            v-bind:course="course"
                            v-bind:surveyRequired="surveyRequired"
                            v-bind:surveyLink="surveyLink"></course-overview>
                    </div> 
                    <div class="tab-pane fade" id="learningstatus" role="tabpanel">
                        {{name}}
                    </div>    
                    <div class="tab-pane fade" id="timemanagement" role="tabpanel">Zeitmanagement</div>
                    <div class="tab-pane fade" id="communication" role="tabpanel">Kommunikation</div>
                    <div class="tab-pane fade" id="strategy" role="tabpanel">
                        
                    </div>
                    <div class="tab-pane fade" id="quiz" role="tabpanel">Quiz</div>
                </div>
            </div>
        </div>
        <hr class="mb-3 mt-3" />
        
        <div v-if="courseid == 24" class="mb-3 mt-3" style="width:100%;height:auto;">
            <video controls="true" style="width:100%;height:100%">
                <source v-if="!controlgroup" src="https://equel.de/videos-eds/1801-Intro-WS2022_23_Versuchsgruppe.mp4" type="video/mp4">
                <source v-if="controlgroup" src="https://equel.de/videos-eds/1801-Intro-WS2022_23_Kontrollgruppe.mp4" type="video/mp4"></source>
                Leider können wir Ihnen das Begrüßungsvideo nicht zeigen, da Ihr Browser keine Videos unterstützt.
            </video>
        </div>
    </div>
</template>

<style>
.single-section .dashboard {
    display:none;
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

export default {
    data: function () {
        return {
            name: 'LAD topics',
            courseid: -1,
            context: {},
            logger: null,
            surveyRequired: false,
            surveyLink: '',
            controlgroup: false,
        }
    },
    components: {
        CourseOverview
    },
    mounted: function () {
        this.courseid = this.$store.state.courseid;
        // assign user to the control group if their user id is even 
        this.controlgroup = this.$store.state.userid % 2 == 0 ? true : false;
        // do not assign user to the control group if they are not in the course 24 (operating systems etc.)
        this.controlgroup = this.$store.state.courseid == 24 ? this.controlgroup : false;
        // do not assign user to the control group if they are accessing the system on localhost
        this.controlgroup = window.location.hostname == 'localhost' ? false : this.controlgroup;
        
        this.context.courseId = this.$store.state.courseid; // TODO
        this.logger = new Logger(this.context.courseId, {
            context: "format_ladtopics",
            outputType: 1,
            url: this.$store.state.url
        });
        this.logger.init();
        this.prepareSurvey();
    },
    methods: {
        log: function (key, values) {
            var a = this.logger ? this.logger.add(key, values) : null;
        },
        prepareSurvey: async function () {
            // which surveys have been done already
            const response = await Communication.webservice(
                'get_surveys',
                { courseid: this.$store.getters.getCourseid }
            );
            if (response.success) {
                
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