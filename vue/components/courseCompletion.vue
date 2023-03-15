<style>

.strategy-bookmarked {
    color: #008fac;
}

.strategy-not-bookmarked {
    color: #aaa;
}

.word-wrap {

    /* These are technically the same, but use both */
    overflow-wrap: break-word;
    word-wrap: break-word;

    -ms-word-break: break-all;
    /* This is the dangerous one in WebKit, as it breaks things wherever */
    word-break: break-all;
    /* Instead use this non-standard one: */
    word-break: break-word;

    /* Adds a hyphen where the word breaks, if supported (No Blink) */
    -ms-hyphens: auto;
    -moz-hyphens: auto;
    -webkit-hyphens: auto;
    hyphens: auto;

}

.completion-item:hover {
    background-color: aliceblue !important;
    opacity: 1.0;
}
.completion-item:hover > div.info-box{
    opacity: 1.0 !important;
}

.prompt-box{
    padding: 1px;
    background-color: lightblue;
}

.prompt-box a {
    text-decoration: underline;
    font-weight: bold;
}


</style>

<script>

import Communication from '../scripts/communication';

export default {
    name: 'CourseCompletion',
    props: ['course', 'log', 'surveyRequired', 'surveyLink', 'aple1801', 'currentGoal'],

    data: function () {
        return {
            controlgroup: false,
            color: {
                default: '#7cc0d8',
                orange: '#e79c63',
                green: '#88c2b7',
                yellow: '#e7c87a'
            },
            hideInfo: true,
            sections: [],
            dashboardsectionexclude: [],
            sectionnames: [],
            activities: [],
            info: '',
            current: { id: 0, section: 0 },
            bookmarked: {},
            stats: [],
            sumScores: {
                longpage: { count: 0, complete: 0, achieved_score: 0, max_score: 0 },
                quiz: { count: 0, complete: 0, achieved_score: 0, max_score: 0 },
                assign: { count: 0, complete: 0, achieved_score: 0, max_score: 0 },
                hypervideo: { count: 0, complete: 0, achieved_score: 0, max_score: 0 }
            },
            reflections: [],
            reflectionError: false, 
            currentReflectionSection: 0,
            currentGoal: 'mastery',
            goals: {
                mastery: {
                    hypervideo_completion: { low: 60, med: 85 },
                    hypervideo_score: { low: 60, med: 85 },
                    longpage_completion: { low: 60, med: 85 },
                    longpage_score: { low: 60, med: 85 },
                    assign_completion: { low: 60, med: 85 },
                    assign_score: { low: 60, med: 85 },
                    quiz_completion: { low: 60, med: 85 },
                    quiz_score: { low: 60, med: 85 },

                },
                passing: {
                    hypervideo_completion: { low: 45, med: 75 },
                    hypervideo_score: { low: 45, med: 75 },
                    longpage_completion: { low: 45, med: 75 },
                    longpage_score: { low: 45, med: 75 },
                    assign_completion: { low: 45, med: 75 },
                    assign_score: { low: 45, med: 75 },
                    quiz_completion: { low: 45, med: 75 },
                    quiz_score: { low: 45, med: 75 },

                },
                overview: {
                    hypervideo_completion: { low: 0, med: 100 },
                    hypervideo_score: { low: 0, med: 100 },
                    longpage_completion: { low: 0, med: 100 },
                    longpage_score: { low: 0, med: 100 },
                    assign_completion: { low: 0, med: 100 },
                    assign_score: { low: 0, med: 100 },
                    quiz_completion: { low: 0, med: 100 },
                    quiz_score: { low: 0, med: 100 },
                }
            }
        };
    },

    mounted: function () {
        // assign user to the control group if their user id is even 
        this.controlgroup = this.$store.state.userid % 2 == 0 ? true : false;
        // do not assign user to the control group if they are not in the course 24 (operating systems etc.)
        this.controlgroup = this.aple1801.includes(this.$store.state.courseid) ? this.controlgroup : false;
        // do not assign user to the control group if they are accessing the system on localhost
        this.controlgroup = window.location.hostname == 'localhost' ? false : this.controlgroup;

        this.loadCourseData();
        this.loadReflection();

        if (this.storageAvailable('localStorage')) {
            try {
                if (localStorage.getItem('ladtopics_course_bookmarks') !== null) {
                    this.bookmarked = JSON.parse(localStorage.getItem('ladtopics_course_bookmarks'));
                } else {
                    this.bookmarked = {};
                }
            } catch (e) {
                //new Error(e);
                this.bookmarked = {};
            }
        }
    },

    methods: {
        activityIsBookmarked: function (id) {
            if (this.bookmarked[id] === undefined) {
                this.bookmarked[id] = false;
            }
            return this.bookmarked[id];
        },

        toggleBookmark: function (id) { 
            console.log('hit toggle')
            if (this.bookmarked[id] === undefined) {
                this.bookmarked[id] = false;
            }
            this.bookmarked[id] = !this.bookmarked[id];
            this.$forceUpdate();
            if (this.storageAvailable('localStorage')) {
                localStorage.setItem('ladtopics_course_bookmarks', JSON.stringify(this.bookmarked));
            }
        },
        storageAvailable: function (type) {
            var storage;
            try {
                storage = window[type];
                var x = '__storage_test__';
                storage.setItem(x, x);
                storage.removeItem(x);
                return true;
            }
            catch (e) {
                return e instanceof DOMException && (
                    // everything except Firefox
                    e.code === 22 ||
                    // Firefox
                    e.code === 1014 ||
                    // test name field too, because code might not be present
                    // everything except Firefox
                    e.name === 'QuotaExceededError' ||
                    // Firefox
                    e.name === 'NS_ERROR_DOM_QUOTA_REACHED') &&
                    // acknowledge QuotaExceededError only if there's something already stored
                    (storage && storage.length !== 0);
            }
        },
        
        getLink: function (instance) {
            instance = instance == undefined ? this.getCurrent() : instance;
            return M.cfg.wwwroot + '/mod/' + instance.type + '/view.php?id=' + instance.id;
        },
        getStatus: function (instance) {
            instance = instance == undefined ? this.getCurrent() : instance;
            return instance.completion === 0 ? '<i class="fa fa-times-square"></i>Nicht abgeschlossen' : '<i class="fa fa-check"></i> Abgeschlossen';
        },
        trackClick: function () {
            let instance = this.getCurrent();
            // xxx this.$emit('log', 'dashboard_completion_item_click', { type: instance.type, instance: instance.id });
        },
        /**
         * okd code
        */
        loadCourseData: async function () {
            const response = await Communication.webservice(
                'overview',
                { courseid: this.$store.getters.getCourseid }
            );
            if (response.success) {
                response.data = JSON.parse(response.data)
                console.log('input debug::', JSON.parse(response.data.debug));
                console.log('input completions::', JSON.parse(response.data.completions));

                this.sections = this.groupBy(JSON.parse(response.data.completions), 'section');
                console.log('sections', this.sections)
                console.log('stats', this.calcStats())
                this.stats = this.calcStats();

                //_this.dashboardsectionexclude = $('#dashboardsectionexclude').text().replace(' ','').split(',');
                //_this.dashboardsectionexclude = _this.dashboardsectionexclude.isArray() ? _this.dashboardsectionexclude : [];
                //_this.dashboardsectionexclude = _this.dashboardsectionexclude.map(function(d){ return parseInt(d, 10); });

                //console.log('ss',_this.sections);
                //_this.sections = _this.sections.filter(function(d, index){  
                //    return _this.dashboardsectionexclude.includes(index) == false; 
                //});
                //console.log('ss2',_this.sections);
            } else {
                if (response.data) {
                    console.log('Faulty response of webservice /overview/', response.data);
                } else {
                    console.log('No connection to webservice /overview/');
                }
            }
        },
        groupBy: function (data, key) {
            var arr = [];
            for (var val in data) {
                arr[data[val][key]] = arr[data[val][key]] || [];
                arr[data[val][key]].push(data[val]);
            }
            return arr.filter(function (el) {
                return el !== null;
            });
        },
        trackClick: function () {
            //let instance = this.getCurrent();
            //this.$emit('log', 'dashboard_overview_item_click', { type: instance.type, instance: instance.id });
        },
        calcStats: function () {
            let stats = [];
            for (var j = 0; j < this.sections.length; j++) {
                var section = this.sections[j];
                for (var i = 0; i < section.length; i++) {
                    if (section[i].visible == '0') {
                        continue;
                    }
                    if (stats[section[i].section] == undefined) {
                        stats[section[i].section] = {};
                    }
                    if (stats[section[i].section][section[i].type] == undefined) {
                        this.sectionnames[section[i].section] = section[i].sectionname
                        stats[section[i].section][section[i].type] = {
                            type: section[i].type,
                            count: 0,
                            achieved_score: 0,
                            max_score: 0,
                            complete: 0
                        };
                    }
                    section[i].count = section[i].count == undefined ? 1 : section[i].count;
                    if (section[i].type == "longpage" || section[i].type == "hypervideo") {
                        stats[section[i].section][section[i].type].count += parseInt(section[i].count, 10);
                        stats[section[i].section][section[i].type].complete += section[i].complete != undefined ? parseInt(section[i].complete, 10) : 0;
                    } else {
                        stats[section[i].section][section[i].type].count = section[i].type == "assign" ? parseInt(section[i].count, 10) : stats[section[i].section][section[i].type].count + 1;
                        stats[section[i].section][section[i].type].complete += section[i].submission_time != null ? 1 : 0;

                        stats[section[i].section][section[i].type].achieved_score += section[i].achieved_score != null ? parseInt(section[i].achieved_score, 10) : 0;
                        stats[section[i].section][section[i].type].max_score += section[i].max_score != null ? parseInt(section[i].max_score, 10) : 0;
                    }
                }
            }
            stats = stats.filter(function (n) { return n; });
            this.sectionnames = this.sectionnames.filter(function (n) { return n; });
            //
            let out = [];
            let sum = {
                hypervideo: { count: 0, complete: 0, achieved_score: 0, max_score: 0 },
                longpage: { count: 0, complete: 0, achieved_score: 0, max_score: 0 },
                quiz: { count: 0, complete: 0, achieved_score: 0, max_score: 0 },
                assign: { count: 0, complete: 0, achieved_score: 0, max_score: 0 }
            };
            for (var i = 0; i < stats.length; i++) {
                var el = {
                    sectionname: this.sectionnames[i].replace(':', ':\n'),
                    id: i
                };
                if (stats[i] == null) {
                    continue;
                }
                if (stats[i].hypervideo) {
                    el.hypervideo = {
                        count: stats[i].hypervideo.count,
                        complete: stats[i].hypervideo.complete
                    }
                    sum.hypervideo.count += stats[i].hypervideo.count;
                    sum.hypervideo.complete += isNaN(stats[i].hypervideo.complete) ? stats[i].hypervideo.complete : 0;
                }
                if (stats[i].longpage) {
                    el.longpage = {
                        count: stats[i].longpage.count,
                        complete: stats[i].longpage.complete
                    }
                    sum.longpage.count += stats[i].longpage.count;
                    sum.longpage.complete += stats[i].longpage.complete;
                }
                if (stats[i].quiz) {
                    el.quiz = {
                        count: stats[i].quiz.count,
                        complete: stats[i].quiz.complete,
                        achieved_score: stats[i].quiz.achieved_score,
                        max_score: stats[i].quiz.max_score
                    }
                    sum.quiz.count += stats[i].quiz.count;
                    sum.quiz.complete += stats[i].quiz.complete;
                    sum.quiz.achieved_score += stats[i].quiz.achieved_score;
                    sum.quiz.max_score += stats[i].quiz.max_score;
                }
                if (stats[i].assign) {
                    el.assign = {
                        count: stats[i].assign.count,
                        complete: stats[i].assign.complete,
                        achieved_score: stats[i].assign.achieved_score,
                        max_score: stats[i].assign.max_score
                    };
                    sum.assign.count += stats[i].assign.count;
                    sum.assign.complete += stats[i].assign.complete;
                    sum.assign.achieved_score += stats[i].assign.achieved_score;
                    sum.assign.max_score += stats[i].assign.max_score;
                }

                out.push(el);
            }
            this.sumScores = sum;
            return out;
        },
        getRatio: function (a, b, max = 0) {
            if (parseInt(a) > 0 && parseInt(b) > 0) {
                var ratio = (parseInt(a) / parseInt(b)) * 100;
                return max > 0 && ratio > max ? max : ratio;
            }
            return 0;
        },
        getBarColor: function (type, ratio) {
            if(type == 'page_completion' || type == 'resource_completion' || type == 'forum_completion' || type == 'announcement_completion'){
                return '#cccccc';
            }
            if (this.currentGoal == 'overview') {
                return this.color.default;
            }
            console.log('ratio ',ratio);
            if(this.goals[this.currentGoal][type] == undefined){
                return this.color.yellow;
            } else if (ratio < this.goals[this.currentGoal][type].low) {
                return this.color.orange;
            } else if (ratio > this.goals[this.currentGoal][type].med) {
                return this.color.green;
            } else {
                return this.color.yellow;
            }
        },
        loadReflection: async function () {
            const response = await Communication.webservice(
                'reflectionread',
                { 'courseid': this.$store.getters.getCourseid }
            );
            if (response.success) {
                console.log(JSON.parse(response.data));
                this.reflections = JSON.parse(response.data);
                console.log("Reflection-data ", this.reflections);
            } else {
                if (response.data) {
                    console.log('Faulty response of webservice /reflectionread/', response.data);
                } else {
                    console.log('No connection to webservice /reflectionread/');
                }
            }
        },
        /**
         * Green color – when Reflection task is done.
         * Yellow color – when the time is to reflect – assign_completion 45 (for passing) or 65 (for mastery).
         * Red color – when the time is to reflect, but student starts doing tasks from another unit 
         * – conditions from yellow + assign_completion 20 (for any other unit in which Reflection task is not done).
         * @param {*} section_id 
         */
        getReflectionButtonColor: function (section_id) {
            if (this.reflectionOfSectionDone(section_id)) {
                return this.currentGoal == 'overview' ? this.color.default : this.color.green;
            }
            if (this.sectionMinimumAchived(section_id)) {
                let sum = 0;
                for (let i = section_id + 1; i < this.sections.length; i++) {
                    var res = this.stats.filter(function (d) { return d.id == i })[0];
                    if (res == null) {
                        continue;
                    }
                    sum += res.hasOwnProperty('quiz') ? res.quiz.complete / res.quiz.count * 100 : 0;
                    sum += res.hasOwnProperty('assign') ? res.assign.complete / res.assign.count * 100 : 0;
                    if (sum > 20) {
                        return this.color.orange;
                    }
                }
                return this.color.yellow;
            }
            // default
            return '#ddd';
        },
        sectionMinimumAchived: function (sectionId) {
            return true;
            var res = this.stats.filter(function (d) { return d.id == sectionId })[0];
            var quiz_ratio = res.hasOwnProperty('quiz') ? res.quiz.complete / res.quiz.count * 100 : 0;
            var assign_ratio = res.hasOwnProperty('assign') ? res.assign.complete / res.assign.count * 100 : 0;
            if (this.currentGoal == 'mastery') {
                return quiz_ratio + assign_ratio > 65 ? true : false;
            }
            if (this.currentGoal == 'passing') {
                return quiz_ratio + assign_ratio > 45 ? true : false;
                //return ( quiz_ratio > 10 && assign_ratio > 10 ) || ( quiz_ratio > 30 || assign_ratio > 30 ) ? true : false;
            }
            if (this.currentGoal == 'overview') {
                return quiz_ratio + assign_ratio > 20 ? true : false;
            }

        },
        setCurrentReflectionSection: function (id) {
            this.currentReflectionSection = id;
        },
        getSectionPositionById: function(id){
            let index = 0;
            let res = -1;
            for(var i in this.sections){
                console.log(i.id, id)
                if(this.sections[i].id == id){
                    res = index;
                }
                index++;
            }
            return res;
        },
        getCurrent: function () {
            //return this.currentReflectionSection; // FIXME
            return this.sections[this.current.section][this.current.id];
        },
        setCurrent: function (id, section) {
            this.current = { id: id, section: section };
            this.hideInfo = false;
            // xxx this.$emit('log', 'dashboard_completion_item_hover', { url: this.getLink(), completion: this.getCurrent().completion });
        },
        saveReflection: async function (data) {
            var _this = this;
            this.reflectionError = false;  
            const response = await Communication.webservice(
                'reflectioncreate',
                {
                    data: {
                        'course': this.$store.getters.getCourseid,
                        'section': parseInt(this.currentReflectionSection, 10),
                        'reflection': this.reflection
                    }
                }
            );
            if (response.success) {
                this.loadReflection();
                this.reflection = '';
                this.reflectionError = false;
                $('#refelctionModal').modal('hide');
            } else {
                this.reflectionError = true; 
                if (response.data) {
                    console.log('Faulty response of webservice /reflectionread/', response.data);
                } else {
                    console.log('No connection to webservice /reflectionread/', response.data);
                }
            }
        },
        getNumberOfReflectedSections: function () {
            var _this = this;
            var t = [];
            for (var ref in this.reflections) {
                t.push(this.reflections[ref].section);
            }
            t = [...new Set(t)];
            return t.length;
        },
        reflectionOfSectionDone: function (section) {
            for (var ref in this.reflections) {
                if (parseInt(this.reflections[ref].section, 10) == section) {
                    return true;
                }
            }
            return false;
        },
        shortenText: function(text, size){
            text = text.replace('Einsendeaufgabe', 'EA');
            text = text.replace('Kurseinheit', 'KE');
            if(text.length <= size){
                return text;
            }
            return text.substring(0, size-7) + '...' + text.substring(text.length-4, text.length)
        },
        isPrompted(type, instance){
            /*if(instance=="1" && type == 'quiz'){
                return 'border:solid 2px red; ';
            }*/
            return 'border: 0; ';
        }
    }
}
</script>

<template>
    <div id="dashboard-completion">
        <div class="row mb-3 form-group">
            <div class="col-4 form-group">
                <nav hidden class="nav nav-pills nav-sm flex-column flex-sm-row">
                    <label for="select-timerange">Zeitraum filtern:</label>
                    <div class="flex-sm-fill text-sm-center nav-link active">etzten 24 Stunden</div>
                    <div class="text-sm-center nav-link">7 Tage</div>
                    <div class="flex-sm-fill text-sm-center ">14 Tage</div>
                    <div class="flex-sm-fill text-sm-center nav-link">letzter Monat</div>
                    <div class="flex-sm-fill text-sm-center nav-link">Semester</div>
                </nav>
                <select hidden id="select-timerange" class="form-control w-50 form-control-sm"
                    aria-label=".form-select-sm example" style="margin-left:15px;display:inline-block;">
                    <option selected>letzten 24 Stunden</option>
                    <option value="1">letzten 7 Tage</option>
                    <option value="2">letzten 14 Tage</option>
                    <option value="3">letzter Monat</option>
                    <option value="3">seit Semesterbeginn</option>
                </select>
            </div>
        </div>

        <div id="promptplace"></div>

        <!-- Completion bar -->
        <p hidden class="w-75" style="font-size:0.9em">Anhand dieser Balken können Sie erkennen, welche Lernangebote Sie bereits genutzt haben. Jedes Kästchen steht für ein Lernangebot, welches Sie durch einen Klick aufrufen können.</p>
        <div v-for="(section, sIndex) in sections" class="row" style="margin-bottom:3px;">
            <div class="col-3 word-wrap" style="border: solid #111 0pt; text-align:right;">{{ section[0].sectionname.replace('Kurseinheit ', 'KE') }}</div>
            <div class="col-9">
                <span v-for="(m, index) in section">
                    <!-- 
                        v-bind:href="getLink()"
                          
                       @mouseover="setCurrent(index, sIndex)" 
                       data-toggle="popover"
                        data-trigger="hover"
                        data-bs-trigger="hover"
                        data-placement="top"
                        data-html="true"
                        :data-content="getItemInfo(index, sIndex)"

                        { 
                            "type": "quiz", 
                            "modulename": "Quiz", 
                            "id": "2", 
                            "instance": "1", 
                            "name": "quiz", 
                            "expected": 0, 
                            "section": "0", 
                            "sectionname": "General", 
                            "position": 1, 
                            "url": "http://localhost/moodle/mod/quiz/view.php?id=2", 
                            "context": {}, 
                            "icon": {}, 
                            "available": null, 
                            "completion": 0, 
                            "visible": "1", 
                            "achieved_score": "2.00000", 
                            "max_score": "10.00000", 
                            "count": "1", 
                            "submission_time": "1665750022" 
                        } 
                    -->
                    <div 
                        v-on:click="trackClick()" 
                        v-if="m.visible==1 && m.type !== 'label' && m.type !== 'headline'" 
                        :class="m.complete !=0 ? 'completion-item-' + m.type + '-' + m.instance +' rect-green completion-item btn btn-default' : 'completion-item-' + m.type + '-' + m.instance + 'rect-blue completion-item btn btn-default'" 
                        :style="''+ isPrompted(m.type, m.instance) + ' position:relative; display:inline-block; height:20px; width:20px; color:#222; margin-left:3px; padding:0; background-color:'+ getBarColor(m.type+'_completion', getRatio(m.complete, m.count)) +';'"
                        @mouseover="setCurrent(index, sIndex)" 
                        @mouseout="hideInfo=true" 
                        >
                        <i v-if="activityIsBookmarked(m.id)" v-on:click="toggleBookmark(m.id)" class="fa fa-star strategy-not-bookmarked" style="color:#008fac;" title="Vormerkung aufheben"></i>
                        <span v-if="!activityIsBookmarked(m.id)">&nbsp;</span>
                        <div 
                            class="info-box text-left" 
                            v-show="current.id === index && current.section === sIndex && hideInfo==false"
                            style="position: absolute; bottom: 20px; left: -100px; background-color:#fff; overflow:hidden; border: 1px solid #ccc; display: inline-block; width:220px; height:auto; max-height:180px;"
                            >
                            <div class="p-1">
                                <i hidden v-if="getCurrent().completion === 0" class="fa fa-circle"></i> 
                                <i v-if="getCurrent().completion !== 0" class="fa fa-check"></i>
                                <a v-bind:href="getLink()" v-on:click="trackClick()" class="font-weight-bold">
                                    {{ m.modulename }}: {{ shortenText(m.name, 20) }}
                                </a>
                            </div>
                            <div class="p-1">Veruche: {{ m.complete }}</div>
                            <div v-if="typeof m.achieved_score == 'number' && typeof m.max_score == 'number' && typeof m.max_score !== 0 " class="display:block;">
                                Punkte: {{ Math.round(m.achieved_score * 10 / m.max_score)/10}}
                            </div>
                            <div class="p-1">
                                Vormerken? 
                                <i v-if="!activityIsBookmarked(m.id)" v-on:click="toggleBookmark(m.id)" class="fa fa-star strategy-bookmarked" style="color:#aaa;" title="Lernaktivität vormerken"></i>
                                <i v-if="activityIsBookmarked(m.id)" v-on:click="toggleBookmark(m.id)" class="fa fa-star strategy-not-bookmarked" style="color:#008fac;" title="Vormerkung aufheben"></i>
                            </div>
                            <div :class="'prompt-box prompt ' + m.type + '-' + m.instance"></div>
                        </div>
                    </div>
                    
                </span>
                
                <!-- Reflection task -->
                <span>
                    <div v-if="!controlgroup" class="btn btn-default m-0" :disabled="sectionMinimumAchived(section[0].id) == false"
                        :style="'display:inline-block; height:20px; width:20px; color:#222; padding:0; background-color:' + getReflectionButtonColor(section[0].id) +';' "
                        data-toggle="modal" data-target="#refelctionCompletionModal" @click="setCurrentReflectionSection(section[0].id)">
                        <span class="d-none d-md-block">R</span>
                </div>
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-3"></div>
            <div hidden v-if="this.current.id != 0" class="col-9">
                <a v-bind:href="getLink()" v-on:click="trackClick()">
                    <span v-if="getCurrent().completion === 0">
                        <i class="fa fa-times-rectangle"></i> {{ getCurrent().name }}, nicht abgeschlossen
                    </span>
                    <span v-if="getCurrent().completion !== 0">
                        <i class="fa fa-check"></i> {{ getCurrent().name }}, abgeschlossen
                    </span>
                </a>
            </div>
        </div>

        <!-- MODAL POPUP for REFLECTIONS -->
        <div class="modal fade" id="refelctionCompletionModal" tabindex="-1" role="dialog" aria-labelledby="refelctionModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <form @submit.prevent="saveReflection">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="refelctionModalLabel">Abschlussreflektion zu KE {{ currentReflectionSection }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Schließen">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3 lead">
                                <!-- 
                        This assignment is designed to help you pause for a moment and think about your learning progress in the Devices and Processes unit. 
                        It is important that you answer these questions truthfully so that you can properly direct your learning.
                        -->
                                Diese Aufgabe soll Ihnen helfen einen Moment innezuhalten und über Ihren Lernfortschritt
                                in der Kurseinheit {{ getSectionPositionById(currentReflectionSection) }} nachzudenken.
                                Es ist wichtig, dass Sie diese Aufgaben wahrheitsgemäß beantworten damit Sie Ihr Lernen
                                danach ausrichten können.
                                <br>
                                <ul>
                                    <li>
                                        <!-- 
                            After completing various self-assessment tasks and receiving feedback, are you satisfied with the results? 
                            Did you develop knowledge of the topic consistent with your learning goals? 
                        -->
                                        Sind Sie nach dem Abschluss mehrerer Selfsttest- oder Einsendeaufgaben mit den
                                        Ergebnissen zufrieden?
                                        Konnten Sie Ihr Wissen zum Thema der Kurseinheit im Hinblick auf die Lernziele
                                        ausbauen?
                                    </li>
                                    <li>
                                        <!-- 
                            Is it necessary to re-study Devices and Processes unit? 
                            Is there a need to change the current way of learning and planning, 
                            to better cope with the next learning chapter (allocate more time for 
                            learning, employ different approach, study material with more attention...)?
                        -->
                                        Ist es notwendig, die Kurseinheit noch einmal zu wiederholen?
                                        Ist es erforderlich, die derzeitige Vorgehensweise beim Lernen und Planen zu
                                        ändern, um die anderen Kurseinheiten besser bewältigen zu können
                                        (mehr Zeit für das Lernen einplanen, eine andere Lernstrategie wählen, das
                                        Material mit mehr Aufmerksamkeit studieren...)?
                                    </li>
                                    <li>
                                        <!--
                            Can you name any problem that has hindered your results and knowledge 
                            (lack of time, poor planning, prior knowledge, inability to understand a particular concept...)? 
                            Did you discover any faults in what you had previously believed to be right? Can you overcome it for the next unit?
                        -->
                                        Können Sie ein Problem nennen, das Ihre Lernergebnisse und Ihren Wissenserwerb
                                        beeinträchtigt haben (Zeitmangel, schlechte Planung, Vorwissen, Unfähigkeit, ein
                                        bestimmtes Konzept zu verstehen...)?
                                        Haben Sie Fehler in dem entdeckt, was Sie bisher für richtig hielten? Können Sie
                                        diese bei der nächsten Kurseinheit beheben?
                                    </li>
                                </ul>
                                <!-- Write a short note of no more than 300 words (approximately 100 per question) to this questions and submit it in the box provided below.   -->
                                Schreiben Sie eine kurze Notiz von nicht mehr als 300 Wörtern (ca. 100 Wörter pro Frage)
                                zu diesen Fragen in das folgende Textfeld.
                            </div>
                            <textarea v-model="reflection" class="mt-2"
                                style="width:100%; min-height:150px;font-size:20px;padding:10px;"></textarea>

                            <input type="hidden" name="version" :value="1" />
                            Sec: {{ currentReflectionSection }}
                            <div class="mt-2" v-for="(ref, index) in reflections">
                                <div v-if="ref.section==currentReflectionSection">
                                    <em>Version {{ index }} vom {{ ref.timecreated }}</em><br>
                                    {{ ref.reflection }}
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div v-if="reflectionError" class="alert alert-danger">Entschuldigen Sie, es ist ein Fehler aufgetreten. Wir kümmern uns darum. Haben Sie bitte Geduld.</div>
                            <button type="button" class="btn btn-link mr-4" data-dismiss="modal">Schließen</button>
                            <button type="submit" class="btn btn-primary">Speichern</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

