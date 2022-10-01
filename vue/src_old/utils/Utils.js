/**
 * Javascript utils for the Moodle videodatabase
 *
 * @module     mod_videodatabase/videodatabase
 * @package    mod_videodatabase
 * @class      Utils
 * @copyright  2019 Niels Seidel, niels.seidel@fernuni-hagen.de
 * @license    MIT
 * @since      3.1
 */



define([
    'jquery',
    'core/ajax',
    M.cfg.wwwroot + '/course/format/ladtopics/amd/src/utils/ErrorHandler.js'
], function ($, ajax, ErrorHandler) {

    
    const Utils = function () {
        this.namex = 'utils';
        
        ErrorHandler.logWindowErrors();
        ErrorHandler.logConsoleErrors();

        this.get_ws = function (ws, params, cb, external) {
            external = external === undefined ? false : external;
            ajax.call([{
                methodname: external ? ws : 'format_ladtopics_' + ws,
                args: params,
                done: function (msg) {
                    if (msg.hasOwnProperty('exception')) {
                        $('#alert')
                            .html('Die Prozedur ' + ws + ' konnte nicht als Webservice geladen werden.<br>')
                            .append(JSON.stringify(msg));
                    } else {
                        cb(msg);
                    }
                },
                fail: function (e) {
                    // console.log(e); // for debuging database stuff we need the console, arn't we?
                    // eslint-disable-next-line no-console 
                    new ErrorHandler(e);

                }
            }]);
        };


        this.mergeObjects = function (obj1, obj2) {
            var obj3 = {};
            for (var attrname in obj1) {
                if (obj1.hasOwnProperty(attrname)) {
                    obj3[attrname] = obj1[attrname];
                }
            }
            for (var attrname in obj2) {
                if (obj2.hasOwnProperty(attrname)) {
                    obj3[attrname] = obj2[attrname];
                }
            }
            return obj3;
        };


    };

    return Utils;
    
});