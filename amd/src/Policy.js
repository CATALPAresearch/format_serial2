/* eslint-disable spaced-comment */
/* eslint-disable require-jsdoc */
/**
 * Main method of the plugin. Load depending javascript and css before starting the timeline dashboard.
 *
 * @module     format/ladtopics
 * @class      LADTopics
 * @copyright  2019 Niels Seidel <niels.seidel@fernuni-hagen.de>
 * @license    MIT
 * @since      3.1
 * 
 * TODO:
 * - temporal limit accaptance to 31.10.2020, window.open("https://example.com", "_blank"); 
 */
define([
    'jquery',
    M.cfg.wwwroot + "/course/format/ladtopics/lib/build/vue.min.js",
    M.cfg.wwwroot + "/course/format/ladtopics/lib/build/moment-with-locales.min.js"
],
    function ($, Vue, moment) {

        require.config({
            enforceDefine: false,
            paths: {
                "moment226": [M.cfg.wwwroot + "/course/format/ladtopics/lib/build/moment-with-locales.min.js"],
            },
            shim: {
                "moment226": {
                    exports: 'moment'
                }
            }
        });

        return {
            init: function (policies, message) {
                return new Vue({

                    el: 'policy-container',

                    data: function () {
                        return {
                            policies: policies,
                            message: message,
                            hasRead:{}
                        }
                    },

                    mounted: function () {
                        
                    },

                    methods:{
                        convertTime: function(utc){
                            return moment.unix(utc).format("DD.MM.YYYY");
                        },
                        getLink: function(p, action){
                            return M.cfg.wwwroot + '/course/format/ladtopics/policy.php?policy='+p.id+'&version='+p.version+'&status='+action;
                        },
                        getPolicyLink: function(version_id){
                            return M.cfg.wwwroot + '/admin/tool/policy/viewall.php#policy-'+version_id;
                        }
                    },

                    template: `
                            <div id="policy-container">
                                <h3 class="my-4">Übersicht der Richtlinien</h3>
                                <div v-if="message != ''" class="alert alert-success w-50">
                                    {{ message }}
                                </div>
                                <div class="row mb-3 border-bottom pb-2" v-for="p in policies">
                                    <div class="col-9">
                                        <i v-if="p.status==1" class="fa fa-check ml-3" style="color:green;"></i>
                                        <i v-if="p.status==0" class="fa fa-times ml-3" style="color:red;"></i>
                                        <a :href="getPolicyLink(p.version)" target="s" class="bold">{{p.name}}</a><br>
                                        <span class="pl-3">Der Version vom {{ convertTime(p.creation) }}</span>
                                        <span v-if="p.status==1">
                                            haben Sie am {{ convertTime(p.acceptance) }} zugestimmt.
                                        </span>
                                        <span v-if="p.status==0">
                                            haben Sie nicht zugestimmt.
                                        </span>
                                    </div>
                                    <div class="col-3">
                                        <span v-if="p.status==1">
                                            <a :href="getLink(p,0)" class="right btn btn-sm btn-outline-primary">Zustimmung widerufen</a>
                                        </span>
                                        <span v-if="p.status==0">
                                            <div class="form-check">
                                                <input v-model="hasRead[p.version]" class="form-check-input" type="checkbox" value="1" id="defaultCheck1">
                                                <label class="form-check-label" style="font-size:0.8em;" for="defaultCheck1">
                                                    Ich habe die Richtlinie gelesen und akzeptiere sie.
                                                </label>
                                            </div>
                                            <a :href="getLink(p,1)" :class="hasRead[p.version] ? 'btn btn-sm btn-primary' : 'disabled btn btn-sm btn-primary'">Akzeptieren</a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        `

                });
            }
        };
    });