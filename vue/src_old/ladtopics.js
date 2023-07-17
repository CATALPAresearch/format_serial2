/* eslint-disable spaced-comment */
/* eslint-disable require-jsdoc */
/**
 * Main method of the plugin. Load depending javascript and css before starting the timeline dashboard.
 *
 * @module     format/serial2
 * @class      serial2
 * @copyright  2022 Niels Seidel <niels.seidel@fernuni-hagen.de>
 * @license    MIT
 * @since      3.1
 */
define([
    'jquery',
    M.cfg.wwwroot + "/course/format/serial2/lib/build/vue.min.js",
    //M.cfg.wwwroot + "/course/format/serial2/amd/src/DashboardOverview.js",
    M.cfg.wwwroot + "/course/format/serial2/amd/src/DashboardCompletion.js",
    M.cfg.wwwroot + "/course/format/serial2/amd/src/DashboardStrategy.js"
    M.cfg.wwwroot + '/course/format/serial2/amd/src/utils/Utils.js',
    M.cfg.wwwroot + '/course/format/serial2/amd/src/utils/Logging.js'//,
    //M.cfg.wwwroot + '/course/format/serial2/amd/src/utils/ErrorHandler.js'
],
    function ($, Vue, /*DashboardOverview,*/ DashboardCompletion, DashboardStrategy, Utils, Log, /*ErrorHandler*/) {
        console.log(0)
        // hide unused div
        let box = $("#region-main-box");
        const h = box.outerHeight();
        box.change(function () {
            if (box.outerHeight() > h) {
                box.show();
            }
        });
        box.hide();
        console.log(0.44)
        return {
            init: function (courseid) {

                require([], function () {
                    /*ErrorHandler.logger = logger;
                    ErrorHandler.logWindowErrors();
                    ErrorHandler.logConsoleErrors();*/
                    console.log(1)
                    var utils = new Utils();
                    var logger = new Log(courseid, {
                        context: 'format_serial2',
                        outputType: 1 // 0: console, 1: logstore_standard_log
                    });
                    console.log(2)
                    var width = document.getElementById('ladtopic-container-0').offsetWidth;
                    var margins = { top: 15, right: 10, bottom: 20, left: 10 };
                    var course = {
                        courseType: 'Kurs', // or 'Modul'
                        semesterShortName: 'WS 2020/21',
                        id: parseInt($('#courseid').text(), 10),
                        // module: parseInt($('#moduleid').html())
                        startDate: new Date(2020, 8, 24, 0, 0, 0),
                        endDate: new Date(2021, 2, 31, 23, 59, 59),
                        start: (new Date(2020, 8, 24, 0, 0, 0)).getTime() / 1000,
                        end: (new Date(2021, 2, 31, 23, 59, 59)).getTime() / 1000,
                        minimumWeeklyWorkload: 6,
                        maximumWeeklyWorkload: 30,
                        maxPlaningPeriod: 12 // months
                    };
                    console.log(3)
                    utils.get_ws('logstore', {
                        'courseid': parseInt(course.id, 10)
                    }, function (e) {
                        try {
                            //draw(JSON.parse(e.data), logger);
                        } catch (e) {
                            // eslint-disable-next-line no-console
                            console.error(e);
                        }
                    });
                    console.log(4)
                    var milestoneApp = new Vue({
                        el: '#planing-component',
                        components: {
                            //'dashboard-overview': DashboardOverview,
                            'dashboard-completion': DashboardCompletion,
                            'dashboard-strategy': DashboardStrategy
                        },
                        data: function () {
                            return {
                                // <s> datepicker
                                startDate: new Date(),
                                endDate: new Date(),
                                semesterRange: null,
                                dpRange: null,
                                daysOffset: 20,
                                course: course,
                                surveyDone: 0,
                                chart: '',
                                timeFilterChart: '',
                                xAxis: '',
                                yAxis: '',
                                x_axis_call: '',
                                y_axis_call: '',
                                colors: ["#98abc5", "#8a89a6", "#7b6888", "#6b486b", "#a05d56", "#d0743c", "#ff8c00"],
                                bars: '',
                                barwidth: 80,
                                barheight: 21,
                                bardist: 3,
                                maxLanes: 3,
                                width: 730,
                                height: 70,
                                margins: {},
                                padding: 100,
                                xmin: 0,
                                xmax: 0,
                                ymin: 0,
                                ymax: 3,
                                done: [],
                                range: [],
                                milestones: [],
                                calendar: {},
                                freeTextRessource: '',
                                emptyMilestone: {
                                    id: 10,
                                    name: '',
                                    objective: '',
                                    start: '',
                                    end: '',
                                    status: 'progress', // progress, ready, urgent, missed, reflected
                                    progress: 0.0,
                                    resources: [],
                                    reflections: [],
                                    reflectionModified: 0,
                                    yLane: 0,
                                    mod: false,
                                    fromPlan: false,
                                    hide: false
                                },
                                invalidName: false,
                                invalidObjective: false,
                                invalidResources: false,
                                invalidEndDate: false,
                                invalidStartDate: false,
                                invalidReflections: [],
                                selectedDay: 1,
                                selectedMonth: 1,
                                selectedYear: course.startDate.getFullYear(),
                                invalidDay: false,
                                selectedStartDay: 1,
                                selectedStartMonth: 1,
                                selectedStartYear: course.startDate.getFullYear(),
                                invalidEndDay: false,
                                invalidStartDay: false,
                                filterPreset: '',
                                selectedMilestone: 0,
                                modalVisible: false,
                                modalReflectionVisible: false,
                                reflectionsFormVisisble: false,
                                modUsers: [],
                                modStatistics: {
                                    users: 0,
                                    surveys: 0,
                                    milestones: 0,
                                    msProgessed: 0,
                                    msReady: 0,
                                    msUrgent: 0,
                                    msMissed: 0,
                                    msReflected: 0,
                                    ptExam: 0,
                                    ptOrientation: 0,
                                    ptInterest: 0,
                                    ptNoAnswer: 0,
                                    ptW1: 0,
                                    ptW4: 0,
                                    ptWA: 0,
                                    ptWA2: 0,
                                    ptWA4: 0,
                                    ptWANA: 0
                                },
                                resources: []
                            };
                        }
                    });

                    
                });
            }
        };
    });